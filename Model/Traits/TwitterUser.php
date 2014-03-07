<?php

namespace Ephp\ACLBundle\Model\Traits;

trait TwitterUser {

    /**
     * @var string
     * 
     * @ORM\Column(name="twitterId", type="string", length=255, nullable=true)
     */
    protected $twitterId;

    /**
     * @var string
     * 
     * @ORM\Column(name="twitter_id", type="string", length=255, nullable=true)
     */
    protected $twitter_id;

    /**
     * @var string
     * 
     * @ORM\Column(name="twitter_access_token", type="string", length=255, nullable=true)
     */
    protected $twitter_access_token;
    
    
    public function getTwitterId() {
        return $this->twitterId;
    }

    public function setTwitterId($twitterId) {
        $this->twitterId = $twitterId;
        $this->twitter_id = $twitterId;
        return $this;
    }
    
    public function getTwitter_id() {
        return $this->twitter_id;
    }

    public function setTwitter_id($twitter_id) {
        $this->twitterId = $twitterId;
        $this->twitter_id = $twitter_id;
        return $this;
    }

    public function getTwitterAccessToken() {
        return $this->twitter_access_token;
    }

    public function setTwitterAccessToken($twitter_access_token) {
        $this->twitter_access_token = $twitter_access_token;
        return $this;
    }

    public function serializeTwitter() {
        return $this->twitterId;
    }

    public function unserializeTwitter($serialize) {
        $this->twitterId = $serialize;
    }

}