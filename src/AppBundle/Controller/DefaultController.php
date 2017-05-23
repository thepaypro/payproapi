<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Controller\Traits\JWTResponseControllerTrait;

class DefaultController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/test", name="testpage")
     */
    public function test(UserInterface $user)
    {
        $responseData = [
            'username' => $user->getUsername(),
            'id'=> $user->getId()
        ];

        return $this->JWTResponse($user, $responseData);
    }
}
