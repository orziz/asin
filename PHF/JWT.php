<?php

namespace PHF;

class JWT {

    private static function secret() {
        return Config::get('jwt.secret', '');
    }

    private static function time() {
        return Config::get('jwt.time', 60*60*24);
    }

    public static function createJWT($id) {
        $time = time();
        $token = md5($id.($time + self::time()).self::secret());
        return array(
            'id' => $id,
            'time' => $time,
            'token' => $token
        );
    }

    public static function checkJWT($jwt) {
        if (empty($jwt)) return -1;
        if (empty($jwt['id'])) return -1;
        if (empty($jwt['time'])) return -1;
        if (empty($jwt['token'])) return -1;
        $time = $jwt['time'] + self::time();
        $token = md5($jwt['id'].$time.self::secret());
        if ($jwt['token'] == $token) {
            if (time() < $time) return 1;
            return 2;
        }
        return -1;
    }

}