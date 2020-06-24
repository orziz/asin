<?php

namespace DB;

use PHF\Request;

/**
 * 
 */
class Model extends DB
{

	function __construct($host = null, $user = null, $pwd = null, $base = null, $port = null, $charset = null)
	{
		parent::__construct($host = null, $user = null, $pwd = null, $base = null, $port = null, $charset = null);
	}

	/**
	 * 获取全部的数据
	 * @return void
	 */
	public function getAllData() {
		return $this->fetch();
	}

	/**
	 * 获取数据总数
	 *
	 * @return void
	 */
	public function getDataNum() {
		return $this->fetch('', 'count(1) as count')[0]['count'];
	}

	/**
	 * 根据主键获取信息
	 * @param  string||array $pk 主键
	 * @return [type]     [description]
	 */
	public function getData($pk) {
		if (!$pk) return false;
		if (is_array($pk)) {
			$data = $this->fetch($this->_pk.' IN ('.implode(',', $pk).')');
			return $data;
		}
		$data = $this->fetch(array($this->_pk=>$pk));
		return $data ? $data[0] : false;
	}

	/**
	 * 根据主键设置信息
	 * @param [type] $pk   主键
	 * @param array  $data 信息数据
	 */
	public function setData($pk,array $datas=array()) {
		if (!$pk) return false;
		$data = $this->getData($pk);
		if ($data) return $this->updateData($pk,$datas);
		return $this->newData($pk,$datas);
	}

	/**
	 * 根据主键新建信息
	 * @param  [type] $pk    主键
	 * @param  array  $datas 信息数据
	 * @return [type]        [description]
	 */
	protected function newData($pk,array $datas) {
		$datas[$this->_pk] = $pk;
		return $this->insert($datas);
	}

	/**
	 * 根据主键更新信息
	 * @param  [type] $pk    主键
	 * @param  array  $datas 信息数据
	 * @return [type]        [description]
	 */
	protected function updateData($pk,array $datas) {
		if (isset($datas[$this->_pk])) unset($datas[$this->_pk]);
		return $this->update($datas,array($this->_pk=>$pk));
	}

	/**
	 * 根据主键删除信息
	 * @param  string||array $pk 主键
	 * @return [type]     [description]
	 */
	public function deleteData($pk) {
		if (!$pk) return false;
		if (is_array($pk)) return $this->delete($this->_pk.' IN ('.implode(',',$pk).')');
		return $this->delete(array($this->_pk=>$pk));
	}

	/**
	 * 从请求数据中获取数据库所需要的数据
	 *
	 * @param string $k 获取的途径，支持 POST、GET、COOKIE 及其他指定key
	 * @return mixed	返回值
	 */
	public function getDBData($k = null) {
        $fields = $this->getFields();
        $data = array();
        foreach ($fields as $key => $value) {
			if ($k) {
				if ($k == 'POST') $v = Request::post($key);
				elseif ($k == 'GET') $v = Request::get($key);
				elseif ($k == 'COOKIE') $v = Request::cookie($key);
				else $v = Request::jv($k, $key);
			} else {
				$v = Request::param($key);
			}
            if ($v === null || $v === '') continue;
            $data[$key] = $v;
        }
        return $data;
    }
}