<?php

namespace AppBundle\Service\ContisApiClient;

use Exception;
use DateTime;

use AppBundle\Entity\Account;

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

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Account_GetStatement', $params, $requestParams);

        if ($response['Account_GetStatementResult']['Description'] == 'Success ') {
            return [$response['Account_GetStatementResult']['ResultObject']];
        }
        dump($response);die();
    }
}
