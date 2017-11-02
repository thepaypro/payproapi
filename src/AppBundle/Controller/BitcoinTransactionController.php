<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Traits\JWTResponseControllerTrait;
use AppBundle\Exception\PayProException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Transaction controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/bitcoin-transactions")
 */
class BitcoinTransactionController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Create a transaction
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     *
     * @Route("", name="bitcoin_transactions_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request): JsonResponse
    {
        $requestData = $request->request->all();
        

        if(isset($requestData['beneficiaryUserID'])){
            $wallet = $this->get('payproapi.get_bitcoin_wallet_service')->execute(
                $requestData['beneficiaryUserID']
            );
            $beneficiary = $wallet['address'];
        }else if (isset($requestData['beneficiary'])){
            $beneficiary = $requestData['beneficiary'];
        }

        try {
            $transaction = $this->get('payproapi.create_bitcoin_transaction_service')->execute(
                $user->getId(),
                $beneficiary,
                $requestData['amount'],
                $requestData['subject']
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['transaction' => $transaction]);
    }

    /**
     * Returns a list of transactions
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     * @throws PayProException
     * @Route("", name="bitcoin_transactions_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $filters = $request->query->all();

        try {
            $transactions = $this->get('payproapi.index_bitcoin_transaction_service')->execute(
                $user->getId(),
                isset($filters['page']) ? $filters['page'] : 1,
                isset($filters['size']) ? $filters['size'] : 10
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['transactions' => $transactions]);
    }
}
