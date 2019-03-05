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
        for ($i=0; $i < count($groupMemberList); $i++) {
            $memberInfo = $groupMemberList[$i];
            //{"age":0,"area":"","card":"\u5fc3\u6001\u7206\u70b8\u81ea\u95ed\u60a3\u8005<%\u0100\u0100\u0007\u00d2>","card_changeable":false,"group_id":675470658,"join_time":1540285272,"last_sent_time":1551434472,"level":"","nickname":"\u5b50\u4e0d\u8bed","role":"owner","sex":"unknown","title":"","title_expire_time":0,"unfriendly":false,"user_id":1063614727}
            param_post('http://asin.ygame.cc/api.php',array(
                'mod' => 'home_userinfo',
                'action'=>'newUserInfo',
                'qq'=>$User_id,
                'nickname'=>$memberInfo->nickname,
                'age'=>$memberInfo->age,
                'sex'=>0,
                'height'=>170,
                'weight'=>50,
                'str'=>20,
                'dex'=>20,
                'con'=>20,
                'ine'=>20,
                'wis'=>20,
                'cha'=>20,
                'free'=>20,
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