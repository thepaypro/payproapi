<?php

namespace AppBundle\Service;

use \Pusher;

/**
* Service to send APN's messages.
*/
class NotificationPusher
{
    protected $pusher;

    function __construct(array $config)
    {
        $this->pusher = new Pusher(
            $config['key'],
            $config['secret'],
            $config['app_id'],
            [
                'cluster' => $config['cluster'],
                'encrypted' => true
            ]
        );
    }

    public function sendMessage()
    {
        $this->pusher->trigger(
            'my-channel',
            'my-event',
            [
                'message' => 'Hello world!!'
            ]
        );
    }
}
