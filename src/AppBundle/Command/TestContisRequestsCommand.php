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

        $response = $this->getContainer()->get('payproapi.contis_request_service')->call($endpoint, $params);

        dump($response);die();
    }
}
