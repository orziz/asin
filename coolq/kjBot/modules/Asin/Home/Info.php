<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class Info extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		if(($event instanceof GroupMessageEvent)) {
			Log::Debug('不是群消息？');
			$senderInfo = $event->getSenderInfo();
			$msg .= CQCode::At($senderInfo['user_id']);
        }

        $data = param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userinfo', 'action'=>'getUserInfo'));
        if ($data['errMsg'] !== 200) return $event->sendBack($msg.' '.$data['errMsg']);
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