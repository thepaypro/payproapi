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
 * AppVersionController
 * 
 * @Route("/app_version")
 */
class AppVersionController extends Controller
{
	use JWTResponseControllerTrait;

	/**
	 * Returns the avaliable apps versions
	 * @return JsonResponse
	 * 
	 * @Route("", name="app_version_get_all")
	 * @Method("GET")
	 */
	public function getAll(UserInterface $user, Request $request): JsonResponse
	{
		$versions = $this->get('payproapi.app_version.get_all_service')->execute();

		return $this->JWTResponse($user,['AppVersions' => $versions]);
	}

}
