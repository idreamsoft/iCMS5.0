<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;版块管理&nbsp;&raquo;&nbsp;<?php echo empty($rs['id'])?'新建':'编辑'; ?>版块</div>
<table id="tips" class="adminlist" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td>以下设置没有继承性，即仅对当前版块有效，不会对下级子版块产生影响。</td>
  </tr>
</table>
<form name="cpform" method="post" action="<?php echo __ADMINCP__; ?>=forums" id="cpform" target="iCMS_FRAME">
  <input name="do" type="hidden" id="do" value="save" />
  <input name="fid" type="hidden" value="<?php echo $rs['fid']  ; ?>" />
  <table class="adminlist" border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th colspan="3">基本设置</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">上级版块:</td>
      <td class="vtop rowform"><?php if(Admin::CP($rootid) || empty($rootid)) {   ?>
        <select name="rootid">
          <option value="0">======顶级版块=====</option>
          <?php echo $forum->select($rootid,0,1,NULL,NULL,true);?>
        </select>
        <?php }else {  ?>
        <input name="rootid" id="rootid" type="hidden" value="<?php echo $rootid ; ?>" />
        <input readonly="true" value="<?php echo $forum->forum[$rootid]['name'] ; ?>" type="text" class="txt" />
        <?php }  ?></td>
      <td>本版块的上级版块或分类</td>
    </tr>
    <tr>
      <td class="td80">版块名称:</td>
      <td class="vtop rowform"><input name="name" id="name" value="<?php echo $rs['name']  ; ?>" type="text" class="txt"  /></td>
      <td>版块名称</td>
    </tr>
    <tr>
      <td class="td80">版块属性:</td>
      <td class="vtop rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($rs['attr']=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="attr" value="1" <?php if($rs['attr']=='1') echo 'checked'  ?>>
            实版块</li>
          <li<?php if($rs['attr']=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="attr" value="0" <?php if($rs['attr']=='0') echo 'checked'  ?>>
            虚版块</li>
        </ul></td>
      <td>实版块:可发布内容,虚版块:可用以虚链接与索引链接,不能发布内容</td>
    </tr>
    <tr>
      <td class="td80">副版块名称:</td>
      <td class="vtop rowform"><input name="subname" id="subname" value="<?php echo $rs['subname']  ; ?>" type="text" class="txt"  /></td>
      <td>副版块名称</td>
    </tr>
    <tr>
      <td class="td80">所属模型:</td>
      <td class="vtop rowform"><select name="modelid" id="modelid">
          <?php echo model::select($rs['mid']) ; ?>
        </select></td>
      <td>本版块的上级版块或分类</td>
    </tr>
    <tr>
      <td class="td80">显示版块:</td>
      <td class="vtop rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($rs['status']=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="status" value="1" <?php if($rs['status']=='1') echo 'checked'  ?>>
            是</li>
          <li<?php if($rs['status']=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="status" value="0" <?php if($rs['status']=='0') echo 'checked'  ?>>
            否 </li>
        </ul></td>
      <td>选择“否”将暂时将版块隐藏不显示，但版块内容仍将保留，且用户仍可通过直接提供的 URL 访问到此版块</td>
    </tr>
    <tr>
      <td class="td80">支持投稿:</td>
      <td class="vtop rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($rs['issend']=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="issend" value="1" <?php if($rs['issend']=='1') echo 'checked'  ?>>
            是</li>
          <li<?php if($rs['issend']=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="issend" value="0" <?php if($rs['issend']=='0') echo 'checked'  ?>>
            否</li>
        </ul></td>
      <td>选择“否”该版块将不允许用户发布内容</td>
    </tr>
    <tr>
      <td class="td80">审核投稿:</td>
      <td class="vtop rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($rs['isexamine']=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="isexamine" value="1" <?php if($rs['isexamine']=='1') echo 'checked'  ?>>
            是</li>
          <li<?php if($rs['isexamine']=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="isexamine" value="0" <?php if($rs['isexamine']=='0') echo 'checked'  ?>>
            否</li>
        </ul></td>
      <td>选择“否”用户发布的内容将不用通过管理员审核,直接显示</td>
    </tr>
    <tr>
      <td class="td80">访问密码:</td>
      <td class="vtop rowform"><input name="password" value="<?php echo $rs['password'] ; ?>" type="text" class="txt"  /></td>
      <td>当您设置密码后，用户必须输入密码才可以访问到此版块</td>
    </tr>
    <tr>
      <td class="td80">转向 URL:</td>
      <td class="vtop rowform"><input name="url" value="<?php echo $rs['url'] ; ?>" type="text" class="txt"  /></td>
      <td>如果设置转向 URL(例如 http://www.idreamsoft.com)，用户点击本分版块将进入转向中设置的 URL。一旦设定将无法进入版块页面，请确认是否需要使用此功能，留空为不设置转向 URL</td>
    </tr>
    <tr>
      <td class="td80">版块图标:</td>
      <td class="vtop rowform"><input name="pic" value="<?php echo $rs['pic'] ; ?>" type="text" id="pic" style="width:220px;"/>
        <input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=file&click=file&type=gif,jpg,png,jpeg','pic','设置版块图标');" hidefocus=true /></td>
      <td>版块图标，可填写相对或绝对地址。</td>
    </tr>
    <tr>
      <td class="td80">标题:</td>
      <td class="vtop rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="title" id="title" cols="50" class="tarea"><?php echo $rs['title'] ; ?></textarea></td>
      <td>附加标题</td>
    </tr>
    <tr>
      <td class="td80">关键词:</td>
      <td class="vtop rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="keywords" id="keywords" cols="50" class="tarea"><?php echo $rs['keywords'] ; ?></textarea></td>
      <td>此关键词用于搜索引擎优化，放在 meta 的 keyword 标签中，多个关键字间请用半角逗号 "," 隔开</td>
    </tr>
    <tr>
      <td class="td80">版块简介:</td>
      <td class="vtop rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="description" id="description" cols="50" class="tarea"><?php echo $rs['description'] ; ?></textarea></td>
      <td>对本版块的简短描述</td>
    </tr>
    <tr>
      <td class="td80">排列顺序:</td>
      <td class="vtop rowform"><input name="orderNum" id="orderNum" value="<?php echo $rs['orderNum']   ?>" type="text" class="txt"  /></td>
      <td>版块的显示顺序</td>
    </tr>
    <thead>
      <tr>
        <th colspan="3">URL规则设置</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">访问模式:</td>
      <td class="vtop rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($rs['mode']=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="mode" value="0" <?php if($rs['mode']=='0') echo 'checked'  ?>>
            动态</li>
          <li<?php if($rs['mode']=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="mode" value="1" <?php if($rs['mode']=='1') echo 'checked'  ?>>
            静态</li>
          <li<?php if($rs['mode']=='2') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="mode" value="2" <?php if($rs['mode']=='2') echo 'checked'  ?>>
            伪静态</li>
        </ul></td>
      <td>伪静态模式可根据需要生成静态文件,<br /><span style="color: #0000BB">程序会尝试创建伪静态文件.htaccess(APACHE)!如果有多种规则或者无法访问请手动修改.htaccess文件</span></td>
    </tr>
    <tr>
      <td class="td80">版块域名:</td>
      <td class="vtop rowform"><input name="domain" id="domain" value="<?php echo $rs['domain']  ; ?>" type="text" class="txt"  /></td>
      <td>请绑定域名到版块目录,不绑定请留空</td>
    </tr>
    <tr>
      <td class="td80">版块目录:</td>
      <td class="vtop rowform"><input name="dir" type="text" id="dir" value="<?php echo $rs['dir'] ; ?>" class="txt"/></td>
      <td>本版块生成静态文件保存目录,为空时程序将自动以版块名称拼音填充</td>
    </tr>
    <tr>
      <td class="td80">静态后缀:</td>
      <td class="vtop rowform"><input name="htmlext" id="htmlext" value="<?php echo $rs['htmlext']   ?>" type="text" class="txt"  /></td>
      <td>生成静态时可用,版块下文章生成后缀继承此设置.推荐使用.html 或者留空</td>
    </tr>
    <tr>
      <td class="td80">版块URL规则:</td>
      <td class="vtop rowform"><input name="forumRule" id="forumRule" value="<?php echo $rs['forumRule']   ?>" type="text" class="txt"  /></td>
      <td><a href="javascript:void(0);" class="viewRule" to="forum">[查看URL规则]</a> <span class="rewrite" style="color: #0000BB">URL规则 必需要有{FDIR}版块目录或者{FID}版块ID</span></td>
    </tr>
    <tr>
      <td class="td80">内容URL规则:</td>
      <td class="vtop rowform"><input name="contentRule" id="contentRule" value="<?php echo $rs['contentRule']   ?>" type="text" class="txt"  /></td>
      <td><a href="javascript:void(0);" class="viewRule" to="content">[查看URL规则]</a> <span class="rewrite" style="color: #0000BB">URL规则 必需要有{AID}文章ID 或者{0xID}文章ID补零或者{LINK}文章自定义链接</span></td>
    </tr>
    <thead>
      <tr>
        <th colspan="3">模版设置</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">首页模板:</td>
      <td class="vtop rowform"><input name="indexTPL" type="text" id="indexTPL" value="<?php echo $rs['indexTPL']?$rs['indexTPL']:'{TPL}/forum.index.htm' ; ?>" size="40" style="width:220px;"/>
        <input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=template&click=file&type=htm','indexTPL','设置版块首页模板');" hidefocus=true /></td>
      <td>设置模板
        {TPL}网站默认模板</td>
    </tr>
    <tr>
      <td class="td80">列表模板:</td>
      <td class="vtop rowform"><input name="listTPL" type="text" id="listTPL" value="<?php echo $rs['listTPL']?$rs['listTPL']:'{TPL}/forum.list.htm' ; ?>" size="40" style="width:220px;"/>
        <input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=template&click=file&type=htm','listTPL','设置版块列表模板');" hidefocus=true /></td>
      <td>设置模板
        {TPL}网站默认模板</td>
    </tr>
    <tr>
      <td class="td80">内容模板:</td>
      <td class="vtop rowform"><input name="contentTPL" type="text" id="contentTPL" value="<?php echo $rs['contentTPL']?$rs['contentTPL']:'{TPL}/show.htm' ; ?>" size="40" style="width:220px;"/>
        <input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=template&click=file&type=htm','contentTPL','设置版块内容模板');" hidefocus=true /></td>
      <td>设置模板
        {TPL}网站默认模板</td>
    </tr>
    <tfoot>
      <tr>
        <td colspan="3"><input type="submit" class="submit" name="detailsubmit" value="提交"  /></td>
      </tr>
    </tfoot>
  </table>
</form>
<div id="viewRule_content" class="tipsdiv">
  <table class="adminlist">
    <thead>
      <tr>
        <th><span style="float:right;margin-top:4px;" class="close" parent="viewRule_content"><img src="admin/images/close.gif" /></span>内容URL规则</th>
      </tr>
    </thead>
    <tr>
      <td> {FDIR}版块目录 {FPDIR}版块目录(含父目录)
        {FID}版块ID<br />
        {P}分页数
        {EXT}后缀<br />
        {YY}2位数年份
        {YYYY}4位数年份<br />
        {M}没前导零1-12 月份
        {MM}有前导零01-12月份<br />
        {D}没前导零1-31
        {DD}有前导零01-31<br />
        {AID}文章ID
        {0x3ID}文章ID补零(8位前3位)<br />
        {0x3,2ID}文章ID补零(8位从第3位起两位)
        {0xID}文章ID补零（8位）<br />
        {LINK}文章自定义链接
        {MD5}文章ID(16位)
        {TIME}文章发布时间戳<br />
        {MID}模型ID{MNAME}模型名称<br />
        {PHP}动态程序</td>
    </tr>
  </table>
</div>
<div id="viewRule_forum" class="tipsdiv">
  <table class="adminlist">
    <thead>
      <tr>
        <th><span style="float:right;margin-top:4px;" class="close" parent="viewRule_forum"><img src="admin/images/close.gif" /></span>版块URL规则</th>
      </tr>
    </thead>
    <tr>
      <td>{FDIR}版块目录,{FID}版块ID,{0xFID}版块ID补零（8位）,{MD5}版块ID MD5 <br />
        {P}分页数,{EXT}后缀<br />
        {PHP}动态程序</td>
    </tr>
  </table>
</div>
<script language="javascript" type="text/javascript">
    mode('<?php echo $rs['mode'] ; ?>');
    function mode(v){
        $(".mode").hide();
        $("#mode"+v).show();
    }
    $(function(){
        $("#cpform").submit(function(){
            if($("#name").val()==''){
                iCMS.D("版块名不能为空!").dialog({modal: true,buttons: {"确定": function() {
                            $(this).dialog('close');
                            $("#name").focus();
                        }}});
                return false;
            }
        });
    });
</script>
</body></html>