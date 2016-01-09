<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<script type="text/javascript" src="admin/js/jquery.ui.datepicker.js?5.0-1.7.3"></script>
<script type="text/javascript">
$(function(){
	$(".datepicker").datepicker();
})
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;评论管理</div>
<form action="<?php echo __SELF__ ; ?>" method="get">
  <input type="hidden" name="mo" value="comment" />
  <table class="adminlist">
    <tr>
      <td class="tipsblock"> 开始时间：
        <input type="text" class="txt datepicker" name="starttime" value="<?php echo $_GET['starttime'] ; ?>" style="width:80px">
        -结束时间：
        <input type="text" class="txt datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" style="width:80px">
        <select name="st" id="st">
          <option value="title"<?php $_GET['st']=='title' && print(' selected="selected"')?>>标题</option>
          <option value="contents"<?php $_GET['st']=='contents' && print(' selected="selected"')?>>评论内容</option>
        </select>
    	模型
    	<select name="mid" id="mid">
          <?php echo model::select($_GET['mid']) ; ?>
        </select>
        <input type="text" name="keywords" class="txt" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" size="30" />
        每页显示
        <input type="text" name="perpage" class="txt" id="perpage" value="<?php echo $_GET['perpage']?$_GET['perpage']:20 ; ?>" style="width:30px;" />
        <select name="status" id="status">
          <option value="-1">状态</option>
          <option value="1"<?php $_GET['status']=='1' && print(' selected="selected"')?>>通过审核</option>
          <option value="0"<?php $_GET['status']=='0' && print(' selected="selected"')?>>未审核</option>
        </select>
        <input type="submit" class="submit" value="搜索"/></td>
    </tr>
  </table>
</form>
<form action="<?php echo __ADMINCP__; ?>=comment" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th style="width:36px;">选择</th>
        <th>文章</th>
        <th>评论者</th>
        <th>评论IP</th>
        <th>支持/反对</th>
        <th>时间</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){?>
    <tbody id="cid<?php echo $rs[$i]['id'] ; ?>" onmouseover="this.style.backgroundColor='#F2F9FD'" onmouseout="this.style.backgroundColor='#FFFFFF'">
	    <tr>
	      <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" />
	        <input name="indexId[<?php echo $rs[$i]['id'] ; ?>]" type="hidden" value="<?php echo $rs[$i]['indexId'] ; ?>"/></td>
	      <td><a href="<?php echo __ADMINCP__; ?>=article&do=preview&id=<?php echo $rs[$i]['indexId'] ; ?>"><?php echo $rs[$i]['title'] ; ?></a></td>
	      <td><?php if ($rs[$i]['userId']){ ?><a href="<?php echo __ADMINCP__; ?>=user&do=view&uid=<?php echo $rs[$i]['userId'] ; ?>"><?php echo $rs[$i]['username'] ; ?></a><?php }else{ echo $rs[$i]['username'];} ?></td>
	      <td><?php echo $rs[$i]['ip'] ; ?></td>
	      <td><?php echo $rs[$i]['up'] ; ?>/<?php echo $rs[$i]['down'] ; ?></td>
	      <td><?php echo get_date($rs[$i]['addtime'],'Y-m-d H:i:s');?></td>
	      <td><a href="<?php echo $this->iCMS->config['publicURL']; ?>/comment.php?indexId=<?php echo $rs[$i]['indexId'] ; ?>&mId=0&sortId=<?php echo $rs[$i]['sortId'];?>" target="_blank">查看详细评论</a> | <?php if ($rs[$i]['status']=='1'){   ?> <a href="<?php echo __ADMINCP__; ?>=comment&do=cancelexamine&id=<?php echo $rs[$i]['id'] ; ?>&indexId=<?php echo $rs[$i]['indexId'] ; ?>" target="iCMS_FRAME">取消审核</a> <?php }else{    ?> <a href="<?php echo __ADMINCP__; ?>=comment&do=examine&id=<?php echo $rs[$i]['id'] ; ?>&indexId=<?php echo $rs[$i]['indexId'] ; ?>" target="iCMS_FRAME">通过审核</a> <?php }  ?>| <a href="<?php echo __ADMINCP__; ?>=comment&do=del&id=<?php echo $rs[$i]['id'] ; ?>&indexId=<?php echo $rs[$i]['indexId'] ; ?>"onclick="return confirm('确定要删除?');" target="iCMS_FRAME">删除</a></td>
	    </tr>
	    <tr>
	      <td>评论(<?php echo $rs[$i]['id'] ; ?>):</td>
	      <td colspan="6"><div class="contents" title="<?php echo $rs[$i]['contents']; ?>"><?php echo $rs[$i]['contents']; ?></div></td>
	    </tr>
    </tbody>
    <?php }  ?>
    <tr>
      <td colspan="7" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="7"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label>
        <select name="do">
          <option value="empty">---批量操作---</option>
          <option value="status1">通过审核</option>
          <option value="status0">取消审核</option>
          <option value="dels">删除</option>
        </select>
        <input type="submit" class="btn" name="forumlinksubmit" value="提交"  /> </td>
    </tr>
  </table>
</form>
<style type="text/css">
.contents{padding:4px; background:#ECECEC;line-height:120%; height:40px; overflow:hidden;}
</style>
</body></html>