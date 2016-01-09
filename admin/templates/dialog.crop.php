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
<link rel="stylesheet" href="admin/css/main.css?5.0" type="text/css" media="all" />
<link rel="stylesheet" href="admin/css/smoothness/jquery.ui.css?5.0-1.7.3" type="text/css" media="all" />
<script src="admin/js/jquery.js?5.0-1.3.2" type="text/javascript"></script>
<script src="admin/js/jquery.ui.js?5.0-1.7.3" type="text/javascript"></script>
	
<link rel="stylesheet" href="admin/css/jquery.Jcrop.css?0.9.8" type="text/css" media="all" />
<script type="text/javascript" src="admin/js/jquery.Jcrop.js?0.9.8"></script>
<script language="javascript" type="text/javascript">
var iCMS_crop_api;
$(function(){
	iCMS_crop_api = $.Jcrop('#iCMS_Crop');
	ratioOptions();
	$("#iCMS_slider").slider({
		value:<?php echo $rate ; ?>,
		min: <?php echo $sliderMin ; ?>,
		max: 100,
		start: function(event, ui) {
			iCMS_crop_api.destroy();
		},					
		slide: function(event, ui) {
		//	if(ui.value<<?php echo $sliderMin ; ?>) ui.slider( 'destroy' ) ;
			$("#amount").val(ui.value);
			var nw=parseInt((ui.value/100)*<?php echo $width ; ?>);
			$("#iCMS_Crop,#crop_preview").width(nw);
			$('#crop_preview').height($('#iCMS_Crop').height());
//			iCMS_crop_api.resize(nw,$('#iCMS_Crop').height());
			$("#nw").text(nw);
			iCMS_crop_api.focus();
		},
		stop: function(event, ui) {
			iCMS_crop_api = $.Jcrop('#iCMS_Crop');
			var Options =  $("input[name=Options]:checked").val();
			$("input[name=Options][value="+Options+"]").click();
		}
	});
	$("#amount").val(<?php echo $rate ; ?>);
	$("#x,#y,#w,#h").change( function() {
		setOptions($("input[name=Options]:checked").val());
	}); 
	$("input[name=Options]").click(function() {
		setOptions(this.value);
	});
	window.focus();
});
function ratioOptions(){
	iCMS_crop_api.setOptions({
		allowResize: true,
		aspectRatio: <?php echo $tw ; ?>/<?php echo $th ; ?>,
		minSize:[<?php echo $tw ; ?>,<?php echo $th ; ?>],
		onChange: xywh
	});
	$("input[name=Options][value=ratio]").attr("checked","checked");
}
function setOptions(o){
	var s={
		x:parseInt($('#x').val()),y:parseInt($('#y').val()),
		w:parseInt($('#w').val()),h:parseInt($('#h').val())
	};
	if(o=="size"){
		iCMS_crop_api.setOptions({allowResize: false,aspectRatio: 0});
	}else if(o=="ratio"){
		ratioOptions();
	}else if(o=="free"){
		iCMS_crop_api.setOptions({ allowResize: true,aspectRatio: 0});
	}
	iCMS_crop_api.setSelect([s.x,s.y,s.w,s.h]);
	iCMS_crop_api.focus();
}
function xywh(c){
	var Options =  $("input[name=Options]:checked").val();
	if(Options!="ratio") return;
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
	if (parseInt(c.w) > 0){
		var rx = <?php echo $tw ; ?> / c.w;
		var ry = <?php echo $th ; ?> / c.h;
		var nw = $('#iCMS_Crop').width();
		var nh = $('#iCMS_Crop').height();
		$('#crop_preview').css({
			width: Math.round(rx * nw) + 'px',
			height: Math.round(ry * nh) + 'px',
			marginLeft: '-' + Math.round(rx * c.x) + 'px',
			marginTop: '-' + Math.round(ry * c.y) + 'px'
		});
	}
};
function check(){
	$("#pWidth").val($('#iCMS_Crop').width());
	$('#PHeight').val($('#iCMS_Crop').height());
	if (parseInt($('#w').val())) return true;
	alert('请选择剪切范围.');
	return false;
};
</script>
<style type="text/css">
body, td, th { font:12px Verdana, Arial, Helvetica, sans-serif; color: #333; }
body { background-color: #FFF; margin-left: 0px; margin-top: 0px; }
.cropbox {
	margin:5px;
	text-align:center;
	padding:5px;
	overflow:scroll;
}
.cropbox .jcrop-holder {
	margin-left:auto;
	margin-right:auto;
	width:98%;
	display:block;
}
.cropbox .jcrop-holder img {
	border:#CCCCCC solid 1px;
}
fieldset {
	margin:5px auto;
	text-align:center;
}
.sbtn{border:1px solid #999999;line-height:13px; height:20px;}
</style>
</head>
<body>
<div class="container" id="cpcontainer">
  <fieldset>
  <legend>图片剪切</legend>
  <div class="cropbox"><img src="<?php echo uploadpath($pFile,'+http') ; ?>" width="<?php echo $pw ; ?>" id="iCMS_Crop"/> </div>
  </fieldset>
  <fieldset>
  <legend>设置</legend>
  <table style="width:98%;">
    <tr>
      <td style="width:100px;">原始比率:</td>
      <td align="right" style="width:500px;"><span id="nw"><?php echo $pw ; ?></span>/
        <?php echo $width ; ?>        <br />
        <div id="iCMS_slider"></div></td>
      <td style="width:100px;"><input type="text" id="amount" style="width:36px; border:#CCCCCC solid 1px;"/>%</td>
    </tr>
    <tr>
      <td colspan="3"><table>
          <form action="<?php echo __ADMINCP__; ?>=files" method="post" onsubmit="return check();" target="iCMS_FRAME">
          	<input type="hidden" name="do" value="crop"/>
            <input type="hidden" name="callback" value="<?php echo $callback ; ?>"/>
            <input type="hidden" id="pFile" name="pFile" value="<?php echo $pFile ; ?>"/>
            <input type="hidden" id="pWidth" name="width" value=""/>
            <input type="hidden" id="PHeight" name="height" value=""/>
            <tr>
              <td>
                <fieldset>
                <legend>选项</legend>
                <input name="Options" type="radio" value="ratio" class="radio"/>固定比例 
                <input name="Options" type="radio" value="size" class="radio" />固定尺寸 
                <input name="Options" type="radio" value="free" class="radio" />自由裁剪 
                </fieldset>
             
                <fieldset>
                <legend>坐标</legend>
                X
                <input type="text" id="x" name="x" value="0"/>
                Y
                <input type="text" id="y" name="y" value="0"/>
                </fieldset>
                <fieldset>
                <legend>尺寸</legend>
                宽:
                <input type="text" id="w" name="w" value="0"/>
                高:
                <input type="text" id="h" name="h" value="0"/>
                <br />
                </fieldset>
                <input type="submit" value="剪切" class="sbtn"/>
              </td>
              <td ><fieldset>
                <legend>预览</legend>
                <div style="width:<?php echo $tw ; ?>px;height:<?php echo $th ; ?>px;overflow:hidden; margin:5px;"> <img src="<?php echo uploadpath($pFile,'+http') ; ?>" id="crop_preview" width="<?php echo $pw ; ?>" style="border:#CCCCCC solid 1px;"/> </div>
                </fieldset></td>
            </tr>
          </form>
        </table></td>
    </tr>
  </table>
  </fieldset>
</div>
</body>
</html>
