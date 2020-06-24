<?php

/**
 * 框架入口文件
 */

define('DIR_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
define('PHF_VERSION', '1.0.0');
date_default_timezone_set('Asia/Shanghai');

//require_once DIR_ROOT . implode(DIRECTORY_SEPARATOR, array('PHF', 'global.php'));
//require_once DIR_ROOT . implode(DIRECTORY_SEPARATOR, array('PHF', 'function', 'core.php'));
require_once DIR_ROOT . implode(DIRECTORY_SEPARATOR, array('PHF', 'Loader.php'));
//require_once DIR_ROOT . implode(DIRECTORY_SEPARATOR, array('PHF', 'Core', 'Log.php'));

spl_autoload_register('\PHF\Loader::autoload');

if (\PHF\Config::get('canCrossDomain')) {
    $_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
    header("Access-Control-Allow-Origin:". $_origin);
    header("Access-Control-Allow-Methods:". "POST, GET, OPTIONS, DELETE");//允许跨域的请求方式
    // header("Access-Control-Max-Age:". "3600");//预检请求的间隔时间
    header("Access-Control-Allow-Headers:". "Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With,userId,token,Access-Control-Allow-Headers");//允许跨域请求携带的请求头
    header("Access-Control-Allow-Credentials:"."true");
}
