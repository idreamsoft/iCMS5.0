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
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo iCMS_CHARSET ;  ?>">
<link rel="stylesheet" href="admin/css/main.css?5.0" type="text/css" media="all" />
<script src="admin/js/jquery.js?5.0-1.3.2" type="text/javascript"></script>
<script src="admin/js/common.js?5.0" type="text/javascript"></script>

<script type="text/javascript">
function uploadSuccess2(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("上传完成!请右侧选择插入...");
//		progress.setStatus(serverData);
		$("#filelist").append(serverData);
		progress.toggleCancel(false);
	} catch (ex) {
		this.debug(ex);
	}
}
window.focus();
</script>
<style type="text/css">
.uploadr-bg, .uploadr-scroll { width:300px !important; }
ul { margin:0px 0px 0px 2px; display:block; overflow-y:auto; overflow-x:hidden; width:400px; height:200px; }
ul li { height:18px; line-height:18px; border-bottom: 1px dotted #CACACA; padding: 0px; margin:0px; }
ul li span{ float:right;}
</style>
</head>
<body>
<form action="<?php echo __ADMINCP__; ?>=dialog" method="post" target="iCMS_FRAME">
<input name="do" type="hidden" id="do" value="insertBody" />
  <table class="adminlist">
    <tr>
      <td rowspan="2" style="width:310px;"><?php
	$upload['param']	="true";
	$upload['success_fun']="uploadSuccess2";
	include "multi.upload.php";
?></td>
      <td style="text-align:left;"><ul id="filelist">
        </ul></td>
    </tr>
    <tr>
      <td style="text-align:left;"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'files')" />全选 
      <input type="submit" value="插入" class="submit" /></td>
    </tr>
  </table>
</form>
<iframe width="0" height="0" style="display:none" id="sub_iCMS_FRAME" name="sub_iCMS_FRAME"></iframe>
</body>
</html>