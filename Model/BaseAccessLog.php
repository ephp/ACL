<?php

namespace Ephp\ACLBundle\Model;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseAccessLog {

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15)
     */
    protected $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="cookie", type="array")
     */
    protected $cookie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logged_at", type="datetime")
     */
    protected $logged_at;

    /**
     * @var array
     *
     * @ORM\Column(name="note", type="array")
     */
    protected $note;

    function __construct() {
        $this->cookie = array();
        $this->note = array();
    }

    
    /**
     * Set ip
     *
     * @param string $ip
     * @return BaseAccessLog
     */
    public function setIp($ip) {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set logged_at
     *
     * @param \DateTime $loggedAt
     * @return BaseAccessLog
     */
    public function setLoggedAt($loggedAt) {
        $this->logged_at = $loggedAt;

        return $this;
    }

    /**
     * Get logged_at
     *
     * @return \DateTime 
     */
    public function getLoggedAt() {
        return $this->logged_at;
    }

    /**
     * Set note
     *
     * @param array $note
     * @return BaseAccessLog
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Set note
     *
     * @param array $note
     * @return BaseAccessLog
     */
    public function addNote($key, $note = null) {
        if($note) {
            $this->note[$key] = $note;
        } else {
            $this->note[] = $key;
        }
        return $this;
    }

    /**
     * Get note
     *
     * @return array 
     */
    public function getNote() {
        return $this->note;
    }
    
    /**
     * Set cookie
     *
     * @param array $cookie
     * @return BaseAccessLog
     */
    public function setCookie($cookie) {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * Add cookie
     *
     * @param array $cookie
     * @return BaseAccessLog
     */
    public function addCookie($key, $cookie = null) {
        if($cookie) {
            $this->cookie[$key] = $cookie;
        } else {
            $this->cookie[] = $key;
        }
        return $this;
    }

    /**
     * Get note
     *
     * @return array 
     */
    public function getCookie() {
        return $this->cookie;
    }

    abstract public function getId();
    
    abstract public function getUser();

    abstract public function setUser($user);
}
