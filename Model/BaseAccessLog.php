<?php

namespace Ephp\ACLBundle\Model;

use Doctrine\ORM\Mapping as ORM;

class BaseAccessLog
{

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logged_at", type="datetime")
     */
    private $logged_at;

    /**
     * @var array
     *
     * @ORM\Column(name="note", type="array")
     */
    private $note;

    /**
     * Set ip
     *
     * @param string $ip
     * @return BaseAccessLog
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set logged_at
     *
     * @param \DateTime $loggedAt
     * @return BaseAccessLog
     */
    public function setLoggedAt($loggedAt)
    {
        $this->logged_at = $loggedAt;
    
        return $this;
    }

    /**
     * Get logged_at
     *
     * @return \DateTime 
     */
    public function getLoggedAt()
    {
        return $this->logged_at;
    }

    /**
     * Set note
     *
     * @param array $note
     * @return BaseAccessLog
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return array 
     */
    public function getNote()
    {
        return $this->note;
    }
}
