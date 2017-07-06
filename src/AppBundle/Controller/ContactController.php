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
 * Contacts controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/contacts")
 */
class ContactController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Create and returns a list of contacts
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="contacts_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $phoneNumbers = $request->request->all();

        $contacts = $this->get('payproapi.contact_manager')->createList($user->getUsername(), $phoneNumbers);

        return $this->JWTResponse($user, ['contacts' => $contacts]);
    }
}