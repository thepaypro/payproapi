<?php

namespace AppBundle\Service\Account;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use DateTime;

use AppBundle\Entity\Account;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\ContisApiClient\Account as ContisAccountApiClient;
use AppBundle\Exception\PayProException;

/**
 * Class UpdateAccountService
 */
class UpdateAccountService
{
    protected $agreementRepository;
    protected $countryRepository;
    protected $accountRepository;
    protected $userRepository;
    protected $validationService;
    protected $contisAccountApiClient;

    /**
     * UpdateAccountService constructor.
     * @param AccountRepository $accountRepository
     * @param AgreementRepository $agreementRepository
     * @param CountryRepository $countryRepository
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validationService
     * @param ContisAccountApiClient $contisAccountApiClient
     */
    public function __construct(
        AccountRepository $accountRepository,
        AgreementRepository $agreementRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        ContisAccountApiClient $contisAccountApiClient
    ) {
        $this->accountRepository = $accountRepository;
        $this->agreementRepository = $agreementRepository;
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->contisAccountApiClient = $contisAccountApiClient;
    }

    /**
     * @param int|null $accountId
     * @param int|null $userId
     * @param string|null $forename
     * @param string|null $lastname
     * @param string|null $email
     * @param string|null $birthDate
     * @param string|null $documentType
     * @param string|null $documentNumber
     * @param int|null $agreementId
     * @param string|null $street
     * @param string|null $buildingNumber
     * @param string|null $postcode
     * @param string|null $city
     * @param string|null $countryIso2
     * @return Account
     * @throws PayProException
     */
    public function execute(
        int $accountId = null,
        int $userId = null,
        string $forename = null,
        string $lastname = null,
        string $email = null,
        string $birthDate = null,
        string $documentType = null,
        string $documentNumber = null,
        int $agreementId = null,
        string $street = null,
        string $buildingNumber = null,
        string $postcode = null,
        string $city = null,
        string $countryIso2 = null
    ) : Account
    {
        $account = $this->accountRepository->findOneById($accountId);
        $user = $this->userRepository->findOneById($userId);

        if (!$account || !$account->getUsers()->contains($user)) {
            throw new PayProException("Account not found", 404);
        }

        $account->setForename($forename ? $forename : $account->getForename());
        $account->setLastname($lastname ? $lastname : $account->getLastname());
        $account->setEmail($email ? $email : $account->getEmail());
        $account->setBirthDate($birthDate ? new DateTime($birthDate) : $account->getBirthDate());
        $account->setDocumentType($documentType ? $documentType : $account->getDocumentType());
        $account->setDocumentNumber($documentNumber ? $documentNumber : $account->getDocumentNumber());

        if ($agreementId) {
            $agreement = $this->agreementRepository->findOneById($agreementId);
            $account->setAgreement($agreement);
        }

        $account->setStreet($street ? $street : $account->getStreet());
        $account->setBuildingNumber($buildingNumber ? $buildingNumber : $account->getBuildingNumber());
        $account->setPostcode($postcode ? $postcode : $account->getPostcode());
        $account->setCity($city ? $city : $account->getCity());

        if ($countryIso2) {
            $country = $this->countryRepository->findOneByIso2($countryIso2);
            $account->setCountry($country);
        }

        $errors = $this->validationService->validate($account);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }

        $this->contisAccountApiClient->update($account);

        $this->accountRepository->save($account);

        return $account;
    }
}
