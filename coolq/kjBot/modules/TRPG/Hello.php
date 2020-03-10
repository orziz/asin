<?php
namespace kjBotModule\TRPG;

use kjBot\Framework\Module;
use kjBot\Framework\Event\MessageEvent;

class Hello extends Module{
    public function process(array $args, MessageEvent $event){
        return $event->sendBack('Hello, COC TRPG!');
    }
}