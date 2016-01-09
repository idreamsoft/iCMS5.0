<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<style type="text/css">
.sbtn { border:1px solid #999999; line-height:13px; height:20px;}
</style>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;文件管理&nbsp;&raquo;&nbsp;上传文件</div>
<table class="adminlist">
  <tr>
    <form action="<?php echo __ADMINCP__; ?>=files" method="post" target="iCMS_FRAME" onsubmit="return checkdirname();">
      <td style="width:70px;">新目录：</td>
      <td><input type='text' name='dirname' value='' style='width:240px'>
        <input name="do" type="hidden" value="CreateDir" />
        <input type="submit" value="创建" class="sbtn" /></td>
    </form>
  </tr>
  <tr>
    <form action="<?php echo __ADMINCP__; ?>=files" method="post" enctype="multipart/form-data" target="iCMS_FRAME" onsubmit="return checkfile();">
      <td>上　传：</td>
      <td><input name="file" type="file" class="sbtn" style='width:245px'/>
        <input name="do" type="hidden" value="Upload_File_Action" />
        <input type="submit" value="上传" class="sbtn" /></td>
    </form>
  </tr>
  <tr>
    <td>批量上传：</td>
    <td style="width:100%;"><?php include "multi.upload.php" ?></td>
  </tr>
</table>
<script type="text/javascript">
function checkdirname(){
	if($("input[name=dirname]").val()==""){
		alert("请输入目录名!");
		$("input[name=dirname]").focus();
		return false;
	}
}
function checkfile(){
	if($("input[name=file]").val()==""){
		alert("请选择文件!!");
		$("input[name=file]").click();
		return false;
	}
}
</script>
</body></html>