<?php

namespace App\Repository;

use App\Entity\OutingStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OutingStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method OutingStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method OutingStatus[]    findAll()
 * @method OutingStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutingStatus::class);
    }

    // /**
    //  * @return OutingStatus[] Returns an array of OutingStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OutingStatus
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
