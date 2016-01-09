<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
$file_types='*.'.str_replace(',',';*.',$this->iCMS->config['fileext']);
?>
<link href="admin/swfupload/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="admin/swfupload/swfupload.js"></script>
<script type="text/javascript" src="admin/swfupload/swfupload.swfobject.js"></script>
<script type="text/javascript" src="admin/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="admin/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="admin/swfupload/handlers.js"></script>
<style type="text/css">
.ubtn { border:1px solid #999999; line-height:16px; margin-left: 2px; height: 22px;font-size:12px;}
.ualert{background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;}
#spanButtonPlaceholder{display:block;}
</style>
<!--div class="fieldset flash" id="fsUploadProgress" style="display:none;"></div-->
<div class="upload" id="upload-table">
  <div class="uploadr-bg">
    <div class="uploadr-scroll" id="uploadr-list"> </div>
  </div>
</div>
<br />
<span id="spanButtonPlaceholder"></span>
  <input type="button" onclick="upload.startUpload();" value="开始上传" class="ubtn"/>
  <input id="btnCancel" type="button" value="取消上传" onClick="cancelQueue(upload);" disabled="disabled" class="ubtn" />
<noscript>
<div class="ualert" style="display:block;"> We're sorry.  SWFUpload could not load.  You must have javascript enabled to enjoy SWFUpload. </div>
</noscript>
<div id="divLoadingContent" class="content ualert"> SWFUpload is loading. Please wait a moment... </div>
<div id="divLongLoading" class="content ualert"> SWFUpload is taking a long time to load or the load has failed.  Please make sure that the Flash Plugin is enabled and that a working version of the Adobe Flash Player is installed. </div>
<div id="divAlternateContent" class="content ualert"> We're sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player.
  Visit the <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a> to get the Flash Player. </div>
<script type="text/javascript">
var upload = new SWFUpload({
	// Backend Settings
	upload_url: "<?php echo __ADMINCP__; ?>=files&do=swfupload<?php if($upload['param']){echo '&param='.$upload['param'];}?>",
	post_params: {"PHPSESSID" : "<?php echo session_id() ; ?>"},

	// File Upload Settings
	file_size_limit : "<?php echo get_cfg_var("upload_max_filesize")?intval(get_cfg_var("upload_max_filesize"))*1024:"0";?>",
	file_types : "<?php echo $file_types ; ?>",
	file_types_description : "所有可上传文件",
	file_upload_limit : "100",
	file_queue_limit : "0",

	// Event Handler Settings (all my handlers are in the Handler.js file)
	swfupload_loaded_handler : swfUploadLoaded,
	file_dialog_start_handler : fileDialogStart,
	file_queued_handler : fileQueued,
	file_queue_error_handler : fileQueueError,
	file_dialog_complete_handler : fileDialogComplete,
	upload_start_handler : uploadStart,
	upload_progress_handler : uploadProgress,
	upload_error_handler : uploadError,
	upload_success_handler : <?php echo $upload['success_fun']?$upload['success_fun']:'uploadSuccess';?>,
	upload_complete_handler : uploadComplete,

	// SWFObject settings
	minimum_flash_version : "9.0.28",
	swfupload_pre_load_handler : swfUploadPreLoad,
	swfupload_load_failed_handler : swfUploadLoadFailed,

	// Button Settings
	button_image_url : "admin/swfupload/XPButtonUploadText_61x22.png",
	button_placeholder_id : "spanButtonPlaceholder",
	button_width: 61,
	button_height: 22,
	
	// Flash Settings
	flash_url : "admin/swfupload/swfupload.swf",
	

	custom_settings : {
		progressTarget : "uploadr-list",
		cancelButtonId : "btnCancel"
	},
	
	// Debug Settings
	debug: false
});
</script>
