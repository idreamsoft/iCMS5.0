<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class link extends AdminCP {
    function doAdd() {
        include	admincp::tpl();
    }
    function dodefault() {
        Admin::MP(array("menu_index_link","menu_link"));
        $maxperpage = 60;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__links`"):(int)$_GET['rowNum'];
        page($total,$maxperpage,'个链接');
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__links` ORDER BY `logo`, `orderNum` ASC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include	admincp::tpl('link');
    }
    function doedit() {
        if(isset($_POST['delete'])) {
            foreach($_POST['delete'] as $k=>$id) {
                iCMS_DB::query("delete from `#iCMS@__links` where `id`='$id'");
            }
            javascript::dialog("已删除!",'url:1');
        }
        foreach($_POST['name'] as $id=>$value) {
            iCMS_DB::query("update `#iCMS@__links` set `sortid`='".$_POST['sortid'][$id]."',`name`='$value',`logo`='".$_POST['logo'][$id]."',`url`='".$_POST['url'][$id]."',`desc`='".$_POST['description'][$id]."',`orderNum`='".$_POST['orderNum'][$id]."' where `id`='$id'");
        }
        javascript::dialog("更新完成!",'url:1');
    }
    function doSave() {
        $sortid	= (int)$_POST['sortid'];
        $name	= dhtmlspecialchars($_POST['name']);
        $url	= dhtmlspecialchars($_POST['url']);
        $desc	= dhtmlspecialchars($_POST['description']);
        $logo	= dhtmlspecialchars($_POST['logo']);
        $orderNum= intval($_POST['orderNum']);
        empty($name)&&javascript::alert('网站名称不能为空!');
        empty($url)&&javascript::alert('网站URL不能为空!');
        strpos($url,'http://')===false && $url='http://'.$url;
        iCMS_DB::query("INSERT INTO `#iCMS@__links` (`sortid`,`name`,`logo`,`desc`,`url`,`orderNum`) VALUES ('$sortid','$name','$logo','$desc','$url','$orderNum')");
        javascript::dialog("添加完成!",'url:'.__SELF__.'?mo=link');
    }
}

