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

class AccountController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns the information of a given account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/accounts/{id}", name="account_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }

    /**
     * Createan account
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Security("has_role('ROLE_USER')")
     * @Route("/accounts", name="accounts_list")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $filters = $request->query->all();

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $accounts = $userRepository->findaccountswithUsernameIn($filters['phoneNumbers']);

        return $this->JWTResponse($user, ['accounts' => $accounts]);
    }

    /**
     * Update the information of the user
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Security("has_role('ROLE_USER')")
     * @Route("/accounts/{id}", name="user_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }
}
