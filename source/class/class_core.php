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
require_once GO_ROOT . './source/class/class_Table.php';
// require_once GO_ROOT . './source/class/class_RCNB.php';
require_once GO_ROOT . './source/class/class_Log.php';

// if(function_exists('spl_autoload_register')) {
	spl_autoload_register(array('core', 'autoload'));
// } else {
// 	function __autoload($class) {
// 		return core::autoload($class);
// 	}
// }

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
			if(!class_exists($cname, false)) {
				self::import(($pluginid ? 'plugin/'.$pluginid : 'class').'/'.$type.'/'.$name);
			}
			if($extendable) {
				self::$_tables[$cname] = new discuz_container();
				switch (count($p)) {
					case 0:	self::$_tables[$cname]->obj = new $cname();break;
					case 1:	self::$_tables[$cname]->obj = new $cname($p[1]);break;
					case 2:	self::$_tables[$cname]->obj = new $cname($p[1], $p[2]);break;
					case 3:	self::$_tables[$cname]->obj = new $cname($p[1], $p[2], $p[3]);break;
					case 4:	self::$_tables[$cname]->obj = new $cname($p[1], $p[2], $p[3], $p[4]);break;
					case 5:	self::$_tables[$cname]->obj = new $cname($p[1], $p[2], $p[3], $p[4], $p[5]);break;
					default: $ref = new ReflectionClass($cname);self::$_tables[$cname]->obj = $ref->newInstanceArgs($p);unset($ref);break;
				}
			} else {
				self::$_tables[$cname] = new $cname();
			}
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
		}
	}
}

class C extends core {}
class DB extends DBConn {}

// $rcnb = new RCNB();
