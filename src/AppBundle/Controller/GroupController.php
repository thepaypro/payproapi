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
 * Group controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/groups")
 */
class GroupController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns the information of a given group
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="groups_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user, Request $request) : JsonResponse
    {
        $group = null;
        return $this->JWTResponse($user, ['group' => $group]);
    }

    /**
     * Returns a list of groups with the given filters
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="groups_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $groups = [];
        return $this->JWTResponse($user, $groups);
    }

    /**
     * Create a group
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="groups_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $groups = []; 
        return $this->JWTResponse($user, $data);
    }

    /**
     * Update the information of the group
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="groups_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }

    /**
     * Delete the group
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="groups_delete")
     * @Method("DELETE")
     */
    public function deleteAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }
}
