<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/plugin.class.php';
class plugins extends AdminCP {
    function domanage() {
        Admin::MP("menu_plugin_manage");
        $plugins= $this->iCMS->getCache('system/plugins');
		$rs		= plugin::doList();
        include admincp::tpl();
    }
    function dosetup($isSetup=1){
    	$plugName	= $_GET['name'];
        $plugins	= $this->iCMS->getCache('system/plugins');
//        $plugins[$plugName]=plugin::config($plugName,'config');
        $plugins[$plugName]['isSetup']=$plugins[$plugName]['status']=$isSetup;
        $this->iCMS->setCache('system/plugins',$plugins,0);
        
		$rs 	= plugin::config($plugName,'admincp');
		$plang	= $this->iCMS->getCache('system/plugins.lang');
		if($rs['lang']){
			foreach($rs['lang'] AS $key=>$val){
				if($isSetup){
					$plang[$key]=$val;
				}else{
					unset($plang[$key]);
				}
			}
	        $this->iCMS->setCache('system/plugins.lang',$plang,0);
		}
		
		$pmenu	= $this->iCMS->getCache('system/plugins.menu');
		if($rs['menu']){
			foreach($rs['menu'] AS $key=>$val){
				if($isSetup){
					$pmenu[$key]=$val;
				}else{
					unset($pmenu[$key]);
				}
			}
	        $this->iCMS->setCache('system/plugins.menu',$pmenu,0);
		}
		$data=plugin::sql($plugName,$isSetup?'install':'uninstall');
		if($data){
			$sqlArray=explode(";",$data);
			foreach($sqlArray AS $sql){
				$sql=trim($sql);
				$sql && iCMS_DB::query($sql);
			}
		}
        javascript::dialog($isSetup?'安装完成!':'卸载成功','url:1');
    }
    function doStatus($status=1){
    	$plugName	= $_GET['name'];
    	$plugins	= $this->iCMS->getCache('system/plugins');
    	$plugins[$plugName]['status']=$status;
    	$this->iCMS->setCache('system/plugins',$plugins,0);
        javascript::dialog($status?'已启用!':'已停用','url:1');
    }
//    function doupdate(){
//	   	$plugName	= $_GET['name'];
//        $plugins	= $this->iCMS->getCache('system/plugins');
//        $pconfig	= plugin::config($plugName,'config');
//        $plugins[$plugName]['status']=$pconfig[$plugName]['status'];
//        //$this->iCMS->setCache('system/plugins',$plugins,0);
//        //javascript::dialog('更新完成!','url:1');
//    }
    function doreadme(){
    	$data	= plugin::readme($_GET['name']);
    	javascript::dialog($data?array(HTML2JS(htmlspecialchars($data)),'查看说明'):'暂无说明','js:','msg','-1');
    }
    function dodefault(){
	   	$plugName	= $_GET['name'];
		$config 	= plugin::config($plugName,'admincp');
	   	$operate	= $_GET['o']?$_GET['o']:'default';
	   	define('__PLUGINACP__',__ADMINCP__.'='.$config['url'].'&o');
		include plugin::mo($plugName);
        $module = new $plugName();
        $method = 'do'.$operate;
        if (method_exists($module, $method)) {
			$module->$method();
        } else {
            throwException('应用程序运行出错.类 ' . $moduleClass. ' 中找不到方法定义:' . $method, 1003);
        }
    }
  
}
