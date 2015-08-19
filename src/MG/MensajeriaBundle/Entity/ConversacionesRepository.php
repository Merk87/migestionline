<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 26/06/13
 * Time: 9:34
 * To change this template use File | Settings | File Templates.
 */

namespace MG\MensajeriaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ConversacionesRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithLimitAndOffset($limit = 10, $offset, $userId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGMensajeriaBundle:Conversaciones');

        return $repoGest->createQueryBuilder('c')
            ->join('c.members', 'u')
            ->where('u.id =?1')
            ->addOrderBy('c.fechaLastMessage', 'DESC')
            ->setParameter(1, $userId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findAllForPagination($userId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGMensajeriaBundle:Conversaciones');

        return $repoGest->createQueryBuilder('c')
            ->join('c.members', 'u')
            ->where('u.id =?1')
            ->addOrderBy('c.fechaLastMessage', 'DESC')
            ->setParameter(1, $userId)
            ->getQuery()
            ->getResult();
    }

    public function findAllWithLimitAndOffsetAndStatus($limit = 10, $offset, $userId, $statusId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGMensajeriaBundle:Conversaciones');

        return $repoGest->createQueryBuilder('c')
            ->join('c.members', 'u')
            ->join('c.mensajes', 'm')
            ->where('u.id =?1')
            ->andWhere('m.idStatus =?2')
            ->andWhere('m.idDestinatario =?3')
            ->addOrderBy('c.fechaLastMessage', 'DESC')
            ->setParameter(1, $userId)
            ->setParameter(2, $statusId)
            ->setParameter(3, $userId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findAllForPaginationStatus($userId, $statusId)
    {
        $repoGest = $this->getEntityManager()->getRepository('MGMensajeriaBundle:Conversaciones');

        return $repoGest->createQueryBuilder('c')
            ->join('c.members', 'u')
            ->JOIN('c.mensajes', 'm')
            ->where('u.id =?1')
            ->andWhere('m.idStatus =?2')
            ->andWhere('m.idDestinatario =?3')
            ->addOrderBy('c.fechaLastMessage', 'DESC')
            ->setParameter(1, $userId)
            ->setParameter(2, $statusId)
            ->setParameter(3, $userId)
            ->getQuery()
            ->getResult();
    }
}
