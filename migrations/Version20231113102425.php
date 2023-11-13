<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113102425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_score (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, score_champion_easy INT NOT NULL, score_champion_hard INT NOT NULL, score_object INT NOT NULL, INDEX IDX_AA4EDEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_score ADD CONSTRAINT FK_AA4EDEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user DROP score');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_score DROP FOREIGN KEY FK_AA4EDEA76ED395');
        $this->addSql('DROP TABLE game_score');
        $this->addSql('ALTER TABLE `user` ADD score INT DEFAULT 0 NOT NULL');
    }
}
