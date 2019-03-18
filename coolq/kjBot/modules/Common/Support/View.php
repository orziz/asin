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
        $data = DataStorage::GetData('support.json');
        $data = $data ? json_decode($data,true) : array();
		$msg = '';
		if($event->fromGroup()) $msg .= CQCode::At($event->getId())."\n";
        $msg .= "感谢以下人员的赞助";
        foreach ($data as $key => $value) {
			$msg .= "\n". CQCode::At($key)."\t\t".$value." 元";
		}
		return $event->sendBack($msg); 
	}
}