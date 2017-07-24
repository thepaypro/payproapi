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

        $this->addSql('CREATE TABLE Invites (id INT AUTO_INCREMENT NOT NULL, inviter_id INT NOT NULL, invited_phone_number VARCHAR(255) NOT NULL, INDEX IDX_CCC353F0B79F4F04 (inviter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Agreements (id INT AUTO_INCREMENT NOT NULL, contis_agreement_code VARCHAR(255) NOT NULL, currency_code VARCHAR(255) NOT NULL, new_card_charge INT NOT NULL, card_reissue_charge INT NOT NULL, local_atmwithdraw_charge INT NOT NULL, abroad_atmwithdraw_charge INT NOT NULL, max_balance INT NOT NULL, card_limit INT NOT NULL, monthly_account_fee INT NOT NULL, daily_spend_limit INT NOT NULL, monthly_spend_limit INT NOT NULL, max_no_of_additional_cards INT NOT NULL, atmweekly_spend_limit INT NOT NULL, atmmonthly_spend_limit INT NOT NULL, cash_back_daily_limit INT NOT NULL, cash_back_weekly_limit INT NOT NULL, cash_back_monthly_limit INT NOT NULL, cash_back_yearly_limit INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Users (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', email_canonical VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D5428AED92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_D5428AEDC05FB297 (confirmation_token), INDEX IDX_D5428AED9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TransactionInvites (id INT AUTO_INCREMENT NOT NULL, invite_id INT DEFAULT NULL, transaction_id INT DEFAULT NULL, requested_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_1ECB7E03EA417747 (invite_id), UNIQUE INDEX UNIQ_1ECB7E032FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Countries (id INT AUTO_INCREMENT NOT NULL, iso2 VARCHAR(2) NOT NULL, iso3 VARCHAR(3) NOT NULL, iso_numeric VARCHAR(3) NOT NULL, name VARCHAR(100) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Cards (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, contis_card_id VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, is_enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C50377F99B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MobileVerificationCodes (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Accounts (id INT AUTO_INCREMENT NOT NULL, agreement_id INT NOT NULL, country_id INT NOT NULL, forename VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, document_type VARCHAR(255) NOT NULL, document_number VARCHAR(255) NOT NULL, card_holder_id VARCHAR(255) NOT NULL, account_number VARCHAR(255) NOT NULL, sort_code VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, building_number VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_33BEFCFA24890B2B (agreement_id), INDEX IDX_33BEFCFAF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Transactions (id INT AUTO_INCREMENT NOT NULL, payer_id INT DEFAULT NULL, beneficiary_id INT DEFAULT NULL, contis_transaction_id VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, subject VARCHAR(255) NOT NULL, INDEX IDX_F299C1B4C17AD9A9 (payer_id), INDEX IDX_F299C1B4ECCAAFA0 (beneficiary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Invites ADD CONSTRAINT FK_CCC353F0B79F4F04 FOREIGN KEY (inviter_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AED9B6B5FBA FOREIGN KEY (account_id) REFERENCES Accounts (id)');
        $this->addSql('ALTER TABLE TransactionInvites ADD CONSTRAINT FK_1ECB7E03EA417747 FOREIGN KEY (invite_id) REFERENCES Invites (id)');
        $this->addSql('ALTER TABLE TransactionInvites ADD CONSTRAINT FK_1ECB7E032FC0CB0F FOREIGN KEY (transaction_id) REFERENCES Transactions (id)');
        $this->addSql('ALTER TABLE Cards ADD CONSTRAINT FK_C50377F99B6B5FBA FOREIGN KEY (account_id) REFERENCES Accounts (id)');
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

        $this->addSql('ALTER TABLE TransactionInvites DROP FOREIGN KEY FK_1ECB7E03EA417747');
        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFA24890B2B');
        $this->addSql('ALTER TABLE Invites DROP FOREIGN KEY FK_CCC353F0B79F4F04');
        $this->addSql('ALTER TABLE Accounts DROP FOREIGN KEY FK_33BEFCFAF92F3E70');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AED9B6B5FBA');
        $this->addSql('ALTER TABLE Cards DROP FOREIGN KEY FK_C50377F99B6B5FBA');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B4C17AD9A9');
        $this->addSql('ALTER TABLE Transactions DROP FOREIGN KEY FK_F299C1B4ECCAAFA0');
        $this->addSql('ALTER TABLE TransactionInvites DROP FOREIGN KEY FK_1ECB7E032FC0CB0F');
        $this->addSql('DROP TABLE Invites');
        $this->addSql('DROP TABLE Agreements');
        $this->addSql('DROP TABLE Users');
        $this->addSql('DROP TABLE TransactionInvites');
        $this->addSql('DROP TABLE Countries');
        $this->addSql('DROP TABLE Cards');
        $this->addSql('DROP TABLE MobileVerificationCodes');
        $this->addSql('DROP TABLE Accounts');
        $this->addSql('DROP TABLE Transactions');
    }
}
