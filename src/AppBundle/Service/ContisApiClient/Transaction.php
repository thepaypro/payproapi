<?php

namespace AppBundle\Service\ContisApiClient;

use Exception;
use DateTime;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction as TransactionEntity;

/**
 * Class Transaction
 * @package AppBundle\Service\ContisApiClient
 */
class Transaction
{
    protected $requestService;
    protected $hashingService;
    protected $authenticationService;

    /**
     * @param RequestService $requestService
     * @param HashingService $hashingService
     * @param AuthorizationService $authorizationService
     */
    public function __construct(
        RequestService $requestService,
        HashingService $hashingService,
        AuthenticationService $authenticationService
    ) {
        $this->requestService = $requestService;
        $this->hashingService = $hashingService;
        $this->authenticationService = $authenticationService;
    }

    public function create(TransactionEntity $transaction) : Array
    {
        $params = [
            'FromAccountNumber' => $transaction->getPayer()->getAccountNumber(),
            'ToAccountNumber' => $transaction->getBeneficiary()->getAccountNumber(),
            'Amount' => $transaction->getAmount()*100,
            'CurrencyCode' => '826',
            'Description' => $transaction->getSubject()
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Account_TransferMoney', $params, $requestParams);

        if ($response['Account_TransferMoneyResult']['Description'] == 'Success ') {
            return $response['Account_TransferMoneyResult']['ResultObject'][0];
        }
        dump($response);die();
    }

    /**
     * Get a list of transactions from Contis.
     * @return Array $response
     */
    public function getAll(Account $account, DateTime $fromDate, DateTime $toDate) : Array
    {
        $params = [
            'CardHolderId' => $account->getCardHolderId(),
            'AccountNumber' => $account->getAccountNumber(),
            'SortCode' => $account->getSortCode(),
            'FromDate' => '/Date('.$fromDate->getTimeStamp().')/',
            'ToDate' => '/Date('.$toDate->getTimeStamp().')/' 
        ];

        // $params = [
        //     'CardHolderID'  => 131232,
        //     'AccountNumber' => '04079462',
        //     'SortCode'      => '623053',
        //     'FromDate' => '/Date('.((DateTime::createFromFormat('d/m/Y', '6/7/2017'))->getTimeStamp()).')/',
        //     'ToDate' => '/Date('.((new DateTime())->getTimeStamp()).')/' 
        // ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Account_GetStatement', $params, $requestParams);

        dump($response);die();
        if ($response['Account_GetStatementResult']['Description'] == 'Success ') {
            return [$response['Account_GetStatementResult']['ResultObject']];
        }
        dump($response);die();
    }
}
