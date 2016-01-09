<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;管理员管理&nbsp;&raquo;&nbsp;设置[<?php echo $rs->username ; ?>]管理权限</div>
<form action="<?php echo __ADMINCP__; ?>=account" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="setpower" />
  <input type="hidden" name="uid" value="<?php echo $rs->uid ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>管理权限设置</th>
      </tr>
    </thead>
    <tr>
      <td><input name="power[]" type="checkbox" class="checkbox" value="ADMINCP" />允许进入后台</td>
    </tr>
    <?php foreach(menu::load() AS $H=>$value){  ?>
    <tr>
      <td class="rowform" style="font-size:14px;font-weight:bold;background-color:#F6F6F6"><input name="power[]" type="checkbox" class="checkbox" value="header_<?php echo $H ; ?>" />
        <?php echo UI::lang('header_'.$H) ; ?></td>
    </tr>
    <tbody id="header_<?php echo $H ; ?>">
      <tr>
        <td class="rowform" style="width:auto;"><ul>
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
      <td><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
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