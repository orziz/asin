<?php

/**
 * 
 */
define('GO_ROOT', substr(dirname(__FILE__), 0, -12));
define('DIR_ROOT', substr(dirname(__FILE__), 0, -12));
date_default_timezone_set('Asia/Shanghai');

require_once GO_ROOT . './config/config_global.php';
require_once GO_ROOT . './source/function/function_core.php';
require_once GO_ROOT . './source/class/class_DBConn.php';
require_once GO_ROOT . './source/class/class_RCNB.php';
require_once GO_ROOT . './source/class/class_Log.php';

if(function_exists('spl_autoload_register')) {
	spl_autoload_register(array('core', 'autoload'));
} else {
	function __autoload($class) {
		return core::autoload($class);
	}
}

/**
 * 
 */
class core
{
	private static $_tables;
	private static $_imports;
	
	function __construct()
	{
		# code...
	}

	public static function init() {
	}

	public static function t($name) {
		return self::_make_obj($name, 'table', false);
	}

	protected static function _make_obj($name, $type, $extendable = false, $p = array()) {
		$pluginid = null;
		if($name[0] === '#') {
			list(, $pluginid, $name) = explode('#', $name);
		}
		$cname = $type.'_'.$name;
		if(!isset(self::$_tables[$cname])) {
			self::$_tables[$cname] = new $cname();
		}
		return self::$_tables[$cname];
	}

	public static function import($name, $folder = '', $force = true) {
		$key = $folder.$name;
		if(!isset(self::$_imports[$key])) {
			$path = GO_ROOT.'/source/'.$folder;
			if(strpos($name, '/') !== false) {
				$pre = basename(dirname($name));
				$filename = dirname($name).'/'.$pre.'_'.basename($name).'.php';
			} else {
				$filename = $name.'.php';
			}

			if(is_file($path.'/'.$filename)) {
				include $path.'/'.$filename;
				self::$_imports[$key] = true;

				return true;
			} elseif(!$force) {
				return false;
			} else {
				throw new Exception('Oops! System file lost: '.$filename);
			}
		}
		return true;
	}

	public static function autoload($class) {
		$class = strtolower($class);
		if(strpos($class, '_') !== false) {
			list($folder) = explode('_', $class);
			$file = 'class/'.$folder.'/'.substr($class, strlen($folder) + 1);
		} else {
			$file = 'class/'.$class;
		}

		try {

			self::import($file);
			return true;

		} catch (Exception $exc) {

			$trace = $exc->getTrace();
			foreach ($trace as $log) {
				if(empty($log['class']) && $log['function'] == 'class_exists') {
					return false;
				}
			}
			discuz_error::exception_error($exc);
		}
	}
}

class C extends core {}
class DB extends DBConn {}

$db = new DBConn($_config['dbhost']['ip'], $_config['dbhost']['user'], $_config['dbhost']['pwd'], $_config['dbhost']['base'],$_config['dbhost']['port']);
$rcnb = new RCNB();
