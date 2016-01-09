<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
function iCMS_list($vars,&$iCMS){
    if($vars['loop']=="rel" && empty($vars['id'])){
        return false;
    }
    $whereSQL    = " status='1'";
    $_cache        = $iCMS->getCache(array('system/forum.cache','system/forum.hidden'));
    $_cache['system/forum.hidden']&&  $whereSQL.=getSQL($_cache['system/forum.hidden'],'fid','not');
    $maxperpage=isset($vars['row'])?(int)$vars['row']:10;
    $cacheTime =isset($vars['time'])?(int)$vars['time']:-1;
    isset($vars['userid'])    &&     $whereSQL.=" AND `userid`='{$vars['userid']}'";
    isset($vars['author'])    &&     $whereSQL.=" AND `author`='{$vars['author']}'";
    isset($vars['top'])		&& 	$whereSQL.=" AND `top`='"._int($vars['top'])."'";
    $vars['call']=='user'    &&     $whereSQL.=" AND `postype`='0'";
    $vars['call']=='admin'    &&     $whereSQL.=" AND `postype`='1'";
    $forum = $_cache['system/forum.cache'];
    if(isset($vars['fid!'])){
        $_Nfid=getfids($vars['fid!']);
         $_Nfid && $Nfids[]=$_Nfid;
           $vars['sub']=='all' && $Nfids[]=$vars['fid!'];
        $ids=($Nfids && $vars['sub']=='all')?implode(',',$Nfids):$vars['fid!'];
        $whereSQL.= getSQL($ids,'fid','not');
    }
    if(isset($vars['fid'])){
        $_fid = getfids($vars['fid']);
        $_fid && $fids[]=$_fid;
        $vars['sub']=='all'&& $fids[]=$vars['fid'];
        $ids=($fids && $vars['sub']=='all')?implode(',',$fids):$vars['fid'];
        $whereSQL.= getSQL($ids,'fid');
    }
    isset($vars['type']) && $whereSQL.= " AND `type` ='{$vars['type']}'";
    $vars['id'] && $whereSQL.= getSQL($vars['id'],'id');
    $vars['id!'] && $whereSQL.= getSQL($vars['id!'],'id','not');
    $by=$vars['by']=="ASC"?"ASC":"DESC";
    if($vars['keywords']){
        if(strpos($vars['keywords'],',')===false){
             $vars['keywords']=str_replace(array('%','_'),array('\%','\_'),$vars['keywords']);
            $whereSQL.= " AND CONCAT(title,keywords,description) like '%".addslashes($vars['keywords'])."%'";
           }else{
            $kw=explode(',',$vars['keywords']);
            foreach($kw AS $v){
                $keywords.=addslashes($v)."|";
            }
            $keywords=substr($keywords,0,-1);
            $whereSQL.= "  And CONCAT(title,keywords,description) REGEXP '$keywords' ";
        }
    }
    isset($vars['pic']) && $whereSQL.= " AND `isPic`='1'";
    switch ($vars['orderby']) {
        case "id":        $orderSQL=" ORDER BY `id` $by";            break;
        case "hot":        $orderSQL=" ORDER BY `hits` $by";        break;
        case "comment":$orderSQL=" ORDER BY `comments` $by";    break;
        case "pubdate":    $orderSQL=" ORDER BY `pubdate` $by";    break;
        case "disorder":$orderSQL=" ORDER BY `orderNum` $by";    break;
//        case "rand":    $orderSQL=" ORDER BY rand() $by";    break;
        case "top":        $orderSQL=" ORDER BY `top`,`orderNum` ASC";break;
        default:        $orderSQL=" ORDER BY `id` DESC";
    }
    isset($vars['date']) && list($iCMS->date['y'],$iCMS->date['m'],$iCMS->date['d'])=explode('-',$vars['date']);
    if($iCMS->date){
        $day    = empty($iCMS->date['d'])?'01':$iCMS->date['d'];
        $start    = strtotime($iCMS->date['y'].$iCMS->date['m'].$day);
        $end    = empty($iCMS->date['d'])?$start+86400*$iCMS->date['total']:$start+86400;
        $whereSQL.=" AND `pubdate`<='{$end}' AND `pubdate`>='{$start}'";
    }else{
        isset($vars['startdate'])    && $whereSQL.=" AND `pubdate`>='".strtotime($vars['startdate'])."'";
        isset($vars['enddate'])     && $whereSQL.=" AND `pubdate`<='".strtotime($vars['enddate'])."'";
    }
    isset($vars['where'])        && $whereSQL.=$vars['where'];
    if($vars['action']=='search'){
        $whereSQL.=$iCMS->actionSQL;
//    }elseif($vars['action']=='vlink'){
//        $this->countSQL    = 'SELECT count(#iCMS@__article.id) FROM `#iCMS@__article`,`#iCMS@__vlink` WHERE #iCMS@__article.id = `indexId` AND `fid`=\''.$vars['fid'].'\' AND';
//        $this->selectSQL= 'SELECT #iCMS@__article.* FROM `#iCMS@__article`,`#iCMS@__vlink` WHERE #iCMS@__article.id = `indexId` AND `fid`=\''.$vars['fid'].'\' AND';
//         $orderSQL        = " ORDER BY #iCMS@__vlink.indexId $by";
   }elseif($vars['action']=='tag'){
              if(empty($vars['tag'])) return false;
              if(is_array($vars['tag'])){
                  $_tCache=$vars['tag'];
              }else{
            $_tCache=$iCMS->getCache($iCMS->getTagKey($vars['tag']));
        }
        if($_tCache['id']){
            $tidSQL = 'AND `tid`=\''.$_tCache['id'].'\'';
        }else{
            if($_tCache)foreach($_tCache AS $_tag){
                if($_tag)$_tids[]=$_tag['id'];
            }
            if(empty($_tids))return false;
            $tidSQL = 'AND `tid` in ('.implode(',',$_tids).')';
        }
        $countSQL    = 'SELECT count(#iCMS@__article.id) FROM `#iCMS@__article`,`#iCMS@__taglist` WHERE #iCMS@__article.id = `indexId` '.$tidSQL.' AND';
        $selectSQL    = 'SELECT #iCMS@__article.* FROM `#iCMS@__article`,`#iCMS@__taglist` WHERE #iCMS@__article.id = `indexId` '.$tidSQL.' AND';
        $orderSQL    = " ORDER BY #iCMS@__taglist.indexId $by";
	    $vars['indexId!'] && $whereSQL.= getSQL($vars['indexId!'],'#iCMS@__article.id','not');
    }
    $offset    = 0;
    if($vars['page']){
        empty($countSQL) && $countSQL = "SELECT count(*) FROM `#iCMS@__article` WHERE";
        $total    = iCMS_DB::getValue($countSQL." {$whereSQL}");
//        echo iCMS_DB::last_query;
        $pagenav= isset($vars['pagenav'])?$vars['pagenav']:"pagenav";
        $pnstyle= isset($vars['pnstyle'])?$vars['pnstyle']:0;
        $offset    = $iCMS->multi(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>$iCMS->language('page:list'),'url'=>$iCMS->url,'nowindex'=>$GLOBALS['page'],'pagenav'=>$pagenav,'pnstyle'=>$pnstyle));
//        $GLOBALS['cpn'] && $iCMS->_vars['pagenav'].='<span><a class="page_more" href="more.php?fid='.$ids.'" target="_self">'.$iCMS->language('page:more').'</a></span>';
        //$iCMS->addto($pagenav,"----------------");
    }
    $iscache=true;
    if($vars['cache']==false||isset($vars['page'])){
        $iscache=false;
        $rs = '';
    }else{
        $cacheName='list/'.md5($whereSQL.$orderSQL.$maxperpage);
        $rs=$iCMS->getCache($cacheName);
    }
    if(empty($rs)){
        empty($selectSQL) && $selectSQL="SELECT id,fid,title,stitle,clink,url,source,author,editor,userid,pic,keywords,tags,description,related,pubdate,hits,good,bad,comments,top FROM `#iCMS@__article` WHERE";
        $rs=iCMS_DB::getArray($selectSQL." {$whereSQL} {$orderSQL} LIMIT {$offset} , {$maxperpage}");
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//var_dump($explain);
        $_count=count($rs);
        for ($i=0;$i<$_count;$i++){
            $rs[$i]['pic'] && $rs[$i]['pic']=uploadpath($rs[$i]['pic'],'+http');
            $F=$forum[$rs[$i]['fid']];
            $rs[$i]['sort']['name']=$F['name'];
            $rs[$i]['sort']['url']=$iCMS->iurl('forum',$F)->href;
            $rs[$i]['sort']['link']="<a href='{$rs[$i]['sort']['url']}'>{$rs[$i]['sort']['name']}</a>";
            $rs[$i]['url']=$iCMS->iurl('show',array($rs[$i],$F))->href;
            $rs[$i]['link']="<a href='{$rs[$i]['url']}'>{$rs[$i]['title']}</a>";
            if($rs[$i]['tags'] && isset($vars['tag'])){
                $tagarray=explode(',',$rs[$i]['tags']);
                foreach($tagarray AS $tk=>$tag){
                    $t = $iCMS->getTag($tag);
                    if($t){
                        $rs[$i]['tag'][$tk]['name']=$tag;
                        $rs[$i]['tag'][$tk]['url']=$t['url'];
                        $rs[$i]['taglink'].='<a href="'.$rs[$i]['tag'][$tk]['url'].'" class="tag" target="_self">'.$tag.'</a> ';
                    }
                }
            }
        }
        $iscache && $iCMS->SetCache($cacheName,$rs,$cacheTime);
    }
    return $rs;
}
?>
