<?php

namespace D;

class UserData {

    private $model;

    public function __construct()
    {
        $this->model = new \Model\UserAttr();
    }

    public function getUserAttr($qq) {
        // $userAttr = $this->model->getData($qq);
        // if (!$userAttr) return false; else {
        //     $userInfo = C::t('userinfo')->getData($qq);
        //     $userAttr['nickname'] = $userInfo['nickname'];
        //     $res['errCode'] = 200;
        //     $res['data'] = $userAttr;
        // }
        // $DBTest->getAllData();
    }

}