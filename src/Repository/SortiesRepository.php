<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sorties;
use App\Entity\SortieSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

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

    public function findByFilters(SortieSearch $sortie)
    {
        $queryBuilder = $this -> createQueryBuilder('s');


        if ($sortie->getCampus()){
            $queryBuilder->andWhere('s.campus = :campus')
                ->setParameter('campus', $sortie->getCampus()->getId());
        }


        if ($sortie->getNom()){
            $queryBuilder->andWhere('s.nom LIKE \'%'.$sortie->getNom().'%\'');
        }

        /*
        if ($sortie->getEnded()){
            $queryBuilder->andWhere('etat = :ended')
                ->setParameter('ended', 'Cloturée');
        }

        if ($sortie->getInscrit()){
            $queryBuilder->join('sorties_user', 'su', 'ON', 's.id')
                ->andWhere('su.user_id = :user')
                ->setParameter('user', $this->getUser()->getId());
        }

        if ($sortie->getOrganisateur()){
            $queryBuilder->andWhere('organisateur_id = :organisateur')
                ->setParameter('organisateur', $this->getUser()->getId());
        }

        if ($sortie->getDate() && $sortie->getDate2()) {
            $queryBuilder->andWhere('s.dateHeureDebut > :date')
                ->setParameter('date', $sortie->getDate())
                ->andWhere('s.dateHeureDebut < :date2')
                ->setParameter('date2', $sortie->getDate2());
        }
        */
        return $queryBuilder -> getQuery()-> getResult();
    }
}
