<?php

namespace AppBundle\Service\ContisApiClient;

use AppBundle\Entity\Account;

/**
 * Class Balance
 * @package AppBundle\Service\ContisApiClient
 */
class Balance
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

    public function get(Account $account)
    {
        $params = [
            'CardHolderID' => $account->getCardHolderId(),
            'AccountNumber' => $account->getAccountNumber(),
        ];

        $endpoint = 'Account_GetBalance';

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientRequestReference' => 'contis123',
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call($endpoint, $params, $requestParams);

        if ($response['Account_GetBalanceResult']['Description'] == 'Success ') {
            return $response['Account_GetBalanceResult']['ResultObject']['AvailableBalance'];
        }
        dump($response);
        die();
    }
}
