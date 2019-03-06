<?php

// 账号唯一标识（QQ号绑定）
$qq = getgpc('qq','param',0);
// 刺客名
$nickname = getgpc('nickname','param','');
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
$introduce = getgpc('introduce','param','');
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
	$userInfo = C::t('userinfo')->getData($qq);
	if ($userInfo) {
		// 如果该用户已存在，则返回错误信息
		$res['errCode'] = 301;
		$res['errMsg'] = '该账户已存在';
	} else {
		$newUserInfo = C::t('userinfo')->setData($qq,array(
			'nickname'=>$nickname,
			'sex'=>$sex,
			'age'=>$age,
			'height'=>$height,
			'weight'=>$weight,
			'arms'=>$arms,
			'introduce'=>$introduce
		));
		if ($newUserInfo) {
			$newUserScore = C::t('userscore')->setData($qq,array(
				'score'=>$score,
				'credit'=>$credit,
				'rank'=>$rank
			));
			if ($newUserScore) {
				$newUserAttr = C::t('userattr')->setData($qq,array(
					'str'=>$str,
					'dex'=>$dex,
					'con'=>$con,
					'ine'=>$ine,
					'wis'=>$wis,
					'cha'=>$cha,
					'free'=>$free
				));
				if ($newUserAttr) {
					$newUserSkill = C::t('userskill')->setData($qq,array(
						'skill1'=>$skill1,
						'skill2'=>$skill2,
						'skill3'=>$skill3,
						'skill4'=>$skill4
					));
					if ($newUserSkill) {
						$rank = C::t('userscore')->getRank($qq);
						$res['errCode'] = 200;
						$res['data'] = array('nickname'=>$nickname,'rank'=>$rank);
					} else {
						$res['errCode'] = 305;
						$res['errMsg'] = '写入 userskill 失败';
					}
				} else {
					$res['errCode'] = 304;
					$res['errMsg'] = '写入 userattr 失败';
				}
			} else {
				$res['errCode'] = 303;
				$res['errMsg'] = '写入 userscore 失败';
			}
		} else {
			$res['errCode'] = 302;
			$res['errMsg'] = '写入 userinfo 失败';
		}
	}
} elseif ($action == 'setUserInfo') {
	$userFields = C::t('userinfo')->getFields();
	$datas = array();
	foreach ($param as $key => $value) {
		if (isset($userFields[$key])) $datas[$key] = $value;
	}
	$isSuccess = C::t('userinfo')->setData($qq,$datas);
	if ($isSuccess) {
		$res['errCode'] = 200;
		$res['errMsg'] = '设置成功';
	} else {
		$res['errCode'] = 301;
		$res['errMsg'] = '设置失败';
	}
} elseif ($action == 'getUserInfo') {
	$userInfo = C::t('userinfo')->getData($qq);
	if (!$userInfo) {
		$res['errCode'] = 301;
		$res['errMsg'] = '你没有加入刺客组织';
	} else {
		$userScore = C::t('userscore')->getData($qq);
		$userInfo['score'] = $userScore['score'];
		$userInfo['credit'] = $userScore['credit'];
		$userInfo['rank'] = C::t('userscore')->getRank($qq);
		$res['errCode'] = 200;
		$res['data'] = $userInfo;
	}
} elseif ($action == 'getUserInfoByWeb') {
	$userInfo = C::t('userinfo')->getData($qq);
	if (!$userInfo) {
		$res['errCode'] = 301;
		$res['errMsg'] = '你没有加入刺客组织';
	} else {
		$userScore = C::t('userscore')->getData($qq);
		$userAttr = C::t('userattr')->getData($qq);
		$userSkill = C::t('userskill')->getData($qq);
		$userInfo['score'] = $userScore['score'];
		$userInfo['credit'] = $userScore['credit'];
		$userInfo['rank'] = $userScore['rank'];
		$userInfo['str'] = $userAttr['str'];
		$userInfo['dex'] = $userAttr['dex'];
		$userInfo['con'] = $userAttr['con'];
		$userInfo['ine'] = $userAttr['ine'];
		$userInfo['wis'] = $userAttr['wis'];
		$userInfo['cha'] = $userAttr['cha'];
		$userInfo['free'] = $userAttr['free'];
		$userInfo['skill1'] = $userSkill['skill1'];
		$userInfo['skill2'] = $userSkill['skill2'];
		$userInfo['skill3'] = $userSkill['skill3'];
		$userInfo['skill4'] = $userSkill['skill4'];
		$res['errCode'] = 200;
		$res['data'] = $userInfo;
	}
} elseif ($action == 'setUserNickName') {
	$userInfo = C::t('userinfo')->getData($qq);
	if (!$userInfo) {
		$res['errCode'] = 301;
		$res['errMsg'] = '没有此用户';
	} else {
		$isSuccess = C::t('userinfo')->setData($qq,array('nickname'=>$nickname));
		if ($isSuccess) {
			$res['errCode'] = 200;
			$res['errMsg'] = '设置成功';
		} else {
			$res['errCode'] = 302;
			$res['errMsg'] = '设置失败';
		}
	}
}