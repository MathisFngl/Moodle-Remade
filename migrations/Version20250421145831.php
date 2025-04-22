<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421145831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant CHANGE id_cours id_cours INT DEFAULT NULL, CHANGE id_utilisateur id_utilisateur INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1150EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11134FCDAC FOREIGN KEY (id_cours) REFERENCES cours (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D79F6B1150EAE44 ON participant (id_utilisateur)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D79F6B11134FCDAC ON participant (id_cours)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1150EAE44
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11134FCDAC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D79F6B1150EAE44 ON participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D79F6B11134FCDAC ON participant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant CHANGE id_utilisateur id_utilisateur INT NOT NULL, CHANGE id_cours id_cours INT NOT NULL
        SQL);
    }
}
