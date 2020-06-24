<?php

namespace PHF;

class ApiBase {

    protected $_needJWT = false;

    public function __construct() {
        if ($this->_needJWT) {
	        // $status = JWT::checkJWT(getgpc('jwt', 'PARAM'));
	        $status = JWT::checkJWT(Request::post('jwt'));
            if ($status === -1) {
                // return $this->setResults(401, '登录态无效');
                Exception::throw('登录态无效', -401);
            } elseif ($status === 2)  {
                Exception::throw('登录态过期', -401);
                // return $this->setResults(402, '登录态过期');
            }
        }
    }

    /**
     * 初始化 API 进程
     */
    public static function init() {

        //获取 mod 和 action
        // $mod = getgpc('mod', 'GP', getgpc('s'));
        // $action = getgpc('action');
        $mod = Request::param('mod', Request::param('s'));
        $action = Request::param('action');

        //将参数里的 mod 和 action 替换原值
        // $mod = getgpc('mod', 'PARAM', $mod);
        // $action = getgpc('action', 'PARAM', $action);

        $_modArr = explode('/', $mod);
        if (empty($action)) {
            // 如果未在参数中设置action，则从mod中分离action
            // if (count($_modArr) < 2) $api->setResults(-4, '请求方法不存在');
            if (count($_modArr) < 2) Exception::throw('请求方法不存在', -403);
            $action = array_pop($_modArr);
        }
        $mod = implode('\\', $_modArr);
        $className = '\Api\\'.$mod;
        // if (empty($mod)) $api->setResults(-2, '没有请求模组');
        // if (!class_exists($className)) $api->setResults(-3, '请求模组不存在');
        if (empty($mod)) Exception::throw('没有请求模组', -402);
        if (!class_exists($className)) Exception::throw('请求模组不存在', -402);
        $class = new $className();
        // if (!method_exists($class, $action)) $api->setResults(-4, '请求方法不存在');
        if (!method_exists($class, $action)) Exception::throw('请求方法不存在', -403);
        // return $class->$action();
        Response::return($class->$action());
        // $api->setResults(200, '', $reqData);
    }

//    public function setResults($code = 200, $msg = '', $data = null) {
//        echo json_encode(array(
//            'errCode' => $code,
//            'errMsg' => $msg,
//            'data' => $data
//        ));
//        exit(0);
//    }

}
