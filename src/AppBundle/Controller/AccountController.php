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

        $response = $this->get('payproapi.contis_login_service')->login();

        dump($response);die();
        try {
            $responseData = $this->get('payproapi.account_manager')->createAccount(
                $requestData['forename'],
                $requestData['lastname'],
                $requestData['birthDate'],
                $requestData['documentType'],
                $requestData['documentNumber'],
                $requestData['agreement'],
                $requestData['principalAddress'],
                $requestData['secondaryAddress'],
                $requestData['postcode'],
                $requestData['city'],
                $requestData['country']
            );

        } catch (Exception $e) {
            $responseData = ['error' => $e->getErrorMessage()];
        }

        return $this->JWTResponse($user, $data);
    }

    /**
     * Update the information of the account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="accounts_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }
}
