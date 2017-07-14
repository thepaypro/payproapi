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
     * @Route("", name="accounts_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $requestData = $request->request->all();

        try {
            $account = $this->get('payproapi.create_account_service')->execute(
                $user->getId(),
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
                $requestData['country']
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
        $accountId = $request->attributes->get('accountId');
        $requestData = $request->request->all();

        try {
            $account = $this->get('payproapi.update_account_service')->execute(
                $accountId,
                $user->getId(),
                $requestData['forename'] ? $requestData['forename'] : null,
                $requestData['lastname'] ? $requestData['lastname'] : null,
                $requestData['birthDate'] ? $requestData['birthDate'] : null,
                $requestData['documentType'] ? $requestData['documentType'] : null,
                $requestData['documentNumber'] ? $requestData['documentNumber'] : null,
                $requestData['agreement'] ? $requestData['agreement'] : null,
                $requestData['street'] ? $requestData['street'] : null,
                $requestData['buildingNumber'] ? $requestData['buildingNumber'] : null,
                $requestData['postcode'] ? $requestData['postcode'] : null,
                $requestData['city'] ? $requestData['city'] : null,
                $requestData['country'] ? $requestData['country'] : null
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['account' => $account]);
    }
}
