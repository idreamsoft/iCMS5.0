<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;留言管理</div>
<form action="<?php echo __SELF__;?>" method="get">
  <input type="hidden" name="mo" value="plugins" />
  <input type="hidden" name="name" value="message" />
  <table class="adminlist">
    <tr>
      <td class="tipsblock">
        关键字
        <input type="text" name="keywords" class="txt" id="keywords" value="<?php echo $_GET['keywords'];?>" size="30" />
        每页显示
        <input type="text" name="perpage" class="txt" id="perpage" value="<?php echo $_GET['perpage']?$_GET['perpage']:20;?>" style="width:30px;" />
        <input name="status" type="checkbox" class="checkbox" value="1" <?php if($_GET['status']){ echo 'checked="checked"';}?> />悄悄话
       <input type="submit" class="submit" value="搜索"/></td>
    </tr>
  </table>
</form>
<form action="<?php echo __PLUGINACP__; ?>=delete" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>留言者</th>
        <th>E-mail</th>
        <th>网址</th>
        <th>IP</th>
        <th>时间</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){?>
    <tbody id="mid<?php echo $rs[$i]['id'] ; ?>">
      <tr>
        <td><input type="checkbox" class="checkbox" name="delete[]" value="<?php echo $rs[$i]['id'];?>" /></td>
        <td><?php echo $rs[$i]['author'];?></td>
        <td><?php echo $rs[$i]['email'] ; ?></td>
        <td><?php echo $rs[$i]['url'] ; ?></td>
        <td><?php echo $rs[$i]['ip'] ; ?></td>
        <td><?php echo get_date($rs[$i]['addtime'],"Y-m-d H:i:s") ; ?></td>
        <td><a href="javascript:void(0);" onclick="$('#reply<?php echo $rs[$i]['id'] ; ?>').toggle();">回复</a> | <a href="<?php echo __PLUGINACP__; ?>=del&id=<?php echo $rs[$i]['id'] ; ?>"onclick="return confirm('确定要删除?');" target="iCMS_FRAME">删除</a></td>
      </tr>
      <tr>
        <td><?php echo $rs[$i]['status']?'悄悄话':'留言';?>(<?php echo $rs[$i]['id'] ; ?>)</td>
        <td colspan="6"><?php echo $rs[$i]['content'] ; ?> <?php if($rs[$i]['reply']){  ?>
          <blockquote style="background-color:#F7F7F7;border:#E5E5E5 solid 1px; padding:4px;"> <?php echo $rs[$i]['reply'] ; ?> </blockquote>
          <?php } ?>
          <blockquote id="reply<?php echo $rs[$i]['id'] ; ?>" style="display:none;background-color:#F7F7F7;border:#E5E5E5 solid 1px; padding:4px;width:405px">回复：<br />
            <textarea id="reply_textarea_<?php echo $rs[$i]['id'] ; ?>" rows="6" onkeyup="textareasize(this)" name="reply" cols="50" class="tarea"><?php echo $rs[$i]['reply'] ; ?></textarea>
            <div class="fixsel">
              <input type="button" class="btn" value="回复"  onclick="_reply(<?php echo $rs[$i]['id'] ; ?>);"/>
              <input type="button" class="btn" value="取消" onclick="$('#reply<?php echo $rs[$i]['id'] ; ?>').hide();"/>
            </div>
          </blockquote></td>
      </tr>
    </tbody>
    <?php }  ?>
    <tr>
      <td height="22" colspan="7" class="pagenav"><?php echo $pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="7"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'delete')" />
        <label for="chkall">删?</label>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
function _reply(id){
	$.post("<?php echo __PLUGINACP__; ?>=reply",{ "id": id, "replytext": $("#reply_textarea_"+id).val()},
		function(data){
			window.location.reload();
		} 
	);
}
</script>
</body></html>