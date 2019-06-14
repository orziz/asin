<?php

namespace kjBotModule\Common\Manager;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 关闭模组
 */
class CloseMod extends Module
{
	public function process(array $args, $event){
        checkAuth($event,'master');
		$closeMods = DataStorage::GetData('CloseMods.json');
        $closeMods = $closeMods ? json_decode($closeMods,true) : array();
        $temp = array();
        for ($i = 1; $i < count($args); $i++) {
            if ($args[$i] == '开启功能' || $args[$i] == '关闭功能') continue;
            if (!in_array($args[$i], $closeMods)) {
                array_push($closeMods, $args[$i]);
                array_push($temp, $args[$i]);
            }
        }
		$isSuccess = DataStorage::SetData('CloseMods.json',json_encode($closeMods));
		if ($isSuccess) {
            if (count($temp) > 0) return $event->sendBack('将 '. implode('，',$temp) .' 关闭成功');
            return $event->sendBack('已关闭');
        }
        return $event->sendBack('关闭失败');
	}
}