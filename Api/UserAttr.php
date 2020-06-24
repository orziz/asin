<?php

/**
 * API 的示例
 */
namespace Api;

use \PHF\ApiBase;

class UserAttr extends ApiBase {

    private $model;

    /**
     * 构造函数可有可无，你懂的
     */
    public function __construct()
    {
        $this->model = new \Model\UserAttr();
        parent::__construct();
    }

    /**
     * 当 mod 为 Example, action 为 test，执行该方法
     *
     * @return void
     */
    public function getUserAttr($qq = null) {
        // 示例示例，不要当
        // $userAttr = $this->model->getData($qq);
        // if (!$userAttr) {

        // } else {
        //     $userInfo = C::t('userinfo')->getData($qq);
        //     $userAttr['nickname'] = $userInfo['nickname'];
        //     $res['errCode'] = 200;
        //     $res['data'] = $userAttr;
        // }
        // $DBTest->getAllData();
    }

}