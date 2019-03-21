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
        $groupId = isset($groupData['asinFightGroup']) ? $groupData['asinFightGroup'] : '758507034';
        $readyTime = 10;
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
                $score = $asinFightData['memberNum'] * 1;
                $credit = $asinFightData['memberNum'] * 200;
                $free = 2;
                param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userscore', 'action'=>'add', 'qq'=>$user, 'score'=>$score,'credit'=>$credit));
                param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userattr', 'action'=>'addUserAttr', 'qq'=>$user, 'free'=>$free));
                $msg = "本次{$actName}活动结束，胜利者为 ".CQCode::At($user)."\n获得奖励：".$score.' 积分，'.$credit.' 暗币，'.$free.' 自由属性点';
                $msg .= "\n\n本次活动排名：\n1.\t\t". CQCode::At($user);
                for ($i=0; $i < count($asinFightData['deadMember']); $i++) { 
                    $msg .= "\n".($i+2).".\t\t".CQCode::At($asinFightData['deadMember'][$i]);
                }
                return $event->sendTo(TargetType::Group,$groupId,$msg); 
            }
            // 从参赛人员中随机获取两名成员
            $fightMember = array_rand($asinFightData['data'],2);
            // 指定user1的id
            $user1 = $fightMember[0];
            // 指定user2的id
            $user2 = $fightMember[1];
            // 随机谁触发事件
            if (mt_rand(1,10000) <= 5000) {
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
            // at受击者
            $callHurtUser = CQCode::At($hurtUser);
            // 攻击者当前血量
            $atkUserBld = $atkUserData['bld'];
            // 受击者当前血量
            $hurtUserBld = $hurtUserData['bld'];
            // at攻击者（携带当前血量）
            $callAtkUserWithBld = $callAtkUser.'（'.$atkUserBld.'）';
            // at受击者（携带当前血量）
            $callHurtUserWithBld = $callHurtUser.'（'.$hurtUserBld.'）';
            // 初始化伤害值
            $hurt = 0;
            // 初始化回复值
            $addBld = 0;
            $isCrit = false;
            // 初始化消息
            $msg = $actName.$asinFightData['msgId'].'. ';
            // 随机触发双人事件或者单人事件
            if (mt_rand(1,10000) <= 5000) {
                // 触发单人事件
                if (mt_rand(0,$hurtUserData['ine']) > 60) {
                    // 触发加血事件
                    $addBld = mt_rand(0,$hurtUserData['maxBld']-$hurtUserBld);
                    $isCrit = mt_rand(0,$atkUserData['crit']) > 50;
                    if ($isCrit) $addBld = min($hurtUserData['maxBld'],$addBld*2);
                    $eventList = [
                        "{$callHurtUserWithBld} 感知到一股洪荒之力，回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 觉得自己应该回复一下血量了，所以回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 看四下没人，偷偷回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 回想起《刺客伍六七》，仿佛受到了什么感悟，回复了 {$addBld} 点血量",
                        "{$callHurtUserWithBld} 大喊了一声“子不语牛逼”，突然一道圣光从天而降，{$callHurtUserWithBld} 回复了 {$addBld} 点血量"
                    ];
                } else  {
                    // $hurt = min($hurtUserData['bld'],mt_rand(1,40));
                    $hurt = min($hurtUserData['bld'],mt_rand(1,$hurtUserData['bld']));
                    $isCrit = mt_rand(1,10000) > 9500;
                    if ($isCrit) $hurt = min($hurtUserData['bld'],$hurt*2);
                    $eventList = [
                        "{$callHurtUserWithBld} 误入汪星人基地，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 看到一对情侣秀恩爱，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 偷看妇女主任洗澡，被妇女主任发现并叫人围殴，受到 {$hurt} 点伤害",
                        "{$callHurtUserWithBld} 向伍六七坦白喜欢梅花十三，结果被伍六七胖揍了一顿，受到 {$hurt} 点伤害"
                    ];
                }
            } else {
                // 触发双人事件
                $hurt = min($hurtUserData['bld'],mt_rand(0,$atkUserData['atk']));
                $isCrit = mt_rand(0,$atkUserData['crit']) > 50;
                if ($isCrit) $hurt = min($hurtUserData['bld'],$hurt*2);
                $eventList = [
                    "{$callAtkUserWithBld} 绕到 {$callHurtUserWithBld} 身后，给予沉重一击，造成 {$hurt} 点伤害",
                    "{$callHurtUserWithBld} 试图偷袭 {$callAtkUserWithBld} ，被 {$callAtkUserWithBld} 发现，受到 {$hurt} 点伤害",
                    "{$callAtkUserWithBld} 对 {$callHurtUserWithBld} 使用【千年杀】，造成 {$hurt} 点伤害",
                    "{$callHurtUserWithBld} 在赶路的时候被 {$callAtkUserWithBld} 发现，{$callAtkUserWithBld} 使用飞镖将 {$callHurtUserWithBld} 击落，造成 {$hurt} 点伤害",
                    "{$callAtkUserWithBld} 偷袭 {$callHurtUserWithBld} 成功，造成 {$hurt} 点伤害"
                ];
            }
            $msg .= $eventList[mt_rand(0,count($eventList)-1)];
            if ($isCrit) $msg .= "（暴击！！！）";
            // 修改受击者血量
            $asinFightData['data'][$hurtUser]['bld'] = $hurtUserData['bld'] - $hurt + $addBld;
            // 判断是否死亡
            if ($asinFightData['data'][$hurtUser]['bld'] <= 0) {
                $msg .= "\n".CQCode::At($hurtUser)." 重伤淘汰，本次{$actName}排名为：".count($asinFightData['data']);
                unset($asinFightData['data'][$hurtUser]);
                if (!isset($asinFightData['deadMember'])) $asinFightData['deadMember'] = array();
                array_unshift($asinFightData['deadMember'],$hurtUser);
            }
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return $event->sendTo(TargetType::Group,$groupId,$msg);
        }
        return NULL;
    }

}