<?php

/**
 * 尝试过将 DB 写成一个静态类，但是想想思路好像不对，那就重构吧
 * 依旧使用 php-mysqli
 * 决定重新研究 php-mysqli
 */
class DBConn extends mysqli
{

    private $host;
    private $user;
    private $passwd;
    private $base;
    private $conn;

    
    function __construct($host,$user,$passwd,$base,$port=3306)
    {
        // 判断php版本，做你妹的兼容，都9102年了，还用5.3以下的版本？
        if (PHP_VERSION < '5.3.0') exit('请将 php 版本升级至 5.3.0 之上');

        // 调用 new mysqli 实例化对象
        parent::__construct($host,$user,$passwd,$base,$port);

        // 别问，问就是 utf-8
        $this->query('set names utf8');
        $this->query('set character set utf8');

        // 如果链接错误就直接报错
        if ($this->connect_errno) die("连接失败: " . $this->connect_error);
    }

    /**
     * 没其他意思，就是做老版本兼容
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
    public function execute($sql) {
        return $this->query($sql);
    }

    /**
     * 这里的 query 只是为了方便写日志
     * @param  [type] $sql          [description]
     * @param  [type] $resultmode   [description]
     * @return [type]               [description]
     */
    public function query($sql, $resultmode = NULL) {
        Log::Sql('query:::'.$sql);
        $resultSet = parent::query($sql, $resultmode);
        if (!$resultSet) {
            Log::Debug('mysql 查询失败：'.$this->error);
            die("执行失败：".$this->error);
        }
        return $resultSet;
    }

    /**
     * 获取当前库的所有表
     * @return [type] [description]
     */
    public function getTables() {
        $resArr = $this->query("show tables");
        $tables = array();
        while ($res = $resArr->fetch_array()) array_push($tables,$res[0]);
        return $tables;
    }

    /**
     * 插入表数据
     * @param  [type] $table 要操作的数据表
     * @param  [type] $data  要插入的数据
     * @return [type]        [description]
     */
    public function insert($table,$data) {
        if(!is_array($data)) return false;
        $k = array();
        $v = array();
        foreach ($data as $key => $value) {
            if (isset($data[$key]) && ($value !== null)) {
                array_push($k, trim($key,"`'\""));
                array_push($v, $this->quote($value));
            }
        }
        $k = '('.implode($k, ',').')';
        $v = '('.implode($v, ',').')';
        $sql = sprintf("INSERT INTO %s %s VALUES %s",_DBConn::table($table),$k,$v);
        return $this->query($sql);
    }

    /**
     * 更新表数据
     * @param  [type] $table 要操作的数据表
     * @param  [type] $data  要更新的数据
     * @param  [type] $check 要更新的键值
     * @return [type]        [description]
     */
    public function update($table,$data,$check) {
        if (!is_array($data) || empty($check)) return false;
        $sql = '';
        $_i = count($data);
        foreach ($data as $key => $value) {
            $_i--;
            if (isset($data[$key]) && ($value !== null)) {
                $sql .= $key . '=' . $this->quote($value);
                if ($_i != 0) $sql .= ' , ';
            }
        }
        $where = _DBConn::getWhere($check);
        $sql = sprintf("UPDATE %s SET %s WHERE %s",_DBConn::table($table),$sql,$where);
        // Log::Sql('update:::'.$sql);
        return $this->query($sql);
    }

    /**
     * 删除表数据
     * @param  [type] $table 要操作的数据表
     * @param  [type] $check 要操作的键值
     * @return [type]        [description]
     */
    public function delete($table,$check) {
        $where = _DBConn::getWhere($check);
        $sql = sprintf("DELETE FROM %s WHERE %s",_DBConn::table($table),$where);
        return $this->query($sql);
    }

