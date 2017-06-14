<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170613111009 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Accounts (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, forename VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, document_type VARCHAR(255) NOT NULL, document_number VARCHAR(255) NOT NULL, account_type_id VARCHAR(255) NOT NULL, card_holder_id VARCHAR(255) NOT NULL, principal_address VARCHAR(255) NOT NULL, secondary_address VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_33BEFCFAF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Countries (id INT AUTO_INCREMENT NOT NULL, iso VARCHAR(2) NOT NULL, name VARCHAR(100) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Accounts ADD CONSTRAINT FK_33BEFCFAF92F3E70 FOREIGN KEY (country_id) REFERENCES Countries (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFAF92F3E70');
        $this->addSql('DROP TABLE Accounts');
        $this->addSql('DROP TABLE Countries');
    }
}