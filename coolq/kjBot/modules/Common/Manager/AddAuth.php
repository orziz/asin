<?php

namespace kjBotModule\Common\Manager;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 添加权限
 */
class AddAuth extends Module
{
	public function process(array $args, $event){
		checkAuth($event,'master');
		$auth = DataStorage::GetData('Auth.json');
		$auth = $auth ? json_decode($auth,true) : array();
		$atqq = isset($args[1]) ? parseQQ($args[1]) : null;
		if (!$atqq) q('没有指定添加的用户');
		if (!isset($args[2])) q('没有指定权限等级');
		$level = $args[2];
		if (!isset($auth[$level])) $auth[$level] = array();
		if (in_array($atqq,$auth[$level])) q('该用户已有改权限');
		array_push($auth[$level], $atqq);
		$isSuccess = DataStorage::SetData('Auth.json',json_encode($auth));
		if ($isSuccess) return $event->sendBack('将 '.CQCode::At($atqq).' 添加进 '.$level.' 权限成功');
		return $event->sendBack('添加失败');
	}
}