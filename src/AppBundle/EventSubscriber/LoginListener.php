<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Controller\Traits\JWTResponseControllerTrait;
use AppBundle\Service\Balance\GetBalanceService;
use AppBundle\Service\BitcoinWallet\GetBitcoinWalletService;
use Symfony\Component\HttpFoundation\Response;

class LoginListener{
    protected $userManager;
    private $logger;
    protected $balanceService;
    protected $bitcoinWalletService;


    public function __construct(
        UserManagerInterface $userManager,
        Logger $logger,
        GetBalanceService $balanceService,
        GetBitcoinWalletService $bitcoinWalletService
    ){
        $this->userManager = $userManager;
        $this->logger = $logger;
        $this->balanceService = $balanceService;
        $this->bitcoinWalletService = $bitcoinWalletService;
    }

    public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
    {
        try {
            // $user = $event->getAuthenticationToken()->getUser();
            // $this->logger->info('user logged in');
            // $this->balanceService->execute($user->getId());
            // $this->bitcoinWalletService->execute($user->getId());
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return new Response();
    }
}