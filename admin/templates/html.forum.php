<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;HTML更新&nbsp;&raquo;&nbsp更新栏目</div>
<form action="<?php echo __ADMINCP__; ?>=html" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="CreateForum" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="2">更新栏目</th>
      </tr>
    </thead>
    <tr>
      <td class="tips2" width="120">选择栏目:</td>
      <td class="rowform"><select name="fid[]" id="fid" multiple="multiple" size="15">
          <option value='all'>所 有 栏 目</option>
          <optgroup label="======================================"></optgroup>
          <?php echo $forum->select() ; ?>
        </select></td>
    </tr>
    <tr>
      <td class="tips2" width="120">指定生成页数:</td>
      <td class="rowform"><input type="text" class="txt" name="cpn" value="" style="width:120px"></td>
    </tr>
    <tr>
      <td class="tips2" width="120">间隔时间(s):</td>
      <td class="rowform"><input type="text" class="txt" name="time" value="1" style="width:120px"></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>
  </table>
</form>
</body></html>