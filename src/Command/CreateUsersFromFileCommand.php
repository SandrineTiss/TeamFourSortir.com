<?php

namespace App\Command;

use App\Entity\Image;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CreateUsersFromFileCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private string $dataDirectory;
    private SymfonyStyle $io;
    private UserRepository $userRepository;

    protected static $defaultName = 'app:create-users-from-file';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory, UserRepository $userRepository)
    {
        parent::__construct();
        $this->dataDirectory = $dataDirectory;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Importer des utilisateurs depuis un fichier CSV')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this-> createUsers();
        return Command::SUCCESS;
    }


    private function getDataFromFile(): array
    {
        $file = $this->dataDirectory . 'random.csv';

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        $normalizers = [new ObjectNormalizer()];
        $encoders = [
            new CsvEncoder(),
            new XmlEncoder(),
            new YamlEncoder()
        ];

        $serializer = new Serializer($normalizers, $encoders);

        /**
         * @var string $fileString
         */
        $fileString = file_get_contents($file);

        $data = $serializer->decode($fileString, $fileExtension);

        return $data;
    }

    private function createUsers(): void
    {

        $this->io->section('CREATION DES UTILISATEURS A PARTIR DU FICHIER');
        // variable incrémenté pour retour du nombre d'utilisateurs créés
        $userCreated = 0;


        // vérification contraint d'unicité
        foreach ($this->getDataFromFile() as $row){
            if(array_key_exists('email', $row) && !empty($row['email'])) {
                $user = $this->userRepository->findOneBy([
                    'email' => $row['email']
                ]);

                // si contraintes d'unicités respectées alors création de l'utilisateur
                if (!$user){
                    $user = new User();
                    $user->setEmail($row['email']) // unique
                        ->setCampus($row['campus'])
                        ->setImage(new Image('imageParDefaut.png')) // image par defaut à la creation
                        ->setNom($row['name'])
                        ->setPrenom($row['prenom'])
                        // 10 charactere Maj - min - chiffre - caractere spec
                        ->setPassword($row['password'])
                        ->setPseudo($row['pseudo']) // unique
                        ->setActif(true)
                        ->setIsVerified(true)
                        ->setRoles(['ROLE_USER'])
                        ->setTelephone($row['phone']); // unique

                    $this->entityManager->persist($user);
                    $userCreated++;
                }
            }
        }
        $this->entityManager->flush();

        // message retour sur les utilisateurs enregistrés en base de données
        if ($userCreated > 1){
            $string = "{$userCreated} UTILISATEURS CREES EN BASE DE DONNEES.";
        } elseif ($userCreated === 1){
            $string = '1 UTILISATEUR A ETE CREE EN BASE DE DONNEES.';
        } else {
            $string = 'AUCUN UTILISATEUR CREE EN BASE DE DONNEES.';
        }

        $this->io->success($string);
    }
}
