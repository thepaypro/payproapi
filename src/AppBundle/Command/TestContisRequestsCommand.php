<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;

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
        // $this->getCardHolder();
        $this->getCard();
        // $this->getCardActivationCode();
        // $this->updateCardStatus();
    }

    public function getCardActivationCode()
    {
        $params = [
            'CardHolderID'  => 131639,
            'AccountNumber' => '04073795',
            'SortCode'      => '623053'
        ];

        $endpoint = 'Card_GetActivationCode';

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

    public function updateCardStatus()
    {
        $params = [
            'CardHolderID'  => 131639,
            'CardID'  => 24687,
            'NewCardStatus'  => '05'
        ];

        $endpoint = 'Card_ChangeStatus';

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

    private function getCard()
    {
        $params = [
            'CardHolderID'  => 131764,
            'AccountNumber' => '04077705',
            'SortCode'      => '623053'
        ];

        $endpoint = 'Card_Lookup_GetInfo';

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

    private function getCardHolder()
    {
        /** Fake CardHolders created by Beth **/
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

        $params = [
            'CardHolderID'  => 131764
            // 'FirstName'     => 'Beth',
            // 'LastName'      => 'Harriman',
            // 'EmailAddress'  => 'beth.harriman@contisgroup.com',
            // 'AccountNumber' => '04079834',
            // 'SortCode'      => '623053'
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

        dump($response);die();
    }

    public function getBalance() {
        $accountRepository = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Account');

        $account = $accountRepository->findOneById('1');

        $this->getContainer()->get('contis_api_client.balance_service')->get($account);
    }
}
