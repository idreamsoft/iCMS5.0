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
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;关键字管理</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>替换内容中的关键字</li>
        <li><a href="<?php echo __ADMINCP__; ?>=setting&do=other">关键字替换次数全局设置</a></li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __SELF__;?>" method="get">
  <input type="hidden" name="mo" value="keywords" />
  <table class="adminlist">
    <tr>
      <td class="tipsblock">
        关键字
        <input type="text" name="keywords" class="txt" id="keywords" value="<?php echo $_GET['keywords'];?>" size="30" />
        替换字
        <input type="text" name="replace" class="txt" id="replace" value="<?php echo $_GET['replace'];?>" size="30" />
        每页显示
        <input type="text" name="perpage" class="txt" id="perpage" value="<?php echo $_GET['perpage']?$_GET['perpage']:20;?>" style="width:30px;" />
        <select name="status" id="status">
          <option value="-1">状态</option>
          <option value="1"<?php $_GET['status']=='1' && print(' selected="selected"')?>>启用</option>
          <option value="0"<?php $_GET['status']=='0' && print(' selected="selected"')?>>禁用</option>
        </select>
       <input type="submit" class="submit" value="搜索"/></td>
    </tr>
  </table>
</form>
<form action="<?php echo __ADMINCP__; ?>=keywords" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="edit" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>ID</th>
        <th>关键字</th>
        <th>替换</th>
        <th>状态</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){  ?>
    <tr id="kid<?php echo $rs[$i]['id'] ; ?>">
      <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
      <td><?php echo $rs[$i]['id'] ; ?></td>
      <td><input type="text" class="txt" name="name[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo dhtmlspecialchars($rs[$i]['keyword']) ; ?>" style="width:120px;" /></td>
      <td><input type="text" class="txt" name="replace[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo dhtmlspecialchars($rs[$i]['replace']) ; ?>" style="width:300px;"/></td>
      <td><?php if ($rs[$i]['status']){   ?> <a href="<?php echo __ADMINCP__; ?>=keywords&do=disabled&id=<?php echo $rs[$i]['id'] ; ?>" title='点击禁用此TAG' target="iCMS_FRAME">启用</a> <?php }else{    ?> <a href="<?php echo __ADMINCP__; ?>=keywords&do=open&id=<?php echo $rs[$i]['id'] ; ?>" title='点击启用此TAG' target="iCMS_FRAME">禁用</a> <?php }  ?></td>
      <td><a href="<?php echo __ADMINCP__; ?>=keywords&do=add&id=<?php echo $rs[$i]['id'] ; ?>">编辑</a>| <a href="<?php echo __ADMINCP__; ?>=keywords&do=del&id=<?php echo $rs[$i]['id'] ; ?>"onClick="return confirm('确定要删除?');" target="iCMS_FRAME">删除</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="6" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="6"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label>
       <select name="do" id="do" onChange="doaction(this);">
        <option value="empty">========批 量 操 作=======</option>
          <option value="edit"> 编辑 </option>
          <option value="dels"> 删除 </option>
        </select>

        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>