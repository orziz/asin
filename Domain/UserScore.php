<?php

namespace Domain;

class UserScore {

    private $model;
    public function __construct()
    {
        $this->model = new \Model\UserScore();
    }

    public function getData($qq) {
        return $this->model->getData($qq);
    }

    public function setData($qq, $datas) {
        return $this->model->setData($qq, $datas);
    }

    public function add($qq, $score = 0, $credit = 0) {
        $userScore = $this->getData($qq);
        if (!$userScore) return -1;
		$_score = max(0,intval($userScore['score'])+$score);
		$_credit = max(0,intval($userScore['credit'])+$credit);
		if ($userScore['rank'] > 0) {
			return $this->model->setData($qq,array(
				'credit'=>$_credit
			));
		} else {
            return $this->model->setData($qq,array(
                'score'=>$_score,
                'credit'=>$_credit
            ));
        }
    }

    public function getRank($qq) {
        return $this->model->getRank($qq);
    }

    public function getRankList($limit = 0) {
        return $this->model->getRankList($limit);
    }

}