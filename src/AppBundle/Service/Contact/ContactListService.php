<?php
namespace AppBundle\Service\Contact;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\Common\Persistence\ObjectRepository;

use Exception;

/**
 * Class ContactListService
 * @package AppBundle\Service
 */
class ContactListService
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
            unset($autocompletedPhoneNumber);
            
            if (!$this->isValidPhoneNumber($phoneNumber)) {
                $autocompletedPhoneNumber = '+'.$userPhoneNumberObject->getCountryCode().$phoneNumber;

                if (!$this->isValidPhoneNumber($autocompletedPhoneNumber)) {
                    $contactsList[$phoneNumber] = [
                        'phoneNumber' => $phoneNumber,
                        'isUser' => false,
                        'fullName' => null
                    ];

                    continue;
                }
            }

            $actualPhoneNumber = isset($autocompletedPhoneNumber) ? $autocompletedPhoneNumber : $phoneNumber;

            $user = $this->userRepository->findOneByUsername($actualPhoneNumber);

            if (!$user || !$user->getAccount()) {
                $contactsList[$phoneNumber] = [
                    'phoneNumber' => $actualPhoneNumber,
                    'isUser' => false,
                    'fullName' => null
                ];
                continue;
            }

            $contactsList[$phoneNumber] = [
                'phoneNumber' => $actualPhoneNumber,
                'isUser' => true,
                'fullName' => $user->getAccount()->getForename().' '.$user->getAccount()->getLastname()
            ];
        }
        return $contactsList;
    }

    private function isValidPhoneNumber(String $phoneNumber)
    {
        try {
            $phoneNumberObject = $this->phoneUtil->parse($phoneNumber, null);
            return $this->phoneUtil->isValidNumber($phoneNumberObject);
        } catch (NumberParseException $e) {
            return false;
        }
    }
}
