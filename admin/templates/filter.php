<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;关键字过滤</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>每行一个</li>
        <li>过滤词格式:过滤词=***</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=filter" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="edit" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>禁用词</th>
        <th>过滤词</th>
      </tr>
    </thead>
    <tr>
      <td class="vtop rowform"><textarea name="disable" id="source" onKeyUp="textareasize(this)" class="tarea"><?php echo implode("\r\n",(array)$cache['system/word.disable']) ; ?></textarea></td>
      <td class="vtop rowform"><textarea name="filter" id="editor" onKeyUp="textareasize(this)" class="tarea"><?php echo implode("\r\n",(array)$filterArray) ; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>