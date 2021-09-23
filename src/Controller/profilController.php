<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class profilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil_user")
     */
    public function profil(): Response
    {
        return $this->render('profil/profilUser.html.twig');
    }
}