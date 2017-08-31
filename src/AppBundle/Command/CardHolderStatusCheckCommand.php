<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CardHolderStatusCheckCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payproapi:contis:card-holder:status-check')
            ->setDescription('Checks the status of the Card Holders in the contis api');
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
            ->findAccountsWithPendingNotification();

        $this->getContainer()->get('payproapi.card_holder_verification_service')->execute($accounts);
    }
}
