<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;数据库管理&nbsp;&raquo;&nbsp;数据库操作&nbsp;&raquo;&nbsp;批量替换</div>
<form action="<?php echo __ADMINCP__; ?>=database" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="2">批量替换</th>
      </tr>
    </thead>
    <tr>
      <td colspan="2">批量替换属直接操作数据库，存在一定危险性，请慎用!!!</td>
    </tr>
    <tr>
      <td class="rowform" colspan="2" ><select name="field" id="field">
          <option value="title">标题</option>
          <option value="clink">自定义链接</option>
          <option value="comments">评论数</option>
          <option value="pic">缩略图</option>
          <option value="cid">栏目</option>
          <option value="tkd">标题/关键字/简介</option>
          <option value="body">内容</option>
        </select></td>
    </tr>
    <thead>
      <tr>
        <th colspan="2">查找:</th>
      </tr>
    </thead>
    <tr>
      <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="pattern" id="pattern" cols="50" class="tarea"></textarea></td>
      <td class="tips2">查找</td>
    </tr>
    <thead>
      <tr>
        <th colspan="2">替换:</th>
      </tr>
    </thead>
    <tr>
      <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="replacement" id="replacement" cols="50" class="tarea"></textarea></td>
      <td class="tips2">替换</td>
    </tr>
    <thead>
      <tr>
        <th colspan="2">附加条件:</th>
      </tr>
    </thead>
    <tr>
      <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="where" id="where" cols="50" class="tarea"></textarea></td>
      <td class="tips2">where (SQL语句)</td>
    </tr>
    <tr>
      <td><input name="do" type="hidden" id="do" value="Replace_Action" />
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>