<?php

/**
 * 
 */
class table_userattr extends Table
{
	
	public function __construct()
	{
		$this->_table = 'asin_userattr';
		$this->_pk = 'qq';

		parent::__construct();
	}

	public function addAttr($pk,array $datas) {
		$userAttr = $this->getData($pk);
		if (!$userAttr) return;
		$free = $userAttr['free'];
		if (isset($datas[$this->_pk])) unset($datas[$this->_pk]);
		foreach ($datas as $key => $value) {
			if ($key != 'free') $free -= $value;
			Log::Debug('===>key：'.$key.' value：'.$value.' free：'.$free);
			if ($free < 0) return 301;
			$datas[$key] = max(0,$userAttr[$key]+$value);
			$datas['free'] = ($key == 'free') ? $datas[$key] : $free;
		}
		return $this->setData($pk,$datas);
	}

}