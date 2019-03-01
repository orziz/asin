<?php

require_once '../source/class/class_core.php';

$qq = intval($_GET['id']);
if (!$qq) exit();
$info = C::t('userinfo')->getUserInfo($db,$qq);
$score = C::t('userscore')->getUserScore($db,$qq);
$skill = C::t('userskill')->getUserSkill($db,$qq);

$str = '<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>';
$str .= '<form id="form" name="form" action="create.php?op=change" method="post">';
$str .= 'QQ:<input type="number" id="qq" name="qq" value="' . $info['qq'] . '"><br>';
$str .= '姓名:<input type="text" id="nickname" name="nickname" value="' . $info['nickname'] . '"><br>';
$str .= '积分:<input type="number" id="score" name="score" value="' . $score['score'] . '"><br>';
$str .= '排名:<input type="number" id="rank" name="rank" value="' . $score['rank'] . '"><br>';
$str .= '性别:<input type="number" id="sex" name="sex" value="' . $info['sex'] . '"><br>';
$str .= '年龄:<input type="number" id="age" name="age" value="' . $info['age'] . '"><br>';
$str .= '身高:<input type="number" id="height" name="height" value="' . $info['height'] . '"><br>';
$str .= '体重:<input type="number" id="weight" name="weight" value="' . $info['weight'] . '"><br>';
$str .= '自由属性点:<input type="number" id="free" name="free" value="' . $info['free'] . '"><br>';
$str .= '力量:<input type="number" id="str" name="str" value="' . $info['str'] . '"><br>';
$str .= '敏捷:<input type="number" id="dex" name="dex" value="' . $info['dex'] . '"><br>';
$str .= '体质:<input type="number" id="con" name="con" value="' . $info['con'] . '"><br>';
$str .= '智力:<input type="number" id="ine" name="ine" value="' . $info['ine'] . '"><br>';
$str .= '感知:<input type="number" id="wis" name="wis" value="' . $info['wis'] . '"><br>';
$str .= '魅力:<input type="number" id="cha" name="cha" value="' . $info['cha'] . '"><br>';
$str .= '武器:<input type="text" id="arms" name="arms" value="' . $info['arms'] . '" style="width:100%;height:50px;"><br>';
// $str .= '技能1:<input type="text" id="skill1" name="skill1" value="' . $skill['skill1'] . '" style="width:100%;height:50px;"><br>';
// $str .= '技能1CD:<input type="number" id="skill1cd" name="skill1cd" value="' . (intval($skill['skill1cd']) / 60) . '" style="width:100%;height:50px;"><br>';
// $str .= '技能2:<input type="text" id="skill2" name="skill2" value="' . $skill['skill2'] . '" style="width:100%;height:50px;"><br>';
// $str .= '技能2CD:<input type="number" id="skill2cd" name="skill2cd" value="' . (intval($skill['skill2cd']) / 60) . '" style="width:100%;height:50px;"><br>';
// $str .= '技能3:<input type="text" id="skill3" name="skill3" value="' . $skill['skill3'] . '" style="width:100%;height:50px;"><br>';
// $str .= '技能3CD:<input type="number" id="skill3cd" name="skill3cd" value="' . (intval($skill['skill3cd']) / 60) . '" style="width:100%;height:50px;"><br>';
// $str .= '技能4:<input type="text" id="skill4" name="skill4" value="' . $skill['skill4'] . '" style="width:100%;height:50px;"><br>';
// $str .= '技能4CD:<input type="number" id="skill4cd" name="skill4cd" value="' . (intval($skill['skill4cd']) / 60) . '" style="width:100%;height:50px;"><br>';
$str .= '介绍:<input type="text" id="introduce" name="introduce" value="' . $info['introduce'] . '" style="width:100%;height:50px;"><br>';
$str .= '<input type="submit" id="submit" name="submit" value="提交">';
$str .= '</form>';
$str .= '<script type="text/javascript" src="../js/creatUser.js"></script>';

echo $str;

$db->close();