<?php

namespace App\Repository;

use App\Entity\VirtualTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VirtualTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method VirtualTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method VirtualTicket[]    findAll()
 * @method VirtualTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VirtualTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirtualTicket::class);
    }

    // /**
    //  * @return VirtualTicket[] Returns an array of VirtualTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VirtualTicket
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
