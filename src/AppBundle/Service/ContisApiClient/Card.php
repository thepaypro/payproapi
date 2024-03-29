<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Bridge\Monolog\Logger;
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
    protected $logger;

    /**
     * @param RequestService $requestService
     * @param HashingService $hashingService
     * @param AuthorizationService $authorizationService
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

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Card_Request','ContisApiClient']
        );

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

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Card_GetActivationCode','ContisApiClient']
        );

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

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Card_Activate','ContisApiClient']
        );

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

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Card_ChangeStatus','ContisApiClient']
        );

        throw new PayProException("Bad Request", 400);
    }

    /**
     * Get Pin card
     * @param  CardEntity $card
     * @param int $cvv2
     * @return string
     */
    public function retrivePin(CardEntity $card, string $hashCardNumber, int $cvv2) : string
    {
        $params = [
            'HashCardNumber' => $hashCardNumber,
            'SecurityCode' => $cvv2,
            'CardID' => $card->getContisCardId()
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
        
        if ($response['Card_RetrivePINResult']['ResponseCode'] == '000') {
            return $this->hashingService->pinDecrypt($response['Card_RetrivePINResult']['ResultObject']['Pin']);
        }
      
        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Card_RetrivePIN','ContisApiClient']
        );

        throw new PayProException("Bad Request", 400);
    }

    public function getInfo(CardEntity $card)
    {
        $params = [
            'CardHolderID' => $card->getAccount()->getCardHolderId(),
            'AccountNumber' => $card->getAccount()->getAccountNumber(),
            'CardID' => $card->getContisCardId(),
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

        $response = $this->requestService->call('Card_Lookup_GetInfo', $params, $requestParams);
        if ($response['Card_Lookup_GetInfoResult']['ResponseCode'] == '000') {
            return $response['Card_Lookup_GetInfoResult']['ResultObject'][0];
        }

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Card_RetrivePIN','ContisApiClient']
        );
        
        throw new PayProException("Bad Request", 400);
    }


    public function getInfo(CardEntity $card)
    {
        $params = [
            'CardHolderID' => $card->getAccount()->getCardHolderId(),
            'AccountNumber' => $card->getAccount()->getAccountNumber(),
            'CardID' => $card->getContisCardId(),
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

        $response = $this->requestService->call('Card_Lookup_GetInfo', $params, $requestParams);

        if ($response['Card_Lookup_GetInfoResult']['ResponseCode'] == '000') {
            return $response['Card_Lookup_GetInfoResult']['ResultObject'][0];
        }

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['Card_Lookup_GetInfo','ContisApiClient']
        );
        
        throw new PayProException("Bad Request", 400);
    }
}
