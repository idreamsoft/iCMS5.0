<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;文章自定义属性管理</div>
<form action="<?php echo __ADMINCP__; ?>=contentype" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>ID</th>
        <th>属性名称</th>
        <th>值</th>
        <th>类型</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php if($this->array)foreach($this->array AS $id=>$CT){  ?>
    <tr>
      <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $CT['id'] ; ?>" /></td>
      <td><?php echo $CT['id'] ; ?></td>
      <td><input type="text" class="txt" name="name[<?php echo $CT['id'] ; ?>]" value="<?php echo $CT['name'] ; ?>" style="width:360px;" /></td>
      <td><input type="text" class="txt" name="val[<?php echo $CT['id'] ; ?>]" value="<?php echo $CT['val'] ; ?>" style="width:60px;" /></td>
      <td><input type="text" class="txt" name="type[<?php echo $CT['id'] ; ?>]" value="<?php echo $CT['type'] ; ?>" style="width:60px;" /></td>
      <td><a href="<?php echo __ADMINCP__; ?>=contentype&do=del&id=<?php echo $CT['id'] ; ?>"onClick="return confirm('确定要删除?');" target="iCMS_FRAME">删除</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="6"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">删?</label>        <select name="do">
          <option value="edit">===编辑===</option>
          <option value="dels">===删除===</option>
        </select>
<input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
<br />
<form action="<?php echo __ADMINCP__; ?>=contentype" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="add" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>添加新属性</th>
      </tr>
    </thead>
    <tr>
      <td>属性名称：
        <input type="text" class="txt" name="name" value="新属性" style="width:360px;" /></td>
    </tr>
    <tr>
      <td>属 性 值：
        <input type="text" class="txt" name="val" value="0" style="width:360px;" /></td>
    </tr>
    <tr>
      <td>属性类型：
        <input type="text" class="txt" name="type" value="article" style="width:60px;" />
        article:文章 push:推送</td>
    </tr>
    <tr class="nobg">
      <td><input type="submit" class="submit" name="forumlinksubmit" value="添加"  /></td>
    </tr>
  </table>
</form>
</body></html>