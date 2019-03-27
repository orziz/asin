<?php

// 账号唯一标识（QQ号绑定）
$qq = getgpc('qq','param',0);

if ($action == 'checkin') {
	if ($qq == '1063614727') {
		$cache = getData('test.txt');
		$cache = $cache ? (int)$cache : 0;
		$cache++;
		setData('test.txt',$cache);
	}
	$userInfo = C::t('userinfo')->getData($qq);
	if (!$userInfo) {
		$res['errCode'] = 301;
		$res['errMsg'] = '没有该用户';
	} else {
		$isCheckin = C::t('checkin')->isCheckin($qq);
		if ($isCheckin) {
			$res['errCode'] = 302;
			$res['errMsg'] = '今天已签到';
		} else {
			$isSuccess = C::t('checkin')->setData($qq);
			if (!$isSuccess) {
				$res['errCode'] = 303;
				$res['errMsg'] = '签到失败';
			} else {
				$data = C::t('checkin')->getData($qq);
				$res['errCode'] = 200;
				$res['errMsg'] = '签到成功';
				$res['data'] = $data;
			}
		}
	}
}