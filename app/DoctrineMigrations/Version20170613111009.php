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

        $this->addSql('CREATE TABLE Accounts (id INT AUTO_INCREMENT NOT NULL, agreement_id INT NOT NULL, country_id INT NOT NULL, forename VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, document_type VARCHAR(255) NOT NULL, document_number VARCHAR(255) NOT NULL, card_holder_id VARCHAR(255) NOT NULL, principal_address VARCHAR(255) NOT NULL, secondary_address VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_33BEFCFA24890B2B (agreement_id), INDEX IDX_33BEFCFAF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Agreements (id INT AUTO_INCREMENT NOT NULL, contis_agreement_code VARCHAR(255) NOT NULL, currency_code VARCHAR(255) NOT NULL, new_card_charge INT NOT NULL, card_reissue_charge INT NOT NULL, local_atmwithdraw_charge INT NOT NULL, abroad_atmwithdraw_charge INT NOT NULL, max_balance INT NOT NULL, card_limit INT NOT NULL, monthly_account_fee INT NOT NULL, daily_spend_limit INT NOT NULL, monthly_spend_limit INT NOT NULL, max_no_of_additional_cards INT NOT NULL, atmweekly_spend_limit INT NOT NULL, atmmonthly_spend_limit INT NOT NULL, cash_back_daily_limit INT NOT NULL, cash_back_weekly_limit INT NOT NULL, cash_back_monthly_limit INT NOT NULL, cash_back_yearly_limit INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Countries (id INT AUTO_INCREMENT NOT NULL, iso2 VARCHAR(2) NOT NULL, iso3 VARCHAR(3) NOT NULL, name VARCHAR(100) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Accounts ADD CONSTRAINT FK_33BEFCFA24890B2B FOREIGN KEY (agreement_id) REFERENCES Agreements (id)');
        $this->addSql('ALTER TABLE Accounts ADD CONSTRAINT FK_33BEFCFAF92F3E70 FOREIGN KEY (country_id) REFERENCES Countries (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFA24890B2B');
        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFAF92F3E70');
        $this->addSql('DROP TABLE Accounts');
        $this->addSql('DROP TABLE Agreements');
        $this->addSql('DROP TABLE Countries');
    }
}