<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>

<table class="adminlist">
  <tr>
    <td><form action="<?php echo __SELF__ ; ?>" method="get" target="iCMS_FRAME">
        <input type="hidden" name="mo" value="dialog" />
        <input type="hidden" name="do" value="search_article" />
        <input type="hidden" name="callback" value="<?php echo $_GET['callback'] ; ?>" />
        关键字：
        <input type="text" name="keywords" class="txt" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" size="30" />
        <input type="submit" class="submit" value="搜索"/>
      </form></td>
  </tr>
</table>
<table class="adminlist">
  <form method="post" onsubmit="return false;" id="iDF">
    <thead>
      <tr>
        <th width="5%">选择</th>
        <th width="4%">ID</th>
        <th>标 题</th>
        <th width="16%">发布时间</th>
        <th width="10%">栏目</th>
        <th width="7%">点/评</th>
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
      <tr>
        <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
        <td><a href="<?php echo $rs[$i]['url'] ; ?>" target="_blank"><?php echo $rs[$i]['id'] ; ?></a></td>
        <td><div style="height:22px;width:100%;overflow:hidden;"><?php if($rs[$i]['pic'])echo '<img src="admin/images/file/image.gif" align="absmiddle">'  ?> <span id="title_<?php echo $rs[$i]['id'] ; ?>"><?php echo $rs[$i]['title'] ; ?></span></div></td>
        <td><?php echo get_date($rs[$i]['pubdate'],'Y-m-d H:i');?></a></td>
        <td><a href="<?php echo __ADMINCP__; ?>=dialog&do=article&fid=<?php echo $rs[$i]['fid'] ; ?><?php echo $uri ; ?>&callback=<?php echo $callback ; ?>" target="iCMS_FRAME"><?php echo $F['name'] ; ?></a></td>
        <td><?php echo $rs[$i]['hits'] ; ?>/<?php echo $rs[$i]['comments'] ; ?></td>
      </tr>
      <?php }  ?>
    </tbody>
    <tr>
      <td colspan="6" align="right" class="pagenav"><?php echo str_replace(array('window.location=','+this.value'),array('reloadDialog(','+this.value);'),$pagenav);?></td>
    </tr>
    <tr>
      <td colspan="6"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label></td>
    </tr>
  </form>
</table>
<script type="text/javascript">
/*$("#alist tr").mouseover(function(){
  $(this).find("td").css("background-color","#F2F9FD");
}).mouseout(function(){
  $(this).find("td").css("background-color","#FFFFFF");
});*/
$(".pagenav a").click(function(){
	var url = $(this).attr("href");
	reloadDialog(url);
	return false;
});
$('#iDF').submit(function(){
  var IDobj=$("[name='id[]']:checked");
  var option="";
  for (var i = 0; i < IDobj.length; i++) {
	   var id=$(IDobj[i]).val();
	   var title=$("#title_"+id).val();
	   option+='<option value="'+id+'">'+title+'</option>';
   }
   if(option!="")$('#<?php echo $_GET['callback'] ; ?>').append(option);
   iCMS.closeDialog();
	return false;
});
</script>
