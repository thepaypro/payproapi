<?php

namespace AppBundle\Service\BitcoinAccount;

use AppBundle\Entity\BitcoinAccount;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\BitcoinAccountRepository;
use AppBundle\Repository\UserRepository;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;

/**
 * Class CreateAccountService
 */
class CreateAccountService
{
    protected $bitcoinAccountRepository;
    protected $userRepository;
    protected $validationService;
    protected $bitcoinWalletApiClient;

    /**
     * CreateAccountService constructor.
     * @param BitcoinAccountRepository $bitcoinAccountRepository
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validationService
     * @param WalletInterface $bitcoinWalletApiClient
     */
    public function __construct(
        BitcoinAccountRepository $bitcoinAccountRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        WalletInterface $bitcoinWalletApiClient
    )
    {
        $this->bitcoinAccountRepository = $bitcoinAccountRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->bitcoinWalletApiClient = $bitcoinWalletApiClient;
    }

    /**
     * @param  int $userId
     * @param  string $address
     * @return BitcoinAccount
     * @throws PayProException
     */
    public function execute(
        int $userId
    ): BitcoinAccount
    {
        $user = $this->userRepository->findOneById($userId);

        if ($user->getBitcoinAccount()) {
            throw new PayProException("You already have a bitcoin account", 400);
        }

        $bitcoinAccount = new BitcoinAccount(
            $user
        );

        $errors = $this->validationService->validate($bitcoinAccount);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath() . ': ' . $error->getMessage(), 400);
            }
        }

        $user->setBitcoinAccount($bitcoinAccount);

        $this->bitcoinAccountRepository->save($bitcoinAccount);

        $wallet = $this->bitcoinWalletApiClient->create(
            $bitcoinAccount->getId(),
            $user->getUsername()
        );
        
        $bitcoinAccount->setAddress($wallet['address']);

        $this->bitcoinAccountRepository->save($bitcoinAccount);

        return $bitcoinAccount;
    }

}
