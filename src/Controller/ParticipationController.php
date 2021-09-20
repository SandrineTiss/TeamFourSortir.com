<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Sorties;
use App\Entity\User;
use App\Repository\SortiesRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/participation/", name="participation")
 */
class ParticipationController extends AbstractController
{
    private $security;

    /**
     * @Route("inscription/{id}", name="_inscription")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->find($id);
        $inscriptionForm = $this->createForm(Sorties::class,$sortie);
        $inscriptionForm->handleRequest($request);
        $user = $this->security->getUser();

        if($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()){
            $sortie->addInscrit($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Votre inscription a bien été prise en compte');
        }

        return $this->render('participation/inscription.html.twig', [
            'sortie' => $inscriptionForm->createView(),
        ]);
    }
}
