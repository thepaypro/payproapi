<?php

namespace AppBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use AppBundle\Service\BruteForce;

class AuthenticationFailureListener{

	protected $brute_force;

	/**
	* @param BruteForce $brute_force
	*/
    public function __construct(BruteForce $brute_force)
    {
        $this->brute_force = $brute_force;
    }

	/**
	 * @param AuthenticationFailureEvent $event
	 * @param Request $request
	 */
	public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
	{
		$this->brute_force->register($event->getException()->getToken()->getUser());

	    $data = [
	        'status'  => '401 Unauthorized',
	        'message' => 'Bad credentials, please verify that your username/password are correctly set',
	    ];

	    $response = new JWTAuthenticationFailureResponse($data);

	    $event->setResponse($response);
	}

	
}