<?php

namespace App\Controller;

use App\Entity\ProfilImage;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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


//        $entityManager = $this->getDoctrine()->getManager();
//        $repository = $entityManager->getRepository(User::class);
//        $user = $repository->find($id);
//        $profilPic = $user->getImage();
//
//        $file = $form['image']->getData();
//
//        if (is_string($file)) {
//            $fileName = $user->getImage()->getName();
//
//        $form = $this->createForm(RegistrationFormType::class, $user);
//        $form->handleRequest($request);
//
//        if ($request->isMethod('post') && $form->isValid()){
//            //insertion en BDD
//
//        }

        //TODO: ajouter enregistrement en BDD des modif profil

        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
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

                $image = new ProfilImage();
                $image->setName($file);
                $image->setUtilisateur($user);

                // récuperer l'image de profil
                $user->getImage();
                // update de l'image
                $user->getImage()->setName($file);

                $user->setImage($image);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush($user);

            }
//            else
//                //traitement form
//
//            // generation nom fichier
//            if($image != null)
//            {
//                $file = md5(uniqid()) . '.' . $image->guessExtension();
//                // copie du fichier dans le dossier public/img/uploads/imageProfil
//                $image->move(
//                    $this->getParameter('profil_images'),
//                    $file
//                );
//                // stocker le nom du fichier dans la BDD
//                $img = new ProfilImage();
//                $img->setName($file);
//                $img->getUtilisateur();
//                $entityManager = $this->getDoctrine()->getManager();
//                $entityManager->persist($img);
//                $entityManager->flush($img);
//                $user->addImage($img);
//            }
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($user);
//            $entityManager->flush();

        }
        return $this->render('profil/modifier_profil.html.twig', [
            'registrationForm' => $form->createView(),
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