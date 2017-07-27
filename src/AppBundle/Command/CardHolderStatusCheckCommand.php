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

    private function getCardHolder()
    {
        $accounts = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Account')
            ->findAccountsWithPendingNotification();

        $this->getContainer()->get('payproapi.card_holder_verification_service')->execute($accounts);
    }
}
