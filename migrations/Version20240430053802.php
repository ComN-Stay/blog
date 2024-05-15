<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430053802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, fk_gender_id INT DEFAULT NULL, fk_coutry_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(150) NOT NULL, lastname VARCHAR(150) NOT NULL, username VARCHAR(150) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(15) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, INDEX IDX_1483A5E966517770 (fk_gender_id), INDEX IDX_1483A5E9C9B33C88 (fk_coutry_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E966517770 FOREIGN KEY (fk_gender_id) REFERENCES genders (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C9B33C88 FOREIGN KEY (fk_coutry_id) REFERENCES countries (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E966517770');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C9B33C88');
        $this->addSql('DROP TABLE users');
    }
}
