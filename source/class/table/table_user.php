<?php

/**
 * 
 */
class table_user extends Table
{
	
	function __construct()
	{
		$this->_table = 'asin_user';
		$this->_pk = 'username';
		parent::__construct();
	}

}