<?php

namespace kjBotModule\Common\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use kjBot\Framework\TargetType;
use \Log;

/**
 * 子不语测试专用
 */
class Test extends Module
{
	
	public function process(array $args, $event){
        checkAuth($event,'master');
        return $event->sendBack(CQCode::At('all'));
	}
}