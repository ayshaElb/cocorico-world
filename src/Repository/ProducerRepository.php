<?php

namespace App\Repository;

use App\Entity\Producer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Producer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producer[]    findAll()
 * @method Producer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProducerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producer::class);
    }

    public function getAllWithSearchQueryBuilder($term): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->addSelect('u')
        ;

        if ($term) {
            $qb->andWhere('p.firstname LIKE :term OR p.lastname LIKE :term OR u.firstname LIKE :term OR u.lastname LIKE :term')
                ->setParameter('term', '%'.$term.'%')
            ;
        }

        return $qb;
    }
    // /**
    //  * @return Producer[] Returns an array of Producer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Producer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
