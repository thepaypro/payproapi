<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170523165703 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Accounts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agreement_id INT NOT NULL, country_id INT NOT NULL, forename VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, document_type VARCHAR(255) NOT NULL, document_number VARCHAR(255) NOT NULL, card_holder_id VARCHAR(255) NOT NULL, principal_address VARCHAR(255) NOT NULL, secondary_address VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_33BEFCFAA76ED395 (user_id), INDEX IDX_33BEFCFA24890B2B (agreement_id), INDEX IDX_33BEFCFAF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Agreements (id INT AUTO_INCREMENT NOT NULL, contis_agreement_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Countries (id INT AUTO_INCREMENT NOT NULL, iso VARCHAR(2) NOT NULL, name VARCHAR(100) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MobileVerificationCode (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Transactions (id INT AUTO_INCREMENT NOT NULL, payer_id INT DEFAULT NULL, beneficiary_id INT DEFAULT NULL, contis_code VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_F299C1B4C17AD9A9 (payer_id), INDEX IDX_F299C1B4ECCAAFA0 (beneficiary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', email_canonical VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D5428AED92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_D5428AEDC05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Accounts ADD CONSTRAINT FK_33BEFCFAA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Accounts ADD CONSTRAINT FK_33BEFCFA24890B2B FOREIGN KEY (agreement_id) REFERENCES Agreements (id)');
        $this->addSql('ALTER TABLE Accounts ADD CONSTRAINT FK_33BEFCFAF92F3E70 FOREIGN KEY (country_id) REFERENCES Countries (id)');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B4C17AD9A9 FOREIGN KEY (payer_id) REFERENCES Accounts (id)');
        $this->addSql('ALTER TABLE Transactions ADD CONSTRAINT FK_F299C1B4ECCAAFA0 FOREIGN KEY (beneficiary_id) REFERENCES Accounts (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B4C17AD9A9');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B4ECCAAFA0');
        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFA24890B2B');
        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFAF92F3E70');
        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFAA76ED395');
        $this->addSql('DROP TABLE Accounts');
        $this->addSql('DROP TABLE Agreements');
        $this->addSql('DROP TABLE Countries');
        $this->addSql('DROP TABLE MobileVerificationCode');
        $this->addSql('DROP TABLE Transactions');
        $this->addSql('DROP TABLE Users');
    }
}
