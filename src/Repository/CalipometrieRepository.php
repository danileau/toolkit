<?php

namespace App\Repository;

use App\Entity\Calipometrie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calipometrie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calipometrie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calipometrie[]    findAll()
 * @method Calipometrie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalipometrieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calipometrie::class);
    }


    public function findAllFromUser($id)
    {
        return $this->findBy(array('user' => $id), array('timestamp' => 'DESC'));
    }
    
    // /**
    //  * @return Calipometrie[] Returns an array of Calipometrie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Calipometrie
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
