<?php

namespace AppBundle\Service\AccountRequest;

use AppBundle\Entity\Account;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\MailingService;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * CreateAccountRequestService constructor.
     * @param AccountRepository $accountRepository
     * @param AgreementRepository $agreementRepository
     * @param CountryRepository $countryRepository
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validationService
     * @param MailingService $mailingService
     */
    public function __construct(
        AccountRepository $accountRepository,
        AgreementRepository $agreementRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        MailingService $mailingService
    )
    {
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
     * @param int $userId
     * @param string $forename
     * @param string $lastname
     * @param string $birthDate
     * @param string $documentType
     * @param string $base64DocumentPicture1
     * @param string $base64DocumentPicture2
     * @param Int $agreementId
     * @param string $street
     * @param string $buildingNumber
     * @param string $postcode
     * @param string $city
     * @param string $countryIso2
     * @param string $deviceToken
     * @return bool
     * @throws PayProException
     */
    public function execute(
        int $userId,
        string $forename,
        string $lastname,
        string $birthDate,
        string $documentType,
        string $base64DocumentPicture1,
        string $base64DocumentPicture2,
        Int $agreementId,
        string $street,
        string $buildingNumber,
        string $postcode,
        string $city,
        string $countryIso2,
        string $deviceToken
    ): bool
    {
        $agreement = $this->agreementRepository->findOneById($agreementId);
        $country = $this->countryRepository->findOneByIso2($countryIso2);
        $user = $this->userRepository->findOneById($userId);
        $documentNumber = 'isInPicture';
        $pictures = [];

        if (!$user) {
            throw new PayProException("User not found", 400);
        }

        if ($user->getAccount()) {
            throw new PayProException("You already have an account", 400);
        }
        if (!$country) {
            throw new PayProException("Country not found", 400);
        }
        if (!$agreement) {
            throw new PayProException("Agreement not found", 400);
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

        if (imagecreatefromstring(base64_decode($base64DocumentPicture1))) {
            $pictures[] = $base64DocumentPicture1;
        } else {
            throw new PayProException('Invalid image1', 400);
        }

        if ($documentType != Account::DOCUMENT_TYPE_PASSPORT) {
            if (imagecreatefromstring(base64_decode($base64DocumentPicture2))) {
                $pictures[] = $base64DocumentPicture2;
            } else {
                throw new PayProException('Invalid image2', 400);
            }
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
            $country,
            Account::STATUS_PENDING
        );

        $errors = $this->validationService->validate($account);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath() . ': ' . $error->getMessage(), 400);
            }
        }

        return $this->mailingService->sendCreateAccountRequest(
            $account,
            $pictures,
            $deviceToken
        );
    }
}
