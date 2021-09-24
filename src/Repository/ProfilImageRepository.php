<?php

namespace App\Repository;

use App\Entity\ProfilImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilImage[]    findAll()
 * @method ProfilImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilImage::class);
    }

    // /**
    //  * @return ProfilImage[] Returns an array of ProfilImage objects
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
    public function findOneBySomeField($value): ?ProfilImage
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
