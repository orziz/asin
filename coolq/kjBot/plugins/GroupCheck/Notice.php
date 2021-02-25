<?php

namespace kjBotPlugin;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupIncreaseEvent;
use kjBot\SDK\CQCode;
use \Log;
use PHF\Log as PHFLog;

class Notice extends Plugin {

	public $handleDepth = 3; //捕获到最底层的事件
	public $handleQueue = true; //声明是否要捕获消息队列

	// const cq_notice_group_increase = true;

	public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
	//此处以正常群聊消息举例
	public function coolq_notice_friend_add($event,$cq): ?Message{
        PHFLog::Debug("debug---->$event");
        // return $cq->set_friend_add_request($event->getId());
        return NULL;
	}
}