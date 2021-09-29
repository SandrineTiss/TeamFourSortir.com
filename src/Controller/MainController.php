<?php

namespace App\Controller;


use App\Entity\SortiesArchivees;
use App\Entity\SortieSearch;
use App\Form\SortieSearchType;
use App\Repository\SortiesArchiveesRepository;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_accueil")
     */
    public function accueil(SortiesRepository $sortiesRepository, Request $request, SortiesArchiveesRepository $sortiesArchiveesRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {

            //requete SQL pour obtenir la liste des sorties à archiver (+ de 30 jours)
            $sortiesAArchiver = $sortiesRepository->archivage();
            if($sortiesAArchiver != null) {
                foreach ($sortiesAArchiver as $s) {
                    // pour chaque sortie à archiver
                    $sortieArchive = new SortiesArchivees();
                    // archivage de la sortie dans la table SortiesArchivees
                    $entityManager->persist($sortieArchive);
                    $entityManager->flush();
                    // supression de la sortie archivée de la table Sorties
                    $entityManager->remove($s);
                    $entityManager->flush();
                }
            }

            // requete pour l'affichage des sorties non archivées sur la page d'accueil
            $search = new SortieSearch();

            //création du formulaire de recherche avec filtres
            $form = $this->createForm(SortieSearchType::class, $search);
            $form->handleRequest($request);
            $sortie = $sortiesRepository->findAllRecents();

            // Lorsque l'utilisateur fait une recherche filtrée
            if($form->isSubmitted())
            {
                $user = $this->getUser();
                $sortie = $sortiesRepository->findByFilters($search, $user);
            }
            return $this->render('main/accueil.html.twig', [
                'sorties' => $sortie,
                'form' => $form->createView(),
            ]);
            // sinon, affichage de l'accueil standard avec liste non filtrée
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
