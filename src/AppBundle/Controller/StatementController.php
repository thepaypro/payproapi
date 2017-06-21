<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

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
    public function getStatementAction(UserInterface $user, Request $request)
    {
        $date_from = $request->query->get('date_from');
        $date_to = $request->query->get('date_to');

        $dateValidator = v::notOptional()
            ->date();
        try {
            $dateValidator->setName('Date from')->assert($date_from);
            $dateValidator->min($date_from)->setName('Date to')->assert($date_to);
        } catch(NestedValidationException $exception) {
            return $this->json([
                'message' => $exception->getMessages()[0]
            ], 400);
        }

        // request data to provider
        
        // generate pdf
        $user_statements_path = $this->get('kernel')->getRootDir() . "/../var/statements/{$user->getId()}/";
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'statements/main.html.twig', [
                    'user' => $user,
                    'transactions' => []
                ]),
            $user_statements_path . 'statement.pdf'
        );

        // send email
        $message = (new \Swift_Message())
            ->setFrom('juanma@mondeapp.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView('emails/statement.html.twig'), 'text/html');

        if(!$this->get('mailer')->send($message)) {
            return $this->json([
                'message' => 'Can\'t send statement'
            ]);
        }

        $fs = new Filesystem();
        if($fs->exists($user_statements_path)) {
            $fs->remove($user_statements_path);
        }

        return $this->json([
            'message' => 'Statement sended',
        ]);
    }
}
