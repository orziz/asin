<?php

namespace Domain;

class UserAttr {

    private $model;

    public function __construct()
    {
        $this->model = new \Model\UserAttr();
    }

    public function getUserAttr($qq) {
        return $this->model->getData($qq);
    }

    public function getUserAttrWithInfo($qq) {
        $dinfo = new \Domain\UserInfo();
        $user = $dinfo->getData($qq);
        if (!$user) return false;
        $info = $this->getUserAttr($qq);
        foreach ($info as $key => $value) {
            $user[$key] = $value;
        }
        return $user;
    }

    public function getUserAttrWithFight($qq) {
        $user = $this->getUserAttrWithInfo($qq);
        if (!$user) return false;
        $user['nickName'] = $user['nickname'];
        $user['maxBld'] = $user['bld'] = 50+floor(log10($user['con']+1)*50);
        $user['atk'] = 20+floor(log10($user['str']+1)*20);
        $user['crit'] = floor(log10($user['dex']+1)*15);
        $user['rat'] = floor(log10($user['wis']+1)*20);
        return $user;
    }

}