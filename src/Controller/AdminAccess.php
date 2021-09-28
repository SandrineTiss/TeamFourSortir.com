<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminAccess extends AbstractController
{
    /**
     * @Route("/admin", name="admin_access")
     */
    public function accueil(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/gestion_utilisateur", name="gestion_utilisateur")
     */
    public function gestionUser(): Response
    {
        return $this->render('admin/gestion_utilisateur.html.twig');
    }
}