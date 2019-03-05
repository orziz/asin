<?php

namespace kjBotModule\Asin\GroupControl;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use \C;
use \Log;

class KickDead extends Module
{

    public function process(array $args, $event){
        $User_id = $event->getId();
        if ($User_id != '1063614727') q('权限不足');
        global $kjBot;
    	$userList = $kjBot->getCoolQ()->getGroupMemberList($event->groupId);
        for ($i=0; $i < count($userList); $i++) {
            $memberInfo = $userList[$i];
            Log::Debug('清除死鱼====>'.$memberInfo->last_sent_time.'   --->'.($memberInfo->last_sent_time < 1533052801));
            if ($memberInfo->last_sent_time < 1533052801) {
                $kjBot->getCoolQ()->setGroupKick($event->groupId,$memberInfo->user_id);
            }
        }
    	return $event->sendBack('清除成功');
    }

}