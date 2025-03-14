<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907013210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564C25F9B88 FOREIGN KEY (voteimage_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_5A108564C25F9B88 ON vote (voteimage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564C25F9B88');
        $this->addSql('DROP INDEX IDX_5A108564C25F9B88 ON vote');
    }
}
