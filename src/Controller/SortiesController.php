<?php

namespace App\Controller;



use App\Entity\Sorties;
use App\Form\AnnulerSortieType;
use App\Form\SortieType;
use App\Repository\EtatRepository;

use App\Repository\SortiesRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/sortie", name="sortie_")
 */
class SortiesController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @Route("/liste", name="liste")
     */
    public function listeSorties(SortiesRepository $sortiesRepository): Response
    {
        $sorties = $sortiesRepository->findAll();
        return $this->render('sortie/liste.html.twig', [
            'sorties' => $sorties
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function createSortie(
        Request $request,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager
        ): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(SortieType::class, $sortie);


        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            $user = $this->security->getUser();
            $etat = $etatRepository->findOneBy(['libelle' => 'Créée']);

            $sortie->setCampus($user->getCampus());
            $sortie->setOrganisateur($user);
            $sortie->setEtat($etat);
            $sortie->addInscrit($user);
            $sortie->setCampus($user->getCampus());
            $entityManager->persist($sortie);
            $entityManager->flush();

    $this->addFlash('success', 'Votre sortie a bien été créée, n\'oubliez pas de la publier pour l\'ouvrir aux inscriptions !');

            return $this->redirectToRoute('sortie_liste');
        }


        return $this->render('sortie/creerSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieux' =>$sortie->getLieu(),
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->find($id);

        return $this->render('sortie/detailSortie.html.twig', [
            'sortie' => $sortie,
            'etat' => $sortie->getEtat(),
            'lieu'=>$sortie->getLieu(),
            'ville'=>$sortie->getVille(),
            'campus' =>$sortie->getCampus(),
            'organisateur' => $sortie->getOrganisateur(),
            'inscrits' => $sortie->getInscrits()
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifySortie(
        Request $request,
        EntityManagerInterface $entityManager,
        SortiesRepository $sortiesRepository,
        int $id
    ): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a bien été modifiée !');
            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/modifierSortie.html.twig', [
            'sortie' => $sortie,
            'sortieForm' =>  $sortieForm->createView(),
            'campus' => $sortie->getCampus(),
            'organisateur' => $sortie->getOrganisateur(),
            'inscrits' => $sortie->getInscrits(),
            'lieu' => $sortie->getLieu(),
            'ville' => $sortie->getLieu()->getVille(),
        ]);

    }

    /**
     * @Route("/publier/{id}", name="publier")
     */
    public function publierSortie(
        Request $request,
        EntityManagerInterface $entityManager,
        SortiesRepository $sortiesRepository,
        EtatRepository $etatRepository,
        int $id
    ): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $etat = $etatRepository->findOneBy(['libelle' => 'Ouverte']);
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a bien été publiée !');
            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/publierSortie.html.twig', [
            'sortie' => $sortie,
            'sortieForm' =>  $sortieForm->createView(),
            'campus' => $sortie->getCampus(),
            'organisateur' => $sortie->getOrganisateur(),
            'inscrits' => $sortie->getInscrits(),
            'lieu' => $sortie->getLieu(),
            'ville' => $sortie->getLieu()->getVille(),
        ]);

    }

    /**
     * @Route("/annuler/{id}", name="annuler")
     */
    public function annulerSortie(
        Request $request,
        EntityManagerInterface $entityManager,
        SortiesRepository $sortiesRepository,
        EtatRepository $etatRepository,
        int $id
    ): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $annulationSortieForm = $this->createForm(AnnulerSortieType::class, $sortie);
        $annulationSortieForm->handleRequest($request);

        if($annulationSortieForm->isSubmitted() && $annulationSortieForm->isValid()) {

            $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a bien été annulée !');
            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            'sortie' => $sortie,
            'annulationSortieForm' =>  $annulationSortieForm->createView(),
        ]);
    }
}