<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
$do		= $_GET['do'];
$action	= $_GET['action'];
$id		= (int)$_GET['id'];
$mid	= (int)$_GET['mid'];
if(empty($mid)) {
    $__TABLE__='article';
}else {
    $__MODEL__	= $iCMS->cache('model.id','include/syscache',0,true);
    $model      = $__MODEL__[$mid];
    $__TABLE__	= $model['table'].'_content';
}
switch ($do) {
    case 'digg':
        if(in_array($action,array('good','bad'))) {
            $aTime=(time()-get_cookie('digg_'.$id)>$iCMS->config['diggtime'])?true:false;
            if($aTime) {
                set_cookie('digg_'.$id,time());
                if($id && iCMS_DB::query("UPDATE `#iCMS@__$__TABLE__` SET `$action` = $action+1  WHERE id='$id'")) {
                    $json="{state:'1'}";
                }
            }else {
                $json="{state:'0',text:'".$iCMS->language('digged')."' }";
            }
            jsonp($json,$_GET['callback']);
        }
        if($action=='show') {
            $digg=iCMS_DB::getValue("SELECT digg FROM `#iCMS@__$__TABLE__` WHERE id='$id' LIMIT 1");
            echo "document.write('{$digg}');\r\n";
        }
        break;
    case 'hits':
        iCMS_DB::query("UPDATE `#iCMS@__$__TABLE__` SET hits=hits+1 WHERE `id` ='$id' LIMIT 1");
        if($action=='show') {
            $hits=iCMS_DB::getValue("SELECT hits FROM `#iCMS@__$__TABLE__` WHERE id='$id'");
            echo "document.write('{$hits}');\r\n";
        }
        break;
    case 'comment':
        if(in_array($action,array('up','down'))) {
            UA($action,(int)$_GET['cid']);
        }
        if($action=='show') {
            if($iCMS->config['iscomment']) {
                $comments=iCMS_DB::getValue("SELECT comments FROM `#iCMS@__$__TABLE__` WHERE id='$id'  LIMIT 1");
                echo "document.write('{$comments}');\r\n";
            }
        }
        break;
}
function UA($act,$cid) {
    global $iCMS;
    $cookietime=$iCMS->config['diggtime'];
    $ajax=intval($_GET['ajax']);
    $cTime=(time()-get_cookie($cid.'_up')>$cookietime && time()-get_cookie($cid.'_against')>$cookietime)?true:false;
    if($cTime) {
        set_cookie($cid.'_'.$act,time(),$cookietime);
        if($cid && iCMS_DB::query("UPDATE `#iCMS@__comment` SET `{$act}` = {$act}+1  WHERE `id` ='$cid'")) {
            $ajax?jsonp("{state:'1'}",$_GET['callback']):_Header($iCMS->config['publicURL']."/comment.php?indexId=".$id);
        }
    }else {
        $ajax?jsonp("{state:'0',text:'".$iCMS->language('digged')."' }",$_GET['callback']):alert($iCMS->language('digged'));
    }
}
function jsonp($json,$callback) {
    echo $callback.'('.$json.')';
    exit;
}
