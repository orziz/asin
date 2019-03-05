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

}