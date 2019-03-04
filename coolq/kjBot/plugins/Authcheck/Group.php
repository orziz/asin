<?php
namespace kjBotPlugin\Authcheck;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

class Group extends Plugin {
    public $handleDepth = 3; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function message_group_normal($event): ?Message{
        if($event instanceof GroupMessageEvent) {
            global $asinGroup;
            if (in_array($event->groupId,$asinGroup)) {
                global $Modules;
                if ((false !== strpos($event->getMsg(), '怎么') || false !== strpos($event->getMsg(), '如何')) && false !== strpos($event->getMsg(), '加入')) {
                    return $event->sendBack(CQCode::At($event->getId()).' 暂不支持自动加入刺客组织，请联系千刃');
                } 
                $Modules['加入刺客组织'] = \kjBotModule\Asin\Join\JoinOrgan::class;
            }
        }
        return NULL;
    }

}