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

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;搜索统计</div>
<form action="<?php echo __ADMINCP__; ?>=search" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>ID</th>
        <th>关键字</th>
        <th>搜索次数</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){
	    $rs[$i]['search']=str_replace(array('\%','\_'),array('%','_'),$rs[$i]['search']);
      ?>
    <tr>
      <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
      <td><?php echo $rs[$i]['id'] ; ?></td>
      <td><input type="text" class="txt" name="search[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo dhtmlspecialchars($rs[$i]['search']) ; ?>" style="width:300px;" /></td>
      <td><input type="text" class="txt" name="times[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo (int)$rs[$i]['times'] ; ?>" style="width:120px;"/></td>
      <td><a href="<?php echo __ADMINCP__; ?>=search&do=del&id=<?php echo $rs[$i]['id'] ; ?>"onClick="return confirm('确定要删除?');" target="iCMS_FRAME">删除 </a></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="5" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="5"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
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