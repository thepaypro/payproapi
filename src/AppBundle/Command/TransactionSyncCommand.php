<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionSyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payproapi:contis:transaction-sync')
            ->setDescription('Syncs the transactions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getCardHolder();
    }

    /**
     * Executes the query to find the accounts older than 20 minutes
     * without a notification sent to them and calls the Card Holder
     * verification service.
     */
    private function getCardHolder()
    {
        $accounts = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Account')
            ->findAll();

        foreach ($accounts as $account) {

            $this->getContainer()->get('payproapi.contis_sync_transaction_service')->execute($account);
        }
    }
}
