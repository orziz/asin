<?php

namespace kjBotModule\Common\GroupManager;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use \C;
use \Log;

/**
 * 清除死鱼
 */
class Ban extends Module {

    protected $needCQ = true;

    // public function processWithCQ(array $args, $event,$cq){
    public function processWithCQ(array $args, $event, $cq = NULL) {
        checkAuth($event);
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');

        date_default_timezone_set('Asia/Shanghai');
        $atqq = isset($args[1]) ? parseQQ($args[1]) : null;
        $time='';
        for ($i = 2; $i < count($args); $i++) {
            if ($args[$i] == 'd') $args[$i] = 'days';
            if ($args[$i] == 'h') $args[$i] = 'hours';
            if ($args[$i] == 'm') $args[$i] = 'minutes';
            if ($args[$i] == 's') $args[$i] = 'seconds';
            $time .= $args[$i].' ';
        }

        try{
            $cq->setGroupBan($event->groupId, $atqq, (strtotime($time)-time()));
        }catch(\Exception $e){}

    }

}