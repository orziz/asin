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
你好 -> 没别的意思，就是问个好

信息 -> 查看自己的信息
信息 其他人QQ号（可以是艾特） -> 查看被艾特人的信息

排名 -> 查看自己的排名
排名 其他人QQ号（可以是艾特） -> 查看被艾特人的排名

属性 -> 查看自己的属性点
属性 其他人QQ号（可以是艾特） -> 查看被艾特人的属性点
属性加点 属性名 加点值 -> 增加多少点的属性
洗点 -> 将所有属性点重置为 20 并返还所加点数

刺客排行榜 -> 看看刺客排行榜吧，万一榜上有你呢？

帮助 -> 你都看到这了，还不知道帮助是干嘛的？？？
EOF;
	
		return $event->sendBack($msg); 
	}
}