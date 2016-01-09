<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
define('iCMS', TRUE);
if ( !defined('iCMS_MEMORY_LIMIT') )
    define('iCMS_MEMORY_LIMIT', '128M');

if ( function_exists('memory_get_usage') && ( (int) @ini_get('memory_limit') < abs(intval(iCMS_MEMORY_LIMIT)) ) )
    @ini_set('memory_limit', iCMS_MEMORY_LIMIT);

@set_magic_quotes_runtime(0);
@ini_set('magic_quotes_sybase', 0);
define('iPATH',dirname(strtr(__FILE__,'\\','/'))."/");
define('iCMS_PLUGINS_PATH',iPATH."plugins");
header('Content-Type: text/html; charset=utf-8');
// 防止 PHP 5.1.x 使用时间函数报错
function_exists('date_default_timezone_set') && date_default_timezone_set('Etc/GMT+0');
unset($_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS,$HTTP_SESSION_VARS,$HTTP_SERVER_VARS);
unset($GLOBALS['_ENV'],$GLOBALS['HTTP_ENV_VARS'],$GLOBALS['_REQUEST'],$GLOBALS['HTTP_POST_VARS'],$GLOBALS['HTTP_GET_VARS'],$GLOBALS['HTTP_POST_FILES'],$GLOBALS['HTTP_COOKIE_VARS'],$GLOBALS['HTTP_SESSION_VARS'],$GLOBALS['HTTP_SERVER_VARS']);

if (ini_get('register_globals')) {
    isset($_REQUEST['GLOBALS']) && die('发现试图覆盖 GLOBALS 的操作');
    // Variables that shouldn't be unset
    $noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE','_SERVER', '_ENV', '_FILES');
    $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
    foreach ( $input as $k => $v ) {
        if ( !in_array($k, $noUnset) && isset($GLOBALS[$k]) ) {
            $GLOBALS[$k] = NULL;
            unset($GLOBALS[$k]);
        }
    }
}
// Fix for IIS when running with PHP ISAPI
if ( empty( $_SERVER['REQUEST_URI'] ) || ( php_sapi_name() != 'cgi-fcgi' && preg_match( '/^Microsoft-IIS\//', $_SERVER['SERVER_SOFTWARE'] ) ) ) {

    if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
    }else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
    }else {
        // Use ORIG_PATH_INFO if there is no PATH_INFO
        if ( !isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO']) )
            $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];

        // Some IIS + PHP configurations puts the script-name in the path-info (No need to append it twice)
        if ( isset($_SERVER['PATH_INFO']) ) {
            if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
                $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
            else
                $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
        }

        // Append the query string if it exists and isn't null
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
}

// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
if ( isset($_SERVER['SCRIPT_FILENAME']) && ( strpos($_SERVER['SCRIPT_FILENAME'], 'php.cgi') == strlen($_SERVER['SCRIPT_FILENAME']) - 7 ) )
    $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];

// Fix for ther PHP as CGI hosts
if (strpos($_SERVER['SCRIPT_NAME'], 'php.cgi') !== false) 
    unset($_SERVER['PATH_INFO']);

if ( empty($_SERVER['PHP_SELF']) )
    $_SERVER['PHP_SELF'] = preg_replace("/(\?.*)?$/",'',$_SERVER["REQUEST_URI"]);

if (version_compare( '4.3', phpversion(), '>' ))
    die( '您的服务器运行的 PHP 版本是' . phpversion() . ' 但 iCMS 要求至少 4.3。' );

if(!extension_loaded('mysql'))
    die( '您的 PHP 安装看起来缺少 MySQL 数据库部分，这对 iCMS 来说是必须的。' );

require_once iPATH.'config.php';
require_once iPATH.'include/version.php';
require_once iPATH.'include/compat.php';
require_once iPATH.'include/site.config.php';
require_once iPATH.'include/common.php';

define('iCMS_BUG', $config['debug']);
define('iCMS_TPL_BUG',$config['tpldebug']);
error_reporting(iCMS_BUG?E_ALL ^ E_NOTICE:0);
define("__CN__",'/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/');
define('__SELF__',dhtmlspecialchars($_SERVER['PHP_SELF']));
define('__REF__',empty($_SERVER['HTTP_REFERER'])?'':$_SERVER['HTTP_REFERER']);

if ( get_magic_quotes_gpc() ) {
    $_GET	&& $_GET    = stripslashes_deep($_GET);
    $_POST	&& $_POST   = stripslashes_deep($_POST);
    $_COOKIE = stripslashes_deep($_COOKIE);
}
$_GET	&& $_GET    = add_magic_quotes($_GET);
$_POST	&& $_POST   = add_magic_quotes($_POST);
$_COOKIE = add_magic_quotes($_COOKIE);
$_SERVER = add_magic_quotes($_SERVER);
$_iGLOBAL= array();
$_iGLOBAL['timestamp']=time();

require_once iPATH.'include/cookie.php';
require_once iPATH.'include/mysql.class.php';
require_once iPATH.'include/FileSystem.class.php';
require_once iPATH.'include/'.($config['cacheEngine']=='memcached'?'memcached':'FileCache').'.class.php';
require_once iPATH.'include/template/template.class.php';
require_once iPATH."include/iCMS.class.php";

$uri =	parse_url(substr($config['setupURL'], -1) != '/'?$config['setupURL'].'/':$config['setupURL']);
//$config['url'] 		= $uri['scheme'].'://'.$uri['host'];
$config['url'] 		= $config['setupURL'];
$config['dir'] 		= $uri['path'];
$config['domain'] 	= substr($uri['host'],strpos($uri['host'],'.')+1);
$iCMS = new iCMS;
unset($config,$uri);
isset($_GET['page']) && $page=(int)$_GET['page'];
if(isset($_GET['date'])) {
    list($y,$m,$d)=explode('_',$_GET['date']);
    $iCMS->date=array('y'=>$y,'m'=>$m,'d'=>$d,'total'=>date('t',mktime(0,0,0,$m+1,0,$y)));
}
if(iCMS_TPL_BUG){
	iCMS_DB::$show_errors=true;
	$iCMS->clear_compiled_tpl();
}
?>