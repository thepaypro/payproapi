<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170704122249 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $agreements = [
            [
                'id' => '1',
                'contis_agreement_code' => 'RJ-AM-GS-L7',
                'currency_code' => 'GB',
                'new_card_charge' => '599',
                'card_reissue_charge' => '0',
                'local_atmwithdraw_charge' => '0',
                'abroad_atmwithdraw_charge' => '150',
                'max_balance' => '500000',
                'card_limit' => '1',
                'monthly_account_fee' => '0',
                'daily_spend_limit' => '12345',
                'monthly_spend_limit' => '1246788',
                'max_no_of_additional_cards' => '0',
                'atmweekly_spend_limit' => '1342',
                'atmmonthly_spend_limit' => '123451',
                'cash_back_daily_limit' => '1234',
                'cash_back_weekly_limit' => '123456',
                'cash_back_monthly_limit' => '12345678',
                'cash_back_yearly_limit' => '1234567890',
            ],
            [
                'id' => '2',
                'contis_agreement_code' => 'RJ-AM-GS-L7',
                'currency_code' => 'GB',
                'new_card_charge' => '59900',
                'card_reissue_charge' => '1',
                'local_atmwithdraw_charge' => '1',
                'abroad_atmwithdraw_charge' => '1',
                'max_balance' => '1',
                'card_limit' => '1',
                'monthly_account_fee' => '1',
                'daily_spend_limit' => '1',
                'monthly_spend_limit' => '1',
                'max_no_of_additional_cards' => '1',
                'atmweekly_spend_limit' => '1',
                'atmmonthly_spend_limit' => '1',
                'cash_back_daily_limit' => '1',
                'cash_back_weekly_limit' => '1',
                'cash_back_monthly_limit' => '1',
                'cash_back_yearly_limit' => '1',
            ],
        ];

        foreach($agreements as $agreement){
            $this->connection->insert('Agreements', $agreement);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
