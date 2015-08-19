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

class GestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithLimitAndOffset($limit = 10,$offset, $ord)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->addOrderBy('g.fechaCreacion', $ord)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findAllWithLimitEstAndOffset($limit = 10, $offset, $ord, $estado, $empId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.estadoId =?1')
            ->andWhere('g.idEmpresa =?2')
            ->addOrderBy('g.fechaCreacion', $ord)
            ->setParameter(1, $estado)
            ->setParameter(2, $empId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findAllForPagination($ord, $estado, $empId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.estadoId =?1')
            ->andWhere('g.idEmpresa =?2')
            ->addOrderBy('g.fechaCreacion', $ord)
            ->setParameter(1, $estado)
            ->setParameter(2, $empId)
            ->getQuery()
            ->getResult();
    }

    public function findAllWithLimitAndOffsetCli($limit = 10, $offset, $ord, $empId, $cliId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.idCliente =?1')
            ->andWhere('g.idEmpresa =?2')
            ->addOrderBy('g.fechaCreacion', $ord)
            ->setParameter(1, $cliId)
            ->setParameter(2, $empId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findAllForPaginationCli($empId, $cliId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.idCliente =?1')
            ->andWhere('g.idEmpresa =?2')
            ->setParameter(1, $cliId)
            ->setParameter(2, $empId)
            ->getQuery()
            ->getResult();
    }

    public function findAllWithLimitAndOffsetCatCli($limit = 10, $offset, $ord, $empId, $cliId, $cat)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.idCliente =?1')
            ->andWhere('g.idEmpresa =?2')
            ->andWhere('g.idCategoria =?3')
            ->addOrderBy('g.fechaCreacion', $ord)
            ->setParameter(1, $cliId)
            ->setParameter(2, $empId)
            ->setParameter(3, $cat)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findAllCatForPaginationCli($empId, $cliId, $cat)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.idCliente =?1')
            ->andWhere('g.idEmpresa =?2')
            ->andWhere('g.idCategoria =?3')
            ->setParameter(1, $cliId)
            ->setParameter(2, $empId)
            ->setParameter(3, $cat)
            ->getQuery()
            ->getResult();
    }

    public function findAllWithLimitAndOffsetEstCli($limit = 10, $offset, $ord, $empId, $cliId, $est)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.idCliente =?1')
            ->andWhere('g.idEmpresa =?2')
            ->andWhere('g.estadoId =?3')
            ->addOrderBy('g.fechaCreacion', $ord)
            ->setParameter(1, $cliId)
            ->setParameter(2, $empId)
            ->setParameter(3, $est)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findAllEstForPaginationCli($empId, $cliId, $est)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGRepoBundle:Gestion');

        return $repoGest->createQueryBuilder('g')
            ->where('g.idCliente =?1')
            ->andWhere('g.idEmpresa =?2')
            ->andWhere('g.estadoId =?3')
            ->setParameter(1, $cliId)
            ->setParameter(2, $empId)
            ->setParameter(3, $est)
            ->getQuery()
            ->getResult();
    }
}
