<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>
<form action="<?php echo __ADMINCP__; ?>=files" method="post" enctype="multipart/form-data" target="iCMS_FRAME" id="iDF">
  请选择文件:
  <input name="file" type="file" class="uploadbtn" id="pic" />
  <input name="do" type="hidden" value="Upload_Action" />
  <input name="callback" type="hidden" value="<?php echo $_GET['callback'];?>" />
</form>
