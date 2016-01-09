<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
function iCMS_plugins_message($vars,&$iCMS) {
    if(isset($vars['call'])) {
        if($vars['call']=='form') {
            echo $iCMS->output('form',plugin::tpl('message'));
            exit;
        }
    }else {
        $maxperpage =isset($vars['row'])?(int)$vars['row']:"20";
        $cacheTime =isset($vars['time'])?(int)$vars['time']:-1;
        $offset	= 0;
        if($vars['page']) {
            $total=iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__plugins_message` WHERE `status`='0'");
            $pagenav= isset($vars['pagenav'])?$vars['pagenav']:"pagenav";
            $pnstyle= isset($vars['pnstyle'])?$vars['pnstyle']:0;
            $offset	= $iCMS->multi(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>$iCMS->language('page:message'),'url'=>$iCMS->url,'nowindex'=>$GLOBALS['page'],'pagenav'=>$pagenav,'pnstyle'=>$pnstyle));
        }

        $iscache=true;
        if($vars['cache']==false||isset($vars['page'])) {
            $iscache=false;
            $rs = '';
        }else {
            $cacheName='message/cache';
            $rs=$iCMS->getCache($cacheName);
        }

        if(empty($rs)) {
            $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__plugins_message` WHERE `status`='0' order by `id` DESC LIMIT {$offset},{$maxperpage}");
            for ($i=0;$i<count($rs);$i++) {
                if($rs[$i]['reply']) {
                    $rs[$i]['reply']='<strong>'.$iCMS->language('reply:admin').'</strong>'.$rs[$i]['reply'];
                }
            }
            $iscache && $iCMS->SetCache($cacheName,$rs,$cacheTime);
        }
        return $rs;
    }
}
