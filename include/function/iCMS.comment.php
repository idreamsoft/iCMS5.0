<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
require_once(iPATH.'/include/ubb.fun.php');
function iCMS_comment($vars,&$iCMS){
	if(!$iCMS->config['iscomment']){return false;}
	$iCMS->assign('commentInfo',array('indexId'=>(int)$iCMS->commentInfo['indexId'],'sortId'=>(int)$iCMS->commentInfo['sortId'],'title'=>$iCMS->commentInfo['title'],'mId'=>$iCMS->commentInfo['mId']));
	if(isset($vars['call'])){
		if(in_array($vars['call'],array('js','frame'))){
			echo $iCMS->iPrint("iTPL","comment_show_".$vars['call']);
		}
	}elseif(isset($vars['loop'])){
		$cacheTime =isset($vars['time'])?(int)$vars['time']:-1;
    	$maxperpage =isset($vars['row'])?(int)$vars['row']:"10";
    	$whereSQL="`mid`='$mid' and `status`='1'";
    	isset($vars['sortid']) && $whereSQL.=" and `sortId`='".(int)$vars['sortid']."'";
//    	($iCMS->comment['indexId'] && $vars['type']!='all') && $whereSQL.=" AND `indexId`='".(int)$iCMS->comment['indexId']."'";
    	($vars['indexid'] && $vars['type']!='all') && $whereSQL.=" AND `indexId`='".(int)$vars['indexid']."'";
		switch ($vars['orderby']) {
			case "hot":	$orderSQL=" ORDER BY up+against DESC";	break;
			case "new":	$orderSQL=" ORDER BY `addtime` DESC";		break;
			default:	$orderSQL=" ORDER BY `id` DESC";
		}
		$total=iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__comment` WHERE {$whereSQL}");
		$offset	=0;
		if($vars['page']){
			$pagenav= isset($vars['pagenav'])?$vars['pagenav']:"pagenav";
			$pnstyle= isset($vars['pnstyle'])?$vars['pnstyle']:0;
			$offset	= $iCMS->multi(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>$iCMS->language('page:comment'),'url'=>$iCMS->url,'nowindex'=>$GLOBALS['page'],'pagenav'=>$pagenav,'pnstyle'=>$pnstyle));
		}
		$iscache=true;
		if($vars['cache']==false||isset($vars['page'])){
			$iscache=false;
			$rs = '';
		}else{
			$cacheName='comment/'.md5($whereSQL.$orderSQL);
			$rs=$iCMS->getCache($cacheName);
		}
		if(empty($rs)){
		//	include_once(iPATH.'include/ubb.fun.php');
			$rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__comment` WHERE {$whereSQL}{$orderSQL} LIMIT {$offset},{$maxperpage}");
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//var_dump($explain);
			$_count=count($rs);
			$ln=($GLOBALS['page']-1)<0?0:$GLOBALS['page']-1;
			for ($i=0;$i<$_count;$i++){
				$rs[$i]['url']=$iCMS->config['publicURL'].'/comment.php?indexId='.$rs[$i]['indexId'].'&mId='.$rs[$i]['mid'].'&sortId='.$rs[$i]['sortId'];
				$rs[$i]['lou']=$total-($i+$ln*$maxperpage);
				$rs[$i]['content']=str_replace("\r",'<br />',$rs[$i]['contents']);
//				$rs[$i]['content']=ubb($rs[$i]['contents']);
				$rs[$i]['contents']=$rs[$i]['quote']?cQuote($rs[$i]['quote']):'';
				$rs[$i]['contents'].=$rs[$i]['content'];
//				if($rs[$i]['reply']){
//					$reply=explode('||',$rs[$i]['reply']);
//					$rs[$i]['reply']=$reply[0]=='admin'?'<strong>'.$iCMS->language('reply:admin').'</strong>'.$reply[1]:'<strong>'.$iCMS->language('reply:author').'</strong>'.$reply[1];
//				}
			}
			$iscache && $iCMS->SetCache($cacheName,$rs,$cacheTime);
		}
		return $rs;
	}else{
		$vars['width']=is_numeric($vars['width'])?$vars['width'].'px':$vars['width'];
		$iCMS->append('commentInfo',array('anonymous'=>$iCMS->config['anonymous'],'anonymousname'=>$iCMS->config['anonymousname'],'width'=>($vars['width']?$vars['width']:'98%'),'height'=>($vars['height']?$vars['height']:'140')),true);
		echo $iCMS->iPrint("iTPL","comment.form");
	}
}
function cQuote($id=0,$i=0){
	global $iCMS;
	if($id){
		$i++;
		$rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__comment` WHERE  `id`='$id'");
		$text='<div class="quote">';
		$i<52 && $rs->quote && $text.=cQuote($rs->quote,$i);
		$text.='<span class="floor">#'.($rs->floor+1).'</span>';
//		$text.='<span>----- 以下引用 <strong><em>'.$rs->username.'</em></strong> 于 '.get_date($rs->addtime,'Y-m-d H:i').' 的发言 -----</span><p>'.$rs->contents. '</p>';
//		$text.='<span>'.$rs->username.'的原贴：</span><p>'.ubb($rs->contents). '</p>';
		$text.='<span>'.$rs->username.'的原贴：</span><p>'.str_replace("\r",'<br />',$rs->contents). '</p>';
//		$text.='<div class="comment-action"><span><a href=\'javascript:iCMS.digg("up",'.$rs->indexId.','.$rs->id.')\'>支持[+<span id="up_'.$rs->id.'">'.$rs->up.'</span>]</a></span><span><a href=\'javascript:iCMS.digg("down",'.$rs->indexId.','.$rs->id.')\'>反对[-<span id="down_'.$rs->id.'">'.$rs->down.'</span>]</a></span><span><a href=\'javascript:iCMS.quote('.$rs->id.','.$rs->floor.')\' id="quote'.$rs->id.'">回复</a> <a href=\'javascript:iCMS.unquote('.$rs->id.')\' id="unquote'.$rs->id.'" style="display:none;">取消回复</a></span></div>';
		$text.='</div>';
		return $text;
	}
}
?>