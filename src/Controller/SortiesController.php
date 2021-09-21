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
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SortiesController extends AbstractController
{

    /**
     * @Route("/liste", name="sortie_liste")
     */
    public function findAll(SortiesRepository $sortiesRepository): Response
    {
        $sorties = $sortiesRepository->findAll();
        return $this->render('sortie/liste.html.twig', [
            "sorties" => $sorties
        ]);
    }

    /**
     * @Route("/creerSortie", name="sortie_creerSortie")
     */
    public function createSortie(
        Request $request,
        EntityManagerInterface $entityManager
        ): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted()){



            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie a bien été créée');
            return $this->redirectToRoute('main_accueil');

        }


        return $this->render('sortie/creerSortie.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }
}