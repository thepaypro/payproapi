<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Bridge\Monolog\Logger;
use AppBundle\Entity\Account;
use AppBundle\Exception\PayProException;
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
    protected $logger;

    /**
     * @param RequestService $requestService
     * @param HashingService $hashingService
     * @param AuthenticationService $authenticationService
     * @param Logger $logger
     */
    public function __construct(
        RequestService $requestService,
        HashingService $hashingService,
        AuthenticationService $authenticationService,
        Logger $logger
    )
    {
        $this->requestService = $requestService;
        $this->hashingService = $hashingService;
        $this->authenticationService = $authenticationService;
        $this->logger = $logger;
    }

    /**
     * @param TransactionEntity $transaction
     * @return array
     * @throws PayProException
     */
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

        if ($response['Account_TransferMoneyResult']['Description'] != 'Success ') {
            $this->logger->addCritical(
                'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
                ['Account_TransferMoney','ContisApiClient']
            );
            throw new PayProException("Bad Request", 400);
        }

        return $response['Account_TransferMoneyResult']['ResultObject'][0];
    }

    /**
     * Get a list of transactions from Contis.
     * @param  Account $account
     * @param  DateTime $fromDate
     * @param  DateTime $toDate
     * @return array $response
     * @throws PayProException
     */
    public function getAll(Account $account, DateTime $fromDate, DateTime $toDate): array
    {
        $params = [
            'CardHolderId' => $account->getCardHolderId(),
            'AccountNumber' => $account->getAccountNumber(),
            'SortCode' => $account->getSortCode()
        ];

        if ($fromDate) {
            $params['FromDate'] = '/Date(' . (intval($fromDate->getTimeStamp() * 1000)) . ')/';
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

        if ($response['Account_GetStatementResult']['Description'] != 'Success ') {
            $this->logger->addCritical(
                'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
                ['Account_GetStatement','ContisApiClient']
            );
            throw new PayProException("Bad Request", 400);
        }

        if (!$response['Account_GetStatementResult']['ResultObject']) {
            return [];
        }

        return $response['Account_GetStatementResult']['ResultObject'];
    }
}
