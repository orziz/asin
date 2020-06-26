<?php

namespace kjBotModule\Asin\Join;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class AllJoinOrgan extends Module
{
	
	public function process(array $args, $event){
        $User_id = $event->getId();
        if ($User_id != '1063614727') q('权限不足');
		$msg = '';
		if(!($event instanceof GroupMessageEvent)) q('仅在群内使用');
        global $kjBot;
        $groupMemberList = $kjBot->getCoolQ()->getGroupMemberList($event->groupId);
        $DInfo = new \Domain\UserInfo();
        for ($i=0; $i < count($groupMemberList); $i++) {
            $memberInfo = $groupMemberList[$i];
            if ($memberInfo->user_id == '1352219126') continue;
            $DInfo->newUser($memberInfo->user_id, array(
                'qq'=>$memberInfo->user_id,
                'nickname'=>$memberInfo->nickname,
                'age'=>$memberInfo->age,
                'sex'=>0,
                'height'=>170,
                'weight'=>50,
                'arms'=>'',
                'introduce'=>'此人太过神秘，暂时没有相关信息',
                'skill1'=>'',
                'skill2'=>'',
                'skill3'=>'',
                'skill4'=>'',
                'score'=>0,
                'credit'=>0,
                'rank'=>0
            ));
        }
        return $event->sendBack('录入成功');
    }
}