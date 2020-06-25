<?php

namespace kjBotModule\Asin\Rank;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

/**
 * 
 */
class GetMyScoreRank extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$User_id = $event->getId();
		if($event instanceof GroupMessageEvent) $msg .= CQCode::At($User_id).' ';
		$atqq = isset($args[1]) ? parseQQ($args[1]) : null;
		$qq = $atqq ? $atqq : $User_id;
		$DScore = new \Domain\UserScore();
		$data = $DScore->getRank($qq);
		if ($data) {
			if ($qq == $User_id) $msg .= '您当前的排名为： '.$data['rank'].' ，请继续努力提高排名';
			else $msg .= '您查询的用户当前排名为：'.$data['rank'];
		} else {
			if ($qq == $User_id) $msg .= '您暂时没有加入刺客组织';
			else $msg .= '您查询的用户暂时没有加入刺客组织';
		}
		$msg .= "\n查看完整排行榜请点击链接 http://asin.ygame.cc/rank";
		return $event->sendBack($msg);
	}
}