<?php

class Log
{
    const kLogFatal = "Fatal";
    const kLogError = "Error";
    const kLogWarn  = "Warn ";
    const kLogInfo  = "Info ";
    const kLogDebug = "Debug";
    const kLogSQL   = "SQL  ";

    /**
     * 写入Log文件
     * @param [type] $filePath  文件名
     * @param [type] $text      log内容
     * @return void
     */
    private static function write (string $filePath,$text) {
        $time = date('Y-m-d H:i:s', time());
        $mouth = date('Y-m',time());
        $day = date('Y-m-d',time());
        $lastDay = date('Y-m-d',time()-86400);
        $filePathArr = explode('.', $filePath);
        $path = DIR_ROOT .'.'. DIRECTORY_SEPARATOR .'log' . DIRECTORY_SEPARATOR . $mouth . DIRECTORY_SEPARATOR;
        if (!file_exists($path.$filePathArr[0].'_'.$lastDay.'.'.$filePathArr[1]) && 
            file_exists($path.$filePath)) {
            rename($path.$filePath, $path.$filePathArr[0].'_'.$lastDay.'.'.$filePathArr[1]);
        }
        $text = $time."\t".$text."\n\n";
        if(!file_exists(dirname($path.$filePath))) if(!mkdir(dirname($path.$filePath), 0777, true)) $null = null;
        file_put_contents($path.$filePath, $text , FILE_APPEND | LOCK_EX);
    }

    public static function Error ($text) {
        $filePath = 'error.log';
        self::write($filePath,$text);
    }

    public static function Debug ($text) {
        $filePath = 'debug.log';
        self::write($filePath,$text);
    }

    public static function Info ($text) {
        $filePath = 'info.log';
        self::write($filePath,$text);
    }

    public static function Sql ($text) {
        $filePath = 'sql.log';
        self::write($filePath,$text);
    }

}
