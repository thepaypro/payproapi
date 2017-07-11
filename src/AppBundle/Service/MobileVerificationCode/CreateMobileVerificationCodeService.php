<?php
namespace AppBundle\Service\MobileVerificationCode;

use libphonenumber\PhoneNumberUtil;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use AppBundle\Entity\MobileVerificationCode;
use AppBundle\Event\MobileVerificationCodeEvent;
use AppBundle\Event\MobileVerificationCodeEvents;

/**
 * Class CreateMobileVerificationCodeService
 * @package AppBundle\Service
 */
class CreateMobileVerificationCodeService
{
    protected $em;
    protected $dispatcher;
    protected $userRepository;
    protected $mobileVerificationCodeRepository;

    /**
     * @param EntityManager            $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->userRepository = $this->em->getRepository('AppBundle:User');
        $this->mobileVerificationCodeRepository = $this->em->getRepository('AppBundle:MobileVerificationCode');
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * This method will create a mobile verification code and dispatch an event of the creation.
     * 
     * @param  phoneNumber $phoneNumber
     * @return something to reflect if something goes ok or not
     */
    public function execute(String $phoneNumber) : Array
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneNumberObject = $phoneNumberUtil->parse($phoneNumber, null);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 400);
        }

        $user = $this->userRepository->findOneByUsername($phoneNumber);

        if ($user) {
            return ['isUser' => true]; 
        }

        $mobileVerificationCode = $this->mobileVerificationCodeRepository->findOneByPhoneNumber($phoneNumber);

        if (!$mobileVerificationCode) {
            $mobileVerificationCode = new MobileVerificationCode($phoneNumber);
            $this->em->persist($mobileVerificationCode);
            $this->em->flush();
        }

        $this->dispatcher->dispatch(
            MobileVerificationCodeEvents::MOBILE_VERIFICATION_CODE_REQUESTED,
            new MobileVerificationCodeEvent($mobileVerificationCode)
        );

        return ['isUser' => false]; 
    }
}
