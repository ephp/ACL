<?php

namespace Ephp\Bundle\ACLBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Ephp\Bundle\ACLBundle\Entity\User;

/**
 * @Route("/fb")
 */
class FacebookController extends Controller {

    /**
     * @Route("/registration", name="fb_reg", defaults={"_format"="json"})
     */
    public function fbRegAction() {
        $em = $this->getEm();
        $req = $this->getRequest();
        $fb = $req->get('fb');
        $_user = $em->getRepository('EphpACLBundle:User');
        $user = $_user->findOneBy(array('facebookId' => $fb['userID']));
        $status = 'unknow';
        if (!$user) {
            $facebook = $this->get('fos_facebook.api');
            /* @var $facebook \Facebook */
            $user_id = $facebook->getUser();
            if ($user_id) {
                try {
                    $me = $facebook->api('/me', 'GET');
                    $user = new User();
                    $user->setFacebookId($me['id']);
                    $user->setUsername($me['id']);
                    $user->setFirstname($me['first_name']);
                    $user->setLastname($me['last_name']);
                    $user->setNickname($me['username']);
                    $user->setGender($me['gender']);
                    $user->setLocale($me['locale']);
                    $user->setEmail($me['email']);
                    $user->setEnabled(true);
                    $user->setPlainPassword('facebook-connect');
                    $em->beginTransaction();
                    $em->persist($user);
                    $em->flush();
                    $em->commit();
                    $status = 'registration';
                } catch (FacebookApiException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    $em->rollback();
                    throw $e;
                }
            }
        } else {
            $status = 'login';
        }
        if ($user) {
            $token = new UsernamePasswordToken(
                    $user, null, 'secured_area', $user->getRoles()
            );
            $this->container->get('security.context')->setToken($token);
            $this->getRequest()->getSession()->set('user', $user->serialize());
        }
        return new Response(json_encode(array('status' => $status)));
    }

    /**
     * @Route("/invite", name="fb_invite", defaults={"_format"="json"})
     */
    public function fbInviteAction() {
        $em = $this->getEm();
        $req = $this->getRequest();
        $fb = $req->get('fb');
        $facebook = $this->get('fos_facebook.api');
        /* @var $facebook \Facebook */
        try {
            $out = $facebook->api("/me/feed", array('to' => $fb, 'message' => "Piccolo Cinema Indipendnete", 'link' => "http://www.piccolocinema.it/", 'description' => "Sito in aggiornamento"));
        } catch (FacebookApiException $e) {
            return $this->redirect($this->generateUrl('home'));
        }
        return new Response(json_encode($out));
    }

    /**
     * @Route("/friends", name="fb_friends", defaults={"_format"="json"})
     */
    public function fbFriendsAction() {
        $em = $this->getEm();
        $facebook = $this->get('fos_facebook.api');
        /* @var $facebook \Facebook */
        try {
            $friends = $facebook->api('/me/friends');
        } catch (FacebookApiException $e) {
            return $this->redirect($this->generateUrl('home'));
        }
        $friends = $friends['data'];
        $fbids = $this->retriveFbId($friends);
        $_user = $em->getRepository('EphpACLBundle:User');
        $reg_friends = $_user->findBy(array('facebookId' => $fbids));
        $out = $this->registredAndFriends($friends, $reg_friends);
        usort($out['registred'], array($this, 'sortByName'));
        usort($out['unregistred'], array($this, 'sortByName'));
        return new Response(json_encode($out));
    }

    /**
     * @Route("/script.html", name="fb_html", defaults={"_format"="html"})
     * @Template()
     */
    public function fbHtmlAction() {
        return array();
    }

    /**
     * @Route("/script.js", name="fb_js", defaults={"_format"="js"})
     * @Template()
     */
    public function fbJsAction() {
        return array(
            'app_id'   => $this->container->getParameter('ephp_acl.facebook.app_id'),
            'app_name' => $this->container->getParameter('ephp_acl.facebook.app_name'),
            'app_url'  => $this->container->getParameter('ephp_acl.facebook.app_url'),
            );
    }

    protected function registredAndFriends($friends, $reg_friends) {
        $out = array('registred' => array());
        foreach ($reg_friends as $reg_friend) {
            /* @var $reg_friend User */
            foreach ($friends as $id => $friend) {
                if ($friend['id'] == $reg_friend->getFacebookId()) {
                    $friend['slug'] = $reg_friend->getEmail();
                    $out['registred'][] = $friend;
                    unset($friends[$id]);
                }
            }
        }
        $out['unregistred'] = $friends;
        return $out;
    }

    protected function retriveFbId($friends) {
        $fbids = array();
        foreach ($friends as $friend) {
            $fbids[] = $friend['id'];
        }
        return $fbids;
    }

    protected function sortByName($a, $b) {
        return strcmp($a['name'], $b['name']);
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEm() {
        return $this->getDoctrine()->getEntityManager();
    }

}

/*
Array
(
    [id] => 1567137771
    [name] => Ephraim Pepe
    [first_name] => Ephraim
    [last_name] => Pepe
    [link] => http://www.facebook.com/Munkeph
    [username] => Munkeph
    [birthday] => 02/17/1976
    [location] => Array
        (
            [id] => 111995875492561
            [name] => Livorno, Italy
        )

    [work] => Array
        (
            [0] => Array
                (
                    [employer] => Array
                        (
                            [id] => 271423612925957
                            [name] => EcoSeekr Italia Srl
                        )

                    [location] => Array
                        (
                            [id] => 105016109536094
                            [name] => Livorn, Toscana, Italy
                        )

                    [position] => Array
                        (
                            [id] => 264497870265862
                            [name] => Senior Web Analyst and Developer
                        )

                    [description] => Social Business Community in Ambiente * Energia * Rifiuti
                    [start_date] => 2012-01
                )

            [1] => Array
                (
                    [employer] => Array
                        (
                            [id] => 105002926206548
                            [name] => Schema31
                        )

                    [description] => Web developer
                    [start_date] => 2009-10
                    [end_date] => 2011-12
                )

            [2] => Array
                (
                    [employer] => Array
                        (
                            [id] => 108933569187838
                            [name] => Centro Artistico Il Grattacielo
                        )

                )

        )

    [education] => Array
        (
            [0] => Array
                (
                    [school] => Array
                        (
                            [id] => 168029866551433
                            [name] => Liceo Scientifico Francesco Cecioni
                        )

                    [year] => Array
                        (
                            [id] => 135676686463386
                            [name] => 1994
                        )

                    [type] => High School
                )

            [1] => Array
                (
                    [school] => Array
                        (
                            [id] => 110935008926894
                            [name] => University of Pisa
                        )

                    [type] => College
                )

            [2] => Array
                (
                    [school] => Array
                        (
                            [id] => 112629142084402
                            [name] => University of Pisa
                        )

                    [type] => Graduate School
                )

        )

    [gender] => male
    [email] => munkeph@gmail.com
    [timezone] => 1
    [locale] => it_IT
    [verified] => 1
    [updated_time] => 2013-02-19T14:47:28+0000
)
 */