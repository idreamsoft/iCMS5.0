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
	$("#saveAdmin").submit(function(){
		if($("#username").val()==''){
			alert("用户名不能为空!");
			$("#username").focus();
			return false;
		}
		<?php if(empty($rs->uid)) {   ?>
		if($("#pwd").val()==''){
			alert("密码不能为空!");
			$("#pwd").focus();
			return false;
		}
		<?php }  ?>
	});
});
</script>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;管理员管理&nbsp;&raquo;&nbsp;编辑管理员信息</div>
<form action="<?php echo __ADMINCP__; ?>=account" method="post" target="iCMS_FRAME" id="saveAdmin">
  <input type="hidden" name="do" value="save" />
  <input type="hidden" name="uid" value="<?php echo $rs->uid ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="3" class="td80">个人资料</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">用户名:</td>
      <td class="rowform"><input name="username" type="text" id="username" value="<?php echo $rs->username ; ?>" <?php if($rs->uid) {   ?>readonly="true"<?php }  ?> class="txt"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80"><?php if($rs->uid) {   ?>新<?php }  ?>密码:</td>
      <td class="rowform"><input name="pwd" type="password" id="pwd" class="txt"/></td>
      <td class="tips2"><?php if($rs->uid){?>不更改请留空<?php }  ?></td>
    </tr>
    <tr>
      <td class="td80">确认密码:</td>
      <td class="rowform"><input name="pwd2" type="password" id="pwd2" class="txt"/></td>
      <td class="tips2"><?php if($rs->uid){?>不更改请留空<?php }  ?></td>
    </tr>
    <tr>
      <td class="td80">管理组:</td>
      <td class="rowform"><select name="groupid" id="groupid" style="width:auto;">
          <option value='0'>==无==</option>
          <?php echo $group->select($rs->groupid,'a') ; ?>
        </select></td>
      <td class="tips2">请选择管理组</td>
    </tr>
    <?php if($rs->uid) {   ?>
    <tr>
      <td class="td80">最后登陆IP:</td>
      <td class="rowform"><input type="text" disabled class="txt" value="<?php echo $rs->lastip ; ?>" readonly="true"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">最后登陆时间:</td>
      <td class="rowform"><input type="text" disabled class="txt" value="<?php echo get_date($rs->lastlogintime,"Y-m-d H:i:s") ; ?>" readonly="true"/></td>
      <td class="tips2"></td>
    </tr>
    <?php }  ?>
    <tr>
      <td class="td80">昵称</td>
      <td class="rowform"><input name="name" type="text" class="txt" value="<?php echo $rs->name ; ?>"/></td>
      <td class="tips2">用于编辑名</td>
    </tr>
    <tr>
      <td class="td80">E-mail</td>
      <td class="rowform"><input name="email" type="text" class="txt" value="<?php echo $rs->email ; ?>"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" value="提交"  /></td>
    </tr>
  </table>
</form>
</div>
</body></html>