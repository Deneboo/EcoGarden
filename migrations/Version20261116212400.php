<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261116212400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advice RENAME COLUMN month TO months');
        $this->addSql('ALTER TABLE advice MODIFY months JSON NOT NULL');
        $this->addSql('UPDATE advice SET months = JSON_ARRAY(JSON_UNQUOTE(months))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE advice SET months = JSON_UNQUOTE(JSON_EXTRACT(months, "$[0]"))');
        $this->addSql('ALTER TABLE advice MODIFY months VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE advice RENAME COLUMN months TO month');
    }
}
