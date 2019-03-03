<?php

// 账号唯一标识（QQ号绑定）
$qq = getgpc('qq','param',0);

if ($action == 'checkin') {
	$userInfo = C::t('userinfo')->getUserInfo($qq);
	if (!$userinfo) {
		$res['errCode'] = 301;
		$res['errMsg'] = '没有该用户';
	} else {
		$isSuccess = C::t('checkin')->setCheckin($qq);
		if (!$isSuccess) {
			$res['errCode'] = 302;
			$res['errMsg'] = '签到失败';
		} else {
			$data = C::t('checkin')->getCheckin($qq);
			$res['errCode'] = 200;
			$res['errMsg'] = '签到成功';
			$res['data'] = $data;
		}
	}
}