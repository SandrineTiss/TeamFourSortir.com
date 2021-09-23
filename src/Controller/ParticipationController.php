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
     * @Route("inscription/{id}", name="_inscription")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $user = $this->getUser();
        $inscriptionForm = $this->createForm(SortieType::class, $sortie);
        $inscriptionForm->handleRequest($request);

        $nbreInscrits = sizeof($sortie->getInscrits());
        $nbMax = $sortie->getNbInscriptionMax();



        if($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()){
            $sortie->addInscrit($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Votre inscription a bien été prise en compte');
            return $this->render('sortie/liste.html.twig');
        }
        elseif ( $nbMax > $nbreInscrits ) {
            return $this->render('participation/inscription.html.twig', [
                'sortie' => $sortie,
                'sortieForm' =>  $inscriptionForm->createView(),
                'organisateur' => $sortie->getOrganisateur(),
                'inscrits' => $sortie->getInscrits(),
                'lieu' => $sortie->getLieu(),
                'ville' => $sortie->getLieu()->getVille(),
            ]);
        }
        else{
            return $this->render('main/accueil.html.twig', [
                'message' => 'Cette sortie a déjà atteint le nombre maximum de participants'
            ]);
        }


    }
}
