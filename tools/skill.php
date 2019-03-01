<?php

require_once '../source/class/class_core.php';

$memberArr = $db->execute(sprintf("SELECT * FROM asin_userinfo"));

while ($userInfo = mysql_fetch_array($memberArr)) {
	C::t('userskill')->newUserSkill($db,$userInfo['qq'],$userInfo['skill1'],$userInfo['skill2'],$userInfo['skill3'],$userInfo['skill4']);
	$userSkill = C::t('userskill')->getUserSkill($db,$userInfo['qq']);
	echo $userSkill['qq'] . '：';
	echo '<br>';
	echo $userSkill['skill1'];
	echo '<br>';
	$skill1cd = strstr($userSkill['skill1'],'cd：');
	if ($skill1cd == null) $skill1cd = strstr($userSkill['skill1'],'CD：');
	if ($skill1cd != null) $skill1cd = substr($skill1cd, 5);
	if ($skill1cd == null) {
		if ($skill1cd == null) $skill1cd = strstr($userSkill['skill1'],'cd:');
		if ($skill1cd == null) $skill1cd = strstr($userSkill['skill1'],'CD:');
		if ($skill1cd != null) $skill1cd = substr($skill1cd, 3);
	}
	if ($skill1cd == null) {
		if ($skill1cd == null) $skill1cd = strstr($userSkill['skill1'],'冷却：');
		if ($skill1cd != null) $skill1cd = substr($skill1cd, 9);
	}
	if ($skill1cd == null) {
		if ($skill1cd == null) $skill1cd = strstr($userSkill['skill1'],'冷却:');
		if ($skill1cd != null) $skill1cd = substr($skill1cd, 6);
	}
	if ($skill1cd != null) {
		$skill1cd = strstr($skill1cd,'分钟',true);
	}
	echo $skill1cd;
	echo '<br>';
	echo $userSkill['skill2'];
	echo '<br>';
	$skill2cd = strstr($userSkill['skill2'],'cd：');
	if ($skill2cd == null) $skill2cd = strstr($userSkill['skill2'],'CD：');
	if ($skill2cd != null) $skill2cd = substr($skill2cd, 5);
	if ($skill2cd == null) {
		if ($skill2cd == null) $skill2cd = strstr($userSkill['skill2'],'cd:');
		if ($skill2cd == null) $skill2cd = strstr($userSkill['skill2'],'CD:');
		if ($skill2cd != null) $skill2cd = substr($skill2cd, 3);
	}
	if ($skill2cd == null) {
		if ($skill2cd == null) $skill2cd = strstr($userSkill['skill2'],'冷却：');
		if ($skill2cd != null) $skill2cd = substr($skill2cd, 9);
	}
	if ($skill2cd == null) {
		if ($skill2cd == null) $skill2cd = strstr($userSkill['skill2'],'冷却:');
		if ($skill2cd != null) $skill2cd = substr($skill2cd, 6);
	}
	if ($skill2cd != null) {
		$skill2cd = strstr($skill2cd,'分钟',true);
	}
	echo $skill2cd;
	echo '<br>';
	echo $userSkill['skill3'];
	echo '<br>';
	$skill3cd = strstr($userSkill['skill3'],'cd：');
	if ($skill3cd == null) $skill3cd = strstr($userSkill['skill3'],'CD：');
	if ($skill3cd != null) $skill3cd = substr($skill3cd, 5);
	if ($skill3cd == null) {
		if ($skill3cd == null) $skill3cd = strstr($userSkill['skill3'],'cd:');
		if ($skill3cd == null) $skill3cd = strstr($userSkill['skill3'],'CD:');
		if ($skill3cd != null) $skill3cd = substr($skill3cd, 3);
	}
	if ($skill3cd == null) {
		if ($skill3cd == null) $skill3cd = strstr($userSkill['skill3'],'冷却：');
		if ($skill3cd != null) $skill3cd = substr($skill3cd, 9);
	}
	if ($skill3cd == null) {
		if ($skill3cd == null) $skill3cd = strstr($userSkill['skill3'],'冷却:');
		if ($skill3cd != null) $skill3cd = substr($skill3cd, 6);
	}
	if ($skill3cd != null) {
		$skill3cd = strstr($skill3cd,'分钟',true);
	}
	echo $skill3cd;
	echo '<br>';
	echo $userSkill['skill4'];
	echo '<br>';
	$skill4cd = strstr($userSkill['skill4'],'cd：');
	if ($skill4cd == null) $skill4cd = strstr($userSkill['skill4'],'CD：');
	if ($skill4cd != null) $skill4cd = substr($skill4cd, 5);
	if ($skill4cd == null) {
		if ($skill4cd == null) $skill4cd = strstr($userSkill['skill4'],'cd:');
		if ($skill4cd == null) $skill4cd = strstr($userSkill['skill4'],'CD:');
		if ($skill4cd != null) $skill4cd = substr($skill4cd, 3);
	}
	if ($skill4cd == null) {
		if ($skill4cd == null) $skill4cd = strstr($userSkill['skill4'],'冷却：');
		if ($skill4cd != null) $skill4cd = substr($skill4cd, 9);
	}
	if ($skill4cd == null) {
		if ($skill4cd == null) $skill4cd = strstr($userSkill['skill4'],'冷却:');
		if ($skill4cd != null) $skill4cd = substr($skill4cd, 6);
	}
	if ($skill4cd != null) {
		$skill4cd = strstr($skill4cd,'分钟',true);
	}
	echo $skill4cd;
	echo '<br><br>';
	C::t('userskill')->setCD($db,$userSkill['qq'],$skill1cd,$skill2cd,$skill3cd,$skill4cd);
	echo '=================<br>';
	C::t('userskill')->setUseTime($db,$userSkill['qq'],'skill1');
	C::t('userskill')->setUseTime($db,$userSkill['qq'],'skill2');
	C::t('userskill')->setUseTime($db,$userSkill['qq'],'skill3');
	C::t('userskill')->setUseTime($db,$userSkill['qq'],'skill4');
}