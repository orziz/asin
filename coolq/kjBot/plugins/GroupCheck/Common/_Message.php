<?php
namespace kjBotPlugin\GroupCheck\Common;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

class _Message extends Plugin {
    public $handleDepth = 1; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function message($event) {
        $Queue[] = $this->checkLifeQestion($event);
        return NULL;
    }

    /**
     * 监听询问生命的意义
     * @param [type] $event
     * @return void
     */
    private function checkLifeQestion($event) {
        if ((false !== strpos($event->getMsg(), '生命') && false !== strpos($event->getMsg(), '意义')) && false !== strpos($event->getMsg(), '什么')) {
            return $event->sendBack('是42！');
        }
        if ((false !== strpos($event->getMsg(), "what's the meaning of life?"))) {
            return $event->sendBack('是42！');
        }
        return NULL;
    }

}