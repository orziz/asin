<?php
namespace kjBotPlugin\GroupCheck\AsinGroup;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\TargetType;
use kjBot\Framework\DataStorage;
use \Log;

class _Meta_event extends Plugin {
    public $handleDepth = 2; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function meta_event_heartbeat($event) {
        $Queue = array();
        $Queue = array_merge($Queue,$this->asinFight($event));
        return $Queue;
    }

    private function asinFight($event) {
        $actName = '【刺客大乱斗】';
        // $groupId = '719994813';
		$groupData = DataStorage::GetData('GroupAuth.json');
        $groupData = $groupData ? json_decode($groupData,true) : array();
        $groupId = isset($groupData['asinFightGroup']) ? $groupData['asinFightGroup'] : '719994813';
        $readyTime = 5;
        $asinFightData = DataStorage::GetData('asinFightData.json');
        $asinFightData = $asinFightData ? json_decode($asinFightData,true) : array();
        // status==》0：关闭；1：开启；2：准备；3：开始；4：过程中
        if (!isset($asinFightData['status']) || $asinFightData['status'] === 0) return NULL;
        if (isset($asinFightData['status']) && $asinFightData['status'] === 1) {
            $asinFightData['status'] = 2;
            $asinFightData['readyTime'] = 0;
            $asinFightData['msgId'] = 0;
            $asinFightData['memberNum'] = 0;
            $asinFightData['data'] = array();
            $asinFightData['deadMember'] = array();
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return $event->sendTo(TargetType::Group,$groupId,"{$actName}将在 {$readyTime} 分钟后开启，请参加的刺客回复`参加刺客大乱斗`");
            // return $event->sendTo(TargetType::Private,$groupId,"{$actName}将在 {$readyTime} 分钟后开启，请参加的刺客回复`参加刺客大乱斗`");
        } elseif (isset($asinFightData['status']) && $asinFightData['status'] === 2) {
            $asinFightData['readyTime'] = isset($asinFightData['readyTime']) ? $asinFightData['readyTime'] : 0;
            $asinFightData['readyTime'] = $asinFightData['readyTime'] + 1;
            if ($asinFightData['readyTime'] >= ($readyTime*6-1)) {
                $asinFightData['status'] = 3;
            }
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return NULL;
        } elseif (isset($asinFightData['status']) && $asinFightData['status'] === 3) {
            if (!isset($asinFightData['data']) || count($asinFightData['data']) < 2) {
                $asinFightData['status'] = 0;
                DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
                return $event->sendTo(TargetType::Group,$groupId,"本次{$actName}参赛人数不足，活动取消");
            }
            $asinFightData['status'] = 4;
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return $event->sendTo(TargetType::Group,$groupId,"{$actName}开始，本次参与人数为 ".$asinFightData['memberNum'].' ，祝你好运');
        } elseif (isset($asinFightData['status']) && $asinFightData['status'] === 4) {
            // 开始判定事件
            $asinFightData['msgId'] = isset($asinFightData['msgId']) ? $asinFightData['msgId'] : 0;
            $asinFightData['msgId'] = $asinFightData['msgId'] + 1;
            if (count($asinFightData['data']) <= 1) {
                // 如果只剩一个人了，就直接胜利
                $asinFightData['status'] = 0;
                DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
                $member = array_keys($asinFightData['data']);
                $user = $member[0];
                // $msg .= "\n\n本次活动排名：\n1.\t\t". CQCode::At($user);
                $deadObj = array_reverse($asinFightData['deadMember']);
                $deadMember = array_keys($asinFightData['deadMember']);
                $deadMember = array_reverse($deadMember);

                $score = $asinFightData['memberNum'] * 1;
                $credit = $asinFightData['memberNum'] * 200;
                $free = 5+floor($asinFightData['memberNum']/10);

                $score2 = floor($score * 0.8);
                $credit2 = floor($credit * 0.8);
                $free2 = 3+floor($asinFightData['memberNum']/10);

                $score3 = floor($score * 0.5);
                $credit3 = floor($credit * 0.5);
                $free3 = 1+floor($asinFightData['memberNum']/10);

                param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userscore', 'action'=>'add', 'qq'=>$user, 'score'=>$score,'credit'=>$credit));
                param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userattr', 'action'=>'addUserAttr', 'qq'=>$user, 'free'=>$free));
                $msg = "本次{$actName}活动结束，胜利者为 [". $asinFightData['data'][$user]['groupId'] . ']' .$asinFightData['data'][$user]['nickName']."\n第一名奖励：".$score.' 积分，'.$credit.' 暗币，'.$free.' 自由属性点';
                // for ($i=0; $i < count($asinFightData['deadMember']); $i++) { 
                //     $msg .= "\n".($i+2).".\t\t".CQCode::At($asinFightData['deadMember'][$i]);
                // }
                // 超 5 人参加给第二名发奖励
                if ($asinFightData['memberNum'] >= 5) {
                    param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userscore', 'action'=>'add', 'qq'=>$deadMember[0], 'score'=>$score2,'credit'=>$credit2));
                    param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userattr', 'action'=>'addUserAttr', 'qq'=>$deadMember[0], 'free'=>$free2));
                    $msg .= "\n第二名奖励：".$score2.' 积分，'.$credit2.' 暗币，'.$free2.' 自由属性点';
                }
                // 超 10 人参加给第三名发奖励
                if ($asinFightData['memberNum'] >= 10) {
                    param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userscore', 'action'=>'add', 'qq'=>$deadMember[1], 'score'=>$score3,'credit'=>$credit3));
                    param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userattr', 'action'=>'addUserAttr', 'qq'=>$deadMember[1], 'free'=>$free3));
                    $msg .= "\n第三名奖励：".$score3.' 积分，'.$credit3.' 暗币，'.$free3.' 自由属性点';
                }

                $msg .= "\n\n本次活动排名：\n1.\t\t[". $asinFightData['data'][$user]['groupId'] . ']' .$asinFightData['data'][$user]['nickName'].'('.$asinFightData['data'][$user]['bld'].')';
                $i = 0;
                foreach ($deadObj as $key => $value) {
                    // $msg .= "\n".($i+2).".\t\t".$value['groupId'].'-'.CQCode::At($deadMember[$key]);
                    $msg .= "\n".($i+2).".\t\t[".$value['groupId'].']'.$value['nickName'];
                    $i++;
                }
                $msgData = "ajax=1&fromBot=1&fid=3&subject=【刺客大乱斗】排名结算_".getTime('Y-m-d_H:i:s')."&doctype=2&message=".implode("\n\n",explode('\n',$msg));
                request_post('https://567.pohun.com/?thread-create.htm',$msgData);
                return $event->sendTo(TargetType::Group,$groupId,$msg); 
            }
            // 从参赛人员中随机获取两名成员
            $fightMember = array_rand($asinFightData['data'],2);
            // 指定user1的id
            $user1 = $fightMember[0];
            // 指定user2的id
            $user2 = $fightMember[1];
            // 随机两人感知，高的为攻击者
            $wis1 = mt_rand(0, $asinFightData['data'][$user1]['rat']);
            $wis2 = mt_rand(0, $asinFightData['data'][$user2]['rat']);
            if ($wis1 > $wis2) {
                // 用户2触发事件
                $atkUser = $user1;
                $hurtUser = $user2;
            } else {
                // 用户1触发事件
                $atkUser = $user2;
                $hurtUser = $user1;
            }
            // 获取攻击者数据
            $atkUserData = $asinFightData['data'][$atkUser];
            // 获取受击者数据
            $hurtUserData = $asinFightData['data'][$hurtUser];
            // at攻击者
            $callAtkUser = CQCode::At($atkUser);
            // $callAtkUser = '['.$atkUserData['groupId'].']'.$atkUserData['nickName'];
            // at受击者
            $callHurtUser = CQCode::At($hurtUser);
            // $callHurtUser = '['.$hurtUserData['groupId'].']'.$hurtUserData['nickName'];
            // 攻击者当前血量
            $atkUserBld = $atkUserData['bld'];
            // 受击者当前血量
            $hurtUserBld = $hurtUserData['bld'];
            // at攻击者（携带当前血量）
            // $callAtkUserWithBld = $callAtkUser.'（'.$atkUserBld.'）';
            $callAtkUserWithBld = $callAtkUser.'（'.$atkUserBld.'）';
            // at受击者（携带当前血量）
            // $callHurtUserWithBld = $callHurtUser.'（'.$hurtUserBld.'）';
            $callHurtUserWithBld = $callHurtUser.'（'.$hurtUserBld.'）';
            // 初始化伤害值
            $hurt = 0;
            // 初始化回复值
            $addBld = 0;
            // 初始化暴击
            $isCrit = false;
            // 初始化消息
            $msg = $actName.$asinFightData['msgId'].'. ';
            // 随机触发双人事件或者单人事件
            if (mt_rand(1,10000) <= 5000) {
                // 触发单人事件
                if (mt_rand(0,$hurtUserData['ine']) > 60) {
                    // 触发加血事件
                    $addBld = mt_rand(0,$hurtUserData['maxBld']-$hurtUserBld);
                    // $isCrit = mt_rand(0,$hurtUserData['crit']) > 50;
                    $isCrit = mt_rand(0, 100) <= $hurtUserData['crit'];
                    if ($isCrit) $addBld = min($hurtUserData['maxBld']-$hurtUserBld,$addBld*2);
                    $eventList = [
                        "{$callHurtUserWithBld} 感知到一股洪荒之力，回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 觉得自己应该回复一下血量了，所以回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 看四下没人，偷偷回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 回想起《刺客伍六七》，仿佛受到了什么感悟，回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 大喊了一声“子不语牛逼”，突然一道圣光从天而降，{$callHurtUserWithBld} 回复了 {$addBld} 点血量",

                        "{$callHurtUserWithBld} 绕到了 {$callAtkUserWithBld} 身后，温柔的摸了一下 {$callAtkUserWithBld} ，{$callHurtUserWithBld} 回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 混进了婚礼现场骗吃骗喝，{$callHurtUserWithBld} 回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 发现了一只蒜头王八，尝试捕捉，被蒜头爸爸用藤编抽打了一顿，抖M的 {$callHurtUserWithBld} 恢复了 {$addBld} 点血量"
                    ];
                } else  {
                    // $hurt = min($hurtUserData['bld'],mt_rand(1,40));
                    // $hurt = min($hurtUserData['bld'],mt_rand(1,$hurtUserData['bld']));
                    $hurt = min($hurtUserData['bld'],mt_rand(1,50));
                    $isCrit = mt_rand(1,10000) > 9500;
                    if ($isCrit) $hurt = min($hurtUserData['bld'],$hurt*2);
                    $eventList = [
                        "{$callHurtUserWithBld} 误入汪星人基地，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 看到一对情侣秀恩爱，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 偷看妇女主任洗澡，被妇女主任发现并叫人围殴，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 向伍六七坦白喜欢梅花十三，结果被伍六七胖揍了一顿，受到 {$hurt} 点伤害",

                        "{$callHurtUserWithBld} 尝试撸梅尔的尾巴，被梅尔发现，直接被梅尔用尾巴扫飞，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 掉进下水道，遇到了承太郎，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 与赌神阿发撞上了，对方的赌技秀了 {$callHurtUserWithBld} 一脸，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 在想去吃饭的时候点了一碗手打牛丸，被守打刘丸暴打一顿，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 在路上碰到了春风一郎，在与他的对决中被他那看不到拔刀的“刀”击伤，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 在上厕所的时候遇到了一名普通打扮的刺客，在 {$callHurtUserWithBld} 一顿嘴炮后，{$callHurtUserWithBld} 和对方同时伤感起来，受到了 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 碰到准备召唤克总的屑教徒，被揍了一顿，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 在乱跑的过程中不小心踩到了愉悦犯安的地雷，受到 {$hurt} 点伤害",
                        // "{$callHurtUserWithBld} 使用了金疮药，导致痔疮爆裂，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 想扶老奶奶过马路，被误认为想要非礼，被路人见义勇为打了一顿，受到 {$hurt} 点伤害",
                        // "{$callHurtUserWithBld} 为了生存参加了抬棺，却因技术不熟练导致棺材翻了，被家属暴打一顿，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 在河边撒尿，惊扰了鱼人，被赏了一脚，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 避让过压路机之后，被一辆无人驾驶的轮椅疾驰而过，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 跑团时当了鸽子，被其他人拿去煲汤，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 在平地上散步，突然来了一个平地摔，受到 {$hurt} 点伤害"
                    ];
                }
            } else {
                // 触发双人事件
                $hurt = min($hurtUserData['bld'],mt_rand(0,$atkUserData['atk']));
                // $isCrit = mt_rand(0,$atkUserData['crit']) > 50;
                $isCrit = mt_rand(0, 100) <= $atkUserData['crit'];
                if ($isCrit) $hurt = min($hurtUserData['bld'],$hurt*2);
                $eventList = [
                    "{$callAtkUserWithBld} 绕到 {$callHurtUserWithBld} 身后，给予沉重一击，造成 {$hurt} 点伤害",
                    "{$callHurtUserWithBld} 试图偷袭 {$callAtkUserWithBld} ，被 {$callAtkUserWithBld} 发现，受到 {$hurt} 点伤害",
                    "{$callAtkUserWithBld} 对 {$callHurtUserWithBld} 使用【千年杀】，造成 {$hurt} 点伤害",
                    "{$callHurtUserWithBld} 在赶路的时候被 {$callAtkUserWithBld} 发现，{$callAtkUserWithBld} 使用飞镖将 {$callHurtUserWithBld} 击落，造成 {$hurt} 点伤害",
                    "{$callAtkUserWithBld} 偷袭 {$callHurtUserWithBld} 成功，造成 {$hurt} 点伤害",

                    "{$callHurtUserWithBld} 溜到了 {$callAtkUserWithBld} 背后，想要背刺，却被碾压而过的河蟹撞飞，受到 {$hurt} 点伤害",
                    // "{$callHurtUserWithBld} 尝试背刺 {$callAtkUserWithBld} ，被 {$callAtkUserWithBld} 发现后用挺起的胸大肌反弹了伤害，受到了 {$hurt} 点伤害"
                ];
            }
            $msg .= $eventList[mt_rand(0,count($eventList)-1)];
            if ($isCrit) $msg .= "（暴击！！！）";
            // 修改受击者血量
            $asinFightData['data'][$hurtUser]['bld'] = $hurtUserData['bld'] - $hurt + $addBld;
            // 判断是否死亡
            if ($asinFightData['data'][$hurtUser]['bld'] <= 0) {
                $msg .= "\n".$callHurtUser." 重伤淘汰，本次{$actName}排名为：".count($asinFightData['data']);
                if (!isset($asinFightData['deadMember'])) $asinFightData['deadMember'] = array();
                $asinFightData['deadMember'][$hurtUser] = $asinFightData['data'][$hurtUser];
                unset($asinFightData['data'][$hurtUser]);
            }
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return $event->sendTo(TargetType::Group,$groupId,$msg);
        }
        return NULL;
    }

}