<?php
namespace AppBundle\Service\EntityManager;

use libphonenumber\PhoneNumberUtil;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ContactManager
 * @package AppBundle\Service
 */
class ContactManager
{
    protected $em;
    protected $phoneNumberUtil;

    /**
     * @param EntityManager            $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();

    }

    /**
     * This method will create a list with contacts.
     * 
     * @param  Array $phoneNumbers
     * @return Array
     */
    public function createList(String $userPhoneNumber, Array $phoneNumbers) : Array
    {
        $userphoneNumber = $this->phoneNumberUtil->parse($phoneNumber, null);
        try {
            $phoneNumberObject = $phoneNumberUtil->parse($phoneNumber, null);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 400);
        }

        $user = $this->userRepository->findOneByUsername($phoneNumber);

        if ($user) {
            return ['isUser' => true];
        }
    }
}
