<?php

/**
 * 数据库操作（示例）
 * 先 new 再操作
 */

namespace Model;

use \DB\Model;

class UserInfo extends Model {

    public function __construct()
    {
        $this->_table = 'asin_userinfo';
        $this->_pk = 'qq';
        parent::__construct();
    }

}