<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/forum.class.php';
class article extends AdminCP {
    function doDefault() {
        $this->doManage();
    }
    function doAdd($isNoCopy=true) {
        Admin::MP(array("menu_index_article_add","menu_article_add"));
        $forum = new forum();
        $id=(int)$_GET['id'];
        $rs=array();
        $id && $rs=iCMS_DB::getRow("SELECT a.*,ad.tpl,ad.body,ad.subtitle FROM `#iCMS@__article` a LEFT JOIN `#iCMS@__article_data` ad ON a.id=ad.aid WHERE a.id='$id'",ARRAY_A);
        $rs['pubdate']=empty($id)?get_date('',"Y-m-d H:i:s"):get_date($rs['pubdate'],'Y-m-d H:i:s');
        $fid=empty($rs['fid'])?intval($_GET['fid']):$rs['fid'];
        $cata_option=$forum->select($fid);
        empty($rs['editor']) && $rs['editor']=empty(Admin::$Rs->name)?Admin::$Rs->username:Admin::$Rs->name;
        empty($rs['userid']) && $rs['userid']=Admin::$uId;
		$rs['body']	= dhtmlspecialchars(str_replace('<!--iCMS.PageBreak-->','<div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>', $rs['body']));
        $strpos = strpos(__REF__,'?');
        $REFERER = $strpos===false?'':substr(__REF__,$strpos);
        include admincp::tpl("article.add");
    }
    function doCopy(){
    	$this->doAdd(false);
    }
    function doManage() {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $time_start = $mtime[1] + $mtime[0];

        Admin::MP(array("menu_article_manage","menu_article_draft","menu_article_user_manage","menu_article_user_draft"));
        $forum  = new forum();

        $fid	= (int)$_GET['fid'];
        $type	= $_GET['type'];
        $sql	= " where ";
        switch($type) { //postype: [0:用户][1:管理员][2:回收] status:[0:隐藏][1:正常]
        	case 'draft'://草稿
        		$sql.="`status` ='0' AND `postype`='1'";
		        $_ptxt=array(0=>'发布',1=>'转成草稿');
		        $position=UI::lang("menu_article_draft");
        	break;
         	case 'trash'://回收站
        		$sql.="`status` ='0' AND `postype`='2'";
        		$_ptxt=array(0=>'还原',1=>'放入回收站');
        		$position=UI::lang("menu_article_trash");
        	break;
        	case 'user'://用户
        		if($_GET['act']=="draft"){
        			$sql.="`status` ='0'"; //用户审核
	            	$position=UI::lang("menu_article_user_draft");
        		}else{
        			$sql.="`status` ='1'";
        			$position=UI::lang("menu_article_user_manage");
        		}
        		$sql.=" AND `postype`='0'";
         		$_ptxt=array(0=>'通过审核',1=>'取消审核');
       		break;
       		default:
	       		$sql.=" `status` ='1' AND `postype`='1'";
	       		$position='';
		       	$fid && $position=$forum->forum[$fid]['name'];
		}
        $position && $position="&nbsp;&raquo;&nbsp;".$position;
        if($_GET['keywords']) {
            if($_GET['st']=="title") {
                $sql.=" AND `title` REGEXP '{$_GET['keywords']}'";
            }else if($_GET['st']=="top") {
                $sql.=" AND `top`='{$_GET['keywords']}'";
            }else if($_GET['st']=="id") {
                $sql.=" AND `id` REGEXP '{$_GET['keywords']}'";
            }else if($_GET['st']=="tkd") {
                $sql.=" AND CONCAT(title,keywords,description) REGEXP '{$_GET['keywords']}'";
            }
        }
        $_GET['title'] 			&& $sql.=" AND `title` like '%{$_GET['title']}%'";
        $_GET['tag'] 			&& $sql.=" AND `tags` REGEXP '[[:<:]]".preg_quote(rawurldecode($_GET['tag']),'/')."[[:>:]]'";
        isset($_GET['at']) && $_GET['at']!='-1' && $sql.=" AND `type` ='".$_GET['at']."'";
        isset($_GET['userid']) 	&& $sql.=" AND `userid`='".(int)$_GET['userid']."'";
        $fid=Admin::CP($fid)?$fid:"0";
        if($fid) {
            $fidIN=$forum->fid($fid).$fid;
            if(isset($_GET['sub']) && strstr($fidIN,',')) {
                $sql.=" AND fid IN(".$fidIN.")";
            }else {
                $sql.=" AND fid ='$fid'";
            }
            //$sql.=" OR `vlink` REGEXP '[[:<:]]".preg_quote($fid, '/')."[[:>:]]')";
        }else {
            Admin::$cpower && $sql.=" AND fid IN(".implode(',',(array)Admin::$cpower).")";
        }
        isset($_GET['nopic'])   && $sql.=" AND `isPic` ='0'";
        $_GET['starttime'] 	&& $sql.=" and `pubdate`>='".strtotime($_GET['starttime'])."'";
        $_GET['endtime'] 	&& $sql.=" and `pubdate`<='".strtotime($_GET['endtime'])."'";

        $act=='user' && $uri.='&act=user';
        $_GET['type']=='draft' && $uri.='&type=draft';
        isset($_GET['userid']) && $uri.='&userid='.(int)$_GET['userid'];
        isset($_GET['keyword']) && $uri.='&keyword='.$_GET['keyword'];
        isset($_GET['tag']) && $uri.='&tag='.$_GET['tag'];

        $orderby=$_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__article` {$sql}"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"篇文章");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__article` {$sql} order by {$orderby} LIMIT {$this->firstcount} , {$maxperpage}");
//echo iCMS_DB::$last_query;
//iCMS_DB::last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::last_query);
//var_dump($explain);
        $_count=count($rs);
        include admincp::tpl("article.manage");
//		$mtime = microtime();
//		$mtime = explode(' ', $mtime);
//		$time_end = $mtime[1] + $mtime[0];
//		echo  "<h1>".($time_end - $time_start);
    }
    function doStatus() {
        $id	= (int)$_GET['id'];
        $s	= (int)$_GET['s'];
        $pt	= (int)$_GET['pt'];
        if($pt=="2") $setSQL=" ,`postype` = '0'";
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `status` = '".($s=='1'?0:1)."'{$setSQL} WHERE `id` ='$id'");
        _Header();
    }
    function doDelvlink() {
        $id	= (int)$_GET['id'];
        $fid= (int)$_GET['fid'];
        $id && $vlink=iCMS_DB::getValue("SELECT vlink FROM `#iCMS@__article` WHERE `id`='$id'");
        $vlinkArray	= explode(',',$vlink);
        $key		= array_search($fid,$vlinkArray);
        unset($vlinkArray[$key]);
        $vlink		= implode(',',$vlinkArray);
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `vlink` = '$vlink' WHERE `id` ='$id'");
        _Header();
    }
    function doupdateHTML() {
        !$_GET['id'] && javascript::alert("请选择要更新的文章");
        include iPATH.'include/iHtml.class.php';
        iHtml::Article($_GET['id']) && javascript::dialog('更新完成!',"url:1");
    }
    function doDel() {
        $id=intval($_GET['id']);
        !$id && javascript::alert("请选择要删除的文章");
        $msg=delArticle($id);
		javascript::dialog($msg.'<br />成功删除!','js:parent.$("#aid'.$id.'").remove();parent.iCMS.closeDialog();');
    }
    function dodels() {
        empty($_POST['id']) && javascript::alert("请选择要删除的文章");
        foreach((array)$_POST['id'] AS $id) {
            $msg.=delArticle($id);
            $js[]='#aid'.$id;
        }
        javascript::dialog($msg.'<br />全部成功删除!','js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
    }
    function doSave() {
//    	print_r($_POST);
//    	exit;
        set_time_limit(0);
        $aid		= (int)$_POST['aid'];
        $fid		= (int)$_POST['fid'];
        $userid		= (int)$_POST['userid'];
        $type		= (int)$_POST['type'];
        $orderNum	= (int)$_POST['orderNum'];
        $title		= dhtmlspecialchars($_POST['title']);
        $subtitle	= dhtmlspecialchars($_POST['subtitle']);
        $stitle		= dhtmlspecialchars($_POST['stitle']);
        $pic		= dhtmlspecialchars($_POST['pic']);
        $source		= dhtmlspecialchars($_POST['source']);
        $author		= dhtmlspecialchars($_POST['author']);
        $editor		= dhtmlspecialchars($_POST['editor']);
        $description= dhtmlspecialchars($_POST['description']);
        $keywords	= dhtmlspecialchars($_POST['keywords']);
        $tags		= dhtmlspecialchars($_POST['tags']);
        $clink		= dhtmlspecialchars($_POST['clink']);
        $url		= dhtmlspecialchars($_POST['url']);
        $tpl		= dhtmlspecialchars($_POST['template']);

        $top		= _int($_POST['top']);
        $vlink		= empty($_POST['vlink'])?"":implode(',',$_POST['vlink']);
        $related	= empty($_POST['related'])?"":implode(',',$_POST['related']);
        $pubdate	= _strtotime($_POST['pubdate']);

        $remote		= isset($_POST['remote'])   ?true:false;
        $dellink	= isset($_POST['dellink'])  ?true:false;
        $this->autopic	= isset($_POST['autopic'])  ?true:false;

        $status     = isset($_POST['draft'])?"0":"1";
        $postype	= $_POST['postype']?$_POST['postype']:"1";
        
		$body		= implode('<!--iCMS.PageBreak-->',$_POST['body']);
        $body		= str_replace(array("\n","\r","\t",'<p>&nbsp;</p><p>&nbsp;</p>','<p>&nbsp;</p><p>&nbsp;</p>','<p>&nbsp;</p><p>&nbsp;</p>'),"",$body);
        $body 		= preg_replace(array('/<script.+?<\/script>/is','/<form.+?<\/form>/is','/<div\s+style=\\\"page-break-after:.*?<\/div>/is'),array('','','<!--iCMS.PageBreak-->'),$body);
        //$body       = strip_tags($body,'<div><br><p><img><a><embed><span><b><strong><u><em>');

        empty($title) && javascript::alert('标题不能为空！');
        empty($fid) && javascript::alert('请选择所属栏目');
        empty($body) && empty($url) && javascript::alert('文章内容不能为空！');
        WordFilter($title) && javascript::alert('标题包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($body) && javascript::alert('文章内容包含被系统屏蔽的字符，请返回重新填写。');

        if($clink) {
            for($i=0;$i<strlen($clink);$i++) {
                !preg_match("/[a-zA-Z0-9_\-~".preg_quote($this->iCMS->config['CLsplit'],'/')."]/",$clink{$i}) && javascript::alert('自定链接只能由英文字母、数字或_-~组成(不支持中文)');
            }
        }
        isset($_POST['keywordToTag']) && empty($tags) && $tags=$keywords;
		$tags=implode(',',(array)tag_split($tags));
        if($this->iCMS->config['autodesc']=="1" && !empty($this->iCMS->config['descLen']) && empty($description) && empty($url)) {
            $description=csubstr(HtmToText($body),$this->iCMS->config['descLen']);
        }
        $remote && FS::remotepic($body,$title);
        (!$remote && $this->autopic) && FS::remotepic($body,$title,true);

        if(empty($clink)) {
            include iPATH.'include/cn.class.php';
            $clink=CN::pinyin($title,$this->iCMS->config['CLsplit']);
        }
        $isPic=empty($pic)?0:1;
        $dellink &&	$body 	= preg_replace("/<a[^>].*?>(.*?)<\/a>/si", "\\1",$body);
        $SELFURL=__SELF__.(empty($_POST['REFERER'])?'?mo=article&do=manage':$_POST['REFERER']);
        $forum = new forum();
        if(empty($aid)) {
            empty($userid) && $userid=Admin::$uId;
            $hits=$good=$bad=$comments=0;
            $checkCL=iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__article` where `clink` ='$clink'");
            if($this->iCMS->config['repeatitle']) {
                iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__article` where `title` = '$title'") && javascript::alert('该标题的文章已经存在!请检查是否重复');
                $checkCL && javascript::alert('该自定链接已经存在!请另选一个');
            }else {
                $checkCL && $clink.=$this->iCMS->config['CLsplit'].random(6,1);
            }
            iCMS_DB::insert('article',compact('fid','title','stitle','clink','orderNum','url','source','author','editor','userid','postype','keywords','tags','description','related','isPic','pic','pubdate','hits' ,'good','bad','comments','type','vlink','top','status'));
            $aid	= iCMS_DB::$insert_id;
            if(empty($url)) {
//            	$body	= addslashes($body);
                iCMS_DB::insert('article_data',compact('aid','subtitle','tpl','body'));
                $this->insert_db_remote($body,$aid);
                //$iCMS->setCache('system/search',$res,0);
            }
            addtags($tags,$aid,$forum->rootid($fid));
            $vlink=empty($vlink)?$fid:$vlink.','.$fid;
            vlinkDiff($vlink,'',$aid);
            if(!strstr($forum->forum[$fid]['contentRule'],'{PHP}') &&!$forum->forum[$fid]['url']&&$forum->forum[$fid]['mode']=="1" && $status) {
                include iPATH.'include/iHtml.class.php';
                iHtml::Article($aid);
                iHtml::forum($fid,1,0,1);
            }
            iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count+1 WHERE `fid` ='$fid' LIMIT 1 ");
            $moreaction=array(
                    array("text"=>"查看该文章","url"=>$this->iCMS->iurl('show',array(array('id'=>$aid,'link'=>$clink,'url'=>$url,'fid'=>$fid,'pubdate'=>$pubdate),$forum->forum[$fid]))->href,"o"=>'target="_blank"'),
                    array("text"=>"编辑该文章","url"=>__SELF__."?mo=article&do=add&id=".$aid),
                    array("text"=>"继续添加文章","url"=>__SELF__."?mo=article&do=add&fid=".$fid),
                    array("text"=>"返回文章列表","url"=>$SELFURL),
                    array("text"=>"查看网站首页","url"=>"../index.php","o"=>'target="_blank"')
            );
            javascript::dialog('文章添加完成!<br />10秒后返回文章列表','url:'.$SELFURL,$moreaction,10);
        }else {
            $checkCL=iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__article` where `clink` ='$clink' AND `id` !='$aid'");
            if($this->iCMS->config['repeatitle']) {
                $checkCL && javascript::alert('该自定链接已经存在!请另选一个');
            }else {
                $checkCL && $clink.=$this->iCMS->config['CLsplit'].random(6,1);
            }
            $art=iCMS_DB::getRow("SELECT `fid`,`tags`,`vlink` FROM `#iCMS@__article` where `id` ='$aid'");
            TagsDiff($tags,$art->tags,$aid,$forum->rootid($fid));
            iCMS_DB::update('article',compact('fid','title','stitle','orderNum','clink','url','source','author','editor','userid','postype','keywords','tags','description','related','isPic','pic','pubdate','type','vlink','top','status'),array('id'=>$aid));
            $vlink=empty($vlink)?$fid:$vlink.','.$fid;
            vlinkDiff($vlink,$art->vlink,$aid);

            if(empty($url)) {
//            	$body	= addslashes($body);
                if(iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__article_data` where `aid` ='$aid'")) {
                    iCMS_DB::update('article_data',compact('tpl','subtitle','body'),compact('aid'));
                }else {
                    iCMS_DB::insert('article_data',compact('aid','subtitle','tpl','body'));
                }
                $this->insert_db_remote($body,$aid);
            }
            if(!strstr($forum->forum[$fid]['contentRule'],'{PHP}')&&!$forum->forum[$fid]['url']&&$forum->forum[$fid]['mode']=="1" && $status) {
                include iPATH.'include/iHtml.class.php';
                iHtml::Article($aid);
                iHtml::forum($fid,1,0,1);
            }
            if($art->fid!=$fid) {
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count-1 WHERE `fid` ='{$art->fid}' LIMIT 1 ");
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count+1 WHERE `fid` ='$fid' LIMIT 1 ");
            }
            javascript::dialog('文章编辑完成!<br />3秒后返回文章列表','url:'.$SELFURL);
        }
    }
    function dotop() {
        empty($_POST['id']) && javascript::alert("请选择要设置置顶权重的文章");
        $top=_int($_POST['top']);
        $ids=implode(',',(array)$_POST['id']);
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `top` = '$top' WHERE `id` IN ($ids)");
        javascript::dialog('文章权重设置完成!',"url:1");
    }
    function dopassed() {
        empty($_POST['id']) && javascript::alert("请选择要显示的文章");
        $ids=implode(',',(array)$_POST['id']);
        $sql=($_POST['type']=='user')?" ,`postype`='0'":" ,`postype`='1'";
	    iCMS_DB::query("UPDATE `#iCMS@__article` SET `status` = '1'{$sql}  WHERE `id` IN ($ids)");
        javascript::dialog('文章状态更新完成!',"url:1");
    }
    function doCancel() {
        empty($_POST['id']) && javascript::alert("请选择要隐藏的文章");
        $ids=implode(',',(array)$_POST['id']);
        $sql=($_POST['type']=='user')?" ,`postype`='0'":" ,`postype`='1'";
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `status` = '0'{$sql} WHERE `id` IN ($ids)");
        javascript::dialog('文章已隐藏!',"url:1");
    }
    function doPassTime() {
        empty($_POST['id']) && javascript::alert("请选择要操作的文章");
        $ids=implode(',',(array)$_POST['id']);
        $sql=($_POST['type']=='user')?",`postype`='0'":" ,`postype`='1'";
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `status` = '1'{$sql},pubdate='".time()."' WHERE `id` IN ($ids)");
        javascript::dialog('文章状态更新完成!',"url:1");
    }
    function dopassTimeALL() {
    	switch($_POST['type']){
    		case "user":$postype=0;$pt=0;break;
    		case "draft":$postype=1;$pt=1;break;
    		case "trash":$postype=2;$pt=1;break;
    	}
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `status` = '1',`postype`='$pt' pubdate='".time()."' WHERE `status` = '0' AND `postype`='$postype'");
        javascript::dialog('文章全部通过审核,并更新发布时间!',"url:1");
    }
    function dopassALL() {
    	switch($_POST['type']){
    		case "user":$postype=0;$pt=0;break;
    		case "draft":$postype=1;$pt=1;break;
    		case "trash":$postype=2;$pt=1;break;
    	}
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `status` = '1',`postype`='$pt' WHERE `status` = '0' AND `postype`='$postype' ");
        javascript::dialog('文章全部通过审核!',"url:1");
    }
    function doTimeALL() {
    	switch($_POST['type']){
    		case "user":$postype=0;$pt=0;break;
    		case "draft":$postype=1;$pt=1;break;
    		case "trash":$postype=2;$pt=1;break;
    	}
        iCMS_DB::query("UPDATE `#iCMS@__article` SET pubdate='".time()."',`postype`='$pt' WHERE `status` = '0' AND `postype`='$postype'");
        javascript::dialog('所有文章发布时间已更新!',"url:1");
    }
    function doupdateHTMLs() {
        empty($_POST['id']) && javascript::alert("请选择要更新的文章");
        $i=0;
        include iPATH.'include/iHtml.class.php';
        foreach((array)$_POST['id'] AS $aid) {
            iHtml::Article($aid) && $i++;
        }
        javascript::dialog($i.'个文件更新完成!',"url:1");
    }
    function docontentype() {
        empty($_POST['id']) && javascript::alert("请选择要更改的文章");
        $type = $_POST['type'];
        $ids=implode(',',(array)$_POST['id']);
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `type` = '$type' WHERE `id` IN ($ids)");
        javascript::dialog('文章属性更改完成!',"url:1");
    }
    function dokeyword() {
        empty($_POST['id']) && javascript::alert("请选择文章");
        if($_POST['pattern']=='replace') {
	        $ids=implode(',',$_POST['id']);
            iCMS_DB::query("UPDATE `#iCMS@__article` SET `keywords` = '".dhtmlspecialchars($_POST['keyword'])."' WHERE `id` IN ($ids)");
        }elseif($_POST['pattern']=='addto') {
        	foreach($_POST['id'] AS $id){
        		$keywords=iCMS_DB::getValue("SELECT keywords FROM `#iCMS@__article` WHERE `id`='$id'");
		        iCMS_DB::query("UPDATE `#iCMS@__article` SET `keywords` = '".($keywords?$keywords.','.dhtmlspecialchars($_POST['keyword']):dhtmlspecialchars($_POST['keyword']))."' WHERE `id`='$id'");
        	}
        }
        javascript::dialog('文章关键字更改完成!',"url:1");
    }
    function dotag() {
        empty($_POST['id']) && javascript::alert("请选择文章");
        empty($_POST['pattern']) && javascript::alert("请选择操作方式");
       	$forum = new forum();
     	foreach($_POST['id'] AS $id){
    		$art=iCMS_DB::getRow("SELECT tags,fid FROM `#iCMS@__article` WHERE `id`='$id'");
	        if($_POST['pattern']=='replace') {
	        	$tags=dhtmlspecialchars($_POST['tag']);
	        }elseif($_POST['pattern']=='addto') {
	        	$tags=$art->tags?$art->tags.','.dhtmlspecialchars($_POST['tag']):dhtmlspecialchars($_POST['tag']);
	        }
	        TagsDiff($tags,$art->tags,$id,$forum->rootid($art->fid));
		    $tagsArray=explode(',',$tags);
		    $tagsArray=array_unique($tagsArray);
	 		iCMS_DB::query("UPDATE `#iCMS@__article` SET `tags` = '$tags' WHERE `id`='$id'");
    	}
        javascript::dialog('文章标签更改完成!',"url:1");
    }
    function dovlink() {
        empty($_POST['id']) && javascript::alert("请选择文章");
        empty($_POST['pattern']) && javascript::alert("请选择操作方式");
	    $vlink = empty($_POST['vlink'])?"":implode(',',$_POST['vlink']);
     	foreach($_POST['id'] AS $id){
    		$art=iCMS_DB::getRow("SELECT vlink,fid FROM `#iCMS@__article` WHERE `id`='$id'");
	        if($_POST['pattern']=='replace') {
	        }elseif($_POST['pattern']=='addto') {
	        	$vlink=$art->vlink?$art->vlink.','.$vlink:$vlink;
	        }
		    $vlink2=empty($vlink)?$art->fid:$vlink.','.$art->fid;
		    vlinkDiff($vlink2,$art->vlink,$id);
		    $vlinkArray=explode(',',$vlink);
		    $vlinkArray=array_unique($vlinkArray);
		    $vlinkArray2=array();
		    foreach($vlinkArray AS $v){
		    	$v!=$art->fid && $vlinkArray2[]=$v;
		    }
		    $vlink = empty($vlinkArray2)?"":implode(',',$vlinkArray2);
	 		iCMS_DB::query("UPDATE `#iCMS@__article` SET `vlink` = '$vlink' WHERE `id`='$id'");
    	}
        javascript::dialog('文章虚拟链接更改完成!',"url:1");
    }
    function dothumb() {
        empty($_POST['id']) && javascript::alert("请选择要提取缩略图的文章");
        foreach((array)$_POST['id'] AS $id) {
            $content	= iCMS_DB::getValue("SELECT body FROM `#iCMS@__article_data` WHERE aid='$id'");
            $img 	= array();
            preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$content,$img);
            $_array = array_unique($img[1]);
            foreach($_array as $key =>$value) {
                $value = uploadpath($value,'http2iPATH');
                if(file_exists($value)) {
                    $value = uploadpath($value,'-iPATH');
                    iCMS_DB::query("UPDATE `#iCMS@__article` SET `isPic`='1',`pic` = '$value' WHERE `id` = '$id'");
                }
            }
        }
        javascript::dialog('成功提取缩略图!',"url:1");
    }
    function domove() {
        empty($_POST['id']) && javascript::alert("请选择要移动的文章");
        !$_POST['fid'] && javascript::alert("请选择目标栏目");
        $fid=intval($_POST['fid']);
        foreach((array)$_POST['id'] AS $id) {
            $id=intval($id);
            $ofid=iCMS_DB::getValue("SELECT `fid` FROM `#iCMS@__article` where `id` ='$id'");
            iCMS_DB::query("UPDATE `#iCMS@__article` SET fid='$fid' WHERE `id` ='$id'");
            if($ofid!=$fid) {
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count-1 WHERE `id` ='{$ofid}' LIMIT 1 ");
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count+1 WHERE `id` ='{$fid}' LIMIT 1 ");
                iCMS_DB::query("UPDATE `#iCMS@__vlink` SET `sortId` = '$fid' WHERE `sortId` ='{$ofid}' and `indexId`='$id'");
            }
        }
        javascript::dialog('成功移动到目标栏目!',"url:1");
    }
    function doOrder() {
        foreach((array)$_POST['orderNum'] AS $id=>$orderNum) {
            iCMS_DB::query("UPDATE `#iCMS@__article` SET `orderNum` = '$orderNum' WHERE `id` ='$id'");
        }
        javascript::dialog('排序已更新!',"url:1");
    }
    function insert_db_remote($content,$aid) {
        $content = stripslashes($content);
        preg_match_all("/<img.*?src\s*=[\"|'|\s]*((http|file):\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$content,$match);
        $_array = array_unique($match[1]);
        set_time_limit(0);
        foreach($_array as $key =>$value) {
            $value = uploadpath($value,'-http');
            $filename = basename($value);
            $pic=iCMS_DB::getValue("SELECT `pic` FROM `#iCMS@__article` WHERE `id` = '$aid'");
            ($this->autopic && $key==0 && empty($pic)) && iCMS_DB::query("UPDATE `#iCMS@__article` SET `isPic`='1',`pic` = '$value' WHERE `id` = '$aid'");
            $faid=iCMS_DB::getValue("SELECT `aid` FROM `#iCMS@__file` WHERE `filename` ='$filename'");
            empty($faid) && iCMS_DB::query("UPDATE `#iCMS@__file` SET `aid` = '$aid' WHERE `filename` ='$filename'");
        }
    }
}