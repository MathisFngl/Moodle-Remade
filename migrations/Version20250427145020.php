<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427145020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cours (code VARCHAR(4) NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, responsable_ue INT NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE examen (id INT AUTO_INCREMENT NOT NULL, code_cours VARCHAR(4) DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, coeff INT DEFAULT NULL, bareme INT NOT NULL, INDEX IDX_514C8FEC119F95D0 (code_cours), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, examen_id INT NOT NULL, utilisateur_id INT NOT NULL, note DOUBLE PRECISION NOT NULL, INDEX IDX_CFBDFA145C8659A (examen_id), INDEX IDX_CFBDFA14FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT DEFAULT NULL, code_cours VARCHAR(4) DEFAULT NULL, INDEX IDX_D79F6B1150EAE44 (id_utilisateur), INDEX IDX_D79F6B11119F95D0 (code_cours), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', admin TINYINT(1) NOT NULL, photo LONGBLOB DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE examen ADD CONSTRAINT FK_514C8FEC119F95D0 FOREIGN KEY (code_cours) REFERENCES cours (code) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note ADD CONSTRAINT FK_CFBDFA145C8659A FOREIGN KEY (examen_id) REFERENCES examen (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1150EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11119F95D0 FOREIGN KEY (code_cours) REFERENCES cours (code) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE examen DROP FOREIGN KEY FK_514C8FEC119F95D0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA145C8659A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1150EAE44
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11119F95D0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cours
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE examen
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE note
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateur
        SQL);
    }
}
