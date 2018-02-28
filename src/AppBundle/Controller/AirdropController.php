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
use PayPro\Ethereum\Exception\EthereumException;

/**
 * AirdropController
 * 
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/airdrop")
 */
class AirdropController extends Controller
{
	use JWTResponseControllerTrait;

	/**
	 * 
	 * @param UserInterface $user
	 * @param  Request $request
	 * @return JsonResponse
	 * 
	 * @Route("", name="do_airdrop")
	 * @Method("GET")
	 */
	public function getAction(UserInterface $user, Request $request): JsonResponse
	{
		try{

			$airdropUsers = $this->get('payproapi.airdrop_service')->execute();

		 	return $this->JWTResponse($user, ['info' => $airdropUsers]);
		
		}catch(EthereumException $e){
			return $this->JWTResponse($user, ['ERROR' => $e->getErrorCode().": ".$e->getErrorMessage()], $e->getHttpCode());
		}
		 
	}

}