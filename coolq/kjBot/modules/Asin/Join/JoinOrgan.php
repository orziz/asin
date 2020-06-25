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
        $User_id = $event->getId();
		$msg = '';
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id).' ';
        }
        $userInfo = $event->getSenderInfo();
        try {
            $age = $userInfo->age;
        } catch (\Throwable $th) {
            $age = 18;
        }
        $DInfo = new \Domain\UserInfo();
        $newUser = $DInfo->newUser($User_id, array(
            'nickname'=>$userInfo->nickname,
            'age'=>$age,
            'sex'=>0,
            'height'=>170,
            'weight'=>50,
            'str'=>20,
            'dex'=>20,
            'con'=>20,
            'ine'=>20,
            'wis'=>20,
            'cha'=>20,
            'free'=>20,
            'arms'=>'',
            'introduce'=>'此人太过神秘，暂时没有相关信息',
            'skill1'=>'',
            'skill2'=>'',
            'skill3'=>'',
            'skill4'=>'',
            'score'=>0,
            'credit'=>0,
            'rank'=>0
        ));

        if ($newUser === true) {
            $DScore = new \Domain\UserScore();
            $msg .= $userInfo->nickname.'，刺客组织欢迎您的加入，您目前的排名为 '.$DScore->getRank($User_id).' ，请努力提高排名吧！';
            return $event->sendBack($msg);
        } elseif ($newUser === -1) {
            return $event->sendBack($msg.' 您已加入刺客组织，请努力提高排名吧！');
        } else {
            return $event->sendBack('加入刺客组织失败');
        }
    }
}