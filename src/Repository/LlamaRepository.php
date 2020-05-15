<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Llama;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Llama|null find($id, $lockMode = null, $lockVersion = null)
 * @method Llama|null findOneBy(array $criteria, array $orderBy = null)
 * @method Llama[]    findAll()
 * @method Llama[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LlamaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Llama::class);
    }

    // /**
    //  * @return Llama[] Returns an array of Llama objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Llama
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
