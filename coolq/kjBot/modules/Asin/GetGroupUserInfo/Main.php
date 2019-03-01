<?php

namespace kjBotModule\Asin\GetGroupUserInfo;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use \C;
use \Log;

class Main extends Module
{

    public function process(array $args, $event){
        if(!($event instanceof GroupMessageEvent)){
            q('只有群聊才能使用本命令');
        }
        global $kjBot;
        Log::Debug('====>');
    	$userList = $kjBot->getCoolQ()->getGroupMemberList($event->groupId);
    	// $userList = json_decode($userList,true);
    	for ($i = 0; $i < count($userList); $i++) {
    		$userInfo = json_encode($userList[$i]);
    		$userInfo = json_decode($userInfo,true);
    		// return $event->sendBack('加群时间为： '.date('Y-m-d H:i:s',$userInfo['join_time']));
    		return $event->sendBack('加群时间为： '.getTime());
    	}
    	// return $event->sendBack(count($userList));
        // return $event->sendBack('Hello, world!');
    }

}