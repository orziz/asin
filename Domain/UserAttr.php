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

    public function setData($qq, $datas) {
        return $this->model->setData($qq, $datas);
    }

	/**
	 * 增加角色属性
	 *
	 * @param mixed $pk
	 * @param array $datas
	 * @return void
	 */
	public function addAttr($pk,array $datas) {
		$userAttr = $this->model->getData($pk);
		if (!$userAttr) return -2;
		$free = $userAttr['free'];
		if (isset($datas['qq'])) unset($datas['qq']);
		foreach ($datas as $key => $value) {
			if ($key != 'free') $free -= $value;
			if ($free < 0) return -1;
			$datas[$key] = max(0,$userAttr[$key]+$value);
			$datas['free'] = ($key == 'free') ? $datas[$key] : $free;
		}
		return $this->model->setData($pk,$datas);
    }
    
    /** 洗点 */
    public function resetAttr($pk) {
        $userAttr = $this->model->getData($pk);
        if (!$userAttr) return -1;
        $attr = 0;
        foreach ($userAttr as $key => $value) {
            if ($key === 'qq') continue;
            if ($key === 'free') continue;
            if ($value >= 20) {
                $attr += $value-20;
            } else {
                $attr -= 20-$value;
            }
            $userAttr[$key] = 20;
        }
        $userAttr['free'] += $attr;
        $qq = $userAttr['qq'];
        unset($userAttr['qq']);
        return $this->model->update($userAttr, array('qq'=>$qq));
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