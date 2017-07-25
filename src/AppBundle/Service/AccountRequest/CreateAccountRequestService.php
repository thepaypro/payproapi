<?php

namespace AppBundle\Service\AccountRequest;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use DateTime;

use AppBundle\Entity\Account;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Exception\PayProException;
use AppBundle\Service\MailingService;
/**
 * Class CreateAccountRequestService
 */
class CreateAccountRequestService
{
    protected $agreementRepository;
    protected $countryRepository;
    protected $accountRepository;
    protected $userRepository;
    protected $validationService;
    protected $mailingService;

    /**
     * @param EntityManager $em
     */
    public function __construct(
        AccountRepository $accountRepository,
        AgreementRepository $agreementRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        MailingService $mailingService
    ) {
        $this->accountRepository = $accountRepository;
        $this->agreementRepository = $agreementRepository;
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->mailingService = $mailingService;

    }

    /**
     * This method will create the cardHolder on Contis system and will persist the new account of the user.
     *
     * @param  int      $userId
     * @param  String   $forename
     * @param  String   $lastname
     * @param  String   $birthDate
     * @param  String   $documentType
     * @param  String   $documentNumber
     * @param  Int      $agreementId
     * @param  String   $street
     * @param  String   $buildingNumber
     * @param  String   $postcode
     * @param  String   $city
     * @param  String   $countryIso2
     * @return Account  $account
     */
    public function execute(
        int $userId,
        String $forename,
        String $lastname,
        String $birthDate,
        String $documentType,
        String $base64DocumentPicture1,
        String $base64DocumentPicture2,
        Int $agreementId,
        String $street,
        String $buildingNumber,
        String $postcode,
        String $city,
        String $countryIso2
    ) : bool
    {
        $agreement = $this->agreementRepository->findOneById($agreementId);
        $country = $this->countryRepository->findOneByIso2($countryIso2);
        $user = $this->userRepository->findOneById($userId);
        $documentNumber = 'isInPicture';

        if (!$user) {
            throw new PayProException("User not found", 400);
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
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }

        return $this->mailingService->sendAccountRequest($account, [$base64DocumentPicture1, $base64DocumentPicture2]);
    }
}
