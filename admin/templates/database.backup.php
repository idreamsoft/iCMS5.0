<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;数据库管理&nbsp;&raquo;&nbsp;<?php echo $this->action=='backup'?'数据库备份':'数据库修复' ; ?></div>
<form action="<?php echo __ADMINCP__; ?>=database<?php echo $this->action=='backup'?'&do=savebackup':'' ; ?>" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th width="60">选择</th>
        <th width="60"></th>
        <th width="780">数据库表</th>
      </tr>
    </thead>
    <?php foreach($iCMSTable as $n=>$table){  ?>
    <tr>
      <td><input type="checkbox" class="checkbox" name="tabledb[]" value="<?php echo $table ; ?>" /></td>
      <td><?php echo $n+1 ; ?></td>
      <td><?php echo $table ; ?></td>
    </tr>
    <?php }if ($this->action=='backup'){   ?>
    <tr>
      <td colspan="3"><strong>分卷备份</strong></td>
    </tr>
    <tr>
      <td colspan="3"><input name="sizelimit" type=text class="txt" value="2048" size="5">
        KB 
        每个分卷文件长度</td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="3"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'tabledb')" />
        <label for="chkall">全选</label>
        <?php if ($this->action=='repair'){   ?>
        <input name="do" type="radio" class="radio" value="repair_action">
        修复表
        <input name="do" type="radio" class="radio" value="optimize" checked>
        优化表 <?php }  ?>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>