<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    /**
     * @return array
     */
    public function findAllVisibleQuery(Search $search): array
    {
        $query = $this->createQueryBuilder('u');

        if ($search->getName()) {
            $query = $query->andWhere('u.name like :name')->setParameter(':name', '%'.$search->getName().'%');
        }

        if ($search->getCampus()) {
            $query = $query->andWhere('u.campus = :campus')->setParameter(':campus', $search->getCampus());
        }


        return $query->getQuery()->getResult();

    }




    /*
    public function findOneBySomeField($value): ?Outing
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
