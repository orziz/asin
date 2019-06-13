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
                if (is_numeric($args[2])) {
                    $num = (int)$args[2];
                    if (isset($args[3])) {
                        if (is_numeric($args[3])) {
                            $time = (int)$args[3];
                            if (!isset($args[4]) || $args[4] != '确认') q('此指令为清除此群内 '.$num.' 名 '.$time.' 天内未发言的用户，如确认执行请在末尾加上 `确认`，否则不会执行');
                        } elseif ($args[3] != '确认') q('此指令为清除此群内 '.$num.' 名 30 天内未发言的用户，如确认执行请在末尾加上 `确认`，否则不会执行');
                    } else q('此指令为清除此群内 '.$num.' 名 30 天内未发言的用户，如确认执行请在末尾加上 `确认`，否则不会执行');
                } elseif ($args[2] != '确认')  q('此指令为清除此群内 30 天内未发言的所有用户，如确认执行请在末尾加上 `确认`，否则不会执行');
            } else q('此指令为清除此群内 30 天内未发言的所有用户，如确认执行请在末尾加上 `确认`，否则不会执行');

            $num = $num ?? 2000;
            $time = $time ?? 30;
            $checkTime = time()-24*60*60*$time;
            $userList = $cq->getGroupMemberList($event->groupId);
            $num = min($num,count($userList));
            $k = 0;
            for ($i=0; $i < count($userList); $i++) {
                $memberInfo = $userList[$i];
                if ($memberInfo->last_sent_time < $checkTime && $memberInfo->user_id != Config('master')) {
                    $k++;
                    if ($k >= $num) break;
                    $cq->setGroupKick($event->groupId,$memberInfo->user_id);
                } else {
                    if ($num < count($userList)) $num++;
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