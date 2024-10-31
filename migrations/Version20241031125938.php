<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031125938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE saved (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, job_id INT NOT NULL, INDEX IDX_F9E3D325A76ED395 (user_id), INDEX IDX_F9E3D325BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE saved ADD CONSTRAINT FK_F9E3D325A76ED395 FOREIGN KEY (user_id) REFERENCES `User` (id)');
        $this->addSql('ALTER TABLE saved ADD CONSTRAINT FK_F9E3D325BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE saved DROP FOREIGN KEY FK_F9E3D325A76ED395');
        $this->addSql('ALTER TABLE saved DROP FOREIGN KEY FK_F9E3D325BE04EA9');
        $this->addSql('DROP TABLE saved');
    }
}
