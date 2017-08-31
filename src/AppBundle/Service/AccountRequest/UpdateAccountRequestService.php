<?php

namespace AppBundle\Service\AccountRequest;

use AppBundle\Entity\Account;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\MailingService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UpdateAccountRequestService
 */
class UpdateAccountRequestService
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
     * @param string $documentType
     * @param string $base64DocumentPicture1
     * @param string $base64DocumentPicture2
     * @return bool
     * @throws PayProException
     */
    public function execute(
        int $userId,
        string $documentType,
        string $base64DocumentPicture1,
        string $base64DocumentPicture2
    ): bool
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();
        $pictures = [];

        if (!$user) {
            throw new PayProException("User not found", 400);
        }

        if (!$account) {
            throw new PayProException("Account not found", 400);
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

        $account->setDocumentType($documentType);

        $errors = $this->validationService->validate($account);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath() . ': ' . $error->getMessage(), 400);
            }
        }

        return $this->mailingService->sendUpdateAccountRequest(
            $account,
            $pictures
        );
    }
}
