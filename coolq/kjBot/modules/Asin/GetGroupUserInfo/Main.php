<?php

namespace kjBotModule\Asin\GetGroupUserInfo;

use kjBot\Framework\Module;
use kjBot\Framework\Event\MessageEvent;

class Main extends Module
{

    public function process(array $args, MessageEvent $event){
    	global $senderInfo;
    	return $event->sendBack(json_encode($sender));
        // return $event->sendBack('Hello, world!');
    }

}