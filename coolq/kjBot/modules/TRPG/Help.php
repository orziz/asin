<?php

namespace kjBotModule\TRPG;

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
.coc <次数> -> 随机<几次>coc属性

.st 属性 -> 导入属性

.show 属性名 <其他人QQ号（可以是艾特）> -> 查看<被艾特人的>属性

.ro 属性名 -> 普通检定属性
.rd 属性名 -> 困难检定属性
.re 属性名 -> 极难检定属性

.sc 最大值（成功ndn） <失败ndn> -> san check

.help -> 这个还需要解释么？
EOF;
	
		return $event->sendBack($msg); 
	}
}