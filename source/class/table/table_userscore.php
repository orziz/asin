<?php

/**
 * 
 */
class table_userscore extends Table
{
	
	public function __construct() {

		$this->_table = 'asin_userscore';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	/**
	 * 新增数据
	 * @param  [type] $pk    [description]
	 * @param  array  $datas [description]
	 * @return [type]        [description]
	 */
	protected function newData($pk,array $datas) {
		$score = (int)$datas['score'];
		$datas['scorerank'] = $score*10000000000+(10000000000-time());
		if (isset($datas['rank']) && $datas['rank']) $datas['scorerank'] = (100000000-intval($datas['rank']))*10000000000+(10000000000-time());
		return parent::newData($pk,$datas);
	}

	/**
	 * 更新数据
	 * @param  [type] $pk    [description]
	 * @param  array  $datas [description]
	 * @return [type]        [description]
	 */
	protected function updateData($pk,array $datas) {
		if (isset($datas['score'])) {
			$score = (int)$datas['score'];
			$datas['scorerank'] = $score*10000000000+(10000000000-time());
		}
		if (isset($datas['rank']) && $datas['rank']) $datas['scorerank'] = (100000000-intval($datas['rank']))*10000000000+(10000000000-time());
		return parent::updateData($pk,$datas);
	}

	/**
	 * 根据 qq 获取排名
	 * @param  [type] $qq [description]
	 * @return [type]     [description]
	 */
	public function getRank($qq) {
		if (!$qq) return false;
		$data = $this->getData($qq);
		if (!$data) return false;
		if ($data['rank']) return $data['rank'];
		$rank = $this->fetch('scorerank > '.$data['scorerank'],'count(qq) AS count');
		$rank = $rank[0]['count'];
		$rank = (int)$rank + 1;
		$rank = $rank + 1001;
		return $rank;
	}

	/**
	 * 获取排行榜列表
	 * @param  [type] $limit 查询的数量
	 * @return [type]        [description]
	 */
	public function getRankList($limit=0) {
		if ($limit) $scoreArr = $this->fetch('','*','scorerank DESC',0,$limit);
		else $scoreArr = $this->fetch();
		$rankList = [];
		for ($i = 0; $i < count($scoreArr); $i++) {
			$data = $scoreArr[$i];
			$userInfo = C::t('userinfo')->getData($data['qq']);
			$data['nickname'] = $userInfo['nickname'];
			$data['rank'] = $this->getRank($data['qq'],$this);
			array_push($rankList, $data);
		}
		array_multisort(array_column($rankList,'rank'),SORT_ASC,$rankList);
		return $rankList;
	}

}