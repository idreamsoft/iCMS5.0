<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
function throwException($msg, $code) {
    trigger_error($msg . '(' . $code . ')');
}
//--------------------------------------------------------------------------------
//翻页函数
Function page($totle,$displaypg=20,$strunit="",$url='',$target='') {
    global $page,$firstcount,$pagenav;
    $firstcount	=intval($firstcount);
    $displaypg	=intval($displaypg);
    $page	=$page?intval($page):1;
    $lastpg	=ceil($totle/$displaypg); //最后页，也是总页数
    $page	=min($lastpg,$page);
    $prepg	=(($page-1)<0)?"0":$page-1; //上一页
    $nextpg	=($page==$lastpg ? 0 : $page+1); //下一页
    $firstcount=($page-1)*$displaypg;
    $firstcount<0 && $firstcount=0;
    $REQUEST_URI=$_SERVER['QUERY_STRING']?$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']:$_SERVER['PHP_SELF'];
    !$url && $url=$_SERVER["REQUEST_URI"]?$_SERVER["REQUEST_URI"]:$REQUEST_URI;
    $url.="&rowNum=".$totle;
    $_parse_url	= parse_url($url);
    parse_str($_parse_url["query"], $output);
    $output		= array_unique($output);
    $url_query	= http_build_query($output); //单独取出URL的查询字串
    if($url_query) {
        $url_query=preg_replace("/(^|&)page=$page/","",$url_query);
        $url=str_replace($_parse_url["query"],$url_query,$url);
        $url.=$url_query?"&page":"page";
    }else {
        $url.="?page";
    }
    $pagenav=" <a href='$url=1' target='_self'>首页</a> ";
    $pagenav.=$prepg?" <a href='$url=$prepg' target='_self'>上一页</a> ":" 上一页 ";
    $flag=0;
    for($i=$page-2;$i<=$page-1;$i++) {
        if($i<1) continue;
        $pagenav.="<a href='$url=$i' target='_self'>[$i]</a>";
    }
    $pagenav.="&nbsp;<b>$page</b>&nbsp;";
    for($i=$page+1;$i<=$lastpg;$i++) {
        $pagenav.="<a href='$url=$i' target='_self'>[$i]</a>";
        $flag++;
        if($flag==4) break;
    }
    $pagenav.=$nextpg?" <a href='$url=$nextpg' target='_self'>下一页</a> ":" 下一页 ";
    $pagenav.=" <a href='$url=$lastpg' target='_self'>末页</a> ";
    $pagenav.="共{$totle}{$strunit}，{$displaypg}{$strunit}/页 ";
    $pagenav.=" 共{$lastpg}页";
    for($i=1;$i<=$lastpg;$i=$i+5) {
        $s=$i==$page?' selected="selected"':'';
        $select.="<option value=\"$i\"{$s}>$i</option>";
    }
    if($lastpg>200) {
        $pagenav.=" 跳到 <input name=\"pageselect\" type=\"text\" id=\"pageselect\" style=\"width:36px\" />页 <input type=\"button\" onClick=\"window.location='{$url}='+$('#pageselect').val();\" value=\"跳转\" />";
    }else {
        $pagenav.=" 跳到 <select name=\"pageselect\" id=\"pageselect\" onchange=\"window.location='{$url}='+this.value\">{$select}</select>页";
    }
    (int)$lastpg<2 &&$pagenav='';
}
//-------------------------------------------
function delArticle($id,$uid='-1',$postype='1') {
    global $iCMS;
    $sql=$uid!="-1"?"and `userid`='$uid' and `postype`='$postype'":"";
    $id=(int)$id;
    $art=iCMS_DB::getRow("SELECT * FROM `#iCMS@__article` WHERE id='$id' {$sql} Limit 1");
    if($art->pic) {
        $usePic=iCMS_DB::getValue("SELECT id FROM `#iCMS@__article` WHERE `pic`='{$art->pic}' and `id`<>'$id'");
       if(empty($usePic)) {
            $thumbfilepath=gethumb($art->pic,'','',false,true,true);
            FS::del(uploadpath($art->pic,'+iPATH'));
            $msg.= $art->pic.' 文件删除…<span style=\'color:green;\'>√</span><br />';
            if($thumbfilepath)foreach($thumbfilepath as $wh=>$fp) {
                    FS::del(uploadpath($fp,'+iPATH'));
                    $msg.= '缩略图 '.$wh.' 文件删除…<span style=\'color:green;\'>√</span><br />';
                }
            $filename=FS::info($art->pic)->filename;
            iCMS_DB::query("DELETE FROM `#iCMS@__file` WHERE `filename` = '{$filename}'");
            $msg.= $art->pic.' 数据删除…<span style=\'color:green;\'>√</span><br />';
        }else {
            $msg.= $art->pic.'文件 其它文章正在使用,请到文件管理删除…<span style=\'color:green;\'>×</span><br />';
        }
    }
    $forum	= $iCMS->getCache('system/forum.cache',$art->fid);
    $body	= iCMS_DB::getValue("SELECT `body` FROM `#iCMS@__article_data` WHERE aid='$id' Limit 1");
    if($forum['mode'] && strstr($forum['contentRule'],'{PHP}')===false && empty($art->url)) {
        $bArray=explode('<!--iCMS.PageBreak-->',$body);
        $total=count($bArray);
        for($i=1;$i<=$total;$i++) {
            $iurl=$iCMS->iurl('show',array((array)$art,$forum),$i);
            FS::del($iurl->path);
            $msg.=$iurl->path.' 静态文件删除…<span style=\'color:green;\'>√</span><br />';
        }
    }
    $frs=iCMS_DB::getArray("SELECT `filename`,`path`,`ext` FROM `#iCMS@__file` WHERE `aid`='$id'");
    for($i=0;$i<count($frs);$i++) {
        if(!empty($frs[$i])) {
        	$path=$frs[$i]['path'].'/'.$frs[$i]['filename'].'.'.$frs[$i]['ext'];
            FS::del(uploadpath($frs[$i]['path'],'+iPATH'));
            $msg.=$path.' 文件删除…<span style=\'color:green;\'>√</span><br />';
        }
    }
    if($art->tags) {
        $tagArray=explode(",",$art->tags);
        foreach($tagArray AS $k=>$v) {
            if(iCMS_DB::getValue("SELECT `count` FROM `#iCMS@__tags` WHERE `name`='$v'")=="1") {
                iCMS_DB::query("DELETE FROM `#iCMS@__tags`  WHERE `name`='$v'");
                $iCMS->iCache->delete($iCMS->getTagKey($v));
            }else {
                iCMS_DB::query("UPDATE `#iCMS@__tags` SET  `count`=count-1 ,`updatetime`='".time()."' WHERE `name`='$v'");
            }
        }
        iCMS_DB::query("DELETE FROM `#iCMS@__taglist` WHERE indexId='$id' AND modelId='0'");
        $msg.='标签更新…<span style=\'color:green;\'>√</span><br />';
    }
    iCMS_DB::query("DELETE FROM `#iCMS@__file` WHERE `aid`='$id'");
    $msg.='相关文件数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("DELETE FROM `#iCMS@__comment` WHERE indexId='$id' and mid='0'");
    $msg.='评论数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("DELETE FROM `#iCMS@__article` WHERE id='$id'");
    iCMS_DB::query("DELETE FROM `#iCMS@__article_data` WHERE `id`='$id'");
    iCMS_DB::query("DELETE FROM `#iCMS@__vlink` WHERE indexId='$id' AND modelId='0'");
    $msg.='文章数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count-1 WHERE `fid` ='{$art->fid}' LIMIT 1");
    $msg.='栏目数据更新…<span style=\'color:green;\'>√</span><br />';
    $msg.='删除完成…<span style=\'color:green;\'>√</span><hr />';
    return $msg;
}
function addtags($tags,$indexId="0",$sortid='0') {
    $a	= explode(',',$tags);
    $c	= count($a);
    for($i=0;$i<$c;$i++) {
        TagUI($a[$i],$indexId,$sortid);
    }
}
function TagUI($tag,$indexId="0",$sortid='0') {
    global $iCMS,$Admin;
    if(empty($tag)) return;
    $tid	= iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__tags` WHERE `name`='$tag'");
    if(empty($tid) && $tag!="") {
        include_once iPATH.'include/cn.class.php';
        $link=CN::pinyin($tag,$iCMS->config['CLsplit']);
        iCMS_DB::query("INSERT INTO `#iCMS@__tags`(`uid`,`sortid`,`name`,`type`,`keywords`,`seotitle`,`subtitle`,`description`,`link`,`count`,`weight`,`ordernum`,`tpl`,`updatetime`,`status`)VALUES ('".Admin::$uId."','$sortid','$tag','0','','','','','$link','1',0,0,'','".time()."','1')");
        $tid = iCMS_DB::$insert_id;
        tags_cache($tid);
        iCMS_DB::query("INSERT INTO `#iCMS@__taglist` (`indexId`, `tid`, `modelId`) VALUES ('$indexId', '$tid', '0')");
    }else {
        $taglist=iCMS_DB::getValue("SELECT * FROM `#iCMS@__taglist` WHERE `indexId`='$indexId' and `tid`='$tid' and `modelId`='0'");
        if(empty($taglist)) {
            iCMS_DB::query("INSERT INTO `#iCMS@__taglist` (`indexId`, `tid`, `modelId`) VALUES ('$indexId', '$tid', '0')");
            iCMS_DB::query("UPDATE `#iCMS@__tags` SET  `count`=count+1,`updatetime`='".time()."'  WHERE `id`='$tid'");
        }
    }
}
function TagsDiff($Ntags,$Otags,$indexId="0",$sortid='0') {
    global $iCMS,$Admin;
    $N		= TagsArray($Ntags);
    $O		= TagsArray($Otags);
    $diff	= array_diff_values($N,$O);
    if($diff['+'])foreach($diff['+'] AS $tag) {//新增
            TagUI($tag,$indexId,$sortid);
        }
    if($diff['-'])foreach($diff['-'] AS $tid=>$tag) {//减少
            $_count	= iCMS_DB::getValue("SELECT `count` FROM `#iCMS@__tags` WHERE `id`='$tid'");
            if($_count==1) {
                iCMS_DB::query("DELETE FROM `#iCMS@__tags`  WHERE `id`='$tid'");
                iCMS_DB::query("DELETE FROM `#iCMS@__taglist` WHERE `tid`='$tid'");
            }else {
                iCMS_DB::query("UPDATE `#iCMS@__tags` SET  `count`=count-1,`updatetime`='".time()."'  WHERE `id`='$tid'");
                iCMS_DB::query("DELETE FROM `#iCMS@__taglist` WHERE `indexId`='$indexId' and `tid`='$tid' and `modelId`='0'");
            }
        }
}
function TagsArray($tags) {
    global $iCMS;
    $a	= explode(',',$tags);
    $c	= count($a);
    $tagRs=iCMS_DB::getArray("SELECT `id`, `name` FROM `#iCMS@__tags` WHERE `name`IN ('".str_replace(',',"','",$tags)."')");
    if($tagRs)foreach($tagRs AS $t) {
            $tagArray[$t['name']]=$t['id'];
        }
    for($i=0;$i<$c;$i++) {
        empty($tagArray[$a[$i]])?$tag[]=$a[$i]:$tag[$tagArray[$a[$i]]]=$a[$i];
    }
    return $tag;
}
function tag_split($string){
	$string=trim($string);
	if(empty($string)) return;
	$a=explode(',', $string);
	foreach($a as $key=>$value){
		$b=explode(' ', $value);
		foreach($b as $k=>$v){
			$ps=$b[$k-1];
			$ps && $ls=substr($ps, -1);
			$ns=$v{0};
			if(eregi("[a-z0-9]",$ls) && eregi("[a-z0-9]",$ns)) {
				unset($c[$key][$k-1]);
				$c[$key][$k]=$ps?$ps.' '.$v:$v;
			}else{
				$c[$key][$k]=$v;
			}
		}
	}
	$d='';
	if($c)foreach($c as $key=>$value){
		$d[]=implode(',',$value);
	}
	$e=explode(',', implode(',',$d));
	$e=array_unique($e);
	if($e)foreach($e as $key=>$value){
		$value && $f[]=$value;
	}
	return $f;
	//return implode(',',$d);
}
function vlinkDiff ($Nsid,$Osid,$indexId="0") {
    global $iCMS;
    $N		= explode(',',$Nsid);
    $O		= explode(',',$Osid);
    $diff	= array_diff_values($N,$O);
    foreach((array)$diff['+'] AS $sortid) {//新增
        if(!iCMS_DB::getValue("SELECT indexId FROM `#iCMS@__vlink` WHERE `indexId`='$indexId' and `sortId`='$sortid' and `modelId`='0'")) {
            iCMS_DB::query("INSERT INTO `#iCMS@__vlink` (`indexId`, `sortId`, `modelId` ) VALUES ('$indexId', '$sortid', '0' )");
        }
    }
    foreach((array)$diff['-'] AS $sortid) {//减少
        iCMS_DB::query("DELETE FROM `#iCMS@__vlink` WHERE `indexId`='$indexId' and `sortId`='$sortid' and `modelId`='0'");
    }
}
//------------------------cache---------------------------
function keywords_cache() {
    global $iCMS;
    $res=iCMS_DB::getArray("SELECT `keyword`,`replace`,`status` FROM `#iCMS@__keywords` ORDER BY CHAR_LENGTH(`keyword`) ASC");
    $iCMS->setCache('system/keywords',$res,0);
}
function search_cache() {
    global $iCMS;
    $res=iCMS_DB::getArray("SELECT `search` FROM `#iCMS@__search`");
    $iCMS->setCache('system/search',$res,0);
}
function tags_cache($id='') {
    global $iCMS;
    set_time_limit(0);
    $forum = new forum();
    $sql=$id ? "where `id`='$id'":'';
    $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__tags` {$sql}");
    $_count=count($rs);
    for($i=0;$i<$_count;$i++) {
        $F=$forum->forum[$rs[$i]['sortid']];
        $iurl=$iCMS->iurl('tag',array($rs[$i],$F));
        $rs[$i]['url']= $iurl->href;
        $key=$iCMS->getTagKey($rs[$i]['name']);
        $iCMS->setCache($key,$rs[$i],0);
    }
}
function model_cache() {
    global $iCMS;
    $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__model`");
    $_count=count($rs);
    for($i=0;$i<$_count;$i++) {
        $res[$rs[$i]['table']][$rs[$i]['id']]=$rs[$i];
        $idRes[$rs[$i]['id']]=$rs[$i];
    }
    $iCMS->setCache('system/model.cache',$rs,0)
    ->setCache('system/model.id',$idRes,0)
    ->setCache('system/model.table',$res,0);
}
function field_cache() {
    global $iCMS;
    $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__field`");
    $_count=count($rs);
    for($i=0;$i<$_count;$i++) {
        $rs[$i]['rules']=unserialize($rs[$i]['rules']);
        if($rs[$i]['rules']['choices']) {
            $rs[$i]['rules']=getFieldChoices($rs[$i]['rules']['choices']);
        }
        $rs[$i]['typeText']	=getFieldType($rs[$i]['type']);
        $rs[$i]['validateText']	=getFieldvalidate($rs[$i]['validate']);

        $res[$rs[$i]['field']][$rs[$i]['mid']]=$rs[$i];
        $mres[$rs[$i]['mid']][$rs[$i]['field']]=$rs[$i];
    }
    $iCMS->setCache('system/model.field',$mres,0)
    ->setCache('system/field.model',$res,0)
    ->setCache('system/field.cache',$rs,0);
}
//----------------------------------------------------------------------

function _strtotime($T) {
    global $iCMS;
    $T	= strtotime($T.' GMT');
    $timeoffset = $iCMS->config['ServerTimeZone'] == '111' ? 0 : $iCMS->config['ServerTimeZone'];
    $iCMS->config['cvtime']&&$cvtime=$iCMS->config['cvtime']*60;
    $T+=-$timeoffset*3600-$cvtime;
    $T<0 && $T=0;
    return $T;
}

function updateConfig($v,$n) {
    global $iCMS;
    iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '$v' WHERE `name` ='$n'");
}
function CreateConfigFile() {
    global $iCMS;
    $tmp=iCMS_DB::getArray("SELECT * FROM `#iCMS@__config`");
    $config_data="<?php\n\t\$config=array(\n";
    for ($i=0;$i<count($tmp);$i++) {
        $_config.="\t\t\"".$tmp[$i]['name']."\"=>\"".$tmp[$i]['value']."\",\n";
    }
    $config_data.=substr($_config,0,-2);
    $config_data.="\t\n);?>";
    FS::write(iPATH.'include/site.config.php',$config_data);
}

