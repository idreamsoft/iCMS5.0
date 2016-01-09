<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo iCMS_CHARSET ; ?>">
<link rel="stylesheet" href="admin/images/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="admin/images/jquery.function.css" type="text/css" media="all" />
<script type="text/javascript" src="javascript/jquery.js"></script>
<script type="text/javascript" src="admin/js/jquery.function.js"></script>
<script type="text/javascript" src="admin/js/admin.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
	window.focus();
	$(".viewpic").snap("href",300,300,10,10);
});
</script>
</head>
<body><div class="container" id="cpcontainer">
<table class="tb tb2 nobdb" width="100%">
  <tr>
    <th height="20">当前路径：<strong><a href="<?php echo __ADMINCP__; ?>=dialog&do=<?php echo $do ; ?>&type=<?php echo $type ; ?>&hit=<?php echo $hit ; ?>&in=<?php echo $in ; ?>&from=<?php echo $from ; ?>"><?php echo $Folder.'/'.$dir ; ?></a></strong></th>
  </tr>
</table>
<table class="tb tb2 nobdb" width="100%">
  <?php if ($L['parentfolder']){   ?>  <tr>
    <td width="24"><a href="<?php echo __ADMINCP__; ?>=dialog&do=<?php echo $do ; ?>&type=<?php echo $type ; ?>&hit=<?php echo $hit ; ?>&in=<?php echo $in ; ?>&from=<?php echo $from ; ?>&dir=<?php echo $L['parentfolder'] ; ?>"><img src="admin/images/file/parentfolder.gif" border="0"></a></td>
    <td class="vtop rowform"><strong><a href="<?php echo __ADMINCP__; ?>=dialog&do=<?php echo $do ; ?>&type=<?php echo $type ; ?>&hit=<?php echo $hit ; ?>&in=<?php echo $in ; ?>&from=<?php echo $from ; ?>&dir=<?php echo $L['parentfolder'] ; ?>">．．</a></strong></td>
  </tr>
  <?php } for($i=0;$i<count($L['folder']);$i++){  ?>  <tr>
    <td width="24"><a href="<?php echo __ADMINCP__; ?>=dialog&do=<?php echo $do ; ?>&type=<?php echo $type ; ?>&hit=<?php echo $hit ; ?>&in=<?php echo $in ; ?>&from=<?php echo $from ; ?>&dir=<?php echo $L['folder'][$i]['path'] ; ?>"><img src="admin/images/file/closedfolder.gif" border="0"></a></td>
    <td class="vtop rowform"><strong><?php if ($hit=='dir'){  ?><a href="javascript:void(0)" onclick="insert('<?php echo $L['folder'][$i]['path'] ; ?>','<?php echo $in ; ?>');"><?php }else{  ?><a href="<?php echo __ADMINCP__; ?>=dialog&do=<?php echo $do ; ?>&type=<?php echo $type ; ?>&hit=<?php echo $hit ; ?>&in=<?php echo $in ; ?>&from=<?php echo $from ; ?>&dir=<?php echo $L['folder'][$i]['path'] ; ?>"><?php }  ?><?php echo $L['folder'][$i]['dir'] ; ?></a></strong></td>
  </tr>
  <?php }   ?></table>
<table class="tb tb2 " width="100%">
  <tr>
    <th>文件名</th>
    <th>文件大小</th>
    <th>最后修改时间</th>
    </tr>
  <?php for($i=0;$i<count($L['FileList']);$i++){
    //$do=='template'?'templates':$iCMS->config['uploadfiledir']
    if($do=='template'){
    	$filepath=$L['FileList'][$i]['path'];
    }elseif($do=='file'){
    	$filepath=$L['FileList'][$i]['path'];
    	$httpfile=uploadpath($filepath);
    	if(in_array($L['FileList'][$i]['ext'],array('jpg','gif','png','bmp','jpeg'))){
			$_tfp=gethumb($filepath,'','',false,false,true);
			$li='';
			if(is_array($_tfp))foreach($_tfp as $wh=>$tfp){
				$from=='editor' && $tfp=uploadpath($tfp);
    			$li.='<li><img src="admin/images/file/image.gif" align="absmiddle" alt="缩略图 '.$wh.'"> <a href="javascript:void(0)" onclick="insert(\''.$tfp.'\',\''.$in.'\');" title="插入缩略图">'.$wh.'</a></li>';
    		}
    	}
    	$from=='editor' && $filepath=$httpfile;
    }
      ?>  <tr>
    <td><a href="<?php echo $httpfile ; ?>" class="viewpic" target="_blank"><?php echo $L['FileList'][$i]['icon'] ; ?></a> <?php if($hit=='file'){  ?><a href="javascript:void(0)" onclick="insert('<?php echo $filepath ; ?>','<?php echo $in ; ?>');"><?php echo $L['FileList'][$i]['name'] ; ?></a><ul id='T<?php echo $i ; ?>'><?php echo $li ; ?></ul><?php }else{  ?><?php echo $L['FileList'][$i]['name'] ; ?><?php }  ?></td>
    <td><?php echo $L['FileList'][$i]['size'] ; ?></td>
    <td><?php echo $L['FileList'][$i]['time'] ; ?></td>
    </tr>
  <?php }   ?></table>
<?php if($do=='file'){  ?><table class="tb tb2 " width="100%">
    <tr>
      <form action="<?php echo __ADMINCP__; ?>=dialog&do=post" method="post" enctype="multipart/form-data" name="uploadfile" target="post" id="uploadfile">
        <td class="td25">上　传：</td>
        <td class="vtop rowform"><input name="file" type="file" class="uploadbtn" id="pic" /><input name="savedir" type="hidden" value="<?php echo $dir ; ?>" /><input name="action" type="hidden" value="uploadfile" /> <input type="submit" value="上传" style="border:1px solid #999999;"/></td>
      </form>
    </tr>
    <tr>
      <form action="<?php echo __ADMINCP__; ?>=dialog&do=post" method="post" name="createdir" target="post" id="createdir">
        <td class="td25">新目录：</td>
        <td class="vtop rowform"><input type='text' name='dirname' value='' style='width:150px'><input name="savedir" type="hidden" value="<?php echo $dir ; ?>" /><input name="action" type="hidden" value="createdir" /> <input type="submit" value="创建" style="border:1px solid #999999;"/></td>
      </form>
    </tr>
  </table>
<?php }   ?></div>
<iframe width="100%" height="100" style="display:none" id="post" name="post"></iframe>
</body>
</html>