<?php

namespace kjBotModule\TRPG;

use kjBotModule\TRPG\Common;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 帮助文档
 */
class Show extends Common
{
	
	public function process(array $args, $event) {
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
		$atqq = isset($args[2]) ? parseQQ($args[2]) : $event->getId();
        $msg = CQCode::At($event->getId())." ";
        if (!isset($args[1])) q('参数不正确');
        if ($args[1] == '-all') {
            q('啥问题？');
            $msg .= $this->getAllAttrs(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $atqq.'.json')));
        } else {
            $attr = $this->getAttr(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $atqq.'.json')), $args[1]);
            $msg .= "您当前的 {$args[1]} 为 {$attr}";
        }
		return $event->sendBack($msg); 
    }

}