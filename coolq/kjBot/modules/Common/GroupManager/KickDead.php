<?php

namespace kjBotModule\Common\GroupManager;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use \C;
use \Log;

/**
 * 清除死鱼
 */
class KickDead extends Module
{

    const needCQ = true;

    public function processWithCQ(array $args, $event, kjBot\SDK\CoolQ $CQ){
        checkAuth($event);
        $User_id = $event->getId();
        $senderInfo = $event->getSenderInfo();
    	$userList = $CQ->getGroupMemberList($event->groupId);
        for ($i=0; $i < count($userList); $i++) {
            $memberInfo = $userList[$i];
            if ($memberInfo->last_sent_time < 1546272001) {
                $CQ->setGroupKick($event->groupId,$memberInfo->user_id);
            }
        }
    	return $event->sendBack('清除成功');
    }

}