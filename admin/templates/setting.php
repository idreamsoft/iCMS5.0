<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http：//www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<style type="text/css">
.waterpos li { width:30%; }
</style>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;系统设置</div>
<form name="cpform" method="post" action="<?php echo __ADMINCP__; ?>=setting&do=update" id="cpform" target="iCMS_FRAME">
  <table class="adminlist" border="0" cellspacing="0" cellpadding="0">
    <?php if(in_array($this->action,array('default','config'))){
		Admin::MP(array("menu_setting_all","menu_setting_config"));   ?>
    <thead>
      <tr>
        <th colspan="4">网站信息配置 </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">网站名称：</td>
        <td class="rowform"><input name="name" value="<?php echo $this->iCMS->config['name'];?>" type="text" class="txt" id="name"  /></td>
        <td>网站名称</td>
        <td width="200">&lt;!--{$site.title}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">标题附加字：</td>
        <td class="rowform"><input name="seotitle" type="text" class="txt" id="seotitle" value="<?php echo $this->iCMS->config['seotitle'];?>"  /></td>
        <td><span class="tdbg">网页标题通常是搜索引擎关注的重点，本附加字设置将出现在标题中网站名称的后面，如果有多个关键字，建议用 &quot;|&quot;、&quot;,&quot;(不含引号) 等符号分隔</span></td>
        <td>&lt;!--{$site.seotitle}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">网站关键字：</td>
        <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="keywords" id="keywords" cols="50" class="tarea"><?php echo $this->iCMS->config['keywords'];?></textarea></td>
        <td><span class="tdbg">更容易被搜索引擎找到用&quot;,&quot;号隔开</span></td>
        <td>&lt;!--{$site.keywords}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">网站描述：</td>
        <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="description" id="description" cols="50" class="tarea"><?php echo $this->iCMS->config['description'];?></textarea></td>
        <td>将被搜索引擎用来说明您网站的主要内容</td>
        <td>&lt;!--{$site.description}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">ICP备案号：</td>
        <td class="rowform"><input name="icp" type="text" class="txt" id="icp" value="<?php echo $this->iCMS->config['icp'];?>"  /></td>
        <td>页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入您的授权码，它将显示在页面底部，如果没有请留空</td>
        <td>&lt;!--{$site.icp}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">站长信箱：</td>
        <td class="rowform"><input name="masteremail" type="text" class="txt" id="masteremail" value="<?php echo $this->iCMS->config['masteremail'];?>"  /></td>
        <td>&nbsp;</td>
        <td>&lt;!--{$site.email}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">默认模板：</td>
        <td class="rowform"><input name="template" type="text" class="txt" id="template" value="<?php echo $this->iCMS->config['template'];?>" /></td>
        <td><input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=template&click=dir','template','选择默认模板');" hidefocus=true /></td>
        <td>&lt;!--{$site.tpl}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">首页模板：</td>
        <td class="rowform"><input name="indexTPL" type="text" class="txt" id="indexTPL" value="<?php echo $this->iCMS->config['indexTPL'];?>" /></td>
        <td><input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=template&click=file&type=htm','indexTPL','选择首页模板');" hidefocus=true /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="td120">程序错误提示：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="debug" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="debug" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="td120">模板错误提示：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="tpldebug" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="tpldebug" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">需要开启程序错误提示</td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','url'))) {
    Admin::MP(array("menu_setting_all","menu_setting_url"));   ?>
    <thead>
      <tr>
        <th colspan="4">网站URL配置 </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">CMS安装地址：</td>
        <td class="rowform"><input name="setupURL" value="<?php echo $this->iCMS->config['setupURL'];?>" type="text" class="txt" id="setupURL"  /></td>
        <td>CMS安装地址，必须以 http：// 开头 <br />
          如：http：//www.idreamsoft.com/iCMS</td>
        <td>&lt;!--{$site.setupURL}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">公共程序URL：</td>
        <td class="rowform"><input name="publicURL" value="<?php echo $this->iCMS->config['publicURL'];?>" type="text" class="txt" id="publicURL"  /></td>
        <td>公共程序访问URL 如果访问出错请修改public/config.php文件</td>
        <td>&lt;!--{$site.publicURL}--&gt;</td>
      </tr>
      <tr>
        <td class="td120">静态目录URL：</td>
        <td class="rowform"><input name="htmlURL" value="<?php echo $this->iCMS->config['htmlURL'];?>" type="text" class="txt" id="htmlURL"  /></td>
        <td colspan="2">静态目录访问URL 可绑定域名 (需开启生成静态)</td>
      </tr>
      <tr>
        <td class="td120">静态目录：</td>
        <td class="rowform"><input name="htmldir" value="<?php echo $this->iCMS->config['htmldir'];?>" type="text" class="txt" id="htmldir"  /></td>
        <td colspan="2">存放静态页面目录，相对于admin目录。可用../表示上级目录</td>
      </tr>
      <tr>
        <td class="td120">文件后缀：</td>
        <td class="rowform"><input name="htmlext" type="text" class="txt" id="htmlext" value="<?php echo $this->iCMS->config['htmlext'];?>"  /></td>
        <td colspan="2">推荐使用.html</td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','tag'))) {
    Admin::MP(array("menu_setting_all","menu_setting_tag"));   ?>
    <thead>
      <tr>
        <th colspan="4">标签设置 </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">标签目录URL：</td>
        <td class="rowform"><input name="tagURL" value="<?php echo $this->iCMS->config['tagURL'];?>" type="text" class="txt" id="tagURL"  /></td>
        <td colspan="2">标签目录访问URL 可绑定域名</td>
      </tr>
      <tr>
        <td class="td120">标签目录：</td>
        <td class="rowform"><input name="tagDir" value="<?php echo $this->iCMS->config['tagDir'];?>" type="text" class="txt" id="tagDir"  /></td>
        <td colspan="2">存放标签静态页面目录，相对于admin目录。可用../表示上级目录</td>
      </tr>
      <tr>
        <td class="td120">标签URL规则：</td>
        <td class="rowform"><input name="tagRule" type="text" class="txt" id="tagRule" value="<?php echo $this->iCMS->config['tagRule'];?>"  /></td>
        <td colspan="2"><a href="javascript:void(0);" class="viewRule" to="tag">查看URL规则</a></td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','cache'))) {
    Admin::MP(array("menu_setting_all","menu_setting_cache"));   ?>
    <thead>
      <tr>
        <th colspan="4">缓存设置</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">启用缓存：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="iscache" value="1" />
              开启</li>
            <li>
              <input class="radio" type="radio" name="iscache" value="0" />
              关闭 </li>
          </ul></td>
        <td colspan="2">系统缓存不受此限制</td>
      </tr>
      <tr>
        <td class="td120">缓存引擎：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="cacheEngine" value="memcached" />
              分布式缓存 memcached</li>
            <li>
              <input class="radio" type="radio" name="cacheEngine" value="file" />
              文件缓存 FileCache</li>
          </ul></td>
        <td colspan="2"><?php if(!class_exists('memcached'))echo '注：当前服务器环境不支持Memcache客户端';   ?></td>
      </tr>
    </tbody>
    <tbody id='filecache' style="display:none">
      <tr>
        <td class="td120">缓存目录：</td>
        <td class="rowform"><input class="txt" name="cachedir" type="text" id="cachedir" value="<?php echo $this->iCMS->config['cachedir'];?>" /></td>
        <td colspan="2">缓存目录</td>
      </tr>
      <tr>
        <td class="td120">缓存目录层级：</td>
        <td class="rowform"><input class="txt" name="cachelevel" type="text" id="cachelevel" value="<?php echo $this->iCMS->config['cachelevel'];?>" /></td>
        <td colspan="2">缓存目录分布层级 最好不要超过3层</td>
      </tr>
    </tbody>
    <tbody id='memCache' style="display:none">
      <tr>
        <td class="td120">缓存服务器：</td>
        <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="cacheServers" id="cacheServers" cols="50" class="tarea"><?php echo $this->iCMS->config['cacheServers'];?></textarea></td>
        <td colspan="2">每行一个,要带端口. <br />
          例：<br />
          127.0.0.1：11211<br />
          192.0.0.1：11211<br />
          10.0.0.1：11211</td>
      </tr>
    </tbody>
    <tbody>
      <tr>
        <td class="td120">缓存时间：</td>
        <td class="rowform"><input class="txt" name="cachetime" type="text" id="cachetime" value="<?php echo $this->iCMS->config['cachetime'];?>" /></td>
        <td colspan="2">缓存时间</td>
      </tr>
      <tr>
        <td class="td120">gzip压缩缓存数据：</td>
        <td colspan="3" class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="iscachegzip" value="1" />
              是</li>
            <li>
              <input class="radio" type="radio" name="iscachegzip" value="0" />
              否</li>
          </ul></td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','other'))) {
    Admin::MP(array("menu_setting_all","menu_setting_other"));   ?>
    <thead>
      <tr>
        <th colspan="4">其它设置</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">评论：</td>
        <td colspan="3" class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="iscomment" value="1" />
              开启</li>
            <li>
              <input class="radio" type="radio" name="iscomment" value="0" />
              关闭</li>
          </ul></td>
      </tr>
      <tr>
        <td class="td120">审核评论：</td>
        <td colspan="3" class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="isexamine" value="1" />
              是</li>
            <li>
              <input class="radio" type="radio" name="isexamine" value="0" />
              否</li>
          </ul></td>
      </tr>
      <tr>
        <td class="td120">匿名评论：</td>
        <td colspan="3" class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="anonymous" value="1" />
              是</li>
            <li>
              <input class="radio" type="radio" name="anonymous" value="0" />
              否</li>
          </ul></td>
      </tr>
      <tr>
        <td class="td120">模板缩略图：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="issmall" value="1" />
              是</li>
            <li>
              <input class="radio" type="radio" name="issmall" value="0" />
              否</li>
          </ul></td>
        <td colspan="2">在模板中使用small调用缩略图，如果缩略图不存在。由程序强制生成缩略图，影响速度</td>
      </tr>
      <tr>
        <td class="td120">匿名显示：</td>
        <td class="rowform"><input class="txt" name="anonymousname" type="text" id="anonymousname" value="<?php echo $this->iCMS->config['anonymousname'];?>" /></td>
        <td colspan="2">匿名显示</td>
      </tr>
      <tr>
        <td class="td120">digg时间间隔限制：</td>
        <td class="rowform"><input class="txt" name="diggtime" type="text" id="diggtime" value="<?php echo $this->iCMS->config['diggtime'];?>" /></td>
        <td colspan="2">内容,回复</td>
      </tr>
      <tr>
        <td class="td120">关键字替换：</td>
        <td class="rowform"><input class="txt" name="kwCount" type="text" id="kwCount" value="<?php echo $this->iCMS->config['kwCount'];?>" /></td>
        <td colspan="2"><span class="td120">关键字替换次数</span> 0为不替换，-1全部替换</td>
      </tr>
      <tr>
        <td class="td120">搜索结果：</td>
        <td class="rowform"><input name="searchprepage" type="text" id="searchprepage" value="<?php echo $this->iCMS->config['searchprepage'];?>" maxlength="6" class="txt"/></td>
        <td colspan="2">搜索每页显示多少条记录</td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','time'))) {
    Admin::MP(array("menu_setting_all","menu_setting_time"));   ?>
    <thead>
      <tr>
        <th colspan="4">时间设置</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">服务器时区：</td>
        <td class="rowform"><select name="ServerTimeZone">
            <option value="-12">(标准时-12：00) 日界线西 </option>
            <option value="-11">(标准时-11：00) 中途岛、萨摩亚群岛 </option>
            <option value="-10">(标准时-10：00) 夏威夷 </option>
            <option value="-9">(标准时-9：00) 阿拉斯加 </option>
            <option value="-8">(标准时-8：00) 太平洋时间(美国和加拿大) </option>
            <option value="-7">(标准时-7：00) 山地时间(美国和加拿大) </option>
            <option value="-6">(标准时-6：00) 中部时间(美国和加拿大)、墨西哥城 </option>
            <option value="-5">(标准时-5：00) 东部时间(美国和加拿大)、波哥大 </option>
            <option value="-4">(标准时-4：00) 大西洋时间(加拿大)、加拉加斯 </option>
            <option value="-3.5">(标准时-3：30) 纽芬兰 </option>
            <option value="-3">(标准时-3：00) 巴西、布宜诺斯艾利斯、乔治敦 </option>
            <option value="-2">(标准时-2：00) 中大西洋 </option>
            <option value="-1">(标准时-1：00) 亚速尔群岛、佛得角群岛 </option>
            <option value="111">(格林尼治标准时) 西欧时间、伦敦、卡萨布兰卡 </option>
            <option value="1">(标准时+1：00) 中欧时间、安哥拉、利比亚 </option>
            <option value="2">(标准时+2：00) 东欧时间、开罗，雅典 </option>
            <option value="3">(标准时+3：00) 巴格达、科威特、莫斯科 </option>
            <option value="3.5">(标准时+3：30) 德黑兰 </option>
            <option value="4">(标准时+4：00) 阿布扎比、马斯喀特、巴库 </option>
            <option value="4.5">(标准时+4：30) 喀布尔 </option>
            <option value="5">(标准时+5：00) 叶卡捷琳堡、伊斯兰堡、卡拉奇 </option>
            <option value="5.5">(标准时+5：30) 孟买、加尔各答、新德里 </option>
            <option value="6">(标准时+6：00) 阿拉木图、 达卡、新亚伯利亚 </option>
            <option value="7">(标准时+7：00) 曼谷、河内、雅加达 </option>
            <option value="8">(北京时间) 北京、重庆、香港、新加坡 </option>
            <option value="9">(标准时+9：00) 东京、汉城、大阪、雅库茨克 </option>
            <option value="9.5">(标准时+9：30) 阿德莱德、达尔文 </option>
            <option value="10">(标准时+10：00) 悉尼、关岛 </option>
            <option value="11">(标准时+11：00) 马加丹、索罗门群岛 </option>
            <option value="12">(标准时+12：00) 奥克兰、惠灵顿、堪察加半岛 </option>
          </select></td>
        <td colspan="2">服务器所在时区</td>
      </tr>
      <tr>
        <td class="td120">服务器时间校正：</td>
        <td class="rowform"><input name="cvtime" type="text" id="cvtime" value="<?php echo $this->iCMS->config['cvtime'];?>" maxlength="6" class="txt"/>
          <span class="td120">分钟</span></td>
        <td colspan="2">此功能用于校正服务器操作系统时间设置错误的问题<br />
          当确认程序默认时区设置正确后，程序显示时间仍有错误，请使用此功能校正</td>
      </tr>
      <tr>
        <td class="td120">默认时间格式：</td>
        <td class="rowform"><input name="dateformat" type="text" id="dateformat" value="<?php echo $this->iCMS->config['dateformat'];?>" class="txt"/></td>
        <td colspan="2">格式如：Y-m-d H：i：s <br />
          Y：4位数年份,y：2位数年份<br />
          m：有前导零01-12,n：没前导零1-12<br />
          d：有前导零01-31,j：没前导零1-31</td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','publish'))) {
    Admin::MP(array("menu_setting_all","menu_setting_publish"));   ?>
    <thead>
      <tr>
        <th colspan="4">发表文章相关设置</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">是否允许使用客户端发布文章：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="enable_xmlrpc" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="enable_xmlrpc" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">开启后发表文章时该选项默认为选中状态</td>
      </tr>
      <tr>
        <td class="td120">关键字转标签：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="keywordToTag" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="keywordToTag" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">开启后发表文章时该选项默认为选中状态</td>
      </tr>
      <tr>
        <td class="td120">下载远程图片：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="remote" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="remote" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">开启后发表文章时该选项默认为选中状态</td>
      </tr>
      <tr>
        <td class="td120">提取缩略图：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="autopic" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="autopic" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">开启后发表文章时该选项默认为选中状态</td>
      </tr>
      <tr>
        <td class="td120">提取摘要：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="autodesc" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="autodesc" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">开启后发表文章时程序要自动提取文章部分内容为文章摘要</td>
      </tr>
      <tr>
        <td class="td120">提取摘要字数：</td>
        <td class="rowform"><input name="descLen" type="text" id="descLen" value="<?php echo $this->iCMS->config['descLen'];?>" class="txt"></td>
        <td colspan="2">设置自动提取内容摘要字数</td>
      </tr>
      <tr>
        <td class="td120">自动内容分页：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="AutoPage" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="AutoPage" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">开启后发表文章时程序要自动提取文章部分内容为文章摘要</td>
      </tr>
      <tr>
        <td class="td120">内容分页字数：</td>
        <td class="rowform"><input name="AutoPageLen" type="text" id="AutoPageLen" value="<?php echo $this->iCMS->config['AutoPageLen'];?>" class="txt"></td>
        <td colspan="2">设置自动内容分页字数</td>
      </tr>
      <tr>
        <td class="td120">检查标题重复：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="repeatitle" value="1">
              开启 </li>
            <li>
              <input class="radio" type="radio" name="repeatitle" value="0">
              关闭 </li>
          </ul></td>
        <td colspan="2">开启后不能发表相同标题的文章</td>
      </tr>
      <tr>
        <td class="td120">拼音分割符：</td>
        <td class="rowform"><input name="CLsplit" type="text" id="CLsplit" value="<?php echo $this->iCMS->config['CLsplit'];?>" class="txt"></td>
        <td colspan="2">留空，按紧凑型生成</td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','attachments'))) {
    Admin::MP(array("menu_setting_all","menu_setting_attachments"));   ?>
    <thead>
      <tr>
        <th colspan="4">附件设置</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">附件URL：</td>
        <td class="rowform"><input name="uploadURL" type="text" id="uploadURL" value="<?php echo $this->iCMS->config['uploadURL'];?>" class="txt"></td>
        <td colspan="2">不设置请留空.暂不支持远程服务器</td>
      </tr>
      <!--
                  <tr>
                    <td class="td120">远程密钥：</td>
                  </tr>
                  <tr>
                    <td class="rowform"><input name="remoteKey" type="text" id="remoteKey" value="<?php echo $this->iCMS->config['remoteKey'];?>" class="txt"></td>
                    <td colspan="2">密钥：请设置在8-64位</td>
                  </tr>
                  <tr>
                    <td class="td120">上传程序名：</td>
                  </tr>
                  <tr>
                    <td class="rowform"><input name="uploadScript" type="text" id="uploadScript" value="<?php echo $this->iCMS->config['uploadScript'];?>" class="txt"></td>
                    <td colspan="2">相关程序</td>
                  </tr>-->
      <tr>
        <td class="td120">文件保存目录：</td>
        <td class="rowform"><input name="uploadfiledir" type="text" id="uploadfiledir" value="<?php echo $this->iCMS->config['uploadfiledir'];?>" class="txt"></td>
        <td colspan="2">相对于程序根目录</td>
      </tr>
      <tr>
        <td class="td120">文件保存方式：</td>
        <td class="rowform"><input name="savedir" type="text" id="savedir" value="<?php echo $this->iCMS->config['savedir'];?>" size="50" class="txt"></td>
        <td colspan="2">为空全部存入同一目录<br />
          EXT：文件类型<br />
          Y：4位数年份，y：2位数年份<br />
          m：有前导零01-12，n：没前导零1-12<br />
          d：有前导零01-31，j：没前导零1-31</td>
      </tr>
      <tr>
        <td class="td120">允许上传类型：</td>
        <td class="rowform"><input name="fileext" type="text" id="fileext" value="<?php echo $this->iCMS->config['fileext'];?>" size="50" class="txt"></td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td class="td120">生成缩略图：</td>
        <td colspan="3" class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="isthumb" value="1" />
              开启 </li>
            <li>
              <input class="radio" type="radio" name="isthumb" value="0" />
              关闭 </li>
          </ul></td>
      </tr>
      <tr>
        <td class="td120">缩略图宽度：</td>
        <td colspan="3" class="rowform"><input name="thumbwidth" type="text" value="<?php echo $this->iCMS->config['thumbwidth'];?>" size=10 maxlength="3" class="txt"/>
          px</td>
      </tr>
      <tr>
        <td class="td120">缩略图高度：</td>
        <td colspan="3" class="rowform"><input name="thumbhight" type="text" value="<?php echo $this->iCMS->config['thumbhight'];?>" size=10 maxlength="3" class="txt"/>
          px</td>
      </tr>
      <tr>
        <td class="td120">缩略图水印：</td>
        <td colspan="3" class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="thumbwatermark" value="1" />
              开启 </li>
            <li>
              <input class="radio" type="radio" name="thumbwatermark" value="0" />
              关闭 </li>
          </ul></td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default','watermark'))) {
    Admin::MP(array("menu_setting_all","menu_setting_watermark"));   ?>
    <thead>
      <tr>
        <th colspan="4">水印设置</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td120">图片水印：</td>
        <td class="rowform"><ul onmouseover="altStyle(this);">
            <li>
              <input class="radio" type="radio" name="iswatermark" value="1" />
              开启 </li>
            <li>
              <input class="radio" type="radio" name="iswatermark" value="0" />
              关闭 </li>
          </ul></td>
        <td colspan="2">将在上传的图片附件中加上您在下面设置的图片或文字水印</td>
      </tr>
      <tr>
        <td class="td120">图片宽度：</td>
        <td class="rowform"><input size="10" name="waterwidth" value="<?php echo $this->iCMS->config['waterwidth'];?>" class="txt"/>
          px</td>
        <td colspan="2">只对超过程序设置的大小的附件图片才加上水印图片或文字(设置为0不限制)</td>
      </tr>
      <tr>
        <td class="td120">图片高度：</td>
        <td class="rowform"><input size="10" name="waterheight" value="<?php echo $this->iCMS->config['waterheight'];?>" class="txt"/>
          px</td>
        <td colspan="2">只对超过程序设置的大小的附件图片才加上水印图片或文字(设置为0不限制)</td>
      </tr>
      <tr>
        <td class="td120">水印位置：</td>
        <td colspan="3" class="rowform"><ul onmouseover="altStyle(this);" class="waterpos">
            <li style="clear:both;width:100%">
              <input class="radio" type="radio" name="waterpos" value="0" />
              随机位置 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="1" />
              顶部居左 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="2" />
              顶部居中 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="3" />
              顶部居右 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="4" />
              中部居左 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="5" />
              中部居中 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="6" />
              中部居右 </li>
            <br />
            <li>
              <input class="radio" type="radio" name="waterpos" value="7" />
              底部居左 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="8" />
              底部居中 </li>
            <li>
              <input class="radio" type="radio" name="waterpos" value="9" />
              底部居右 </li>
          </ul></td>
      </tr>
      <tr>
        <td class="td120">水印图片文件：</td>
        <td class="rowform"><input name="waterimg" value="<?php echo $this->iCMS->config['waterimg'];?>" class="txt"/></td>
        <td colspan="2">水印图片存放路径：include/watermark/<?php echo $this->iCMS->config['waterimg'];?>，
          如果水印图片不存在，则使用文字水印</td>
      </tr>
      <tr>
        <td class="td120">水印文字：</td>
        <td class="rowform"><input name="watertext" value="<?php echo $this->iCMS->config['watertext'];?>" size="40" class="txt"/></td>
        <td colspan="2">暂不支持中文</td>
      </tr>
      <tr>
        <td class="td120">水印文字字体大小：</td>
        <td colspan="3" class="rowform"><input name="waterfontsize" value="<?php echo $this->iCMS->config['waterfontsize'];?>" size="10" class="txt"/></td>
      </tr>
      <tr>
        <td class="td120">水印文字颜色：</td>
        <td colspan="3" class="rowform"><input name="watercolor" value="<?php echo $this->iCMS->config['watercolor'];?>" size="10" maxlength="7" class="txt"/></td>
      </tr>
      <tr>
        <td class="td120">水印透明度：</td>
        <td colspan="3" class="rowform"><input name="waterpct" value="<?php echo $this->iCMS->config['waterpct'];?>" size="10" maxlength="3" class="txt"/></td>
      </tr>
    </tbody>
    <?php }if(in_array($this->action,array('default0000','patch0000'))) {
    Admin::MP(array("menu_setting_all","menu_setting_patch"));   ?>
    <thead>
      <tr>
        <th colspan="4">自动更新</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="rowform" colspan="4"><br />
        自动更新程序能够自动找到可用新并通知您.<br /><br />
        请选择自动更新iCMS的方式<br /><br />
        <ul onmouseover="altStyle(this);">
            <li style="clear:both;width:100%">
              <input class="radio" type="radio" name="autopatch" value="1" />
              自动下载,安装时询问(推荐) </li>
            <li style="clear:both;width:100%">
              <input class="radio" type="radio" name="autopatch" value="2" />
              不自动下载更新,有更新时提示 </li>
            <li style="clear:both;width:100%">
              <input class="radio" type="radio" name="autopatch" value="0" />
              关闭自动更新 </li>
          </ul></td>
      </tr>
    </tbody>
    <thead>
      <tr>
        <th colspan="4">手动更新</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="4">你可以立即检查更新,升级到最新版本.查看更多信息请访问 <a href="http://www.idreamsoft.com" target="_blank">www.idreamsoft.com</a> <input type="button" value="在线升级" onClick="iCMS_patch();" class="submit"></td>
      </tr>
    </tbody>
    <?php }   ?>
    <tfoot>
      <tr>
        <td colspan="4" align="center"><input type="submit" class="submit" name="settingsubmit" value="提交"  /></td>
      </tr>
    </tfoot>
  </table>
