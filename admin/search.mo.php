<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class search extends AdminCP {
    function doadd() {
        $id=(int)$_GET['id'];
        $id && $rs = iCMS_DB::getRow("SELECT * FROM `#iCMS@__search` where `id`='$id'");
        include admincp::tpl('search.add');
    }
    function dodel() {
        $id=(int)$_GET['id'];
        $id && iCMS_DB::query("DELETE FROM `#iCMS@__search` WHERE `id` ='$id'");
        search_cache();
        javascript::dialog('操作完成!','url:0');
    }
    function dodisabled() {
        $id=(int)$_GET['id'];
        $id&&iCMS_DB::query("UPDATE `#iCMS@__search` SET `status` = '0'  WHERE `id` ='$id'");
        search_cache();
        javascript::dialog('操作完成!','url:0');
    }
    function doopen() {
        $id=(int)$_GET['id'];
        $id&&iCMS_DB::query("UPDATE `#iCMS@__search` SET `status` = '1'  WHERE `id` ='$id'");
        search_cache();
        javascript::dialog('操作完成!','url:0');
    }
    function dodels() {
        empty($_POST['id']) && javascript::alert('请选择要操作项');
        foreach($_POST['id'] as $k=>$id) {
            $id && iCMS_DB::query("DELETE FROM `#iCMS@__search` WHERE `id` ='$id'");
        }
        search_cache();
        javascript::dialog('操作完成!','url:0');

    }
    function doedit() {
        foreach($_POST['search'] as $id=>$value) {
            $value=str_replace(array('%','_'),array('\%','\_'),$value);
            iCMS_DB::query("update `#iCMS@__search` set `search`='$value',`times`='".$_POST['times'][$id]."' where `id`='$id'");
        }
        search_cache();
        javascript::dialog('操作完成!','url:0');

    }
    function dosave() {
        $id	= (int)$_POST['id'];
        $search=dhtmlspecialchars($_POST['search']);
        $search=str_replace(array('%','_'),array('\%','\_'),$search);
        $times=(int)$_POST['times'];
        if(empty($id)) {
            iCMS_DB::query("insert into `#iCMS@__search`(`search`,`times`,`addtime`) values ('$search','$times','".time()."')");
        }else {
            iCMS_DB::query("update `#iCMS@__search` set `search`='$search',`times`='$times' where id='$id'");
        }
        search_cache();
        javascript::dialog('操作完成!','url:'.__SELF__.'?mo=search');
    }
    function dodefault() {
        Admin::MP("menu_search");
        $maxperpage =20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__search`"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"个关键字");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__search` order by id DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include admincp::tpl('search');
    }
}
