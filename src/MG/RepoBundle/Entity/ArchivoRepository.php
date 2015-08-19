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

class ArchivoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithLimitAndOffset($limit = 10,$offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM MGRepoBundle:Archivo a')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    public function findAllByCategory($limit = 10, $offset)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByCategoryRepo($repoId, $limit = 10, $offset)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->where('a.repoId =?1')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->setParameter(1, $repoId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByCategoryUser($authUser, $repoId, $limit = 10, $offset)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->join('s.users', 'u')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->where('a.repoId =?2')
            ->andWhere('u.id =?1')
            ->setParameter(1, $authUser)
            ->setParameter(2, $repoId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByCategoryUserNL($authUser, $repoId)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->join('s.users', 'u')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->where('a.repoId =?2')
            ->andWhere('u.id =?1')
            ->setParameter(1, $authUser)
            ->setParameter(2, $repoId)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByOwner($authUser, $limit = 10, $offset)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->where('a.idUsuario =?1')
            ->setParameter(1, $authUser)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByOwnerNL($authUser)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->where('a.idUsuario =?1')
            ->setParameter(1, $authUser)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByEmpUser($client, $authUser, $limit = 10, $offset)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->join('s.users', 'u')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->where('u.id =?1')
            ->andWhere('a.idUsuario =?2 ')
            ->setParameter(1, $authUser)
            ->setParameter(2, $client)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findBetweenDates($fechaIni, $fechaFin, $repoId, $limit = 10, $offset)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->where('a.repoId =?3')
            ->andWhere('a.fechaSubida >=?1')
            ->andWhere('a.fechaSubida <=?2')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->setParameter(1, $fechaIni)
            ->setParameter(2, $fechaFin)
            ->setParameter(3, $repoId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findBetweenDatesNL($fechaIni, $fechaFin, $repoId)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->where('a.repoId =?3')
            ->andWhere('a.fechaSubida >=?1')
            ->andWhere('a.fechaSubida <=?2')
            ->setParameter(1, $fechaIni)
            ->setParameter(2, $fechaFin)
            ->setParameter(3, $repoId)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByOwnerBetweenDate($owner,  $fechaIni, $fechaFin, $limit = 10, $offset)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->where('a.idUsuario =?1')
            ->andWhere('a.fechaSubida >=?2')
            ->andWhere('a.fechaSubida <=?3')
            ->setParameter(1, $owner)
            ->setParameter(2, $fechaIni)
            ->setParameter(3, $fechaFin)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

    public function findAllByOwnerBetweenDateNL($owner, $fechaIni, $fechaFin)
    {
        $cat_files = $this->getEntityManager()->getRepository('MGRepoBundle:Archivo');

        $query =  $cat_files -> createQueryBuilder('a')
            ->join('a.gestion', 'g')
            ->join('g.categoria', 'c')
            ->join('c.servicio', 's')
            ->OrderBy('c.id')
            ->addOrderBy('a.fechaSubida', 'DESC')
            ->addorderBy('s.id')
            ->where('a.idUsuario =?1')
            ->andWhere('a.fechaSubida >=?2')
            ->andWhere('a.fechaSubida <=?3')
            ->setParameter(1, $owner)
            ->setParameter(2, $fechaIni)
            ->setParameter(3, $fechaFin)
            ->getQuery();

        return $categorizedFiles = $query ->getResult();
    }

}
