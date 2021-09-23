<?php

namespace App\Controller;

use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\SortiesRepository;

/**
 * @Route("/participation/", name="participation")
 */
class ParticipationController extends AbstractController
{
    /**
     * @Route("sortie/{id}", name="_sortie")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $user = $this->getUser();
        $inscriptionForm = $this->createForm(SortieType::class, $sortie);
        $inscriptionForm->handleRequest($request);

        $nbreInscrits = sizeof($sortie->getInscrits());
        $nbMax = $sortie->getNbInscriptionMax();
        $inscrits = $sortie->getInscrits();


        if($inscriptionForm->isSubmitted() && $inscriptionForm->isValid() && $nbMax > $nbreInscrits ){

            $sortie->addInscrit($user);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Votre inscription a bien été prise en compte');
            return $this->redirectToRoute('main_accueil');
        }
        if($inscriptionForm->isSubmitted() && $inscriptionForm->isValid() ){

            $sortie->removeInscrit($user);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Votre inscription a bien été prise en compte');
            return $this->redirectToRoute('participation_sortie');
        }
        elseif ( $nbMax > $nbreInscrits ) {
            return $this->render('participation/inscription.html.twig', [
                'sortie' => $sortie,
                'sortieForm' =>  $inscriptionForm->createView(),
                'campus' => $sortie->getCampus(),
                'organisateur' => $sortie->getOrganisateur(),
                'inscrits' => $inscrits,
                'lieu' => $sortie->getLieu(),
                'ville' => $sortie->getLieu()->getVille(),
            ]);
        }
        else{
            $this->addFlash('warning','Cette sortie a déjà atteint son maximum de participants');
            return $this->render('participation/inscription.html.twig', [
                'sortie' => $sortie,
                'sortieForm' =>  $inscriptionForm->createView(),
                'campus' => $sortie->getCampus(),
                'organisateur' => $sortie->getOrganisateur(),
                'inscrits' => $inscrits,
                'lieu' => $sortie->getLieu(),
                'ville' => $sortie->getLieu()->getVille(),
            ]);
        }


    }

    /**
     * @Route("sortie/desistement/{id}", name="_desistement")
     */
    public function desister(Request $request, EntityManagerInterface $entityManager, int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $user = $this->getUser();

        $sortie->removeInscrit($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success','Votre désistement a bien été pris en compte');
        return $this->redirectToRoute('main_accueil');




    }
}
