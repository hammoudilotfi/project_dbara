<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824230030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564FEDEAEF2');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085644FA90DB2');
        $this->addSql('DROP INDEX IDX_5A108564FEDEAEF2 ON vote');
        $this->addSql('DROP INDEX IDX_5A1085644FA90DB2 ON vote');
        $this->addSql('ALTER TABLE vote DROP abonnes_id, DROP optione_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote ADD abonnes_id INT DEFAULT NULL, ADD optione_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564FEDEAEF2 FOREIGN KEY (abonnes_id) REFERENCES abonnes (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085644FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id)');
        $this->addSql('CREATE INDEX IDX_5A108564FEDEAEF2 ON vote (abonnes_id)');
        $this->addSql('CREATE INDEX IDX_5A1085644FA90DB2 ON vote (optione_id)');
    }
}
