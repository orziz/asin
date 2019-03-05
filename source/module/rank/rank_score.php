<?php

$num = getgpc('num','PARAM',0);

if ($action == 'getRankList') {
	$rankList = C::t('userscore')->getRankList((int)$num);
	$res['errCode'] = 200;
	$res['data'] = $rankList;
}