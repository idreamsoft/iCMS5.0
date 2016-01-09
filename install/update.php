<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
//error_reporting(E_ERROR | E_PARSE);
require_once(dirname(__FILE__)."/../global.php");


$lockfile = iPATH.'include/update.lock';
if(file_exists($lockfile)) {
	show_msg('警告!您已经升级过iCMS数据库结构<br>
		为了保证数据安全，请立即手动删除 update.php 文件<br>
		如果您想再次升级iCMS，请删除 ./include/update.lock 文件，再次运行安装文件');
}

//处理开始
if(empty($_GET['step'])) {
	//开始
	$_GET['step'] = 0;

	show_msg('<a href="?step=1">升级开始</a><br>本升级程序会参照最新的SQL文,对你的数据库进行升级');

} elseif ($_GET['step'] == 1) {
	//写入新配置
	iCMS_DB::query("INSERT INTO `#iCMS@__config`(`name`,`value`) values ('tagURL','".$iCMS->config['url']."'),('tagDir','');");
	//更新网站设置
	$tmp=iCMS_DB::getArray("SELECT * FROM `#iCMS@__config`");
	$config_data="<?php\n\t\$config=array(\n";
	for ($i=0;$i<count($tmp);$i++){
		$_config.="\t\t\"".$tmp[$i]['name']."\"=>\"".$tmp[$i]['value']."\",\n";
	}
	$config_data.=substr($_config,0,-2);
	$config_data.="\t\n);?>";
	FS::write(iPATH.'include/site.config.php',$config_data);
	show_msg('网站配置更新完成，进入下一步', '?step=2');
} elseif ($_GET['step'] == 2) {
	//写log
	if(@$fp = fopen($lockfile, 'w')) {
		fwrite($fp, 'iCMS');
		fclose($fp);
	}
	show_msg('升级完成，请到后台工具->更新缓存.为了您的数据安全，避免重复升级，请登录FTP删除本文件!');
}

function getgpc($k, $var='G') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}


//ob
function obclean() {
	ob_end_clean();
	if (function_exists('ob_gzhandler')) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
}
//显示
function show_msg($message, $url_forward='') {
	global $_iGLOBAL;

	obclean();

	if($url_forward) {
		$_iGLOBAL['extrahead'] = '<meta http-equiv="refresh" content="100; url='.$url_forward.'">';
		$message = "<a href=\"$url_forward\">$message(跳转中...)</a>";
	} else {
		$_iGLOBAL['extrahead'] = '';
	}

	show_header();
	print<<<END
	<table>
	<tr><td>$message</td></tr>
	</table>
END;
	show_footer();
	exit();
}


//页面头部
function show_header() {
	global $_iGLOBAL;

	$nowarr = array($_GET['step'] => ' class="current"');

	if(empty($_iGLOBAL['extrahead'])) $_iGLOBAL['extrahead'] = '';
	print<<<END
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	$_iGLOBAL[extrahead]
	<title> iCMS 数据库升级程序 </title>
	<style type="text/css">
	* {font-size:12px; font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 1.5em; word-break: break-all; }
	body { text-align:center; margin: 0; padding: 0; background: #EAEAEA; }
	.bodydiv { margin: 40px auto 0; width:720px; text-align:left; border: solid #cccccc; border-width: 5px 1px 1px; background: #FFF; }
	h1 { font-size: 18px; margin: 1px 0 0; line-height: 50px; height: 50px; background: #F7F7F7; padding-left: 10px; }
	#menu {width: 100%; margin: 10px auto; text-align: center; }
	#menu td { height: 30px; line-height: 30px; color: #999; border-bottom: 3px solid #EEE; }
	.current { font-weight: bold; color: #090 !important; border-bottom-color: #F90 !important; }
	.showtable { width:100%; border: solid; border-color:#86B9D6 #B2C9D3 #B2C9D3; border-width: 3px 1px 1px; margin: 10px auto; background: #F5FCFF; }
	.showtable td { padding: 3px; }
	.showtable strong { color: #5086A5; }
	.datatable { width: 100%; margin: 10px auto 25px; }
	.datatable td { padding: 5px 0; border-bottom: 1px solid #EEE; }
	input { border: 1px solid #B2C9D3; padding: 5px; background: #F5FCFF; }
	.button { margin: 10px auto 20px; width: 100%; }
	.button td { text-align: center; }
	.button input, .button button { border: solid; border-color:#F90; border-width: 1px 1px 3px; padding: 5px 10px; color: #090; background: #FFFAF0; cursor: pointer; }
	#footer { line-height: 40px; background: #F7F7F7; text-align: center; height: 38px; overflow: hidden; color: #333333; margin-top: 20px; font-family: "Courier New", Courier, monospace; }
	</style>
	</head>
	<body>
	<div class="bodydiv"><img src="http://www.idreamsoft.com/doc/iCMS.logo.gif" width="172" height="68"  style="margin:5px 0px 3px 5px"/>
	<h1>iCMS数据库升级工具</h1>
	<div style="width:90%;margin:0 auto;">
	<table id="menu">
	<tr>
	<td{$nowarr[0]}>升级开始</td>
	<td{$nowarr[1]}>更新网站配置</td>
	<td{$nowarr[2]}>升级完成</td>
	</tr>
	</table>
	<br>
END;
}

//页面顶部
function show_footer() {
	print<<<END
	</div>
	<div id="footer">&copy; iDreamSoft Inc. 2007-2010 http://www.idreamsoft.com</div>
	</div>
	<br>
	</body>
	</html>
END;
}
//判断提交是否正确
function submitcheck($var) {
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
			return true;
		} else {
			showmessage('submit_invalid');
		}
	} else {
		return false;
	}
}
//获取到表名
function tname($name) {
	return DB_PREFIX.$name;
}
//对话框
function showmessage(){
		if(!empty($url_forward)) {
			$second = $second * 1000;
			$message .= "<script>setTimeout(\"window.location.href ='$url_forward';\", $second);</script>";
		}
}

//取消HTML代码
function shtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}
?>