<?php

namespace PHF;

class File {

    public static function getFileInfo($file) {
        if (!file_exists($file)) return array();
        return array(
            'name' => $file,
            'type' => filetype($file),
            'size' => filesize($file),
            'create_time' => getTime('Y-m-d H:i:s', filectime($file)),
            'change_time' => getTime('Y-m-d H:i:s', filemtime($file))
        );
    }

    public static function hasDir($path) {
        return (file_exists($path) && is_dir($path));
    }

    public static function getDirPath($path) {
        if (substr($path, -1) == DIRECTORY_SEPARATOR) return $path;
        return $path . DIRECTORY_SEPARATOR;
    }

    public static function dirname($path) {
        $tempArr = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($tempArr);
        return implode(DIRECTORY_SEPARATOR, $tempArr);
    }

    public static function readDir($path) {
		$fileArr = array();
		if (!is_dir($path)) return -1;
        $handle = opendir($path);
        while (($fl = readdir($handle)) !== false) {
            if ($fl == '__MACOSX' || $fl == '.' || $fl == '..') continue;
            array_push($fileArr, $fl);
		}
		return $fileArr;
    }

    public static function readDirWithInfo($path) {
        $fileArr = self::readDir($path);
        for ($i = 0; $i < count($fileArr); $i++) {
            $filePath = self::getDirPath($path) . $fileArr[$i];
            $fileName = $fileArr[$i];
            $fileArr[$i] = self::getFileInfo($filePath);
            $fileArr[$i]['name'] = $fileName;
        }
        return $fileArr;
    }

    public static function getUploadArr() {
		$i = 0;
		$files = array();
		foreach ($_FILES as $key => $file) {
			if ($file['name'] !== '') {
				if (is_string($file['name'])) {
					if ($file['name'][$key] !== '') {
						$files[$i] = $file;
						$i++;
					}
				} elseif (is_array($file['name'])) {
					for ($k=0; $k < count($file['name']); $k++) { 
						if ($file['name'][$k] !== '') {
							$files[$i]['name'] = $file['name'][$k];
							$files[$i]['type'] = $file['type'][$k];
							$files[$i]['tmp_name'] = $file['tmp_name'][$k];
							$files[$i]['error'] = $file['error'][$k];
							$files[$i]['size'] = $file['size'][$k];
							$i++;
						}
					}
				}
			}
		}
		return $files;
	}

}