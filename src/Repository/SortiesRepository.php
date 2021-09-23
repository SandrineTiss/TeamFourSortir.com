<?php

namespace App\Repository;

use App\Entity\Sorties;
use App\Entity\SortieSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sorties::class);
    }

    public function participate(int $id){


        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder->join('s.etat', 'etat')->addSelect('etat');

        $queryBuilder->join('s.lieu', 'lieu')->addSelect('lieu');

        $queryBuilder->join('lieu.ville', 'ville')->addSelect('ville');

        $queryBuilder->join('s.campus', 'campus')->addSelect('campus');

        $queryBuilder->join('s.organisateur', 'organisateur')->addSelect('organisateur');

        $queryBuilder->leftjoin('s.inscrits', 'inscrits')->addSelect('inscrits');

        $queryBuilder->where('s.id = '.$id);

        $query = $queryBuilder ->getQuery();

        return $query->getSingleResult();

    }

    public function findByFilters(SortieSearch $sortieSearch): Query
    {
        $query = $this -> createQueryBuilder('s');

        if ($sortieSearch->getCampus()){
            $query->join('s.campus', 'campus')->addSelect('campus.nom');
        }

        if ($sortieSearch->getNom()){
            $query->join('s.nom', 'nom')->addSelect('s.nom');
        }

        if ($sortieSearch->getEnded()){
            $query->join('s.etat', 'etat')->addSelect('s.etat');
        }

        if ($sortieSearch->getInscrit()){
            $query->join('s.inscrits', 'inscrits')->addSelect('s.inscrits');
        }

        if ($sortieSearch->getOrganisateur()){
            $query->join('s.organisateur', 'orga')->addSelect('s.organisateur');
        }

        if ($sortieSearch->getDate()){
            $query->join('s.date_heure_debut', 'debut')->addSelect('s.dateHeureDebut');
        }
        if ($sortieSearch->getDate2()){
            $query->join('s.date_heure_debut', 'fin')->addSelect('s.dateLimiteInscription');
        }

        if ($sortieSearch->getNotInscrit()){
            $query->join('s.inscrits', 'inscrits')->addSelect('.inscrits.estInscrit');

        }

        return $query->getQuery();
    }


    // /**
    //  * @return Sorties[] Returns an array of Sorties objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sorties
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
