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
use Symfony\Component\Config\Definition\Exception\Exception;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * User controller.
 *
 * @Route("/users")
 */
class UserController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Check the user password
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/update-password", name="update_password")
     * @Method("PUT")
     */
    public function updatePasswordAction(UserInterface $user, Request $request) : JsonResponse
    {
        $old_password = $request->query->get('old_password');
        $new_password = $request->query->get('new_password');
        $confirm_password = $request->query->get('confirm_password');

        $encoderService = $this->container->get('security.password_encoder');
        $match = $encoderService->isPasswordValid($user, $old_password);
        if(!$match) {
            return $this->json([
                'message' => 'Wrong old password',
            ], 400);
        }

        $passwordValidator = v::notBlank()
            ->Length(6, 6)
            ->Digit();
        try {
            $passwordValidator->setName('Old password')->assert($old_password);
            $passwordValidator->setName('New password')->assert($new_password);
            $passwordValidator->equals($new_password)->setName('Confirm password')
                ->assert($confirm_password);
        } catch(NestedValidationException $exception) {
            return $this->json([
                'message' => $exception->getMessages()[0]
            ], 400);
        }

        $em = $this->getDoctrine()->getManager();
        $hash = $encoderService->encodePassword($user, $new_password);
        $user->setPassword($hash);
        $em->flush();

        return $this->json([
            'message' => 'Password change success',
            'user' => $user,
        ]);
    }

    /*
     * Returns if a user with the given phone number exists
     * @param  Request       $request
     * @return JsonResponse
     *
     * @Route("/check/{phoneNumber}", name="user_check")
     * @Method("GET")
     */
    public function checkAction(Request $request) : JsonResponse
    {
        $phoneNumber = $request->attributes->get('phoneNumber');

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $userRepository->findOneByUsername($phoneNumber);

        $data = ['isUser' => $user ? true : false];

        return $this->json($data);
    }

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
