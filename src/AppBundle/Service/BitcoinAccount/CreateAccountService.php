<?php

namespace AppBundle\Service\BitcoinAccount;

use AppBundle\Entity\BitcoinAccount;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\BitcoinAccountRepository;
use AppBundle\Repository\UserRepository;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateAccountService
 */
class CreateBitcoinAccountService
{
    protected $bitcoinAccountRepository;
    protected $userRepository;
    protected $validationService;

    /**
     * CreateAccountService constructor.
     * @param BitcoinAccountRepository $bitcoinAccountRepository
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validationService
     */
    public function __construct(
        BitcoinAccountRepository $bitcoinAccountRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService
    )
    {
        $this->bitcoinAccountRepository = $bitcoinAccountRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
    }

    /**
     * @param  int $userId
     * @param  string $address
     * @param  string $deviceToken
     * @return BitcoinAccount
     * @throws PayProException
     */
    public function execute(
        int $userId,
        string $address,
        string $deviceToken
    ): BitcoinAccount
    {
        $user = $this->userRepository->findOneById($userId);

        if ($user->getBitcoinAccount()) {
            throw new PayProException("You already have a bitcoin account", 400);
        }

        if (!is_string($deviceToken) || strlen($deviceToken) > 255){
            throw new PayProException("invalid deviceToken format", 400);
        }

        $bitcoinAccount = new BitcoinAccount(
            $user,
            $address
        );

        $errors = $this->validationService->validate($bitcoinAccount);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath() . ': ' . $error->getMessage(), 400);
            }
        }

        $user->setBitcoinAccount($bitcoinAccount);

        $this->bitcoinAccountRepository->save($bitcoinAccount);

        return $bitcoinAccount;
    }
}
