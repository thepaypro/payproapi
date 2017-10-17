<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Bridge\Monolog\Logger;
use AppBundle\Entity\Account;
use AppBundle\Exception\PayProException;

/**
 * Class Balance
 * @package AppBundle\Service\ContisApiClient
 */
class Balance
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

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Account_GetBalance','ContisApiClient']
        );
        
        throw new PayProException("Bad Request", 400);
    }
}
