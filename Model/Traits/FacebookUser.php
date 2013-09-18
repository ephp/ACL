<?php

namespace Ephp\ACLBundle\Model\Traits;

trait FacebookUser {

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    protected $facebookId;
    
    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    //protected $facebook_id;
 
    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;
    
    
    
    public function getFacebookAccessToken() {
        return $this->facebook_access_token;
    }

    public function setFacebookAccessToken($facebook_access_token) {
        $this->facebook_access_token = $facebook_access_token;
    }

    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
    }

    public function getFacebookId() {
        return $this->facebookId;
    }

    public function setFBData($fbdata) {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->setUsername($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setLastname($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
        if (isset($fbdata['username'])) {
            $this->setNickname($fbdata['username']);
        }
        if (isset($fbdata['gender'])) {
            $this->setGender($fbdata['gender']);
        }
        if (isset($fbdata['birthday'])) {
            $this->setBirthday($fbdata['birthday'], self::FACEBOOK);
        }
        if (isset($fbdata['locale'])) {
            $this->setLocale($fbdata['locale']);
        }
    }
    
    public function serializeFacebook() {
        return $this->facebookId;
    }
    
    public function unserializeFacebook($serialize) {
        $this->facebookId = $serialize;
    }

}