<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171027114832 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE BitcoinAccounts (id INT AUTO_INCREMENT NOT NULL, last_synced_transaction_id INT DEFAULT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_9E472569ABCB3E15 (last_synced_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE BitcoinTransactions (id INT AUTO_INCREMENT NOT NULL, payer_id INT DEFAULT NULL, beneficiary_id INT DEFAULT NULL, blockchain_transaction_id VARCHAR(255) NOT NULL, amount BIGINT NOT NULL, subject VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B22D04E0C17AD9A9 (payer_id), INDEX IDX_B22D04E0ECCAAFA0 (beneficiary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE BitcoinAccounts ADD CONSTRAINT FK_9E472569ABCB3E15 FOREIGN KEY (last_synced_transaction_id) REFERENCES BitcoinTransactions (id)');
        $this->addSql('ALTER TABLE BitcoinTransactions ADD CONSTRAINT FK_B22D04E0C17AD9A9 FOREIGN KEY (payer_id) REFERENCES BitcoinAccounts (id)');
        $this->addSql('ALTER TABLE BitcoinTransactions ADD CONSTRAINT FK_B22D04E0ECCAAFA0 FOREIGN KEY (beneficiary_id) REFERENCES BitcoinAccounts (id)');
        $this->addSql('ALTER TABLE Users ADD bitcoin_account_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AEDCEA82518 FOREIGN KEY (bitcoin_account_id) REFERENCES BitcoinAccounts (id)');
        $this->addSql('CREATE INDEX IDX_D5428AEDCEA82518 ON Users (bitcoin_account_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE BitcoinTransactions DROP FOREIGN KEY FK_B22D04E0C17AD9A9');
        $this->addSql('ALTER TABLE BitcoinTransactions DROP FOREIGN KEY FK_B22D04E0ECCAAFA0');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AEDCEA82518');
        $this->addSql('ALTER TABLE BitcoinAccounts DROP FOREIGN KEY FK_9E472569ABCB3E15');
        $this->addSql('DROP TABLE BitcoinAccounts');
        $this->addSql('DROP TABLE BitcoinTransactions');
        $this->addSql('DROP INDEX IDX_D5428AEDCEA82518 ON Users');
        $this->addSql('ALTER TABLE Users DROP bitcoin_account_id');
    }
}
