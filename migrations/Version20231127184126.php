<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127184126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merchant ADD email VARCHAR(255) NOT NULL, ADD createdat DATETIME NOT NULL, ADD updatedat DATETIME NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merchant DROP email, DROP createdat, DROP updatedat, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE phonenumber phonenumber INT NOT NULL');
    }
}
