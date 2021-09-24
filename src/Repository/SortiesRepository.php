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

        //$user = $this->security->getUser();

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


    public function findByFilters(SortieSearch $sortie, User $utilisateur)
    {
        /*
        $queryBuilder = $this -> createQueryBuilder('s');

        $queryBuilder->join('s.etat', 'etat')->addSelect('etat');

        $queryBuilder->join('s.lieu', 'lieu')->addSelect('lieu');

        $queryBuilder->join('lieu.ville', 'ville')->addSelect('ville');

        $queryBuilder->join('s.campus', 'campus')->addSelect('campus');

        $queryBuilder->join('s.organisateur', 'organisateur')->addSelect('organisateur');

        $queryBuilder->leftJoin('s.inscrits', 'inscrits')->addSelect('inscrits');


        if ($sortie->getCampus()){
            $queryBuilder->andWhere('s.campus = :campus')
                ->setParameter('campus', $sortie->getCampus()->getId());
        }


        if ($sortie->getNom()){
            $queryBuilder->andWhere('s.nom LIKE \'%'.$sortie->getNom().'%\'');
        }


        if ($sortie->getEnded()){
            $queryBuilder->andWhere('etat.libelle = :ended')
                ->setParameter('ended', 'Cloturée');
        }


        if ($sortie->getInscrit()){
            $queryBuilder->andWhere('inscrits.id = :user')
                ->setParameter('user', $utilisateur->getId());
        }

        // TODO: regler probleme sorties non inscrit

        if($sortie->getNotInscrit()){


        }





        if ($sortie->getOrganisateur()){
            $queryBuilder->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $utilisateur->getId());
        }

        if ($sortie->getDate() && $sortie->getDate2()) {
            $queryBuilder->andWhere('s.dateHeureDebut > :date')
                ->setParameter('date', $sortie->getDate())
                ->andWhere('s.dateHeureDebut < :date2')
                ->setParameter('date2', $sortie->getDate2());
        }

        return $queryBuilder -> getQuery()-> getResult();
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
            // ne pas afficher les sorties annulées
            //->andWhere('e.libelle IN ("Ouverte", "Cloturée", "Passée", "En cours")') ... ne fonctionne pas ...
            ->setParameter('dernierMois', new \DateTime('-1 month'));

        if ($sortie->getNotInscrit()) {
            $subQueryBuilder = $this->createQueryBuilder('s2')
                ->leftJoin('s2.inscrits', 'p2')
                ->andWhere('p2 = :user');
            $queryBuilder
                //->andWhere(:user member of s.inscrits)
                ->andWhere($queryBuilder->expr()->notIn('s.id', $subQueryBuilder->getDQL()))
                ->orWhere(':user = s.organisateur')
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

        if ($sortie->getOrganisateur()) {
            $queryBuilder
                ->andWhere('s.organisateur = :user')
                // si c'est l'organisateur, alors les sorties créées mais non ouvertes s'affichent aussi...

                ->setParameter('user', $utilisateur);
        }

        if ($sortie->getInscrit()) {
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
