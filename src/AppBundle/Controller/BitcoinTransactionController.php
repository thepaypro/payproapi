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
 * @Route("/bitcoin_transactions")
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

        try {
            $transaction = $this->get('payproapi.create_bitcoin_transaction_service')->execute(
                $user->getId(),
                $requestData['beneficiary'],
                $requestData['amount'],
                $requestData['subject']
            );
        } catch (PayProException $e) {
            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
        }

        return $this->JWTResponse($user, ['transaction' => $transaction]);
    }

//    /**
//     * Returns a list of transactions
//     * @param  UserInterface $user
//     * @param  Request $request
//     * @return JsonResponse
//     * @throws PayProException
//     * @Route("", name="transactions_list")
//     * @Method("GET")
//     */
//    public function indexAction(UserInterface $user, Request $request) : JsonResponse
//    {
//        $filters = $request->query->all();
//
//        try {
//            $transactions = $this->get('payproapi.index_transaction_service')->execute(
//                $user->getId(),
//                isset($filters['page']) ? $filters['page'] : null,
//                isset($filters['size']) ? $filters['size'] : null
//            );
//        } catch (PayProException $e) {
//            return $this->JWTResponse($user, ['errorMessage' => $e->getMessage()], $e->getCode());
//        }
//
//        return $this->JWTResponse($user, ['transactions' => $transactions]);
//    }
}
