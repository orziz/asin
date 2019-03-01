<?php

require_once 'source/class/class_core.php';

// $bagArr = DB::fetch_all(sprintf("SELECT * FROM %s",'asin_userbag'));
// $bagArr = $db->execute(sprintf("SELECT * FROM %s",'asin_userbag'));

// // for ($i=0; $i < count($bagArr); $i++) {
// // 	$data = $bagArr[$i]['bag'];
// // 	var_dump($data);
// // 	echo '<br><br>';
// // }
// while ($data = mysql_fetch_array($bagArr)) {
// 	var_dump(unserialize($data['bag']));
// 	echo '<br><br>';
// }
$userArr = $db->execute(sprintf("SELECT * FROM %s",'asin_userinfo'));
while ($data = mysql_fetch_array($userArr)) {
	C::t('userinfo')->add($db,$data['qq'],'free',5);
}

// var_dump(C::t('userbag')->newItem($db,1063614727,'牛眼泪',1));