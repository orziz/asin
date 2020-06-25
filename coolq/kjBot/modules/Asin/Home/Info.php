<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

/**
 * 查看用户信息
 */
class Info extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$atqq = isset($args[1]) ? parseQQ($args[1]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id)."\n";
        }
        $DInfo = new \Domain\UserInfo();
        $DScore = new \Domain\UserScore();
        $userInfo = $DInfo->getData($qq);
        $userScore = $DScore->getData($qq);
        $userInfo['score'] = $userScore['score'];
        $userInfo['credit'] = $userScore['credit'];
        $userInfo['rank'] = $DScore->getRank($qq);
        if (!$userInfo) {
            $msg .= '您查询的用户暂未加入刺客组织';
        } else {
            $msg .= '姓名：'.$userInfo['nickname']."\n";
            $msg .= '排名：'.$userInfo['rank']."\n";
            $msg .= '积分：'.($userInfo['score'] < 0 ? '？？？' : $userInfo['score'])."\n";
            $msg .= '暗币：'.$userInfo['credit']."\n";
            $msg .= '年龄：'.$userInfo['age']."\n";
            $msg .= '性别：'.($userInfo['sex'] === 0 ? '未知' : ($userInfo['sex'] === 1 ? '男' : '女'))."\n";
            $msg .= '身高：'.$userInfo['height']." cm\n";
            $msg .= '体重：'.$userInfo['weight']." kg\n";
            $msg .= '介绍：'.$userInfo['introduce']."\n";
            $msg .= '加入组织时间：'.$userInfo['ctime'];
        }
        return $event->sendBack($msg);
    }
}