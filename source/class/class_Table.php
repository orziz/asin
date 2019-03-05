<?php

/**
 * 
 */
class Table
{
	
	protected $_table;
	protected $_pk;

	function __construct()
	{
	}

	/**
	 * 根据主键获取信息
	 * @param  [type] $pk 主键
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	public function getData($pk,$db=null) {
		if (!$db) global $db;
		if (!$pk) return false;
		if (is_array($pk)) {
			$data = $db->fetch($this->_table,$this->_pk.' IN ('.implode(',',$pk).')');
			return $data;
		}
		$data = $db->fetch($this->_table,array($this->_pk=>$pk));
		return $data ? $data[0] : false;
	}

	/**
	 * 根据主键设置信息
	 * @param [type] $pk   主键
	 * @param array  $data 信息数据
	 * @param [type] $db   [description]
	 */
	public function setData($pk,array $data=null,$db=null) {
		if (!$db) global $db;
		if (!$pk) return false;
		$data = $this->getData($pk,$db);
		if ($data) return $this->updateData($pk,$data,$db);
		return $this->newData($pk,$data,$db);
	}

	/**
	 * 根据主键新建信息
	 * @param  [type] $pk   主键
	 * @param  array  $data 信息数据
	 * @param  [type] $db   [description]
	 * @return [type]       [description]
	 */
	protected function newData($pk,array $data,$db) {
		$data[$this->pk] = $pk;
		return $db->insert($this->_table,$data);
	}

	/**
	 * 根据主键更新信息
	 * @param  [type] $pk   主键
	 * @param  array  $data 信息数据
	 * @param  [type] $db   [description]
	 * @return [type]       [description]
	 */
	protected function updateData($pk,array $data,$db) {
		if (isset($data[$this->_pk])) unset($data[$this->pk]);
		return $db->update($this->_table,$data,array($this->_pk=>$pk));
	}

	/**
	 * 根据主键删除信息
	 * @param  [type] $pk 主键
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	public function deleteData($pk,$db=null) {
		if (!$db) global $db;
		if (!$pk) return false;
		if (is_array($pk)) return $db->delete($this->_table,$this->_pk.' IN ('.implode(',',$pk).')');
		return $db->delete($this->_table,array($this->_pk=>$pk));
	}
}