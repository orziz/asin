<?php

namespace kjBotModule\Common\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use kjBot\Framework\TargetType;

/**
 * 
 */
class Say extends Module
{
	
	public function process(array $args, $event){
        checkAuth($event);
        if (!isset($args[1])) q('没有参数');
        switch ($args[1]) {
            case '-escape':
                $escape = true;
                break;
            case '-async':
                $async = true;
                break;
            case '-toGroup':
                $toGroup = true;
                if (!isset($args[2])) q('没有指定接收对象');
                if (!isset($args[3])) q('没有指定发送内容');
                $id = $args[2];
                $authArr = DataStorage::GetData('Auth.json');
                $authArr = $authArr ? json_decode($authArr,true) : array();
                $id = isset($authArr[$id]) ? $authArr[$id] : $id;
                break;
            case '-toPerson':
                $toPerson = true;
                if (!isset($args[2])) q('没有指定接收对象');
                if (!isset($args[3])) q('没有指定发送内容');
                $id = parseQQ($args[2]);
                break;
            default:
                break;
        }

        if ($toGroup) {
            return $event->sendTo(TargetType::Group, $id, $args[3]);
        } elseif ($toPerson) {
            return $event->sendTo(TargetType::Private, $id, $args[3]);
        } else {
            return $event->sendBack($args[1]);
        }
	}
}