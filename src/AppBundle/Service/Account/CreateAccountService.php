<?php

namespace AppBundle\Service\Account;

use AppBundle\Entity\Account;
use AppBundle\Event\AccountEvent;
use AppBundle\Event\AccountEvents;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\ContisApiClient\Account as ContisAccountApiClient;
use DateTime;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    protected $dispatcher;

    /**
     * CreateAccountService constructor.
     * @param AccountRepository $accountRepository
     * @param AgreementRepository $agreementRepository
     * @param CountryRepository $countryRepository
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validationService
     * @param ContisAccountApiClient $contisAccountApiClient
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        AccountRepository $accountRepository,
        AgreementRepository $agreementRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        ContisAccountApiClient $contisAccountApiClient,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->accountRepository = $accountRepository;
        $this->agreementRepository = $agreementRepository;
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->contisAccountApiClient = $contisAccountApiClient;
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * @param  int $userId
     * @param  string $forename
     * @param  string $lastname
     * @param  string $birthDate
     * @param  string $documentType
     * @param  string $documentNumber
     * @param  Int $agreementId
     * @param  string $street
     * @param  string $buildingNumber
     * @param  string $postcode
     * @param  string $city
     * @param  string $countryIso2
     * @param string $deviceToken
     * @return Account
     * @throws PayProException
     */
    public function execute(
        int $userId,
        string $forename,
        string $lastname,
        string $birthDate,
        string $documentType,
        string $documentNumber,
        int $agreementId,
        string $street,
        string $buildingNumber,
        string $postcode,
        string $city,
        string $countryIso2,
        string $deviceToken
    ): Account
    {
        $agreement = $this->agreementRepository->findOneById($agreementId);
        $country = $this->countryRepository->findOneByIso2($countryIso2);
        $user = $this->userRepository->findOneById($userId);
        $birthDate = DateTime::createFromFormat('d/m/Y', $birthDate);

        if (!$country) {
            throw new PayProException("Country not found", 400);
        }
        if (!$agreement) {
            throw new PayProException("Agreement not found", 400);
        }
        if ($user->getAccount()) {
            throw new PayProException("You already have an account", 400);
        }

        if (!is_string($forename) || strlen($forename) > 255){
            throw new PayProException("invalid forename format", 400);
        }
        if (!is_string($lastname) || strlen($lastname) > 255){
            throw new PayProException("invalid lastname format", 400);
        }
        if (!is_string($documentNumber) || strlen($documentNumber) > 255){
            throw new PayProException("invalid documentNumber format", 400);
        }
        if (!is_string($street) || strlen($street) > 255){
            throw new PayProException("invalid street format", 400);
        }
        if (!is_string($buildingNumber) || strlen($buildingNumber) > 255){
            throw new PayProException("invalid buildingNumber format", 400);
        }
        if (!is_string($postcode) || strlen($postcode) > 255){
            throw new PayProException("invalid postcode format", 400);
        }
        if (!is_string($city) || strlen($city) > 255){
            throw new PayProException("invalid city format", 400);
        }
        if (!is_string($deviceToken) || strlen($deviceToken) > 255){
            throw new PayProException("invalid deviceToken format", 400);
        }

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
            $country,
            Account::STATUS_PENDING
        );

        $errors = $this->validationService->validate($account);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath() . ': ' . $error->getMessage(), 400);
            }
        }

        $response = $this->contisAccountApiClient->create($account);

        $account->setCardHolderId($response['CardHolderID']);
        $account->setAccountNumber($response['AccountNumber']);
        $account->setSortCode($response['SortCode']);
        $user->setAccount($account);

        $this->accountRepository->save($account);

        $this->dispatcher->dispatch(
            AccountEvents::ACCOUNT_CREATED,
            new AccountEvent($account, $deviceToken)
        );

        return $account;
    }
}
