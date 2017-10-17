<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Bridge\Monolog\Logger;
use AppBundle\Entity\Account as AccountEntity;
use AppBundle\Exception\PayProException;
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
    protected $logger;

    const STATUS_ACTIVATED = "01";
    const STATUS_INCOMPLETED = "09";
    const STATUS_DENIED = "07";

    private $accountCardHolderStatusMapping = [
      self::STATUS_ACTIVATED => AccountEntity::STATUS_ACTIVATED,
      self::STATUS_INCOMPLETED => AccountEntity::STATUS_INCOMPLETED,
      self::STATUS_DENIED => AccountEntity::STATUS_DENIED
    ];

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
    ) {
        $this->requestService = $requestService;
        $this->hashingService = $hashingService;
        $this->authenticationService = $authenticationService;
        $this->logger = $logger;
    }

    /**
     * Create an account (CardHolder in Contis).
     * @param  AccountEntity $account
     * @return array $response
     */
    public function create(AccountEntity $account) : array
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
            'Passportnumber' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_PASSPORT ? $account->getDocumentNumber() : '',
            'Drivinglicence' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_DRIVING_LICENSE ? $account->getDocumentNumber() : '',
            'IsMain' => 'true',
            'Relationship' => '01',
            'Street' => $account->getStreet(),
            'Title' => 'Other',
            'IsSkipCardIssuance' => true
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

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['CardHolder_Create','ContisApiClient']
        );

        throw new PayProException("Bad Request", 400);
    }

    /**
     * Update an account (CardHolder in Contis)
     * @param  AccountEntity $account
     * @return array $response
     */
    public function update(AccountEntity $account) : array
    {
        $params = [
            'CardHolderID' => $account->getCardHolderId(),
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
            'Passportnumber' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_PASSPORT ? $account->getDocumentNumber() : '',
            'Drivinglicence' => $account->getDocumentType() == AccountEntity::DOCUMENT_TYPE_DRIVING_LICENSE ? $account->getDocumentNumber() : '',
            'Relationship' => '01',
            'Street' => $account->getStreet(),
            'Title' => 'Other'
        ];

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientUniqueReferenceID' => strtotime('now')
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call('CardHolder_Update', $params, $requestParams);

        if ($response['CardHolder_UpdateResult']['Description'] == 'Success ') {
            return $response['CardHolder_UpdateResult']['ResultObject'];
        }

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['CardHolder_Update','ContisApiClient']
        );

        throw new PayProException("Bad Request", 400);
    }

    public function getOne(string $cardHolderId)
    {
        $params = [
            'CardHolderID' => $cardHolderId
        ];

        $endpoint = 'CardHolder_Lookup_GetInfo';

        $params['Token'] = $this->authenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token'],
            'ClientRequestReference' => 'contis123',
            'SchemeCode' => 'PAYPRO'
        ];

        $params = $this->hashingService->generateHashDataStringAndHash($params);
        $requestParams = $this->hashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->requestService->call($endpoint, $params, $requestParams);

        if ($response['CardHolder_Lookup_GetInfoResult']['Description'] == 'Success ') {
            return $response['CardHolder_Lookup_GetInfoResult']['ResultObject'][0];
        }

        $this->logger->addCritical(
            'Call Params: '.json_encode($params).' // Call Request Params: '.json_encode($requestParams).' // Response Service: '.json_encode($response),
            ['CardHolder_Lookup_GetInfo','ContisApiClient']
        );
        
        throw new PayProException("Bad Request", 400);
    }

    /**
     * @return array
     */
    public static function getConstants() : array
    {
        $clientClass = new \ReflectionClass(__CLASS__);
        return $clientClass->getConstants();
    }

    /**
     * @return array
     */
    public function getContisStatuses(): array
    {
        $constants = self::getConstants();
        $key_types = array_filter(array_flip($constants), function ($k) {
            return (bool)preg_match('/STATUS_/', $k);
        });

        $statuses = array_intersect_key($constants, array_flip($key_types));
        return $statuses;
    }

    /**
     * @return string
     */
    public function getAccountStatusFromContisStatus(string $contisStatus): string
    {
        return $this->accountCardHolderStatusMapping[$contisStatus];
    }
}
