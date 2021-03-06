<?php

namespace Ephp\ACLBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Ephp\ACLBundle\Entity\User;
use Ephp\ACLBundle\Utility\Facebook;

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
        $user = $this->getUser();
        /* @var $user \Ephp\ACLBundle\Model\BaseUser */
        $nofb = is_null($user) || is_null($user->getFacebookId());
        $status = 'anonimous';
        if(isset($fb['checkLogin'])) {
            if($nofb) {
                $nofb = false;
            }
        }
        if ($nofb) {
            $_user = $em->getRepository($this->container->getParameter('ephp_acl.user.class'));
            $fbuser = $_user->findOneBy(array('facebookId' => $fb['userID']));
            /* @var $fbuser \Ephp\ACLBundle\Model\BaseUser */
            if($user && $fbuser && $user->getId() != $fbuser->getId()) {
                throw new Exception('Facebook user already registred');
            }
            $facebook = $this->get('fos_facebook.api');
            /* @var $facebook \Facebook */
            $user_id = $facebook->getUser();
            if ($user_id) {
                try {
                    $me = $facebook->api('/me', 'GET');
                    if (!$user) {
                        $user_class = $this->container->getParameter('ephp_acl.user.namespace');
                        $user = new $user_class();
                    }
                    $user->setFBData($me);
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
            if ($user) {
                $status = 'login';
            }
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
        $fbids = Facebook::retriveFbId($friends);
        $_user = $em->getRepository('EphpACLBundle:User');
        $reg_friends = $_user->findBy(array('facebookId' => $fbids));
        $out = Facebook::registredAndFriends($friends, $reg_friends);
        Facebook::sortByName($out['registred']);
        Facebook::sortByName($out['unregistred']);
        return new Response(json_encode($out));
    }

    /**
     * @Route("/pictures", name="fb_picture", defaults={"_format"="json"})
     */
    public function fbPicturesAction() {
        $em = $this->getEm();
        $facebook = $this->get('fos_facebook.api');
        /* @var $facebook \Facebook */
        try {
            $pictures = $facebook->api('/me/photos');
        } catch (FacebookApiException $e) {
            return $this->redirect($this->generateUrl('home'));
        }
        return new Response(json_encode($pictures['data']));
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