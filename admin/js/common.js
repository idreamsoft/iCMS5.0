var lang = new Array();
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var iCMS=iCMS||{};
iCMS.eId="iEditor_1";
iCMS.D =function(html,title){
    title=title||'iCMS - 提示信息';
    var d = $('#iCMS_DIALOG');
    d.attr("title",title).html(html).dialog('open');
    return d;
}
iCMS.msg = function(text,title,callback){
    this.D(text,title).dialog({
	    height: '400',
	    width: '500',
        modal: false
    });
}
iCMS.ok = function(text,title,callback){
    this.D(text,title).dialog({
        modal: true,
        buttons: {
            "确定": function() {
                if(callback.fn && typeof callback.fn =='function'){
                	callback.fn();
                }else{
                    $(this).dialog('close');
                }
            }
        }
    });
}
iCMS.yORn = function(text,title,callback){
    this.D(text,title).dialog({
        modal: true,
        buttons: {
            '确定': function() {
                if(callback.yf && typeof callback.yf =='function'){
                    callback.yf();
                }else{
                    $(this).dialog('close');
                }
            },
            '取消': function() {
                if(callback.nf && typeof callback.nf =='function'){
                    callback.nf();
                }else{
                    $(this).dialog('close');
                }
            }
        }
    });
}
iCMS.CDB = function(text,title,callback){
   this.D(text,title).dialog({
	   height: callback.height ? callback.height:'300',
	   width: callback.width ? callback.width:'350',
       modal:true,
       buttons:callback.buttons
    });
}
iCMS.closeDialog=function(){
    $('#iCMS_DIALOG').dialog('close');
}
iCMS.checkAll=function(type, form, value, checkall, changestyle) {
	var checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(type == 'option' && e.type == 'radio' && e.value == value && e.disabled != true) {
			e.checked = true;
		} else if(type == 'value' && e.type == 'checkbox' && e.value == value) {
			e.checked = form.elements[checkall].checked;
		} else if(type == 'prefix' && e.name && e.name != checkall && (!value || (value && e.name.match(value)))) {
			e.checked = form.elements[checkall].checked;
			if(changestyle && e.parentNode && e.parentNode.tagName.toLowerCase() == 'li') {
				e.parentNode.className = e.checked ? 'checked' : '';
			}
		}
	}
}
iCMS.setEditorSize=function (o,h,e){
	e=e||iCMS.eId;
	var id="#"+e+"___Frame";
	var oh=parseInt($(id).height());
	if(o=="+"){
		$(id).height(oh+parseInt(h));
	}else{
		if(oh>400){
			$(id).height(oh-parseInt(h));
		}
	}
}
iCMS.insert=function (val,id){
	if(id=='iCMSEDITOR'){
		if(in_array(val.substr(val.lastIndexOf(".")+1), ['gif', 'jpeg', 'jpg', 'png', 'bmp'])){
			var content='<p><img src=\"'+val+'\" /></p>';
		}else{
			var name=val.substr(val.lastIndexOf("/")+1);
			var content='<p class="attachment"><a href="'+ val +'" target="_blank"><img src="images/attachment.gif" border="0" align="center"></a>&nbsp;<a href="'+ val +'" target="_blank"><u>'+ name +'</u></a></p>';
		}
		appendEditor(content);
	}else{
		$("#"+id).val(val);
	}
	$('#iCMS_DIALOG').dialog('close');
}
iCMS.showDialog=function(url,callback,title,width,height){
	$('.close').click();
	width=width||770;
	height=height||510;
	$.get(url,{'callback':callback},function(html){
	    title=title||'iCMS - 提示信息';
	    var d = $('#iCMS_DIALOG');
	    d.attr("title",title).html(html).dialog({
	        modal: true,
	        minHeight: height,
	        minWidth: width,
			width: width,
			height: height,
	        buttons: {
	            '确定': function() {
	            	if(document.getElementById('iDF')){
	                    $('#iDF').submit();
	                }else{
	                	d.dialog('close');
	                }
	            },
	            '取消': function() {
	                    d.dialog('close');
	            }
	        }
	    }).dialog('open');
	});
}
function reloadDialog(url){
	$.get(url,function(html){
		$('#iCMS_DIALOG').html(html);
	});
}
function doane(event) {
	e = event ? event : window.event;
	if(is_ie) {
		e.returnValue = false;
		e.cancelBubble = true;
	} else if(e) {
		e.stopPropagation();
		e.preventDefault();
	}
}
Array.prototype.indexOf = function (vItem) {
  for (var i=0; i<this.length; i++) {
    if (vItem == this[i]) {
	  return i;
	}
  }
  return -1;
}
function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
					return true;
			}
		}
	}
	return false;
}
function menuGroup(obj,url){
    var id=$(obj).attr("id");
    $(".main_menu dl dd").removeClass('active').addClass('other');
    $("#"+id).addClass('active');
    $(".left_menu ul").css("display","none");
    $("."+id).css("display","block");
    $("."+id+" ul").css("display","block");
    $(".main_menu_title_left").html($(obj).text());
    $(".left").css("width","180px");
    $(".main_menu_title_left").css("display","block");
    $("iframe").attr("src",url);
}
function isUndefined(variable) {
    return typeof variable == 'undefined' ? true : false;
}
function appendEditor(c){
	var oEditor = FCKeditorAPI.GetInstance(iCMS.eId);
	var content = oEditor.GetXHTML(true);
	if(oEditor.GetXHTML( true )==''){
		oEditor.SetHTML(c) ;
	}else{
		oEditor.SetHTML(content+c);
	}
}
/*--------------------------------------------------------------------*/

