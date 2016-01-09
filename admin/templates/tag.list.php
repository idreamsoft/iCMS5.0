<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;TAG管理&nbsp;&raquo;&nbsp;TAG[<?php echo $tagName ; ?>]关联管理</div>
<script type="text/javascript">
$(function(){
<?php if(isset($_GET['at'])){  ?>$("#at").val("<?php echo $_GET['at'] ; ?>");
<?php } if($_GET['fid']){  ?>$("#fid").val("<?php echo $_GET['fid'] ; ?>");
<?php } if($_GET['st']){  ?>$("#st").val("<?php echo $_GET['st'] ; ?>");
<?php } if($_GET['sub']=="on"){  ?>$("#sub").attr("checked",true);
<?php } if($_GET['nopic']=="on"){  ?>$("#nopic").attr("checked",true);
<?php }  ?>
$(".datepicker").datepicker();
});
function doaction(obj){
	switch(obj.value){ 
		case "dels":
			if(confirm("确定要删除！！！")){
				return true;
			}else{
				obj.value="";
				return false;
			}
		break;
	}
}
</script>
<script type="text/javascript" src="admin/js/jquery.ui.datepicker.js?5.0-1.7.3"></script>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>点击ID可查看该文章</li>
        <li>删除关联只删除TAG相关数据，不会删除文章</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __SELF__ ; ?>" method="get">
  <input type="hidden" name="mo" value="tag" />
  <input type="hidden" name="do" value="list" />
  <input type="hidden" name="id" value="<?php echo $_GET['id'] ; ?>" />
  <table class="adminlist">
    <tr>
      <td class="tipsblock"><select name="at" id="at">
          <option value="-1"> == 文章属性 == </option>
          <option value="0">普通文章[type='0']</option>
          <?php echo contentype("article") ; ?>
        </select>
        <select name="fid" id="fid">
          <option value="0"> == 按栏目 == </option>
          <?php echo $forum->select(0,0,1,'all',UULL,true) ; ?>
        </select>
        <input type="checkbox" name="sub" class="checkbox" id="sub"/>
        子栏目
        <input type="checkbox" name="nopic" class="checkbox" id="nopic"/>
        无缩略图 </td>
    </tr>
    <tr>
      <td class="tipsblock">开始时间：
        <input type="text" class="txt datepicker" name="starttime" value="<?php echo $_GET['starttime'] ; ?>" style="width:80px">
        -结束时间：
        <input type="text" class="txt datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" style="width:80px">
        关键字：
        <input type="text" name="keywords" class="txt" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" size="30" />
        每页显示
        <input type="text" name="perpage" class="txt" id="perpage" value="<?php echo $_GET['perpage']?$_GET['perpage']:20 ; ?>" style="width:30px;" />
        <input type="submit" class="btn" value="搜索"/></td>
    </tr>
  </table>
