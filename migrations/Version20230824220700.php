<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824220700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chef CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE specialite specialite VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE numero_telephone numero_telephone INT DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chef CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE specialite specialite VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE numero_telephone numero_telephone INT NOT NULL, CHANGE photo photo VARCHAR(255) NOT NULL');
    }
}
