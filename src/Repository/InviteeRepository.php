<?php

namespace App\Repository;

use App\Entity\Invitee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Invitee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitee[]    findAll()
 * @method Invitee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InviteeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitee::class);
    }

    // /**
    //  * @return Invitee[] Returns an array of Invitee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Invitee
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function save(Invitee $invitee): void
    {
        $this->_em->persist($invitee);
    }
}
