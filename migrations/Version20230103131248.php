<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230103131248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calipometrie (id INT AUTO_INCREMENT NOT NULL, bauch INT DEFAULT NULL, brust INT DEFAULT NULL, huefte INT DEFAULT NULL, trizeps INT DEFAULT NULL, bein INT DEFAULT NULL, achsel INT DEFAULT NULL, ruecken INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE tdee');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tdee (id INT AUTO_INCREMENT NOT NULL, gewicht_id INT DEFAULT NULL, pal_schlaf INT NOT NULL, pal_liegen INT NOT NULL, pal_sitzen INT NOT NULL, pal_ab_und_zu INT NOT NULL, pal_gehend INT NOT NULL, pal_arbeit INT NOT NULL, timestamp DATETIME NOT NULL, grundumsatz DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_AF95811E248148BA (gewicht_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tdee ADD CONSTRAINT FK_AF95811E248148BA FOREIGN KEY (gewicht_id) REFERENCES gewicht (id)');
        $this->addSql('DROP TABLE calipometrie');
    }
}
