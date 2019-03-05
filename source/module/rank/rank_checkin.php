<?php

if ($action == 'getRankList') {
	$rankList = C::t('checkin')->getRankList(10);
	$res['errCode'] = 200;
	$res['data'] = $rankList;
}