<?php

namespace AppBundle\Service;

use \Swift_Mailer;
use \Swift_Attachment;
use AppBundle\Entity\Account;

/**
* class MailingService
*/
class MailingService
{
    private $sender;
    private $userAdministratorEmail;
    private $mailer;

    function __construct(String $from, String $userAdministratorEmail, Swift_Mailer $mailer)
    {
        $this->sender = $from;
        $this->userAdministratorEmail = $userAdministratorEmail;
        $this->mailer = $mailer;
    }

    private function sendMail(String $from, String $to, Array $pictures, Array $data)
    {
        $message = (new \Swift_Message())
        ->setFrom($from)
        ->setTo($to);

        $message = $message->setBody(json_encode($data, JSON_UNESCAPED_SLASHES));

        foreach ($pictures as $key => $picture) {
            $message = $message->attach(
                Swift_Attachment::newInstance(
                    base64_decode($picture),
                    'picture'.$key.'.jpeg'
                )->setContentType('image/jpeg')
            );
        }
        $this->mailer->send($message);
        return true;
    }

    public function sendAccountRequest(Account $account, Array $pictures)
    {
        return $this->sendMail(
            $this->sender,
            $this->userAdministratorEmail,
            $pictures,
            [
                'agreement' => $account->getAgreement()->getId(),
                'forename' => $account->getForename(),
                'lastname' => $account->getLastname(),
                'birthDate' => $account->getBirthdate()->format('d/m/Y'),
                'documentType' => $account->getDocumentType(),
                'documentNumber' => $account->getDocumentNumber(),
                'street' => $account->getStreet(),
                'buildingNumber' => $account->getBuildingNumber(),
                'postcode' => $account->getPostcode(),
                'city' => $account->getCity(),
                'country' => $account->getCountry()->getId()
            ]
        );
    }
}
