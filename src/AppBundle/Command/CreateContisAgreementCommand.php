<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SoapClient;


class CreateContisAgreementCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('contis:agreement:create')
            ->setDescription('Creates a new agreement that refers to account type.')
            ->setHelp('This command allows you to create an agreement
                in Contis to create the policy pricing for the accounts'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $host = $this->getContainer()->getParameter('contis_api_host');

        // $client = new SoapClient($host.'/ContisCardAPI.svc?singleWsdl');

        // $function = array_filter($client->__getFunctions(), function($functionName) {
        //     return strpos($functionName, 'Agreement') !== false ? true : false;
        // });

        // $params = array_filter($client->__getTypes(), function($functionName) {
        //     return strpos($functionName, 'AgreementInfo') !== false ? true : false;
        // });

        // $params = [
        //     'CurrencyCode' => ,
        //     'NewCardCharge' => ,
        //     'CardReissueCharge' => ,
        //     'LocalATMwithdrawCharge' => ,
        //     'AbroadATMwithdrawCharge' => ,
        //     'MaxBalance' => ,
        //     'CardLimit' => ,
        //     'MonthlyAccountFee' => ,
        //     'DailySpendLimit' => ,
        //     'MonthlySpendLimit' => ,
        //     'MaxNoOfAdditionalCards' => ,
        //     'ATMWeeklySpendLimit' => ,
        //     'ATMMonthlySpendLimit' => ,
        //     'CashBackDailyLimit' => ,
        //     'CashBackWeeklyLimit' => ,
        //     'CashBackMonthlyLimit' => ,
        //     'CashBackYearlyLimit' => ,
        // ];

        // $client->__soapCall('Agreement_Create', )

        // var_dump($function);
        // var_dump('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
        // var_dump('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
        // var_dump('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
        // var_dump($params);
        // die();
    }
}
