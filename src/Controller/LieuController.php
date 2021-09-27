<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu/creation", name="lieu_creation")
     */
    public function addLieu(EntityManagerInterface $entityManager,Request $request): Response
    {
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){

            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Le lieu de sortie a bien été créé !');

            return $this->redirectToRoute('lieu_affichage');
        }

        return $this->render('lieu/creerLieu.html.twig', [
            'lieuForm' => $lieuForm->createView(),
        ]);
    }

    /**
     * @Route("/lieu", name="lieu_affichage")
     */
    public function afficheCampus( LieuRepository $lieuRepository  ): Response
    {
        $listeLieux = $lieuRepository->findAll();
        return $this->render('lieu/lieux.html.twig', [
            'listeLieux' => $listeLieux,
        ]);
    }

    /**
     * @Route("/lieu/{id}", name="lieu_suppression")
     */
    public function deleteCampus(EntityManagerInterface $entityManager,
                                 Request $request,
                                 LieuRepository $lieuRepository,
                                 int $id
    ): Response
    {
        $lieu = $lieuRepository->findOneBy(['id' => $id]);
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){

            $entityManager->remove($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Le lieu de sortie a bien été supprimé !');

            return $this->redirectToRoute('lieu_affichage');
        }

        return $this->render('lieu/supprimerLieu.html.twig', [
            'lieuForm' => $lieuForm->createView(),
        ]);
    }




}
