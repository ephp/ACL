<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ephp\ACLBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class EphpUserManager extends UserManager
{
    protected $container;
    protected $accessClass;
    protected $accessRepository;
    
    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param ObjectManager           $om
     * @param string                  $userClass
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $userClass, $accessClass, $container)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $userClass);

        $this->accessRepository = $om->getRepository($accessClass);

        $metadata = $om->getClassMetadata($accessClass);
        $this->accessClass = $metadata->getName();
        $this->container = $container;
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     * @param Boolean       $andFlush Whether to flush the changes (default true)
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        parent::updateUser($user, $andFlush);
        
        $accessClassName = $this->accessClass;
        try {
            $access = new $accessClassName();
            /* @var $access \Ephp\ACLBundle\Model\BaseAccessLog */
            $access->setUser($user);
            $request = $this->container->get('request');
            /* @var $request \Symfony\Component\HttpFoundation\Request */
            $access->setIp($request->server->get('REMOTE_ADDR'));
            $access->setLoggedAt(new \DateTime());
            $access->setNote(array(
                'user_agent' => $request->server->get('HTTP_USER_AGENT'),
                'accept_language' => $request->server->get('HTTP_ACCEPT_LANGUAGE'),
            ));
            $this->objectManager->persist($access);
            $this->objectManager->flush();
//            \Ephp\UtilityBundle\Utility\Debug::pr($request->server);
            $request->getSession()->set('access.log', $access->getId());
        } catch (\Exception $e) {
        }
    }
}
