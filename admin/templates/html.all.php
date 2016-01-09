<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;HTML更新&nbsp;&raquo;&nbsp全站更新</div>
<form action="<?php echo __ADMINCP__; ?>=html" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="CreateAll" />
  <table class="adminlist">
    <tr>
      <td class="tips2" align="center">全站HTML更新，很消耗服务器资源．请慎用！</td>
    </tr>
    <tr>
      <td align="center"><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>
  </table>
</form>
</body></html>