<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class StatementController extends Controller
{
    /**
     * Send a statement by email
     * @param  Request       $request
     * @return JsonResponse
     *
     * @Route("/statements")
     * @Method("GET")
     */
    public function getStatementAction()
    {
        return $this->json([
            'message' => 'Statement sended',
        ]);
    }

}
