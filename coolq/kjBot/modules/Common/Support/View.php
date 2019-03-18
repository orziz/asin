<?php

namespace kjBotModule\Common\Support;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 查看赞助
 */
class View extends Module
{
	
	public function process(array $args, $event) {
		$msg = '';
		if($event->fromGroup()) $msg .= CQCode::At($event->getId())."\n";
        $msg .= "感谢以下人员的赞助";
        $msg .= "\n". CQCode::At(1845896706)."\t\t100 元";
		return $event->sendBack($msg); 
	}
}