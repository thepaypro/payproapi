<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestContisRequestsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payproapi:contis:call')
            ->setDescription('Call contis endpoint');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $params = [
        //     'CardHolderID'  => 131232,
        //     'FirstName'     => 'Bethany',
        //     'LastName'      => 'Harriman',
        //     'EmailAddress'  => 'beth.harriman@contisgroup.com',
        //     'AccountNumber' => '04079462',
        //     'SortCode'      => '623053'
        // ];

        // $params = [
        //     'CardHolderID'  => 131366,
        //     'FirstName'     => 'Beth',
        //     'LastName'      => 'Harriman',
        //     'EmailAddress'  => 'beth.harriman@contisgroup.com',
        //     'AccountNumber' => '04079834',
        //     'SortCode'      => '623053'
        // ];

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

        dump($response);die();
    }
}
