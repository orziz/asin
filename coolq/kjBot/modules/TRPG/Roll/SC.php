<?php

namespace kjBotModule\TRPG\Roll;

use kjBot\Framework\DataStorage;
use kjBotModule\TRPG\Common;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class SC extends Common
{
	
	public function process(array $args, $event){
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
        if (!isset($args[1])) q('检定最大值');
        $User_id = $event->getId();
        $msg = CQCode::At($User_id)."\n";
        // 获取san值
        $attrs = $this->getAttrs(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $User_id.'.json')));
        $san = $attrs['san'];
        
        // 初始化范围值
        $scArr = $this->parserCheck($args[1]);

        // 是否需要成功检定并判定是否需要成功，如果失败就修改范围值
        $needCheck = isset($args[2]);
        if ($needCheck) {
            $isPass = mt_rand(1, 100) < $san;
            $msg .= 'san值检定 ' . $isPass ? '成功' : '失败' . "\n";
            if (!$isPass) $scArr = $this->parserCheck($args[2]);
        }
        $num = mt_rand($scArr[0], $scArr[1]);

        // 初始化需要扣除的值并扣除
        $sanName = array('san', 'san值', '理智', '理智值');
        for ($i = 0; $i < count($sanName); $i++) {
            $attrs[$sanName[$i]] -= $num;
        }

        // 写入数据
        $isSuccess = DataStorage::SetData(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $User_id.'.json')), json_encode($attrs));
        if ($isSuccess) {
            $msg .= "San Check：{$scArr[0]}d{$scArr[1]}：". $num;
        } else {
            $msg .= '检定失败，请重试（如连续多次，联系子不语检查）';
        }
		return $event->sendBack($msg);
    }
    
    private function parserCheck($str) {
        $arr = explode('d', $str);
        for ($i = 0; $i < count($arr); $i++) {
            $arr[$i] = intval($arr[$i]);
        }
        if (count($arr) < 2) {
            $arr[1] = $arr[0];
            $arr[0] = 1;
        }
        $arr[0] = max(0, $arr[0]);
        $arr[1] = max(1, $arr[1]);
        return array($arr[0], $arr[1]);
    }
}