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
		$senderInfo = $event->getSenderInfo();
		if($event instanceof GroupMessageEvent){
			global $kjBot;
			$userInfo = $kjBot->getCoolQ()->getStrangerInfo($User_id);
			foreach ($userInfo as $key => $value) {
				if (!in_array($key, $senderInfo)) array_push($senderInfo, array($key=>$value));
			}
			$msg .= CQCode::At($qq)."\n";
		}
		$msg .= 'QQ：'.$User_id;
		foreach ($senderInfo as $key => $value) {
			if (!$value) continue;
			if ($key == 'isGroupSender') continue;
			if ($key == 'join_time') continue;
			if ($key == 'last_sent_time') continue;
			if ($key == 'title_expire_time') continue;
			if ($key == 'card_changeable') continue;
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
			if ($key == 'sex') {
				$k = '性别';
				if ($value == 'male') $value = '男';
				if ($value == 'female') $value = '女';
				if ($value == 'unknown') $value = '未知';
			}
			if ($key == 'age') $k = '年龄';
			if ($key == 'area') $k = '地区';
			if ($key == 'unfriendly') {
				$k = '是否是不良记录成员';
				$value = $value ? '是' : '否';
			}
			$msg .= "\n".$k.'：'.$value;
		}
		if($event instanceof GroupMessageEvent){

		}
		return $event->sendBack($msg);
	}
}