<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825112519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE savednote ADD abonnees_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE savednote ADD CONSTRAINT FK_66ACC4E65B9C9299 FOREIGN KEY (abonnees_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_66ACC4E65B9C9299 ON savednote (abonnees_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE savednote DROP FOREIGN KEY FK_66ACC4E65B9C9299');
        $this->addSql('DROP INDEX IDX_66ACC4E65B9C9299 ON savednote');
        $this->addSql('ALTER TABLE savednote DROP abonnees_id');
    }
}
