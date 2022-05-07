<?php

namespace Domain;

class UserSkill {

    private $model;
    public function __construct()
    {
        $this->model = new \Model\UserSkill();
    }

    public function setData($qq, $datas) {
        return $this->model->setData($qq, $datas);
    }

}