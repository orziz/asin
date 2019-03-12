<?php

/**
 * 
 */
class Table extends DBConn
{

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 根据主键获取信息
	 * @param  [type] $pk 主键
	 * @return [type]     [description]
	 */
	public function getData($pk) {
		if (!$pk) return false;
		if (is_array($pk)) {
			$data = $this->fetch($this->_pk.' IN ('.implode(',',$pk).')');
			return $data;
		}
		Log::Debug('username==>'.$pk);
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
		if (isset($datas[$this->_pk])) unset($datas[$this->pk]);
		return $this->update($datas,array($this->_pk=>$pk));
	}

	/**
	 * 根据主键删除信息
	 * @param  [type] $pk 主键
	 * @return [type]     [description]
	 */
	public function deleteData($pk) {
		if (!$pk) return false;
		if (is_array($pk)) return $this->delete($this->_pk.' IN ('.implode(',',$pk).')');
		return $this->delete(array($this->_pk=>$pk));
	}
}