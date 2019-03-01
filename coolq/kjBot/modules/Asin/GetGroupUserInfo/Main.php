<?php

namespace kjBotModule\Asin\GetGroupUserInfo;

use kjBot\Framework\Module;
use kjBot\Framework\Event\MessageEvent;

class Main extends AnotherClass
{

    public function process(array $args, MessageEvent $event){
    	global $sender;
    	return $event->sendBack(json_encode($sender));
        // return $event->sendBack('Hello, world!');
    }

}