<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;组管理&nbsp;&raquo;&nbsp;设置[<?php echo $rs->name ; ?>]组栏目管理权限</div>
  <form action="<?php echo __ADMINCP__; ?>=groups" method="post" target="iCMS_FRAME">
    <input type="hidden" name="do" value="setfpower" />
    <input type="hidden" name="gid" value="<?php echo $rs->gid ; ?>" />
    <table class="adminlist">
        <thead>
      <tr>
        <th>组栏目权限设置</th>
      </tr>
    </thead>
      <tr>
        <td class="rowform" style="width:auto;"><ul>
    		<?php if($forum->Farray)foreach($forum->Farray AS $key=>$F){  ?>
    			<li style="width:100%;"><?php echo str_repeat("│　", $F['level'])."├" ; ?><input name="power[]" type="checkbox" class="checkbox" value="<?php echo $F['fid'] ; ?>" parent="<?php echo $F['rootid'] ; ?>"/> <?php echo $F['name'] ; ?></li>
	      	<?php }  ?>
	      </ul>
      </td>
      </tr>
     <tr>
        <td><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
      </tr>
    </table>
  </form>
<script type="text/javascript">
$(function(){ 
	var powerText	= '<?php echo $rs->cpower ; ?>';
	var powerArray	= powerText.split(',');
	for (i=0;i<powerArray.length;i++){
		$("input[name^=power][value="+powerArray[i]+"]").attr('checked',true);
	}
});
</script>
</body></html>