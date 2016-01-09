<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class contentype extends AdminCP {
    function init() {
        $this->array = $this->iCMS->getCache('system/contentype');
    }
    function doDel() {
        $this->init();
        $id=(int)$_GET['id'];
        unset ($this->array[$id]);
        $this->iCMS->setCache('system/contentype',$this->array,0);
        javascript::dialog('删除成功!<br />3秒后返回','url:0');
    }
    function doDels() {
    	$this->init();
        foreach($_POST['id'] as $k=>$id) {
            unset ($this->array[$id]);
        }
        $this->iCMS->setCache('system/contentype',$this->array,0);
        javascript::dialog('删除成功!<br />3秒后返回','url:0');
    }
    function doEdit() {
    	if(empty($_POST['name']))return false;
    	
        $this->init();
        foreach((array)$_POST['name'] as $id=>$value) {
            $this->array[$id]=array('id'=>$id,'name'=>$value,'type'=>$_POST['type'][$id],'val'=>$_POST['val'][$id]);
        }
        $this->iCMS->setCache('system/contentype',$this->array,0);
        javascript::dialog('更新完成!<br />3秒后返回','url:0');
    }
    function doAdd() {
    	$this->init();
        $name=dhtmlspecialchars($_POST['name']);
        $type=dhtmlspecialchars($_POST['type']);
        $val=intval($_POST['val']);
        $end=end($this->array);
        $id=$end['id']+1;
        $this->array[$id]=array('id'=>$id,'name'=>$name,'type'=>$type,'val'=>$val);
        $this->iCMS->setCache('system/contentype',$this->array,0);
        javascript::dialog('添加完成!<br />3秒后返回','url:'.__SELF__.'?mo=contentype');
    }
    function doDefault() {
    	$this->init();
        Admin::MP("menu_contentype");
        include admincp::tpl('contentype');
    }
}

