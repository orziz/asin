<?php

if (in_array($event->groupId,['719994813'])) {
	return $event->sendBack('这是测试');
	$data = param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userinfo', 'action'=>'getUserInfo', 'qq'=>$qq));
	if (!$data && $data['errCode'] === 301) {

	}
	else {
		if ($data['errCode'] !== 200) {
			$msg .= $data['errMsg'];
		}
		else {
			$userInfo = $data['data'];
			if ($userInfo['score'] > 0) {
				$rand = mt_rand(0,100);
				if ($rand >= 0) {

				}
			}
		}
	}
}