<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus_creation")
     */
    public function createCampus(EntityManagerInterface $entityManager,
                           Request $request,
                           CampusRepository $campusRepository
                            ): Response
    {
        $campus = new Campus();
        $campusForm = $this->createForm(CampusFormType::class, $campus);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){

            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau Campus a bien été créé !');

            return $this->redirectToRoute('campus_affichage');
        }

        return $this->render('campus/creerCampus.html.twig', [
            'campusForm' => $campusForm->createView(),
        ]);
    }

    /**
     * @Route("/campus/affichage", name="campus_affichage")
     */
    public function afficheCampus( CampusRepository $campusRepository  ): Response
    {
        $listeCampus = $campusRepository->findAll();
        return $this->render('campus/campus.html.twig', [
            'listeCampus' => $listeCampus,
        ]);
    }

    /**
     * @Route("/campus/{id}", name="campus_suppression")
     */
    public function deleteCampus(EntityManagerInterface $entityManager,
                                 Request $request,
                                 CampusRepository $campusRepository,
                                int $id
    ): Response
    {
        $campus = $campusRepository->findOneBy(['id' => $id]);
        $campusForm = $this->createForm(CampusFormType::class, $campus);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){

            $entityManager->remove($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Le campus a bien été supprimé !');

            return $this->redirectToRoute('campus_affichage');
        }

        return $this->render('campus/supprimerCampus.html.twig', [
            'campusForm' => $campusForm->createView(),
        ]);
    }

    /**
     * @Route("/campus/modifier/{id}", name="campus_modifier")
     */
    public function modifyCampus(EntityManagerInterface $entityManager,
                                 Request $request,
                                 CampusRepository $campusRepository,
                                 int $id
    ): Response
    {
        $campus = $campusRepository->findOneBy(['id' => $id]);
        $campusForm = $this->createForm(CampusFormType::class, $campus);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){

            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Le campus a bien été modifié !');

            return $this->redirectToRoute('campus_affichage');
        }

        return $this->render('campus/modifierCampus.html.twig', [
            'campusForm' => $campusForm->createView(),
        ]);
    }
}
