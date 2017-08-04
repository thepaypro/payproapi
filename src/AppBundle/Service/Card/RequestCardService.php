<?php

namespace AppBundle\Service\Card;

use AppBundle\Service\Balance\GetBalanceService;
use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use AppBundle\Service\ContisApiClient\Card as ContisCardApiClient;
use AppBundle\Exception\PayProException;
use AppBundle\Entity\Card;

/**
 * Class RequestCardService
 * @package AppBundle\Service\Card
 */
class RequestCardService
{
    protected $userRepository;
    protected $cardRepository;
    protected $contisCardApiClient;
    protected $getBalanceService;
    protected $validationService;

    /**
     * @param ObjectRepositoryInterface $userRepository
     * @param ObjectRepositoryInterface $cardRepository
     * @param ContisCardApiClient $contisCardApiClient
     * @param GetBalanceService $getBalanceService
     * @param ValidatorInterface $validationService
     */
    public function __construct(
        ObjectRepositoryInterface $userRepository,
        ObjectRepositoryInterface $cardRepository,
        ContisCardApiClient $contisCardApiClient,
        GetBalanceService $getBalanceService,
        ValidatorInterface $validationService
    )
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->contisCardApiClient = $contisCardApiClient;
        $this->getBalanceService = $getBalanceService;
        $this->validationService = $validationService;
    }

    /**
     * This method create a Card entity, persist it and request a card to Contis.
     *
     * @param  int $userId
     * @return Card
     * @throws PayProException
     */
    public function execute(int $userId) : Card
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$account = $user->getAccount()) {
            throw new PayProException('You must have an account to request a card', 400);
        }
        if ($account->getAgreement()->getNewCardCharge() > $this->getBalanceService->execute($user->getId())) {
            throw new PayProException('Insufficient funds', 400);
        }

        $card = new Card($account, false, false);

        $errors = $this->validationService->validate($card);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }

        $response = $this->contisCardApiClient->request($card);

        $this->cardRepository->save($card);

        return $card;
    }
}
