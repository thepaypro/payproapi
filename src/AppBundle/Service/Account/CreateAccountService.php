<?php

namespace AppBundle\Service\Account;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use DateTime;

use AppBundle\Entity\Account;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\ContisApiClient\Account as ContisAccountApiClient;

/**
 * Class CreateAccountService
 */
class CreateAccountService
{
    protected $agreementRepository;
    protected $countryRepository;
    protected $accountRepository;
    protected $userRepository;
    protected $validationService;
    protected $contisAccountApiClient;

    /**
     * @param EntityManager $em
     */
    public function __construct(
        AgreementRepository $agreementRepository,
        CountryRepository $countryRepository,
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        ContisAccountApiClient $contisAccountApiClient
    ) {
        $this->agreementRepository = $agreementRepository;
        $this->countryRepository = $countryRepository;
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->contisAccountApiClient = $contisAccountApiClient;

    }

    /**
     * This method will create the cardHolder on Contis system and will persist the new account of the user.
     * @param  Account $account
     * @return something to reflect if something goes ok or not
     */
    public function execute(
        int $userId,
        String $forename,
        String $lastname,
        String $birthDate,
        String $documentType,
        String $documentNumber,
        Int $agreementId,
        String $street,
        String $buildingNumber,
        String $postcode,
        String $city,
        Int $countryId
    ) {
        $agreement = $this->agreementRepository->findOneById($agreementId);
        $country = $this->countryRepository->findOneById($countryId);
        $user = $this->userRepository->findOneById($userId);

        $birthDate = new DateTime($birthDate);

        $account = new Account(
            $user,
            $forename,
            $lastname,
            $birthDate,
            $documentType,
            $documentNumber,
            $agreement,
            $street,
            $buildingNumber,
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

        $response = $this->contisAccountApiClient->create($account);

        $account->setCardHolderId($response['CardHolderID']);
        $account->setAccountNumber($response['AccountNumber']);
        $account->setSortCode($response['SortCode']);

        $this->accountRepository->save($account);

        return $account;
    }
}