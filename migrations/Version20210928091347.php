<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928091347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sorties ADD ville_id INT NOT NULL');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_488163E8A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_488163E8A73F0036 ON sorties (ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sorties DROP FOREIGN KEY FK_488163E8A73F0036');
        $this->addSql('DROP INDEX IDX_488163E8A73F0036 ON sorties');
        $this->addSql('ALTER TABLE sorties DROP ville_id');
    }
}
