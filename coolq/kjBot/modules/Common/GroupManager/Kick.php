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
class Kick extends Module {

    protected $needCQ = true;

    // public function processWithCQ(array $args, $event,$cq){
    public function processWithCQ(array $args, $event, $cq = NULL) {
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
        checkAuth($event,'group');
        if (!isset($args[1])) q('未选择目标');
        if ($args[1] == '死鱼') {
            if (isset($args[2])) {
                $time = (int)$args[2];
                if (!is_integer($time)) q('请输入天数');
                $checkTime = time()-$time*24*60*60;
            } else {
                $checkTime = time()-24*60*60*30;
            }
            $userList = $cq->getGroupMemberList($event->groupId);
            for ($i=0; $i < count($userList); $i++) {
                $memberInfo = $userList[$i];
                if ($memberInfo->user_id == Config('master')) continue;
                if ($memberInfo->last_sent_time < $checkTime) {
                    $cq->setGroupKick($event->groupId,$memberInfo->user_id);
                }
            }
        } else {
            $atqq = parseQQ($args[1]);
            if (!$atqq) q('目标类型不正确');
            if ($atqq == Config('master')) q('该用户不可被踢出');
            $cq->setGroupKick($event->groupId,$atqq);
        }
    	return $event->sendBack('踢出成功');
    }

}