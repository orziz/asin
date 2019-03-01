<?php

require_once '../source/class/class_core.php';

$qq = intval($_POST['qq']);

$op = $_GET['op'];
if ($_GET['op']) {
	switch ($_GET['op']) {
		case 'checkQQ':
			$res = $db->execute("SELECT * FORM %a WHERE qq = $qq");
			if (mysql_fetch_array($res)) {
				exit('false');
			} else {
				exit('200');
			}
			break;
		case 'create':
			C::t('userscore')->newUserScore($db,$qq,$_POST['nickname'],$_POST['score'],$_POST['rank']);
			C::t('userinfo')->newUserInfo($db,$qq,$_POST['nickname'],intval($_POST['sex']),intval($_POST['age']),intval($_POST['height']),intval($_POST['weight']),intval($_POST['free']),intval($_POST['str']),intval($_POST['dex']),intval($_POST['con']),intval($_POST['ine']),intval($_POST['wis']),intval($_POST['cha']),$_POST['arms'],$_POST['introduce']);
			C::t('userSkill')->newUserSkill($db,$qq,$_POST['skill1'],$_POST['skill2'],$_POST['skill3'],$_POST['skill4']);
			C::t('userSkill')->setCD($db,$qq,$_POST['skill1cd'],$_POST['skill2cd'],$_POST['skill3cd'],$_POST['skill4cd']);
			// echo sprintf("INSERT INTO asin_userinfo (qq, nickname, sex) VALUES ($qq,'%s',intval($_POST['sex']))");
			break;
		case 'change':
			C::t('userscore')->updateUserScore($db,$qq,$_POST['nickname'],$_POST['score'],$_POST['rank']);
			C::t('userinfo')->updateUserInfo($db,$qq,$_POST['nickname'],intval($_POST['sex']),intval($_POST['age']),intval($_POST['height']),intval($_POST['weight']),intval($_POST['free']),intval($_POST['str']),intval($_POST['dex']),intval($_POST['con']),intval($_POST['ine']),intval($_POST['wis']),intval($_POST['cha']),$_POST['arms'],$_POST['introduce']);
			C::t('userSkill')->newUserSkill($db,$qq,$_POST['skill1'],$_POST['skill2'],$_POST['skill3'],$_POST['skill4']);
			C::t('userSkill')->setCD($db,$qq,$_POST['skill1cd'],$_POST['skill2cd'],$_POST['skill3cd'],$_POST['skill4cd']);
			break;
		default:
			break;
	}
}

$db->close();

header('Location: ./?id=' . $qq . '&pwd=zby');