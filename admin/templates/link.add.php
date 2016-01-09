<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;友情链接管理&nbsp;&raquo;&nbsp;添加友情链接</div>
<form action="<?php echo __ADMINCP__; ?>=link" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="2">添加友情链接</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">网站名称</td>
      <td class="rowform"><input type="text" class="txt" name="name" size="15" /></td>
    </tr>
    <tr>
      <td class="td80">分类ID</td>
      <td class="rowform"><input type="text" class="txt" name="sortid" size="15" /></td>
    </tr>
    <tr>
      <td>网站URL</td>
      <td class="rowform"><input type="text" class="txt" name="url" size="20" /></td>
    </tr>
    <tr>
      <td>文字说明</td>
      <td class="rowform"><input type="text" class="txt" name="description" size="30" /></td>
    </tr>
    <tr>
      <td>logo地址</td>
      <td class="rowform"><input type="text" class="txt" name="logo" size="20" /></td>
    </tr>
    <tr>
      <td>显示顺序</td>
      <td class="rowform"><input name="displayorder" type="text" class="txt" value="0" size="3" /></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>