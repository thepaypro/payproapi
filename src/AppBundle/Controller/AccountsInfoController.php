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
 * AccountsInfoController
 * 
 * @Security("has_role('ROLE_USER')")
 * @Route("/accounts_info")
 */
class AccountsInfoController extends Controller
{
	use JWTResponseControllerTrait;

	/**
	 * Returns the information of the user accounts
	 * @param UserInterface $user
	 * @param  Request $request
	 * @return JsonResponse
	 * 
	 * @Route("", name="account_info_show")
	 * @Method("GET")
	 */
	public function getAction(UserInterface $user, Request $request): JsonResponse
	{
		$requestData = $request->query->all();

		try {
			$bitcoinBalance = (null !==$user->getBitcoinAccount()) ? $this->get('payproapi.get_bitcoin_wallet_service')->execute(
                $user->getId()
            	)['balance'] : NULL;

			$bitcoinTransactions = (null !==$user->getBitcoinAccount()) ? 
				isset($requestData['bitcoinTransactionId']) ?
					$this->get('payproapi.bitcoin_last_transactions_service')->execute(
		            	$user->getId(),
		            	$requestData['bitcoinTransactionId']
		        	)
		        :   $this->get('payproapi.bitcoin_transaction_service')->execute(
                		$user->getId()
            		)
			: NULL;

			$info = [
                'userId' => $user->getId(),
                'bitcoinBalance' => $bitcoinBalance,
                'bitcoinTransactions' =>$bitcoinTransactions
            ];

		} catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['info' => $info]);
	}
}