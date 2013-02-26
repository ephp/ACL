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

}