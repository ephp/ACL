<?php

namespace Ephp\ACLBundle\Model;

use FOS\UserBundle\Model\User;
use Doctrine\ORM\Mapping as ORM;
use Ephp\UtilityBundle\Interfaces\EP8;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @Gedmo\Slug(fields={"nickname"}, style="default", separator="-", updatable=true, unique=true)    
     * @ORM\Column(name="slug", type="string", length=64, unique=true)
     */
    protected $slug;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="dati", type="array", nullable=true)
     */
    protected $dati;
    
    
    
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
        
    /**
     * @var string
     * 
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    protected $avatar;
    
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

        
    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    const FACEBOOK = 1;
    const TWITTER = 2;
    const GOOGLE = 3;

    function __construct() {
        parent::__construct();
    }

    public function serialize() {
        $s = array();
        if(method_exists($this, 'serializeFacebook')) {
            $s[] = $this->serializeFacebook();
        } else {
            $s[] = 'no-facebook';
        }
        if(method_exists($this, 'serializeTwitter')) {
            $s[] = $this->serializeTwitter();
        } else {
            $s[] = 'no-twitter';
        }
        if(method_exists($this, 'serializeGoogle')) {
            $s[] = $this->serializeGoogle();
        } else {
            $s[] = 'no-google';
        }
        $s[] = parent::serialize();
        return serialize($s);
    }

    public function unserialize($data) {
        list($facebook, $twitter, $google, $parentData) = unserialize($data);
        if(method_exists($this, 'unserializeFacebook')) {
            $s[] = $this->unserializeFacebook($facebook);
        }
        if(method_exists($this, 'unserializeTwitter')) {
            $s[] = $this->unserializeTwitter($twitter);
        }
        if(method_exists($this, 'unserializeGoogle')) {
            $s[] = $this->unserializeGoogle($google);
        }
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
     * Set dati
     *
     * @param array $dati
     * @return User
     */
    public function setDati($dati) {
        $this->dati = $dati;

        return $this;
    }

    /**
     * Get dati
     *
     * @return array 
     */
    public function getDati() {
        return $this->dati;
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
    
    public function hasRole($role) {
        if(is_array($role)) {
            $roles = $role;
            foreach ($roles as $role) {
                foreach($this->getRoles() as $_role) {
                    if(strtolower($_role) == strtolower($role)) {
                        return true;
                    }
                }
            }
            return false;
        }
        foreach($this->getRoles() as $_role) {
            if(strtolower($_role) == strtolower($role)) {
                return true;
            }
        }
        return false;
    }
}