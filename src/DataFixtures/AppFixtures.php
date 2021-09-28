<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\User;
use App\Entity\Image;
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

        $image=new Image();
        $image->setName('imageParDefaut.png');

        // ajout des user
        for ($i = 0; $i < 8; $i++){
            $user = new User($image);
            $user->setNom('user '.$i);
            $user->setPrenom('prenom '.$i);
            $user->setPseudo('pseudo'.$i);
            $user->setEmail('test'.$i.'@user.com');
            $user->setCampus($campus1);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'test'));
            $user->setRoles(["ROLE_USER"]);
            $user->setActif('true');
            $user->setTelephone(mt_rand(01, 100));
            $user->setIsVerified('true');
            $manager->persist($user);
            $manager->persist($image);
            $manager->flush($user);
            $manager->flush($image);
        }
        for ($i = 8; $i < 16; $i++){
            $user = new User($image);
            $user->setNom('user '.$i);
            $user->setPrenom('prenom '.$i);
            $user->setPseudo('pseudo'.$i);
            $user->setEmail('test'.$i.'@user.com');
            $user->setCampus($campus2);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'test'));
            $user->setRoles(["ROLE_USER"]);
            $user->setActif('true');
            $user->setTelephone(mt_rand(01, 100));
            $user->setIsVerified('true');
            $manager->persist($user);
            $manager->persist($image);
            $manager->flush($user);
            $manager->flush($image);
        }
        for ($i = 16; $i < 24; $i++){
            $user = new User($image);
            $user->setNom('user '.$i);
            $user->setPrenom('prenom '.$i);
            $user->setPseudo('pseudo'.$i);
            $user->setEmail('test'.$i.'@user.com');
            $user->setCampus($campus1);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'test'));
            $user->setRoles(["ROLE_USER"]);
            $user->setActif('true');
            $user->setTelephone(mt_rand(01, 100));
            $user->setIsVerified('true');
            $manager->persist($user);
            $manager->persist($image);
            $manager->flush($user);
            $manager->flush($image);
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
        for ($i = 0; $i < 5; $i++){
            $lieu = new Lieu();
            $lieu->setNom('lieu'.$i);
            $lieu->setVille($ville1);
            $lieu->setRue('rue_de_'.$i);
            $lieu->setLatitude(mt_rand(10,10000));
            $lieu->setLongitude(mt_rand(10,10000));
            $manager->persist($lieu);
            $manager->flush($lieu);
        }
        for ($i = 5; $i < 10; $i++){
            $lieu = new Lieu();
            $lieu->setNom('lieu'.$i);
            $lieu->setVille($ville2);
            $lieu->setRue('rue_de_'.$i);
            $lieu->setLatitude(mt_rand(10,10000));
            $lieu->setLongitude(mt_rand(10,10000));
            $manager->persist($lieu);
            $manager->flush($lieu);
        }
        for ($i = 10; $i < 15; $i++){
            $lieu = new Lieu();
            $lieu->setNom('lieu'.$i);
            $lieu->setVille($ville3);
            $lieu->setRue('rue_de_'.$i);
            $lieu->setLatitude(mt_rand(10,10000));
            $lieu->setLongitude(mt_rand(10,10000));
            $manager->persist($lieu);
            $manager->flush($lieu);
        }

        // Ajout de sorties
        for ($i = 5; $i < 15; $i++){
            $sortie = new Sorties();
            $sortie->setNom('nom_sortie'.$i);
            $sortie->setCampus($campus1);
            $sortie->setOrganisateur($user);
            $sortie->setLieu($lieu);
            $date = new \DateTime();
            $sortie->setDateHeureDebut($date->setDate(2021,10,5));
            $sortie->setDateLimiteInscription($date->setDate(2021,10,1));
            $sortie->setDuree(mt_rand(60, 240));
            $sortie->setEtat($etat1);
            $sortie->addInscrit($user);
            $sortie->setInfoSortie('une info sur la sortie_'.$i);
            $sortie->setNbInscriptionMax(mt_rand(1, 8));
            $sortie->setVille($ville1);
            $manager->persist($sortie);
            $manager->flush($sortie);
        }
        for ($i = 0; $i < 5; $i++){
            $sortie = new Sorties();
            $sortie->setNom('nom_sortie'.$i);
            $sortie->setCampus($campus1);
            $sortie->setOrganisateur($user);
            $sortie->setLieu($lieu);
            $date = new \DateTime();
            $sortie->setDateHeureDebut($date->setDate(2021,9,25));
            $sortie->setDateLimiteInscription($date->setDate(2021,9,20));
            $sortie->setDuree(mt_rand(60, 240));
            $sortie->setEtat($etat1);
            $sortie->addInscrit($user);
            $sortie->setInfoSortie('une info sur la sortie_'.$i);
            $sortie->setNbInscriptionMax(mt_rand(1, 8));
            $sortie->setVille($ville2);
            $manager->persist($sortie);
            $manager->flush($sortie);
        }
        for ($i = 0; $i < 5; $i++){
            $sortie = new Sorties();
            $sortie->setNom('nom_sortie'.$i);
            $sortie->setCampus($campus1);
            $sortie->setOrganisateur($user);
            $sortie->setLieu($lieu);
            $date = new \DateTime();
            $sortie->setDateHeureDebut($date->setDate(2021,9,29));
            $sortie->setDateLimiteInscription($date->setDate(2021,9,28));
            $sortie->setDuree(mt_rand(60, 240));
            $sortie->setEtat($etat1);
            $sortie->addInscrit($user);
            $sortie->setInfoSortie('une info sur la sortie_'.$i);
            $sortie->setNbInscriptionMax(mt_rand(1, 8));
            $sortie->setVille($ville3);
            $manager->persist($sortie);
            $manager->flush($sortie);
        }

    }
}
