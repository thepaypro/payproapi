<?php

namespace AppBundle\Service\ContisApiClient;

use Exception;
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
    public function getAll(/**Account $account, DateTime $fromDate = null, DateTime $toDate = null**/) : Array
    {
        $params = [
            // 'CardHolderId' => $account->getCardHolderId(),
            'CardHolderId' => '131232',
            // 'AccountNumber' => $account->getAccountNumber()
            'AccountNumber' => '04079462'
        ];

        // if ($fromDate) {
        //     $params['FromDate'] = $fromDate->getTimestamp();
        // }

        // if ($toDate) {
        //     $params['ToDate'] = $toDate->getTimestamp();
        // }

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now')
        ];

        $params = [$this->hashingService->generateHashDataStringAndHash($params)];
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Account_GetStatement', $params, $requestParams);

        if ($response['Account_GetStatementResult']['Description'] == 'Success ') {
            return $response['Account_GetStatementResult']['ResultObject'][0];
        }
        dump($response);die();
    }
}
