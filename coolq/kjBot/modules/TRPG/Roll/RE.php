<?php

namespace kjBotModule\TRPG\Roll;

use kjBotModule\TRPG\Common;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class RE extends Common
{
	
	public function process(array $args, $event){
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
        if (!isset($args[1])) q('请输入属性');
        $User_id = $event->getId();
        $msg = CQCode::At($User_id)."\n";
        // 获取属性
        $attr = $this->getAttr(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $User_id.'.json')), $args[1]);
        $attr = floor($attr/5);
        // 通用属性检定
        $msg .= $this->rollCheck($attr, $args[1], 'e');
		return $event->sendBack($msg);
	}
}