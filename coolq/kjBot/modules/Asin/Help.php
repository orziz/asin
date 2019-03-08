<?php

namespace kjBotModule\Asin;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 帮助文档
 */
class Help extends Module
{
	
	public function process(array $args, $event) {
		$msg = '';
		if(($event instanceof GroupMessageEvent)) $msg .= CQCode::At($event->getId())."\n";

$msg .= <<<EOF
当前可用的指令为：
----- 你好
你好 -> 没别的意思，就是问个好
----- 信息
信息 -> 查看自己的信息
信息 其他人QQ号（可以是艾特） -> 查看被艾特人的信息
----- 排名
排名 -> 查看自己的排名
排名 其他人QQ号（可以是艾特） -> 查看被艾特人的排名
----- 签到
签到 -> 还活着就签个到吧😊
----- 刺客排行榜
刺客排行榜 -> 看看刺客排行榜吧，万一榜上有你呢？
----- 签到排行榜
签到排行榜 -> 看看哪些人整天闲得没事来签到
----- 帮助
帮助 -> 你都看到这了，还不知道帮助是干嘛的？？？

EOF;
	
		return $event->sendBack($msg); 
	}
}