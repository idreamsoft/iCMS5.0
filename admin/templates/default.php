<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;预设</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>每行一个</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=defaults" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="edit" />
  <table class="adminlist">
    <thead>
      <tr>
        <th width="50%">出处</th>
        <th>作者</th>
      </tr>
    </thead>
    <tr>
      <td><textarea name="source" id="source" onKeyUp="textareasize(this)" class="tarea" style="width:98%;"><?php echo implode("\r\n",(array)$defArray['source']) ; ?></textarea></td>
      <td><textarea name="author" id="author" onKeyUp="textareasize(this)" class="tarea" style="width:98%;"><?php echo implode("\r\n",(array)$defArray['author']) ; ?></textarea></td>
    </tr>
    <thead>
      <tr>
        <th width="50%">编辑</th>
        <th></th>
      </tr>
    </thead>
    <tr>
      <td><textarea name="editor" id="editor" onKeyUp="textareasize(this)" class="tarea" style="width:98%;"><?php echo implode("\r\n",(array)$defArray['editor']) ; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>