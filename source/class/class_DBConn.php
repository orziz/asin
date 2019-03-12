<?php

/**
 * 尝试过将 DB 写成一个静态类，但是想想思路好像不对，那就重构吧
 * 依旧使用 php-mysqli
 * 决定重新研究 php-mysqli
 */
class DBConn extends mysqli
{

    protected $host;
    protected $user;
    protected $passwd;
    protected $base;
    protected $_table;
    protected $_pk;
    protected $conn = array();

    function __construct()
    {
        global $_config;
        $host = $this->host ?? $_config['dbhost']['ip'];
        $user = $this->user ?? $_config['dbhost']['user'];
        $passwd = $this->passwd ?? $_config['dbhost']['pwd'];
        $base = $this->base ?? $_config['dbhost']['base'];
        $port = $this->port ?? $_config['dbhost']['port'];

        // $baseName = implode('_',array($host,$user,$passwd,$base,$port));
        // if (isset($conn[$baseName])) return $conn[$baseName];
        // 调用 new mysqli 实例化对象
        parent::__construct($host,$user,$passwd,$base,$port);

        // 如果链接错误就直接报错
        if ($this->connect_errno) {
            Log::Error('数据库连接失败：'. $this->connect_error);
            die("连接失败: " . $this->connect_error);
        }

        // 别问，问就是 utf-8
        $this->query('set names utf8');
        $this->query('set character set utf8');
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
        Log::Sql('query:::'.$sql.';');
        $resultSet = parent::query($sql.';', $resultmode);
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
     * @param  [type] $data  要插入的数据
     * @return [type]        [description]
     */
    public function insert($data) {
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
        $sql = sprintf("INSERT INTO %s %s VALUES %s",self::table($this->_table),$k,$v);
        return $this->query($sql);
    }

    /**
     * 更新表数据
     * @param  [type] $data  要更新的数据
     * @param  [type] $check 要更新的键值
     * @return [type]        [description]
     */
    public function update($data,$check) {
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
        $where = $this->getWhere($check);
        $sql = sprintf("UPDATE %s SET %s WHERE %s",self::table($this->_table),$sql,$where);
        // Log::Sql('update:::'.$sql);
        return $this->query($sql);
    }

    /**
     * 删除表数据
     * @param  [type] $check 要操作的键值
     * @return [type]        [description]
     */
    public function delete($check) {
        $where = $this->getWhere($check);
        $sql = sprintf("DELETE FROM %s WHERE %s",self::table($this->_table),$where);
        return $this->query($sql);
    }

    /**
     * 查询表数据
     * @param  string  $check  要查询的键值
     * @param  string  $field  要查询的字段
     * @param  string  $order  要查询的排列形式
     * @param  integer $limits 要查询的数据起点
     * @param  integer $limitn 要查询的数据个数
     * @return [type]          [description]
     */
    public function fetch($check='',$field='*',$order='',$limits=0,$limitn=0) {
        $where = $this->getWhere($check);
        $sql = sprintf("SELECT %s FROM %s",$field,self::table($this->_table));
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

    /**
     * 预写数据库，防注入
     * @param  [type] $v 预写的值
     * @return [type]    [description]
     */
    public function quote($v) {
        if (is_int($v) || is_float($v)) return '\'' . $v . '\'';
        // if (is_string($v)) return '\'' . $this->real_escape_string($v) . '\'';
        if (is_string($v)) return '\'' . $v . '\'';
    }

    /**
     * 将 where 数组转换为 SQL 字符串
     * @param  string $check 要转换的查询键值
     * @return [type]        [description]
     */
    private function getWhere($check='') {
        $where = '';
        if (is_string($check)) {
            $where = $check;
        } elseif (is_array($check)) {
            $_k = count($check);
            foreach ($check as $key => $value) {
                $_k--;
                Log::Debug('？？？：：：'.$this->quote($value));
                $where .= '(' . trim($key,"`'\"") . '=' . $this->quote($value) .')';
                if ($_k != 0) $where .= ' AND ';
            }
        }
        return $where;
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

}
