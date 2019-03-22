<?php

require_once '../source/class/class_core.php';

$checkinData = C::t('checkin')->getAllData();
$userScoreData = C::t('userscore')->getAllData();

$checkinMember = array();
$userScoreMember = array();

for ($i=0; $i < count($checkinData); $i++) { 
    array_push($checkinMember,$checkinData[$i]['qq']);
}

for ($k=0; $k < count($userScoreData); $k++) {
    if (in_array($userScoreData[$k]['qq'],array(1,37,1063614727,2201565219))) continue;
    array_push($userScoreMember,$userScoreData[$k]['qq']);
}

$outMember = array_diff($userScoreMember,$checkinMember);

Log::Debug('userScoreMember==>'.json_encode($outMember));
Log::Debug('checkinMember==>'.json_encode($checkinMember));
Log::Debug('outMember==>'.json_encode($outMember));

for ($j=0; $j < count($outMember); $j++) {
    Log::Debug(json_encode($outMember));
    C::t('userscore')->setData($outMember[$j],array('score'=>0,'credit'=>0));
}