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
        if (!isset($asinFightData['status']) || $asinFightData['status'] !== 2) return $event->sendBack('活动未开始');
        $User_id = $event->getId();
        if (!isset($asinFightData['data'])) $asinFightData['data'] = array();
        if (isset($asinFightData['data'][$User_id])) return $event->sendBack(CQCode::At($User_id).' 您已参加本次活动，无需重复参加');
        $asinFightData['data'][$User_id] = array('hurt'=>0);
        DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
        $msgList = [
            "%s 参加了本次活动，好运光环笼罩着TA"
        ];
        return $event->sendBack(sprintf($msgList[mt_rand(0,count($msgList)-1)],CQCode::At($User_id)));
	}
}