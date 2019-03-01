<?php

//header('Location: http://asin.ygame.cc/aaa.html');
//
header('Content-Type: text/html; charset=utf-8');

require_once '../source/class/class_core.php';

$rankList = C::t('userscore')->getRankList($db);
$db->close();

$str = '<style>
		a { text-decoration: none; cursor: pointer; color: #0000FF; }
		a:hover { text-decoration: none; outline: none; color: #FF0000; }
		table { font-size: 2em; }
		tr { border-top: 1px solid #000000; border-left: 1px solid #000000;}
		td { border-right: 1px solid #000000; border-bottom: 1px solid #000000; }</style>
			<table style="width:80%;margin: 0 auto;">
				<tr><td width="30%">排名</td><td width="40%">姓名</td><td width="30%">积分</td></tr>';

for ($i=0; $i < count($rankList); $i++) {
	if (intval($rankList[$i]['score']) === -1) $rankList[$i]['score'] = "？？？";
	if ($i > 0 && (intval($rankList[$i]['rank']) - intval($rankList[$i-1]['rank']) > 1)) $str .= '<tr><td>...</td><td>...</td><td>...</td></tr>';
	$str .= '<tr><td>' . $rankList[$i]['rank'] . '</td><td><a href="../info/?id=' . $rankList[$i]['qq'] .'" target="_blank">'.$rankList[$i]['nickname'] . '</a></td><td>' . $rankList[$i]['score'] . '</td></tr>';
}


$str .= '</table>';

if ($_GET['pwd'] == 'zby') $str .= '<a href="../info/create.html" target="_blank">新增</a>';

echo $str;
