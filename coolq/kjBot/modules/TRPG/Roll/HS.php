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
class HS extends Common
{
	
	public function process(array $args, $event){
        if(!($event instanceof GroupMessageEvent)) q('只有群聊才能使用本命令');
        if (!isset($args[1])) q('缺少参数');
        $User_id = $event->getId();
        $msg = CQCode::At($User_id)."\n";
        // 获取san值
        $attrs = $this->getAttrs(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $User_id.'.json')));
        $hp = $attrs['hp'];

        $min = 1;
        $max = intval($args[1]);
        if (isset($args[2])) {
            $min = intval($args[1]);
            $max = intval($args[2]);
        }

        // 是否需要成功检定并判定是否需要成功，如果失败就修改范围值
        $num = mt_rand($min, $max);

        // 初始化需要扣除的值并扣除
        $hpName = array('hp', '体力');
        for ($i = 0; $i < count($hpName); $i++) {
            $attrs[$hpName[$i]] -= $num;
        }

        // 写入数据
        $isSuccess = DataStorage::SetData(implode(DIRECTORY_SEPARATOR, array('trpg', $event->groupId, $User_id.'.json')), json_encode($attrs));
        if ($isSuccess) {
            $msg .= "体力减少：{$min}d{$max}：{$num}，剩余 体力 {$attrs['hp']}";
        } else {
            $msg .= '扣除失败，请重试（如连续多次，联系子不语检查）';
        }
		return $event->sendBack($msg);
    }
}