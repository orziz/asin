<?php

$Modules = [
	'测试' => kjBotModule\Common\Home\Test::class,
	'say' => kjBotModule\Common\Home\Say::class,
	'你好' => kjBotModule\Hello\Main::class,
	'踢出' => kjBotModule\Common\GroupManager\Kick::class,
	'禁言' => kjBotModule\Common\GroupManager\Ban::class,
	'获取我的信息' => kjBotModule\Common\Home\SenderInfo::class,
	'添加权限' => kjBotModule\Common\Manager\AddAuth::class,
	'移出权限' => kjBotModule\Common\Manager\RmAuth::class,
	'添加群组' => kjBotModule\Common\Manager\AddGroup::class,
	'移出群组' => kjBotModule\Common\Manager\RmGroup::class,
	'添加赞助' => kjBotModule\Common\Support\Add::class,
	'查看赞助' => kjBotModule\Common\Support\View::class,
	'roll' => kjBotModule\Common\Roll\Roll::class,

	'关闭功能' => kjBotModule\Common\Manager\CloseMod::class,
	'开启功能' => kjBotModule\Common\Manager\OpenMod::class,

	'垃圾分类' => kjBotModule\Common\Life\Trash::class,

	'.m' => kjBotModule\Monopoly\Main::class,
];
