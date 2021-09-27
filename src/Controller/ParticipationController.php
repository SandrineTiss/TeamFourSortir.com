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
        $inscrit = 0;
        $etat = $sortie->getEtat()->getLibelle();
        foreach ($inscrits as $i) {
            if ($user == $i) {
                $inscrit = 1;
            }
        }
        if ($etat == 'Créée') {
            $this->addFlash('danger', 'Cette sortie n\'est pas encore ouverte aux inscriptions');
        }
        $date = new \DateTime();
        $interval = date_diff($date, $sortie->getDateLimiteInscription());
        $intervalEnCours = date_diff($date, $sortie->getDateHeureDebut());
        $duree = new \DateInterval('PT'.$sortie->getduree().'M');
        $dateFinSortie = date_add($sortie->getDateHeureDebut(), $duree);
        $intervalFinSortie = date_diff($date, $dateFinSortie);
        //tester si la date d'inscription est passée et la mettre en Clôturée
        if ($interval->format('%R%a') < 0 && $etat != 'Créée' && $etat != 'Passée' ) {
            $etat = $etatRepository->findOneBy(['libelle' => 'Cloturée']);
            $sortie->setEtat($etat);
            $this->addFlash('danger', 'Cette sortie n\'accepte plus les inscriptions !');
        }
        // tester si date et heure de fin de sortie passée
        if ($intervalFinSortie->format('%R%a') < 0 && $etat != 'Créée' ) {
            $etat = $etatRepository->findOneBy(['libelle' => 'Passée']);
            $sortie->setEtat($etat);
            $this->addFlash('info', 'Cette sortie est passée');
        }
        //tester si la sortie est en cours et la mettre En cours
        if ($intervalEnCours->format('%R%a') == 0 && $etat != 'Créée') {
            $etat = $etatRepository->findOneBy(['libelle' => 'En cours']);
            $sortie->setEtat($etat);
            $this->addFlash('info', 'Cette sortie est en cours');
        }
        if($etat == 'Annulée')
        {
            $this->addFlash('danger', 'Cette sortie est annulée');
        }
        $entityManager->persist($sortie);
        $entityManager->flush();
        if ($etat == 'Ouverte') {

            if ($inscriptionForm->isSubmitted() && $inscriptionForm->isValid() && $nbMax > $nbreInscrits) {
                $sortie->addInscrit($user);
                $nbreInscrits = sizeof($sortie->getInscrits());
                if ($nbMax == $nbreInscrits) {
                    $etat = $etatRepository->findOneBy(['libelle' => 'Cloturée']);
                    $sortie->setEtat($etat);
                }
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Votre inscription a bien été prise en compte');
                return $this->redirectToRoute('main_accueil');
            } elseif ($nbMax > $nbreInscrits) {
                return $this->render('participation/inscription.html.twig', [
                    'sortie' => $sortie,
                    'sortieForm' => $inscriptionForm->createView(),
                    'campus' => $sortie->getCampus(),
                    'organisateur' => $sortie->getOrganisateur(),
                    'inscrits' => $inscrits,
                    'lieu' => $sortie->getLieu(),
                    'ville' => $sortie->getLieu()->getVille(),
                ]);
            } else {
                $this->addFlash('warning', 'Cette sortie a déjà atteint son maximum de participants');
                return $this->render('participation/inscription.html.twig', [
                    'sortie' => $sortie,
                    'sortieForm' => $inscriptionForm->createView(),
                    'campus' => $sortie->getCampus(),
                    'organisateur' => $sortie->getOrganisateur(),
                    'inscrits' => $inscrits,
                    'lieu' => $sortie->getLieu(),
                    'ville' => $sortie->getLieu()->getVille(),
                ]);
            }
        }
        elseif ($sortie->getOrganisateur() == $user && ($etat == 'Créée' || $etat == 'En cours')) {
            return $this->render('participation/inscription.html.twig', [
                'sortie' => $sortie,
                'sortieForm' => $inscriptionForm->createView(),
                'campus' => $sortie->getCampus(),
                'organisateur' => $sortie->getOrganisateur(),
                'inscrits' => $inscrits,
                'lieu' => $sortie->getLieu(),
                'ville' => $sortie->getLieu()->getVille(),
            ]);
        }
        elseif ($etat == 'Cloturée' && $inscrit == 1) {
            return $this->render('participation/inscription.html.twig', [
                'sortie' => $sortie,
                'sortieForm' => $inscriptionForm->createView(),
                'campus' => $sortie->getCampus(),
                'organisateur' => $sortie->getOrganisateur(),
                'inscrits' => $inscrits,
                'lieu' => $sortie->getLieu(),
                'ville' => $sortie->getLieu()->getVille(),
            ]);
        }

        return $this->render('sortie/detailSortie.html.twig', [
            'sortie' => $sortie,
            'etat'=> $sortie->getEtat(),
            'sortieForm' => $inscriptionForm->createView(),
            'campus' => $sortie->getCampus(),
            'organisateur' => $sortie->getOrganisateur(),
            'inscrits' => $inscrits,
            'lieu' => $sortie->getLieu(),
            'ville' => $sortie->getLieu()->getVille(),
        ]);
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
