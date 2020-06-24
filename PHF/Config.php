<?php

/**
 * 配置类
 */

namespace PHF;

class Config {

    private static $paramKeySeparator = '.';

    /**
     * getValue
     *
     * @param string $key
     * @param mixed $def    默认返回值
     * @param string $file 请求的配置文件，默认为global，无需携带.php
     * @return mixed    返回值
     */
    public static function get($key,$def = null,$file = null){
        $path = DIR_ROOT.'config'.DIRECTORY_SEPARATOR.($file ?? 'global').'.php';
        $config = @include($path);
        if (!$config && !is_array($config)) return $def;
        if (!self::$paramKeySeparator) self::$paramKeySeparator = self::get('paramKeySeparator', '.');
        $keys = explode(self::$paramKeySeparator, $key);
        $rs = null;
        foreach ($keys as $value) {
            if (!isset($config[$value])) {
                $rs = null;
                break;
            }
            $config = $rs = $config[$value];
        }
        return $rs !== null ? $rs : $def;
    }

}
