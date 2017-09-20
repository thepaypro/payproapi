<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Controller\Traits\JWTResponseControllerTrait;
use AppBundle\Exception\PayProException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param  Request $request
     * @return JsonResponse
     *
     * @Route("/request", name="card_request")
     * @Method("POST")
     */
    public function requestAction(UserInterface $user, Request $request): JsonResponse
    {
        try {
            $card = $this->get('payproapi.request_card_service')->execute($user->getId());
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['card' => $card]);
    }


    /**
     * Update the card
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     * @throws PayProException
     * @Route("/{id}", name="card_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request): JsonResponse
    {
        $enabled = $request->request->get('enabled');

        if (!is_bool($enabled)) {
            throw new PayProException("enabled must be boolean");
        }

        try {
            $card = $this->get('payproapi.update_card_service')->execute(
                $user->getId(),
                $enabled
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['card' => $card]);
    }

    /**
     * Sends the card activation code to the user
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     *
     * @Route("/requestActivationCode", name="request_activation_code")
     * @Method("POST")
     */
    public function requestActivationCode(UserInterface $user, Request $request): JsonResponse
    {
        try {
            $card = $this->get('payproapi.activate_card_service')->getActivationCode($user->getId());
            $this->get('payproapi.activate_card_service')->sendActivationCodeToUser($user->getId());
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['card' => $card]);
    }
}
