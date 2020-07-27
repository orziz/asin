<?php

/** 数据存储类 */

namespace kjBotModule\Ninepm;
use kjBot\Framework\DataStorage;

class Data {

    public static function getData() {
        global $event;
        if ($event->fromGroup()) {
            $data = DataStorage::GetData(implode(DIRECTORY_SEPARATOR, array('Ninepm', $event->groupId.'.json')));
        } else {
            $data = DataStorage::GetData('Ninepm.json');
        }
        return $data ? json_decode($data, true) : $data;
    }

    public static function setData($data) {
        global $event;
        if ($event->fromGroup()) {
            $path = implode(DIRECTORY_SEPARATOR, array('Ninepm', $event->groupId.'.json'));
        } else {
            $path = 'Ninepm.json';
        }
        return DataStorage::SetData($path, json_encode($data));
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