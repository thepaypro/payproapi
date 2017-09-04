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
 * Account controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/accounts")
 */
class AccountController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns the information of a given account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="accounts_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user, Request $request) : JsonResponse
    {
        $account = null;
        return $this->JWTResponse($user, ['account' => $account]);
    }

    /**
     * Create an account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     *
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("", name="accounts_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $requestData = $request->request->all();

        try {
            $account = $this->get('payproapi.create_account_service')->execute(
                in_array('ROLE_ADMIN', $user->getRoles()) ? $requestData['userId'] : $user->getId(),
                $requestData['forename'],
                $requestData['lastname'],
                $requestData['birthDate'],
                $requestData['documentType'],
                $requestData['documentNumber'],
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

        return $this->JWTResponse($user, ['account' => $account]);
    }

    /**
     * Update the information of the account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{accountId}", name="accounts_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request) : JsonResponse
    {

        $requestData = $request->request->all();
        $accountId = $request->attributes->get('accountId');

        try {
            $account = $this->get('payproapi.update_account_service')->execute(
                $accountId,
                in_array('ROLE_ADMIN', $user->getRoles()) ? $requestData['userId'] : $user->getId(),
                isset($requestData['forename']) ? $requestData['forename'] : null,
                isset($requestData['lastname']) ? $requestData['lastname'] : null,
                isset($requestData['email']) ? $requestData['email'] : null,
                isset($requestData['birthDate']) ? $requestData['birthDate'] : null,
                isset($requestData['documentType']) ? $requestData['documentType'] : null,
                isset($requestData['documentNumber']) ? $requestData['documentNumber'] : null,
                isset($requestData['agreement']) ? $requestData['agreement'] : null,
                isset($requestData['street']) ? $requestData['street'] : null,
                isset($requestData['buildingNumber']) ? $requestData['buildingNumber'] : null,
                isset($requestData['postcode']) ? $requestData['postcode'] : null,
                isset($requestData['city']) ? $requestData['city'] : null,
                isset($requestData['country']) ? $requestData['country'] : null
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['account' => $account]);
    }
}
