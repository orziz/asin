<?php

namespace kjBotModule\Ninepm;
use kjBotModule\Ninepm\Data;

class Brand {

    private static $s = [
        "1" => ['text'=>'A','value'=>1],
        "2" => ['text'=>'2','value'=>2],
        "3" => ['text'=>'3','value'=>3],
        "4" => ['text'=>'4','value'=>4],
        "5" => ['text'=>'5','value'=>5],
        "6" => ['text'=>'6','value'=>6],
        "7" => ['text'=>'7','value'=>7],
        "8" => ['text'=>'8','value'=>8],
        "9" => ['text'=>'9','value'=>9],
        "10" => ['text'=>'10','value'=>10],
        "11" => ['text'=>'J','value'=>10],
        "12" => ['text'=>'Q','value'=>10],
        "13" => ['text'=>'K','value'=>10],
        "14" => ['text'=>'A','value'=>1],
        "15" => ['text'=>'2','value'=>2],
        "16" => ['text'=>'3','value'=>3],
        "17" => ['text'=>'4','value'=>4],
        "18" => ['text'=>'5','value'=>5],
        "19" => ['text'=>'6','value'=>6],
        "20" => ['text'=>'7','value'=>7],
        "21" => ['text'=>'8','value'=>8],
        "22" => ['text'=>'9','value'=>9],
        "23" => ['text'=>'10','value'=>10],
        "24" => ['text'=>'J','value'=>10],
        "25" => ['text'=>'Q','value'=>10],
        "26" => ['text'=>'K','value'=>10],
        "27" => ['text'=>'A','value'=>1],
        "28" => ['text'=>'2','value'=>2],
        "29" => ['text'=>'3','value'=>3],
        "30" => ['text'=>'4','value'=>4],
        "31" => ['text'=>'5','value'=>5],
        "32" => ['text'=>'6','value'=>6],
        "33" => ['text'=>'7','value'=>7],
        "34" => ['text'=>'8','value'=>8],
        "35" => ['text'=>'9','value'=>9],
        "36" => ['text'=>'10','value'=>10],
        "37" => ['text'=>'J','value'=>10],
        "38" => ['text'=>'Q','value'=>10],
        "39" => ['text'=>'K','value'=>10],
        "40" => ['text'=>'A','value'=>1],
        "41" => ['text'=>'2','value'=>2],
        "42" => ['text'=>'3','value'=>3],
        "43" => ['text'=>'4','value'=>4],
        "44" => ['text'=>'5','value'=>5],
        "45" => ['text'=>'6','value'=>6],
        "46" => ['text'=>'7','value'=>7],
        "47" => ['text'=>'8','value'=>8],
        "48" => ['text'=>'9','value'=>9],
        "49" => ['text'=>'10','value'=>10],
        "50" => ['text'=>'J','value'=>10],
        "51" => ['text'=>'Q','value'=>10],
        "52" => ['text'=>'K','value'=>10]
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
        Data::setDataByKey('brands', $brands);
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
        $users = Data::getDataByKey('users', array());
        $ausers = Data::getDataByKey('ausers', array());
        $ausers[$cuser['user']] = $cuser['brands'];
        Data::setDataByKey('ausers', $ausers);
        if (count($users)>0) {
            $user = array_shift($users);
            Data::setDataByKey('users', $users);
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