<?php

require_once('miscClass.php');

use kjBot\Framework\Event\BaseEvent;
use kjBot\Framework\TargetType;
use kjBot\Framework\Message;
use kjBot\Framework\DataStorage;

function checkAuth($event,$level='group') {
	global $Config;
	if (!$event) return false;
	$userInfo = $event->getSenderInfo();
	$userId = $userInfo->user_id;
	switch ($level) {
		case 'group':
			if (in_array($userInfo->role,['admin','owner'])) return true;
		case 'asin':
			if (in_array($userId,[])) return true;
		default:
			if ($userId == Config('master')) return true;
			return q('权限不足');
			break;
	}
	
}