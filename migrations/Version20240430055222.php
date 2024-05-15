<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430055222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, fk_team_id INT DEFAULT NULL, fk_user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, summary LONGTEXT NOT NULL, content LONGTEXT NOT NULL, picture VARCHAR(255) DEFAULT NULL, date_add DATE NOT NULL, INDEX IDX_885DBAFAD943E582 (fk_team_id), INDEX IDX_885DBAFA5741EEB9 (fk_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAD943E582 FOREIGN KEY (fk_team_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C9B33C88');
        $this->addSql('DROP INDEX IDX_1483A5E9C9B33C88 ON users');
        $this->addSql('ALTER TABLE users CHANGE fk_coutry_id fk_country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9941F4E3 FOREIGN KEY (fk_country_id) REFERENCES countries (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9941F4E3 ON users (fk_country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAD943E582');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA5741EEB9');
        $this->addSql('DROP TABLE posts');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9941F4E3');
        $this->addSql('DROP INDEX IDX_1483A5E9941F4E3 ON users');
        $this->addSql('ALTER TABLE users CHANGE fk_country_id fk_coutry_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C9B33C88 FOREIGN KEY (fk_coutry_id) REFERENCES countries (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1483A5E9C9B33C88 ON users (fk_coutry_id)');
    }
}
