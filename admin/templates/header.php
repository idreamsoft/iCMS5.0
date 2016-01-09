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
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo iCMS_CHARSET ;  ?>">
<?php if($isFm){?>
<link rel="stylesheet" href="admin/css/main.css?5.0" type="text/css" media="all" />
<link rel="stylesheet" href="admin/css/smoothness/jquery.ui.css?5.0-1.7.3" type="text/css" media="all" />
<script src="admin/js/jquery.js?5.0-1.3.2" type="text/javascript"></script>
<script src="admin/js/jquery.ui.js?5.0-1.7.3" type="text/javascript"></script>
<script src="admin/js/common.js?5.0" type="text/javascript"></script>
<script type="text/javascript">
	if('<?php echo $this->frames ;  ?>'!= 'no' && !parent.document.getElementById('left_menu')) window.location.replace(document.URL + (document.URL.indexOf('?') != -1 ? '&' : '?') + 'frames=yes');
</script>
<?php } ?>
</head>
<body>
<?php if($isFm){?>
<iframe width="0" height="0" style="display:none" id="iCMS_FRAME" name="iCMS_FRAME"></iframe>
<div id="iCMS_DIALOG" title="iCMS提示" style="display:none"><img src="admin/images/loading.gif" /></div>
<?php } ?>
