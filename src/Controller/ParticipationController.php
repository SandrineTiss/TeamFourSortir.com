<?php

namespace App\Controller;

use App\Form\SortieType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\SortiesRepository;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/participation/", name="participation")
 */
class ParticipationController extends AbstractController
{
    /**
     * @Route("sortie/{id}", name="_sortie")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, int $id, SortiesRepository $sortiesRepository, EtatRepository $etatRepository): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $user = $this->getUser();
        $inscriptionForm = $this->createForm(SortieType::class, $sortie);
        $inscriptionForm->handleRequest($request);
        $nbreInscrits = sizeof($sortie->getInscrits());
        $nbMax = $sortie->getNbInscriptionMax();
        $inscrits = $sortie->getInscrits();
        $inscrit=0;
        $etat = $sortie->getEtat()->getLibelle();
        foreach($inscrits as $i)
        {
            if ($user == $i)
            {
                $inscrit=1;
            }
        }
        if( $etat == 'Ouverte')
        {
            //tester si la date d'inscription est passée et la mettre en Clôturée

            if($inscriptionForm->isSubmitted() && $inscriptionForm->isValid() && $nbMax > $nbreInscrits ){

                $sortie->addInscrit($user);

                if ($nbMax == sizeof($sortie->getInscrits()))
                {
                    $etat = $etatRepository->findOneBy(['libelle' => 'Cloturée']);
                    $sortie->setEtat($etat);
                }

                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success','Votre inscription a bien été prise en compte');
                return $this->redirectToRoute('main_accueil');
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
            } else{
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
        } elseif ($sortie->getOrganisateur() == $user && ($etat == 'Créée' || $etat == 'En cours')) {
            return $this->render('participation/inscription.html.twig', [
                'sortie' => $sortie,
                'sortieForm' =>  $inscriptionForm->createView(),
                'campus' => $sortie->getCampus(),
                'organisateur' => $sortie->getOrganisateur(),
                'inscrits' => $inscrits,
                'lieu' => $sortie->getLieu(),
                'ville' => $sortie->getLieu()->getVille(),
            ]);
        } else {
            if($etat == 'Créée')
            {
                $this->addFlash('danger','Cette sortie n\'est pas encore ouverte aux inscriptions');
                return $this->redirectToRoute('main_accueil');
            }
            if($etat == 'Cloturée' && $inscrit==1)
            {
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
            $this->addFlash('danger','Cette sortie est annulée');
            return $this->redirectToRoute('main_accueil');
        }




    }

    /**
     * @Route("sortie/desistement/{id}", name="_desistement")
     */
    public function desister(Request $request, EntityManagerInterface $entityManager, int $id, SortiesRepository $sortiesRepository, EtatRepository $etatRepository): Response
    {
        $sortie = $sortiesRepository->participate($id);
        $user = $this->getUser();

        $sortie->removeInscrit($user);
        $date = new \DateTime();
        $interval = date_diff($date, $sortie->getDateLimiteInscription());

        //dd($interval->format('%R%a')>0);
        if($interval->format('%R%a')>0)
        {
            $etat = $etatRepository->findOneBy(['libelle' => 'Ouverte']);
            $sortie->setEtat($etat);
        }

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success','Votre désistement a bien été pris en compte');
        return $this->redirectToRoute('main_accueil');

    }
}
