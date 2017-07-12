<?php

namespace AppBundle\Controller;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

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

        if ($id != $user->getId()) {//TODO: change that when the account is multiuser.
            
        }

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
    // public function indexAction(UserInterface $user, Request $request) : JsonResponse
    // {
    //     $filters = $request->query->all();

    //     $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
    //     $users = $userRepository->findUserswithUsernameIn($filters['phoneNumbers']);

    //     return $this->JWTResponse($user, ['users' => $users]);
    // }

    /**
     * Create a User with the information specified.
     * @param  UserInterface $user    [description]
     * @param  Request       $request [description]
     * @return JsonResponse
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("", name="user_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $payload = $request->request->all();

        try {
            $user = $this->get('payproapi.create_user_service')->execute(
                $user->getId(),
                $payload['phoneNumber'],
                $payload['mobileVerificationCode']
            );   
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['message' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['user' => $user]);
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
        $old_password = $request->request->get('old_password');
        $new_password = $request->request->get('new_password');
        $confirm_password = $request->request->get('confirm_password');

        $passwordValidator = v::notOptional()
            ->Length(6, 6)
            ->Digit();
        try {
            $passwordValidator->setName('Old password')->assert($old_password);
            $passwordValidator->setName('New password')->assert($new_password);
            $passwordValidator->equals($new_password)->setName('Confirm password')
                ->assert($confirm_password);
        } catch(NestedValidationException $exception) {
            return $this->JWTResponse($user, [
                'message' => $exception->getMessages()[0]
            ], 400);
        }

        $encoderService = $this->container->get('security.password_encoder');
        $match = $encoderService->isPasswordValid($user, $old_password);
        if(!$match) {
            return $this->JWTResponse($user, [
                'message' => 'Wrong old password',
            ], 400);
        }

        $em = $this->getDoctrine()->getManager();
        $hash = $encoderService->encodePassword($user, $new_password);
        $user->setPassword($hash);
        $em->flush();

        return $this->JWTResponse($user, [
            'message' => 'Password change success',
            'user' => $user,
        ]);
    }

    /**
     * Delete the user with the given id.
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(UserInterface $user, Request $request) : JsonResponse
    {
        $id = $request->attributes->get('id');

        try {
            $this->get('payproapi.delete_user_service')->execute($user->getId(), $id);
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['message' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['message' => 'User deleted']);
    }
}