</form>
<form action="<?php echo __ADMINCP__; ?>=article" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th width="4%" height="22">选择</th>
        <th width="4%">ID</th>
        <th width="4%">排序</th>
        <th>标 题</th>
        <th width="120">发布时间</th>
        <th width="10%">栏目</th>
        <th width="7%">点/评</th>
        <th width="4%">属性</th>
        <th width="120">操作</th>
      </tr>
    </thead>
    <tbody id="alist">
      <?php for($i=0;$i<$_count;$i++){
      	$ourl=$rs[$i]['url'];
      	$F=$forum->forum[$rs[$i]['fid']];
		$iurl=$this->iCMS->iurl('show',array($rs[$i],$F));
		empty($ourl) && $htmlurl=$iurl->path;
		$rs[$i]['url']=$iurl->href;
	  ?>
      <tr id="aid<?php echo $rs[$i]['id'] ; ?>" onmouseover="this.style.backgroundColor='#F2F9FD'" onmouseout="this.style.backgroundColor='#FFFFFF'">
        <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
        <td><input type="text" name="order[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['orderNum'] ; ?>" style="width:24px;border:1px #F6F6F6 solid;"/></td>
        <td><a href="<?php echo $rs[$i]['url'] ; ?>" target="_blank"><?php echo $rs[$i]['id'] ; ?></a></td>
        <td><div style="height:22px;width:100%;overflow:hidden;"><a href="<?php echo __ADMINCP__; ?>=article&do=status&id=<?php echo $rs[$i]['id'] ; ?>&s=<?php echo $rs[$i]['status'] ; ?>&pt=<?php echo $rs[$i]['postype'] ; ?>" title="点击<?php echo $_ptxt[$rs[$i]['status']] ; ?>"><img src="admin/images/article.gif" align="absmiddle"></a> <?php if($rs[$i]['isPic'])echo '<img src="admin/images/image.gif" align="absmiddle">'  ?> <?php echo $rs[$i]['title'] ; ?></div></td>
        <td><?php echo get_date($rs[$i]['pubdate'],'Y-m-d H:i');?></a></td>
        <td><a href="<?php echo __ADMINCP__; ?>=article&do=manage&fid=<?php echo $rs[$i]['fid'] ; ?><?php echo $uri ; ?>"><?php echo $F['name'] ; ?></a></td>
        <td><?php echo $rs[$i]['hits'] ; ?>/<a href="<?php echo $this->iCMS->publicURL ; ?>/comment.php?indexId=<?php echo $rs[$i]['id'] ; ?>&mId=0&sortId=<?php echo $rs[$i]['fid'] ; ?>" target="_blank"><?php echo $rs[$i]['comments'] ; ?></a></td>
        <td><?php if($ourl){  ?><img src="admin/images/olink.gif" align="absmiddle" alt="外部链接"><?php }elseif($fid && $fid!=$rs[$i]['fid']){  ?>虚<?php }else{ echo $rs[$i]['type']; }  ?></td>
        <td>
		<?php if($fid&&$fid!=$rs[$i]['fid']){?> 
        	<a href="<?php echo __ADMINCP__; ?>=article&do=delvlink&id=<?php echo $rs[$i]['id'] ; ?>&fid=<?php echo $fid ; ?>" onclick="return confirm('删除<?php echo $rs[$i]['title'] ; ?>此栏目的虚链接?');" target="iCMS_FRAME">删除</a> 
		<?php }else{?> 
			<?php if ($F['mode'] && strstr($F['contentRule'],'{PHP}')===false && $rs[$i]['status']=="1" && empty($ourl)){  ?> 
            <a href="<?php echo __ADMINCP__; ?>=article&do=updateHTML&id=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME"><?php echo file_exists($htmlurl)?"更新":"待发布" ; ?></a> | 
            <?php }  ?> 
            <a href="<?php echo __ADMINCP__; ?>=article&do=add&id=<?php echo $rs[$i]['id'] ; ?>">编辑</a> | 
            <!--<a href="<?php echo __ADMINCP__; ?>=push&do=add&id=<?php echo $rs[$i]['id'] ; ?>&TB_iframe=true&height=480&width=800" class="thickbox" title="推送">推送</a> | -->
            <a href="<?php echo __ADMINCP__; ?>=article&do=del&id=<?php echo $rs[$i]['id'] ; ?>" onclick="return confirm('确定要删除<?php echo HTML2JS($rs[$i]['title']) ; ?>');" target="iCMS_FRAME">删除</a> 
		<?php }  ?></td>
      </tr>
      <?php }  ?>
    </tbody>
    <tr>
      <td colspan="10" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="10"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label>
          <select name="do" id="do" onChange="doaction(this);">
            <option value="">========批 量 操 作=======</option>
            <option value="dels">删除</option>
          </select>
          <input type="submit" class="submit" name="forumlinksubmit" value="提交"  />
        </td>
    </tr>
  </table>
</form>
</body></html>