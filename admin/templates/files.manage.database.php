<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<script type="text/javascript" src="admin/js/jquery.function.js"></script>
<script type="text/javascript">
$(function(){
	$(".viewpic").snap("href",300,300,10,10);
});
function reupload(fid){
	iCMS.showDialog('<?php echo __ADMINCP__; ?>=files&do=reupload&fid='+fid,'','重新上传',400,320);	
}
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;文件管理&nbsp;&raquo;&nbsp;
  <select name="filemethod" onchange="parent.main.location.href='<?php echo __ADMINCP__; ?>=files&do=manage&method='+this.value">
    <option value="database"<?php if($method=="database") echo ' selected="selected"'  ?>>数据库模式</option>
    <option value="file"<?php if($method=="file") echo ' selected="selected"'  ?>>文件浏览器</option>
  </select>
</div>
<div class="itemtitle">
  <ul class="tab1" id="submenu">
    <li id="nav_manage"<?php if(empty($_GET['type'])) echo ' class="current"'  ?>><a href="<?php echo __ADMINCP__; ?>=files&do=manage&method=database"><span>所有文件</span></a></li>
    <li id="nav_image"<?php if($_GET['type']=='image') echo ' class="current"'  ?>><a href="<?php echo __ADMINCP__; ?>=files&do=manage&type=image&method=database"><span>图片文件</span></a></li>
    <li id="nav_other"<?php if($_GET['type']=='other') echo ' class="current"'  ?>><a href="<?php echo __ADMINCP__; ?>=files&do=manage&type=other&method=database"><span>其它文件</span></a></li>
  </ul>
</div>
<table class="adminlist" id="tips">
  <tr>
    <td class="tipsblock">总共:<?php echo FS::sizeUnit($totalSize) ; ?></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=files" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>删</th>
        <th>ID</th>
        <th>文件名</th>
        <th>内容ID</th>
        <th>文件大小</th>
        <th>上传时间</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){
			$filename=$rs[$i]['filename'].'.'.$rs[$i]['ext'];
			$path=$rs[$i]['path'].'/'.$filename;
    		$rs[$i]['time']=get_date($rs[$i]['time'],'Y-m-d H:i');;
			$rs[$i]['size']=FS::sizeUnit($rs[$i]['size']);
			$rs[$i]['icon']=FS::icon($filename);
			$rs[$i]['path']=uploadpath($path);
      ?>
    <tr id="fid<?php echo $rs[$i]['id'] ; ?>">
      <td><input type="checkbox" class="checkbox" name="delete[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
      <td><?php echo $total-($i+$this->firstcount) ; ?></td>
      <td><a href="<?php echo $rs[$i]['path'] ; ?>" class="viewpic" target="_blank"><?php echo $rs[$i]['icon'] ; ?></a> <a href="javascript:void(0)" title="原文件名：<?php echo $rs[$i]['ofilename'] ; ?>"><?php echo $filename; ?></a></td>
      <td><?php if($rs[$i]['aid']){ ?><a href="<?php echo __ADMINCP__; ?>=files&do=manage&aid=<?php echo $rs[$i]['aid'] ; ?>&method=database"> <?php echo $rs[$i]['aid'] ; ?> </a> <?php }else{ echo '0'; }  ?></td>
      <td><?php echo $rs[$i]['size'] ; ?></td>
      <td><?php echo $rs[$i]['time'] ; ?></td>
      <td><?php if($rs[$i]['type']){?>
      <a href="<?php echo __ADMINCP__; ?>=files&do=reremote&path=<?php echo urlencode($path) ; ?>&url=<?php echo urlencode($rs[$i]['ofilename']) ; ?>"  target="iCMS_FRAME">重新下载</a> | <?php }  ?>
      <a href="javascript:void(0);" onClick="reupload(<?php echo $rs[$i]['id'];?>);">重新上传</a> | 
      <a href="<?php echo __ADMINCP__; ?>=files&do=del&fid=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME">删除</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="7" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <td colspan="7"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'delete')" />
        <label for="chkall">全选</label>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>