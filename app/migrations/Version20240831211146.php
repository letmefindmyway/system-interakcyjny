<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831211146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books ADD creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A9261220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_4A1B2A9261220EA6 ON books (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9261220EA6');
        $this->addSql('DROP INDEX IDX_4A1B2A9261220EA6 ON books');
        $this->addSql('ALTER TABLE books DROP creator_id');
    }
}
