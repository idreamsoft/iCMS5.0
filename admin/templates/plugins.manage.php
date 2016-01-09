<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;插件管理</div>

<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>插件，例：&lt;!--{iCMS:plugins name="archives"}--&gt;</li>
        <li>卸载操作 如果插件有提供数据库卸载文件(uninstall.sql),将会删除插件相关的数据.</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=plugins" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th>插件名称</th>
        <th>插件描述</th>
        <th>作者</th>
        <th>版本</th>
        <th>调用方法</th>
        <th>状态</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php foreach($rs AS $key=>$plugName){
    	if($rs=plugin::config($plugName)){
   	?>
    <tr>
      <td><a href="<?php echo $rs['config']['Home'];?>" target="_blank"><?php echo $rs['config']['Name'];?></a></td>
      <td><?php echo $rs['config']['Description'];?></td>
      <td><a href="<?php echo $rs['config']['Blog'];?>" target="_blank"><?php echo $rs['config']['Author'];?></a></td>
      <td><?php echo $rs['config']['Version'];?></td>
      <td><?php echo htmlspecialchars($rs['config']['TAG']);?></td>
      <td><?php echo $plugins[$plugName]['isSetup']?($plugins[$plugName]['status']?'使用中':'已停用'):'未安装';?></td>
      <td>
	    <a href="<?php echo __ADMINCP__; ?>=plugins&do=readme&name=<?php echo $plugName;?>" target="iCMS_FRAME">查看说明</a>
        <?php if ($plugins[$plugName]['isSetup']){?>
	       	<?php if ($rs['admincp']['url']){?>
	         | <a href="<?php echo __ADMINCP__; ?>=<?php echo $rs['admincp']['url'];?>">后台</a>
	        <?php }?>
	        <?php if ($rs['config']['URL']){?>
	         | <a href="<?php echo $rs['config']['URL']=='1'?$this->iCMS->config['publicURL'].'/plugins.php?name='.$plugName:$rs['config']['URL'];?>" target="_blank">前台</a>
	        <?php }?>
	        <?php if ($plugins[$plugName]['status']){?>
	        | <a href="<?php echo __ADMINCP__; ?>=plugins&do=status&name=<?php echo $plugName;?>&param=0" target="iCMS_FRAME">关闭</a>
	        <?php }else{?>
	        | <a href="<?php echo __ADMINCP__; ?>=plugins&do=status&name=<?php echo $plugName;?>&param=1" target="iCMS_FRAME">启用</a>
	        <?php }?>
 	         <!--| <a href="<?php echo __ADMINCP__; ?>=plugins&do=update&name=<?php echo $plugName;?>" target="iCMS_FRAME">更新缓存</a>-->
	     	| <a href="<?php echo __ADMINCP__; ?>=plugins&do=setup&name=<?php echo $plugName;?>&param=0" onclick="return confirm('确定要卸载<?php echo $rs['config']['Name'];?>');" target="iCMS_FRAME">卸载</a>
        <?php }else{?>
        	| <a href="<?php echo __ADMINCP__; ?>=plugins&do=setup&name=<?php echo $plugName;?>" target="iCMS_FRAME">安装</a>
       <?php }?>
        </td>
    </tr>
    <?php }
    	}  ?>
  </table>
</form>
</body></html>