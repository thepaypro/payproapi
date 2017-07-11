<?php

namespace AppBundle\Service\ContisApiClient;

use AppBundle\Entity\Account as AccountEntity;
use Exception;

/**
 * Class AuthenticationService
 * @package AppBundle\Service\ContisApiClient
 */
class Account
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
     * @param  Account $account
     * @return Array $response
     */
    public function create(AccountEntity $account) : Array
    {
        $params = [
            'AgreementCode' => $account->getAgreement()->getContisAgreementCode(),
            'Buildingno' => $account->getBuildingNumber(),
            'City' => $account->getCity(),
            'Country' => $account->getCountry()->getIsoNumeric(),
            'County' => $account->getCountry()->getIso2(),
            'DOB' => '/Date('.strtotime($account->getBirthdate()).')/',
            'FirstName' => $account->getForename(),
            'Gender' => 'N',
            'LastName' => $account->getLastname(),
            'Postcode' => $account->getPostcode(),
            'IsMain' => 'true',
            'Relationship' => '01',
            'Street' => $account->getStreet(),
            'Title' => 'Other'
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientRequestReference' => $account->getId()
        ];

        $params = [$this->hashingService->generateHashDataStringAndHash($params)];
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('CardHolder_Create', $params, $requestParams);

        return $response;
    }
}
