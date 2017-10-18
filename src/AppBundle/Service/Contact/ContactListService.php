<?php

namespace AppBundle\Service\Contact;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectRepository as ObjectRepositoryInterface;

use Exception;

use AppBundle\Service\PhoneNumberValidatorService;

/**
 * Class ContactListService
 * @package AppBundle\Service
 */
class ContactListService
{
    protected $userRepository;
    protected $phoneUtil;
    protected $phoneNumberValidator;

    /**
     * @param UserRepository              $userRepository
     * @param PhoneNumberValidatorService $phoneNumberValidator
     */
    public function __construct(ObjectRepositoryInterface $userRepository, PhoneNumberValidatorService $phoneNumberValidator)
    {
        $this->userRepository = $userRepository;
        $this->phoneUtil = PhoneNumberUtil::getInstance();
        $this->phoneNumberValidator = $phoneNumberValidator;
    }

    /**
     * This method will create a list with contacts.
     * 
     * @param  array $phoneNumbers
     * @return array
     */
    public function createList(string $userPhoneNumber, array $phoneNumbers) : array
    {
        $userPhoneNumberObject = $this->phoneUtil->parse($userPhoneNumber, null);
        $contactsList = [];

        foreach ($phoneNumbers as $phoneNumber) {
            unset($autocompletedPhoneNumber);
            
            if (!$this->phoneNumberValidator->isValid($phoneNumber)) {
                $autocompletedPhoneNumber = '+'.$userPhoneNumberObject->getCountryCode().$phoneNumber;

                if (!$this->phoneNumberValidator->isValid($autocompletedPhoneNumber)) {
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
                'fullName' => $user->getAccount()->getForename().' '.$user->getAccount()->getLastname(),
                'accountId' => $user->getAccount()->getId(),
                'userId' => $user->getId()
            ];
        }
        return $contactsList;
    }
}
