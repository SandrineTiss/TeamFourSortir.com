<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922082640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sorties CHANGE duree duree INT NOT NULL, CHANGE date_limite_inscription date_limite_inscription DATE NOT NULL, CHANGE nb_inscription_max nb_inscription_max INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sorties CHANGE duree duree TIME DEFAULT NULL, CHANGE date_limite_inscription date_limite_inscription DATETIME NOT NULL, CHANGE nb_inscription_max nb_inscription_max INT DEFAULT NULL');
    }
}
