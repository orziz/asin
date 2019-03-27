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
		if ($User_id == Config('master')) {
			$nomsg = 0;
			$error = 0;
			for ($i=0; $i < 100; $i++) {
				$data = param_post('http://asin.ygame.cc/api.php',array('mod'=>'home_checkin','action'=>'checkin','qq'=>$User_id));
				if (!$data) {
					$nomsg++;
					if (is_array($data)) $error++;
				}
			}
			return $event->sendBack('未收到消息数为：'.$nomsg.' 空数组数为：'.$error);
		}
		$data = param_post('http://asin.ygame.cc/api.php',array('mod'=>'home_checkin','action'=>'checkin','qq'=>$User_id));
		if ($data['errCode'] === 301) $msg .= '签到失败：暂没有加入刺客组织';
		elseif ($data['errCode'] === 302) $msg .= '签到失败：今天您已签到，无需重复签到';
		elseif ($data['errCode'] === 303) $msg .= '签到失败';
		else $msg .= "恭喜你签到成功啦，可惜没奖励……\n你已连续签到 ".$data['data']['count'].' 天，请继续保持';
		$msg .= "\n（该功能由 ".CQCode::At(1845896706).' 赞助）';
		return $event->sendBack($msg);
	}
}