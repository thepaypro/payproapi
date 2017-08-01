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
use AppBundle\Exception\PayProException;

/**
 * AccountRequest controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/account-requests")
 */
class AccountRequestController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Create an account request
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     *
     * @Route("", name="account_requests_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request): JsonResponse
    {
        $requestData = $request->request->all();

        try {
            $response = $this->get('payproapi.create_account_request_service')->execute(
                $user->getId(),
                $requestData['forename'],
                $requestData['lastname'],
                $requestData['birthDate'],
                $requestData['documentType'],
                $requestData['documentPicture1'],
                $requestData['documentPicture2'],
                $requestData['agreement'],
                $requestData['street'],
                $requestData['buildingNumber'],
                $requestData['postcode'],
                $requestData['city'],
                $requestData['country'],
                $requestData['deviceToken']
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['emailSended' => $response]);
    }
}