    /**
     * 查询表数据
     * @param  [type]  $table  要查询的数据表
     * @param  string  $check  要查询的键值
     * @param  string  $field  要查询的字段
     * @param  string  $order  要查询的排列形式
     * @param  integer $limits 要查询的数据起点
     * @param  integer $limitn 要查询的数据个数
     * @return [type]          [description]
     */
    public function fetch($table,$check='',$field='*',$order='',$limits=0,$limitn=0) {
        $where = _DBConn::getWhere($check);
        $sql = sprintf("SELECT %s FROM %s",$field,_DBConn::table($table));
        if (!empty($where)) $sql .= sprintf(" WHERE %s",$where);
        if (!empty($order)) $sql .= sprintf(" ORDER BY %s",$order);
        if ($limitn !== 0) $sql .= sprintf(" LIMIT %d,%d",$limits,$limitn);
        $resArr = $this->query($sql);
        $resultSet = array();
        $resFeildArr = array();
        while ($resFeild = $resArr->fetch_field()) {
            $resFeildArr[$resFeild->name] = $resFeild->type;
        }
        while ($res = $resArr->fetch_array()) {
            foreach ($res as $key => $value) {
                if (intval($key) || $key == '0') {
                    unset($res[$key]);
                } else {
                    if ($resFeildArr[$key] == 'int' || $resFeildArr[$key] == 'bigint' || $resFeildArr[$key] === 3 || $resFeildArr[$key] === 8) $res[$key] = intval($value);
                }
            }
            array_push($resultSet,$res);
        }
        return $resultSet;
    }

    public function quote($v) {
        if (is_int($v) || is_float($v)) return '\'' . $v . '\'';
        if (is_string($v)) return '\'' . $this->real_escape_string($v) . '\'';
    }

}

/**
 * 写代码一时爽
 * 一直写一直爽
 */

class _DBConn
{
    public static $db;
    public static $field = array();

    /**
     * 初始化数据库链接
     * @return [type] [description]
     */
    public static function init() {
        global $_config;
        if (self::$db) return self::$db;
        $conn = mysql_connect($_config['dbhost']['ip'], $_config['dbhost']['user'], $_config['dbhost']['pwd'],$_config['dbhost']['base']);

        if ($conn === false) return false;

        // $ret = mysql_select_db($gConfig['dbhost']['base'], $conn);
        // if (!$ret) return false;

        self::$db = $conn;

        mysql_query("set names 'utf8'",self::$db);
        mysql_query("set character set 'utf8'",self::$db);
        // mysql_query("set character set 'utf8'");
        
        return self::$db;
    }

    /**
     * 获取当前数据库所有的表
     * @return [type] [description]
     */
    public static function getTables() {
        $resArr = self::query("show tables");
        $tables = array();
        while ($res = mysql_fetch_array($resArr)) array_push($tables,$res[0]);
        return $tables;
    }

    /**
     * 删除数据库里的某张表
     * @param  [type] $table 表名
     * @return [type]        [description]
     */
    public static function dropTable($table) {
        if (is_string($table)) $table = array($table);
        if (count($table) === 0) return true;
        //DROP TABLE `achivement`, `config`, `debugOut`, `enemy`, `formula`, `genius`, `level`, `map`, `mapRes`, `resList`, `skill`, `task`, `testData`, `tower`;
        $sql = 'DROP TABLE';
        for ($i = 0;$i<count($table);$i++) {
            $sql .= '`'.self::table($table[$i]).'`';
            if ($i !== count($table)-1) $sql .= ', ';
        }
        $sql .= ';';
        return self::query($sql);
    }

    /**
     * 将数据表名称格式化
     * @param  [type] $table 要格式化的表名
     * @return [type]        [description]
     */
    public static function table($table) {
        global $gConfig;
        if (substr($table, 0, strlen($gConfig['dbhost']['tablepre'])) == $gConfig['dbhost']['tablepre']) return $table;
        return $gConfig['dbhost']['tablepre'].$table;
    }

