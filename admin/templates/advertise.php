<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;广告管理</div>
<script type="text/javascript">
	function doaction(obj){
		switch(obj.value){ 
			case "del":
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
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>广告标签名支持中文，例：&lt;!--{iCMS:advertise name="首页通栏广告"}--&gt;</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=advertise" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th></th>
        <th>广告标签</th>
        <th>广告描述</th>
        <th>展现方式</th>
        <th>起始时间</th>
        <th>终止时间</th>
        <th>状态</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php for($i=0;$i<$_count;$i++){  ?>
    <tr>
      <td><input class="checkbox" type="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>"><input type="hidden" name="varname[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['varname'] ; ?>" /></td>
      <td>&lt;!--{iCMS:advertise name="<?php echo $rs[$i]['varname'] ; ?>"}--&gt;</td>
      <td><?php echo $rs[$i]['title'] ; ?></td>
      <td><?php switch ($rs[$i]['style']){case 'code':echo "代码";break;case 'text':echo "文字";break;case 'image':echo "图片";break;case 'flash':echo "FLASH";break;}  ?></td>
      <td><?php echo get_date($rs[$i]['starttime'],'Y-m-d');?></td>
      <td><?php echo empty($rs[$i]['endtime'])?'无限制':get_date($rs[$i]['endtime'],'Y-m-d');?></td>
      <td><?php echo $rs[$i]['status']?'<a href="'.__SELF__.'?mo=advertise&do=status&id='.$rs[$i]['id'].'&act=0" target="iCMS_FRAME">启用</a>':'<a href="'.__SELF__.'?mo=advertise&do=status&id='.$rs[$i]['id'].'&act=1" target="iCMS_FRAME">关闭</a>' ; ?></td>
      <td><a href="<?php echo __ADMINCP__; ?>=advertise&do=add&advid=<?php echo $rs[$i]['id'] ; ?>" class="act">修改</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td colspan="8" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="8"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label>
        <select name="do" id="do" onChange="doaction(this);">
          <option value="">====批量操作====</option>
          <option value="del">删除</option>
          <option value="js">生成js</option>
        </select>
        <input type="submit" class="submit" name="advsubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>