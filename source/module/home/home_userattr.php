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
} elseif ($action == 'addUserAttr') {
    $userAttr = C::t('userAttr')->getData($qq);
    if (!$userAttr) {
        $res['errCode'] = 301;
        $res['errMsg'] = '没有该用户';
    } else {
        $userAttrFields = C::t('userAttr')->getFields();
        $data = array();
        foreach ($param as $key => $value) {
            if (isset($userAttr[$key])) $data[$key] = $value;
        }
        $isSuccess = C::t('userattr')->addAttr($qq,$data);
        if (!$isSuccess) {
            $res['errCode'] = 302;
            $res['errMsg'] = '修改失败';
        } else {
            if ($isSuccess === 301) {
                $res['errCode'] = 303;
                $res['errMsg'] = '自由属性点不足';
            } else {
                $res['errCode'] = 200;
                $res['errMsg'] = C::t('userAttr')->getData($qq);
            }
        }
    }
}