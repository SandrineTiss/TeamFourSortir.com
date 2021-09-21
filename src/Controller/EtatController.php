<?php

namespace App\Controller;

use App\Entity\Etat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtatController extends AbstractController
{
    /**
     * @Route("/etat", name="etat")
     */
    public function etat(EntityManagerInterface $entityManager): Response
    {

    }
}
