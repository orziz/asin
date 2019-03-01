<?php

require_once '../source/class/class_core.php';

$userInfo = getData('userinfo.json');

$userInfo = json_decode($userInfo,true);

for ($i = 0; $i < count($userInfo); $i++) {
	$data = $userInfo[$i];
	print_r($data);
	echo '<br><br>';
	$db->insert('userinfo',$data);
}