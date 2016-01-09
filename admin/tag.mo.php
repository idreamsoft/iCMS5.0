<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/forum.class.php';
class tag extends AdminCP {
    function doadd() {
        $id=(int)$_GET['id'];
        $forum = new forum();
        $rs	= iCMS_DB::getRow("SELECT * FROM #iCMS@__tags WHERE id='$id'");
        include admincp::tpl('tag.add');
    }
    function dodel() {
        $tId=(int)$_GET['id'];
        $this->delArticleTag($tId);
        $this->delTagCache($tId);
        javascript::dialog('成功删除!','js:parent.$("#tid'.$tId.'").remove();parent.iCMS.closeDialog();');
    }
    function doupdateHTML() {
        $id		=(int)$_GET['id'];
        $name	=isset($_GET['name'])?rawurldecode($_GET['name']):'';
        $cpage	=isset($_GET['cpage'])?$_GET['cpage']:1;
        $loop	=isset($_GET['loop'])?$_GET['loop']:0;
        if($cpage==1) {
            $rs=iCMS_DB::getRow("SELECT `name`,`status` FROM `#iCMS@__tags` WHERE `id` ='$id' and `status`='1'");
            empty($rs) && javascript::alert("禁用的标签,不能生成静态.");
            tags_cache($id);
            $name=$rs->name;
        }
        include iPATH.'include/iHtml.class.php';
        $c = iHtml::Tag($name,$cpage,$loop,0);
        if($c['loop']>0 && $c['page']<=$c['pagesize']) {
            javascript::dialog($c['name']."共".$c['pagesize']."页，已生成".$c['page']."页",'src:'.__SELF__.'?mo=tag&do=updateHTML&name='.rawurlencode($name).'&cpage='.$c['page'].'&loop='.($c['loop']-1),'ok',0);
        }else {
            javascript::dialog("标签更新完毕!",'url:1');
        }
    }
    function doDisabled() {
        $id=(int)$_GET['id'];
        $id && iCMS_DB::query("UPDATE `#iCMS@__tags` SET `status` = '0'  WHERE `id` ='$id'");
        tags_cache($id);
        javascript::dialog('该TAG已禁用!','url:0');
    }
    function doOpen() {
        $id=(int)$_GET['id'];
        $id && iCMS_DB::query("UPDATE `#iCMS@__tags` SET `status` = '1'  WHERE `id` ='$id'");
        tags_cache($id);
        javascript::dialog('TAG启用完成!','url:0');
    }
    function doupdateCache() {
        include iPATH.'include/cn.class.php';
        $id=(int)$_GET['id'];
        $name=$_GET['name'];
        $_count=iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__taglist` WHERE `tid`='$id'");
        $link=CN::pinyin($name,$this->iCMS->config['CLsplit']);
        iCMS_DB::query("UPDATE `#iCMS@__tags` SET `count` = '$_count',`link` = '$link',`updatetime`='".time()."'  WHERE `id` ='$id'");
        tags_cache($id);
        javascript::dialog('TAG缓存更新完成!');
    }
    function dodels() {
        empty($_POST['id']) && javascript::alert('请选择要操作的TAG');
        foreach((array)$_POST['id'] as $tId) {
            $this->delArticleTag($tId);
            $this->delTagCache($tId);
            $js[]='#tid'.$tId;
        }
        javascript::dialog('全部成功删除!','js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
    }
    function dosave() {
        $id         = (int)$_POST['id'];
        $name		= dhtmlspecialchars($_POST['name']);
        $sortid		= (int)$_POST['sortid'];
        $type		= (int)$_POST['type'];
        $link		= dhtmlspecialchars($_POST['link']);
        $keywords	= dhtmlspecialchars($_POST['keywords']);
        $seotitle	= dhtmlspecialchars($_POST['seotitle']);
        $subtitle	= dhtmlspecialchars($_POST['subtitle']);
        $description= dhtmlspecialchars($_POST['description']);
        $count		= (int)$_POST['count'];
        $weight		= _int($_POST['weight']);
        $ordernum	= _int($_POST['ordernum']);
        $tpl		= $_POST['tpl'];
        $status		= (int)$_POST['status'];
        if(empty($link)) {
            include iPATH.'include/cn.class.php';
            $link=CN::pinyin($name,$this->iCMS->config['CLsplit']);
        }
        if(empty($id)) {
            iCMS_DB::query("INSERT INTO `#iCMS@__tags`(`sortid`, `uid`, `name`,`type`,`keywords`,`seotitle`,`subtitle`,`description`, `link`, `count`, `weight`,`ordernum`, `tpl`,`updatetime`,`status`) VALUES ('$sortid', '".Admin::$uId."', '$name','$type','$keywords','$seotitle','$subtitle','$description', '$link', '$count', '$weight','$ordernum','$tpl','".time()."' '$status');");
        }else {
            iCMS_DB::query("UPDATE `#iCMS@__tags` SET `sortid` = '$sortid',`name` = '$name',`type` = '$type',`keywords` = '$keywords',`seotitle` = '$seotitle',`subtitle` = '$subtitle',`description` = '$description', `link` = '$link', `count` = '$count', `weight` = '$weight',`ordernum` = '$ordernum',`tpl` = '$tpl',`updatetime`='".time()."', `status` = '$status' WHERE `id` = '$id'");
        }
        tags_cache($id);
        javascript::dialog('TAG更新完成!','url:'.__SELF__.'?mo=tag&do=manage');
    }
    function doEdit() {
        foreach((array)$_POST['name'] as $id=>$value) {
                iCMS_DB::query("update `#iCMS@__tags` set `name`='$value',`sortid`='".$_POST['sortid'][$id]."',`ordernum`="._int($_POST['ordernum'][$id]).",`updatetime`='".time()."' where `id`='$id'");
                tags_cache($id);
            }
        javascript::dialog('TAG更新完成!','url:1');
    }
    function dolist() {
        $id	= (int)$_GET['id'];
        $fid    = (int)$_GET['fid'];
        $tagName= iCMS_DB::getValue("SELECT name FROM #iCMS@__tags WHERE id='$id'");
        $forum  = new forum();
        $sql    ='#iCMS@__article.id = `indexId`';
        $_GET['keywords']       && $sql.=" AND `title` REGEXP '{$_GET['keywords']}'";
        isset($_GET['nopic'])   &&$sql.=" AND `isPic` ='0'";
        $_GET['starttime'] 	&& $sql.=" and `pubdate`>='".strtotime($_GET['starttime'])."'";
        $_GET['endtime'] 	&& $sql.=" and `pubdate`<='".strtotime($_GET['endtime'])."'";
        (isset($_GET['at']) && $_GET['at']!='-1') && $sql.=" AND `type` ='".$_GET['at']."'";
        isset($_GET['keyword']) && $uri.='&keyword='.$_GET['keyword'];
        $fid=Admin::CP($fid)?$fid:"0";
        if($fid) {
            $cidIN=$forum->fid($fid).$fid;
            if(isset($_GET['sub']) && strstr($cidIN,',')) {
                $sql.=" AND fid IN(".$cidIN.")";
            }else {
                $sql.=" AND fid ='$fid'";
            }
        }else {
            Admin::$cpower && $sql.=" AND fid IN(".implode(',',(array)Admin::$cpower).")";
        }
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(#iCMS@__article.id) FROM `#iCMS@__article`,`#iCMS@__taglist` WHERE `tid`='".$id."' AND {$sql}"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"篇文章");
        $rs=iCMS_DB::getArray("SELECT #iCMS@__article.* FROM `#iCMS@__article`,`#iCMS@__taglist` WHERE `tid`='".$id."' AND {$sql} ORDER BY #iCMS@__taglist.indexId DESC LIMIT {$this->firstcount} , {$maxperpage}");
        $_count=count($rs);
        include admincp::tpl('tag.list');
    }
    function domanage() {
        Admin::MP("menu_tag_manage");
        $forum = new forum();
        $_GET['sortid'] && $sql[]=" `sortid` = '{$_GET['sortid']}'";
        $_GET['type'] && $sql[]=" `sortid` = '{$_GET['type']}'";
        $_GET['keywords'] && $sql[]=" `name` REGEXP '{$_GET['keywords']}'";
        isset($_GET['status']) && $_GET['status']!='-1' 	&& $sql[]=" `status`='".$_GET['status']."'";
        $where =$sql ? ' where '.implode(' AND ',(array)$sql):'';
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__tags` $where"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"个TAG");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__tags` $where order by id DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include admincp::tpl("tag.manage");
    }
    function delArticleTag($tId,$aId='') {
        $sql='#iCMS@__article.id = `indexId`';
        $aId && $sql.=" AND #iCMS@__article.id='$aId'";
        $tagName= iCMS_DB::getValue("SELECT name FROM #iCMS@__tags WHERE id='$tId'");
        $rs=iCMS_DB::getArray("SELECT #iCMS@__article.id,#iCMS@__article.tags FROM `#iCMS@__article`,`#iCMS@__taglist` WHERE {$sql} AND `tid`='".$tId."'");
        $_count=count($rs);
        for($i=0;$i<$_count;$i++) {
            if($rs[$i]['tags']) {
                $tagArray=explode(',',$rs[$i]['tags']);
                $key = array_search($tagName, $tagArray);
                unset($tagArray[$key]);
                $tags=implode(',',$tagArray);
                iCMS_DB::query("update `#iCMS@__article` set `tags`='$tags' where id='".$rs[$i]['id']."'");
                iCMS_DB::query("DELETE FROM `#iCMS@__taglist` WHERE `tid`='$tId' and `indexId`='".$rs[$i]['id']."'");
            }
        }
    }
    function delTagCache($id) {
        $id=implode(',',(array)$id);
        $rs=iCMS_DB::getRow("SELECT `name` FROM `#iCMS@__tags` WHERE `id` in ($id) ");
        $this->iCMS->iCache->delete($this->iCMS->getTagKey($rs->name));
        iCMS_DB::query("DELETE FROM `#iCMS@__tags` WHERE `id` in ($id) ");
    }
    function doDefault() {
        $this->domanage();
    }
}

