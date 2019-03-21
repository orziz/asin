<?php

namespace kjBotModule\Common\Manager;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 添加权限
 */
class RmGroup extends Module
{
	public function process(array $args, $event){
        checkAuth($event,'master');
        if (!$event->fromGroup()) q('这不是群');
        $groupId = $event->groupId;
		$groupData = DataStorage::GetData('GroupAuth.json');
		$groupData = $groupData ? json_decode($groupData,true) : array();
		if (!isset($args[1])) q('没有指定群组');
		$group = $args[1];
		if (!isset($groupData[$group])) $groupData[$group] = array();
		if (!in_array($groupId,$groupData[$group])) q('该群不在此群组');
		array_diff($groupData[$group],array($groupId));
		$groupData[$group] = array_values($groupData[$group]);
		$isSuccess = DataStorage::SetData('GroupAuth.json',json_encode($groupData));
		if ($isSuccess) return $event->sendBack('将此群移出进 '.$group.' 群组成功');
		return $event->sendBack('移出失败');
	}
}