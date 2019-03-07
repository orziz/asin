<?php

$username = getgpc('username','PARAM');
$password = getgpc('username','PARAM');
$token = getgpc('username','PARAM');

session_set_cookie_params(24*3600); 
session_start();

if ($token) {
	$token = $rcnb->decode($token);
	if ($token == $_SESSION['asinToken']) {
		$res['errCode'] = 200;
		$res['errMsg'] = '登录成功';
	} else {
		$res['errCode'] = 401;
		$res['errMsg'] = '登录态失效';
	}
} else {
	if (!$username) {
		$res['errCode'] = 301;
		$res['errMsg'] = '未输入用户名';
	} elseif (!$password) {
		$res['errCode'] = 302;
		$res['errMsg'] = '未输入密码';
	} else {
		$userInfo = C::t('user')->getData($username);
		if (!$userInfo) {
			$res['errCode'] = 311;
			$res['errMsg'] = '没有该用户';
		} else {
			$password = md5($rcnb->encode(md5(md5($username).md5($password))));
			if ($password == $userInfo['password']) {
				$token = $rcnb->encode($password).'_'.time();
				$_SESSION['asinToken'] = $token;
				$res['errCode'] = 200;
				$res['errMsg'] = '登录成功';
				$res['data'] = array('token'=>$token);
			} else {
				$res['errCode'] = 303;
				$res['errMsg'] = '密码错误';
			}
		}
	}
}