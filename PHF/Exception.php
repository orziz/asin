<?php

/**
 * 错误类
 */

namespace PHF;

class Exception extends \Exception {

    public static function throw($msg, $code = 0, $data = null) {
        Response::return($data, $msg, $code + 400);
    }

}
