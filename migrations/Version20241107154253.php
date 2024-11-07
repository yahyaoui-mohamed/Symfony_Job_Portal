<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107154253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE skill ADD skill VARCHAR(1000) NOT NULL, DROP skills');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE password_reset DROP FOREIGN KEY FK_B1017252A76ED395');
        $this->addSql('DROP INDEX IDX_B1017252A76ED395 ON password_reset');
        $this->addSql('ALTER TABLE skill ADD skills JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP skill');
    }
}
