<?php

namespace App\Controller;

use App\Entity\ProfilImage;
use App\Entity\User;
use App\Form\ParticipantType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\never;

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
        $form = $this->createForm(ParticipantType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // upload image de profil?
            if ($form->get('image')->getData()){

                // recupération de l'image uploadé
                $image = $form->get('image')->getData();
                $file = md5(uniqid()) . '.' . $image->guessExtension();

                // copie du fichier dans le dossier public/img/uploads/imageProfil
                $image->move(
                    $this->getParameter('profil_images'),
                    $file
                );

                $image = new Image();
                $image->setName($file);

                // récuperer l'image de profil
                $user->getImage();
                // update de l'image
                $user->getImage()->setName($file);
                $user->setImage($image);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($image);
                $entityManager->flush($image);
            }
            $entityManager->persist($user);
            $entityManager->flush($user);

            $this->addFlash('success', 'Votre profil a bien été modifié !');
            return $this->redirectToRoute('main_accueil');
        }
        return $this->render('profil/modifier_profil.html.twig', [
            'profilForm' => $form->createView(),
            'user' => $user,
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