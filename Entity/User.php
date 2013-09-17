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

    use \Ephp\ACLBundle\Model\Traits\FacebookUser,
        \Ephp\ACLBundle\Model\Traits\TwitterUser,
        \Ephp\ACLBundle\Model\Traits\GoogleUser;

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

}