</form>
<div id="viewRule_tag" class="tipsdiv">
  <table class="adminlist">
    <thead>
      <tr>
        <th><span style="float:right;margin-top:4px;" class="close" parent="viewRule_tag"><img src="admin/images/close.gif" /></span>版块URL规则</th>
      </tr>
    </thead>
    <tr>
      <td>{TID}标签ID,{TID500}标签ID值除以500,{MD5}标签ID MD5<br />
        {ZH_CN}标签名(中文),{NAME}标签名<br />
        {LINK}标签自定义链接,<br />
        {FDIR}分类目录,{SID}分类ID <br />
        {P}分页数,{EXT}后缀<br />
        {PHP}动态程序</td>
    </tr>
  </table>
</div>
<script type="text/javascript">
$(function(){
<?php if(in_array($this->action,array('default','config'))) {?>
	checked('debug',"<?php echo $this->iCMS->config['debug'];?>");
	checked('tpldebug',"<?php echo $this->iCMS->config['tpldebug'];?>");
<?php }if(in_array($this->action,array('default','cache'))) {?>
	$('input[name=cacheEngine]').click(function(){
		if(this.value=="file"){
			$("#filecache").show();
			$("#memCache").hide();
		}else{
			$("#filecache").hide();
			$("#memCache").show();
		}
	});
	$('input[name=cacheEngine][value=<?php echo $this->iCMS->config['cacheEngine'];?>]').click();
    checked('iscache',"<?php echo $this->iCMS->config['iscache'];?>");
    checked('cacheEngine',"<?php echo $this->iCMS->config['cacheEngine'];?>");
    checked('iscachegzip',"<?php echo $this->iCMS->config['iscachegzip'];?>");
<?php }if(in_array($this->action,array('default','other'))) {?>
	checked('iscomment',"<?php echo $this->iCMS->config['iscomment'];?>");
    checked('isexamine',"<?php echo $this->iCMS->config['isexamine'];?>");
    checked('anonymous',"<?php echo $this->iCMS->config['anonymous'];?>");
    checked('issmall',"<?php echo $this->iCMS->config['issmall'];?>");
<?php }if(in_array($this->action,array('default','time'))) {?>
	$('select[name=ServerTimeZone]').val(<?php echo $this->iCMS->config['ServerTimeZone'];?>);
<?php }if(in_array($this->action,array('default','publish'))) {?>        
    checked('enable_xmlrpc',"<?php echo $this->iCMS->config['enable_xmlrpc'];?>");
    checked('keywordToTag',"<?php echo $this->iCMS->config['keywordToTag'];?>");
    checked('remote',"<?php echo $this->iCMS->config['remote'];?>");
    checked('autopic',"<?php echo $this->iCMS->config['autopic'];?>");
    checked('autodesc',"<?php echo $this->iCMS->config['autodesc'];?>");
    checked('AutoPage',"<?php echo $this->iCMS->config['AutoPage'];?>");
    checked('repeatitle',"<?php echo $this->iCMS->config['repeatitle'];?>");
<?php }if(in_array($this->action,array('default','attachments'))) {?>
	checked('isthumb',"<?php echo $this->iCMS->config['isthumb'];?>");
	checked('thumbwatermark',"<?php echo $this->iCMS->config['thumbwatermark'];?>");
<?php }if(in_array($this->action,array('default','watermark'))) {?>
	checked('iswatermark',"<?php echo $this->iCMS->config['iswatermark'];?>");
    checked('waterpos',"<?php echo $this->iCMS->config['waterpos'];?>");
<?php }if(in_array($this->action,array('default','patch'))) {?>
	checked('autopatch',"<?php echo $this->iCMS->config['autopatch'];?>");
<?php }?>        
});
function checked(n,v){
	$('input[name='+n+'][value='+v+']').attr("checked",true).parent().addClass("checked");
}
</script>
</body></html>