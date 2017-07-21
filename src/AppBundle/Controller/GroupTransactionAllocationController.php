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
 * GroupTransactionAllocation controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/group-transaction-allocations")
 */
class GroupTransactionAllocationController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns a list of a transactions allocated in a group, with the given filters
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="group_transaction_allocations_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $groupTransactionAllocations = [];
        return $this->JWTResponse($user, $GroupTransactionAllocations);
    }

    /**
     * Assigns a transaction to a group creating a group transaction allocation.
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="group_transaction_allocations_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $groupTransactionAllocations = [];
        return $this->JWTResponse($user, $data);
    }

    /**
     * Unassigns a transaction of a group
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="group_transaction_allocations_delete")
     * @Method("DELETE")
     */
    public function deleteAction(UserInterface $user, Request $request) : JsonResponse
    {
        $data = [];
        return $this->JWTResponse($user, $data);
    }
}
