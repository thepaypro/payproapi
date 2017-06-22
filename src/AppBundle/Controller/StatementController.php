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
use Swift_Message, Swift_Attachment;
use AppBundle\Controller\Traits\JWTResponseControllerTrait;

class StatementController extends Controller
{
    use JWTResponseControllerTrait;

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

        $dateValidator = v::notOptional()->date();
        try {
            $dateValidator->setName('Date from')->assert($date_from);
            $dateValidator->min($date_from)->setName('Date to')->assert($date_to);
        } catch(NestedValidationException $exception) {
            return $this->JWTResponse($user, [
                'message' => $exception->getMessages()[0]
            ], 400);
        }

        // convert dates to api request format
        $date_from = str_replace('-', '', $date_from) . '000000';
        $date_to = str_replace('-', '', $date_to) . '000000';

        // request data to provider

        // generate pdf
        $user_statements_path = $this->get('kernel')->getRootDir() . "/../var/statements/{$user->getId()}";
        $statement_file_name = 'statement-' . time() . '.pdf';
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'statements/main.html.twig', [
                    'user' => $user,
                    'transactions' => []
                ]),
            "$user_statements_path/$statement_file_name"
        );

        // send email
        $message = (new Swift_Message('Extracto'))
            ->setFrom('juanma@mondeapp.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView('emails/statement.html.twig'), 'text/html')
            ->attach(Swift_Attachment::fromPath("$user_statements_path/$statement_file_name"));
        $mailer = $this->get('mailer');
        if(!$mailer->send($message)) {
            return $this->JWTResponse($user, [
                'message' => 'Can\'t send statement'
            ], 400);
        }

        // delete pdf
        $fs = new Filesystem();
        $spool = $mailer->getTransport()->getSpool();
        $spool->flushQueue($this->container->get('swiftmailer.transport.real'));
        if($fs->exists($user_statements_path)) {
            $fs->remove($user_statements_path);
        }

        return $this->JWTResponse($user, [
            'message' => 'Statement sended',
        ]);
    }
}
