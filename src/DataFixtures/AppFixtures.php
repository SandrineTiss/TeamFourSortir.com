<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // ajout des campus
        $campus1 = new Campus();
        $campus1->setNom('Nantes');
        $campus2 = new Campus();
        $campus2->setNom('Rennes');
        $campus3 = new Campus();
        $campus3->setNom('La Roche sur Yon');
        $manager->persist($campus1);
        $manager->persist($campus2);
        $manager->persist($campus3);
        $manager->flush($campus1);
        $manager->flush($campus2);
        $manager->flush($campus3);

        // ajout des user
        for ($i = 0; $i < 20; $i++){
            $user = new User();
            $user->setNom('user '.$i);
            $user->setPrenom('prenom '.$i);
            $user->setPseudo('pseudo'.$i);
            $user->setEmail('test@user'.$i);
            $user->setCampus($campus1);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'test'));
            $user->setRoles(["ROLE_USER"]);
            $user->setActif('true');
            $user->setAdmin('false');
            $user->setTelephone(mt_rand(01, 06).mt_rand(10, 100).mt_rand(10, 100).mt_rand(10, 100).mt_rand(10, 100));
            $user->setIsVerified('true');


            $manager->persist($user);
            $manager->flush($user);
        }


        // ajout des villes
        $ville1 = new Ville();
        $ville1->setNom('Nantes');
        $ville1->setCodePostal(44000);
        $ville2 = new Ville();
        $ville2->setNom('Rennes');
        $ville2->setCodePostal(35000);
        $ville3 = new Ville();
        $ville3->setNom('La Roche sur Yon');
        $ville3->setCodePostal(85000);
        $manager->persist($ville1);
        $manager->persist($ville2);
        $manager->persist($ville3);
        $manager->flush($ville1);
        $manager->flush($ville2);
        $manager->flush($ville3);

        // ajout des etat
        $etat1 = new Etat();
        $etat1->setLibelle('Créée');
        $etat2 = new Etat('Ouverte');
        $etat2->setLibelle('Ouverte');
        $etat3 = new Etat('Cloturée');
        $etat3->setLibelle('Cloturée');
        $etat4 = new Etat('Passée');
        $etat4->setLibelle('Passée');
        $etat5 = new Etat('En cours');
        $etat5->setLibelle('En cours');
        $etat6 = new Etat('Annulée');
        $etat6->setLibelle('Annulée');
        $manager->persist($etat1);
        $manager->persist($etat2);
        $manager->persist($etat3);
        $manager->persist($etat4);
        $manager->persist($etat5);
        $manager->persist($etat6);
        $manager->flush($etat1);
        $manager->flush($etat2);
        $manager->flush($etat3);
        $manager->flush($etat4);
        $manager->flush($etat5);
        $manager->flush($etat6);

        // Ajout des Lieu
        for ($i = 0; $i < 20; $i++){
            $lieu = new Lieu();
            $lieu->setNom('lieu'.$i);
            $lieu->setVille($ville1);
            $lieu->setRue('rue_de_'.$i);
            $lieu->setLatitude(mt_rand(10,10000));
            $lieu->setLongitude(mt_rand(10,10000));
            $manager->persist($lieu);
            $manager->flush($lieu);
        }

        // Ajout de sorties
        for ($i = 0; $i < 20; $i++){
            $sortie = new Sorties();
            $sortie->setNom('nom_sortie'.$i);
            $sortie->setCampus($campus1);
            $sortie->setOrganisateur($user);
            $sortie->setLieu($lieu);
            $date = new \DateTime();
            $sortie->setDateHeureDebut($date->setDate(2021,10,01));
            $sortie->setDateLimiteInscription($date->setDate(2021,9,28));
            $sortie->setDuree(mt_rand(60, 240));
            $sortie->setEtat($etat1);
            $sortie->addInscrit($user);
            $sortie->setInfoSortie('une info sur la sortie_'.$i);
            $sortie->setNbInscriptionMax(2);
            $manager->persist($sortie);
            $manager->flush($sortie);
        }

    }
}
