<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 11/10/13
 * Time: 11:00
 * To change this template use File | Settings | File Templates.
 */

namespace MG\RepoBundle\Entity;


use Doctrine\ORM\EntityRepository;

class ClientContratacionRepository extends EntityRepository {

    public function findAllWithLimitAndOffset($limit = 10,$offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM MGRepoBundle:ClientContratacion c')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

}