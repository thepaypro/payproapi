<?php

namespace AppBundle\Service\Card;

use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use AppBundle\Service\ContisApiClient\Card as ContisCardApiClient;
use AppBundle\Exception\PayProException;
use AppBundle\Entity\Card;

/**
 * Class UpdateCardService
 * @package AppBundle\Service\Card
 */
class UpdateCardService
{
    protected $userRepository;
    protected $cardRepository;
    protected $contisCardApiClient;
    protected $validationService;

    /**
     * @param UserRepository        $userRepository
     * @param CardRepository        $cardRepository
     * @param ContisCardApiClient   $contisCardApiClient
     * @param ValidatorInterface    $validationService
     */
    public function __construct(
        ObjectRepositoryInterface $userRepository,
        ObjectRepositoryInterface $cardRepository,
        ContisCardApiClient $contisCardApiClient,
        ValidatorInterface $validationService
    )
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->contisCardApiClient = $contisCardApiClient;
        $this->validationService = $validationService;
    }

    /**
     * This method disable/enable the card in Contis and PayPro.
     * 
     * @param  int  $userId
     * @param  bool $isEnabled
     * @return Card
     */
    public function execute(int $userId, bool $isEnabled) : Card
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$account = $user->getAccount()) {
            throw new PayProException('You must have an account to request a card', 400);
        }
        if (!$card = $account->getCard()) {
            throw new PayProException('You must request a card to update it', 400);
        }
        if (!$card->getIsActive()) {
            throw new PayProException('You must activate the card to update it', 400);
        }
        if ($card->getIsEnabled() == $isEnabled) {
            throw new PayProException('Card is already in this status', 400);
        }

        $card->setIsEnabled($isEnabled);

        $errors = $this->validationService->validate($card);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }

        $response = $this->contisCardApiClient->update($card);

        $this->cardRepository->save($card);

        return $card;
    }
}
