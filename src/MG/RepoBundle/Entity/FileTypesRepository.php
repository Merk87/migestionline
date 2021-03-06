<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 11/06/13
 * Time: 11:39
 * To change this template use File | Settings | File Templates.
 */
namespace MG\RepoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FileTypesRepository extends EntityRepository
{

    public function findAllWithLimitAndOffset($limit = 10,$offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM MGRepoBundle:FileTypes f')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

}
