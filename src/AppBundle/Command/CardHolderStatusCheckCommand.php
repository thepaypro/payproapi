<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Account;

class TestContisRequestsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payproapi:contis:cardholderstatuscheck')
            ->setDescription('Checks the status of the Card Holders in the contis api');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getCardHolder();
    }

    private function getCardHolder(Account $account)
    {
        // This seems wrong in so many levels I don't even.
        $params = [
            'CardHolderID' => $account->getCardHolderId()
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

        return $response;
    }
}
