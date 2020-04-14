<?php

namespace App\Repository;

use App\Entity\VettingRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VettingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method VettingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method VettingRequest[]    findAll()
 * @method VettingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VettingRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VettingRequest::class);
    }

    // /**
    //  * @return VettingRequest[] Returns an array of VettingRequest objects
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
    public function findOneBySomeField($value): ?VettingRequest
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
