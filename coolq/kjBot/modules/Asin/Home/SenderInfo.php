<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class SenderInfo extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$atqq = isset($args[1]) ? getAtQQ($args[1]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($qq)."\n";
		}
		$senderInfo = $event->getSenderInfo();
		$msg .= 'QQ：'.$User_id;
		foreach ($senderInfo as $key => $value) {
			$msg .= "\n".$key.'：'.$value;
		}
		return $event->sendBack($msg);
	}
}