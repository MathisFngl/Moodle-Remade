<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422211956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1150EAE44
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11134FCDAC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D79F6B11134FCDAC ON participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D79F6B1150EAE44 ON participant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD cours_id INT NOT NULL, ADD utilisateur_id INT NOT NULL, DROP id_cours, DROP id_utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B117ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D79F6B117ECF78B0 ON participant (cours_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D79F6B11FB88E14F ON participant (utilisateur_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B117ECF78B0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D79F6B117ECF78B0 ON participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D79F6B11FB88E14F ON participant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD id_cours INT NOT NULL, ADD id_utilisateur INT NOT NULL, DROP cours_id, DROP utilisateur_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1150EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11134FCDAC FOREIGN KEY (id_cours) REFERENCES cours (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D79F6B11134FCDAC ON participant (id_cours)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D79F6B1150EAE44 ON participant (id_utilisateur)
        SQL);
    }
}
