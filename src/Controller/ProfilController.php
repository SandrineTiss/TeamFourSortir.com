<?php

namespace App\Controller;

use App\Entity\ProfilImage;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{

    /**
     * @Route("/profil", name="profil_user")
     * @return Response
     */
    public function modifierProfil(EntityManagerInterface $entityManager,
                                   Request $request): Response

    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // recupération de l'image de profil

            $image = $form->get('image')->getData();
            // generation nom fichier
            $file = md5(uniqid()) . '.' . $image->guessExtension();
            // copie du fichier dans le dossier public/img/uploads/imageProfil
            $image->move(
                $this->getParameter('profil_images'),
                $file
            );

            // stocker le nom du fichier dans la BDD
            $img = new ProfilImage();
            $img->setName($file);
            $img->getUtilisateur();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($img);
            $entityManager->flush($img);
            $user->addImage($img);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        }
        return $this->render('profil/modifier_profil.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/profil/{id}", name="profil_participant")
     */
    public function profilParticipant(int $id, UserRepository $userRepository) : Response
    {
        $participant = $userRepository->find($id);
        return $this->render('participation/profil.html.twig',[
            'participant' => $participant,
        ]);
    }

}