function contenType($T="article",$currentID='') {
    global $iCMS;
    $cTypeArray = $iCMS->getCache('system/contentype');
    if($cTypeArray)foreach($cTypeArray AS $id=>$CT) {
            $T==$CT['type'] && $opt.="<option value='{$CT['val']}'".($currentID==$CT['val']?" selected='selected'":'').">{$CT['name']}[type='{$CT['val']}'] </option>";
        }
    return $opt;
}
//日志
function admincp_log() {
    global $_GET, $_POST,$_iGLOBAL;
    if($_GET['mo']=="html") return;
    $log_message = '';
    if($_GET) {
        $log_message .= 'GET{';
        foreach ($_GET as $g_k => $g_v) {
            $g_v = is_array($g_v)?serialize($g_v):$g_v;
            $log_message .= "{$g_k}={$g_v};";
        }
        $log_message .= '}';
    }
    if($_POST) {
        $log_message .= 'POST{';
        foreach ($_POST as $g_k => $g_v) {
            $g_v = is_array($g_v)?serialize($g_v):$g_v;
            $log_message .= "{$g_k}={$g_v};";
        }
        $log_message .= '}';
    }
    runlog('admincp', $log_message);
}

function RewriteRule($rule,$b,$EXT,$HDIR){
	global $iCMS;
	$ext=empty($EXT)?$iCMS->config['htmlext']:$EXT;
	switch($b){
		case "forum":
    		$search = array('{FID}','{0xFID}','{P}','{FDIR}');
    		if(strstr($rule,'{FDIR}')===false){
    			$arg='&fid=$1';
    		}else{
    			$arg='&dir=$1';
    		}
    	break;
    	case "show":
    		$search = array('{AID}','{0xID}','{P}','{LINK}');
    		if(strstr($rule,'{LINK}')===false){
    			$arg='&id=$1';
    		}else{
    			$arg='&clink=$1';
    		}
    	break;
    	case "tag":
    	break;
	}

	$e	= str_replace($search,array('#~NUM~#','#~NUM~#','#~NUM~#','#~WORD~#'),$rule);
	$e	= str_replace(array('{FID}','{0xFID}','{P}','{AID}','{0xID}','{TID}','{MID}','{TIME}','{YY}','{YYYY}','{M}','{MM}','{D}','{DD}','{0x3ID}','{0x3,2ID}','{SID}'),'#_NUM_#',$e);
	$e	= str_replace(array('{FDIR}','{LINK}','{FPDIR}','{MD5}','{MNAME}','{ZH_CN}','{TNAME}','{TID}'),'#_WORD_#',$e);
	$e	= str_replace(array('{TID500}','{EXT}'),array('#_NUM_#/#~NUM~#',$ext),$e);
	$e	= $HDIR.'/'.$e;
	
    $ei	= preg_quote($e,'/');
    $ei	= str_replace(array('#~NUM~#','#_NUM_#','#~WORD~#','#_WORD_#'),array('(\d+)','\d+','(.*)','.*'),$ei);
    
	if(strstr($rule,'{P}')===false){
        $_dir    = dirname($e);
        $_file   = basename($e);
        $_name   = substr($_file,0,strrpos($_file,'.'));
        var_dump($e,$_dir,$_file,$_name);
        empty($_name) && $_name=$_file;
        $_ext    = strrchr($_file, ".");
        if(empty($_file)||substr($e,-1)=='/'||empty($_ext)) {
            $_name    = 'index';
            $_file    = $_name.'_#~NUM~#'.$ext;
            $ep    	  = $e.'/'.$_file;
        }
        
        if($b=="show"||empty($ep)){
	        $_dir    = dirname($e);
	        $fn		 = $_name.'_#~NUM~#'.$ext;
	        $ep		 = $_dir.'/'.$fn;
        }
    	$ep=preg_quote($ep,'/');
    	$ep=str_replace(array('#~NUM~#','#_NUM_#','#~WORD~#','#_WORD_#'),array('(\d+)','\d+','(.*)','.*'),$ep);
    	$rewrite="RewriteCond %{REQUEST_FILENAME} !-s\nRewriteRule ^{$ep}$\t\trewrite.php?do={$b}{$arg}&page=$2 [NC,L]\n";
	    $rewrite.="RewriteCond %{REQUEST_FILENAME} !-s\nRewriteRule ^{$ei}$\t\t\trewrite.php?do={$b}{$arg} [NC,L]\n";
    }else{
	    $rewrite="RewriteCond %{REQUEST_FILENAME} !-s\nRewriteRule ^{$ei}$\t\t\trewrite.php?do={$b}{$arg}&page=$2 [NC,L]\n";
    }
    return $rewrite;
}
