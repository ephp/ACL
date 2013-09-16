<?php

namespace Ephp\ACLBundle\Controller\Traits;

trait NotifyController {

    /**
     * 
     * @return \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    protected function getTranslator() {
        return $this->get('translator');
    }

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    protected function getEm() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Restituisce il repository richiesto
     * 
     * @param type $classe nome del repository
     * @return \Doctrine\ORM\EntityRepository 
     */
    protected function getRepository($classe) {
        return $this->getEm()->getRepository($classe);
    }

    /**
     * Fa il find da un repository
     * 
     * @param string $classe nome del repository
     * @param array $id id dell'oggetto
     * @return Mixed
     */
    protected function find($classe, $id) {
        return $this->getRepository($classe)->find($id);
    }

    /**
     * Fa il findBy da un repository
     * 
     * @param string $classe nome del repository
     * @param array $find array con i criteri di ricerca
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    protected function findBy($classe, $find, $order = array(), $limit = null, $from = null) {
        return $this->getRepository($classe)->findBy($find, $order, $limit, $from);
    }

    /**
     * Fa il findOneBy da un repository
     * 
     * @param string $classe nome del repository
     * @param array $find array con i criteri di ricerca
     * @return Mixed
     */
    protected function findOneBy($classe, $find, $order = array()) {
        return $this->getRepository($classe)->findOneBy($find, $order);
    }

    /**
     * Restituisce il repository richiesto
     * 
     * @param type $classe entity da persistere
     */
    protected function persist($entity) {
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
    }

    /**
     * Restituisce il repository richiesto
     * 
     * @param type $classe entity da persistere
     */
    protected function remove($entity) {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();
    }

    /**
     * Fa il SELECT * da un repository o esegue una query
     * 
     * @param string $classe nome del repository
     * @param array $find array con i criteri di ricerca
     * @return Mixed
     */
    protected function countDql($classe, $find = array()) {
        $repo = $this->getRepository($classe);
        /* @var $repo \Doctrine\ORM\EntityRepository */
        $qb = $repo->createQueryBuilder('c')->select('COUNT(c)');
        foreach ($find as $key => $value) {
            $qb->andWhere("c.{$key} = :{$key}")
                    ->setParameter($key, $value);
        }
        $out = $qb->getQuery()->getScalarResult();
        return intval($out[0][1]);
    }

    /**
     * Fa il SELECT COUNT(*) usando una query SQL
     * C'è il controllo che sia SELECT COUNT(*) FROM o scoppia
     * 
     * @param string $sql query sql
     * @param array $params array con i criteri di ricerca
     * @return Mixed
     */
    protected function countSql($sql, $params = array()) {
        if (!preg_match('/^[ \n\r\t]*select[ \n\r\t]+count\(\*\)[ \n\r\t]+from[ \n\r\t]+/i', $sql)) {
            $sql = 'SELECT COUNT(*) FROM ' . $sql;
        }
        $em = $this->getEm();
        /* @var $em \Doctrine\ORM\EntityManager */
        $conn = $em->getConnection();
        $stmt = $conn->executeQuery($sql, $params);
        $out = $stmt->fetch();
        return intval(array_shift($out));
    }

    protected function hasRole($role) {
        $user = $this->getUser();
        return $user->hasRole($role);
    }

    protected function inRole($roles) {
        $user = $this->getUser();
        $out = false;
        foreach ($roles as $role) {
            $out != $user->hasRole($role);
        }
        return $out;
    }

    /**
     * 
     * @param string $name
     * @param mixed $default
     * @return mixed
     * @throws \Exception
     */
    protected function getParam($name, $default = null) {
        $out = $this->getRequest()->get($name, null);
        if ($out === null) {
            if ($default instanceof \Exception) {
                throw $default;
            }
            $out = $default;
        }
        return $out;
    }

}