<?php

namespace AppBundle\Service\AccountRequest;

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

        if (!$user) {
            throw new PayProException("User not found", 400);
        }
        if (!$account) {
            throw new PayProException("Account not found", 400);
        }
        if (!imagecreatefromstring(base64_decode($base64DocumentPicture1))) {
            throw new PayProException('Invalid image', 400);
        }
        if (!$base64DocumentPicture2 == "" && !$documentType == "PASSPORT") {
            if (!imagecreatefromstring(base64_decode($base64DocumentPicture2))) {
                throw new PayProException('Invalid image', 400);
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
            [
                $base64DocumentPicture1,
                $base64DocumentPicture2
            ]
        );
    }
}
