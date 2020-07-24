<?php

namespace kjBotModule\Ninepm;
use kjBotModule\Ninepm\Data;

class Brand {

    private static $s = [
        "1" => ['text'=>'黑桃A','value'=>1],
        "2" => ['text'=>'黑桃2','value'=>2],
        "3" => ['text'=>'黑桃3','value'=>3],
        "4" => ['text'=>'黑桃4','value'=>4],
        "5" => ['text'=>'黑桃5','value'=>5],
        "6" => ['text'=>'黑桃6','value'=>6],
        "7" => ['text'=>'黑桃7','value'=>7],
        "8" => ['text'=>'黑桃8','value'=>8],
        "9" => ['text'=>'黑桃9','value'=>9],
        "10" => ['text'=>'黑桃10','value'=>10],
        "11" => ['text'=>'黑桃J','value'=>11],
        "12" => ['text'=>'黑桃Q','value'=>11],
        "13" => ['text'=>'黑桃K','value'=>11],
        "14" => ['text'=>'红桃A','value'=>1],
        "15" => ['text'=>'红桃2','value'=>2],
        "16" => ['text'=>'红桃3','value'=>3],
        "17" => ['text'=>'红桃4','value'=>4],
        "18" => ['text'=>'红桃5','value'=>5],
        "19" => ['text'=>'红桃6','value'=>6],
        "20" => ['text'=>'红桃7','value'=>7],
        "21" => ['text'=>'红桃8','value'=>8],
        "22" => ['text'=>'红桃9','value'=>9],
        "23" => ['text'=>'红桃10','value'=>10],
        "24" => ['text'=>'红桃J','value'=>11],
        "25" => ['text'=>'红桃Q','value'=>11],
        "26" => ['text'=>'红桃K','value'=>11],
        "27" => ['text'=>'梅花A','value'=>1],
        "28" => ['text'=>'梅花2','value'=>2],
        "29" => ['text'=>'梅花3','value'=>3],
        "30" => ['text'=>'梅花4','value'=>4],
        "31" => ['text'=>'梅花5','value'=>5],
        "32" => ['text'=>'梅花6','value'=>6],
        "33" => ['text'=>'梅花7','value'=>7],
        "34" => ['text'=>'梅花8','value'=>8],
        "35" => ['text'=>'梅花9','value'=>9],
        "36" => ['text'=>'梅花10','value'=>10],
        "37" => ['text'=>'梅花J','value'=>11],
        "38" => ['text'=>'梅花Q','value'=>11],
        "39" => ['text'=>'梅花K','value'=>11],
        "40" => ['text'=>'方块A','value'=>1],
        "41" => ['text'=>'方块2','value'=>2],
        "42" => ['text'=>'方块3','value'=>3],
        "43" => ['text'=>'方块4','value'=>4],
        "44" => ['text'=>'方块5','value'=>5],
        "45" => ['text'=>'方块6','value'=>6],
        "46" => ['text'=>'方块7','value'=>7],
        "47" => ['text'=>'方块8','value'=>8],
        "48" => ['text'=>'方块9','value'=>9],
        "49" => ['text'=>'方块10','value'=>10],
        "50" => ['text'=>'方块J','value'=>11],
        "51" => ['text'=>'方块Q','value'=>11],
        "52" => ['text'=>'方块K','value'=>11]
    ];

    public static function getBrand() {
        return self::$s;
    }

    public static function id2text($id) {
        return isset(self::getBrand()[$id]) ? self::getBrand()[$id]['text'] : false;
    }

    public static function id2value($id) {
        return isset(self::getBrand()[$id]) ? self::getBrand()[$id]['value'] : false;
    }

    public static function getOneBrand() {
        $brands = Data::getDataByKey('brands', self::getBrand());
        $scrad = array_rand($brands, 1);
        unset($brands["$scrad"]);
        return $scrad;
    }

    public static function getCuserBrand() {
        $cuser = Data::getDataByKey('cuser', array());
        if (!isset($cuser['brands'])) $cuser['brands'] = array();
        return $cuser['brands'];
    }

    public static function setCuserBrand($brands) {
        $cuser = Data::getDataByKey('cuser', array());
        $cuser['brands'] = $brands;
        Data::setDataByKey('cuser', $cuser);
    }

    /** 给玩家发牌 */
    public static function sendBrand() {
        $cb = self::getCuserBrand();
        $b = self::getOneBrand();
        $cb["$b"] = self::getBrand()["$b"];
        self::setCuserBrand($cb);
        return $b;
    }

    /** 更换玩家 */
    public static function changeUser() {
        $cuser = Data::getDataByKey('cuser');
        $users = Data::getDataByKey('users');
        if (count($users)>0) {
            $user = array_shift($users);
            Data::setDataByKey('users', $users);
            $ausers = Data::getDataByKey('ausers', array());
            $ausers[$cuser['user']] = $cuser['brands'];
            Data::setDataByKey('ausers', $ausers);
            Data::setDataByKey('cuser', array('user'=>$user));
            return $user;
        }
        return false;
    }

    /** 获取玩家卡牌 */
    public static function getUserBrand($id) {
        $ausers = Data::getDataByKey('ausers', array());
        if (!isset($ausers[$id])) $ausers[$id] = array();
        return $ausers[$id];
    }

    public static function setUserBrand($id, $bid) {
        $ausers = Data::getDataByKey('ausers', array());
        if (!isset($ausers[$id])) $ausers[$id] = array();
        array_push($ausers[$id], $bid);
        Data::setDataByKey('ausers', $ausers);
    }

}