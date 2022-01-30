<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110214845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gewicht ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gewicht ADD CONSTRAINT FK_36459659A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_36459659A76ED395 ON gewicht (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gewicht DROP FOREIGN KEY FK_36459659A76ED395');
        $this->addSql('DROP INDEX IDX_36459659A76ED395 ON gewicht');
        $this->addSql('ALTER TABLE gewicht DROP user_id');
    }
}
