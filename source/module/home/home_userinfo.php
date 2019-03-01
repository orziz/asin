<?php

$openid = getgpc('openid');
$nickname = getgpc('nickname') ? getgpc('nickname') : null;
$avatarurl = getgpc('avatarurl') ? getgpc('avatarurl') : null;
$gender = getgpc('gender') ? getgpc('gender') : 0;
$country =  getgpc('country') ? getgpc('country') : null;
$proid = getgpc('proid') ? getgpc('proid') : 0;
$province = getgpc('province') ? getgpc('province') : null;
$cityid = getgpc('cityid') ? getgpc('cityid') : 0;
$city =  getgpc('city') ? getgpc('city') : null;


C::t('userinfo')->newUserInfo($db,$openid,$nickname,$avatarurl,$gender,$country,$proid,$province,$cityid,$city);
$userinfo = C::t('userinfo')->getUserInfo($db,$openid);
echo json_encode($userinfo);