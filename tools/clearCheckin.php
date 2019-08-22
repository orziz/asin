<?php

require_once '../source/class/class_core.php';

$checkinInfo = C::t('checkin')->getAllData();

$today = time();
$day = getTime("Y-m-d", $today);
$yday = getTime("Y-m-d", $today - 80400);

for ($i = 0; $i < count($checkinInfo); $i++) {
    if ($checkinInfo[$i]['lday'] != $day && $checkinInfo[$i]['lday'] != $yday) {
        C::t('checkin')->cleanDataByQQ($checkinInfo[$i]['qq']);
    }
}