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
    )
    {
        $this->requestService = $requestService;
        $this->hashingService = $hashingService;
        $this->authenticationService = $authenticationService;
    }

    /**
     * Request a card.
     * @param  Card $card
     * @return true
     */
    public function request(CardEntity $card) : bool
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
            return true;
        }
        dump($response);die();
    }

    /**
     * Activate a card.
     * @param  Card $card
     * @return true
     */
    public function getActivationCode(CardEntity $card) : array
    {
        $params = [
            'AccountNumber' => $card->getAccount()->getAccountNumber(),
            'CardHolderID' => $card->getAccount()->getCardHolderId(),
            'SortCode' => $card->getAccount()->getSortCode()
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Card_GetActivationCode', $params, $requestParams);
        dump($response);die();

        if ($response['Card_GetActivationCodeResult']['Description'] == 'Success ') {
            return $response['Card_GetActivationCodeResult']['ResultObject'];
        }
        dump($response);die();
    }

    /**
     * Activate a card.
     * @param  Card $card
     * @return true
     */
    public function activate(CardEntity $card) : array
    {
        $params = [
            'CardHolderID' => $card->getAccount()->getCardHolderId(),
            'CardActivationCode' => $card->getContisCardActivationCode(),
            'CardID' => $card->getContisCardId(),
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Card_Activate', $params, $requestParams);

        dump($response);die();
        if ($response['Card_ActivateResult']['Description'] == 'Success ') {
            return $response['Card_ActivateResult']['ResultObject'];
        }
        dump($response);die();
    }

    /**
     * Change the status of a card.
     * @param  Card $card
     * @return true
     */
    public function update(CardEntity $card) : bool
    {
        $params = [
            'CardHolderID' => $card->getAccount()->getCardHolderId(),
            'CardID' => $card->getContisCardId(),
            'SortCode' => $card->getAccount()->getSortCode(),
            'NewCardStatus' => $card->getIsEnabled()?'01':'10'
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Card_ChangeStatus', $params, $requestParams);

        if ($response['Card_ChangeStatusResult']['Description'] == 'Success ') {
            return true;
        }
        dump($response);die();
    }
}
