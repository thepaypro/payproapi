<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Controller\Traits\JWTResponseControllerTrait;
use AppBundle\Exception\PayProException;

/**
 * Balance controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/balance")
 */
class BalanceController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns the information of a given account
     * @param UserInterface $user
     * @return JsonResponse
     *
     * @Route("", name="balance_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user): JsonResponse
    {
        try {
            $balance = $this->get('payproapi.get_balance_service')->execute($user->getId());
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['balance' => $balance]);
    }
}
