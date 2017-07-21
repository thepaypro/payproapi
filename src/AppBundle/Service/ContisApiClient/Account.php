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
            'DOB' => '/Date('.$account->getBirthdate()->getTimeStamp().')/',
            'FirstName' => $account->getForename(),
            'Gender' => 'N',
            'LastName' => $account->getLastname(),
            'Postcode' => $account->getPostcode(),
            'Nationalidcardline1' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_DNI ? $account->getDocumentNumber() : '',
            'Drivinglicence' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_PASSPORT ? $account->getDocumentNumber() : '',
            'Passportnumber' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_DRIVING_LICENSE ? $account->getDocumentNumber() : '',
            'IsMain' => 'true',
            'Relationship' => '01',
            'Street' => $account->getStreet(),
            'Title' => 'Other'
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now')
        ];

        $params = [$this->hashingService->generateHashDataStringAndHash($params)];
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('CardHolder_Create', $params, $requestParams);

        if ($response['CardHolder_CreateResult']['Description'] == 'Success ') {
            return $response['CardHolder_CreateResult']['ResultObject'][0];
        }
        dump($response);die();
    }

    /**
     * Update an account (CardHolder in Contis)
     * @param  AccountEntity $account
     * @return Array $response
     */
    public function update(AccountEntity $account) : Array
    {
        $params = [
            'AgreementCode' => $account->getAgreement()->getContisAgreementCode(),
            'Buildingno' => $account->getBuildingNumber(),
            'City' => $account->getCity(),
            'Country' => $account->getCountry()->getIsoNumeric(),
            'County' => $account->getCountry()->getIso2(),
            'DOB' => '/Date('.$account->getBirthdate()->getTimeStamp().')/',
            'FirstName' => $account->getForename(),
            'LastName' => $account->getLastname(),
            'Postcode' => $account->getPostcode(),
            'Nationalidcardline1' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_DNI ? $account->getDocumentNumber() : '',
            'Drivinglicence' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_PASSPORT ? $account->getDocumentNumber() : '',
            'Passportnumber' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_DRIVING_LICENSE ? $account->getDocumentNumber() : '',
            'Street' => $account->getStreet(),
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now')
        ];

        $params = [$this->hashingService->generateHashDataStringAndHash($params)];
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('CardHolder_Update', $params, $requestParams);

        if ($response['CardHolder_UpdateResult']['Description'] == 'Success ') {
            return $response['CardHolder_UpdateResult']['ResultObject'][0];
        }
        dump($response);die();
    }
}
