<?php

namespace Domain;

class UserInfo {

    private $model;
    public function __construct()
    {
        $this->model = new \Model\UserInfo();
    }

    public function getData($qq) {
        return $this->model->getData($qq);
    }

    public function newUser($qq, $datas = array()) {
        $DScore = new \Domain\UserScore();
        $DAttr = new \Domain\UserAttr();
        $DSkill = new \Domain\UserSkill();
        // 响应请求为新建用户
        $userInfo = $this->getData($qq);
        if ($userInfo) return -1;
        $newUserInfo = $this->model->setData($qq,array(
            'nickname'=> $datas['nickname'] ?? '无名刺客',
            'sex'=> $datas['sex'] ?? 0,
            'age'=> $datas['age'] ?? 18,
            'height'=> $datas['height'] ?? 170,
            'weight'=> $datas['weight'] ?? 60,
            'arms'=> $datas['arms'] ?? '',
            'introduce'=> $datas['introduce'] ?? '此人太过神秘，暂时没有相关信息'
        ));
        if (!$newUserInfo) return -2;
        $newUserScore = $DScore->setData($qq,array(
            'score'=> $datas['score'] ?? 0,
            'credit'=> $datas['credit'] ?? 0,
            'rank'=> $datas['rank'] ?? 0
        ));
        if (!$newUserScore) return -3;
        $newUserAttr = $DAttr->setData($qq,array(
            'str'=> $datas['str'] ?? 0,
            'dex'=> $datas['dex'] ?? 0,
            'con'=> $datas['con'] ?? 0,
            'ine'=> $datas['ine'] ?? 0,
            'wis'=> $datas['wis'] ?? 0,
            'cha'=> $datas['cha'] ?? 0,
            'free'=> $datas['free'] ?? 150
        ));
        if (!$newUserAttr) return -4;
        $newUserSkill = $DSkill->setData($qq,array(
            'skill1'=> $datas['skill1'],
            'skill2'=>$datas['skill2'],
            'skill3'=>$datas['skill3'],
            'skill4'=>$datas['skill4']
        ));
        if (!$newUserSkill) return -5;
        return true;
    }

}