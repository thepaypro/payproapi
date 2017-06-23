<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Twilio\Rest\Client;

use AppBundle\Entity\MobileVerificationCode;
use AppBundle\Event\MobileVerificationCodeEvent;
use AppBundle\Event\MobileVerificationCodeEvents;

/**
 * Class twilioShortMessageService
 * @package AppBundle\Service
 */
class TwilioShortMessageService
{
    protected $twilioClient;
    protected $phoneNumber;

    /**
     * @param String $twilioAccountId Twilio account id
     * @param String $twilioAuthToken Twilio authorization token
     * @param String $payProPhoneNumber A Twilio phone number you purchased at twilio.com/console
     */
    public function __construct(String $twilioAccountId, String $twilioAuthToken, String $payProPhoneNumber)
    {
        $this->twilioClient = new Client($twilioAccountId, $twilioAuthToken);
        $this->phoneNumber = $payProPhoneNumber;
    }

    /**
     * This method send an SMS with the specified message to the specified phone number.
     * 
     * @param  String $phoneNumber The number you'd like to send the message to
     * @param  String $message The body of the text message you'd like to send
     */
    public function sendShortMessage(String $to, String $message)
    {
        $response = $this->twilioClient->account->messages->create(
            $to,
            [
                'from' => $this->phoneNumber,
                'body' => $message
            ]
        );
    }
}
