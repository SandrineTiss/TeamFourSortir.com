<?php

namespace App\Controller;


use App\Entity\Sorties;
use App\Form\SortieType;
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
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie a bien été créée');
            return $this->redirectToRoute('sortie_details', ['id' => $sortie->getId()]);
        }


        return $this->render('sortie/creerSortie.html.twig', [
            'sortieForm' => $sortieForm->createView()
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
}