<?php

namespace AppBundle\Service\Card;

use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use AppBundle\Service\ContisApiClient\Card as ContisCardApiClient;
use AppBundle\Exception\PayProException;
use AppBundle\Entity\Card;

/**
 * Class ActivateCardService
 * @package AppBundle\Service\Card
 */
class ActivateCardService
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
        $this->contisCardApiClient = $contisCardApiClient;
        $this->validationService = $validationService;
    }

    /**
     * This method activate the card in Contis and update the card in PayPro.
     * 
     * @param  int $userId
     * @return Array
     */
    public function execute(int $userId)
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$account = $user->getAccount()) {
            throw new PayProException('You must have an account to request a card', 400);
        }
        if (!$card = $account->getCard()) {
            throw new PayProException('You must request a card to activate it', 400);
        }

        $card->setIsActive(true);

        $errors = $this->validationService->validate($card);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }

        $response = $this->contisCardApiClient->getActivationCode($card);

        $card->setContisCardID($response['CardID']);
        $card->setContisCardActivationCode($response['CardActivationCode']);

        $response = $this->contisCardApiClient->activate($card);

        $this->cardRepository->save($card);

        return $card;
    }
}