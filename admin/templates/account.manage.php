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
	function doaction(obj){
		switch(obj.value){ 
			case "dels":
				if(confirm("确定要删除！！！")){
					return true;
				}else{
					obj.value="empty";
					return false;
				}
			break;
		}
	}
</script>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;管理员管理</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>点击ID可查看该管理员</li>
        <li>可单独设置管理员后台权限和栏目权限</li>
        <li>用户权限:综合用户组和管理员单独设置的权限</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=account" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>ID</th>
        <th>用户名</th>
        <th>管理组</th>
        <th>最后登陆IP</th>
        <th>最后登陆时间 [登陆次数]</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){
    	$rs[$i]['info']=unserialize($rs[$i]['info']);
      ?>
    <tr id="uid<?php echo $rs[$i]['uid'] ; ?>">
      <td><?php if($rs[$i]['uid']!="1"){  ?>
        <input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['uid'] ; ?>" />
        <?php }  ?></td>
      <td><?php echo $rs[$i]['uid'] ; ?></td>
      <td><?php echo $rs[$i]['username'] ; ?>[<?php echo $rs[$i]['name'] ; ?>]</td>
      <td><select name="groupid[<?php echo $rs[$i]['uid'] ; ?>]" id="groupid" style="width:auto;">
          <option value='0'>==无==</option>
          <?php echo $group->select($rs[$i]['groupid'],'a') ; ?>
        </select></td>
      <td><?php echo $rs[$i]['lastip'] ; ?></td>
      <td><?php echo get_date($rs[$i]['lastlogintime'],"Y-m-d H:i") ; ?> [<?php echo $rs[$i]['logintimes'] ; ?>]</td>
      <td><a href="<?php echo __ADMINCP__; ?>=account&do=edit&uid=<?php echo $rs[$i]['uid'] ; ?>">编辑</a> | <a href="<?php echo __ADMINCP__; ?>=account&do=power&uid=<?php echo $rs[$i]['uid'] ; ?>">后台权限</a> | <a href="<?php echo __ADMINCP__; ?>=account&do=fpower&uid=<?php echo $rs[$i]['uid'] ; ?>">版块权限</a> | <a href="<?php echo __ADMINCP__; ?>=account&do=del&uid=<?php echo $rs[$i]['uid'] ; ?>"  onclick='return confirm("确定要删除?\n删除管理员不会删除其发表的文章.");' target="iCMS_FRAME">删除</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="7" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="7"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label>
        <select name="do" id="do" onChange="doaction(this);">
          <option value="empty">========批 量 操 作=======</option>
          <option value="update"> 更新管理组 </option>
          <option value="dels"> 删除 </option>
        </select>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>