<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

/**
 * 
 */
class CheckIn extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$atqq = isset($args[1]) ? getAtQQ($args[1]) : null;
		$User_id = $event->getId();
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($qq).' ';
        }
        return $event->sendBack($msg."恭喜你签到成功啦，可惜没奖励~\n（小声逼逼：我也没记录……）");
    }
}