<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

/**
 * 插件plugins
 *
 * @author coolmoo
 */
class plugin {
    public static $name;
    function path($name,$file='') {
        self::$name=$name;
        $path=iCMS_PLUGINS_PATH.'/'.$name.'/';
        $file && $filepath=$path.$file;
        if(file_exists($filepath)) {
            return $filepath;
        }
        return empty($file)?$path:false;
    }
    function config($name,$key='') {
        $_file = self::path($name,'config.php');
        if($_file) {
            include $_file;
            return $key?$_plugins[$key]:$_plugins;
        }
    }
    function fn($name) {
        $_file = self::path($name,'function.php');
        if($_file) {
            require_once ($_file);
            return true;
        }else {
            return false;
        }
    }
    function readme($name) {
        $_file = self::path($name,'readme.txt');
        if($_file) {
            return FS::read($_file);
        }
    }
    function sql($name,$type='install') {
        $_file = self::path($name,$type.'.sql');
        if($_file) {
            return FS::read($_file);
        }
    }
    function mo($name) {
        return self::path($name,$name.'.mo.php');
    }
    function acptpl($p='') {
        empty($p) && $p=self::$name;
        return self::path(self::$name,'templates/admincp/'.$p.'.php');
    }
    function tpl($name) {
        return self::path($name,'templates');
    }
    function doList() {
        if ($handle = opendir(iCMS_PLUGINS_PATH)) {
            while (false !== ($file = readdir($handle))) {
                $sFileType = filetype(iCMS_PLUGINS_PATH."/".$file);
                if($sFileType=="dir"&& $file!='.' && $file!='..') {
                    $rs[] = $file;
                }
            }
        }
        return (array)$rs;
    }

}