    /**
     * 执行SQL语句
     * @param  [type] $sql SQL语句
     * @return [type]      [description]
     */
    public static function query($sql) {
        if (!(self::$db)) self::init();
        Log::Sql('query:::'.$sql);
        $resultSet = mysql_query($sql, self::$db);

        if (!$resultSet) {
            // die('Error: ' . mysql_error(self::$db));
            die('Error: ' . mysql_error(self::$db));
        }
        $errno = mysql_errno(self::$db);
        
        if ($errno == 1062) $resultSet = 0;
        
        return $resultSet;
    }

    /**
     * 插入表数据
     * @param  [type] $table 要操作的数据表
     * @param  [type] $data  要插入的数据
     * @return [type]        [description]
     */
    public static function insert($table,$data) {
        if(!is_array($data)) return false;
        $k = array();
        $v = array();
        foreach ($data as $key => $value) {
            if (isset($data[$key]) && ($value !== null)) {
                array_push($k, trim($key,"`'\""));
                array_push($v, $this->quote($value));
            }
        }
        $k = '('.implode($k, ',').')';
        $v = '('.implode($v, ',').')';
        $sql = sprintf("INSERT INTO %s %s VALUES %s",_DBConn::table($table),$k,$v);
        // Log::Sql('insert:::'.$sql);
        return $this->query($sql);
    }

    /**
     * 更新表数据
     * @param  [type] $table 要操作的数据表
     * @param  [type] $data  要更新的数据
     * @param  [type] $check 要更新的键值
     * @return [type]        [description]
     */
    public static function update($table,$data,$check) {
        if (!is_array($data) || empty($check)) return false;
        $sql = '';
        $_i = count($data);
        foreach ($data as $key => $value) {
            $_i--;
            if (isset($data[$key]) && ($value !== null)) {
                $sql .= $key . '=' . DB::quote($value);
                if ($_i != 0) $sql .= ' , ';
            }
        }
        $where = self::getWhere($check);
        $sql = sprintf("UPDATE %s SET %s WHERE %s",self::table($table),$sql,$where);
        // Log::Sql('update:::'.$sql);
        return self::query($sql);
    }

    /**
     * 删除表数据
     * @param  [type] $table 要操作的数据表
     * @param  [type] $check 要操作的键值
     * @return [type]        [description]
     */
    public static function delete($table,$check) {
        $where = self::getWhere($check);
        $sql = sprintf("DELETE FROM %s WHERE %s",self::table($table),$where);
        return self::query($sql);
    }

    /**
     * 查询表数据
     * @param  [type]  $table  要查询的数据表
     * @param  string  $check  要查询的键值
     * @param  string  $field  要查询的字段
     * @param  string  $order  要查询的排列形式
     * @param  integer $limits 要查询的数据起点
     * @param  integer $limitn 要查询的数据个数
     * @return [type]          [description]
     */
    public static function fetch($table,$check='',$field='*',$order='',$limits=0,$limitn=0) {
        $where = self::getWhere($check);
        $sql = sprintf("SELECT %s FROM %s",$field,self::table($table));
        if (!empty($where)) $sql .= sprintf(" WHERE %s",$where);
        if (!empty($order)) $sql .= sprintf(" ORDER BY %s",$order);
        if ($limitn !== 0) $sql .= sprintf(" LIMIT %d,%d",$limits,$limitn);
        $resArr = self::query($sql);
        $resultSet = array();
        $resFeildArr = array();
        while ($resFeild = mysql_fetch_field($resArr)) {
            $resFeildArr[$resFeild->name] = $resFeild->type;
        }
        while ($res = mysql_fetch_array($resArr)) {
            foreach ($res as $key => $value) {
                if (intval($key) || $key == '0') {
                    unset($res[$key]);
                } else {
                    if ($resFeildArr[$key] == 'int' || $resFeildArr[$key] == 'bigint' || $resFeildArr[$key] === 3 || $resFeildArr[$key] === 8) $res[$key] = intval($value);
                }
            }
            array_push($resultSet,$res);
        }
        return $resultSet;
    }

