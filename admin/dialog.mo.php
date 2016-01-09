<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');

class dialog extends AdminCP {
    function doFile($do=null) {
        $dir	= trim($_GET["dir"]);
        $type	= $_GET["type"];
        $click	= $_GET["click"];
        $from	= $_GET["from"];
        $Folder	= $do=='template'?'templates':$this->iCMS->config['uploadfiledir'];
        $callback= $_GET["callback"];
        $L		= FS::folder($dir,$Folder,$type);
        include admincp::tpl("files.manage.file");
    }
    function doTemplate() {
        $this->doFile("template");
    }
    function doSQLfile() {
        $_GET['aid'] && $sql=" WHERE `aid`='".(int)$_GET['aid']."'";
        $_GET['type']=='image' && $sql=" WHERE ext IN('jpg','gif','png','bmp','jpeg')";
        $_GET['type']=='other' && $sql=" WHERE ext NOT IN('jpg','gif','png','bmp','jpeg')";
        $maxperpage =30;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__file` {$sql}"):(int)$_GET['rowNum'];
        $totalSize=iCMS_DB::getValue("SELECT SUM(size) FROM `#iCMS@__file` {$sql}");
        page($total,$maxperpage,"个文件");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__file` {$sql} order by `id` DESC LIMIT {$this->firstcount} , {$maxperpage}");
        $_count=count($rs);
        include admincp::tpl();
    }
    function doinsertBody(){
    	echo '<script type="text/javascript">';
    	if($_POST['files']){
    		foreach($_POST['files'] AS $src){
    			$content.='<p><img src=\"'.$src.'\" /></p>';
    		}
    		echo 'parent.appendEditor("'.$content.'");parent.iCMS_WINDOW_'.iCMSKEY.'.close();';
    	}else{
    		echo 'alert("请选择文件!");parent.iCMS_WINDOW_'.iCMSKEY.'.focus();';
    	}
    	echo '</script>';
    }
    function domultiUpload(){
    	include admincp::tpl();
    }
    function doAupload() {
        include admincp::tpl();
    }
    function doviewPic() {
		echo '<img src="'.uploadpath($_GET['callback']).'"/>';
    }
    function doCrop() {
        $pFile=$_GET['pic'];
        $iFile=uploadpath($pFile,'+iPATH');
        $callback=$_GET['callback'];
        list($width, $height,$imagetype) = @getimagesize($iFile);
        $pw	=$width>500?500:$width;
        $tw	= (int)$this->iCMS->config['thumbwidth'];
        $th	= (int)$this->iCMS->config['thumbhight'];
        $rate=round($pw/$width,2)*100;
        $sliderMin=round($tw/$width,2)*100;
        include admincp::tpl();
    }
    function dosearch_article(){
    	echo '<script>parent.reloadDialog("'.__ADMINCP__.'=dialog&do=article&callback='.$_GET['callback'].'&keywords='.rawurlencode($_GET['keywords']).'");</script>';
    }
    function doarticle() {
        include iPATH.'include/forum.class.php';
        $forum 	= new forum();
        $callback= $_GET['callback'];
        $fid	= (int)$_GET['fid'];
        $sql	= " where ";
        $sql.=$_GET['type']=='draft'?"`status` ='0'":"`status` ='1'";
        $sql.=$act=='user'?" AND `postype`='0'":" AND `postype`='1'";
        $_GET['keywords'] && $sql.=" AND CONCAT(title,keywords,description) REGEXP '{$_GET['keywords']}'";
        $fid=Admin::CP($fid)?$fid:"0";
        if($fid) {
            if(isset($_GET['sub'])) {
                $sql.=" AND ( fid IN(".$forum->fid($fid).$fid.")";
            }else {
                $sql.=" AND ( fid ='$fid'";
            }
            $sql.=" OR `vlink` REGEXP '[[:<:]]".preg_quote($fid, '/')."[[:>:]]')";
        }else {
            Admin::$cpower && $sql.=" AND fid IN(".implode(',',Admin::$cpower).")";
        }
        isset($_GET['keyword']) && $uri.='&keyword='.$_GET['keyword'];

        $maxperpage =8;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__article` {$sql}"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"篇文章");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__article`{$sql} order by id DESC LIMIT {$this->firstcount} , {$maxperpage}");
        $_count=count($rs);
        include admincp::tpl();
    }
}
