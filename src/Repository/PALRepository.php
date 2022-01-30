<?php

namespace App\Repository;

use App\Entity\PAL;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PAL|null find($id, $lockMode = null, $lockVersion = null)
 * @method PAL|null findOneBy(array $criteria, array $orderBy = null)
 * @method PAL[]    findAll()
 * @method PAL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PALRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PAL::class);
    }

    public function getLastPAL($id){
        return $this->createQueryBuilder('p')
            ->select('p.value')
            ->where('p.user_id = :user')
            ->orderBy('p.timestamp', 'DESC')
            ->setMaxResults(1)
            ->setParameter('user', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastTwoPAL($id){
        return $this->createQueryBuilder('p')
            ->select('p.value')
            ->where('p.user_id = :user')
            ->orderBy('p.timestamp', 'DESC')
            ->setMaxResults(2)
            ->setParameter('user', $id)
            ->getQuery()
            ->getScalarResult();
    }
    // /**
    //  * @return PAL[] Returns an array of PAL objects
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
    public function findOneBySomeField($value): ?PAL
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
