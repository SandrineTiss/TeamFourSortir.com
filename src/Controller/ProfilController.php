<?php

namespace App\Controller;

use App\Entity\ProfilImage;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil_user")
     */
    public function profil(): Response
    {
        return $this->render('profil/profilUser.html.twig');
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