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
	 * @Security("has_role('ROLE_USER')")
	 * @Route("", name="app_version_get_all")
	 * @Method("GET")
	 */
	public function getAll(UserInterface $user, Request $request): JsonResponse
	{
		$versions = $this->get('payproapi.app_version.get_all_service')->execute();

		return $this->JWTResponse($user,['AppVersions' => $versions]);
	}


	/**
	 * Returns the android app version
	 * @return JsonResponse
	 * 
	 * @Security("has_role('ROLE_USER')")
	 * @Route("/android", name="app_version_get_android")
	 * @Method("GET")
	 */
	public function getAndroid(UserInterface $user, Request $request): JsonResponse
	{
		$version = $this->get('payproapi.app_version.get_android_service')->execute();

		return $this->JWTResponse($user,['AndroidAppVersion' => $version]);
	}

	/**
	 * Sets the android app version
	 * @return JsonResponse
	 * 
	 * @Security("has_role('ROLE_ADMIN')")
	 * @Route("/android", name="app_version_set_android")
	 * @Method("POST")
	 */
	public function setAndroid(UserInterface $user, Request $request): JsonResponse
	{
		$payload = $request->request->all();

		$version = $this->get('payproapi.app_version.set_android_service')->execute($payload['lastVersion'], $payload['oldestSupportedVersion']);

		return $this->JWTResponse($user,['AndroidAppVersion' => $version]);
	}


	/**
	 * Returns the ios app version
	 * @return JsonResponse
	 * 
	 * @Security("has_role('ROLE_USER')")
	 * @Route("/ios", name="app_version_get_ios")
	 * @Method("GET")
	 */
	public function getIOS(UserInterface $user, Request $request): JsonResponse
	{
		$version = $this->get('payproapi.app_version.get_ios_service')->execute();

		return $this->JWTResponse($user,['IOSAppVersion' => $version]);
	}

	/**
	 * Sets the ios app version
	 * @return JsonResponse
	 * 
	 * @Security("has_role('ROLE_ADMIN')")
	 * @Route("/ios", name="app_version_set_ios")
	 * @Method("POST")
	 */
	public function setIOS(UserInterface $user, Request $request): JsonResponse
	{

		$payload = $request->request->all();

		$version = $this->get('payproapi.app_version.set_ios_service')->execute($payload['lastVersion'], $payload['oldestSupportedVersion']);

		return $this->JWTResponse($user,['IOSAppVersion' => $version]);
	}


	



}
