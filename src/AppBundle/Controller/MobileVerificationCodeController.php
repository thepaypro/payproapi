<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Exception\PayProException;

/**
 * Card controller.
 * @Route("/mobile-verification-code")
 */
class MobileVerificationCodeController extends Controller
{
    /**
     * Create a mobile verification code
     * @param  Request       $request
     * @return JsonResponse
     * 
     * @Route("", name="create_mobile_verification_code")
     * @Method("POST")
     */
    public function createAction(Request $request) : JsonResponse
    {
        $phoneNumber = $request->request->get('phoneNumber');

        try {
            return $this->json(
                $this->get('payproapi.create_mobile_verification_code_service')
                    ->execute($phoneNumber)
            );
        } catch (PayProException $e) {
            return $this->json(['errorMessage' => $e->getMessage()], $e->getCode());
        }

    }
}
