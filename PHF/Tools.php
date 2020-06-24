<?php

namespace PHF;

class Tools {

    /**
     * 获取当前时间
     * @param string $format    时间格式
     * @param string $time      时间戳
     * @return string
     */
    public static function getTime(string $format='Y-m-d H:i:s',$time=null) {
        if (!$time) $time = time();
        return date($format, $time);
    }
    
    /**
     * 解析编码
     * @param  string $str
     * @return string
     */
    function detect_encoding($str) {
        $list = array('GBK', 'GB2312' ,'UTF-8', 'UTF-16LE', 'UTF-16BE', 'ISO-8859-1');
        foreach ($list as $item) {
            $tmp = mb_convert_encoding($str, $item, $item);
            if (md5($tmp) == md5($str)) {
                return $item;
            }
        }
        return '遇到识别不出来的编码！';
    }

    /**
     * get请求
     * @param   string  $url    请求的url
     * @param   mixed   $param  请求参数（不要带 ?）
     * 
     * @return  mixed   返回值
     */
    public static function request_get($url = "", $param = "") {
        if (empty($url)) return false;
        if (is_array($param)) {
            $parr = [];
            foreach ($param as $key => $value) {
                if (empty($value)) continue;
                $parr[] = $key . '=' . (is_array($value) ? json_encode($value) : $value);
            }
            $p = explode('&', $parr);
        } else {
            $p = $param;
        }
        return file_get_contents(urlencode($url.'?'.$p));
    }

    /**
     * post提交
     * @param  string $url   提交到的url
     * @param  string $param 提交的参数
     * @return mixed
     */
    public static function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        
        return $data;
    }

    public static function param_post($url = '', $param = '') {
        if (empty($url) || empty($param) || !is_array($param)) {
            return false;
        }
        $param = json_encode($param);
        $data = self::request_post($url,json_encode(array('param' => $param)));
        $data = json_decode($data,true);
        return $data;
    }

}
