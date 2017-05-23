<?php

namespace AppBundle\Controller\Traits;

use Symfony\Component\Security\Core\User\UserInterface;

trait JWTResponseControllerTrait {

    public function JWTResponse(UserInterface $user, $data, $status = 200, $headers = array(), $context = array()) {
        $data['token'] = $this->get('lexik_jwt_authentication.jwt_manager')->create($user);

        return Parent::json($data, $status, $headers, $context);
    }
}
