<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class groups extends AdminCP {
    function domanage() {
        Admin::MP("menu_group_manage");
        include iPATH.'include/group.class.php';
        $group = new group();
        $type  = $_GET['type'];
        include admincp::tpl();
    }
    function dopower() {
        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__group` WHERE `gid`='".intval($_GET['groupid'])."'");
        include admincp::tpl();
    }
    function dofpower() {
        include_once iPATH.'include/forum.class.php';
        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__group` WHERE `gid`='".intval($_GET['groupid'])."'");
        admincp::head();
        $forum =new forum();
        $forum->allArray();
        include admincp::tpl();
    }
    function dodel() {
        $gid=(int)$_GET['groupid'];
        $gid&&iCMS_DB::query("DELETE FROM `#iCMS@__group` WHERE `gid`='$gid'");
        javascript::dialog('已删除!','js:parent.$("#gid'.$gid.'").remove();parent.iCMS.closeDialog();');
    }
    function doSetpower() {
        $gid=(int)$_POST['gid'];
        $power=@implode(",",$_POST['power']);
        iCMS_DB::query("UPDATE `#iCMS@__group` SET `power` = '{$power}' WHERE `gid` ='$gid' LIMIT 1");
        javascript::dialog("设置完成!","url:".__SELF__."?mo=groups&do=manage");
    }
    function doSetfpower() {
        $gid=(int)$_POST['gid'];
        $power=@implode(",",$_POST['power']);
        iCMS_DB::query("UPDATE `#iCMS@__group` SET `cpower` = '{$power}' WHERE `gid` ='$gid' LIMIT 1");
        javascript::dialog("设置完成!","url:".__SELF__."?mo=groups&do=manage");
    }
    function doEdit() {
        foreach($_POST['name'] as $id=>$value) {
            iCMS_DB::query("update `#iCMS@__group` set `name`='$value',`order`='".$_POST['order'][$id]."' where `gid`='$id'");
        }
        if($_POST['addnewname']) {
            iCMS_DB::query("INSERT INTO `#iCMS@__group`(`gid`,`name`,`order`,`power`,`cpower`,`type`) VALUES (NULL,'".$_POST['addnewname']."','".$_POST['addneworder']."','','','".$_POST['type']."')");
            javascript::dialog('添加完成!','url:1');
            exit;
        }
        javascript::dialog('更新完成!','url:1');
        //_Header();
    }
}

