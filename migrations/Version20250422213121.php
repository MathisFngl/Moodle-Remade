<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422213121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B117ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE
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
    }
}
