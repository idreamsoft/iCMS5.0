<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<script type="text/javascript" src="admin/js/jquery.ui.datepicker.js?5.0-1.7.3"></script>
<script type="text/javascript">
$(function(){
	$(".datepicker").datepicker();
})
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;HTML更新&nbsp;&raquo;&nbsp更新TAG</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>请选择下列其中一种方式，如不采用请留空</li>
        <li>下列方式优先权：按分类>按TAG ID>按日期</li>
        <li>结束时间为空时则使用当前时间为结束时间</li>
        <li>归类/日期 方式TAG ID 将缓存24小时</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=html" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="CreateTag" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="2">更新TAG</th>
      </tr>
    </thead>
    <tr>
      <td class="tips2" width="120">按归类:</td>
      <td class="rowform"><select name="sortid[]" id="sortid" multiple="multiple" size="15">
          <option value='all'>所 有 归 类</option>
          <optgroup label="======================================"></optgroup>
          <?php echo $forum->select() ; ?>
        </select></td>
    </tr>
    <!--
    <tr>
      <td class="tips2">按TAG ID：</td>
      <td class="rowform">开始ID：
        <input name="startid" type="text" id="startid" class="txt" style="width:80px" />
        <br />
        -<br />
        结束ID：
        <input name="endid" type="text" id="endid" class="txt" style="width:80px" /></td>
    </tr>-->
    <tr>
      <td class="tips2">按日期：</td>
      <td class="rowform">开始时间：
        <input type="text" class="txt datepicker" name="starttime" value="" style="width:120px">
        <br />
        -<br />
        结束时间：
        <input type="text" class="txt datepicker" name="endtime" value="" style="width:120px"></td>
    </tr>
    <!--
       <tr>
        <td class="tips2" width="120">指定生成页数:</td>
        <td class="rowform"><input type="text" class="txt" name="cpn" value="" style="width:120px"></td>
      </tr>
       <tr>
        <td class="tips2" width="120">间隔时间(s):</td>
        <td class="rowform"><input type="text" class="txt" name="time" value="1" style="width:120px"></td>
      </tr>-->
    <tr>
      <td colspan="2"><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>
  </table>
</form>
</body></html>