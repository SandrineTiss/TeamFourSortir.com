<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ParticipantType;
use App\Form\RegistrationFormType;
use App\Repository\EtatRepository;
use App\Repository\ImageRepository;
use App\Repository\SortiesRepository;
use App\Repository\UserRepository;
use App\Security\AppAuthentificatorAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participants", name="participants_gestion")
     */
    public function affichageParticipants(UserRepository $participantsrepository): Response
    {
        $participants = $participantsrepository->findAll();
        return $this->render('participants/gestionParticipants.html.twig', [
            'participants' => $participants,
        ]);
    }

    /**
     * @Route("/participants/supprimer/{id}", name="participants_suppression")
     */
    public function supprimerParticipant(Request $request,
                             UserRepository $userRepository,
                             int $id
    ): Response
    {
        $participant = $userRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Le participant a été supprimé');
            return $this->redirectToRoute('participants_gestion');
        }

        return $this->render('participants/supprimerParticipant.html.twig', [
            'participant' => $form->createView(),
        ]);

    }

    /**
     * @Route("/participants/sommeil/{id}", name="participants_sommeil")
     */
    public function sommeilParticipant(Request $request,
                                         UserRepository $userRepository,
                                         SortiesRepository $sortiesRepository,
                                         EtatRepository $etatRepository,
                                         int $id
                                         ): Response
    {
        $participant = $userRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $participant->setActif(false);
            $entityManager->persist($participant);
            $annulations = $participant->getEstOrganisateur();
            $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
            foreach ($annulations as $sortie)
            {
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Le participant '.$participant->getNom().' '.$participant->getPrenom().' a été mis en sommeil');
            return $this->redirectToRoute('participants_gestion');
        }

        return $this->render('participants/sommeilParticipant.html.twig', [
            'participant' => $form->createView(),
        ]);
    }

        /**
         * @Route("/participants/reactiver/{id}", name="participants_reactivation")
         */
        public function activationParticipant(Request $request,
                                           UserRepository $userRepository,
                                           int $id
        ): Response
        {
        $participant = $userRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $participant->setActif(true);
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Le participant '.$participant->getNom().' '.$participant->getPrenom().' a été réactivé');
            return $this->redirectToRoute('participants_gestion');
        }

        return $this->render('participants/reactivationParticipant.html.twig', [
            'participant' => $form->createView(),
        ]);

    }

    /**
     * @Route("/participants/modifier/{id}", name="participants_modification")
     */
    public function modifierParticipant(Request $request,
                                         UserRepository $userRepository,
                                         int $id
    ): Response
    {
        $participant = $userRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Le participant '.$participant->getNom().' '.$participant->getPrenom().' a été modifié avec succès');
            return $this->redirectToRoute('participants_gestion');
        }

        return $this->render('participants/modifierParticipant.html.twig', [
            'participant' => $form->createView(),
        ]);

    }

}
