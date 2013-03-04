<?php

namespace Ephp\ACLBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ephp\ACLBundle\Entity\BaseAccessLog;

/**
 * BaseAccessLog
 *
 * @ORM\Table('ephp_access_log')
 * @ORM\Entity(repositoryClass="Ephp\ACLBundle\Entity\AccessLogRepository")
 */
class AccessLog
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
    private $user;


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
     * Set user
     *
     * @param string $user
     * @return BaseAccessLog
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

}
