<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Traits\JWTResponseControllerTrait;
use AppBundle\Exception\PayProException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Wallet controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/bitcoin_wallet")
 */
class BitcoinWalletController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Create a transaction
     * @param  UserInterface $user
     * @return JsonResponse
     *
     * @Route("", name="bitcoin_wallet_create")
     * @Method("GET")
     */
    public function getAction(UserInterface $user): JsonResponse
    {
        try {
            $transaction = $this->get('payproapi.get_bitcoin_wallet_service')->execute(
                $user->getId()
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['transaction' => $transaction]);
    }
}
