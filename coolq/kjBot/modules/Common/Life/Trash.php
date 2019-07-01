<?php

namespace kjBotModule\Common\Life;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use kjBot\Framework\TargetType;
use \Log;

/**
 * 
 */
class Trash extends Module
{
	
	public function process(array $args, $event){
        if (!isset($args[1])) q('请输入要查询的物品');
        $msg = "";
        $User_id = $event->getId();
        if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id)." ";
        }
        $kw = urlencode($args[1]);
        $a = file_get_contents("http://trash.lhsr.cn/sites/feiguan/trashTypes_2/TrashQuery.aspx?kw={$kw}");


        $cache1 = explode('是指：', $a);
        if (count($cache1) < 2) {
            $msg .= '你是什么垃圾？';
        } else {
            $cache2 = explode('<b>', $cache1[0]);
            $msg .= $cache2[count($cache2)-1];
        }
        return $event->sendBack($msg);
        // echo $cache2[0];
    }
}