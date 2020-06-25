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

}