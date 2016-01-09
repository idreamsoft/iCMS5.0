<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class keywords extends AdminCP {
    function doadd() {
        include_once(iPATH."include/fckeditor.php");
        $editor = new FCKeditor('replace') ;
        $id=(int)$_GET['id'];
        $rs	= iCMS_DB::getRow("SELECT * FROM `#iCMS@__keywords` where `id`='$id'");
        $editor->Value= $rs->replace;
        include admincp::tpl('keywords.add');
    }
    function dodel() {
        $id=(int)$_GET['id'];
        $id && iCMS_DB::query("DELETE FROM `#iCMS@__keywords` WHERE `id` ='$id'");
        keywords_cache();
        javascript::dialog('成功删除!','js:parent.$("#kid'.$id.'").remove();parent.iCMS.closeDialog();');
    }
    function dodisabled() {
        $id=(int)$_GET['id'];
        $id&&iCMS_DB::query("UPDATE `#iCMS@__keywords` SET `status` = '0'  WHERE `id` ='$id'");
        keywords_cache();
        javascript::dialog('操作完成!','url:0');
    }
    function doopen() {
        $id=(int)$_GET['id'];
        $id&&iCMS_DB::query("UPDATE `#iCMS@__keywords` SET `status` = '1'  WHERE `id` ='$id'");
        keywords_cache();
        javascript::dialog('操作完成!','url:0');
    }
    function dodels() {
    	empty($_POST['id']) && javascript::alert('请选择要操作的关键字');
        foreach($_POST['id'] as $k=>$id) {
            $id && iCMS_DB::query("DELETE FROM `#iCMS@__keywords` WHERE `id` ='$id'");
            $js[]='#kid'.$id;
        }
        keywords_cache();
        javascript::dialog('全部成功删除!','js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
    }
    function doedit() {
        foreach($_POST['name'] as $id=>$value) {
            iCMS_DB::query("update `#iCMS@__keywords` set `keyword`='$value',`replace`='".$_POST['replace'][$id]."' where `id`='$id'");
        }
        keywords_cache();
        javascript::dialog('操作完成!','url:0');

    }
    function dosave() {
        $id	= (int)$_POST['id'];
        $keyword=dhtmlspecialchars($_POST['keyword']);
        $replace = preg_replace ("'<p>(.*?)<\/p>'si",'\\1', $_POST['replace']);
        if(empty($id)) {
            iCMS_DB::query("INSERT IGNORE INTO `#iCMS@__keywords`(`keyword`,`replace`,`addtime`,`status`) values ('$keyword','$replace','".time()."','1')");
        }else {
            iCMS_DB::query("update `#iCMS@__keywords` set `keyword`='$keyword',`replace`='$replace' where id='$id'");
        }
        keywords_cache();
        javascript::dialog('操作完成!','url:'.__SELF__.'?mo=keywords');
    }
    function dodefault() {
        Admin::MP("menu_keywords");
        $_GET['keywords'] && $sql[]=" `keyword` REGEXP '{$_GET['keywords']}'";
        $_GET['replace'] && $sql[]=" `replace` REGEXP '{$_GET['replace']}'";
        isset($_GET['status']) && $_GET['status']!='-1' 	&& $sql[]=" `status`='".$_GET['status']."'";
        $where =$sql ? ' where '.implode(' AND ',(array)$sql):'';
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__keywords` $where"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"个关键字");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__keywords` $where order by id DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include admincp::tpl('keywords');
    }
}
