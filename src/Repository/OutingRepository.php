<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\OutingStatus;
use App\Entity\Search;
use App\Entity\User;
use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\HttpFoundation\Request;

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
    public function findAllVisibleQuery(Search $search, User $user): array
    {
        $query = $this->createQueryBuilder('u');
        $query  ->leftJoin('u.inscriptions','i');
        $arrayOutingId =array();
        $arrayOutingStatusName =array();

        if ($search->getName()) {
            $query = $query->andWhere('u.name like :name')->setParameter(':name', '%'.$search->getName().'%');
        }

        if ($search->getCampus()) {
            $query = $query->andWhere('u.campus = :campus')->setParameter(':campus', $search->getCampus());
        }

        if ($search->getDateMin()) {
            $query = $query->andWhere('u.startingTime > :dateMin')->setParameter(':dateMin', $search->getDateMin());
        }

        if ($search->getDateMax()) {
            $query = $query->andWhere('u.startingTime < :dateMax')->setParameter(':dateMax', $search->getDateMax());
        }

        if ($search->getPlanner()) {
            $query = $query->andWhere('u.planner = :planner')
                    ->setParameter(':planner', $search->getPlanner()->getId());
        }

        if ($search->getRegistered()) {
            $query = $query ->andwhere('i.user = :user')
                            ->setParameter(':user', $user);
        }

        if ($search->getNotRegistered()) {
            $outingsIRegistered = $this->getEntityManager()->getRepository(Inscription::class)->findBy(['user' => $user]);
            foreach ($outingsIRegistered as $oIR){
                array_push($arrayOutingId, $oIR->getOuting()->getId());
            }

            $query = $query ->andWhere('u.id NOT IN (:array)')
                            ->setParameter(':array', $arrayOutingId);
        }

        $outingStatus = $this->getEntityManager()->getRepository(OutingStatus::class)->findAll();
        foreach ($outingStatus as $oSN){
            if ($oSN->getDescription() != "Closed" && $oSN->getDescription() != "Created") {
                array_push($arrayOutingStatusName, $oSN);
            }
        }

        $query = $query ->andWhere('u.status IN (:status)')
            ->setParameter(':status', $arrayOutingStatusName);

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
