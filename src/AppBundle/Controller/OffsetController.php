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
 * Offset controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/offsets")
 */
class OffsetController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns a list of offsets with the given filters
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="offsets_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $offsets = [];
        return $this->JWTResponse($user, $offsets);
    }

    /**
     * Create a offset
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="offsets_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }
}
