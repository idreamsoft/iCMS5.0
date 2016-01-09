<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;HTML更新&nbsp;&raquo;&nbsp更新首页</div>
<?php if(isset($_GET['all'])){  ?>
<script type="text/javascript">$(function(){$("#cpform").submit();})</script>
<?php }  ?>
<form action="<?php echo __ADMINCP__; ?>=html<?php if(isset($_GET['all'])){?>&all=true<?php }?>" method="post" target="iCMS_FRAME" id="cpform">
  <input type="hidden" name="do" value="CreateIndex" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="3">更新首页</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">选择主页模板:</td>
      <td class="rowform"><input name="indexTPL" type="text" id="indexTPL" value="<?php echo $this->iCMS->config['indexTPL'] ; ?>" class="txt"/></td>
      <td class="tips2"><input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=template&click=file&type=htm','indexTPL','选择首页模板');" hidefocus=true /></td>
    </tr>
    <tr>
      <td class="td80">首页文件名:</td>
      <td class="rowform"><input name="indexname" type="text" id="indexname" value="<?php echo $this->iCMS->config['indexname'] ; ?>" class="txt"/>
        <?php echo $this->iCMS->config['htmlext'] ; ?></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>
  </table>
</form>
</body></html>