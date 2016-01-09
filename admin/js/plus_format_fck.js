// 设置编辑器中内容
function SetEditorContents(EditorName, ContentStr) { 
    var oEditor = FCKeditorAPI.GetInstance(EditorName) ; 
    oEditor.InsertHtml(ContentStr) ; 
}

function trans(bodyname){
    var str = "";
    rtf.focus();
    rtf.document.body.innerHTML = "";
    rtf.document.execCommand("paste");
    str = rtf.document.body.innerHTML;
    if(str.length == 0) {
        alert("剪切版不存在超文本数据！");
        return "";
    }
appendEditor(html_trans(str));
//    var oEditor = FCKeditorAPI.GetInstance(bodyname) ; 
//    oEditor.InsertHtml(html_trans(str));
}
//格式化图片格式
function FormatImages(bodyname) {
    var oEditor = FCKeditorAPI.GetInstance(bodyname);
	var content = oEditor.GetXHTML(true);
	oEditor.SetHTML(html_trans(content));
}
function my_format(str){
    var cc,tempstr;
    cc = str;
    tempstr = "";
    var ss=cc.split("\n");
    for (var i=0; i< ss.length; i++ ){
        while (ss[i].substr(0,1)==" "||ss[i].substr(0,1)=="　"){
            ss[i]=ss[i].substr(1,ss[i].length);
        }
        if (ss[i].length>0) tempstr+="<p>"+ss[i]+"</p>";
    }
    return tempstr;
}

function delnull(str)
{
    return str.replace(/([\s]*\r\n[\s]*){2,}/gm,"\r\n");
}
function refix(str)
{
    return str.replace(/([\s]*\r\n[\s]*){2,}/gm,"\r\n\r\n");
}
function url(str)
{
    return str.replace(/^[ ]*(http:\/\/|^mms:\/\/|^rtsp:\/\/|^pnm:\/\/|^ftp:\/\/|^mmst:\/\/|^mmsu:\/\/)([^\r\n]*)$/igm,"[url]$1$2[/url]");
}

function addbr(str)
{
    return str.replace(/\r\n/gm,"\r\n\r\n");
}

function html_trans(str) {

str = str.replace(/\/"/g,'"');
str = str.replace(/\\\&quot;/g,"");

str = str.replace(/<div style="page-break-after: always; "><span style="DISPLAY:none">&nbsp;<\/span><\/div>/g, '#--iCMS.PageBreak--#');
str = str.replace(/<div style="page-break-after: always"><span style="display: none">&nbsp;<\/span><\/div>/g, '#--iCMS.PageBreak--#');

str = str.replace(/\r/g,"");
str = str.replace(/<p>,<\/p>/g,"");
str = str.replace(/on(load|click|dbclick|mouseover|mousedown|mouseup)="[^"]+"/ig,"");
str = str.replace(/<script[^>]*?>([\w\W]*?)<\/script>/ig,"");


str = str.replace(/<a[^>]+href=[" ]?([^"]+)[" ]?[^>]*>(.*?)<\/a>/ig,"[url=$1]$2[/url]");
//	str = str.replace(/<a[^>]+href=[" ]?([^"]+)[" ]?[^>]*>([^<]*)<\/a>/ig,""); //
str = str.replace(/<img[^>]+src=[" ]?([^"]+)[" ]?[^>]*>/ig,"[img]$1[/img]");
str = str.replace(/<b[^>]*>(.*?)<\/b>/ig,"[b]$1[/b]");
str = str.replace(/<strong[^>]*>(.*?)<\/strong>/ig,"[b]$1[/b]");

//str = str.replace(/<object[^>]+classid=[" ]?clsid:D27CDB6E-AE6D-11cf-96B8-444553540000[" ]?[^>]*>(.*?)<param[^>]+name=[" ]?movie[" ]?[^>]+value=[" ]?([^"]+)[" ]?[^>]*>/ig,"\n[swf]$2[/swf]\n");
//str = str.replace(/<object[^>]+classid=[" ]?clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA[" ]?[^>]*>.*<param[^>]+name="src"[^>]+value="([^"]+)">/ig,"\n[rm]$1[/rm]\n");


str = str.replace(/&nbsp;/g," ");
str = str.replace(/&amp;/g,"&");

str = str.replace(/&quot;/g,"\"");
str = str.replace(/&lt;/g,"<");
str = str.replace(/&gt;/g,">");
str = str.replace(/--<\/p>/g,"</p>");

str = str.replace(/<[^>]*?>/g,"");
str = str.replace(/\[url=([^\]]+)\]\n(\[img\]\1\[\/img\])\n\[\/url\]/g,"$2");
str = str.replace(/\n+/g,"\n");
str = my_format(str);
str = str.replace(/\[img\](.*?)\[\/img\]/ig,'<img src="$1" />');
str = str.replace(/\[b\](.*?)\[\/b\]/ig,'<b>$1</b>');
str = str.replace(/\[url=([^\]|#]+)\](.*?)\[\/url\]/g,'$2');
str = str.replace(/\[url=([^\]]+)\](.*?)\[\/url\]/g,'<a target="_blank" href="$1">$2</a>');
str = str.replace(/<p>#--iCMS\.PageBreak--#<\/p>/g, '<div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>');

    return str;
}
function copycode(obj){
    obj.select();
    objcopy=obj.createTextRange();
    objcopy.execCommand("Cut"); //Copy
}

function zhen(str)
{
    strfound=str.replace(/\\/ig,"\\\\");
    strfound=strfound.replace(/\[/ig,"\\[");
    strfound=strfound.replace(/\]/ig,"\\]");
    strfound=strfound.replace(/\{/ig,"\\{");
    strfound=strfound.replace(/\}/ig,"\\}");
    strfound=strfound.replace(/\|/ig,"\\|");
    strfound=strfound.replace(/\//ig,"\\/");
    strfound=strfound.replace(/\^/ig,"\\^");
    strfound=strfound.replace(/\./ig,"\\.");
    strfound=strfound.replace(/\*/ig,"\\*");
    strfound=strfound.replace(/\?/ig,"\\?");
    strfound=strfound.replace(/\+/ig,"\\+");
    return strfound;
}
function replace_star()
{
    var str=document.getElementById('text').value;
    if(!reg.checked)
        strfound=zhen(find_text.value);
    else
        strfound=find_text.value;
    var re = new RegExp(strfound,"ig");
    str=str.replace(re,replace_text.value);
    document.getElementById('text').value=str;
}
