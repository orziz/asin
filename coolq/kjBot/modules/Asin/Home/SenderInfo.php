<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class SenderInfo extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$atqq = isset($args[1]) ? getAtQQ($args[1]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($qq)."\n";
			$msg .= '群号：'.$event->groupId."\n";
		}
		$senderInfo = $event->getSenderInfo();
		$msg .= 'QQ：'.$User_id;
		foreach ($senderInfo as $key => $value) {
			if (!$value || $key == 'isGroupSender') continue;
			// nickname：欧阳尘星
			// isGroupSender：1
			// card：江苏-欧阳尘星
			// level：刺客大师
			// role：admin
			// title：人事部经理
			if ($key == 'nickname') $k = '昵称';
			if ($key == 'card') $k = '群昵称';
			if ($key == 'level') $k = '聊天等级';
			if ($key == 'title') $k = '头衔';
			if ($key == 'role') {
				$k = '权限';
				if ($value == 'owner') $value = '群主';
				if ($value == 'admin') $value = '管理员';
				if ($value == 'member') $value = '成员';
			}
			$msg .= "\n".$k.'：'.$value;
		}
		return $event->sendBack($msg);
	}
}