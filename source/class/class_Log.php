<?php

class Log
{
    const kLogFatal = "Fatal";
    const kLogError = "Error";
    const kLogWarn  = "Warn ";
    const kLogInfo  = "Info ";
    const kLogDebug = "Debug";
    const kLogSQL   = "SQL  ";

    // public static function write($level, $content)
    // {
    //     $csv = CSVServer::getInstance();

    //     $fatalEnable = $csv->getLogFatalEnable();
    //     $errorEnable = $csv->getLogErrorEnable();
    //     $warnEnable  = $csv->getLogWarnEnable();
    //     $infoEnable  = $csv->getLogInfoEnable();
    //     $debugEnable = $csv->getLogDebugEnable();
    //     $sqlEnable   = $csv->getLogSqlEnable();

    //     $levelInfo = array(
    //         self::kLogFatal => $fatalEnable,
    //         self::kLogError => $errorEnable,
    //         self::kLogWarn  => $warnEnable,
    //         self::kLogInfo  => $infoEnable,
    //         self::kLogDebug => $debugEnable,
    //         self::kLogSQL   => $sqlEnable, );
      
    //     if ($levelInfo[$level])
    //     {
    //         self::doWrite($level, $content);
    //     }
    // }

    // private static function doWrite($level, $content)
    // {
    //     $csv = CSVServer::getInstance();

    //     $logDir  = $csv->getLogDirLinux();
    //     $logName = $csv->getLogName();

    //     if (strtoupper(substr(PHP_OS, 0, 3)) === "WIN")
    //     {
    //         $logDir = $csv->getLogDirWin();
    //     }

    //     if (defined("SERVICE_ACCOUNTDB_WORKER"))    $logDir = $csv->getAccountDBWorkerLogDir();
    //     else if (defined("SERVICE_GAMEDB_WORKER"))  $logDir = $csv->getGameDBWorkerLogDir();
    //     else if (defined("SERVICE_OPLOGDB_WORKER")) $logDir = $csv->getOpLogDBWorkerLogDir();
    //     else if (defined("SERVICE_OPLOGDB_TIMER"))  $logDir = $csv->getOpLogDBTimerLogDir();
        
    //     if (! file_exists($logDir))
    //     {
    //         mkdir($logDir, 0777);
    //     }

    //     if(!(substr(sprintf("%o",fileperms($logDir)),-4) === '0777'))
    //     {
    //     	@chmod($logDir, 0777);
    //     }
        
        
    //     $logDate = date("Y_m_d/");
    //     $dir = $logDir . $logDate;

    //     if (! file_exists($dir))
    //     {
    //         mkdir($dir, 0777);
    //     }
		
    //     if(!(substr(sprintf("%o",fileperms($dir)),-4) === '0777'))
    //     {
    //     	@chmod($dir, 0777);
    //     }
        
    //     $hour = date("H");
    //     $logInterval = $csv->getLogInterval();
    //     $minute = (int)date("i");
    //     $minute = (int)($minute / $logInterval) * $logInterval;
    //     $minute = sprintf("%02d", $minute);
    //     $sapi = php_sapi_name();
    //     $logFile = $logName . "_" . $hour . $minute. "_" . $sapi . ".log";
    //     $fullPath = $dir . $logFile;
 
    //     $timestamp = date("H:i:s");
    //     $text = $timestamp . " " . $level . " " . $content . "\r\n";
                
    //     file_put_contents($fullPath, $text, FILE_APPEND | LOCK_EX);
    // }

    private static function write ($filePath,$text) {
        $time = date('Y-m-d H:i:s', time());
        $day = date('Y-m-d',time());
        $lastDay = date('Y-m-d',time()-86400);
        $filePathArr = explode('.', $filePath);
        if (!file_exists(DIR_ROOT . './log/'.$filePathArr[0].'_'.$lastDay.'.'.$filePathArr[1]) && file_exists(DIR_ROOT . './log/'.$filePath)) {
            rename(DIR_ROOT . './log/'.$filePath, DIR_ROOT . './log/'.$filePathArr[0].'_'.$lastDay.'.'.$filePathArr[1]);
        }
        $text = $time."\t".$text."\n\n";
        if(!file_exists(dirname(DIR_ROOT . './log/'.$filePath))) if(!mkdir(dirname(DIR_ROOT . './log/'.$filePath), 0777, true)) $null = null;
        file_put_contents(DIR_ROOT . './log/'.$filePath, $text , FILE_APPEND | LOCK_EX);
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

function logFatal()
{
    $args    = func_get_args();
    if (count($args) > 1)
    {
        $content = call_user_func_array('sprintf', $args);
    }
    else
    {
        $content = $args[0];
    }

    Log::Write(Log::kLogFatal, $content);
}

function logError()
{
    $args    = func_get_args();
    if (count($args) > 1)
    {
        $content = call_user_func_array('sprintf', $args);
    }
    else
    {
        $content = $args[0];
    }

    Log::Write(Log::kLogError, $content);
}

function logInfo()
{
    $args    = func_get_args();
    if (count($args) > 1)
    {
        $content = call_user_func_array('sprintf', $args);
    }
    else
    {
        $content = $args[0];
    }

    Log::Write(Log::kLogInfo, $content);
}

function logWarn()
{
    $args    = func_get_args();
    if (count($args) > 1)
    {
        $content = call_user_func_array('sprintf', $args);
    }
    else
    {
        $content = $args[0];
    }

    Log::Write(Log::kLogWarn, $content);
}

function logDebug()
{
    $args    = func_get_args();
    if (count($args) > 1)
    {
        $content = call_user_func_array('sprintf', $args);
    }
    else
    {
        $content = $args[0];
    }
	Log::Write(Log::kLogDebug, $content);
}

function logSQL()
{
    $args    = func_get_args();
    $content = call_user_func_array('sprintf', $args);

    Log::Write(Log::kLogSQL, $content);
}

?>
