<?php

namespace Ephp\ACLBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ephp\UtilityBundle\Utility\String;

/**
 * InvitoFacebook
 *
 * @ORM\Table(name="ephp_inviti_facebook")
 * @ORM\Entity(repositoryClass="Ephp\ACLBundle\Entity\InvitoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class InvitoFacebook extends Invito
{

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="integer")
     */
    private $facebook_id;

    /**
     * Set facebook_id
     *
     * @param string $facebook_id
     * @return Invito
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
    
        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }
    
    protected function generaCodice(\DateTime $now) {
        return 'FB-'.String::ep8(array($now, 'invito via facebook', $this->getUser()));
    }
    
    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        parent::prePersist();
        $this->setNotificato(true);
    }

}
