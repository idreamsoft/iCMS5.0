<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;会员管理</div>
<form action="<?php echo __ADMINCP__; ?>=user" method="post" target="iCMS_FRAME">
  <input name="do" type="hidden" value="dels" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>ID</th>
        <th>用户名[昵称]</th>
        <th>最后登陆IP</th>
        <th>最后登陆时间 [登陆次数]</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){
    	$rs[$i]['info']=unserialize($rs[$i]['info']);
      ?>
    <tr id="uid<?php echo $rs[$i]['uid'] ; ?>">
      <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['uid'] ; ?>" /></td>
      <td><?php echo $rs[$i]['uid'] ; ?></td>
      <td><?php echo $rs[$i]['username'];if($rs[$i]['info']['nickname']) echo "[{$rs[$i]['info']['nickname']}]"  ?></td>
      <td><?php echo $rs[$i]['lastip'] ; ?></td>
      <td><?php echo get_date($rs[$i]['lastlogintime'],"Y-m-d H:i") ; ?> [<?php echo $rs[$i]['logintimes'] ; ?>]</td>
      <td><a href="<?php echo __ADMINCP__; ?>=article&do=manage&act=user&userid=<?php echo $rs[$i]['uid'] ; ?>">文章</a> | <a href="<?php echo __ADMINCP__; ?>=article&do=manage&act=user&userid=<?php echo $rs[$i]['uid'] ; ?>&type=draft">审核</a> | <a href="<?php echo __ADMINCP__; ?>=user&do=edit&userid=<?php echo $rs[$i]['uid'] ; ?>">编辑</a> | <a href="<?php echo __ADMINCP__; ?>=user&do=del&userid=<?php echo $rs[$i]['uid'] ; ?>"  onclick='return confirm("确定要删除?\n删除会员不会删除其发表的文章.");' target="iCMS_FRAME">删除</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="6" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="6"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">删除?</label>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>