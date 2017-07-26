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
use AppBundle\Exception\PayProException;

/**
 * Profile controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/profiles")
 */
class ProfileController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns the information of a given profile
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{id}", name="profiles_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user, Request $request) : JsonResponse
    {
        $profile = null;
        return $this->JWTResponse($user, ['profile' => $profile]);
    }

    /**
     * Create a profile
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="profiles_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $picture = $request->request->get('picture');

        try {
            $profile = $this
                ->get('payproapi.create_profile_service')
                ->execute($user->getId(), $picture);
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }
        return $this->JWTResponse($user, ['profile' => $profile]);
    }

    /**
     * Update the information of the profile
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("/{profileId}", name="profiles_update")
     * @Method("PUT")
     */
    public function updateAction(UserInterface $user, Request $request) : JsonResponse
    {
        $picture = $request->request->get('picture');
        $profileId = $request->attributes->get('profileId');

        try {
            $profile = $this
                ->get('payproapi.update_profile_service')
                ->execute($profileId, $picture);
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }
        return $this->JWTResponse($user, ['profile' => $profile]);
    }
}
