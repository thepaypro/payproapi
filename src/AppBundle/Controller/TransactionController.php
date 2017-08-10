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
 * Transaction controller.
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/transactions")
 */
class TransactionController extends Controller
{
    use JWTResponseControllerTrait;

    /**
     * Returns a list of transactions
     * @param  UserInterface $user
     * @param  Request $request
     * @return JsonResponse
     * @throws PayProException
     * @Route("/latest", name="last_transactions_list")
     * @Method("GET")
     */
    public function lastTransactionsAction(UserInterface $user, Request $request) : JsonResponse
    {
        $filters = $request->query->all();

        try {
            $transactions = $this->get('payproapi.last_transactions_service')->execute(
                $user->getId(),
                isset($filters['transactionId']) ? $filters['transactionId'] : null);
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['transactions' => $transactions]);
    }

    /**
     * Returns the information of a given transaction
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     *
     * @Route("/{id}", name="transactions_show")
     * @Method("GET")
     */
    public function getAction(UserInterface $user, Request $request) : JsonResponse
    {
        $transaction = null;
        return $this->JWTResponse($user, ['transaction' => $transaction]);
    }

    /**
     * Create a transaction
     * @param  UserInterface $user
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="transactions_create")
     * @Method("POST")
     */
    public function createAction(UserInterface $user, Request $request) : JsonResponse
    {
        $requestData = $request->request->all();

        try {
            $transaction = $this->get('payproapi.create_transaction_service')->execute(
                $user->getId(),
                $requestData['beneficiary'],
                $requestData['amount'],
                $requestData['subject'],
                isset($requestData['title']) ? $requestData['title'] : null
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
     * @Route("", name="transactions_list")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user, Request $request) : JsonResponse
    {
        $filters = $request->query->all();

        try {
            $transactions = $this->get('payproapi.index_transaction_service')->execute(
                $user->getId(),
                isset($filters['page']) ? $filters['page'] : null,
                isset($filters['size']) ? $filters['size'] : null
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['transactions' => $transactions]);
    }
}
