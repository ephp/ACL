<?php

/*
 * This file is part of the prestaSitemapPlugin package.
 * (c) David Epely <depely@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ephp\ACLBundle\Listener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent ;

/**
 * Generator Manager service
 * 
 * @author David Epely <depely@prestaconcept.net>
 * @author Christophe Dolivet
 */
class Permission {

    /**
     * @var Router
     */
    private $router;

    /**
     * @var \appDevDebugProjectContainer 
     */
    private $container;

    /**
     * @var GetResponseEvent 
     */
    private $event = null;

    /**
     * @var Request 
     */
    private $request = null;

    /**
     * @var SecurityContextInterface 
     */
    private $sc = null;

    /**
     * @var \Ephp\ACLBundle\Model\BaseUser 
     */
    private $user;

    public function __construct($router, $container, SecurityContextInterface $sc) {
        $this->router = $router;
        $this->container = $container;
        $this->sc = $sc;
        if ($this->sc) {
            if (null !== $token = $this->sc->getToken()) {
                if (is_object($user = $token->getUser())) {
                    $this->user = $user;
                }
            }
        }
    }

    public function onKernelRequest(FilterControllerEvent $event) {
        $this->event = $event;
        $this->request = $event->getRequest();

        $rc = $this->router->getRouteCollection();
        /* @var $rc \Symfony\Component\Routing\RouteCollection */

        $route = $rc->get($this->request->get('_route'));
        if (!$route)
            return false;
        $acl = $route->getOption('ACL');
        try {
            // Verifico che sia stata richiesta la memorizzazione delle statistiche
            if ($acl && is_array($acl)) {
                if (!is_object($this->user)) {
                    throw new \Exception('User not logged');
                }
                // Opzioni default in caso di assenza
                $options = array_merge(array(
                    'in_role' => array(),
                    'out_role' => array(),
                        ), $acl);
                // Trasformo i parametri in un array
                if (!is_array($options['in_role'])) {
                    $options['in_role'] = array($options['in_role']);
                }
                if (!is_array($options['out_role'])) {
                    $options['out_role'] = array($options['out_role']);
                }

                // Verifico che l'utente abbia il ruolo necessario per visualizzare la pagina
                $test_in = count($options['in_role']) == 0;
                foreach ($options['in_role'] as $role) {
                    $test_in |= $this->user->hasRole($role);
                }
                if(!$test_in) {
                    throw new \Exception("User doesn't have permission");
                }
                $test_out = true;
                foreach ($options['out_role'] as $role) {
                    $test_out &=!$this->user->hasRole($role);
                }
                if(!$test_out) {
                    throw new \Exception("User doesn't have permission");
                }
            }
        } catch (\Exception $e) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($e->getMessage());
        }
    }

}
