<?php

namespace kjBotModule\TRPG\Roll;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 
 */
class RO extends Module
{
	
	public function process(array $args, $event){
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
        $User_id = $event->getId();
        $msg = CQCode::At($User_id)."\n";
        $text = DataStorage::GetData(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $User_id.'.json')));
        if (!$text) q('没有您的信息，请导入');
        $attrs = json_decode($text, true);
        if (!isset($args[1])) q('请输入属性');
        if (!isset($attrs[$args[1]])) q('属性不正确');
        $num = mt_rand(1, 100);
        $attr = $attrs[$args[1]];
        $msg .= '普通检定：1d100：'. $num . '，检测 '.$args[1]. '('. $attr .') ';
        if ($num <= 5) {
            $msg .= '大成功！！！';
        } else {
            if ($num >= 96 && $num > $attr) {
                $msg .= '大失败！！！';
            } elseif ($num <= $attr) {
                $msg .= '成功';
            } else {
                $msg .= '失败';
            }
        }
		return $event->sendBack($msg);
	}
}