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
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;HTML更新&nbsp;&raquo;&nbsp更新文章</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>请选择下列其中一种方式，如不采用请留空</li>
        <li>下列方式优先权：按栏目>按文章ID>按日期</li>
        <li>结束时间为空时则使用当前时间为结束时间</li>
        <li>每次生成文章数,可以根据服务器读写性能手动调整到最佳</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=html" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="CreateArticle" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="2">更新文章</th>
      </tr>
    </thead>
    <tr>
      <td class="tips2" width="120">按栏目:</td>
      <td class="rowform"><select name="fid[]" id="fid" multiple="multiple" size="15">
          <option value='all'>所 有 栏 目</option>
          <optgroup label="======================================"></optgroup>
          <?php echo $forum->select() ; ?>
        </select></td>
    </tr>
    <tr>
      <td class="tips2">按文章ID：</td>
      <td class="rowform">开始ID：
        <input name="startid" type="text" id="startid" class="txt" style="width:80px" />
        <br />
        -<br />
        结束ID：
        <input name="endid" type="text" id="endid" class="txt" style="width:80px" /></td>
    </tr>
    <tr>
      <td class="tips2">按日期：</td>
      <td class="rowform">开始时间：
        <input type="text" class="txt datepicker" name="starttime" value="" style="width:120px">
        <br />
        -<br />
        结束时间：
        <input type="text" class="txt datepicker" name="endtime" value="" style="width:120px"></td>
    </tr>
    <tr>
      <td class="tips2" width="120">每次生成文章数:</td>
      <td class="rowform"><input type="text" class="txt" name="speed" value="" style="width:120px"></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>
  </table>
</form>
</body></html>