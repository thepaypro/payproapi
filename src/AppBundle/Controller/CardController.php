<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Controller\Traits\JWTResponseControllerTrait;

/**
 * Card controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/cards")
 */
class CardController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Request the card for an account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/request", name="card_request")
     * @Method("POST")
     */
    public function requestAction(UserInterface $user, Request $request) : JsonResponse
    {
        try {
            $card = $this->get('payproapi.request_card_service')->execute($user->getId());
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['card' => $card]);
    }

    /**
     * Activate the card for an account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/activation", name="card_activation")
     * @Method("POST")
     */
    public function activationAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }
}
