<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 26/06/13
 * Time: 9:34
 * To change this template use File | Settings | File Templates.
 */

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RepoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithLimitAndOffset($limit = 10,$offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM MGRepoBundle:Repo r')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }
}
