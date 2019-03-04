<?php

namespace kjBotModule\Asin\Join;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class JoinOrgan extends Module
{
	
	public function process(array $args, $event){
        $arr = ['1063614727','2426311997','2354782466'];
        $User_id = $event->getId();
        if (!in_array($User_id, $arr)) return $event->sendBack('暂不支持自动加入刺客组织，请联系 '.CQCode::At('2354782466'));
		$msg = '';
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id).' ';
        }
        $userInfo = $event->getSenderInfo();
        $data = param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userinfo', 'action'=>'newUserInfo', 'qq'=>$User_id,'nickname'=>$userInfo->nickname));
        if ($data['errCode'] === 200) {
            $msg .= $data['data']['nickname'].'，刺客组织欢迎您的加入，您目前的排名为 '.$data['data']['rank'].' ，请努力提高排名吧！';
            return $event->sendBack($msg);
        } elseif ($data['errCode'] === 301) {
            return $event->sendBack($msg.' 您已加入刺客组织，请努力提高排名吧！');
        } else {
            Log::Error('Coolq JoinOrgan===>'.$data['errMsg']);
            return $event->sendBack('加入刺客组织失败');
        }
    }
}