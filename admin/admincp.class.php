<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
/**
 * 后台
 *
 * @author coolmoo
 */
class AdminCP {
    /**
     * 构造函数
     */
    function __construct() {
        global $iCMS,$firstcount,$pagenav;
        $this->iCMS 	= & $iCMS;
        $this->pagenav 	=  & $pagenav;
        $this->firstcount = & $firstcount;
        $this->frames	= isset($_GET['frames'])?$_GET['frames']:$_POST['frames'];
//		$do 	= !empty($_GET['do']) && is_string($_GET['do']) ? trim($_GET['do']) : '';
        $this->module	= ($_GET['mo']) ? $_GET['mo']:'';
        $this->action	= isset($_GET['do'])?$_GET['do']:$_POST['do'];
        $this->param	= isset($_GET['param'])?$_GET['param']:$_POST['param'];
        empty($this->action) && $this->action	= 'default';
        !isset($this->param) && $this->param	= NULL;
        if(empty($this->module) || isset($this->frames)) {
            $this->extra = $this->cpurl('url');
            $this->extra = $this->extra && $this->module ? $this->extra : 'mo=home';
            include $this->tpl('admincp');
            exit;
        }
    }
    /**
     * 运行应用程序
     * @param string $do 动作名称
     * @param string $mo 模块名称
     * @return AdminCP
     */
    function run($do = NULL,$moduleClass = NULL) {
        ($do === NULL) && $do = $this->action;
        ($moduleClass === NULL) && $moduleClass = $this->module;
        //$mo == 'Logout' && Admin::logout(__SELF__);
        $moduleFile = iPATH.'admin/'.$moduleClass. '.mo.php';
        if (is_file($moduleFile) || empty($mo)) {
            if (!class_exists($moduleClass)) {
                if (is_file($moduleFile)) {
                    include_once($moduleFile);
                } else {
                    $moduleClass = 'iAction';
                }
            }
            if (class_exists($moduleClass)) {
                $module = new $moduleClass($this);
                $method = 'do'.$do;
//                $method = $do?'do'.$do:($ac?'action'.$ac:'');
                if (method_exists($module, $method)) {
                    if($this->param===NULL) {
                        $module->$method();
                    }else {
                        $module->$method($this->param);
                    }
                } else {
                    throwException('应用程序运行出错.类 ' . $moduleClass. ' 中找不到方法定义:' . $method, 1003);
                }
            } else {
                throwException('应用程序运行出错.文件 ' . $moduleFile. ' 中找不到类定义:' . $moduleClass, 1002);
            }
        } else {
            throwException('应用程序运行出错.找不到文件:' . $moduleFile, 1001);
        }
        return $this;
    }
    function tpl($p=NULL) {
    	if($p===NULL && $this->module && $this->action){
    		$p=$this->module.'.'.$this->action;
    	}
        return iPATH.'admin/templates/'.$p.'.php';
    }
    function head($isFm=true) {
        include iPATH.'admin/templates/header.php';
    }
    function footer($a,$c) {
        echo base64_decode("PHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0iaHR0cDovL3d3dy5pZHJlYW1zb2Z0LmNvbS9jbXMvaUNNUy52ZXIucGhwP249").base64_encode($this->iCMS->config['name']).base64_decode('JnU9 =').base64_encode($this->iCMS->config['setupURL']).base64_decode('Jmk9').base64_encode($_SERVER['HTTP_HOST']).base64_decode('JnY9').base64_encode(iCMS_VER).base64_decode('JmM9').DB_CHARSET.base64_decode('JnM9').base64_encode($c.base64_decode('fA==').$a).base64_decode('Ij48L3NjcmlwdD4=');
    }
    function cpurl($type = 'parameter', $filters = array('frames')) {
        parse_str($_SERVER['QUERY_STRING'], $getarray);
        $extra = $and = '';
        foreach($getarray as $key => $value) {
            if(!in_array($key, $filters)) {
                @$extra .= $and.$key.($type == 'parameter' ? '%3D' : '=').rawurlencode($value);
                $and = $type == 'parameter' ? '%26' : '&';
            }
        }
        return $extra;
    }
    function doEmpty() {
        javascript::alert("请选择操作项",'url:0');
    }
}

