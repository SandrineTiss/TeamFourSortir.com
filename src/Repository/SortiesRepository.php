<?php

namespace App\Repository;


use App\Entity\Sorties;
use App\Entity\SortieSearch;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\ORM\Query\Expr\GroupBy;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;


/**
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesRepository extends ServiceEntityRepository
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sorties::class);
        $this->security = $security;
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

    public function findAllRecents()
    {
        // afficher sur la page d'accueil toutes les sorties depuis un mois

        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->join('s.etat', 'etat')->addSelect('etat');
        $queryBuilder->join('s.lieu', 'lieu')->addSelect('lieu');
        $queryBuilder->join('lieu.ville', 'ville')->addSelect('ville');
        $queryBuilder->join('s.campus', 'campus')->addSelect('campus');
        $queryBuilder->join('s.organisateur', 'organisateur')->addSelect('organisateur');
        $queryBuilder->leftjoin('s.inscrits', 'inscrits')->addSelect('inscrits');
            //Pour ne pas afficher les sorties archivées (plus d'un mois)
        $queryBuilder->andWhere('s.dateHeureDebut >= :dernierMois')
            ->setParameter('dernierMois', new \DateTime('-1 month'));
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }



    public function findByFilters(SortieSearch $sortie, User $utilisateur)
    {
        /*
         * Documentation :
         * Les Alias des entités en requête et sous-requête :
         * s, s2 : sorties
         * p,i : inscrits
         * e : etat
         * o : organisateur
         */
        $queryBuilder = $this->createQueryBuilder('s')
            ->groupBy('s.id')
            ->join('s.etat', 'e')
            ->addOrderBy('s.dateHeureDebut', 'DESC')
            ->addOrderBy('e.id', 'ASC')
            ->leftJoin('s.inscrits', 'p')
            ->join('s.organisateur', 'o')
            ->select('s', 'p', 'e', 'o')
            //Pour ne pas afficher les sorties archivées (plus d'un mois)
            ->andWhere('s.dateHeureDebut >= :dernierMois')
            ->setParameter('dernierMois', new \DateTime('-1 month'));

        if ($sortie->getNotInscrit() && !$sortie->getInscrit()) {
            $queryBuilder
                ->andWhere('s NOT IN ('.$this->createQueryBuilder('s2')->leftJoin('s2.inscrits','i')->where('i = :user').')')
                ->setParameter('user', $utilisateur);
        }

        if ($sortie->getNom()) {
            $queryBuilder
                ->andWhere('s.nom LIKE :rechercheNom')
                ->setParameter('rechercheNom', '%'.$sortie->getNom().'%');
        }

        if ($sortie->getDateMin()) {
            $queryBuilder
                ->andWhere('s.dateHeureDebut >= :dateMin')
                ->setParameter('dateMin', $sortie->getDateMin());
        }

        if ($sortie->getDateMax()) {
            $queryBuilder
                ->andWhere('s.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $sortie->getDateMax());
        }

        if ($sortie->getOrganisateur() && !$sortie->getInscrit()) {
            $queryBuilder
                ->andWhere('s.organisateur = :user')
                ->setParameter('user', $utilisateur);
        }

        if ($sortie->getInscrit() && !$sortie->getNotInscrit()) {
            $queryBuilder
                ->andWhere(':user = p')
                ->setParameter('user', $utilisateur);
        }

        if ($sortie->getEnded()) {
            $queryBuilder
                ->andWhere('s.dateHeureDebut <= CURRENT_DATE()');
        }

        if($sortie->getCampus()) {
            $queryBuilder
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $sortie->getCampus());
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();

    }
}
