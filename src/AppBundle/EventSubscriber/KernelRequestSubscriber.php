<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['formatRequest', 10]
            ]
        ];
    }

    public function formatRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->headers->has('content-type') && $request->headers->get('content-type') === 'application/json') {
            foreach (json_decode($request->getContent(), true) as $key => $value) {
                $request->request->set($key, $value);
            }
        }
    }
}