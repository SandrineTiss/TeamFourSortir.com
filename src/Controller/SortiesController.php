<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/liste", name="sortie_liste")
     */
    public function liste(): Response
    {
        // todo: aller chercher les sortie et les passer a twig

        return $this->render('sortie/liste.html.twig');
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