<?php

namespace kjBotModule\Asin\Act;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\Framework\DataStorage;
use kjBot\SDK\CQCode;

/**
 * 
 */
class BeginAsinFight extends Module
{
	
	public function process(array $args, $event){
		checkAuth($event,'master');
		$asinFightData = DataStorage::GetData('asinFightData.json');
		$asinFightData = $asinFightData ? json_decode($asinFightData,true) : array();
		if (isset($asinFightData['status']) && $asinFightData['status'] !== 0) return NULL;
		$asinFightData['status'] = 1;
		DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
		return NULL;
	}
}