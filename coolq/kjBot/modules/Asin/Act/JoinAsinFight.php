<?php

namespace kjBotModule\Asin\Act;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\Framework\DataStorage;
use kjBot\SDK\CQCode;

/**
 * 参加刺客模拟赛活动
 */
class JoinAsinFight extends Module
{
	
	public function process(array $args, $event){
        $asinFightData = DataStorage::GetData('asinFightData.json');
        $asinFightData = $asinFightData ? json_decode($asinFightData,true) : array();
        if (!isset($asinFightData['status']) || $asinFightData['status'] === 0 || $asinFightData['status'] === 1) return $event->sendBack('活动未开始');
        if (!isset($asinFightData['status']) || $asinFightData['status'] === 3 || $asinFightData['status'] === 4) return $event->sendBack('本次活动进行中，请耐心等待活动结束');
        $User_id = $event->getId();
        if (!isset($asinFightData['data'])) $asinFightData['data'] = array();
        if (isset($asinFightData['data'][$User_id])) return $event->sendBack(CQCode::At($User_id).' 您已参加本次活动，无需重复参加');
        $asinFightData['data'][$User_id] = array('bld'=>100);
        DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
        $msgList = [
            "%s 参加了本次活动，好运光环笼罩着TA"
        ];
        return $event->sendBack(sprintf($msgList[mt_rand(0,count($msgList)-1)],CQCode::At($User_id)));
	}
}