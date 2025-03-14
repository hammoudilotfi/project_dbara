<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825114124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE savednote ADD dbaretelchef_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE savednote ADD CONSTRAINT FK_66ACC4E6AEC18D5B FOREIGN KEY (dbaretelchef_id) REFERENCES dbaretelchef (id)');
        $this->addSql('CREATE INDEX IDX_66ACC4E6AEC18D5B ON savednote (dbaretelchef_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE savednote DROP FOREIGN KEY FK_66ACC4E6AEC18D5B');
        $this->addSql('DROP INDEX IDX_66ACC4E6AEC18D5B ON savednote');
        $this->addSql('ALTER TABLE savednote DROP dbaretelchef_id');
    }
}
