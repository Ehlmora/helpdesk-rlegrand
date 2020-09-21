<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200630121620 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket CHANGE user_id user_id INT DEFAULT NULL, CHANGE ticket_state_id ticket_state_id INT DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE ticket_log MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket_log DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ticket_log CHANGE ticket_id ticket_id INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C46B3E9700047D2 ON ticket_log (ticket_id)');
        $this->addSql('ALTER TABLE ticket_log ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user CHANGE role_id role_id INT DEFAULT NULL, CHANGE phone phone VARCHAR(13) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE ticket CHANGE user_id user_id INT NOT NULL, CHANGE ticket_state_id ticket_state_id INT DEFAULT 1 NOT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket_log MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8C46B3E9700047D2 ON ticket_log');
        $this->addSql('ALTER TABLE ticket_log DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ticket_log CHANGE ticket_id ticket_id INT NOT NULL, CHANGE updated_by updated_by INT NOT NULL');
        $this->addSql('ALTER TABLE ticket_log ADD PRIMARY KEY (id, ticket_id)');
        $this->addSql('ALTER TABLE user CHANGE role_id role_id INT NOT NULL, CHANGE phone phone VARCHAR(13) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`');
    }
}
