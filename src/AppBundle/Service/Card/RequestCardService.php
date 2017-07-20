<?php

namespace AppBundle\Service\Card;

use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use AppBundle\Service\ContisApiClient\Card as ContisCardApiClient;
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
    protected $validationService;

    /**
     * @param UserRepository        $userRepository
     * @param ContisCardApiClient   $userRepository
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
     * This method create a Card entity, persist it and request a card to Contis.
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

        $card = new Card($account, false, false);

        $errors = $this->validationService->validate($card);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }

        $response = $this->contisCardApiClient->request($card);

        $card->setContisCardId($response['ID']);

        $this->cardRepository->save($card);

        return $card;

    }
}
