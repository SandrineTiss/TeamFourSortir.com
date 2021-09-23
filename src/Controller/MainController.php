<?php

namespace App\Controller;


use App\Entity\SortieSearch;
use App\Form\SortieSearchType;
use App\Repository\SortiesRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_accueil")
     */
    public function accueil(SortiesRepository $sortiesRepository, Request $request): Response
    {
        if ($this->getUser()) {
            $search = new SortieSearch();
            $form = $this->createForm(SortieSearchType::class, $search);
            $form->handleRequest($request);

               $user = $this->getUser();
               $sortie = $sortiesRepository->findByFilters($search, $user);

            return $this->render('main/accueil.html.twig', [
                'sorties' => $sortie,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