function dropDown(){
    var timeOutID 	= null;
    var hidedropDown 	= function(){
        $("#dropDown").hide();
    };
    $(".dropDown").mouseover(function(){
        window.clearTimeout(timeOutID);
        $("#dropDown").remove();
        var id		= $(this).attr("id");
        var offset	= $(this).offset();
        var dropDown= $('<div id="dropDown"></div>')
        $("body").append(dropDown);
        dropDown.append($("."+id).clone().show())
        .css({
            position:"absolute",
            left:offset.left,
            top:offset.top+28
        })
        .mouseover(function(){
            window.clearTimeout(timeOutID);
            $("#dropDown").show();
        }).mouseout(function(){
            $("#dropDown").hide();
        });
    }).mouseout(function(){
        timeOutID = window.setTimeout(hidedropDown,1000);
    });
}
$(document).ready(function(){
    //	dropDown();
    $(".text").click(function(){
        $(".text_hover").removeClass('text_hover');
        $(this).addClass('text');
        $(this).addClass('text_hover');
    });
    $(".submit").mousedown(function(){
        $(this).removeClass('submit');
        $(this).addClass('submit_click');
    }).mouseup(function(){
        $(this).addClass('submit');
        $(this).removeClass('submit_click');
    });
    $(".button").mousedown(function(){
        $(this).removeClass('button');
        $(this).addClass('button_click');
    }).mouseup(function(){
        $(this).addClass('button');
        $(this).removeClass('button_click');
    });

    $(".main_menu_title_right").click(function (){
        if($(".left").css("width")=="180px"){
            $(".left").css("width","10px");
            $(".main_menu_title_left").css("display","none");
        }
        else{
            $(".left").css("width","180px");
            $(".main_menu_title_left").css("display","block");
        }
    });
    $("#off").mouseover(function(){
        $("#off").removeClass("main_menu_title_right");
        $("#off").addClass("main_menu_title_right_hover");
    }).mouseout(function(){
        $("#off").addClass("main_menu_title_right");
        $("#off").removeClass("main_menu_title_right_hover");
    });

    $(".tab").css("display","none");
    $("#tab1").css("display","inline");
	$(".viewRule").click(function(){
		var offset 		= $(this).offset();
		var snapTop 	= offset.top+14;
		var snapLeft 	= offset.left;
//		alert(snapTop+"-"+snapLeft);
		var inid		= $(this).attr("to");
		$("#viewRule_"+inid).show().css({"top" : snapTop, "left" : snapLeft,"width":320})
		.slideDown("slow");
	});
	$(".close").click(function(){
		var parentdiv=$(this).attr("parent");
	    $("#"+parentdiv).hide();
	});
});
function main_frame_src(url){
    $("iframe").attr("src",url);
}

