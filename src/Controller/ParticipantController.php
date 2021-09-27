<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\ImageRepository;
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
                             ImageRepository $imageRepository,
                             UserRepository $userRepository,
                             int $id,
                             AppAuthentificatorAuthenticator $authenticator): Response
    {
        $user = $userRepository->findOneBy(['id'=> $id]);
        $user->setPassword('pa$$w0rd');
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('participants_gestion');
        }

        return $this->render('participants/supprimerParticipant.html.twig', [
            'participant' => $form->createView(),
        ]);

    }

}
