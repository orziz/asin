<?php

/** 数据存储类 */

namespace kjbotModule\Monopoly;
use kjBot\Framework\DataStorage;

class Data {

    public static function getData() {
        $data = DataStorage::GetData('Monopoly.json');
        return $data ? json_decode($data, true) : $data;
    }

    public static function setData($data) {
        return DataStorage::SetData('Monopoly.json', json_encode($data));
    }

    public static function getDataByKey($key, $value = null) {
        $data = self::getData();
        return isset($data[$key]) ? $data[$key] : $value;
    }

    public static function setDataByKey($key, $value) {
        $data = self::getData();
        $data[$key] = $value;
        return self::setData($data);
    }

}