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
	$userId = $event->getId();
	$authArr = DataStorage::GetData('Auth.json');
	$authArr = $authArr ? json_decode($authArr,true) : array();
	switch ($level) {
		case 'tester':
			if (isset($authArr['tester']) && in_array($userId,$authArr['tester'])) return true;
		case 'group':
			if (!empty($userInfo->role) && in_array($userInfo->role,['admin','owner'])) return true;
		case 'admin':
			if (isset($authArr['admin']) && in_array($userId,$authArr['admin'])) return true;
		case 'master':
			if ($userId == Config('master')) return true;
		default:
			return q('权限不足');
			break;
	}
	
}