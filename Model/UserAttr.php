<?php

/**
 * 数据库操作（示例）
 * 先 new 再操作
 */

namespace Model;

use \DB\Model;

class UserAttr extends Model {

    public function __construct()
    {
        // 设置表名
        $this->_table = "asin_userattr";

        // 设置主键名
        $this->_pk = "qq";

        parent::__construct();
    }

	/**
	 * 增加角色属性
	 *
	 * @param mixed $pk
	 * @param array $datas
	 * @return void
	 */
	public function addAttr($pk,array $datas) {
		$userAttr = $this->getData($pk);
		if (!$userAttr) return;
		$free = $userAttr['free'];
		if (isset($datas[$this->_pk])) unset($datas[$this->_pk]);
		foreach ($datas as $key => $value) {
			if ($key != 'free') $free -= $value;
			if ($free < 0) return 301;
			$datas[$key] = max(0,$userAttr[$key]+$value);
			$datas['free'] = ($key == 'free') ? $datas[$key] : $free;
		}
		return $this->setData($pk,$datas);
	}
 
}