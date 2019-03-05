<?php

namespace kjBotModule\Asin\Rank;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

/**
 * 
 */
class CheckinRank extends Module
{
	
	public function process(array $args, $event){
		$msg = "签到排行榜";
		$data = param_post('http://asin.ygame.cc/api.php',array('mod'=>'rank_checkin','action'=>'getRankList'));
		$rankList = $data['data'];
		for ($i=0; $i < count($rankList); $i++) { 
			$msg .= "\n".($i+1).' '.CQCode::At($rankList[$i]['qq'])."\t\t\t\t".$rankList[$i]['count'].' 天';
		}
		return $event->sendBack($msg);
	}
}