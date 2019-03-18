<?php

namespace kjBotModule\Common\Support;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 查看赞助
 */
class Add extends Module
{
	
	public function process(array $args, $event) {
        checkAuth($event,'master');
        $data = DataStorage::GetData('support.json');
        $data = $data ? json_decode($data,true) : array();
        if (!isset($args[1])) q('没有指定用户');
        if (!isset($args[2])) q('没有指定金额');
        $user = $args[1];
        $credit = $args[2];
        if (!isset($data[$user])) $data[$user] = 0;
        $data[$user] += $credit;
        DataStorage::SetData('support.json',json_encode($data));
		return NULL; 
	}
}