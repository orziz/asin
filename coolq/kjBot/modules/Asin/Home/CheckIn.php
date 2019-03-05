<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

/**
 * 
 */
class Checkin extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$User_id = $event->getId();
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id).' ';
		}
		$data = param_post('http://asin.ygame.cc/api.php',array('mod'=>'home_checkin','action'=>'checkin','qq'=>$User_id));
		if ($data['errCode'] === 301) return $event->sendBack($msg.'签到失败：暂没有加入刺客组织');
		if ($data['errCode'] === 302) return $event->sendBack($msg.'签到失败：今天您已签到，无需重复签到');
		if ($data['errCode'] === 303) return $event->sendBack($msg.'签到失败');
		return $event->sendBack($msg."恭喜你签到成功啦，可惜没奖励……\n你已连续签到 ".$data['data']['count'].' 天，请继续保持');
	}
}