<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<link rel="stylesheet" href="admin/css/jquery.treeview.css" />
<script src="admin/js/jquery.cookie.js" type="text/javascript"></script>
<script src="admin/js/jquery.treeview.js" type="text/javascript"></script>
<script src="admin/js/jquery.treeview.async.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        $("#tree .operation").mouseover(function(){
            $(this).parent().css("background-color","#F2F9FD");
        }).mouseout(function(){
            $(this).parent().css("background-color","#FFFFFF");
        });
        $("#tree").treeview({
        	url:'<?php echo __ADMINCP__; ?>=ajax&do=forums&<?php echo time(); ?>',
            collapsed: <?php if($do=="fold") {  ?>true<?php }else {  ?>false<?php }  ?>,
            animated: "medium",
            control:"#submenu",
//            persist: "location",
            persist: "cookie",
            cookieId: "iCMS-treeview-black"
        });
    });
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;版块管理</div>
<div class="itemtitle">
  <ul class="tab1" id="submenu">
    <li class="current"><a href="#" hidefocus=true><span>收缩所有版块</span></a></li>
    <li class="current"><a href="#" hidefocus=true><span>展开所有版块</span></a></li>
  </ul>
</div>
<table class="adminlist" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td>点击FID后面数字，可查看该版块</td>
  </tr>
  <tr>
    <td>点击<img src="admin/images/+.gif"/> 可展开下级</td>
  </tr>
</table>
<form name="cpform" method="post" action="<?php echo __ADMINCP__; ?>=forums" id="cpform" target="iCMS_FRAME" >
  <input type="hidden" name="do" value="edit" />
  <table class="adminlist" border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th><div class="ordernum">顺序</div>
          <div class="name">版块名称</div>
          <div class="operation">管理</div></th>
      </tr>
    </thead>
    <tbody id="clist">
      <tr>
        <td><ul id="tree">
            <?php
                        //if($do=="fold"){
                        //echo $forum->row("0",0);
                        //}elseif($do=="expand"){
                        //echo $forum->all();
                        //}
                        ?>
          </ul></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td><input type="submit" class="submit" name="editsubmit" value="提交"  /></td>
      </tr>
    </tfoot>
  </table>
</form>
</body></html>