<?php

namespace kjBotPlugin\GroupCheck\AsinGroup;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupIncreaseEvent;
use kjBot\SDK\CQCode;

class _Message extends Plugin {

	public $handleDepth = 2; //捕获到最底层的事件
	public $handleQueue = true; //声明是否要捕获消息队列

	public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
	//此处以正常群聊消息举例
	public function notice_group_increase($event): ?Message{
		if (in_array($event->groupId,$asinGroup));
	}
}