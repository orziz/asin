<?php
namespace kjBotModule\Hello;

use kjBot\Framework\Module;
use kjBot\Framework\Event\MessageEvent;

class Main extends Module{
    public function process(array $args, MessageEvent $event){
        return $event->sendBack('Hello, world!');
    }
}