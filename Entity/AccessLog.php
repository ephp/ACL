<?php

namespace Ephp\ACLBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ephp\ACLBundle\Model\BaseAccessLog;

/**
 * BaseAccessLog
 *
 * @ORM\Table(name="ephp_access_log")
 * @ORM\Entity(repositoryClass="Ephp\ACLBundle\Entity\AccessLogRepository")
 */
class AccessLog extends BaseAccessLog {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return BaseAccessLog
     */
    public function setUser($user) {
        if(! $user instanceof User) {
            throw new \Exception('Classe User non conforme a quella attesa');
        }
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser() {
        return $this->user;
    }

}
