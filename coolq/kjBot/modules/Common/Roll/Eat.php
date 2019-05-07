<?php

namespace kjBotModule\Common\Roll;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class Eat extends Module
{
	
	public function process(array $args, $event){
        $arr = array('炒菜','米线','干锅','金拱门','老娘舅','开封菜','鑫花溪','赛百味','重庆小面','冒菜','麻辣烫');
        $msg = '';
		$User_id = $event->getId();
        if ($event->fromGroup()) $msg .= CQCode::At($User_id)."\n";
        $msg .= '吃 '. $arr[mt_rand(0,count($arr)-1)] .' 吧';
		return $event->sendBack($msg);
	}
}