<?php

namespace kjBotModule\TRPG\Roll;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class RD extends Module
{
	
	public function process(array $args, $event){
        $msg = '';
		$User_id = $event->getId();
        if ($event->fromGroup()) $msg .= CQCode::At($User_id)."\n";
        $min = isset($args[2]) ? intval($args[1]) : 1;
        $max = isset($args[1]) ? isset($args[2]) ? intval($args[2]) : intval($args[1]) : 100;
        $msg .= $min.'d'.$max.'：'. mt_rand($min,$max);
		return $event->sendBack($msg);
	}
}