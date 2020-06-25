<?php

/**
 * 数据库操作（示例）
 * 先 new 再操作
 */

namespace Model;

use \DB\Model;

class UserAttr extends Model {

    public function __construct() {
        $this->_table = "asin_userattr";
        $this->_pk = "qq";
        parent::__construct();
    }
 
}