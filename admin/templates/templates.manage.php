<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;模板管理</div>
<table class="adminlist" width="100%">
  <thead>
    <tr>
      <th>模板管理</th>
    </tr>
  </thead>
  <tr>
    <td>当前路径：<strong><a href="<?php echo __ADMINCP__; ?>=templates&do=manage"><?php echo $Folder.'/'.$dir ; ?></a></strong></td>
  </tr>
  <?php if ($L['parentfolder']){   ?>
  <tr>
    <td><a href="<?php echo __ADMINCP__; ?>=templates&do=manage&dir=<?php echo $L['parentfolder'] ; ?>"><img src="admin/images/file/parentfolder.gif" border="0" align="absmiddle"></a><strong><a href="<?php echo __ADMINCP__; ?>=templates&do=manage&dir=<?php echo $L['parentfolder'] ; ?>">．．</a></strong></td>
  </tr>
  <?php } 
  	  for($i=0;$i<count($L['folder']);$i++){  ?>
  <tr>
    <td><a href="<?php echo __ADMINCP__; ?>=templates&do=manage&dir=<?php echo $L['folder'][$i]['path'] ; ?>"><img src="admin/images/file/closedfolder.gif" border="0" align="absmiddle"></a><strong><a href="<?php echo __ADMINCP__; ?>=templates&do=manage&dir=<?php echo $L['folder'][$i]['path'] ; ?>"><?php echo $L['folder'][$i]['dir'] ; ?></a></strong></td>
  </tr>
  <?php }   ?>
</table>
<?php if ($L['FileList']){   ?>
<table class="adminlist" width="100%">
  <thead>
    <tr>
      <th>文件名</th>
      <th>文件大小</th>
      <th>最后修改时间</th>
      <th>操作</th>
    </tr>
  </thead>
  <?php for($i=0;$i<count($L['FileList']);$i++){
    $filepath=$L['FileList'][$i]['path'];
      ?>
  <tr>
    <td><?php echo $L['FileList'][$i]['icon'] ; ?> <?php echo $L['FileList'][$i]['name'] ; ?></td>
    <td><?php echo $L['FileList'][$i]['size'] ; ?></td>
    <td><?php echo $L['FileList'][$i]['time'] ; ?></td>
    <td><!--a href="admincp.php?mo=file&do=rename&path=<?php echo $Folder."/".$L['FileList'][$i]['path'] ; ?>">重命名 | </a--> <?php if (in_array($L['FileList'][$i]['ext'],array('htm','html','css','js'))){   ?> <a href="<?php echo __ADMINCP__; ?>=templates&do=edit&path=<?php echo $Folder."/".$L['FileList'][$i]['path'] ; ?>">编辑</a> <?php } if ($L['FileList'][$i]['ext']=='htm'){   ?> | <a href="<?php echo __ADMINCP__; ?>=templates&do=clear&path=<?php echo substr($Folder."/".$L['FileList'][$i]['path'],1) ; ?>" target="iCMS_FRAME">清除缓存</a> <?php }   ?> <!--a href="admincp.php?mo=file&do=delTpl&path=<?php echo $Folder."/".$L['FileList'][$i]['path'] ; ?>">删除</a--></td>
  </tr>
  <?php }   ?>
</table>
<?php }   ?>
</body></html>