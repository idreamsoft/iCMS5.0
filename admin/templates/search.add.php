<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;搜索统计&nbsp;&raquo;&nbsp;添加搜索</div>
<form action="<?php echo __ADMINCP__; ?>=search" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <input type="hidden" name="id" value="<?php echo $id ; ?>"  />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="3">添加关键字</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">关键字:</td>
      <td class="rowform"><input name="search" id="search" value="<?php echo $rs->search ; ?>" type="text" class="txt"  /></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">次数:</td>
      <td class="rowform"><input name="times" id="times" value="<?php echo $rs->times ; ?>" type="text" class="txt"  /></td>
      <td class="tips2"></td>
    </tr>
    <tr class="nobg">
      <td colspan="3"><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>