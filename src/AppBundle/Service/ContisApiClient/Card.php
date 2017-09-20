<?php

namespace AppBundle\Service\ContisApiClient;

use AppBundle\Entity\Card as CardEntity;
use AppBundle\Exception\PayProException;

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
     * @param CardEntity $card
     * @return bool
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
        throw new PayProException("Bad Request", 400);
    }

    /**
     * Activate a card.
     * @param CardEntity $card
     * @return array
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

        if ($response['Card_GetActivationCodeResult']['Description'] == 'Success ' &&
            $response['Card_GetActivationCodeResult']['ResultObject'] != null) {
            return $response['Card_GetActivationCodeResult']['ResultObject'];
        }
        throw new PayProException("Bad Request", 400);
    }

    /**
     * Activate a card.
     * @param CardEntity $card
     * @return bool
     */
    public function activate(CardEntity $card, int $pan) : bool
    {
        $params = [
            'CardHolderID' => $card->getAccount()->getCardHolderId(),
            'CardActivationCode' => $card->getContisCardActivationCode(),
            'CardID' => $card->getContisCardId(),
            'PAN' => $pan
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

        if ($response['Card_ActivateResult']['Description'] == 'Success ') {
            return true;
        }
        throw new PayProException("Bad Request", 400);
    }

    /**
     * Change the status of a card.
     * @param CardEntity $card
     * @return bool
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
        throw new PayProException("Bad Request", 400);
    }

    /**
     * Get Pin card
     * @param  CardEntity $card
     * @param int $cvv2
     * @return bool
     */
    public function retrivePin(CardEntity $card, int $cvv2) : bool
    {
        $params = [
            'CardID' => $card->getContisCardId(),
            'SecurityCode' => $cvv2
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now'),
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('Card_RetrivePIN', $params, $requestParams);
        
        if ($response['Card_RetrivePINResult']['Description'] == '000') {
            return $response['Card_RetrivePINResult']['ResultObject'];
        }
        throw new PayProException("Bad Request", 400);
    }
}
