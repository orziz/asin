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
        Log::Debug('完全不知道崩在哪了');
		if($event instanceof GroupMessageEvent){
			$senderInfo = $event->getSenderInfo();
			$msg .= CQCode::At($senderInfo['user_id']);
        }

        $param = json_encode(array('mode' => 'home_userinfo', 'action'=>'getUserInfo'));
        Log::Debug('1-->'.$param);
        $data = request_post('http://asin.ygame.cc/api.php',json_encode(array('param' => $param)));

        return $event->sendBack($data);
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