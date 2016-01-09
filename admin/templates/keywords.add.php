<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;关键字管理&nbsp;&raquo;&nbsp;添加关键字</div>
<script type="text/javascript">var admincp='<?php echo __SELF__ ; ?>';</script>
<form action="<?php echo __ADMINCP__; ?>=keywords" method="post" target="iCMS_FRAME">
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
      <td class="rowform"><input name="keyword" id="keyword" value="<?php echo $rs->keyword ; ?>" type="text" class="txt"  /></td>
      <td class="tips2">要替换的文字</td>
    </tr>
    <tr>
      <td class="td80">替换:</td>
      <td colspan="2" class="rowform" style="width:615px;"><?php echo $editor->CreateHtml() ; ?></td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>