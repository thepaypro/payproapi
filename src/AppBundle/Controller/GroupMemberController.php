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
 * GroupMember controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/group-members")
 */
class GroupMemberController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns a list of group members with the given filters
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="group_members_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $groupMembers = [];
        return $this->JWTResponse($user, $groupMembers);
    }

    /**
     * Create a group member
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="group_members_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $groupMembers = []; 
        return $this->JWTResponse($user, $data);
    }

    /**
     * Update the information of the group member
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="group_members_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }

    /**
     * Delete the group member
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="group_members_delete")
     * @Method("DELETE")
     */
    public function deleteAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }
}
