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
	function doaction(obj){
		switch(obj.value){ 
			case "dels":
				if(confirm("确定要删除！！！")){
					return true;
				}else{
					obj.value="empty";
					return false;
				}
			break;
		}
	}
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;TAG管理</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>点击ID可查看该TAG</li>
        <li>点击使用数可查看关联内容</li>
        <li>批理编辑操作可不用选择TAG</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __SELF__;?>" method="get">
  <input type="hidden" name="mo" value="tag" />
  <input type="hidden" name="do" value="manage" />
  <table class="adminlist">
    <tr>
      <td class="tipsblock"><select name="sortid" id="sortid">
          <option value="0"> == 按分类 == </option>
          <?php echo $forum->select($_GET['sortid'],0,1,NULL,NULL,true);?>
        </select>
<select name="type" id="type">
          <option value="0"> == 按属性 == </option>
          <option value="0"> 普通标签[type='0'] </option>
          <?php echo contentype("tag",$_GET['type']) ; ?>
        </select>        标签
        <input type="text" name="keywords" class="txt" id="keywords" value="<?php echo $_GET['keywords'];?>" size="30" />
        每页显示
        <input type="text" name="perpage" class="txt" id="perpage" value="<?php echo $_GET['perpage']?$_GET['perpage']:20;?>" style="width:30px;" />
        <select name="status" id="status">
          <option value="-1">状态</option>
          <option value="1"<?php $_GET['status']=='1' && print(' selected="selected"')?>>启用</option>
          <option value="0"<?php $_GET['status']=='0' && print(' selected="selected"')?>>禁用</option>
        </select>
       <input type="submit" class="submit" value="搜索"/></td>
    </tr>
  </table>
</form>
<form action="<?php echo __ADMINCP__; ?>=tag" method="post"  target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>选择</th>
        <th>ID</th>
        <th>排序</th>
        <th>TAG</th>
        <th>TAG归类</th>
        <th>使用数</th>
        <th>状态</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){
    	$iurlArray=array('id'=>$rs[$i]['id'],'link'=>$rs[$i]['link'],'name'=>$rs[$i]['name']);
		$F=$forum->forum[$rs[$i]['sortid']];
		$iurl=$this->iCMS->iurl('tag',array($rs[$i],$F));
		$rs[$i]['url']= $iurl->href;
      ?>
    <tr id="tid<?php echo $rs[$i]['id'];?>" onmouseover="this.style.backgroundColor='#F2F9FD'" onmouseout="this.style.backgroundColor='#FFFFFF'">
      <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'];?>" /></td>
      <td><a href="<?php echo $rs[$i]['url'];?>" target="_blank"><?php echo $rs[$i]['id'];?></a></td>
      <td><input type="text" class="txt" name="ordernum[<?php echo $rs[$i]['id'];?>]" value="<?php echo _int($rs[$i]['ordernum']);?>" style="width:40px;"/></td>
      <td><input type="text" class="txt" name="name[<?php echo $rs[$i]['id'];?>]" value="<?php echo $rs[$i]['name'];?>" style="width:240px;"/></td>
      <td><select name="sortid[<?php echo $rs[$i]['id'];?>]" style="width:auto;">
          <option value="0"> == 暂无归类 == </option>
          <?php echo $forum->select($rs[$i]['sortid'],0,1,NULL,NULL,true);?>
        </select></td>
      <td><a href="<?php echo __ADMINCP__; ?>=tag&do=list&id=<?php echo $rs[$i]['id'];?>" title="点击查看关联内容"><?php echo $rs[$i]['count'];?></a></td>
      <td><?php if ($rs[$i]['status']){   ?><a href="<?php echo __ADMINCP__; ?>=tag&do=disabled&id=<?php echo $rs[$i]['id'];?>" title='点击禁用此TAG' target="iCMS_FRAME">启用</a><?php }else{?><a href="<?php echo __ADMINCP__; ?>=tag&do=open&id=<?php echo $rs[$i]['id'];?>" title='点击启用此TAG' target="iCMS_FRAME">禁用</a><?php }?></td>
      <td><?php if (strstr($this->iCMS->config['tagRule'],'{PHP}')===false){  ?><a href="<?php echo __ADMINCP__; ?>=tag&do=updateHTML&id=<?php echo $rs[$i]['id'];?>" title='生成静态文件' target="iCMS_FRAME"><?php echo file_exists($iurl->path)?"发布":"待发布";?></a> | <?php }?> <a href="<?php echo __ADMINCP__; ?>=tag&do=add&id=<?php echo $rs[$i]['id'];?>">编辑</a> | <a href="<?php echo __ADMINCP__; ?>=tag&do=updateCache&id=<?php echo $rs[$i]['id'];?>&name=<?php echo rawurlencode($rs[$i]['name']);?>" title='更新TAG缓存' target="iCMS_FRAME">更新缓存</a> | <a href="<?php echo __ADMINCP__; ?>=tag&do=del&id=<?php echo $rs[$i]['id'];?>"onClick="return confirm('确定要删除?');" target="iCMS_FRAME">删除</a></td>
    </tr>
    <?php }?>
    <tr>
      <td colspan="8" class="pagenav"><?php echo $this->pagenav;?></td>
    </tr>
    <tr>
      <td colspan="8"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label>
        <select name="do" id="do" onChange="doaction(this);">
        <option value="empty">========批 量 操 作=======</option>
          <option value="edit"> 编辑 </option>
          <option value="dels"> 删除 </option>
          <?php if(strstr($this->iCMS->config['tagRule'],'{PHP}')===false){  ?> <!--option value="html">生成静态</option--> <?php }?>
        </select>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>