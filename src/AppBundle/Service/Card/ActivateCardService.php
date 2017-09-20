<?php

namespace AppBundle\Service\Card;

use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use AppBundle\Service\ContisApiClient\Card as ContisCardApiClient;
use AppBundle\Exception\PayProException;
use AppBundle\Entity\Card;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Event\CardActivationCodeEvent;
use AppBundle\Event\CardActivationCodeEvents;

/**
 * Class ActivateCardService
 * @package AppBundle\Service\Card
 */
class ActivateCardService
{
    protected $dispatcher;
    protected $userRepository;
    protected $cardRepository;
    protected $contisCardApiClient;
    protected $validationService;

    /**
     * @param UserRepository        $userRepository
     * @param CardRepository        $cardRepository
     * @param ContisCardApiClient   $contisCardApiClient
     * @param ValidatorInterface    $validationService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ObjectRepositoryInterface $userRepository,
        ObjectRepositoryInterface $cardRepository,
        ContisCardApiClient $contisCardApiClient,
        ValidatorInterface $validationService,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->contisCardApiClient = $contisCardApiClient;
        $this->validationService = $validationService;
        $this->dispatcher = $eventDispatcher;
    }
  
    /**
     * This method activate the card in Contis and update the card in PayPro.
     * 
     * @param  int $userId
     * @return array
     * @throws PayProException
     */
    public function execute(
        int $userId,
        string $card_activation_code,
        int $pan
    )
    {
        $user = $this->userRepository->findOneById($userId);
        if (!$account = $user->getAccount()) {
            throw new PayProException('You must have an account to request a card', 400);
        }
        if (!$card = $account->getCard()) {
            throw new PayProException('You must request a card to activate it', 400);
        }
        if($card->getIsActive()){
            throw new PayProException('Your card it\'s already active', 400);
        }
        if($card_activation_code != $card->getContisCardActivationCode()){
            throw new PayProException('Your card activation code is incorrect', 400);
        }
        $response = $this->contisCardApiClient->activate($card,$pan);
        
        $card->setIsActive(true);
        $errors = $this->validationService->validate($card);
        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }
        $this->cardRepository->save($card);
        return $card;
    }

    /**
     * This method request a activation code in Contis.
     * 
     * @param  int $userId
     * @return array
     * @throws PayProException
     */
    public function getActivationCode(int $userId)
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$account = $user->getAccount()) {
            throw new PayProException('You must have an account to request a card', 400);
        }
        if (!$card = $account->getCard()) {
            throw new PayProException('You must request a card to activate it', 400);
        }
        if($card->getIsActive()){
            throw new PayProException('Your card it\'s already active', 400);
        }

        if(!$card->getContisCardActivationCode()){
            $response = $this->contisCardApiClient->getActivationCode($card);
            $card->setContisCardActivationCode($response['CardActivationCode']);
            if(!$card->getContisCardId()){
                $card->setContisCardId($response['CardID']);
            }
        }

        $errors = $this->validationService->validate($card);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 400);
            }
        }

        $this->cardRepository->save($card);

        return $card;
    }

    /**
     * This method will dispatch an event of the card activation code
     * 
     * @param int $userId
     * @return array
     * @throws PayProException 
     */
    public function sendActivationCodeToUser(int $userId){

        $account = $this->userRepository->findOneById($userId)->getAccount();
        $phoneNumber = $account->getUsers()[0]->getUsername();
        $cardActivationCode = $account->getCard()->getContisCardActivationCode();

        $this->dispatcher->dispatch(
            CardActivationCodeEvents::CARD_ACTIVATION_CODE_REQUESTED,
            new CardActivationCodeEvent($phoneNumber, $cardActivationCode)
        );
    }


}
