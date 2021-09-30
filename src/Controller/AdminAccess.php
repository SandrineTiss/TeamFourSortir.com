<?php

namespace App\Controller;

use App\Entity\SortieSearch;
use App\Form\RegistrationFormType;
use App\Form\SortieSearchType;
use App\Repository\SortiesRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminAccess extends AbstractController
{
    /**
     * @Route("/admin", name="admin_access")
     */
    public function accueil(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

//    /**
//     * @Route("/gestion_utilisateur", name="gestion_utilisateur")
//     * @param $request
//     * @return Response
//     */
//    public function gestionUser(SortiesRepository $sortiesRepository, Request $request): Response
//    {
////        if ($this->getUser()) {
////            $search = new SortieSearch();
////            $form = $this->createForm(RegistrationFormType::class, $search);
////            $form->handleRequest($request);
////            $sortie = $sortiesRepository->findAllRecents();
////
////            if($form->isSubmitted())
////            {
////                $user = $this->getUser();
////                $sortie = $sortiesRepository->findByFilters($search, $user);
////            }
////            return $this->render('main/accueil.html.twig', [
////                'sorties' => $sortie,
////                'form' => $form->createView(),
////            ]);
////        } else {
////            return $this->redirectToRoute('app_login');
////        }
//        return $this->render('admin/gestion_utilisateur.html.twig');
//    }
}