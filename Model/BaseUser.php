<?php

namespace Ephp\ACLBundle\Model;

use FOS\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Ephp\UtilityBundle\Interfaces\EP8;

abstract class BaseUser extends User implements EP8 {

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255, nullable=true, unique=true)
     */
    protected $nickname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=16, nullable=true)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=8, nullable=true)
     */
    protected $locale;

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
    
    
    /**
     * @var string
     * 
     * @ORM\Column(name="email_nuova", type="string", length=128, nullable=true)
     */
    protected $email_nuova;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="email_nuova_token", type="string", length=128, nullable=true)
     */
    protected $email_nuova_token;
    
    
    
    /**
     * @var string
     * 
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    protected $avatar;
    
    /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
    protected $google_id;
 
    /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
    protected $google_access_token;
    
    public function getEmailNuovaToken() {
        return $this->email_nuova_token;
    }

    public function setEmailNuovaToken($email_nuova_token) {
        $this->email_nuova_token = $email_nuova_token;
    }

        
    public function getEmailNuova() {
        return $this->email_nuova;
    }

    public function setEmailNuova($email_nuova) {
        $this->email_nuova = $email_nuova;
    }

    
    public function getFacebookAccessToken() {
        return $this->facebook_access_token;
    }

    public function setFacebookAccessToken($facebook_access_token) {
        $this->facebook_access_token = $facebook_access_token;
    }

        
    
    public function getGoogleId() {
        return $this->google_id;
    }

    public function setGoogleId($google_id) {
        $this->google_id = $google_id;
    }

    public function getGoogleAccessToken() {
        return $this->google_access_token;
    }

    public function setGoogleAccessToken($google_access_token) {
        $this->google_access_token = $google_access_token;
    }

        
    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

        
    public function getTwitterId() {
        return $this->twitter_id;
    }

    public function setTwitterId($twitter_id) {
        $this->twitter_id = $twitter_id;
    }

    public function getTwitterAccessToken() {
        return $this->twitter_access_token;
    }

    public function setTwitterAccessToken($twitter_access_token) {
        $this->twitter_access_token = $twitter_access_token;
    }

        
    const FACEBOOK = 1;
    const TWITTER = 2;
    const GOOGLE = 3;

    function __construct() {
        parent::__construct();
    }

    public function serialize() {
        return serialize(array($this->facebookId, $this->twitter_id,$this->google_id, parent::serialize()));
    }

    public function unserialize($data) {
        list($this->facebookId,$this->twitter_id,$this->google_id, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }

    /**
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName() {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
        $this->setUsername($facebookId);
        $this->salt = '';
    }

    /**
     * @return string
     */
    public function getFacebookId() {
        return $this->facebookId;
    }

    /**
     * @param Array
     */
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

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return User
     */
    public function setNickname($nickname) {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname() {
        return $this->nickname;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return User
     */
    public function setLocale($locale) {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * Set locale
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday, $from = null) {
        if ($birthday) {
            if (is_string($birthday)) {
                if (in_array($from, array(self::FACEBOOK))) {
                    $birthday = \DateTime::createFromFormat('m/d/Y', $birthday);
                } else {
                    $birthday = \DateTime::createFromFormat('d/m/Y', $birthday);
                }
            }
            if ($birthday instanceof \DateTime) {
                $this->birthday = $birthday;
            }
        }
        return $this;
    }

    /**
     * Get locale
     *
     * @return \DateTime
     */
    public function getBirthday() {
        return $this->birthday;
    }

    public function ep8String() {
        return $this->getEmail() . '|' . $this->getNickname() . '|' . $this->getId() . '|' . $this->getSalt() . '|' . $this->getPassword();
    }

}