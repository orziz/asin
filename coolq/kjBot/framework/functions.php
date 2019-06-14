<?php

require_once('miscClass.php');

use kjBot\Framework\Event\BaseEvent;
use kjBot\Framework\TargetType;
use kjBot\Framework\Message;
use kjBot\Framework\DataStorage;

/**
 * 是否在黑名单内
 *
 * @param [type] $event
 * @return boolean
 */
function isBan($event) {
	if (!$event) return false;
	$userId = $event->getId();
	$authArr = DataStorage::GetData('Auth.json');
	$authArr = $authArr ? json_decode($authArr,true) : array();
	if (isset($authArr['baner']) && in_array($userId,$authArr['baner'])) return true;
	return false;
}

/**
 * 检查权限
 *
 * @param [type] $event
 * @param string $level 最低等级
 * @return void
 */
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

/**
 * 检查群权限
 *
 * @param [type] $event
 * @param [type] $group
 * @return void
 */
function checkGroup($event,$group) {
	if (!$event->groupId) return false;
	if (!$group) return false;
	$groupData = DataStorage::GetData('GroupAuth.json');
	$groupData = $groupData ? json_decode($groupData,true) : array();
	if (!isset($groupData[$group])) return false;
	return in_array($event->groupId,$groupData[$group]);
}