<?php

require_once '../source/class/class_core.php';

$userInfo = $db->fetch('userinfo');
setData('userinfo.json',json_encode($userInfo));

$userScore = $db->fetch('userscore');
setData('userscore.json',json_encode($userScore));

for ($i = 0; $i < count($userInfo); $i++) {
	print_r($userInfo[$i]);
	echo '<br><br>';
}