<?php

require_once('miscClass.php');

use kjBot\Framework\Event\BaseEvent;
use kjBot\Framework\TargetType;
use kjBot\Framework\Message;
use kjBot\Framework\DataStorage;

function checkAuth($event,$level='admin') {
	global $Config;
	if (!$event) return false;
	$userInfo = $event->getSenderInfo();
	$userId = $event->getId();
	$authArr = DataStorage::GetData('Auth.json');
	$authArr = $authArr ? json_decode($authArr,true) : array();
	switch ($level) {
		case 'group':
			if (!empty($userInfo->role) && in_array($userInfo->role,['admin','owner'])) return true;
			if (isset($authArr['admin']) && in_array($userId,$authArr['admin'])) return true;
			if ($userId == Config('master')) return true;
			return q('权限不足');
			break;
		case 'tester':
			if (isset($authArr['tester']) && in_array($userId,$authArr['tester'])) return true;
		case 'admin':
			if (isset($authArr['admin']) && in_array($userId,$authArr['admin'])) return true;
		case 'master':
			if ($userId == Config('master')) return true;
		default:
			return q('权限不足');
			break;
	}
	
}

function checkGroup($event,$group) {
	if (!$event->fromGroup()) return false;
	if (!$group) return false;
	$groupData = DataStorage::GetData('GroupAuth.json');
	$groupData = $groupData ? json_decode($groupData,true) : array();
	if (!isset($groupData[$group])) return false;
	return in_array($event->groupId,$groupData[$group]);
}