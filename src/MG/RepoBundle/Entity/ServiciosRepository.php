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

class ServiciosRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithLimitAndOffset($limit = 10,$offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM MGRepoBundle:Servicios s')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    public function findAllServicesActiveUsingName($nombre)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->from('MGRepoBundle:Servicios', 's')
            ->join('s.empresa', 'e')
            ->where('s.nombre =?1')
            ->andwhere('s.enabled =?2')
            ->andWhere('e.public =?3')
            ->setParameter(1, $nombre)
            ->setParameter(2, true)
            ->setParameter(3, true)
            ;

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findAllServicesActive()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->from('MGRepoBundle:Servicios', 's')
            ->join('s.empresa', 'e')
            ->where('s.enabled =?1')
            ->andWhere('e.public =?2')
            ->setParameter(1, true)
            ->setParameter(2, true)
        ;

        $query = $qb->getQuery();
        return $query->getResult();
    }
}
