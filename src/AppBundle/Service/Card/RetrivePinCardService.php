<?php

namespace AppBundle\Service\Card;

use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;

use AppBundle\Service\ContisApiClient\Card as ContisCardApiClient;
use AppBundle\Exception\PayProException;
use AppBundle\Entity\Card;

/**
 * Class RetrivePinCardService
 * @package AppBundle\Service\Card
 */
class RetrivePinCardService
{
    protected $userRepository;
    protected $contisCardApiClient;

    /**
     * @param UserRepository        $userRepository
     * @param ContisCardApiClient   $contisCardApiClient
     */
    public function __construct(
        ObjectRepositoryInterface $userRepository,
        ContisCardApiClient $contisCardApiClient
    )
    {
        $this->userRepository = $userRepository;
        $this->contisCardApiClient = $contisCardApiClient;
    }

    /**
     * This method activate the card in Contis and update the card in PayPro.
     * 
     * @param  int $userId
     * @param  int $cvv2
     * @return array
     * @throws PayProException
     */
    public function execute(int $userId, int $cvv2)
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$account = $user->getAccount()) {
            throw new PayProException('You must have an account to request a card', 400);
        }
        if (!$card = $account->getCard()) {
            throw new PayProException('You must request a card to activate it', 400);
        }
        if (!$card->getIsActive()) {
            throw new PayProException('You must have an active card to get pin', 400);
        }
        if (!is_int($cvv2) || strlen($cvv2) != 3){
            throw new PayProException("invalid cvv2 format", 400);
        }

        $cardInfo = $this->contisCardApiClient->getInfo($card);

        return $this->contisCardApiClient->retrivePin($card, $cardInfo["HashCardNumber"], $cvv2);
    }
}
