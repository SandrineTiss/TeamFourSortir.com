<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210929154225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sorties_archivees (id INT AUTO_INCREMENT NOT NULL, etat_id INT NOT NULL, campus_id INT NOT NULL, lieu_id INT NOT NULL, organisateur_id INT NOT NULL, ville_id INT NOT NULL, nom VARCHAR(255) NOT NULL, date_heure_debut DATETIME NOT NULL, duree INT NOT NULL, date_limite_inscription DATE NOT NULL, nb_inscription_max INT NOT NULL, info_sortie LONGTEXT NOT NULL, INDEX IDX_BC437F9BD5E86FF (etat_id), INDEX IDX_BC437F9BAF5D55E1 (campus_id), INDEX IDX_BC437F9B6AB213CC (lieu_id), INDEX IDX_BC437F9BD936B2FA (organisateur_id), INDEX IDX_BC437F9BA73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sorties_archivees_user (sorties_archivees_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7F90D2B4D075E78E (sorties_archivees_id), INDEX IDX_7F90D2B4A76ED395 (user_id), PRIMARY KEY(sorties_archivees_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sorties_archivees ADD CONSTRAINT FK_BC437F9BD5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE sorties_archivees ADD CONSTRAINT FK_BC437F9BAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE sorties_archivees ADD CONSTRAINT FK_BC437F9B6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE sorties_archivees ADD CONSTRAINT FK_BC437F9BD936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sorties_archivees ADD CONSTRAINT FK_BC437F9BA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE sorties_archivees_user ADD CONSTRAINT FK_7F90D2B4D075E78E FOREIGN KEY (sorties_archivees_id) REFERENCES sorties_archivees (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sorties_archivees_user ADD CONSTRAINT FK_7F90D2B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sorties_archivees_user DROP FOREIGN KEY FK_7F90D2B4D075E78E');
        $this->addSql('DROP TABLE sorties_archivees');
        $this->addSql('DROP TABLE sorties_archivees_user');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone INT NOT NULL');
    }
}
