<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210164201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_note ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_note ADD CONSTRAINT FK_7FFF7D157294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_7FFF7D157294869C ON article_note (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_note DROP FOREIGN KEY FK_7FFF7D157294869C');
        $this->addSql('DROP INDEX IDX_7FFF7D157294869C ON article_note');
        $this->addSql('ALTER TABLE article_note DROP article_id');
    }
}
