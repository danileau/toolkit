<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110132242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tdee ADD gewicht_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tdee ADD CONSTRAINT FK_AF95811E248148BA FOREIGN KEY (gewicht_id) REFERENCES gewicht (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF95811E248148BA ON tdee (gewicht_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tdee DROP FOREIGN KEY FK_AF95811E248148BA');
        $this->addSql('DROP INDEX UNIQ_AF95811E248148BA ON tdee');
        $this->addSql('ALTER TABLE tdee DROP gewicht_id');
    }
}
