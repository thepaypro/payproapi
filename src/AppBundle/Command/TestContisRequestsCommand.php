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
        $params = [
            'CardHolderID'  => 0,
            'FirstName'     => 'Bethany',
            'LastName'      => 'Harriman',
            'MobileNumber'  => '',
            'EmailAddress'  => 'beth.harriman@contisgroup.com',
            'AccountNumber' => '04079462',
            'CardID'        => 0,
            'HashCardNumber'=> '',
            'UserName'      => '',
            'SortCode'      => '623053'
        ];
        $endpoint = 'CardHolder_Lookup_GetInfo';

        $params['Token'] = $this->getContainer()->get('payproapi.contis_authentication_service')->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientRequestReference' => 'contis123',
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->getContainer()->get('payproapi.contis_hashing_service')->generateHashDataStringAndHash($params);
        $requestParams = $this->getContainer()->get('payproapi.contis_hashing_service')->generateHashDataStringAndHash($requestParams);

        $response = $this->getContainer()->get('payproapi.contis_request_service')->call($endpoint, $params, $requestParams);

        dump($response);die();
    }
}
