<?php

namespace Ephp\ACLBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ephp\UtilityBundle\Utility\String;

/**
 * InvitoEmail
 *
 * @ORM\Table(name="ephp_inviti_email")
 * @ORM\Entity(repositoryClass="Ephp\ACLBundle\Entity\InvitoEmailRepository")
 * @ORM\HasLifecycleCallbacks
 */
class InvitoEmail extends Invito
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $email;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Invito
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    protected function generaCodice(\DateTime $now) {
        return 'EM-'.String::ep8(array($now, 'invito via email', $this->getUser()));
    }
    
}
