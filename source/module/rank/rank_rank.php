<?php

if ($action == 'getRankList') {
	$rankList = C::t('userscore')->getRankList();
	$res['errCode'] = 200;
	$res['data'] = $rankList;
}