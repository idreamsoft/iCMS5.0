<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<script type="text/javascript" src="admin/js/jquery.ui.datepicker.js?5.0-1.7.3"></script>
<script type="text/javascript">
$(function(){$(".datepicker").datepicker();})
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;广告管理&nbsp;&raquo;&nbsp;<?php echo empty($id)?'添加':'修改' ; ?>广告</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>展现方式: 页头通栏广告显示于页面右上方，通常使用 468x60 图片或 Flash 的形式。当前页面有多个页头通栏广告时，系统会随机选取其中之一显示。</li>
        <li>价值分析: 由于能够在页面打开的第一时间将广告内容展现于最醒目的位置，因此成为了网页中价位最高、最适合进行商业宣传或品牌推广的广告类型之一。</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=advertise" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <input type="hidden" name="id" value="<?php echo $id ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="3" class="partition"><?php echo empty($id)?'添加':'修改' ; ?>广告</th>
      </tr>
    </thead>
    <tr>
      <td class="td120">广告标识符(必填):</td>
      <td class="rowform"><input name="varname" value="<?php echo $rs['varname'] ; ?>" type="text" class="txt"  /></td>
      <td class="tips2">模板标签: &lt;!--{iCMS:advertise name="广告标识符"}--&gt;</td>
    </tr>
    <tr>
      <td class="td120">广告描述:</td>
      <td class="rowform"><input name="title" value="<?php echo $rs['title'] ; ?>" type="text" class="txt"  /></td>
      <td class="tips2">广告描述</td>
    </tr>
    <tr>
      <td class="td120">广告状态:</td>
      <td class="rowform"><select name="state" >
          <option value="1"<?php if($rs['state']=="1")echo " selected"  ?>> 启用 </option>
          <option value="0"<?php if($rs['state']=="0")echo " selected"  ?>> 关闭</option>
        </select></td>
      <td class="tips2">关闭状态广告将不显示</td>
    </tr>
    <tr>
      <td class="td120">代码加载方式:</td>
      <td class="rowform"><select name="load" >
          <option value=""<?php if($rs['load']=="")echo " selected"  ?>> 直接显示 </option>
          <option value="iframe"<?php if($rs['load']=="iframe")echo " selected"  ?>> iframe </option>
          <option value="js"<?php if($rs['load']=="js")echo " selected"  ?>> js</option>
          <option value="shtml"<?php if($rs['load']=="shtml")echo " selected"  ?>> shtml</option>
        </select></td>
      <td class="tips2">iframe:框架<br />
        js:javascript脚本<br />
        shtml:SSI include</td>
    </tr>
    <tr>
      <td class="td120">广告起始时间(选填):</td>
      <td class="rowform"><input type="text" class="txt datepicker" name="starttime" value="<?php echo $rs['starttime'] ; ?>"></td>
      <td class="tips2">设置广告起始生效的时间，格式 yyyy-mm-dd，留空为不限制起始时间</td>
    </tr>
    <tr>
      <td class="td120">广告结束时间(选填):</td>
      <td class="rowform"><input type="text" class="txt datepicker" name="endtime" value="<?php echo $rs['endtime'] ; ?>"></td>
      <td class="tips2">设置广告展示结束的时间，格式 yyyy-mm-dd，留空为不限制结束时间</td>
    </tr>
    <tr>
      <td class="td120">展现方式:</td>
      <td class="rowform"><select name="style" id="style" onchange="chStyle(this.value);">
          <option value="code"<?php if($rs['style']=="code")echo " selected"  ?>> 代码</option>
          <option value="text"<?php if($rs['style']=="text")echo " selected"  ?>> 文字</option>
          <option value="image"<?php if($rs['style']=="image")echo " selected"  ?>> 图片</option>
          <option value="flash"<?php if($rs['style']=="flash")echo " selected"  ?>> Flash</option>
        </select></td>
      <td class="tips2">请选择所需的广告展现方式</td>
    </tr>
    <tbody id="style_code" style="display: none">
      <tr>
        <td class="td120">广告 HTML 代码:</td>
        <td class="rowform" colspan="2" style=" width:auto;">请直接输入需要展现的广告的 HTML 代码<br />
          <textarea  rows="6" onkeyup="textareasize(this)" name="adv[code][html]" id="adv[code][html]" cols="50" class="tarea" style="width:520px;"><?php echo $adv['code']['html'] ; ?></textarea></td>
      </tr>
    </tbody>
    <tbody id="style_text" style="display: none">
      <tr>
        <td class="td120">文字内容(必填):</td>
        <td class="rowform"><input name="adv[text][title]" value="<?php echo $adv['text']['title'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入文字广告的显示内容</td>
      </tr>
      <tr>
        <td class="td120">文字链接(必填):</td>
        <td class="rowform"><input name="adv[text][link]" value="<?php echo $adv['text']['link'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入文字广告指向的 URL 链接地址</td>
      </tr>
      <tr>
        <td class="td120">文字大小(选填):</td>
        <td class="rowform"><input name="adv[text][size]" value="<?php echo $adv['text']['size'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入文字广告的内容显示字体，可使用 pt、px、em 为单位</td>
      </tr>
    </tbody>
    <tbody id="style_image" style="display: none">
      <tr>
        <td class="td120">图片地址(必填):</td>
        <td class="rowform"><input name="adv[image][url]" value="<?php echo $adv['image']['url'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入图片广告的图片调用地址</td>
      </tr>
      <tr>
        <td class="td120">图片链接(必填):</td>
        <td class="rowform"><input name="adv[image][link]" value="<?php echo $adv['image']['link'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入图片广告指向的 URL 链接地址</td>
      </tr>
      <tr>
        <td class="td120">图片宽度(选填):</td>
        <td class="rowform"><input name="adv[image][width]" value="<?php echo $adv['image']['width'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入图片广告的宽度，单位为像素</td>
      </tr>
      <tr>
        <td class="td120">图片高度(选填):</td>
        <td class="rowform"><input name="adv[image][height]" value="<?php echo $adv['image']['height'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入图片广告的高度，单位为像素</td>
      </tr>
      <tr>
        <td class="td120">图片替换文字(选填):</td>
        <td class="rowform"><input name="adv[image][alt]" value="<?php echo $adv['image']['alt'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入图片广告的鼠标悬停文字信息</td>
      </tr>
    </tbody>
    <tbody id="style_flash" style="display: none">
      <tr>
        <td class="td120">Flash 地址(必填):</td>
        <td class="rowform"><input name="adv[flash][url]" value="<?php echo $adv['flash']['url'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入 Flash 广告的调用地址</td>
      </tr>
      <tr>
        <td class="td120">Flash 宽度(必填):</td>
        <td class="rowform"><input name="adv[flash][width]" value="<?php echo $adv['flash']['width'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入 Flash 广告的宽度，单位为像素</td>
      </tr>
      <tr>
        <td class="td120">Flash 高度(必填):</td>
        <td class="rowform"><input name="adv[flash][height]" value="<?php echo $adv['flash']['height'] ; ?>" type="text" class="txt"  /></td>
        <td class="tips2">请输入 Flash 广告的高度，单位为像素</td>
      </tr>
    </tbody>
    <tr>
      <td colspan="3"><input type="submit" class="submit" name="advsubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</div>
<script type="text/javascript">
chStyle('<?php echo $rs['style'];?>');
function chStyle(e){
	$("tbody[id^='style']").hide();
	$("#style_"+e).show();
}
</script>
</body></html>