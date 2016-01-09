<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
require_once iPATH.'include/upload.class.php';
class files extends AdminCP{
	function dodefault(){
        if(isset($_POST['delete'])) {
            $i=0;
            foreach($_POST['delete'] as $fid) {
                if($this->delfile($fid)){
	                $i++;
		            $js[]='#fid'.$fid;
	            }
            }
         	javascript::dialog("共删除{$i}个文件!",'js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
       }
	}
	function doFileJson(){
        $type		= $_GET["type"];
        $click		= $_GET["click"];
        $from		= $_GET["from"];
        $callback	= $_GET["callback"];
        $dir		= trim($_GET["dir"]);
        $opt		= $_GET["opt"];
        $Folder		= $opt=='template'?'templates':$this->iCMS->config['uploadfiledir'];
        $L			= FS::folder($dir,$Folder,$type);
        $_dir		= $L['folder'];
        $_count		= count($_dir);
    	//include iPATH.'include/cn.class.php';
        for($i=0;$i<$_count;$i++) {
        	$dirArray[]=array('path'=>$_dir[$i]['path'],'dir'=>$_dir[$i]['dir']);
        }
        $_FL	= $L['FileList'];
        $_fCount= count($_FL);
        $filelist='';
        for($i=0;$i<$_fCount;$i++) {
            $href=$opt=='template'?$this->iCMS->config['url'].'/templates/'.$_FL[$i]['path']:uploadpath($_FL[$i]['path']);
            $filepath=$from=='editor'?$href:$_FL[$i]['path'];
            $fileArray[]=array('name'=>$_FL[$i]['name'],'href'=>$href,'path'=>$filepath,'icon'=>$_FL[$i]['icon'],'size'=>$_FL[$i]['size'],'time'=>$_FL[$i]['time']);
        }
        echo $_GET['jsoncallback']."({dir:".json_encode($dirArray).",file:".json_encode($fileArray)."})";
	}
    function doUpload_Action() {
        $F = iUpload::FILES("file");
        javascript::dialog('文件:'.$F["OriginalFileName"].'<br />上传成功!',
                'js:parent.$("#'.$_POST['callback'].'").val("'.$F["FilePath"].'");parent.iCMS.closeDialog();');
    }
    function doUpload_File_Action() {
		strpos($_POST['dir'],'.')!==false && javascript::alert('目录不能带有 . ');
		$F=iUpload::FILES("file",'',$_POST['dir']);
		javascript::alert($F["OriginalFileName"].' 上传成功!\n\n路径:'.$F["FilePath"].'\n\n新文件名:'.$F["FileName"],
					"js:parent.reloadDialog('".$_POST['REQUEST_URI']."');");
    }
    function doCreateDir() {
		$dirname=$_POST['dirname'];
		$dir=$_POST['dir'];
		strpos($dir,'.')!==false && javascript::alert('目录不能带有 . ');
		strpos($dirname,'.')!==false && javascript::alert('目录名不能带有 . ');
		$dirRootPath = FS::path_join(iPATH,$this->iCMS->config['uploadfiledir']."/".$dir.$dirname);
		is_dir($dirRootPath) && javascript::alert('该目录名已经存在');
		FS::mkdir($dirRootPath);
		javascript::alert("目录[{$dirname}]创建成功！","js:parent.reloadDialog('".$_POST['REQUEST_URI']."');");
    }
    function docrop(){
	//header('Content-type: image/jpeg');
		$tMap	= array( 1 => 'gif', 2 => 'jpeg', 3 => 'png' );
		$pic	= $_POST['pFile'];
		$iPic	= uploadpath($pic,'+iPATH');
		list($width, $height,$type) = @getimagesize($iPic);
		$_width	= $_POST['width'];
		$_height= $_POST['height'];
		$w 		= $_POST['w'];
		$h 		= $_POST['h'];
		$x 		= $_POST['x'];
		$y 		= $_POST['y'];
		if($width==$w && $height==$h){
			javascript::alert('源图小于或等于剪裁尺寸,不剪裁!','js:parent.iCMS.insert("'.$pic.'","'.$_POST['callback'].'");');
		}
		if($width==$_width && $height==$_height){//不对源图缩放
			$_img	= iUpload::imagecreate($tMap[$type],$iPic);
			$_Type	= $_img['type'];
		}else{
			$img= iUpload::imagecreate($tMap[$type],$iPic);
			$_Type	= $img['type'];
			if ($img['res']) {
				$thumb = imagecreatetruecolor($_width,$_height);
				imagecopyresampled($thumb, $img['res'], 0, 0, 0, 0, $_width,$_height, $width, $height);
				$_tmpfile=FS::path_join(iPATH,$iCMS->config['uploadfiledir']).'/crop_tmp_'.time().rand(1,999999);
				iUpload::image($thumb,$_Type,$_tmpfile);
				$_tmpfile.='.'.$_Type;
				$_img= iUpload::imagecreate($tMap[$type],$_tmpfile);
				FS::del($_tmpfile);
			}
		}
		if ($_img['res']) {
			$_thumb = imagecreatetruecolor($w,$h);
			imagecopyresampled($_thumb,$_img['res'],0,0,$x,$y,$w,$h,$w,$h);
			$thumbpath	= substr($iPic,0,strrpos($iPic,'/'))."/thumb";
			$picName	= substr($iPic,0,strrpos($iPic,'.'));
			$picName	= substr($picName,strrpos($picName,'/'));
			$fileName	= $thumbpath.$picName.'_'.$w.'x'.$h;
			FS::mkdir($thumbpath);
			iUpload::image($_thumb,$_img['type'],$fileName);
			$fileName.='.'.$_Type;
			javascript::dialog($pic.'<br />剪裁成功！','js:parent.iCMS.insert("'.uploadpath($fileName,'-iPATH').'","'.$_POST['callback'].'");parent.iCMS_WINDOW_'.iCMSKEY.'.close();');
		}
    }
    function domanage(){
        Admin::MP("menu_file_manage");
        $method=$_GET['method'];
        if($method=='database') {
            $sql="";
            $_GET['aid'] && $sql=" WHERE `aid`='".(int)$_GET['aid']."'";
            $_GET['type']=='image' && $sql=" WHERE ext IN('jpg','gif','png','bmp','jpeg')";
            $_GET['type']=='other' && $sql=" WHERE ext NOT IN('jpg','gif','png','bmp','jpeg')";
            $maxperpage =30;
            $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__file` {$sql}"):(int)$_GET['rowNum'];
            $totalSize=iCMS_DB::getValue("SELECT SUM(size) FROM `#iCMS@__file` {$sql}");
            page($total,$maxperpage,"个文件");
            $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__file` {$sql} order by `id` DESC LIMIT {$this->firstcount} , {$maxperpage}");
            $_count=count($rs);
            include admincp::tpl("files.manage.database");
        }else {
            $Folder=$do=='template'?'templates':$this->iCMS->config['uploadfiledir'];
            $L=FS::folder('',$Folder,$type);
            include admincp::tpl("files.manage.file");
        }
    }
    function doupload(){
        include admincp::tpl();
    }
    function doreupload(){
        $fid	= (int)$_GET['fid'];
        $rs		= iCMS_DB::getRow("SELECT * FROM `#iCMS@__file` WHERE `id`='$fid' LIMIT 1");
        include admincp::tpl();
    }
    function doreremote(){
        require_once(iPATH.'include/snoopy.class.php');
        $Snoopy 		= new Snoopy;
        $Snoopy->agent	= "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5";
        $Snoopy->accept = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $Snoopy->fetch($_GET['url']);
        $path	= urldecode($_GET['path']);
        FS::write(iPATH.$this->iCMS->config['uploadfiledir'].'/'.$path,$Snoopy->results);
        javascript::dialog($_GET['url'].'<br />下载完成!');
    }
    function doreupload_Action(){
    	empty($_FILES['file']['name']) && javascript::alert("请选择文件");
        $fid	=(int)$_POST['fid'];
        $rs		=iCMS_DB::getRow("SELECT * FROM `#iCMS@__file` WHERE `id`='$fid' LIMIT 1");
        $filename=$rs->filename.'.'.$rs->ext;
        iUpload::FILES('file','',$rs->path,$filename,'reupload');
        javascript::dialog($filename.'<br />重新上传成功！');
    }
    function doDel(){
    	$msg	=$this->delfile((int)$_GET['fid']);
    	javascript::dialog($msg,'js:parent.$("#fid'.$_GET['fid'].'").remove();parent.iCMS.closeDialog();');
    }
	function delfile($fid) {
	    $rs		= iCMS_DB::getRow("SELECT * FROM `#iCMS@__file` WHERE `id`='$fid' LIMIT 1");
	    $path	= $rs->path.'/'.$rs->filename.'.'.$rs->ext;
	    $tfp	= gethumb($path,'','',false,true,true);
	    FS::del(uploadpath($path,'+iPATH'));
	    $msg=$path.' 文件删除…<span style=\"color:green;\">√</span><br />';
	    if($tfp)foreach($tfp as $wh=>$fp) {
	            FS::del(uploadpath($fp,'+iPATH'));
	            $msg.='缩略图 '.$wh.' 文件删除…<span style=\"color:green;\">√</span><br />';
	     }
	    iCMS_DB::query("UPDATE `#iCMS@__article` SET `pic`='' WHERE `pic`='{$path}'");
	    iCMS_DB::query("DELETE FROM `#iCMS@__file` WHERE `id`='$fid' LIMIT 1");
	    return $msg;
	}
    function doDel2(){
    	$msg	= $this->delfile((int)$_GET['fid']);
    	$msg	= str_replace(array('<br />','<span style=\"color:green;\">√</span>'),array('\n','√'),$msg);
    	javascript::alert($msg,'js:parent.$("#fid'.$_GET['fid'].'").remove();');
    }
	function doswfupload($param=false){
        $F = iUpload::FILES("Filedata");
        if($param){
	        echo '<li id="fid'.$F['fid'].'"><span><a href="'.__ADMINCP__.'=files&do=del2&fid='.$F['fid'].'" target="sub_iCMS_FRAME">删除</a></span><input name="files[]" type="checkbox" class="checkbox" value="'.uploadpath($F["FilePath"],'+http').'" /> '.$F["OriginalFileName"].' </li>';
        }else{
	        echo '<div><ul><li>文件:'.$F["OriginalFileName"].' 上传成功！<span style="color:green;">√</span></li><li>路径:'.$F["FilePath"].'</ul></div>';
        }
	}
	function doextract(){
		include iPATH.'include/forum.class.php';
        $forum = new forum();
        include admincp::tpl();
	}
    function PG($k,$def='') {
        $val=$_POST[$k]?$_POST[$k]:$_GET[$k];
        empty($val) && $val=$def;
        return $val;
    }
	function doextractpic(){
        Admin::MP("menu_extract_pic");
        include iPATH.'include/forum.class.php';
        set_time_limit(0);
        $speed		= 100;//提取速度
        $action		= $this->PG('action');
        $fids		= $this->PG('fid');
        $startid	= (int)$this->PG('startid');
        $endid		= (int)$this->PG('endid');
        $starttime	= $this->PG('starttime');
        $endtime	= $this->PG('endtime');
        $totle		= isset($_GET['totle'])?$_GET['totle']:0;
        $loop		= isset($_GET['loop'])?$_GET['loop']:1;
        $i			= isset($_GET['i'])?$_GET['i']:0;
        empty($action) && javascript::alert("请选择操作项");
        if($fids) {
            empty($fids) && javascript::alert("请选择版块");
            is_array($fids) && $fids = implode(",", $fids);
            if(strstr($fids,'all')) {
                $forum	= new forum();
                $fids	= substr($forum->fid(),0,-1);
                if(empty($fids)) {
                    javascript::dialog("提取完毕",'url:'.__SELF__.'?mo=files&do=extract');
                }else {
                    _header(__SELF__.'?mo=files&do=extractpic&fid='.$fids.'&action='.$action);
                }
            }else {
                $cArray	=explode(',',$fids);
                $_Ccount=count($cArray);
                $k		=isset($_GET['k'])?$_GET['k']:0;
                $rs=iCMS_DB::getArray("SELECT id FROM #iCMS@__article WHERE fid in ($fids) and `status`='1'");
                empty($totle)&&$totle=count($rs);
                $tloop=ceil($totle/$speed);
                if($loop<=$tloop) {
                    $max=$i+$speed>$totle?$totle:$i+$speed;
                    for($j=$i;$j<$max;$j++) {
                        if($action=="thumb") {
                            if($this->extractThumb($rs[$j]['id'])) {
                                $msg.="文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
                            }
                        }elseif($action=="into") {
                        	$intoMsg=$this->into($rs[$j]['id']);
                        	if($intoMsg) {
                            	$msg.=$intoMsg."文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
                            }
                        }
                    }
                    javascript::dialog($msg?$msg:"暂无提取信息!",'src:'.__SELF__.'?mo=files&do=extractpic&fid='.$fids.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j.'&action='.$action);
                }else {
					javascript::dialog("提取完毕",'url:'.__SELF__.'?mo=files&do=extract');
                }
            }
        }elseif($startid && $endid) {
            ($startid>$endid &&!isset($_GET['g'])) && javascript::alert("开始ID不能大于结束ID");
            empty($totle)&&$totle=($endid-$startid)+1;
            empty($i)&&$i=$startid;
            $tloop=ceil($totle/$speed);
            if($loop<=$tloop) {
                $max=$i+$speed>$endid?$endid:$i+$speed;
                for($j=$i;$j<=$max;$j++) {
                    if($action=="thumb") {
                        if($this->extractThumb($j)) {
                            $msg.="文章ID:".$j."提取…<span style='color:green;'>√</span><br />";
                        }
                    }elseif($action=="into") {
                        $intoMsg=$this->into($j);
                        if($intoMsg) {
                            $msg.=$intoMsg."文章ID:".$j."提取…<span style='color:green;'>√</span><br />";
                        }
                    }
                }
                javascript::dialog($msg?$msg:"暂无提取信息!",'src:'.__SELF__.'?mo=files&do=extractpic&startid='.$startid.'&endid='.$endid.'&g&loop='.($loop+1).'&i='.$j.'&action='.$action);
            }else {
                javascript::dialog("提取完毕",'url:'.__SELF__.'?mo=files&do=extract');
            }
        }elseif($starttime) {
            $s	= strtotime($starttime);
            $e	= empty($endtime)?time()+86400:strtotime($endtime);
            $rs=iCMS_DB::getArray("SELECT id FROM #iCMS@__article WHERE `pubdate`>='$s' and `pubdate`<='$e' and `status`='1'");
            empty($totle)&&$totle=count($rs);
            $tloop=ceil($totle/$speed);
            if($loop<=$tloop) {
                $max=$i+$speed>$totle?$totle:$i+$speed;
                for($j=$i;$j<$max;$j++) {
                    if($action=="thumb") {
                        if($this->extractThumb($rs[$j]['id'])) {
                            $msg.="文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
                        }
                    }elseif($action=="into") {
                    	$intoMsg=$this->into($rs[$j]['id']);
                        if($intoMsg) {
                            $msg.=$intoMsg."文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
                        }
                    }
                }
               javascript::dialog($msg?$msg:"暂无提取信息!",'src:'.__SELF__.'?mo=files&do=extractpic&starttime='.$starttime.'&endtime='.$endtime.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j.'&action='.$action);
            }else {
                javascript::dialog("提取完毕",'url:'.__SELF__.'?mo=files&do=extract');
            }
        }else {
            javascript::alert("请选择方式");
        }
	}
	function extractThumb($id) {
	    $content	= iCMS_DB::getValue("SELECT body FROM `#iCMS@__article_data` WHERE aid='$id'");
	    $img 		= array();
	    preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$content,$img);
	    $_array = array_unique($img[1]);
	    $uri 	= parse_url($this->iCMS->config['uploadURL']);
	    foreach($_array as $key =>$value) {
	        if(!strstr(strtolower($value),$uri['host'])) continue;
	        $value = uploadpath($value,'http2iPATH');
	        if(file_exists($value)) {
	            $value = uploadpath($value,'-iPATH');
	            iCMS_DB::query("UPDATE `#iCMS@__article` SET `isPic`='1',`pic` = '$value' WHERE `id` = '$id'");
	            return true;
	            break;
	        }
	    }
	}
	function into($id) {
	    $rs	 = iCMS_DB::getRow("SELECT a.title,ad.body FROM `#iCMS@__article` a LEFT JOIN `#iCMS@__article_data` ad ON a.id=ad.aid WHERE a.id='$id'");
	    $img = array();
	    $msg = false;
	    preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$rs->body,$img);
	    $_array = array_unique($img[1]);
	    foreach($_array as $key =>$value) {
	        $value	= uploadpath($value,'-http');
	        $rootpf	= uploadpath($value,'+iPATH');
	        if(file_exists($rootpf)) {
	        	$pti 		= pathinfo($rootpf);
	            $_FileSize	= @filesize($rootpf);
	            $filename	= $pti['filename'];
	            $frs		= iCMS_DB::getRow("SELECT `id`,`aid` FROM `#iCMS@__file` WHERE `filename`='$filename'");
	            if(empty($frs)){
	                iCMS_DB::query("INSERT INTO `#iCMS@__file` (`aid`,`filename`,`ofilename`,`path`,`intro`,`ext`,`size` ,`time`,`type`) VALUES ('$id','".$filename."', '', '$path','{$rs->title}', '".$pti['extension']."', '$_FileSize', '".time()."', '0')");
	                $msg.="图片: ".$value." 入库…<span style='color:green;'>√</span><br />";
	            }else{
	            	$msg.="图片: ".$value." 文件库中已有…<span style='color:green;'>×</span><br />";
	            }
	            if(empty($frs->aid)){
	            	iCMS_DB::query("UPDATE `#iCMS@__file` SET `aid`='$id' where `filename`='$filename'");
	            	$msg.="图片: ".$value." 所属文章ID已更新…<span style='color:green;'>√</span><br />";
	            }
	        }else {
	            $data="AID: ".$id." 路径: [".$rootpf."] 标题: ".$rs->title."\n";
	            FS::write(iPATH."admin/logs/pic_exist_".date('Y-m-d').".txt",$data,true,"a+");
	        }
	    }
	    return $msg;
	}
}

