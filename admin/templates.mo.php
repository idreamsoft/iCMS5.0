<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class templates extends AdminCP {
    function domanage() {
        Admin::MP("menu_template_manage");
        $dir=trim($_GET["dir"]);
        $L=FS::folder($dir,"templates","");
        include	admincp::tpl();
    }
    function doedit() {
        $path=trim($_GET["path"]);
        $FileData=FS::read(iPATH."templates".$path);
        $strpos = strpos(__REF__,'?');
        $REFERER = $strpos===false?'':substr(__REF__,$strpos);
        include	admincp::tpl();
    }
    function doclear() {
        $path=trim($_GET["path"]);
        $this->iCMS->clear_compiled_tpl($path);
        javascript::dialog('清除完成!');
    }
    function doSave() {
        strpos($_POST['tplpath'],'..')!==false && javascript::alert("文件路径不能带有..");
        preg_match("/\.([a-zA-Z0-9]{2,4})$/",$_POST['tplpath'],$exts);
        $FileExt=strtolower($exts[1]);
        strstr($FileExt, 'ph') && javascript::alert("文件格式错误！");
        in_array($FileExt,array('cer','htr','cdx','asa','asp','jsp','aspx','cgi'))&& javascript::alert("文件格式错误！");
        $FileData=stripslashes($_POST['html']);
        FS::write(iPATH.'templates'.$_POST['tplpath'],$FileData);
        javascript::dialog('保存成功!','url:'.__SELF__.$_POST['REFERER']);
    }
}

