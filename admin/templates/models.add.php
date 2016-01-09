<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<script type="text/javascript">
	$(function(){
		$("#binding").click(function(){
		    $('#tableText').html("模块表名:");
		    $('#tableTips').html("请以字母开头,留空将按模块名称拼音");
		    if(this.checked){
			    $('#tableText').html("模块名:");
		    	$('#tableTips').html("模块文件 xxxx.mo.php 请填上模块名xxxx <br />例:<br /> 模块文件:download.mo.php <br />模块名:download");
		    }
		  }); 
		  $("input[name=position]").click(function(){
			  var pt=$("#posText").html();
			  $(".pos").empty().hide();
			  $("#pos"+this.value).html(pt).show();
		  });
		 $("input[name=position][value=<?php echo $rs['position'];?>]").click();
		 $("input[name=pos][value=<?php echo $rs['position2'];?>]").attr('checked','checked');
	})
</script>
<style type="text/css">
.pos { border: 1px dotted #B0B0B0; background: #F6F6F6; display:none; padding:2px; float:right; }
#posText { display:none; }
</style>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;自定义模块&nbsp;&raquo;&nbsp;<?php echo empty($id)?'新增':'编辑';?>模块</div>
<form action="<?php echo __ADMINCP__; ?>=models" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <input type="hidden" name="id" value="<?php echo $id ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="4"><?php echo empty($id)?'新增':'编辑' ; ?>模块</th>
      </tr>
    </thead>
    <tr class="nobg">
      <td class="td80">模块名称:</td>
      <td class="rowform"><input name="name" type="text" id="name" value="<?php echo $rs['name'] ; ?>" class="txt" /></td>
      <td class="tips2" colspan="2">&nbsp;</td>
    </tr>
    <tr class="nobg">
      <td class="td80" id="tableText">模块表名:</td>
      <td class="rowform"><input name="table" type="text" id="table" value="<?php echo $rs['table'] ; ?>" class="txt" />
        <br />
        <br />
        <input name="binding" type="checkbox" class="checkbox" id="binding" value="1" <?php echo $rs['binding']?'checked="checked" ':'';?>/>
        绑定已有模块</td>
      <td id="tableTips" colspan="2">请以字母开头,留空将按模块名称拼音</td>
    </tr>
    <tr class="nobg">
      <td class="td80">模块说明:</td>
      <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="desc" id="desc" cols="50" class="tarea"><?php echo $rs['desc'] ; ?></textarea></td>
      <td class="tips2" colspan="2">100字以内</td>
    </tr>
    <tr class="nobg">
      <td class="td80">菜单位置:</td>
      <td class="rowform"><?php foreach(menu::load() AS $H=>$value){
      	  	if($rs['table']!=$H){
		  	$rs['position']==$H && $checked=' checked="checked" ';
			echo '<span id="pos'.$H.'" class="pos"></span><input type="radio" name="position" class="radio" value="'.$H.'"'.$checked.'/> '.UI::lang('header_'.$H).'<br /><br />';
    }} ?></td>
      <td class="tips2" colspan="2">选择菜单所在位置</td>
    </tr>
    <thead>
      <tr>
        <th colspan="4">快捷链接</th>
      </tr>
    </thead>
    <tr class="nobg">
      <td class="td80">链接名:</td>
      <td class="rowform"><input name="table" type="text" id="table" value="<?php echo $rs['table'] ; ?>" class="txt" /></td>
      <td class="td80">链接参数:</td>
      <td class="rowform"><input name="table" type="text" id="table" value="<?php echo $rs['table'] ; ?>" class="txt" /></td>
    </tr>
    <tr class="nobg">
      <td colspan="4"><div class="fixsel">
          <input type="submit" class="btn" value="提交"  />
        </div></td>
    </tr>
  </table>
</form>
</div>
<div id="posText">
  <input name="pos" type="radio" value="left" class="radio" />
  左边
  <input name="pos" type="radio" value="sub" class="radio" />
  子菜单
  <input name="pos" type="radio" value="right" class="radio" />
  右边</div>
</body></html>