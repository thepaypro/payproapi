<?php

namespace AppBundle\Service\Account;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use AppBundle\Entity\Account;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Service\Contis\RequestService;
use AppBundle\Service\Contis\HashingService;
use AppBundle\Service\Contis\AuthenticationService;

/**
 * Class CreateAccountService
 */
class CreateAccountService
{
    protected $agreementRepository;
    protected $countryRepository;
    protected $validationService;
    protected $contisRequestService;
    protected $contisAuthenticationService;
    protected $contisHashingService;

    /**
     * @param EntityManager $em
     */
    public function __construct(
        AgreementRepository $agreementRepository,
        CountryRepository $countryRepository,
        ValidatorInterface $validationService,
        RequestService $contisRequestService,
        AuthenticationService $contisAuthenticationService,
        HashingService $contisHashingService
    ) {
        $this->agreementRepository = $agreementRepository;
        $this->countryRepository = $countryRepository;
        $this->validationService = $validationService;
        $this->contisRequestService = $contisRequestService;
        $this->contisAuthenticationService = $contisAuthenticationService;
        $this->contisHashingService = $contisHashingService;
    }

    /**
     * This method will create the cardHolder on Contis system and will persist the new account of the user.
     * @param  Account $account
     * @return something to reflect if something goes ok or not
     */
    public function execute(
        String $forename,
        String $lastname,
        String $birthDate,
        String $documentType,
        String $documentNumber,
        Int $agreementId,
        String $principalAddress,
        String $secondaryAddress,
        String $postcode,
        String $city,
        Int $countryId
    ) {
        $agreement = $this->agreementRepository->findOneById($agreementId);
        $country = $this->countryRepository->findOneById($countryId);

        $account = new Account(
            $forename,
            $lastname,
            $birthDate,
            $documentType,
            $documentNumber,
            $agreement,
            $principalAddress,
            $secondaryAddress,
            $postcode,
            $city,
            $country
        );

        $errors = $this->validationService->validate($account);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new BadRequestHttpException($error->getMessage());
            }
        }

        $params = [
            'AgreementCode' => $account->getAgreement()->getContisAgreementCode(),
            'FirstName' => $account->getForename(),
            'LastName' => $account->getLastname(),
            'Gender' => 'N',
            'DOB' => $account->getBirthdate(),
            'Street' => $account->getPrincipalAddress(),
            'City' => $account->getCity(),
            'Country' => $account->getCountry()->getIso3(),
            'Postcode' => $account->getPostcode(),
            'IsMain' => 1,
            'Relationship' => 'self',
        ];

        $params['Token'] = $this->contisAuthenticationService->getAuthenticationToken();

        $requestParams = [
            'Token' => $params['Token']
        ];

        $params = [$this->contisHashingService->generateHashDataStringAndHash($params)];
        $requestParams = $this->contisHashingService->generateHashDataStringAndHash($requestParams);

        $response = $this->contisRequestService->call('CardHolder_Create', $params, $requestParams);

        dump($response);die();

        return $response;
    }
}
