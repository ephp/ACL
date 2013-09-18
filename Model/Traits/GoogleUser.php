<?php

namespace Ephp\ACLBundle\Model\Traits;

trait GoogleUser {

    /** 
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true) 
     */
    protected $googleId;
 
    /** 
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $google_access_token;

    public function getGoogleId() {
        return $this->googleId;
    }

    public function setGoogleId($googleId) {
        $this->googleId = $googleId;
    }

    public function getGoogleAccessToken() {
        return $this->google_access_token;
    }

    public function setGoogleAccessToken($google_access_token) {
        $this->google_access_token = $google_access_token;
    }

    public function serializeGoogle() {
        return $this->googleId;
    }

    public function unserializeGoogle($serialize) {
        $this->googleId = $serialize;
    }

}