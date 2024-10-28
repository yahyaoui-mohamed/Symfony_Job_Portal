<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028190251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applications (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, job_id INT NOT NULL, applied_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F7C966F0A76ED395 (user_id), INDEX IDX_F7C966F0BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F0BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64929A0022');
        $this->addSql('DROP INDEX IDX_8D93D64929A0022 ON user');
        $this->addSql('ALTER TABLE user DROP applications_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applications DROP FOREIGN KEY FK_F7C966F0A76ED395');
        $this->addSql('ALTER TABLE applications DROP FOREIGN KEY FK_F7C966F0BE04EA9');
        $this->addSql('DROP TABLE applications');
        $this->addSql('ALTER TABLE `user` ADD applications_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64929A0022 FOREIGN KEY (applications_id) REFERENCES applications (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64929A0022 ON `user` (applications_id)');
    }
}
