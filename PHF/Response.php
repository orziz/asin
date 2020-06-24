<?php

/**
 * 响应类
 */

namespace PHF;

class Response {
    
    public static function return($data = null, $msg = '', $code = 200) {
        self::json($data, $msg, $code);
    }

    public static function json($data = null, $msg = '', $code = 200) {
        echo json_encode(array(
            'errCode' => $code,
            'errMsg' => $msg,
            'data' => $data
        ));
        exit(0);
    }

}