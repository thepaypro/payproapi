<?php
namespace AppBundle\Service\EntityManager;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\Common\Persistence\ObjectRepository;

use Exception;
/**
 * Class ContactManager
 * @package AppBundle\Service
 */
class ContactManager
{
    protected $userRepository;
    protected $phoneUtil;

    /**
     * @param EntityManager            $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ObjectRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * This method will create a list with contacts.
     * 
     * @param  Array $phoneNumbers
     * @return Array
     */
    public function createList(String $userPhoneNumber, Array $phoneNumbers) : Array
    {
        $userPhoneNumberObject = $this->phoneUtil->parse($userPhoneNumber, null);

        $contactsList = [];

        foreach ($phoneNumbers as $phoneNumber) {
            try {
                $phoneNumberObject = $this->phoneUtil->parse($phoneNumber, null);
            } catch (NumberParseException $e) {
                $autocompletedPhoneNumber = '+'.$userPhoneNumberObject->getCountryCode().$phoneNumber;

                try {
                    $phoneNumberObject = $this->phoneUtil->parse($autocompletedPhoneNumber, null);
                } catch (Exception $e) {
                    $contactsList[$phoneNumber] = [
                        'phoneNumber' => $phoneNumber,
                        'isUser' => false,
                        'fullName' => null
                    ];
                    continue;
                }
            }

            $formattedPhoneNumber = '+'.$phoneNumberObject->getCountryCode().$phoneNumberObject->getNationalNumber();

            $user = $this->userRepository->findOneByUsername($formattedPhoneNumber);

            if ($user && $user->getAccount()) {
                $contactsList[$phoneNumber] = [
                    'phoneNumber' => $formattedPhoneNumber,
                    'isUser' => true,
                    'fullName' => $user->getAccount()->getForename().' '.$user->getAccount()->getLastname()
                ];
            } else {
                $contactsList[$phoneNumber] = [
                    'phoneNumber' => $formattedPhoneNumber,
                    'isUser' => false,
                    'fullName' => null
                ];
            }
        }

        return $contactsList;
    }
}
