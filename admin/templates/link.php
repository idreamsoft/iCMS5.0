<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;友情链接管理</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>未填写文字说明的项目将以紧凑型显示。</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=link" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="edit" />
  <table class="adminlist">
    <thead>
      <tr>
        <th></th>
        <th>显示顺序</th>
        <th>分类ID</th>
        <th>网站名称</th>
        <th>网站 URL</th>
        <th>文字说明</th>
        <th>logo 地址(可选)</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){  ?>
    <tr>
      <td><input type="checkbox" class="checkbox" name="delete[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
      <td><input type="text" class="txt" name="orderNum[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['orderNum'] ; ?>" size="3" /></td>
      <td><input type="text" class="txt" name="sortid[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['sortid'] ; ?>" size="3" /></td>
      <td><input type="text" class="txt" name="name[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['name'] ; ?>" size="15" /></td>
      <td><input type="text" class="txt" name="url[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['url'] ; ?>" size="20" /></td>
      <td><input type="text" class="txt" name="description[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['desc'] ; ?>" size="30" /></td>
      <td><?php if($rs[$i]['logo']){echo '<img src="'.$rs[$i]['logo'].'" align="absmiddle">';} ?>
        <input type="text" class="txt" name="logo[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['logo'] ; ?>" size="20" /></td>
    </tr>
    <?php }  ?>
    <tr>
      <td height="22" colspan="6" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr class="nobg">
      <td colspan="6"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'delete')" />
        <label for="chkall">删?</label>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>