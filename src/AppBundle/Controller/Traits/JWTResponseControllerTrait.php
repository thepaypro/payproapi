<?php

namespace AppBundle\Controller\Traits;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

trait JWTResponseControllerTrait {

    public function JWTResponse(
    	UserInterface $user,
    	array $data,
    	$status = 200,
    	array $headers = array(),
    	array $context = array()
	) : JsonResponse
	{
        $data['token'] = $this->get('lexik_jwt_authentication.jwt_manager')->create($user);

        return Parent::json($data, $status, $headers, $context);
    }
}
