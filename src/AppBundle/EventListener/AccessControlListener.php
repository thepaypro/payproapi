<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccessControlListener implements EventSubscriberInterface
{
    private $BruteForce;
    /**
     * @param BruteForce $BruteForceService
     */
    public function __construct($BruteForceService)
    {
        $this->BruteForce = $BruteForceService;
    }
    /**
     * @param  GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->get('_route') == 'fos_user_security_check') {
            dump($request);die();
            if ($request->request->get('_username') != null) {
                if ($this->BruteForce->block($request->request->get('_username')) == true) {
                    throw new HttpException('440','User blocked');
                }
            }
        }
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }
}