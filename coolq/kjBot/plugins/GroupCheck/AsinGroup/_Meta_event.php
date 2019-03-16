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
        $Queue[] = $this->asinFight($event);
        return $Queue;
    }

    private function asinFight($event) {
        $actName = '【刺客大乱斗】';
        // $groupId = '719994813';
        $groupId = '758507034';
        // $groupId = '805348195';
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
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return $event->sendTo(TargetType::Group,$groupId,"{$actName}将在 {$readyTime} 分钟后开启，请参加的刺客回复`参加刺客大乱斗`");
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
            $asinFightData['msgId'] = isset($asinFightData['msgId']) ? $asinFightData['msgId'] : 0;
            $asinFightData['msgId'] = $asinFightData['msgId'] + 1;
            if (count($asinFightData['data']) <= 1) {
                $asinFightData['status'] = 0;
                DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
                $member = array_keys($asinFightData['data']);
                $user = $member[0];
                $score = $asinFightData['memberNum'] * 2;
                $credit = $asinFightData['memberNum'] * 500;
                // $data = param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userscore', 'action'=>'add', 'qq'=>$user, 'score'=>$score,'credit'=>$credit));
                return $event->sendTo(TargetType::Group,$groupId,"本次{$actName}活动结束，胜利者为 ".CQCode::At($user)."\n获得奖励：".$score.' 积分，'.$credit.' 暗币'); 
            }
            // 从参赛人员中随机获取两名成员
            $fightMember = array_rand($asinFightData['data'],2);
            // 指定user1的id
            $user1 = $fightMember[0];
            // 指定user2的id
            $user2 = $fightMember[1];
            // 随机谁挨打
            $rand = mt_rand(1,100);
            if ($rand <= 50) {
                $atkUser = $user1;
                $hurtUser = $user2;
            } else {
                $atkUser = $user2;
                $hurtUser = $user1;
            }
            $hurt = min($asinFightData['data'][$hurtUser]['bld'],mt_rand(1,50));
            // at攻击者
            $callAtkUser = CQCode::At($atkUser);
            // at受击者
            $callHurtUser = CQCode::At($hurtUser);
            // 攻击者剩余血量
            $atkUserBld = $asinFightData['data'][$atkUser]['bld'];
            // 受击者剩余血量
            $hurtUserBld = $asinFightData['data'][$hurtUser]['bld'];
            // at攻击者（携带剩余血量）
            $callAtkUserWithBld = $callAtkUser.'（'.$atkUserBld.'）';
            // at受击者（携带剩余血量）
            $callHurtUserWithBld = $callHurtUser.'（'.$hurtUserBld.'）';
            $eventList = [
                "{$callAtkUserWithBld} 绕到 {$callHurtUserWithBld} 身后，给予沉重一击，造成 {$hurt} 点伤害",
                "{$callHurtUserWithBld} 误入汪星人基地，受到 {$hurt} 点伤害",
                "{$callHurtUserWithBld} 试图偷袭 {$callAtkUserWithBld} ，被 {$callAtkUserWithBld} 发现，受到 {$hurt} 点伤害",
                "{$callHurtUserWithBld} 看到一对情侣秀恩爱，受到 {$hurt} 点伤害",
                "{$callHurtUserWithBld} 偷看妇女主任洗澡，被妇女主任发现并叫人围殴，受到 {$hurt} 点伤害",
                "{$callAtkUserWithBld} 对 {$callHurtUserWithBld} 使用【千年杀】，造成 {$hurt} 点伤害"
            ];
            $msg = $asinFightData['msgId'].'. '.$eventList[mt_rand(0,count($eventList)-1)];
            // 修改受击者血量
            $asinFightData['data'][$hurtUser]['bld'] = $asinFightData['data'][$hurtUser]['bld'] - $hurt;
            // 判断是否死亡
            if ($asinFightData['data'][$hurtUser]['bld'] <= 0) {
                $msg .= "\n".CQCode::At($hurtUser)." 重伤淘汰，本次{$actName}排名为：".count($asinFightData['data']);
                unset($asinFightData['data'][$hurtUser]);
            }
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return $event->sendTo(TargetType::Group,$groupId,$msg);
        }
        return NULL;
    }

}