var addrowdirect = 1;
function addrow(obj, type) {
    var table = obj.parentNode.parentNode.parentNode.parentNode;
    if(!addrowdirect) {
        var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex);
    } else {
        var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex + 1);
    }
    var typedata = rowtypedata[type];
    for(var i = 0; i <= typedata.length - 1; i++) {
        var cell = row.insertCell(i);
        cell.colSpan = typedata[i][0];
        var tmp = typedata[i][1];
        if(typedata[i][2]) {
            cell.className = typedata[i][2];
        }
        tmp = tmp.replace(/\{(\d+)\}/g, function($1, $2) {
            return addrow.arguments[parseInt($2) + 1];
        });
        cell.innerHTML = tmp;
    }
    addrowdirect = 0;
}
function slider_menu(id,thisobj){
    if($("#"+id).css('display')=='none'){
        var type="block";
        $("#"+thisobj).addClass('menu_title');
        $("#"+thisobj).removeClass('menu_title2');
    }else{
        var type="none";
        $("#"+thisobj).removeClass('menu_title');
        $("#"+thisobj).addClass('menu_title2');
    }
    $("#"+id).css('display',type);
}

function moveUp(obj){
    with (obj){
        if(selectedIndex==0){
            options[length]=new Option(options[0].text,options[0].value)
            options[0]=null
            selectedIndex=length-1
        }
        else if(selectedIndex>0) moveG(obj,-1)
        }
}
function moveDown(obj){
    with (obj){
        try {
            if(selectedIndex==length-1){
                var otext=options[selectedIndex].text
                var ovalue=options[selectedIndex].value
                for(i=selectedIndex; i>0; i--){
                    options[i].text=options[i-1].text
                    options[i].value=options[i-1].value
                }
                options[i].text=otext
                options[i].value=ovalue
                selectedIndex=0
            }else if(selectedIndex<length-1) moveG(obj,+1)
        } catch(e) {
		
        }
        }
}
function del(obj) {
    with(obj) {
        try {
            options[selectedIndex]=null
            selectedIndex=length-1
        } catch(e) {}
     }
}
function moveG(obj,offset){
    with (obj){
        desIndex=selectedIndex+offset
        var otext=options[desIndex].text
        var ovalue=options[desIndex].value
        options[desIndex].text=options[selectedIndex].text
        options[desIndex].value=options[selectedIndex].value
        options[selectedIndex].text=otext
        options[selectedIndex].value=ovalue
        selectedIndex=desIndex
        }
}
function setEditorSize(o,e,h){
    e=e||iCMS.eId;
    var id="#"+e+"___Frame";
    var oh=parseInt($(id).height());
    if(o=="+"){
        $(id).height(oh+parseInt(h));
    }else{
        if(oh>400){
            $(id).height(oh-parseInt(h));
        }
    }
}
function textareasize(obj) {
    if(obj.scrollHeight > 70) {
        obj.style.height = obj.scrollHeight + 'px';
    }
}
function altStyle(obj) {
    function altStyleClear(obj) {
        var input, lis, i;
        lis = obj.parentNode.getElementsByTagName('li');
        for(i=0; i < lis.length; i++){
            lis[i].className = '';
        }
    }

    var input, lis, i, cc, o;
    cc = 0;
    lis = obj.getElementsByTagName('li');
    for(i=0; i < lis.length; i++){
        lis[i].onclick = function(e) {
            o = is_ie ? event.srcElement.tagName : e.target.tagName;
            if(cc) {
                return;
            }
            cc = 1;
            input = this.getElementsByTagName('input')[0];
            if(input.getAttribute('type') == 'checkbox' || input.getAttribute('type') == 'radio') {
                if(input.getAttribute('type') == 'radio') {
                    altStyleClear(this);
                }
                if(is_ie || o != 'INPUT' && input.onclick) {
                    input.click();
                }
                if(this.className != 'checked') {
                    this.className = 'checked';
                    input.checked = true;
                } else {
                    this.className = '';
                    input.checked = false;
                }
            }
        }
        lis[i].onmouseup = function(e) {
            cc = 0;
        }
    }
}