<?php

namespace kjBotModule\Asin\Rank;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

/**
 * 
 */
class ScoreRank extends Module
{
	
	public function process(array $args, $event){
		$msg = "刺客排行榜";
		$DScore = new \Domain\UserScore();
		$rankList = $DScore->getRankList(10);
		for ($i=0; $i < count($rankList); $i++) {
			if (intval($rankList[$i]['score']) < 0) $rankList[$i]['score'] = '？？？';
			$msg .= "\n".$rankList[$i]['rank']."\t\t".$rankList[$i]['nickname']."\t\t".$rankList[$i]['score'];
		}
		$msg .= "\n查看完整排行榜请点击链接 http://asin.ygame.cc/rank";
		return $event->sendBack($msg);
	}
}