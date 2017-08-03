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

    /**
     * MailingService constructor.
     * @param string $from
     * @param string $userAdministratorEmail
     * @param Swift_Mailer $mailer
     */
    function __construct(string $from, string $userAdministratorEmail, Swift_Mailer $mailer)
    {
        $this->sender = $from;
        $this->userAdministratorEmail = $userAdministratorEmail;
        $this->mailer = $mailer;
    }

    /**
     * @param string $from
     * @param string $to
     * @param array $pictures
     * @param array $data
     * @param string|null $textBody
     * @return bool
     */
    private function sendMail(string $from, string $to, array $pictures, array $data, string $textBody = null)
    {
        $message = (new \Swift_Message())
            ->setFrom($from)
            ->setTo($to);

        $message = $message->setBody($textBody. "\n" .json_encode($data, JSON_UNESCAPED_SLASHES));

        foreach ($pictures as $key => $picture) {
            $message = $message->attach(
                Swift_Attachment::newInstance(
                    base64_decode($picture),
                    'picture' . $key . '.jpeg'
                )->setContentType('image/jpeg')
            );
        }
        $this->mailer->send($message);
        return true;
    }

    /**
     * @param Account $account
     * @param array $pictures
     * @param string $deviceToken
     * @return bool
     */
    public function sendCreateAccountRequest(
        Account $account,
        array $pictures,
        string $deviceToken
    ): bool
    {
        return $this->sendMail(
            $this->sender,
            $this->userAdministratorEmail,
            $pictures,
            [
                'userId' => $account->getUsers()->first()->getId(),
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
                'country' => $account->getCountry()->getIso2(),
                'deviceToken' => $deviceToken
            ]
        );
    }

    /**
     * @param Account $account
     * @param array $pictures
     * @return bool
     */
    public function sendUpdateAccountRequest(
        Account $account,
        array $pictures
    ): bool
    {
        return $this->sendMail(
            $this->sender,
            $this->userAdministratorEmail,
            $pictures,
            [
                'userId' => $account->getUsers()->first()->getId(),
                'documentType' => $account->getDocumentType(),
                'documentNumber' => 'Number in picture',
            ],
            'AccountId: '.$account->getId()
        );
    }
}
