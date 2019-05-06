<?php

namespace kjBotModule\Asin\Roll;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class Roll extends Module
{
	
	public function process(array $args, $event){
        $msg = '';
        $User_id = $event->getId();
        if ($event->fromGroup()) $msg .= CQCode::At($User_id)."\n";
        $data = param_post('http://asin.ygame.cc/api.php',array('mod'=>'home_userinfo','action'=>'getUserInfo','qq'=>$User_id));
        if ($data['errCode'] !== 200) {
            $msg .= $data['errMsg'];
        } else {
            $userInfo = $data['data'];
            $msg .= "您目前魅力为 ".$userInfo['cha']."\n";
            $min = isset($args[2]) ? intval($args[1]) : 1;
            $max = isset($args[1]) ? isset($args[2]) ? intval($args[2]) : intval($args[1]) : 100;
            $msg .= $min.'d'.$max.'：'. mt_rand($min,$max);
        }
        return $event->sendBack($msg);
	}
}