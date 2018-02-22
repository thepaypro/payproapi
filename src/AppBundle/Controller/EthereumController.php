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

/**
 * Contacts controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/ethereum")
 */
class EthereumController extends Controller
{
	use JWTResponseControllerTrait;

	public $client;

    /**
     * Test
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="ethereum_test")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
		$client = $this->get('ethereum.client');
		$cv = $client->web3()->clientVersion();
		$accounts = $client->eth()->accounts();
		$gasPrice = $client->eth()->gasPrice();

		$transaction = $client->eth()->newTransaction(
			$accounts[0],
			$accounts[1],
			null,
			"0x76c0",
			"0x9184e72a000",
			1,
			null
		);
		$sendTransaction = $client->eth()->sendTransaction($transaction);
		$balance = $client->eth()->getBalance2($accounts[0]);

		dump($cv);
		dump($gasPrice);
		dump($transaction);
		dump($balance->toEther());
		dump($accounts);
		die();
    }
}