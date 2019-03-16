<?php

// 账号唯一标识（QQ号绑定）
$qq = getgpc('qq','param',0);

if ($action == 'getUserAttr') {
    $userAttr = C::t('userattr')->getData($qq);
    if (!$userAttr) {
        $res['errCode'] = 301;
        $res['errMsg'] = '没有该用户';
    } else {
        $userInfo = C::t('userinfo')->getData($qq);
        $userAttr['nickname'] = $userInfo['nickname'];
        $res['errCode'] = 200;
        $res['data'] = $userAttr;
    }
}