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
 * User controller.
 *
 * @Route("/users")
 */
class UserController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns the information of user with the given id
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user, Request $request) : JsonResponse
    {
        $id = $request->attributes->get('id');

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $userRepository->findOneById($id);

        return $this->JWTResponse($user, ['user' => $user]);
    }

    /**
     * Returns the information of the users that match the filters
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Security("has_role('ROLE_USER')")
     * @Route("", name="users_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $filters = $request->query->all();

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $userRepository->findUserswithUsernameIn($filters['phoneNumbers']);

        return $this->JWTResponse($user, ['users' => $users]);
    }

    /**
     * Update the information of the user
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}", name="user_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }


}
