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
class CloseModMsg extends Module
{
	public function process(array $args, $event){
		$msg = '该功能已关闭';
		$msg .= "\n现寻求人员参与某东西的开发测试，如果你会html、js、php、mysql或者绘图（ps或其他绘图软件均可）并对此感兴趣，请联系【子不语】";
		return $event->sendBack($msg);
	}
}