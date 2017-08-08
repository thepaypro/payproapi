<?php

namespace AppBundle\Service\ContisApiClient;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction as TransactionEntity;
use DateTime;

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
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        RequestService $requestService,
        HashingService $hashingService,
        AuthenticationService $authenticationService
    )
    {
        $this->requestService = $requestService;
        $this->hashingService = $hashingService;
        $this->authenticationService = $authenticationService;
    }

    public function create(TransactionEntity $transaction): array
    {
        $params = [
            'FromAccountNumber' => $transaction->getPayer()->getAccountNumber(),
            'ToAccountNumber' => $transaction->getBeneficiary()->getAccountNumber(),
            'Amount' => $transaction->getAmount(),
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
        dump($response);
        die();
    }

    /**
     * Get a list of transactions from Contis.
     * @param  Account $account
     * @param  DateTime $fromDate
     * @param  DateTime $toDate
     * @return array $response
     */
    public function getAll(Account $account, DateTime $fromDate, DateTime $toDate): array
    {
        $params = [
            'CardHolderId' => $account->getCardHolderId(),
            'AccountNumber' => $account->getAccountNumber(),
            'SortCode' => $account->getSortCode()
        ];

        if ($fromDate) {
            $params['FromDate'] = '/Date(' . (intval($fromDate->getTimeStamp() * 1000)) . ')/'; // Previous format, this returns an empty array for correct timestamp values.
//            $params['FromDate'] = $fromDate->format('YmdHms'); // Contis Contract format, this returns 500.
        }

        if ($toDate) {
            $params['ToDate'] = '/Date(' . (intval($toDate->getTimeStamp() * 1000)) . ')/';
        }

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Account_GetStatement', $params, $requestParams);

        if ($response['Account_GetStatementResult']['Description'] == 'Success ' &&
            $response['Account_GetStatementResult']['ResultObject'] != null) {
            return $response['Account_GetStatementResult']['ResultObject'];
        }
        dump($response);
        die();
    }
}
