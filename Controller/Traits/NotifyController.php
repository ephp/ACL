<?php

namespace Ephp\ACLBundle\Controller\Traits;

trait NotifyController {

    /**
     * 
     * @param \Ephp\ACLBundle\Model\BaseUser $user
     * @param string $title
     * @param string $twig
     * @param array $params
     */
    private function notify(\Ephp\ACLBundle\Model\BaseUser $user, $title, $twig, $params = array()) {
        $params['user'] = $user;
        $message = \Swift_Message::newInstance()
                ->setSubject($title)
                ->setFrom($this->container->getParameter('email_robot'))
                ->setTo(trim($user->getEmail()))
                ->setBody($this->renderView("{$twig}.txt.twig", $params))
                ->addPart($this->renderView("{$twig}.html.twig", $params), 'text/html');
        ;
        $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
        $this->get('mailer')->send($message);
    }

}