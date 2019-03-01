<?php

namespace kjBotModule\Asin\GetGroupUserInfo;

use kjBot\Framework\Module;
use kjBot\Framework\Event\MessageEvent;

class Main extends Module
{

    public function process(array $args, MessageEvent $event){
    	Log::Debug('-->',json_encode($this));
    	return $event->sendBack(json_encode($this->senderInfo));
        // return $event->sendBack('Hello, world!');
    }

}