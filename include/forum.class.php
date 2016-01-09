<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class forum {
    public $_array=array();
    public $forum;
    public $Farray;
    private $parent;

    function __construct($id='',$status="1",$attr="1") {
        global $iCMS;
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__forum` ORDER BY `orderNum` , `fid` ASC",ARRAY_A);
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//var_dump($explain);
//exit;
        foreach((array)$rs AS $row) {
            $this->forum[$row['fid']] =
                    $this->_array[$row['rootid']][$row['fid']] =
                    $this->parent[$row['fid']][$row['rootid']] = $row;
            $this->cacheRootId[$row['rootid']][$row['fid']] = $row['fid'];
            $this->cacheParent[$row['fid']]=$row['rootid'];
        }
        $this->model = $iCMS->getCache('system/model.id');
    }
    
    function cache() {
        global $iCMS;
        foreach((array)$this->forum AS $F) {
            if($F['status']) {
                $iCMS->iCache->delete('system/forum/'.$F['fid']);
                $iCMS->setCache('system/forum/'.$F['fid'],$F,0);
                $urlArray[$F['fid']]=$this->forum[$F['fid']]['iurl']=$iCMS->iurl('forum',$F);
            }else {
                $_forum_hidden_id[]=$F['fid'];
            }
           $Dir2Fid[$F['dir']]=$F['fid'];
        }
        $iCMS->setCache('system/forum.url',$urlArray,0)
        	->setCache('system/forum.cache',$this->forum,0)
        	->setCache('system/forum.array',$this->_array,0)
        	->setCache('system/forum.rootid',$this->cacheRootId,0)
        	->setCache('system/forum.parent',$this->cacheParent,0)
        	->setCache('system/forum.dir2fid',$Dir2Fid,0)
        	->setCache('system/forum.hidden',implode(',',(array)$_forum_hidden_id),0);
    }
    function rootid($fid="0"){
    	$rootid = $this->cacheParent[$fid];
    	if(empty($rootid)){
    		return $fid;
    	}else{
    		return $this->rootid($rootid);
    	}
    }
    function allArray($fid="0", $level = 0) {
        foreach((array)$this->_array[$fid] AS $root=>$F) {
            $F['level']=$level;
            $this->Farray[]=$F;
            $this->_array[$fid] && $this->allArray($F['fid'],$level+1);
        }
    }
    function all($fid =0) {
        foreach((array)$this->_array[$fid] AS $root=>$F) {
            $tr.='<li id="forum_'.$F['fid'].'" class="forumList">';
            $tr.=$this->tr($F,'html');
            $sub=$this->all($F['fid']);
            $sub && $tr.='<ul id="subforum_'.$F['fid'].'">'.$sub.'</ul>';
            $tr.='</li>';
        }
        return $tr;
    }
    function json($fid =0,$op='html'){
    	$fid=='source' && $fid=0;
        foreach((array)$this->_array[$fid] AS $root=>$F) {
	    	if(Admin::CP($F['fid'])) {
	        	$a=array();
	        	$a['text']=$this->tr($F,$op);
	            if($this->hasChildren($F['fid'])){
	            	$a['id']=$F['fid'];
	            	$a['hasChildren']=true;
	            }
	            $tr[]=$a;
		    }
        }
        return $tr?json_encode($tr):'[]';
    }
    function hasChildren($fid){
    	return $this->_array[$fid]?true:false;
    }
    
    function row($fid =0) {
        foreach((array)$this->_array[$fid] AS $F) {
            $tr.='<li id="forum_'.$F['fid'].'" class="forumList" fid="'.$F['fid'].'">';
            $tr.=$this->tr($F);
            $tr.='<ul id="subforum_'.$F['fid'].'"></ul></li>';
        }
        return $tr;
    }
    function tr($F,$op='html') {
        global $iCMS;
        if(Admin::CP($F['fid'])) {
            $readonly='  class="txt"';
            $FAction=true;
        }else {
            $readonly=' readonly="true" class="readonly"';
            $FAction=false;
            return '';
//			if($Q=='all')return false;
        }
        $this->model[$F['modelid']] && $model=$this->model[$F['modelid']];
        if($op=='text'){
        	if($FAction) {
        		if(empty($model)) {
        			return '<a href="'.__ADMINCP__.'=article&do=manage&fid='.$F['fid'].'&sub=on" target="main">'.$F['name'].'</a>('.$F['count'].')';
        		}else {
                	return '<a href="'.__ADMINCP__.'=content&do=manage&table='.$model['table'].'&mid='.$F['mid'].'&fid='.$F['fid'].'&sub=on" target="main">'.$F['name'].'</a>('.$F['count'].')';
        		}
        	}
        }
        $name=empty($model)?'文章':'内容';
        $tr='<div class="ordernum"><input'.$readonly.' type="text" name="orderNum['.$F['fid'].']" value="'.$F['orderNum'].'" style="width:32px;"/></div>';
        $tr.='<div class="name">';
        $tr.=$ls.'<input'.$readonly.' type="text" name="name['.$F['fid'].']" value="'.$F['name'].'"';
        $F['rootid']==0 && $tr.='style="font-weight:bold"';
        $tr.='/>[FID:<a href="'.$iCMS->iurl('forum',$F)->href.'" target="_blank">'.$F['fid'].'</a>]';
        $F['url'] && $tr.='[外部链接]';
        $F['mode'] && $F['domain'] && $tr.='[绑定域名]';
        $tr.='('.$F['count'].')</div><div class="operation">';
        if($FAction) {
            $tr.='<a href="'.__ADMINCP__.'=forums&do=add&rootid='.$F['fid'].'" class="addtr">子版块</a> |';
            if(empty($model)) {
                $F['attr'] && $tr.=' <a href="'.__ADMINCP__.'=article&do=add&fid='.$F['fid'].'" class="addtr">文章</a>|';
                $tr.='<a href="'.__ADMINCP__.'=article&do=manage&fid='.$F['fid'].'&sub=on" class="act">文章管理</a>|';
                $tr.='<a href="'.__ADMINCP__.'=article&do=manage&fid='.$F['fid'].'&type=draft" class="act">草稿箱</a>|';
                $tr.='<a href="'.__ADMINCP__.'=article&do=manage&fid='.$F['fid'].'&type=draft&act=trash" class="act">回收站</a>|';
            }else {
                $F['attr'] && $tr.=' <a href="'.__ADMINCP__.'=content&do=add&table='.$model['table'].'&mid='.$F['mid'].'&fid='.$F['fid'].'"  class="addtr">内容</a>|';
                $tr.='<a href="'.__ADMINCP__.'=content&do=manage&table='.$model['table'].'&mid='.$F['mid'].'&fid='.$F['fid'].'&sub=on" class="act">内容管理</a>|';
                $tr.='<a href="'.__ADMINCP__.'=content&do=manage&table='.$model['table'].'&mid='.$F['mid'].'&fid='.$F['fid'].'&type=draft" class="act">草稿箱</a>|';
                $tr.='<a href="'.__ADMINCP__.'=content&do=manage&table='.$model['table'].'&mid='.$F['mid'].'&fid='.$F['fid'].'&type=draft&act=trash" class="act">回收站</a>|';
            }
            $F['mode'] && $tr.="<a href='".__ADMINCP__."=html&do=CreateForum&fid={$F['fid']}&time=0&p=1' class='act' target='iCMS_FRAME'>生成HTML</a>|";
            $tr.='<a href="'.__ADMINCP__.'=forums&do=add&fid='.$F['fid'].'" class="act" title="编辑版块设置">编辑</a>|<a href="'.__ADMINCP__.'=forums&do=del&fid='.$F['fid'].'" onClick="return confirm(\'确定要删除此版块和版块下的所有文章?\');" class="act" target="iCMS_FRAME">删除</a>';
        }else {
            $tr.='无权限';
        }
        $tr.='</div>';
        return $tr;
    }
    function fid($fid = "0",$attr=NULL) {
        foreach((array)$this->_array[$fid] AS $root=>$F) {
            if(Admin::CP($F['fid']) && empty($F['url'])) {
                if($attr===NULL) {
                    $ID.=$F['fid'].",";
                }else {
                    $attr==$F['attr'] && $ID.=$F['fid'].",";
                }
            }
            $ID.=$this->fid($F['fid'],$attr);
        }
//    	var_dump(array_intersect($p,Admin::cpower));
        return $ID;
    }
    function AJAXid($fid = "0") {
        foreach((array)$this->_array[$fid] AS $root=>$F) {
            $ID.=$F['fid'].",".$this->fid($F['fid']);
        }
        return substr($ID,0,-1);
    }
    function select($currentid="0",$fid="0",$level = 1,$attr=NULL,$mid=NULL,$url=NULL) {
        foreach((array)$this->_array[$fid] AS $root=>$F) {
            if($F['modelid']==$mid||$mid===NULL) {
                if(Admin::CP($F['fid'])) {
                    $t=$level=='1'?"":"├ ";
                    $selected=($currentid==$F['fid'])?"selected='selected'":"";
                    $text=str_repeat("│　", $level-1).$t.$F['name']."[FID:{$F['fid']}]";
                    if(empty($F['url'])){
                        if($attr===NULL){
                            if($F['attr']) {
                                $option.="<option value='{$F['fid']}' $selected>{$text}</option>";
                            }else {
                                $option.="<optgroup label=\"{$text}\"></optgroup>";
                            }
                        }elseif($attr=='all') {
                            $option.="<option value='{$F['fid']}' $selected>{$text}</option>";
                        }else {
                            $attr==$F['attr'] && $option.="<option value='{$F['fid']}' $selected>{$text}</option>";
                        }
                    }else{
                        if($url){
                            $option.="<option value='{$F['fid']}' $selected>{$text}</option>";
                        }else {
                            $option.="<optgroup label=\"{$text}\"></optgroup>";
                        }
                    }
                }
                $option.=$this->select($currentid,$F['fid'],$level+1,$attr,$mid);
            }
        }
        return $option;
    }
}