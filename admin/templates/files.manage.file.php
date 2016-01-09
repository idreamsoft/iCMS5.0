<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head($this->module!='dialog');
?>
<style type="text/css">
body, td, th { font:12px Verdana, Arial, Helvetica, sans-serif; color: #333; }
body { background-color: #FFF; margin-left: 0px; margin-top: 0px; }
#explorer {width:740px; border-collapse:collapse; word-break:break-all; border:solid 1px #ECE9D8; border-bottom:solid 1px #959385; background-color:#ECE9D8; }
#explorer #m { background-color:#ECE9D8; width:5px; }
#explorer #tdfile { background-color: #FFF; width:600px; border-left:solid 1px #7F9DB9; border-top:solid 1px #7F9DB9; }
#explorer #tddir { background-color: #FFF; width:220px; height:350px; border-right:solid 1px #7F9DB9; border-top:solid 1px #ACA899; }
.dirlist { width:220px; height:350px; overflow-x:auto; overflow-y:scroll; clear: both; list-style:none; margin: 0px; display: block; padding: 0px 0px 0px 4px; }
.dirlist li { clear: both; height: 16px; margin-top: 1px; text-align: left; }
.dirlist li span { margin-left:36px; display: block; cursor:pointer;}
.dirlist ul { clear: both; list-style:none; margin: 0px; display: block; padding: 0px 0px 0px 16px; }
/*缩略图*/
#filelist { width:100%; height:375px; overflow-y:scroll; clear: both; list-style:none; margin: 0px 0px; padding: 0px; display: block; margin-top: 4px; }
#filelist li { float:left; width:96px; height:96px; border:solid 1px #ECE9D8; margin-left:8px; }
/*缩略图 end*/
#fList {clear:both;width:100%;margin: 0px; padding: 0px;height:350px;overflow-y: scroll; overflow-x: visible; }
#tdfile .name{width:175px;}
#tdfile .size{ width: 72px;text-align: right; padding-right: 6px; }
#tdfile .date {width: 120px; }
#tdfile .manage {width:102px; <?php if($this->module=='dialog') {echo 'display:none;';}?>}
.filelist {height: 18px;line-height:16px;clear: both; overflow:hidden;list-style:none; margin: 0px 0px 1px 0px; padding: 0px; display: block; }
.filelist li { float:left;padding-left:1px;text-align: left;}
.filelist .n{background-color: #F7F7F7;width:180px !important;cursor:pointer;}
.filelist .n img{vertical-align:middle;}
.filelist .n .icon,#tdfile .name .checkbox,#tdfile .name span{float:left;}
.filelist .n span{width:136px;height:16px;overflow:hidden;margin-left:2px;display:block;}
.filelist .s {width:84px !important;}
.fileheader { background: url(admin/images/header-bg.gif) repeat-x; height: 22px; overflow: visible; }
.fileheader li { background: url(admin/images/header-li-left.gif) no-repeat right top;padding-left: 8px;}
.opendir { background: url(admin/images/file/opendir.gif) no-repeat left top; height: 14px; }
span.on { border:dotted 1px #000; background-color:#316AC5; color:#FFF; }
.closedir { background: url(admin/images/file/closedir.gif) no-repeat left top; height: 14px; }
.closedir span { }
.sbtn{border:1px solid #999999;line-height:13px; height:20px;}
</style>
<script type="text/javascript" src="admin/js/jquery.function.js"></script>
<script type="text/javascript">
var iFM = iFM||window;
iFM.dir = function(j,t){
	this.a=$(j);
	if(t=='p') a=$(j).parent();
	var dir=a.attr("folder"),c=a.attr("class");
	$(".dirlist span").removeClass("on");
	a.find("span").addClass("on");
	$('input[name=dir]').each(function(i){ this.value = dir+'/'; }); 
	$('input[name=REQUEST_URI]').each(function(i){
		var uri=this.value.substr(0,this.value.indexOf("&dir="));
		if(uri==''){
			this.value+='&dir='+dir;
		}else{
			this.value=uri+'&dir='+dir;
		}
	}); 
	var sid=dir.replace(/\//g,'-');
	if(t=='p'||(t!='p'&& c=='closedir')){
		a.attr("class","opendir");
		this.get(sid,dir);
	}
	if(t!='p'&& c=='opendir'){
		a.attr("class","closedir");
		$("#"+sid).remove();
	}
}
iFM.get=function(sid,dir){
	$("#fList").html('<img src="admin/images/loading.gif" /> 加载中...');
	$.getJSON("<?php echo __ADMINCP__; ?>=files&do=filejson&opt=<?php echo $do;?>&click=<?php echo $click;?>&from=<?php echo $from;?>&callback=<?php echo $callback;?>&jsoncallback=?&dir="+dir,
		function(json){
			$("#"+sid).remove();
			if(json.dir){
				var li='';
				for(var i=0;i<json.dir.length;i++){
					li+='<li class="closedir" folder="'+json.dir[i]['path']+'" onclick="iFM.dir(this);"><span onclick=iFM.dir(this,"p");>'+json.dir[i]['dir']+'</span></li>';
				}
				if(li)a.after('<ul id="'+sid+'">'+li+'</ul>');
			}
			var ul='';
			if(json.file){
				for(var i=0;i<json.file.length;i++){
					var item=json.file[i];
					ul+='<ul class="filelist"><li class="name n" title="'+item['name']+'"><input type="checkbox" class="checkbox" name="files[]" value="'+item['href']+'" /><a href="'+item['href']+'" class="icon" target="_blank" title="点击查看">'+item['icon']+'</a> ';
					if('<?php echo $click;?>'=='file'){
						ul+='<span onclick=iCMS.insert("'+item['path']+'","<?php echo $callback;?>"); title="'+item['name']+'">'+item['name']+'</span></li>';
					}else{
						ul+=item['name']+'</li>';
					}
		            ul+='<li class="size s">'+item['size']+'</li>';
		            ul+='<li class="date">'+item['time']+'</li>';
		            ul+='<li class="manage">重新上传 | 删除</li></ul>';
				}
			}
			$("#fList").html(ul);
		<?php if($do!='template'){  ?>
			$(".icon").snap("href",180,200,10,10);
		<?php }  ?>
		}
	);
}
iFM.checkAll = function(){
	$("input[name^='files']") .each(function(i){
		if($(this).attr('checked')){
			$(this).attr('checked',false);
		}else{
			$(this).attr('checked',true);
		}
	}); 
}
$(function(){
<?php if($do!='template'){  ?>
$(".icon").snap("href",180,200,10,10);
<?php }  ?>
	$('#iDF').submit(function(){
		var content='';
		$("input[name^='files'][checked=true]") .each(function(i){
			var src=this.value;
			if(in_array(src.substr(src.lastIndexOf(".")+1), ['gif', 'jpeg', 'jpg', 'png', 'bmp'])){
				content+='<p><img src=\"'+src+'\" /></p>';
			}else{
				var name=src.substr(src.lastIndexOf("/")+1);
				content+='<p class="attachment"><a href="'+ src +'" target="_blank"><img src="images/attachment.gif" border="0" align="center"></a>&nbsp;<a href="'+ src +'" target="_blank"><u>'+ name +'</u></a></p>';
			}
		});
		appendEditor(content);
		iCMS.closeDialog();
		return false;
	});
});
</script>
<?php if($this->module!='dialog'){  ?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;文件管理&nbsp;&raquo;&nbsp;<select name="filemethod" onchange="parent.main.location.href='<?php echo __ADMINCP__; ?>=files&do=manage&method='+this.value"><option value="database"<?php if($method=="database") echo ' selected="selected"'  ?>>数据库模式</option><option value="file"<?php if($method=="file") echo ' selected="selected"'  ?>>文件浏览器</option></select></div>
<?php }  ?>
<table border="0" align="left" cellpadding="0" cellspacing="0" id="explorer">
  <tr>
    <td height="22" colspan="2" align="left" style="padding-left:4px;" onclick="void(0);">文件夹 <?php echo $Folder;?></td>
    <td rowspan="2" valign="top" id="tdfile"><ul class="filelist fileheader">
        <li class="name">名称</li>
        <li class="size">大小</li>
        <li class="date">修改日期</li>
        <li class="manage">管理</li>
      </ul><form action="<?php echo __ADMINCP__; ?>=files" method="post" target="iCMS_FRAME" id="iDF">
    	<div id="fList">
        <?php 
			$_FL=$L['FileList'];
			$_fCount=count($_FL);
			for($i=0;$i<$_fCount;$i++){
			$href=$do=='template'?$iCMS->config['url'].'/templates/'.$_FL[$i]['path']:uploadpath($_FL[$i]['path']);
			$filepath=$from=='editor'?$href:$_FL[$i]['path'];
		  ?>
      	<ul class="filelist">
        <li class="name n" title="<?php echo $_FL[$i]['name'];?>"><input type="checkbox" class="checkbox" name="files[]" value="<?php echo $href;?>" /><a href="<?php echo $href;?>" class="icon" target="_blank" title="点击查看"><?php echo $_FL[$i]['icon'];?></a> <?php if($click=='file'){  ?><span onclick="iCMS.insert('<?php echo $filepath;?>','<?php echo $callback;?>');"><?php echo $_FL[$i]['name'];?></span><?php }else{  ?><?php echo $_FL[$i]['name'];?><?php }  ?></li>
        <li class="size s"><?php echo $_FL[$i]['size'];?></li>
        <li class="date"><?php echo $_FL[$i]['time'];?></li>
        <li class="manage">重新上传 | 删除</li>
        </ul>
       <?php }   ?></div></form>
      </td>
  </tr>
  <tr>
    <td id="tddir">
    	<ul class="dirlist">
        <?php
      		$_dir=$L['folder'];
			$_count=count($_dir);
    		for($i=0;$i<$_count;$i++){
    			//$_dir[$i]=CN::g2u($_dir[$i]);
    		?>
        <li class="closedir" folder="<?php echo $_dir[$i]['path'];?>" onclick="iFM.dir(this);"><span><?php echo $_dir[$i]['dir'];?></span></li>
        <?php }   ?>
      </ul>
     </td>
    <td id ='m'></td>
  </tr>
  <tr>
    <td colspan="4" style="padding:1px;"><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="280"><form action="<?php echo __ADMINCP__; ?>=files" method="post" enctype="multipart/form-data" target="iCMS_FRAME">
              <input name="file" type="file" class="sbtn"/>
              <input name="dir" type="hidden" value="<?php echo $dir;?>" />
              <input name="do" type="hidden" value="Upload_File_Action" />
              <input name="REQUEST_URI" type="hidden" value="<?php echo $_SERVER["REQUEST_URI"]?>" />
              <input type="submit" value="上传" class="sbtn"/>
            </form></td>
          <td align="left"><form action="<?php echo __ADMINCP__; ?>=files" method="post" target="iCMS_FRAME">
              <input type='text' name='dirname' value='创建新目录' style='width:96px;border:1px solid #999999;' onclick="if(this.value=='创建新目录')this.value=''" >
              <input name="dir" type="hidden" value="<?php echo $dir;?>" />
              <input name="do" type="hidden" value="CreateDir" />
              <input name="REQUEST_URI" type="hidden" value="<?php echo $_SERVER["REQUEST_URI"]?>" />
              <input type="submit" value="创建" class="sbtn"/>
            </form></td>
          <td align="left"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iFM.checkAll()" />全选</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>