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
use \Pusher;

class UserController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/users", name="users_list")
     * @Method("GET")
     */
    public function indexAction(
        UserInterface $user,
        Request $request
    ) : JsonResponse
    {
        $filters = $request->query->all();

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $userRepository->findUserswithUsernameIn($filters['phoneNumbers']);

        return $this->JWTResponse($user, ['users' => $users]);
    }
}
