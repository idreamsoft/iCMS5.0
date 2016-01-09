<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

/**
 * 生成静态类
 *
 * @author coolmoo
 */
class iHtml {
    //put your code here
    function Index($tpl,$name='') {
        global $iCMS;
        $iCMS->mode		= 'CreateHtml';
        $iCMS->htmlConf=array('enable'=>true,'ext'=>$iCMS->config['htmlext']);
        empty($name) && $name='index';
        $filename=FS::path(iPATH.$name.$iCMS->config['htmlext']);
        return FS::write($filename,$iCMS->Index($tpl,$name),false);
    }
    function forum($fid,$p=1,$loop=0,$cpn=0) {
        global $iCMS;
        $rs=$iCMS->getCache('system/forum.cache',$fid);
        if(empty($fid)||strstr($rs['forumRule'],'{PHP}')||$rs['url']||$rs['mode']==0) return false;

        $GLOBALS['page'] = $p;
        $iCMS->mode	= 'CreateHtml';
        $iCMS->htmlConf=array('enable'=>true,'ext'=>($rs['htmlext']?$rs['htmlext']:$iCMS->config['htmlext']));
        $iurl		= $iCMS->iurl('forum',$rs,$p);
        $iCMS->url	= $iurl->hdir.'/'.$iurl->name.'_';
        $html 		= $iCMS->iList($fid,'list');
        FS::mkdir($iurl->dir);
        $GLOBALS['cpn']=$cpn;
        $iCMS->pagesize<$cpn && $GLOBALS['cpn']=$cpn=$iCMS->pagesize;
        $p==1 && FS::write($iurl->dir.'/'.$iurl->file,$iCMS->iList($fid),false);
        FS::write($iurl->dir.'/'.$iurl->name.'_'.$p.$iurl->ext,$html,false);
        $iCMS->pagesize>0 && $p++;
        empty($loop) && $loop=ceil($iCMS->pagesize/10);
        if($p<$cpn||empty($cpn)) {
            if($p<=$iCMS->pagesize && $iCMS->pagesize>0 && $loop==ceil(($iCMS->pagesize-$p)/10)) {
                self::forum($fid,$p,$loop,$cpn);
            }
            return array('name'=>$rs['name'],'page'=>$p,'loop'=>$loop,'pagesize'=>$iCMS->pagesize);
        }else {
            return array('name'=>$rs['name'],'page'=>$p,'loop'=>0,'pagesize'=>$iCMS->pagesize);
        }
    }
    function Article($aid=0,$p=1) {
        global $iCMS;
        if(empty($aid)) return false;

        $iCMS->mode	= 'CreateHtml';
        $iCMS->htmlConf=array('enable'=>true,'ext'=>($rs['htmlext']?$rs['htmlext']:$iCMS->config['htmlext']));
        $html		= $iCMS->Show($aid,$p);
        if(!$html) return false;
        $total		= $iCMS->result->pagetotal;
        FS::mkdir($iCMS->result->iurl->dir);
        FS::write($iCMS->result->iurl->path,$html,false);
        $total>1 && $p<$total && self::Article($aid,$p+1);
        return true;
    }
    function Tag($name,$p=1,$loop=0,$cpn=0) {
        global $iCMS;
        if(empty($name)||strstr($iCMS->config['tagRule'],'{PHP}'))
            return false;

        $rs	= $iCMS->getCache($iCMS->getTagKey($name));
        if(empty($rs)||$rs['status']=="0")return false;
        $F	= $iCMS->getCache('system/forum.cache',$rs['sortid']);
        $iurl		= $iCMS->iurl('tag',array($rs,$F));
        $iCMS->url	= $iurl->hdir.'/'.$iurl->name.'_';
        $GLOBALS['page']=$p;
        $GLOBALS['cpn']=$cpn;
        $iCMS->mode='CreateHtml';
        $iCMS->htmlConf=array('enable'=>true,'ext'=>($rs['htmlext']?$rs['htmlext']:$iCMS->config['htmlext']));
        $html=$iCMS->tag($name);
        FS::mkdir($iurl->dir);
        $p==1 && FS::write($iurl->dir.'/'.$iurl->file,$html,false);
        FS::write($iurl->dir.'/'.$iurl->name.'_'.$p.$iurl->ext,$html,false);
        $iCMS->pagesize>0 && $p++;
        empty($loop) && $loop=ceil($iCMS->pagesize/25);
        if($p<$cpn||empty($cpn)) {
            if($p<=$iCMS->pagesize && $iCMS->pagesize>0 && $loop==ceil(($iCMS->pagesize-$p)/25)) {
                self::Tag($name,$p,$loop,$cpn);
            }
            return array('name'=>$name,'page'=>$p,'loop'=>$loop,'pagesize'=>$iCMS->pagesize);
        }else {
            return array('name'=>$name,'page'=>$p,'loop'=>0,'pagesize'=>$iCMS->pagesize);
        }
    }
    function Content($mid,$id) {
        global $iCMS;
        if(empty($id)||!$iCMS->config['ishtm']||strstr($iCMS->config['aRule'],'{PHP}'))
            return false;

        $iCMS->mode = 'CreateHtml';
        $iCMS->htmlConf=array('enable'=>true,'ext'=>($rs['htmlext']?$rs['htmlext']:$iCMS->config['htmlext']));
        $html	= $iCMS->content($mid,$id);
        FS::mkdir($iCMS->result->iurl->dir);
        FS::write($iCMS->result->iurl->path,$html,false);
        return true;
    }
//    function Page($cid) {
//        global $iCMS;
//        if(empty($cid)||!$iCMS->config['ishtm']||strstr($iCMS->config['pRule'],'{PHP}'))
//            return false;
//        
//        $iCMS->mode='CreateHtml';
//        $html = $iCMS->page('',$cid);
//        FS::mkdir($iCMS->purl->dir);
//        FS::write($iCMS->purl->path,$html,false);
//        return $rs->name;
//    }
}

