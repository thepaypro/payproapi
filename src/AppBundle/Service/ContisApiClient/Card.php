<?php

namespace AppBundle\Service\ContisApiClient;

use AppBundle\Entity\Card as CardEntity;
use Exception;

/**
 * Class AuthenticationService
 * @package AppBundle\Service\ContisApiClient
 */
class Card
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
     * Create an account (CardHolder in Contis).
     * @param  Card $account
     * @return Array $response
     */
    public function request(CardEntity $card) : Array
    {
        $params = [
            'AccountNumber' => $card->getAccount()->getAccountNumber(),
            'ClientDesignCode' => 'PAYPROPA',
            'CardHolderID' => $card->getAccount()->getCardHolderId()
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now')
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Card_Request', $params, $requestParams, 'objCardRequestInfo');

        if ($response['Card_RequestResult']['Description'] == 'Success ') {
            return $response['Card_RequestResult']['ResultObject'][0];
        }
        dump($response);die();
    }
}
