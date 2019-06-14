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
		return $event->sendBack('该功能已关闭');
	}
}