<?php
namespace kjBotPlugin\GroupCheck\AsinGroup;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\TargetType;
use \Log;

class _Meta_event extends Plugin {
    public $handleDepth = 2; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function meta_event_heartbeat($event) {
        $Queue[] = $this->test($event);
        return $Queue;
    }

    private function test($event) {
        return $event->sendTo(TargetType::Group,'719994813','心跳测试：：：'.time());
    }

}