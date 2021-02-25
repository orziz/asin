<?php

namespace kjBotModule\Asin\Forum;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 查看用户信息
 */
class RegWithQQ extends Module
{
	
	public function process(array $args, $event){
        $Queue[] = $event->sendBack(CQCode::At($event->getId()).' 请查看私聊信息（如未收到私聊信息请先加小不语好友并给小不语发一个"你好"）');
        $msg = '请点击以下连接进行QQ号绑定注册';
        $msg .= "\nhttps://567.pohun.com/?qq_login.htm&state=".$event->getId();
        $msg .= "\n（因该注册会携带QQ信息，固请不要随意修改及告知他人！切记！）";
        $Queue[] = $event->sendPrivate($msg);
        return $Queue;
    }
}