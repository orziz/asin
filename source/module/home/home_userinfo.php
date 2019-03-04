<?php

// 账号唯一标识（QQ号绑定）
$qq = getgpc('qq','param',0);
// 刺客名
$nickName = getgpc('nickName','param','');
// 性别
$sex = getgpc('sex','param',0);
// 年龄
$age = getgpc('age','param',0);
// 身高（cm）
$height = getgpc('height','param',0);
// 体重（kg）
$weight = getgpc('weight','param',0);
// 力量
$str = getgpc('str','param',0);
// 敏捷
$dex = getgpc('dex','param',0);
// 体质
$con = getgpc('con','param',0);
// 智力
$ine = getgpc('ine','param',0);
// 感知
$wis = getgpc('wis','param',0);
// 魅力
$cha = getgpc('cha','param',0);
// 自由属性点
$free = getgpc('free','param',0);
// 武器
$arms = getgpc('arms','param','');
// 介绍
$introduce = getgpc('introduce','param','此人较为神秘，没有任何关于他的信息');
// 技能1
$skill1 = getgpc('skill1','param','');
// 技能2
$skill2 = getgpc('skill2','param','');
// 技能3
$skill3 = getgpc('skill3','param','');
// 技能4
$skill4 = getgpc('skill4','param','');
// 积分
$score = getgpc('score','param',0);
// 暗币
$credit = getgpc('credit','param',0);
// 排名
$rank = getgpc('rank','param',0);

if ($action == 'newUserInfo') {
	// 响应请求为新建用户
	$userInfo = C::t('userinfo')->getUserInfo($qq);
	if ($userInfo) {
		// 如果该用户已存在，则返回错误信息
		$res['errCode'] = 301;
		$res['errMsg'] = '该账户已存在';
	} else {
		$newUserInfo = C::t('userinfo')->setUserInfo($qq,$nickName,$sex,$age,$height,$weight,$arms,$introduce);
		if ($newUserInfo) {
			$newUserScore = C::t('userscore')->setUserScore($qq,$score,$credit,$rank);
			if ($newUserScore) {
				$newUserAttr = C::t('userattr')->setUserAttr($qq,$str,$dex,$con,$ine,$wis,$cha,$free);
				if ($newUserAttr) {
					$newUserSkill = C::t('userskill')->setUserAttr($qq,$skill1,$skill2,$skill3,$skill4);
				}
			}
		}
	}
} elseif ($action == 'setUserInfo') {
	# code...
} elseif ($action == 'getUserInfo') {
	$userInfo = C::t('userinfo')->getUserInfo($qq);
	if (!$userInfo) {
		$res['errCode'] = 301;
		$res['errMsg'] = '你没有加入刺客组织';
	} else {
		$res['errCode'] = 200;
		$res['data'] = $userInfo;
	}
}