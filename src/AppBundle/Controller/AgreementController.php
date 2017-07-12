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
use Exception;

/**
 * Agreement controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/agreements")
 */
class AgreementController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns the information of a given agreement
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="agreements_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user, Request $request) : JsonResponse
    {
        $agreement = null;
        return $this->JWTResponse($user, ['agreement' => $agreement]);
    }

    /**
     * Returns a list of agreements
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="agreements_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $agreementRepository = $this->getDoctrine()->getRepository('AppBundle:Agreement');

        $agreements = $agreementRepository->findAll();
        return $this->JWTResponse($user, $agreements);
    }
}
