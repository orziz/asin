<?php

$qq = getgpc('qq','param',0);
$nickName = getgpc('nickName','param','');


if ($action == 'newUserInfo') {

} elseif ($action = 'getUserInfo') {
	$userInfo = C::t('userinfo')->getUserInfo($qq);
	if (!$userInfo) {
		$res['errCode'] = 301;
		$res['errMsg'] = '你没有加入刺客组织';
	} else {
		$res['errCode'] = 200;
		$res['data'] = $userInfo;
	}
}