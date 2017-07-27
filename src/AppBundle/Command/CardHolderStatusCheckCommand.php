<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Account;

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

        //TODO: Query the accounts older than 20 mins here.

        $params = [
            'CardHolderID' => 1234
        ];

        $endpoint = 'CardHolder_Lookup_GetInfo';

        $params['Token'] = $this->getContainer()->get('contis_api_client.authentication_service')->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientRequestReference' => 'contis123',
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->getContainer()->get('contis_api_client.hashing_service')->generateHashDataStringAndHash($params);
        $requestParams = $this->getContainer()->get('contis_api_client.hashing_service')->generateHashDataStringAndHash($requestParams);

        $response = $this->getContainer()->get('contis_api_client.request_service')->call($endpoint, $params, $requestParams);

        //TODO: Dispatch an event for every single account with non-pending status.

        dump($response);die();
    }
}
