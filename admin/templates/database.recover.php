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
function yn(f){
    iCMS.D('备份恢复功能将覆盖原来的数据,您确认要导入备份数据?','导入备份数据').dialog({
        modal: true,
        buttons: {
            '确认导入备份': function() {
                $('#iCMS_FRAME').attr('src','<?php echo __ADMINCP__; ?>=database&do=bakin&pre='+f);
            },
            '取消': function() {
                 $(this).dialog('close');
            }
        }
    });
}
</script>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;数据库管理&nbsp;&raquo;&nbsp;数据库恢复</div>
<form action="<?php echo __ADMINCP__; ?>=database&do=del" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
    <tr>
      <th style="width:60px;">删</th>
      <th style="width:60px;">ID</th>
      <th>文件名</th>
      <th>版本</th>
      <th>备份时间</th>
      <th>卷号</th>
      <th>导入</th>
    </tr>
    </thead>
    <?php foreach($filedb as $n=>$file){  ?>
    <tr>
      <td><input type="checkbox" class="checkbox" name="delete[]" value="<?php echo $file['name'] ; ?>" /></td>
      <td><?php echo $n+1 ; ?></td>
      <td><?php echo $file['name'] ; ?></td>
      <td><?php echo $file['version'] ; ?></td>
      <td><?php echo $file['time'] ; ?></td>
      <td><?php echo $file['num'] ; ?></td>
      <td><a href="javascript:yn('<?php echo $file['pre'] ; ?>');">导入</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="7"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'delete')" />
        <label for="chkall">全选</label>
          <input type="submit" class="submit" name="forumlinksubmit" value="删除"  />
      </td>
    </tr>
  </table>
</form>
</div>
</body></html>