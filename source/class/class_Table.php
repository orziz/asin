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
	public function setData($pk,array $datas=array(),$db=null) {
		if (!$db) global $db;
		if (!$pk) return false;
		$data = $this->getData($pk,$db);
		if ($data) return $this->updateData($pk,$datas,$db);
		return $this->newData($pk,$datas,$db);
	}

	/**
	 * 根据主键新建信息
	 * @param  [type] $pk    主键
	 * @param  array  $datas 信息数据
	 * @param  [type] $db    [description]
	 * @return [type]        [description]
	 */
	protected function newData($pk,array $datas,$db) {
		$datas[$this->_pk] = $pk;
		return $db->insert($this->_table,$datas);
	}

	/**
	 * 根据主键更新信息
	 * @param  [type] $pk    主键
	 * @param  array  $datas 信息数据
	 * @param  [type] $db    [description]
	 * @return [type]        [description]
	 */
	protected function updateData($pk,array $datas,$db) {
		if (isset($datas[$this->_pk])) unset($datas[$this->pk]);
		return $db->update($this->_table,$datas,array($this->_pk=>$pk));
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

	/**
	 * 获取表字段信息
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	public function getFields($db=null) {
		if (!$db) global $db;
		if (!$this->_table) return false;
		return $db->getFields($this->_table);
	}
}