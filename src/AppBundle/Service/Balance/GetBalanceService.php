<?php

namespace AppBundle\Service\Balance;

use AppBundle\Exception\PayProException;
use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;
use AppBundle\Service\ContisApiClient\Balance;

/**
 * Class GetBalanceService
 * @package AppBundle\Service\Balance
 */
class GetBalanceService
{
    protected $userRepository;
    protected $contisBalanceApiClient;

    /**
     * GetBalanceService constructor.
     * @param ObjectRepositoryInterface $userRepository
     * @param Balance $contisBalanceApiClient
     */
    public function __construct(
        ObjectRepositoryInterface $userRepository,
        Balance $contisBalanceApiClient)
    {
        $this->userRepository = $userRepository;
        $this->contisBalanceApiClient = $contisBalanceApiClient;
    }

    /**
     * Retrieve the balance of the user's account from Contis.
     *
     * @param  int $userId
     * @return float
     * @throws PayProException
     */
    public function execute(int $userId): float
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$account = $user->getAccount()) {
            throw new PayProException('Account not found', 404);
        }

        return $this->contisBalanceApiClient->get($account);
    }
}
