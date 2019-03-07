<?php

$username = getgpc('username','PARAM');
$password = getgpc('username','PARAM');

$code = getgpc('code','PARAM');

if (!$username) {
	$res['errCode'] = 301;
	$res['errMsg'] = '未输入用户名';
} elseif (!$password) {
	$res['errCode'] = 302;
	$res['errMsg'] = '未输入密码';
} elseif (!$code) {
	$res['errCode'] = 303;
	$res['errMsg'] = '未输入验证码';
} else {
	if ($code != 'orzzz') {
		$res['errCode'] = 304;
		$res['errMsg'] = '验证码不正确';
	} else {
		$password = md5($rcnb->encode(md5(md5($username).md5($password))));
		$isSuccess = C::t('user')->setData($username,array('password'=>$password));
		if ($isSuccess) {
			$res['errCode'] = 200;
			$res['errMsg'] = '注册成功';
		} else {
			$res['errCode'] = 305;
			$res['errMsg'] = '注册失败';
		}
	}
}