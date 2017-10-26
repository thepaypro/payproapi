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
     * Activate the card for an account
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     *
     * @Route("/activation", name="card_activation")
     * @Method("POST")
     */
    public function activationAction(UserInterface $user, Request $request): JsonResponse
    {
        $card_activation_code = $request->request->get('card_activation_code');
        $PAN = $request->request->get('PAN');

        try {
            $card = $this->get('payproapi.activate_card_service')->execute(
                $user->getId(),
                $card_activation_code,
                $PAN
                );
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
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     * @throws PayProException
     * @Route("/retrive-pin", name="retrive_pin")
     * @Method("POST")
     */
    public function retrivePinAction(UserInterface $user, Request $request): JsonResponse
    {
        $cvv2 = $request->request->get('cvv2');
        
        try {
            $pin = $this->get('payproapi.retrive_pin_card_service')->execute(
                $user->getId(),
                isset($cvv2)?$cvv2:00
            );

            $pin = strip_tags($pin);

         } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['pin' => $pin]);
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
