<?php

/**
 * 获取文件路径
 * @param  string $libname 要获取的文件名
 * @param  string $folder  要获取的文件目录
 * @return [type]          文件的完整路径
 */
function libfile($libname, $folder = '') {
    $libpath = '/source/'.$folder;
    if(strstr($libname, '/')) {
        list($pre, $name) = explode('/', $libname);
        $path = "{$libpath}/{$pre}/{$pre}_{$name}";
    } else {
        $path = "{$libpath}/{$libname}";
    }
    return preg_match('/^[\w\d\/_]+$/i', $path) ? realpath(DIR_ROOT.$path.'.php') : false;
}

/**
 * 获取 POST/GET/COOKIE 信息
 * @param  [type] $k    [description]
 * @param  string $type 要获取信息的来源类型
 * @return [type]       [description]
 */
function getgpc($k, $type='GP',$value=NULL) {
    $type = strtoupper($type);
    switch($type) {
        case 'G': $var = &$_GET; break;
        case 'P': $var = &$_POST; break;
        case 'C': $var = &$_COOKIE; break;
        case 'PARAM': global $param; $var = $param; break;
        default:
            if(isset($_GET[$k])) {
                $var = &$_GET;
            } elseif (isset($_POST[$k])) {
                $var = &$_POST;
            } else {
                $var = json_decode(file_get_contents('php://input'),true);
            }
            break;
    }

    return (isset($var[$k]) && $var[$k] != 'null') ? $var[$k] : $value;

}

/**
 * 记录数据
 * @param string $filePath 相对于 storage/data/ 的路径
 * @param $data 要存储的数据内容
 * @param bool $pending 是否追加写入（默认不追加）
 * @return mixed string|false
 */
function setData(string $filePath, $data, bool $pending = false) {
    if(!file_exists(dirname(DIR_ROOT.'./storage/data/'.$filePath))) if(!mkdir(dirname(DIR_ROOT.'./storage/data/'.$filePath), 0777, true))throw new \Exception('Failed to create data dir');
    return file_put_contents(DIR_ROOT.'./storage/data/'.$filePath, $data, $pending?(FILE_APPEND | LOCK_EX):LOCK_EX);
}

/**
 * 读取数据
 * @param $filePath 相对于 storage/data/ 的路径
 * @return mixed string|false
 */
function getData(string $filePath) {
    if (!file_exists(DIR_ROOT.'./storage/data/'.$filePath)) return false;
    return file_get_contents(DIR_ROOT.'./storage/data/'.$filePath);
}

/**
 * 获取当前时间
 * @param string $format    时间格式
 * @param string $time      时间戳
 */
function getTime(string $format='Y-m-d H:i:s',$time=null) {
    if (!$time) $time = time();
    return date($format, $time);
}

/**
 * 解析编码
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function detect_encoding($str) {
    $list = array('GBK', 'GB2312' ,'UTF-8', 'UTF-16LE', 'UTF-16BE', 'ISO-8859-1');
    foreach ($list as $item) {
        $tmp = mb_convert_encoding($str, $item, $item);
        if (md5($tmp) == md5($str)) {
            return $item;
        }
    }
    return '遇到识别不出来的编码！';
}

function runquery($sql) {
    global $_config;
    global $db;
    $tablepre = $_config['dbhost']['tablepre'];
    $dbcharset = $_config['dbhost']['dbcharset'];

    $sql = str_replace(array(' cdb_', ' `cdb_', ' pre_', ' `pre_'), array(' {tablepre}', ' `{tablepre}', ' {tablepre}', ' `{tablepre}'), $sql);
    $sql = str_replace("\r", "\n", str_replace(array(' {tablepre}', ' `{tablepre}'), array(' '.$tablepre, ' `'.$tablepre), $sql));

    $ret = array();
    $num = 0;
    foreach(explode(";\n", trim($sql)) as $query) {
        $queries = explode("\n", trim($query));
        foreach($queries as $query) {
            if (!isset($ret[$num])) $ret[$num] = '';
            $ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[0]) &&  isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
        }
        $num++;
    }
    unset($sql);

    foreach($ret as $query) {
        $query = trim($query);
        if($query) {
            // echo $query.'<br>';
            if(substr($query, 0, 12) == 'CREATE TABLE') {
                $name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
                $sql = createtable($query, $dbcharset);
                $db->query($sql);
                // $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $query));
                // $type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
                // DB::query($query." ENGINE=$type DEFAULT CHARSET=$dbcharset");
            } else {
                $db->query($query);
            }
            // DB::query($query);
        }
    }
}

function createtable($sql, $dbcharset) {
    $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
    $type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
    if (substr($sql, -1) == ';') $sql = substr($sql, 0, -1);
    return $sql." ENGINE=$type DEFAULT CHARSET=$dbcharset";
    // return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
    // " ENGINE=$type DEFAULT CHARSET=$dbcharset";
}

/**
 * post提交
 * @param  string $url   提交到的url
 * @param  string $param 提交的参数
 * @return [type]        [description]
 */
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }
    
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    
    return $data;
}

function param_post($url = '', $param = '') {
    if (empty($url) || empty($param) || !is_array($param)) {
        return false;
    }
    $param = json_encode($param);
    $data = request_post($url,json_encode(array('param' => $param)));
    $data = json_decode($data,true);
    return $data;
}

if(PHP_VERSION >= '7.0.0'){
    function mysql_pconnect($dbhost, $dbuser, $dbpass){
        global $dbport;
        global $dbname;
        global $mysqli;
        $mysqli = mysqli_connect("$dbhost:$dbport", $dbuser, $dbpass, $dbname);
        return $mysqli;
    }
    function mysql_connect($url,$user,$pwd,$base){
        return mysqli_connect($url, $user, $pwd, $base);
    }
    function mysql_select_db($dbname,$conn){
        return mysqli_select_db($conn, $dbname);
        }
    function mysql_fetch_array($result){
        return mysqli_fetch_array($result);
        }
    function mysql_fetch_assoc($result){
        return mysqli_fetch_assoc($result);
        }
    function mysql_fetch_row($result){
        return mysqli_fetch_row($result);
        }
    function mysql_query($query,$conn){
        return mysqli_query($conn, $query);
        }
    function mysql_escape_string($data,$conn){
        return mysqli_real_escape_string($conn, $data);
        //return addslashes(trim($data));
        }
    function mysql_real_escape_string($data){
        return mysqli_real_escape_string(DB::$db,$data);
        }
    function mysql_close($conn){
        return mysqli_close($conn);
        }
    function mysql_errno($conn){
        return mysqli_errno($conn);
    }
    function mysql_error($conn){
        return mysqli_error($conn);
    }
    function mysql_fetch_field($query) {
        return mysqli_fetch_field($query);
    }
    function mysql_fetch_fields($query) {
        return mysqli_fetch_fields($query);
    }
}