    /**
     * 查询数据表并返回第一条数据（即将废弃）
     * @param  [type] $sql 要执行的SQL语句
     * @return [type]      [description]
     */
    public static function fetch_first($sql) {
        $res = self::query($sql);
        $resFeildArr = array();
        while ($resFeild = mysql_fetch_field($res)) {
            if (!isset($resFeildArr[$resFeild->name])) $resFeildArr[$resFeild->name] = $resFeild->type;
        }
        $res = mysql_fetch_array($res);
        if (is_array($res)) {
            foreach ($res as $key => $value) {
                if (intval($key) || $key == '0') {
                    unset($res[$key]);
                } else {
                    if ($resFeildArr[$key] == 'int' || $resFeildArr[$key] == 'bigint' || $resFeildArr[$key] === 3 || $resFeildArr[$key] === 8) $res[$key] = intval($value);
                }
            }
        }
        return $res ? $res : array();
    }

    /**
     * 查询数据表并返回所有数据（即将废弃）
     * @param  [type] $sql 要执行的SQL语句
     * @return [type]      [description]
     */
    public static function fetch_all($sql) {
        $resArr = self::query($sql);
        $resultSet = array();
        $resFeildArr = array();
        while ($resFeild = mysql_fetch_field($resArr)) {
            $resFeildArr[$resFeild->name] = $resFeild->type;
        }
        while ($res = mysql_fetch_array($resArr)) {
            foreach ($res as $key => $value) {
                if (intval($key) || $key == '0') {
                    unset($res[$key]);
                } else {
                    if ($resFeildArr[$key] == 'int' || $resFeildArr[$key] == 'bigint' || $resFeildArr[$key] === 3 || $resFeildArr[$key] === 8) $res[$key] = intval($value);
                }
            }
            array_push($resultSet,$res);
        }
        return $resultSet;
    }

    /**
     * 查询数据个数
     * @param  [type] $table 要操作的数据表
     * @param  [type] $check 要查询的键值
     * @param  string $pk    要查询的字段
     * @return [type]        [description]
     */
    public static function fetch_num($table,$check,$pk='*') {
        $where = self::getWhere($check);
        $sql = sprintf("SELECT count(%s) AS count FROM %s WHERE %s",$pk,$table,$where);
        $num = DB::fetch_first($sql);
        return intval($num['count']);
    }

    /**
     * 格式化参数
     * @param  [type] $v 要格式化的参数
     * @return [type]    [description]
     */
    public static function quote($v) {
        if (is_int($v) || is_float($v)) return '\'' . $v . '\'';
        if (is_string($v)) return '\'' . mysql_real_escape_string($v,self::$db) . '\'';
    }

    /**
     * 获取数据表的字段信息（但是好像没啥用）
     * @param  [type] $table 要查询的数据表
     * @return [type]        [description]
     */
    private static function getField($table) {
        if (isset(self::$field["$table"])) return self::$field["$table"];
        $result = self::query(sprintf("DESC %s",$table));
        $resArr = array();
        while($row = mysql_fetch_assoc($result)){
            $resArr[$row['Field']] = $row['Type'];
        }
        self::$field["$table"] = $resArr;
        return self::$field["$table"];
    }

    /**
     * 将 where 数组转换为 SQL 字符串
     * @param  string $check 要转换的查询键值
     * @return [type]        [description]
     */
    public static function getWhere($check='') {
        if (is_string($check)) {
            $where = $check;
        } elseif (is_array($check)) {
            $where = '';
            $_k = count($check);
            foreach ($check as $key => $value) {
                $_k--;
                $where .= '(' . trim($key,"`'\"") . '=' . self::quote($value) .')';
                if ($_k != 0) $where .= ' AND ';
            }
        }
        return $where;
    }
}
