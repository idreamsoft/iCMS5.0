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

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;自定义模块</div>
<form action="<?php echo __ADMINCP__;?>=models" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>ID</th>
        <th>模块名称</th>
        <th>模块表名/模块名</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){?>
    <tr>
      <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'];?>" /></td>
      <td><?php echo $rs[$i]['id'];?></td>
      <td><?php echo $rs[$i]['name'];?></td>
      <td><?php echo $rs[$i]['table'];?></td>
      <td>
        <?php if($rs[$i]['binding']){?>
        <a href="<?php echo __ADMINCP__;?>=<?php echo $rs[$i]['table'];?>&do=manage">内容管理</a> |
        <a href="<?php echo __ADMINCP__;?>=<?php echo $rs[$i]['table'];?>&do=add">添加内容</a> |
        <?php }else{ ?>
        <a href="<?php echo __ADMINCP__;?>=models&do=manage&table=<?php echo $rs[$i]['table'];?>&id=<?php echo $rs[$i]['id']?>">内容管理</a> |
        <a href="<?php echo __ADMINCP__;?>=models&do=add&table=<?php echo $rs[$i]['table'];?>&id=<?php echo $rs[$i]['id']?>">添加内容</a> |
        <a href="<?php echo __ADMINCP__;?>=models&do=managefield&id=<?php echo $rs[$i]['id'];?>">字段管理</a> |
        <a href="<?php echo __ADMINCP__;?>=models&do=Addfield&id=<?php echo $rs[$i]['id'];?>">添加字段</a> |
	    <?php } ?>
        <a href="<?php echo __ADMINCP__;?>=models&do=truncate&table=<?php echo $rs[$i]['table']?>"  onclick='return confirm("确定要清空?\n此操作会清空该模块的所有数据.");'>清空模块</a> | 
        <a href="<?php echo __ADMINCP__;?>=models&do=add&id=<?php echo $rs[$i]['id'];?>">编辑模块</a> |
        <a href="<?php echo __ADMINCP__;?>=models&do=del&id=<?php echo $rs[$i]['id'];?>"onClick="return confirm('确定要删除模块?');" target="iCMS_FRAME">删除</a>
      </td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="5" class="pagenav"><?php echo $this->pagenav;?></td>
    </tr>
  </table>
</form>
</body></html>