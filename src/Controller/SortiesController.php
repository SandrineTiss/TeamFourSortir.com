<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sorties;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortiesRepository;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Forms;
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
     * @Route("/", name="liste")
     */
    public function findAll(SortiesRepository $sortiesRepository): Response
    {
        $sorties = $sortiesRepository->findAll();
        return $this->render('main/accueil.html.twig', [
            'sorties' => $sorties
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function createSortie(
        Request $request,
        EntityManagerInterface $entityManager
        ): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            $user = $this->security->getUser();

            $sortie->setOrganisateur($user);
            $sortie->addInscrit($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie a bien été créée');

            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/creerSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route("/details/{id}", name="sortie_details")
     */
    public function details(int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->find($id);

        return $this->render('sortie/details', ['sortie' => $sortie]);
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
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        $formFactory = Forms::createFormFactory();
        $annulationForm = $formFactory->createBuilder()
            ->add('motifAnnulation', TextType::class)
            ->getForm();
        $annulationForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
            $sortie->setEtat($etat);
            dd($annulationForm->getData()  );
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a bien été annulée !');
            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            'sortie' => $sortie,
            'sortieForm' =>  $sortieForm->createView(),
            'annulationForm' => $annulationForm->createView(),
            'campus' => $sortie->getCampus(),
            'organisateur' => $sortie->getOrganisateur(),
            'inscrits' => $sortie->getInscrits(),
            'lieu' => $sortie->getLieu(),
            'ville' => $sortie->getLieu()->getVille(),
        ]);
    }
}