<?php

/**
 * Riferimenti
 * 
 * https://gist.github.com/danvbe/4476697
 * https://github.com/hwi/HWIOAuthBundle
 */

namespace Ephp\ACLBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class EphpUserProvider extends BaseClass implements \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface {

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var array
     */
    protected $properties;
    protected $em;
    private $router;
    
    

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager FOSUB user provider.
     * @param array                $properties  Property mapping.
     */
    public function __construct(\FOS\UserBundle\Model\UserManagerInterface $userManager, array $properties, $em, RouterInterface $router) {
        $this->userManager = $userManager;
        $this->properties = $properties; //parametri facebook e twitter
        $this->em = $em;
        $this->router = $router;
        
        //$user = $this->getUser();

        
    }
    
    /**
     * Do the magic.
     * 
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $pippo = "";
//        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
//            // user has just logged in
//            $pippo = 1;
//        }
//
//        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//            // user has logged in using remember_me cookie
//            $pippo = 1;
//        }
//
//        // do some other magic here
//        $user = $event->getAuthenticationToken()->getUser();
//
//        if ($user->getProfilo()) { //se uno si è registrato ma ha chiuso subito
//            switch ($user->getProfilo()->getFaseRegistrazione()) {
//                case 0:
//                    $url = $this->router->generate('configurazione_profilo');
//                    //$event->setResponse(new RedirectResponse($url));
//                    break;
//                case 10:
//                    $url = $this->router->generate('upload_foto_configurazione');
//                    //$event->setResponse(new RedirectResponse($url));
//                    break;
//                case 100:
//                    $url = $this->router->generate('wall');
//                    //$event->setResponse(new RedirectResponse($url));
//                    break;
//            }
//        } else {
//            $url = $this->router->generate('configurazione_profilo');
//        }
//
//        return new \Symfony\Component\HttpFoundation\RedirectResponse($url);

        // ...
    }

    /**
     * {@inheritDoc}
     */
    public function connect($user, UserResponseInterface $response) {
        $property = $this->getProperty($response);
        $username = $response->getUsername();


        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    public function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach ($params as $key => $value) {
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    public function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach ($oauth as $key => $value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    private function getEm() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        //when the user is registrating
        if (null === $user) {

            //$response->getResponse() => ARRAY CON MOLTI CAMPI

            $service = $response->getResourceOwner()->getName();
            $setter = 'set' . ucfirst($service);
            $setter_id = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data

            $user->setEmail($username);

            switch ($service) {
                case 'twitter':
                    $user->setUsername($response->getResponse()["screen_name"]);
                    $user->setNickname("TW_".$response->getResponse()["screen_name"].uniqid());
                    $user->setLocale($response->getResponse()["lang"]);
                    $user->setAvatar($response->getResponse()["profile_image_url_https"]);
                    break;
                case 'facebook':
                    $user->setFirstName($response->getResponse()["first_name"]);
                    $user->setLastName($response->getResponse()["last_name"]);
                    $user->setNickname("FB_".$response->getResponse()["username"].uniqid());
                    $user->setUsername($response->getResponse()["name"]);
                    $user->setGender($response->getResponse()["gender"]);
                    $user->setBirthday($response->getResponse()["birthday"], \Ephp\ACLBundle\Entity\User::FACEBOOK);
                    $user->setEmail($response->getEmail());
                    $user->setAvatar($response->getProfilePicture());
                    $user->setLocale($response->getResponse()["locale"]);
                    break;
                    $user->setUsername($username);
                default:
            }
            
            


//                $utente = $this->returnTweet($username); ALTRO METODO PER PRENDERE I DATI, PER ORA COMMENTO

            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);

            /**
             * Creazione Profilo
             */
//            $this->em->beginTransaction();
//            $profilo = new \SN\ProfiloBundle\Entity\Profilo();
//            $profilo->setSlug($user->getNickname());
//            $profilo->setNickname($user->getNickname());
//            $profilo->setUtente($user);
//            $this->em->persist($profilo);
//            $this->em->flush();
//            $this->em->commit();

            return $user;
        }

        switch ($response->getResourceOwner()->getName()) {
            case 'twitter':
                $user->setUsername($response->getResponse()["screen_name"]);
                $user->setLocale($response->getResponse()["lang"]);
                $user->setAvatar($response->getResponse()["profile_image_url_https"]);
                break;
            case 'facebook':
                $user->setFirstName($response->getResponse()["first_name"]);
                $user->setLastName($response->getResponse()["last_name"]);
                //$user->setNickname($response->getResponse()["username"]);
                $user->setUsername($response->getResponse()["name"]);
                $user->setGender($response->getResponse()["gender"]);
                //$user->setBirthday($response->getResponse()["birthday"]);
                //$user->setEmail($response->getEmail());
                $user->setAvatar($response->getProfilePicture());
                $user->setLocale($response->getResponse()["locale"]);
                break;
                $user->setUsername($username);
            default:
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }

    public function onAuthenticationSuccess(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token) {
        $url = $this->router->generate('index');

        return new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    }

}