<?php

namespace Ephp\ACLBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invito
 *
 * @ORM\Table(name="ephp_inviti")
 * @ORM\Entity(repositoryClass="Ephp\ACLBundle\Entity\InvitoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *                  "email" = "InvitoEmail", 
 *                  "facebook" = "InvitoFacebook"
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class Invito {

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
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="codice", type="string", length=255)
     */
    private $codice;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notificato", type="boolean")
     */
    private $notificato;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accettato", type="boolean")
     */
    private $accettato;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

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
     * @return Invito
     */
    public function setUser($user) {
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

    /**
     * Set codice
     *
     * @param string $codice
     * @return Invito
     */
    public function setCodice($codice) {
        $this->codice = $codice;

        return $this;
    }

    /**
     * Get codice
     *
     * @return string 
     */
    public function getCodice() {
        return $this->codice;
    }

    /**
     * Set notificato
     *
     * @param boolean $notificato
     * @return Invito
     */
    public function setNotificato($notificato) {
        $this->notificato = $notificato;

        return $this;
    }

    /**
     * Get notificato
     *
     * @return boolean 
     */
    public function getNotificato() {
        return $this->notificato;
    }

    /**
     * Set accettato
     *
     * @param boolean $accettato
     * @return Invito
     */
    public function setAccettato($accettato) {
        $this->accettato = $accettato;

        return $this;
    }

    /**
     * Get accettato
     *
     * @return boolean 
     */
    public function getAccettato() {
        return $this->accettato;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Invito
     */
    public function setCreatedAt($createdAt) {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        $zona = new \DateTimeZone('Europe/Rome');
        $now = new \DateTime('now', $zona);
        $this->created_at = $now;
        $this->codice_verifica = $this->generaCodice($now);
        $this->notificato = false;
        $this->accettato = false;
    }

    abstract protected function generaCodice(\DateTime $now);
    
}