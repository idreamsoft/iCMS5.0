<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class setting extends AdminCP {
    function doDefault() {
        include $this->tpl("setting");
    }
    function doConfig() {
        $this->doDefault();
    }
    function doUrl() {
        $this->doDefault();
    }
    function doCache() {
        $this->doDefault();
    }
    function doTag() {
        $this->doDefault();
    }
    function doAttachments() {
        $this->doDefault();
    }
    function doWatermark() {
        $this->doDefault();
    }
    function doPublish() {
        $this->doDefault();
    }
    function doTime() {
        $this->doDefault();
    }
    function doOther() {
        $this->doDefault();
    }
//    function dopatch() {
//        $this->doDefault();
//    }
    function doUpdate() {
        foreach($_POST AS $key =>$value) {
            updateConfig(dhtmlspecialchars($value),$key);
        }
        CreateConfigFile();
        javascript::dialog('配置已更新!');
    }
}