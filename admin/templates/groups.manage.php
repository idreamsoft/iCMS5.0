<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;组管理</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>超级管理员禁止删除</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=groups" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="edit" />
  <input type="hidden" name="type" value="a" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>排序</th>
        <th>名称</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php 
		$rs	= $group->group['a'];
		$_count	= count($rs);
      	for($i=0;$i<$_count;$i++){
      ?>
    <tr id="gid<?php echo $rs[$i]['gid'] ; ?>">
      <td><input type="text" name="order[<?php echo $rs[$i]['gid'] ; ?>]" value="<?php echo $rs[$i]['order'] ; ?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
      <td><input name="name[<?php echo $rs[$i]['gid'] ; ?>]" type="text" class="txt" value="<?php echo $rs[$i]['name'] ; ?>"/></td>
      <td><a href="<?php echo __ADMINCP__; ?>=groups&do=power&groupid=<?php echo $rs[$i]['gid'] ; ?>">后台权限</a> | <a href="<?php echo __ADMINCP__; ?>=groups&do=fpower&groupid=<?php echo $rs[$i]['gid'] ; ?>">栏目权限</a> <?php if($rs[$i]['gid']!='1'){  ?> | <a href="<?php echo __ADMINCP__; ?>=groups&do=del&groupid=<?php echo $rs[$i]['gid'] ; ?>"  onclick='return confirm("确定要删除该管理组?");' target="iCMS_FRAME">删除</a><?php }  ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <td><input type="text" name="addneworder" value="<?php echo $i+1 ; ?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
      <td><input name="addnewname" type="text" class="txt" value=""/>
        添加新组</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" value="提交"  /></td>
    </tr>
  </table>
</form>
<?php /*
  <table class="tb tb2 nobdb" id="tips">
    <tr>
      <th colspan="15" class="partition">会员组</th>
    </tr>
    <tr>
      <td class="tipsblock"><ul id="tipslis">
          <li></li>
        </ul></td>
    </tr>
  </table>
  <form action="<?=__SELF__?>?mo=group&do=post" method="post">
    <input type="hidden" name="action" value="edit" />
    <input type="hidden" name="type" value="u" />
    <table class="adminlist">
      <tr>
        <th>排序</th>
        <th>名称</th>
      </tr>
      <?php
    	$rs	= $group->group['u'];
		$_count	= count($rs);
      	for($i=0;$i<$_count;$i++){
    ?>
      <tr>
        <td><input type="text" name="order[<?=$rs[$i]['gid']?>]" value="<?=$rs[$i]['order']?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
        <td><input name="name[<?=$rs[$i]['gid']?>]" type="text" class="txt" value="<?=$rs[$i]['name']?>"/></td>
      </tr>
      <?php }?>
      <tr>
        <td><input type="text" name="addneworder" value="<?=$i+1?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
        <td><input name="addnewname" type="text" class="txt" value=""/>添加新组</td>
      </tr>
      <tr class="nobg">
        <td class="td25"></td>
        <td colspan="6"><div class="fixsel"> <input type="submit" class="btn" value="提交"  /> </div></td>
      </tr>
    </table>
  </form>
  */  ?>
</body></html>