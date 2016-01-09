<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class comment extends AdminCP {
    function doExamine() {
        $id		=intval($_GET['id']);
        $indexId=intval($_GET['indexId']);
        $id && iCMS_DB::query("UPDATE `#iCMS@__comment` SET `status` = '1' WHERE `id` ='$id'");
        $indexId&&iCMS_DB::query("UPDATE `#iCMS@__article` SET `comments` = comments+1  WHERE `id` ='$indexId'");
        javascript::dialog("该评论已通过审核!","url:0");
    }
    function doCancelexamine() {
        $id=intval($_GET['id']);
        $indexId=intval($_GET['indexId']);
        $id && iCMS_DB::query("UPDATE `#iCMS@__comment` SET `status` = '0' WHERE `id` ='$id'");
        $indexId&&iCMS_DB::query("UPDATE `#iCMS@__article` SET `comments` = comments-1  WHERE `id` ='$indexId'");
        javascript::dialog("该评论已取消审核!","url:0");
    }
    function doDel() {
        $id	=intval($_GET['id']);
        $indexId=intval($_GET['indexId']);
        $id && iCMS_DB::query("DELETE FROM `#iCMS@__comment` WHERE `id` ='$id'");
        $indexId&&iCMS_DB::query("UPDATE `#iCMS@__article` SET `comments` = comments-1  WHERE `id` ='$indexId'");
        javascript::dialog('评论删除成功!','js:parent.$("#cid'.$id.'").remove();parent.iCMS.closeDialog();');
    }
    function dodels() {
        empty($_POST['id']) && javascript::dialog("请选择要操作的评论！");
        foreach($_POST['id'] as $k=>$id) {
            $indexId=$_POST['indexId'][$id];
            iCMS_DB::query("DELETE FROM `#iCMS@__comment` WHERE `id` ='$id'");
            iCMS_DB::query("UPDATE `#iCMS@__article` SET `comments` = comments-1  WHERE `id` ='$indexId'");
            $js[]='#cid'.$id;
        }
        javascript::dialog('评论删除成功!!','js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
    }
    function dostatus1() {
        empty($_POST['id']) && javascript::dialog("请选择要操作的评论！");
        foreach($_POST['id'] as $k=>$id) {
            $indexId=$_POST['indexId'][$id];
            iCMS_DB::query("UPDATE `#iCMS@__comment` SET `status` = '1' WHERE `id` ='$id'");
            iCMS_DB::query("UPDATE `#iCMS@__article` SET `comments` = comments+1  WHERE `id` ='$indexId'");
        }
        javascript::dialog("通过审核!","url:0");
    }
    function dostatus0() {
        empty($_POST['id']) && javascript::dialog("请选择要操作的评论！");
        foreach($_POST['id'] as $k=>$id) {
            $indexId=$_POST['indexId'][$id];
            iCMS_DB::query("UPDATE `#iCMS@__comment` SET `status` = '0' WHERE `id` ='$id'");
            iCMS_DB::query("UPDATE `#iCMS@__article` SET `comments` = comments-1  WHERE `id` ='$indexId'");
        }
        javascript::dialog("取消审核!","url:0");
    }
    function doDefault() {
        Admin::MP(array("menu_index_comment","menu_comment"));
        include_once(iPATH.'include/model.class.php');
        if($_GET['st']=="title") {
            $_GET['keywords'] 	&& $sql[]=" `title` REGEXP '{$_GET['keywords']}'";
        }else if($_GET['st']=="contents") {
            $_GET['keywords'] 	&& $sql[]=" `contents` REGEXP '{$_GET['keywords']}'";
        }
        $_GET['starttime'] 	&& $sql[]=" `addtime`>='".strtotime($_GET['starttime'])."'";
        $_GET['endtime'] 	&& $sql[]=" `addtime`<='".strtotime($_GET['endtime'])."'";
        $_GET['mid'] 	&& $sql[]=" `mid`='".strtotime($_GET['mid'])."'";
        isset($_GET['status'])&&$_GET['status']!='-1' 	&& $sql[]=" `status`='".$_GET['status']."'";
        $where =$sql ? ' where '.implode(' AND ',(array)$sql):'';
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__comment` $where"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"条评论");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__comment` $where order by id DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include admincp::tpl('comment');
    }

}