//switch ($do) {

//    case 'page':
//        include_once(iPATH."include/fckeditor.php") ;
//        $cid=(int)$_GET['cid'];
//        $forum=iCMS_DB::getRow("SELECT * FROM `#iCMS@__forum` WHERE id='$cid'");
//        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__page` WHERE cid='$cid'");
//        if(empty($rs)) {
//            $rs->createtime=$rs->updatetime=time();
//            $rs->creater=$rs->updater=$administrator;
//            $rs->body='';
//        }
//        $editor = new FCKeditor('body');
//        $editor->Value= $rs->body;
//        //	$editor->CreateHtml()
//        $rs->createtime=get_date($rs->createtime,'Y-m-d H:i:s');
//        $rs->updatetime=get_date($rs->updatetime,'Y-m-d H:i:s');
//        include admincp::tpl("file.page");
//        break;
//    case 'extract':
//        Admin::MP("menu_extract_pic");
//        include iPATH.'include/forum.class.php';
//        if(empty($_GET['o'])) {
//            $forum =new forum();
//            include admincp::tpl("file.extract.pic");
//        }else {
//            set_time_limit(0);
//            $action		=$_GET['action'];
//            $QUERY_STRING="&o=1&action=".$action;
//            $speed		=100;//提取速度
//            $cids		=$_GET['cid'];
//            $startid	=(int)$_GET['startid'];
//            $endid		=(int)$_GET['endid'];
//            $starttime	=$_GET['starttime'];
//            $endtime	=$_GET['endtime'];
//            $totle		=isset($_GET['totle'])?$_GET['totle']:0;
//            $loop		=isset($_GET['loop'])?$_GET['loop']:1;
//            $i			=isset($_GET['i'])?$_GET['i']:0;
//            empty($action) && javascript::alert("请选择操作");
//            if($cids) {
//                empty($cids) && javascript::alert("请选择栏目");
//                is_array($cids) && $cids = implode(",", $cids);
//                if(strstr($cids,'all')) {
//                    $forum =new forum();
//                    $cids=substr($forum->id(),0,-1);
//                    if(empty($cids)) {
//                        redirect("提取完毕",__SELF__.'?mo=files&do=extract');
//                    }else {
//                        _header(__SELF__.'?mo=files&do=extract&cid='.$cids);
//                    }
//                }else {
//                    $cArray	=explode(',',$cids);
//                    $_Ccount=count($cArray);
//                    $k		=isset($_GET['k'])?$_GET['k']:0;
//                    $rs=iCMS_DB::getArray("SELECT id FROM #iCMS@__article WHERE cid in ($cids) and `status`='1'");
//                    empty($totle)&&$totle=count($rs);
//                    $tloop=ceil($totle/$speed);
//                    if($loop<=$tloop) {
//                        $max=$i+$speed>$totle?$totle:$i+$speed;
//                        for($j=$i;$j<$max;$j++) {
//                            if($action=="thumb") {
//                                if(extractThumb($rs[$j]['id'])) {
//                                    echo "文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
//                                    flush();
//                                }
//                            }elseif($action=="into") {
//                                if(into($rs[$j]['id'])) {
//                                    echo "文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
//                                    flush();
//                                }
//                            }
//                        }
//                        _header(__SELF__.'?mo=files&do=extract&cid='.$cids.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j);
//                    }else {
//                        redirect("提取完毕",__SELF__.'?mo=files&do=extract');
//                    }
//                }
//            }elseif($startid && $endid) {
//                ($startid>$endid &&!isset($_GET['g'])) && javascript::alert("开始ID不能大于结束ID");
//                empty($totle)&&$totle=($endid-$startid)+1;
//                empty($i)&&$i=$startid;
//                $tloop=ceil($totle/$speed);
//                if($loop<=$tloop) {
//                    $max=$i+$speed>$endid?$endid:$i+$speed;
//                    for($j=$i;$j<=$max;$j++) {
//                        if($action=="thumb") {
//                            if(extractThumb($j)) {
//                                echo "文章ID:".$j."提取…<span style='color:green;'>√</span><br />";
//                                flush();
//                            }
//                        }elseif($action=="into") {
//                            if(into($j)) {
//                                echo "文章ID:".$j."提取…<span style='color:green;'>√</span><br />";
//                                flush();
//                            }
//                        }
//                    }
//                    _header(__SELF__.'?mo=files&do=extract&startid='.$startid.'&endid='.$endid.'&g&loop='.($loop+1).'&i='.$j);
//                }else {
//                    redirect("提取完毕",__SELF__.'?mo=files&do=extract');
//                }
//            }elseif($starttime) {
//                $s	= strtotime($starttime);
//                $e	= empty($endtime)?time()+86400:strtotime($endtime);
//                $rs=iCMS_DB::getArray("SELECT id FROM #iCMS@__article WHERE `pubdate`>='$s' and `pubdate`<='$e' and `status`='1'");
//                empty($totle)&&$totle=count($rs);
//                $tloop=ceil($totle/$speed);
//                if($loop<=$tloop) {
//                    $max=$i+$speed>$totle?$totle:$i+$speed;
//                    for($j=$i;$j<$max;$j++) {
//                        if($action=="thumb") {
//                            if(extractThumb($rs[$j]['id'])) {
//                                echo "文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
//                                flush();
//                            }
//                        }elseif($action=="into") {
//                            if(into($rs[$j]['id'])) {
//                                echo "文章ID:".$rs[$j]['id']."提取…<span style='color:green;'>√</span><br />";
//                                flush();
//                            }
//                        }
//                    }
//                    _header(__SELF__.'?mo=files&do=extract&starttime='.$starttime.'&endtime='.$endtime.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j);
//                }else {
//                    redirect("提取完毕",__SELF__.'?mo=files&do=extract');
//                }
//            }else {
//                javascript::alert("请选择方式");
//            }
//        }
//        break;
//    case 'post':
//        if($action=='pagedit') {
//            $id				= intval($_POST['id']);
//            $cid			= intval($_POST['cid']);
//            $name			= dhtmlspecialchars($_POST['name']);
//            $title			= dhtmlspecialchars(HtmToText($_POST['title']));
//            $keyword		= dhtmlspecialchars(HtmToText($_POST['keyword']));
//            $description	= dhtmlspecialchars(HtmToText($_POST['description']));
//            $body			= $_POST['body'];
//            $creater		= $updater=$administrator;
//            $createtime		= _strtotime($_POST['createtime']);
//            $updatetime		= time();
//            $data			= compact('cid','title','keyword','description','body','creater','updater','createtime','updatetime');
//            if(empty($id)) {
//                iCMS_DB::insert('page',$data);
//                redirect($name."页面添加完成!",__SELF__."?mo=forum");
//            }else {
//                iCMS_DB::update('page',$data,compact('id'));
//                redirect($name."编辑完成!",__SELF__."?mo=forum");
//            }
//        }
//        break;
//}

