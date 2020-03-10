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
class CK extends Module
{
	
	public function process(array $args, $event) {
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
        $msg = CQCode::At($event->getId())." ";
        $time = 1;
        $attrs = array();
        if (isset($args[1])) $time = intval($args[1]);
        for ($i = 0; $i < $time; $i++) {
            array_push($attrs, $this->randomAttr());
        }
        $msg .= implode("\n", $attrs);
		return $event->sendBack($msg); 
    }
    
    private function randomAttr() {
        $attrs = ['力量' => 0, '体质' => 0, '体型' => 0, '敏捷' => 0, '外貌' => 0, '智力' => 0, '意志' => 0, '教育' => 0, '幸运' => 0];
        $all = 0;
        $text = '';
        foreach ($attrs as $key => $value) {
            $num = mt_rand(0, 99);
            $attrs[$key] = $num;
            $all += $num;
            $text .= $key . ':' . $num . '，';
        }
        $text .= '不含幸运:' . ($all - $attrs['幸运']) . '，';
        $text .= '总共：' . $all;
        return $text;
    }
}