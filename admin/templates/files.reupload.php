<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
?>
<form action="<?php echo __ADMINCP__; ?>=files" method="post" enctype="multipart/form-data" target="iCMS_FRAME" id="iDF">
  <table class="adminlist">
    <tr>
      <td>文 件 名：<?php echo $rs->filename; ?>.<?php echo $rs->ext ; ?></td>
    </tr>
    <tr>
      <td>原文件名：<?php echo $rs->ofilename ; ?></td>
    </tr>
    <tr>
      <td>文件路径：<?php echo $rs->path ; ?></td>
    </tr>
    <tr>
      <td>文件类型：<?php echo FS::icon($rs->filename.'.'.$rs->ext);?> .<?php echo $rs->ext ; ?></td>
    </tr>
    <tr>
      <td>保存方式：<?php echo $rs->type?"远程":"本地上传" ; ?></td>
    </tr>
    <tr>
      <td>保存时间：<?php echo get_date($rs->time,'Y-m-d H:i:s') ; ?></td>
    </tr>
    <tr>
      <td>新文件：
        <input name="file" type="file" class="uploadbtn" id="pic" />
        <input name="fid" type="hidden" value="<?php echo $fid ; ?>" />
        <input name="do" type="hidden" value="reupload_Action" /></td>
    </tr>
  </table>
</form>
</body></html>