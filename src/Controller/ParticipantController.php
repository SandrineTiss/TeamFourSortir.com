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
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('mc.baloons@gmail.com', 'Sortir.com'))
//                    ->to($user->getEmail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
            // do anything else you need here, like send an email



            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml

            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
