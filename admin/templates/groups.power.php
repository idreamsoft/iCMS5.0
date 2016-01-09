<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;组管理&nbsp;&raquo;&nbsp;设置[<?php echo $rs->name ; ?>]组管理权限</div>
<form action="<?php echo __ADMINCP__; ?>=groups" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="setpower" />
  <input type="hidden" name="gid" value="<?php echo $rs->gid ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="2">管理权限设置</th>
      </tr>
    </thead>
    <tr>
      <td colspan="2"><input name="power[]" type="checkbox" class="checkbox" value="ADMINCP" />
        允许进入后台</td>
    </tr>
    <?php foreach(menu::load() AS $H=>$value){  ?>
    <tr>
      <td class="rowform" style="font-size:14px;font-weight:bold;background-color:#F6F6F6" colspan="2"><input name="power[]" type="checkbox" class="checkbox" value="header_<?php echo $H ; ?>" />
        <?php echo UI::lang('header_'.$H) ; ?></td>
    </tr>
    <tbody id="header_<?php echo $H ; ?>">
      <tr>
        <td class="rowform" style="width:auto;" colspan="2"><ul>
            <?php foreach($value AS $key=>$url){
            	if(is_array($url)){
            		foreach((array)$url AS $k=>$v){
             ?>	<li style="float:left; width:20%">
              <input name="power[]" type="checkbox" class="checkbox" value="<?php echo $k ; ?>" parent="header_<?php echo $H ; ?>"/>
              <?php echo UI::lang($k) ; ?> </li>
            <?php }
            	}else{
            ?>
            <li style="float:left; width:20%">
              <input name="power[]" type="checkbox" class="checkbox" value="<?php echo $key ; ?>" parent="header_<?php echo $H ; ?>"/>
              <?php echo UI::lang($key) ; ?> </li> 
            <?php }}  ?>
          </ul></td>
      </tr>
    </tbody>
    <?php }  ?>
    <tr>
      <td><input name="power[]" type="checkbox" class="checkbox" value="Allow_View_Article" />
        允许查看所有文章</td>
      <td>在有权限的栏目 是否查看该栏目所有文章 不选只能看到自己的发表的文章</td>
    </tr>
    <tr>
      <td><input name="power[]" type="checkbox" class="checkbox" value="Allow_Edit_Article" />
        允许编辑所有文章</td>
      <td>在有权限的栏目 是否编辑该栏目所有文章 不选只能编辑自己的发表的文章</td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" class="submit" value="提交" /></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
$(function(){ 
	var powerText	= '<?php echo $rs->power ; ?>';
	var powerArray	= powerText.split(',');
	for (i=0;i<powerArray.length;i++){
		$("input[name^=power][value="+powerArray[i]+"]").attr('checked',true);
	}
	$("input[parent]").click(function(){
		var p=$(this).attr("parent");
		var all=$("input[parent="+p+"]");
		var s=false;
		for (i=0;i<all.length;i++){
			if($(all[i]).attr("checked")){
				s=true;
				break;
			}
		}
		s && $("input[value="+p+"]").attr('checked',true);
	}); 
	$("input[value^=header]").click(function(){
		var sub=$("input[parent="+$(this).val()+"]");
		if($(this).attr("checked")){
			sub.attr('checked',true);
		}else{
			sub.attr('checked',false);
		}
	});
	$("input[value=ADMINCP]").click(function(){
		if(!$(this).attr("checked")){
			$("input[name^=power]").attr('checked',false);
		}
	});
});
</script>
</body></html>