<?php

namespace kjBotModule\TRPG;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;
use \Log;

/**
 * 
 */
class Common extends Module
{

    protected function setAttrs() {

    }

    protected function getAttrs($file) {
        $text = DataStorage::GetData($file);
        if (!$text) q('没有您的信息，请导入');
        return json_decode($text, true);
    }

    protected function getAllAttrs($file) {
        $attrs = $this->getAllAttrs($file);
        $text = '';
        foreach ($attrs as $key => $value) {
            $text .= "$key:$value,";
        }
        return $text;
    }
    
    /**
     * 获取属性
     *
     * @return void
     */
    protected function getAttr($file, $name) {
        if (!$name) q('没有该属性');
        $attrs = $this->getAttrs($file);
        if (!isset($attrs[$name])) q('属性不正确');
        return $attrs[$name];
    }

    /**
     * 属性检定
     *
     * @param [type] $num
     * @param [type] $name
     * @param string $type
     * @param integer $min
     * @param integer $max
     * @return string
     */
    protected function rollCheck($num, $name, $type = 'o', $min = 1, $max = 100):string {
        switch ($type) {
            case 'd':
                $text = '困难';
                break;
            case 'e':
                $text = '极难';
                break;
            default:
                $text = '普通';
                break;
        }
        $n = mt_rand($min, $max);
        $text .= "检定：{$min}d{$max}：{$n}，检定 {$name}({$num}) ";
        if ($n <= 5) {
            $text .= '大成功！！！';
        } else {
            if ($n >= 96 && $n > $num) {
                $text .= '大失败！！！';
            } elseif ($n <= $num) {
                $text .= '成功';
            } else {
                $text .= '失败';
            }
        }
        return $text;
    }
    
}