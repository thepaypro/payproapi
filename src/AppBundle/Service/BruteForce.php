<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\AccessLog;

/**
* Service to send APN's messages.
*/
class BruteForce
{
    protected $em;
    protected $brute_force;

    public function __construct(EntityManager $em, $brute_force)
    {
        $this->em = $em;
        $this->brute_force = $brute_force;
    }

    public function register($phone_number)
    {
        $access_log = new AccessLog();
        $access_log->setPhoneNumber($phone_number);
        $access_log->setIpAddress($_SERVER['REMOTE_ADDR']);

        $this->em->persist($access_log);
        $this->em->flush();
    }

    public function block($phone_number)
    {
        $access_phone_number = $this->em->getRepository('AppBundle:AccessLog')->findUserLastHour($phone_number);

        if (count($access_phone_number) >= $this->brute_force['fails_username_hour']) {
            return true;
        }

        return false;
    }
}
