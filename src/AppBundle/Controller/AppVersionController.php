<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Exception\PayProException;

/**
 * AppVersionController
 * @Route("/app_version")
 */
class AppVersionController extends Controller
{

	/**
	 * Checks if the android app needs an update
	 * @param  Request  $request
	 * @return JsonResponse
	 * 
	 * @Route("/android", name="app_version_check_android")
	 * @Method("POST")
	 */
	public function checkAndroid(Request $request): JsonResponse
	{
		$app_version = $request->request->get('app_version');

		try{
			return $this->json(['need_update' => $this->get('payproapi.app_version.check_android_service')->execute($app_version)]);
		} catch (PayProException $e) {
           return $this->json(['errorMessage' => $e->getMessage()], $e->getCode());
        }

		
	}

	/**
	 * Checks if the ios app needs an update
	 * @param  Request  $request
	 * @return JsonResponse
	 * 
	 * @Route("/ios", name="app_version_check_ios")
	 * @Method("POST")
	 */
	public function checkIOS(Request $request): JsonResponse
	{
		$app_version = $request->request->get('app_version');

		try{
			return $this->json(['need_update' => $this->get('payproapi.app_version.check_ios_service')->execute($app_version)]);
		} catch (PayProException $e) {
            return $this->json(['errorMessage' => $e->getMessage()], $e->getCode());
        }

	}



	



}
