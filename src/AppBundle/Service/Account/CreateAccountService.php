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
     * @param string $deviceId
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
        string $deviceId
    ): Account
    {
        $agreement = $this->agreementRepository->findOneById($agreementId);
        $country = $this->countryRepository->findOneByIso2($countryIso2);
        $user = $this->userRepository->findOneById($userId);

        if (!$country) {
            throw new PayProException("Country not found", 400);
        }
        if (!$agreement) {
            throw new PayProException("Agreement not found", 400);
        }
        if ($user->getAccount()) {
            throw new PayProException("You already have an account", 400);
        }

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
            new AccountEvent($account, $deviceId)
        );

        return $account;
    }
}
