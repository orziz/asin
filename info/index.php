<?php

//header('Location: http://asin.ygame.cc/aaa.html');

require_once '../source/class/class_core.php';

$qq = intval($_GET['id']);
$str = '';
//$str .= '请输入QQ号查询或昵称查询<br>';
if ($qq) {
	$info = C::t('userscore')->getUserScore($db,$qq);
	if ($info) {
		$str = '<style>
				a { text-decoration: none; cursor: pointer; color: #0000FF; }
				a:hover { text-decoration: none; outline: none; color: #FF0000; }
				table { font-size: 2em; }
				tr { border-top: 1px solid #000000; border-left: 1px solid #000000;}
				td { border-right: 1px solid #000000; border-bottom: 1px solid #000000; }</style>
				<table style="width:80%;margin: 0 auto;">';
		$rank = C::t('userscore')->getRank($db,$qq);
		$str .= '<tr><td colspan="30">姓名：' . $info['nickname'] . '</td></tr>';
		if (intval($info['score']) === -1) $info['score'] = '？？？';
		$str .= '<tr><td colspan="15">排名：' . $rank . '</td><td colspan="15">积分：' . $info['score'] . '</td></tr>';
		$info = C::t('userinfo')->getUserInfo($db,$qq);

		$info['atk'] = C::t('userinfo')->getAttrInfo($info,'atk');
		$info['def'] = C::t('userinfo')->getAttrInfo($info,'def');
		$info['bld'] = C::t('userinfo')->getAttrInfo($info,'bld');
		$info['ats'] = C::t('userinfo')->getAttrInfo($info,'ats');
		$info['spd'] = C::t('userinfo')->getAttrInfo($info,'spd');
		$info['hit'] = C::t('userinfo')->getAttrInfo($info,'hit');
		$info['dge'] = C::t('userinfo')->getAttrInfo($info,'dge');
		$info['lcy'] = C::t('userinfo')->getAttrInfo($info,'lcy');
		$info['spy'] = C::t('userinfo')->getAttrInfo($info,'spy');
		$info['lck'] = C::t('userinfo')->getAttrInfo($info,'lck');
		$info['pik'] = C::t('userinfo')->getAttrInfo($info,'pik');
		$info['sek'] = C::t('userinfo')->getAttrInfo($info,'sek');
		$info['elo'] = C::t('userinfo')->getAttrInfo($info,'elo');


		// if ($_GET['pwd'] != 'zby') {
		// 	switch ($qq) {
		// 		case 1415121913:
		// 			$info['age'] = '？？？';
		// 		case 1:
		// 		case 37:
		// 		case 117369:
		// 		case 1063614727:
		// 			$info['atk'] = '？？？';		//攻击
		// 			$info['def'] = '？？？';		//防御
		// 			$info['bld'] = '？？？';		//血量
		// 			$info['ats'] = '？？？';		//攻速
		// 			$info['spd'] = '？？？';		//移速
		// 			$info['hit'] = '？？？';		//命中
		// 			$info['dge'] = '？？？';		//闪避
		// 			$info['lcy'] = '？？？';		//幸运
		// 			$info['spy'] = '？？？';		//侦查
		// 			$info['lck'] = '？？？';		//开锁
		// 			$info['pik'] = '？？？';		//扒窃
		// 			$info['sek'] = '？？？';		//潜行
		// 			$info['elo'] = '？？？';		//口才
		// 			$info['str'] = '？？？';		//力量
		// 			$info['dex'] = '？？？';		//敏捷
		// 			$info['con'] = '？？？';		//体质
		// 			$info['ine'] = '？？？';		//智力
		// 			$info['wis'] = '？？？';		//感知
		// 			$info['cha'] = '？？？';		//魅力
		// 			break;
		// 		default:
		// 			break;
		// 	}
		// }
		$str .= '<tr><td colspan="15">性别：';
		switch ($info['sex']) {
			case '1':
				$str .= '男';
				break;
			case '2':
				$str .= '女';
				break;
			default:
				$str .= '？？？';
				break;
		}
		$str .= '</td><td colspan="15">';
		$str .= '年龄：' . $info['age'];
		$str .= '</td></tr>';
		$str .= '<tr><td colspan="15">';
		$str .= '身高：' . $info['height'] . 'cm';
		$str .= '</td><td colspan="15">';
		$str .= '体重：' . $info['weight'] . 'kg';
		$str .= '</td></tr>';

		$str .= '<tr><td colspan="10">';
		$str .= '攻击：' . $info['atk'];
		$str .= '</td><td colspan="10">';
		$str .= '防御：' . $info['def'];
		$str .= '</td><td colspan="10">';
		$str .= '血量：' . $info['bld'];
		$str .= '</td></tr>';
		$str .= '<tr><td colspan="7">';
		$str .= '攻速：' . $info['ats'];
		$str .= '</td><td colspan="8">';
		$str .= '移速：' . $info['spd'];
		$str .= '</td><td colspan="8">';
		$str .= '命中：' . $info['hit'];
		$str .= '</td><td colspan="7">';
		$str .= '闪避：' . $info['dge'];
		$str .= '</td></tr>';

		$str .= '<tr><td colspan="6">';
		$str .= '幸运：' . $info['lcy'];		//侦查
		$str .= '</td><td colspan="6">';
		$str .= '侦查：' . $info['spy'];		//开锁
		$str .= '</td><td colspan="6">';
		$str .= '巧手：' . $info['lck'];		//巧手
		$str .= '</td><td colspan="6">';
		$str .= '潜行：' . $info['sek'];		//潜行
		$str .= '</td><td colspan="6">';
		$str .= '口才：' . $info['elo'];		//口才
		$str .= '</td></tr>';

		$str .= '<tr><td colspan="30">自由属性点：' . $info['free'] . '</td></tr>';

		$str .= '<tr><td colspan="10">';
		$str .= '力量：' . $info['str'];
		$str .= '</td><td colspan="10">';
		$str .= '敏捷：' . $info['dex'];
		$str .= '</td><td colspan="10">';
		$str .= '体质：' . $info['con'];
		$str .= '</td></tr>';
		$str .= '<tr><td colspan="10">';
		$str .= '智力：' . $info['ine'];
		$str .= '</td><td colspan="10">';
		$str .= '感知：' . $info['wis'];
		$str .= '</td><td colspan="10">';
		$str .= '魅力：' . $info['cha'];
		$str .= '</td></tr>';
		$str .= '<tr><td colspan="30">';
		$str .= '武器：' . $info['arms'];
		$str .= '</td></tr>';
		// $str .= '<tr><td colspan="30">';
		// $userSkill = C::t('userskill')->getUserSkill($db,$qq);
		// $str .= '技能：1.' . $userSkill['skill1'];
		// if ($userSkill['skill1cd']) {
		// 	$skillCD = intval($userSkill['skill1cd']);
		// 	$str .= '（CD：';
		// 	if (($skillCD / 60) >= 1) $str .= ($skillCD / 60) . '分';
		// 	if (($skillCD % 60) >= 1) $str .= ($skillCD % 60) . '秒';
		// 	$str .= '）';
		// }
		// if ($userSkill['skill2']) {
		// 	$str .= '<br>&emsp;&emsp;&emsp;2.' . $userSkill['skill2'];
		// 	if ($userSkill['skill2cd']) {
		// 		$skillCD = intval($userSkill['skill2cd']);
		// 		$str .= '（CD：';
		// 		if (($skillCD / 60) >= 1) $str .= ($skillCD / 60) . '分';
		// 		if (($skillCD % 60) >= 1) $str .= ($skillCD % 60) . '秒';
		// 		$str .= '）';
		// 	}
		// }
		// if ($userSkill['skill3']) {
		// 	$str .= '<br>&emsp;&emsp;&emsp;3.' . $userSkill['skill3'];
		// 	if ($userSkill['skill3cd']) {
		// 		$skillCD = intval($userSkill['skill3cd']);
		// 		$str .= '（CD：';
		// 		if (($skillCD / 60) >= 1) $str .= ($skillCD / 60) . '分';
		// 		if (($skillCD % 60) >= 1) $str .= ($skillCD % 60) . '秒';
		// 		$str .= '）';
		// 	}
		// }
		// if ($userSkill['skill4']) {
		// 	$str .= '<br>&emsp;&emsp;&emsp;4.' . $userSkill['skill4'];
		// 	if ($userSkill['skill4cd']) {
		// 		$skillCD = intval($userSkill['skill4cd']);
		// 		$str .= '（CD：';
		// 		if (($skillCD / 60) >= 1) $str .= ($skillCD / 60) . '分';
		// 		if (($skillCD % 60) >= 1) $str .= ($skillCD % 60) . '秒';
		// 		$str .= '）';
		// 	}
		// }
		// $str .= '</td></tr>';
		$str .= '<tr><td colspan="30">';
		$str .= '介绍：' . $info['introduce'];
		$str .= '</td></tr>';
		$str .= '</table>';
	}
}

if ($_GET['pwd'] == 'zby') $str .= '<a href="change.php?id=' . $qq .'">修改</a>';

echo $str;

$db->close();
