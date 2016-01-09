<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class advertise extends AdminCP {
    function doAdd() {
        $id=intval($_GET['advid']);
        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__advertise` where id='$id'",ARRAY_A);
        $rs['starttime']=get_date($rs['starttime'],'Y-m-d');
        $rs['endtime']=empty($rs['endtime'])?'':get_date($rs['endtime'],'Y-m-d');
        $adv=stripslashes_deep(unserialize($rs['code']));
        empty($adv) && $adv=array();
        empty($rs['style']) &&$rs['style']='code';
        include admincp::tpl();
    }
    function doSave() {
        $id	=intval($_POST['id']);
        $load	=$_POST['load'];
        $state	=intval($_POST['state']);
        $varname=$_POST['varname'];
        $title	=dhtmlspecialchars($_POST['title']);
        $style	=$_POST['style'];
        $starttime=empty($_POST['starttime'])?0:_strtotime($_POST['starttime']);
        $endtime=empty($_POST['endtime'])?0:_strtotime($_POST['endtime']);
        $code=addslashes(serialize($_POST['adv']));
        !$varname && javascript::alert("广告标识符不能为空");
        if($id) {
        	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__advertise` where `varname` ='$varname' AND `id` !='$id'") && javascript::alert('该广告标识已经存在!请检查是否重复');
            iCMS_DB::query("UPDATE `#iCMS@__advertise` SET `varname` = '$varname',`title` = '$title',`style`='$style',`starttime` = '$starttime',`endtime` = '$endtime',`code` = '$code',`load` = '$load',`status` = '$state' WHERE `id` ='$id'");
        }else{
        	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__advertise` where `varname` ='$varname'") && javascript::alert('该广告标识已经存在!请检查是否重复');
            iCMS_DB::query("INSERT INTO `#iCMS@__advertise`(`varname` , `title` ,`style`, `starttime` , `endtime` , `code` , `load`, `status` ) VALUES ('$varname','$title','$style','$starttime', '$endtime', '$code', '$load', '$state')");
            $id=iCMS_DB::$insert_id;
        }
        $this->create($id);
        javascript::dialog("更新完成!",'url:'.__SELF__."?mo=advertise");
    }
    function doDel() {
        foreach($_POST['id'] as $id) {
            FS::del($this->create($id,true));
            iCMS_DB::query("delete from `#iCMS@__advertise` where `id`='$id'");
            $this->iCMS->iCache->delete('system/adm/'.md5($_POST['varname'][$id]));
        }
        javascript::dialog("操作完成!",'url:1');
    }
    function doJs() {
        foreach($_POST['id'] as $id) {
            $this->create($id);
        }
        javascript::dialog("操作完成!",'url:1');
    }
    function doStatus() {
        $id=intval($_GET['id']);
        $act=intval($_GET['act']);
        iCMS_DB::query("UPDATE `#iCMS@__advertise` SET `status` = '$act' WHERE `id` ='$id'");
        $this->create($id);
        javascript::dialog("操作完成!",'url:1');
    }
    function dodefault() {
        Admin::MP(array("menu_index_advertise","menu_advertise"));
        $maxperpage =30;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__advertise`"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"个广告");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__advertise` order by id DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include admincp::tpl("advertise");
    }
    function create($id,$fn=false) {
        $rs	  = iCMS_DB::getRow("SELECT * FROM `#iCMS@__advertise` WHERE `id`='$id'");
        return adm($rs);
    }
}
function getadvhtml($style,$c) {
	if(empty($c))return '';
    switch ($style) {
        case 'code':
            $html=$c['code']['html'];
            break;
        case "image":
            $c['image']['width'] && $width=" width=\"{$c['image']['width']}\"";
            $c['image']['height'] && $height=" height=\"{$c['image']['height']}\"";
            $html="<a href=\"{$c['image']['link']}\" target=\"_blank\" title=\"{$c['image']['alt']}\"><img src=\"{$c['image']['url']}\" alt=\"{$c['image']['alt']}\"{$width}{$height} alt=\"{$c['image']['alt']}\" border=\"0\"></a>";
            break;
        case "flash":
            $c['flash']['width'] && $width=" width=\"{$c['flash']['width']}\"";
            $c['flash']['height'] && $height=" height=\"{$c['flash']['height']}\"";
            $html="<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0\" {$width}{$height}><param name=\"movie\" value=\"{$c['flash']['url']}\" /><param name=\"quality\" value=\"high\" /><embed src=\"{$c['flash']['url']}\" quality=\"high\" pluginspage=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\"{$width}{$height}></embed></object>";
            break;
        case "text":
            $c['text']['size'] &&$style=" style=\"font-size:{$c['text']['size']};\"";
            $html="<a href=\"{$c['text']['link']}\" target=\"_blank\" title=\"{$c['text']['title']}\"{$style}>{$c['text']['title']}</a>";
            break;
    }
    return addslashes($html);
}
function adm($rs){
	global $iCMS;
    $rs->code = stripslashes_deep(unserialize($rs->code));
    $file = FS::path(iPATH.$iCMS->config['htmldir'].'/!adm').'/';
    FS::mkdir($file);
    switch ($rs->load) {
        case "js":
            $file .= "{$rs->style}-{$rs->id}.js";
            $html = "/*\n广告:{$rs->varname}\n标签:<!--{iCMS:advertise name=\"{$rs->varname}\"}-->\n*/\n";
            if($rs->status) {
                $html.="var timestamp = Date.parse(new Date());\n";
                $html.="var startime = Date.parse(new Date(\"".get_date($rs->starttime,'Y/m/d')."\"));\n";
                $rs->endtime && $html.="var endtime = Date.parse(new Date(\"".get_date($rs->endtime,'Y/m/d')."\"));\n";
                $html.="if(timestamp>=startime";
                $rs->endtime && $html.="||timestamp<endtime";
                $html.="){\n";
                $html.= document(getadvhtml($rs->style,$rs->code));
                $html.="}";
            }
            break;
        case "iframe":
            $file .= "{$rs->style}-{$rs->id}.html";
            $html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>无标题文档</title><script type="text/javascript">function resizeFrame(){try{ var frames=parent.document.documentElement.getElementsByTagName("iframe"); for(var i=0;i<frames.length;i++){ frames[i].style.height=document.body.scrollHeight+"px"; var topPos=location.href.indexOf("#top");if(topPos>0){location.href=location.href.substring(0,topPos)+"#top1"}break;}}}catch(e){return;}}</script></head><body>'.getadvhtml($rs->style,$rs->code).'</body><script type="text/javascript">resizeFrame();</scrip</html>';
            break;
        case "shtml":
            $file .= "{$rs->style}-{$rs->id}.shtml";
            $html = getadvhtml($rs->style,$rs->code);
            break;
    }
    $rs->src=$file;
    $iCMS->setCache('system/adm/'.md5($rs->varname),$rs,0);
    if($fn){
        return $file;
    }
    $rs->load && FS::write($file,$html);
}
function document($HTML) {
    $HTML = str_replace("\r\n", "\n", $HTML);
    foreach(explode("\n",$HTML) AS $val) {
        $JS.="document.writeln(\"".$val."\");\n";
    }
    return $JS;
}
