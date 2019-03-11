<?php

$qq = getgpc('qq','PARAM');
$score = getgpc('score','PARAM',0);
$credit = getgpc('credit','PARAM',0);
$score = (int)$score;
$credit = (int)$credit;

if ($action == 'add') {
	$userScore = C::t('userscore')->getData($qq);
	if ($userScore) {
		$_score = max(0,intval($userScore['score'])+$score);
		$_credit = max(0,intval($userScore['credit'])+$credit);
		if ($userScore['rank'] > 0) {
			$isSuccess = C::t('userscore')->setData(array(
				'credit'=>$_credit
			));
		} else {
			if ($score !== 0 && $credit !== 0) {
				$isSuccess = C::t('userscore')->setData(array(
					'score'=>$_score,
					'credit'=>$_credit
				));
			} elseif ($score !== 0) {
				$isSuccess = C::t('userscore')->setData(array(
					'score'=>$_score
				));
			} elseif ($credit !== 0) {
				$isSuccess = C::t('userscore')->setData(array(
					'credit'=>$_credit
				));
			}
		}
		if ($isSuccess) {
			$res['errCode'] = 200;
			$res['data'] = array('score'=>$_score,'credit'=>$_credit,'isNPC'=>($userScore['rank'] > 0));
		} else {
			$res['errCode'] = 302;
			$res['errMsg'] = '修改失败';
		}
	} else {
		$res['errCode'] = 301;
		$res['errMsg'] = '没有该用户';
	}
}