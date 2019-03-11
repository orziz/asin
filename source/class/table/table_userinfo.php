<?php

/**
 * 
 */
class table_userinfo extends Table
{
	
	public function __construct() {

		$this->_table = 'asin_userinfo';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	protected function newData($pk,array $datas) {
		$time = getTime();
		$datas['ctime'] = $time;
		$datas['utime'] = $time;
		return parent::newData($pk,$datas);
	}

	protected function updateData($pk,array $datas) {
		$time = getTime();
		$datas['utime'] = $time;
		return parent::updateData($pk,$datas);
	}

}