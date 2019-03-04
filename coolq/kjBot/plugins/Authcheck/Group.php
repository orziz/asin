<?php
namespace kjBotPlugin\Authcheck;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

class Main extends Plugin {
    public $handleDepth = 3; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function message_group_normal(MessageEvent $event): ?Message{
        if($event instanceof GroupMessageEvent) {
            global $asinGroup;
            if (in_array($event->groupId,$asinGroup)) {
                global $modules;
                if ((false !== strpos($event->getMsg(), '怎么') || false !== strpos($event->getMsg(), '如何')) && false !== strpos($event->getMsg(), '加入')) {
                    return $event->sendBack(CQCode::At($enent->getId()).' 暂不支持自动加入刺客组织，请联系千刃');
                } 
                array_push($modules, array(
                    '加入刺客组织' => kjBotModule\Asin\Home\JoinOrgan::class
                ));
            }
            $msg .= CQCode::At($User_id).' ';
        }
        return NULL;
    }

}