<?php

$qq = getgpc('qq','PARAM');
$num = getgpc('num','PARAM',0);

if ($action == 'getRankList') {
	$rankList = C::t('userscore')->getRankList((int)$num);
	$res['errCode'] = 200;
	$res['data'] = $rankList;
} elseif ($action == 'getMyRank') {
	$rank = C::t('userscore')->getRank($qq);
	if ($rank) {
		$res['errCode'] = 200;
		$res['data'] = array('rank'=>$rank);
	} else {
		$userInfo = C::t('userinfo')->getData($qq);
		if (!$userInfo) {
			$res['errCode'] = 301;
			$res['errMsg'] = '没有该用户';
		} else {
			$res['errCode'] = 302;
			$res['errMsg'] = '查询失败';
		}
	}
}