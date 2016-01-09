<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>iCMS Administrator's Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo iCMS_CHARSET ; ?>">
<meta content="iDreamSoft Inc." name="Copyright" />
<link rel="stylesheet" href="admin/css/main.css?5.0" type="text/css" media="all" />
<link rel="stylesheet" href="admin/css/smoothness/jquery.ui.css?5.0-1.7.3" type="text/css" media="all" />
<script src="admin/js/jquery.js?5.0-1.3.2" type="text/javascript"></script>
<script src="admin/js/jquery.ui.js?5.0-1.7.3" type="text/javascript"></script>
<script src="admin/js/common.js?5.0" type="text/javascript"></script>
</head>
<body style="margin: 0px" scroll="no">
<div id="iCMS_DIALOG" title="iCMS提示" style="display:none"><img src="admin/images/loading.gif" /></div>
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
  <tr>
    <td colspan="3" height="53"><div class="top">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="150" align="right"><div class="logo"><img src="admin/images/logo.png" width="138" height="53" alt="iCMS" longdesc="iCMS 内容管理系统 http://www.idreamsoft.com"></div></td>
            <td><div class="top_left">
                <div class="user_info">
                  <ul>
                    <li>管理员信息:</li>
                    <li>用户名[<?php echo Admin::$Rs->username;?>]</li>
                    <li>用户组[<?php echo Admin::$group->name;?>]</li>
                    <li>|</li>
                    <LI><a href="javascript:void(0);" onclick="logout();">退出登录</a></li>
                    <li>|</li>
                    <li><a href="http://faq.idreamsoft.com" target="_blank">帮助</a></li>
                    <li>|</li>
                    <li><a href="index.php" target="_blank">网站首页</a></li>
                    <li>|</li>
                    <li><a href="http://www.idreamsoft.com" target="_blank">官方论坛</a></li>
                  </ul>
                  <div style="float:left"><?php echo iCMS_VER ; ?></div>
                </div>
                <div class='main_menu'> <!--主菜单--> <?php menu::main(); ?> </div>
              </div></td>
          </tr>
        </table>
        <div class="top_border"></div>
      </div></td>
  </tr>
  <tr>
    <td valign="top" width="1%"><!--次菜单-->
      <div class="left">
        <div class="main_menu_title">
          <div class="main_menu_title_left"></div>
          <div class="main_menu_title_right" id="off"> </div>
          <div class="none"></div>
        </div>
        <div class="left_menu" id="left_menu"> <?php menu::left(); ?>
          <ul id="forumTree" style="display: none"><div class="backmenu" onclick="hideFT();"><img src="admin/images/home.gif" align="absmiddle" /> 返回菜单</div></ul>
        </div>
      </div></td>
    <td rowspan="2" valign="top" style="padding:0px;" id="iTd"><iframe src="<?php echo __SELF__.'?'.$this->extra;?>" name="main" id="main" width="100%" height="100%" frameborder="0" scrolling="auto" style="overflow: visible;"></iframe></td>
  </tr>
  <tr>
    <td valign="top" style="height:32px;"><div class="left">
        <p>Powered by:<a href="http://www.idreamsoft.com" target="_blank">iCMS</a> <?php echo iCMS_VER ; ?></p>
        <p>&copy;2007-<?php echo date("Y") ; ?> <a href="http://www.idreamsoft.com" target="_blank">iDreamSoft</a>.</p>
      </div></td>
  </tr>
</table>
<link rel="stylesheet" href="admin/css/jquery.treeview.text.css" />
<script src="admin/js/jquery.cookie.js" type="text/javascript"></script>
<script src="admin/js/jquery.treeview.js" type="text/javascript"></script>
<script src="admin/js/jquery.treeview.async.js" type="text/javascript"></script>
<script language="javascript">
$("#header_index").click();
$(function(){
	$("#main").height($("#iTd").height());
	$(".left_menu .menu_title span").click(function(){
	  $(".left_menu .menu_title span").removeClass('menu_title_hover');
	  $(this).removeClass("menu_title"); 
	  $(this).addClass('menu_title_hover');
	});
    $("#forumTree").treeview({
    	url:'<?php echo __ADMINCP__; ?>=ajax&do=forums&param=text',
        animated: "medium",
        persist: "cookie",
        cookieId: "iCMS-treeview-black-text"
    });
});
function hideFT(){
	$(".header_article").show();
	$("#forumTree").hide();
}
function forumTree(){
	$(".left_menu ul").css("display","none");
	$("#forumTree").show();
}
function logout(){
	$.get("<?php echo __ADMINCP__; ?>=user&do=logout"); 
	window.fn=function(){ window.location.href='<?php echo __SELF__; ?>'; };
	iCMS.ok("注销成功, 请稍后......","iCMS - 提示信息",window);
	setTimeout(window.fn,2*1000);
}
</script>
</body>
</html>
