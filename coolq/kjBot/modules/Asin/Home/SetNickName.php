<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 设置用户昵称
 */
class SetNickName extends Module
{
	
	public function process(array $args, $event){
        $User_id = $event->getId();
        if ($User_id != '1063614727') q('权限不足');
		$msg = '';
        if (!isset($args[1])) q('未输入新昵称');
        $nickName = $args[1];
		$atqq = isset($args[2]) ? getAtQQ($args[2]) : null;
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent) $msg .= CQCode::At($User_id)."\n";
        $data = param_post('http://asin.ygame.cc/api.php',array(
            'mod' => 'home_userinfo',
            'action'=>'getUserInfo',
            'qq'=>$qq,
            'nickname'=>$nickName
        ));
        if ($data['errCode'] !== 200) {
            $msg .= $data['errMsg'];
        } else {
            $msg .= '已将 '.CQCode::At($qq).' 昵称设置为 '.$nickName;
        }
        return $event->sendBack($msg);
    }
}