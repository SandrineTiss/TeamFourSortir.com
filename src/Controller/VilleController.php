<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\CampusFormType;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="ville_affichage")
     */
    public function afficheVilles( VilleRepository $villeRepository  ): Response
    {
        $listeVilles = $villeRepository->findAll();
        return $this->render('ville/ville.html.twig', [
            'listeVilles' => $listeVilles,
        ]);
    }

    /**
     * @Route("/ville/creation", name="ville_creation")
     */
    public function createVille(EntityManagerInterface $entityManager,
                                 Request $request
    ): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){

            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouvelle ville a bien été créée !');

            return $this->redirectToRoute('ville_affichage');
        }

        return $this->render('ville/creerVille.html.twig', [
            'villeForm' => $villeForm->createView(),
        ]);
    }

    /**
     * @Route("/ville/{id}", name="ville_suppression")
     */
    public function deleteVille(EntityManagerInterface $entityManager,
                                 Request $request,
                                 VilleRepository $villeRepository,
                                 int $id
    ): Response
    {
        $ville = $villeRepository->findOneBy(['id' => $id]);
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){

            $entityManager->remove($ville);
            $entityManager->flush();

            $this->addFlash('success', 'La ville a bien été supprimée !');

            return $this->redirectToRoute('ville_affichage');
        }

        return $this->render('ville/supprimerVille.html.twig', [
            'villeForm' => $villeForm->createView(),
        ]);
    }

    /**
     * @Route("/ville/modifier/{id}", name="ville_modification")
     */
    public function modifierVille(EntityManagerInterface $entityManager,
                                Request $request,
                                VilleRepository $villeRepository,
                                int $id
    ): Response
    {
        $ville = $villeRepository->findOneBy(['id' => $id]);
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){

            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'La ville a bien été modifiée !');

            return $this->redirectToRoute('ville_affichage');
        }

        return $this->render('ville/modifierVille.html.twig', [
            'villeForm' => $villeForm->createView(),
        ]);
    }
}
