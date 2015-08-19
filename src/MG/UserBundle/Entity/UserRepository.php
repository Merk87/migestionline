<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 11/06/13
 * Time: 11:39
 * To change this template use File | Settings | File Templates.
 */
namespace MG\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function findAllWithLimitAndOffset($limit = 10,$offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u FROM MGUserBundle:User u')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    public function findAllByEmpresaId($empId)
    {
        $repo_users = $this->getEntityManager()->getRepository('MGUserBundle:User');

        $query =  $repo_users -> createQueryBuilder('u')
            ->join('u.empresas', 'e')
            ->where('e.id =?1')
            ->setParameter(1, $empId)
            ->getQuery();

        return $allus = $query ->getResult();
    }

}