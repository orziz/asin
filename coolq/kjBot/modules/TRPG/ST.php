<?php

namespace kjBotModule\TRPG;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 帮助文档
 */
class ST extends Module
{
	
	public function process(array $args, $event) {
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
        $msg = CQCode::At($event->getId())." ";
        if (!isset($args[1])) q('参数不正确');
        $attrs = $this->marthText($args[1]);
        $isSuccess = DataStorage::SetData(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $event->getId().'.json')), $attrs);
        if ($isSuccess) {
            $msg .= '导入成功';
        } else {
            $msg .= '导入失败';
        }
		return $event->sendBack($msg); 
    }
    
    private function marthText($text) {
        $out = null;
        preg_match_all("/(\D*)(\d*)/", $text, $out);
        $arr = [];
        for ($i = 0; $i < count($out[1]); $i++) {
            $arr[$out[1][$i]] = $out[2][$i];
        }
        array_pop($arr);
        return $arr;
    }
}