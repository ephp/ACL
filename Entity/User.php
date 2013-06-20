<?php

namespace Ephp\ACLBundle\Entity;

use Ephp\ACLBundle\Model\BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\ACLBundle\Entity\User
 *
 * @ORM\Table(name="ephp_users")
 * @ORM\Entity(repositoryClass="Ephp\ACLBundle\Entity\UserRepository")
 */
class User extends BaseUser {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    function __construct() {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @var string
     */
    protected $twitterID;

    /**
     * @var string
     */
    protected $twitter_username;

    /**
     * Set twitterID
     *
     * @param string $twitterID
     */
    public function setTwitterID($twitterID) {
        $this->twitterID = $twitterID;
        $this->setUsername($twitterID);
        $this->salt = '';
    }

    /**
     * Get twitterID
     *
     * @return string 
     */
    public function getTwitterID() {
        return $this->twitterID;
    }

    /**
     * Set twitter_username
     *
     * @param string $twitterUsername
     */
    public function setTwitterUsername($twitterUsername) {
        $this->twitter_username = $twitterUsername;
    }

    /**
     * Get twitter_username
     *
     * @return string 
     */
    public function getTwitterUsername() {
        return $this->twitter_username;
    }

}