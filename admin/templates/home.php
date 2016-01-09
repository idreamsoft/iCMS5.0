<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<style type="text/css">
#cmsnews {
    display:block;
    clear:both;
}
.homepop {
    display:none;
    width:360px;
    background-color:#EBEBEB;
    border-top: 0px none;
    border-right: 4px solid #EBEBEB;
    border-bottom: 5px solid #EBEBEB;
    border-left: 0px none;
}
.homepop .adminlist{margin:0px; width:100%;}
</style>
<script src="admin/js/jquery.floatDiv.js" type="text/javascript"></script>
<script type="text/javascript">
    function dofeedback(){
        $('#feedback').toggle().floatdiv("middle").draggable();
    }
    $(function(){
        $.getJSON("http://www.idreamsoft.com/cms/getLicense.php?callback=?",{license: '<?php echo $license ; ?>'},
        function(o){
            $('#license').html(o.license);
        }
    );
    });
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;首页</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  style="width:24%; height:110px;"><table class="adminlist border" cellspacing="0">
                <thead>
                    <tr>
                        <th>iCMS帮助</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="http://www.idreamsoft.com/doc/iCMS/index.html" target="_blank">模版标签说明</a></td>
                    </tr>
                    <tr>
                        <td><a href="http://www.idreamsoft.com/doc/iCMS.License.html" target="_blank">iCMS使用许可协议</a></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:void(0)" onclick="dofeedback();">提交BUG/问题</a></td>
                    </tr>
                </tbody>
            </table></td>
        <td style="width:45%;"><table class="adminlist border" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan="4">数据统计</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="80">版块数</td>
                        <td><?php echo $c ; ?></td>
                        <td width="80">文章总数</td>
                        <td><?php echo $a ; ?></td>
                    </tr>
                    <tr>
                        <td>侍审内容</td>
                        <td><?php echo iCMS_DB::getValue("SELECT count(*) FROM #iCMS@__article WHERE status='0'");
; ?></td>
                        <td>评论总数</td>
                        <td><?php echo iCMS_DB::getValue("SELECT count(*) FROM #iCMS@__comment") ; ?></td>
                    </tr>
                    <tr>
                        <td>数据库大小</td>
                        <td><?php echo FS::sizeUnit($datasize+$indexsize) ; ?></td>
                        <td>文章数据大小</td>
                        <td><?php echo FS::sizeUnit($content_datasize) ; ?></td>
                    </tr>
                </tbody>
            </table></td>
        <td style="width:30%;"><table class="adminlist border" cellspacing="0">
                <thead>
                    <tr>
                        <th>iCMS相关信息</th>
                    </tr>
                </thead>
                <tr>
                    <td>授权信息:<span id="license">未获取授权</span></td>
                </tr>
                <tr>
                    <td><div id="cmsnews" style="height:53px; overflow-y:auto;"></div></td>
                </tr>
            </table></td>
    </tr>
</table>
<table class="adminlist" border="0" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th colspan="4" ><img src="admin/images/info.gif" width="18" height="18" align="absmiddle" /> 系统信息</th>
        </tr>
    </thead>
    <tr>
        <td width="120">当前程序版本</td>
        <td width="300">iCMS <?php echo iCMS_VER ; ?>[<?php echo iCMS_RELEASE ; ?>]</td>
        <td width="120"><a href="http://www.idreamsoft.com/thread.php?fid=8" target="_blank">最新版本</a></td>
        <td><span id="newversion"><img src="admin/images/ajax_loader.gif" width="16" height="16" align="absmiddle"></span></td>
    </tr>
    <tr>
        <td>服务器操作系统</td>
        <td><?php echo PHP_OS ; ?></td>
        <td>服务器端口</td>
        <td><?php echo getenv(SERVER_PORT) ; ?></td>
    </tr>
    <tr>
        <td>服务器剩余空间</td>
        <td><?php echo intval(diskfreespace(".") / (1024 * 1024))."M" ; ?></td>
        <td>服务器时间</td>
        <td><?php echo get_date('',"Y年n月j日H点i分s秒") ; ?></td>
    </tr>
    <tr>
        <td>WEB服务器版本</td>
        <td><?php echo $_SERVER['SERVER_SOFTWARE'] ; ?></td>
        <td>服务器语种</td>
        <td><?php echo getenv("HTTP_ACCEPT_LANGUAGE") ; ?></td>
    </tr>
    <tr>
        <td>PHP版本</td>
        <td><?php echo PHP_VERSION ; ?></td>
        <td>ZEND版本</td>
        <td><?php echo zend_version() ; ?></td>
    </tr>
    <tr>
        <td>MySQL 数据库</td>
        <td><?php echo $this->okorno(function_exists("mysql_close")) ; ?></td>
        <td>MySQL 版本</td>
        <td><?php echo mysql_get_server_info() ; ?></td>
    </tr>
    <tr>
        <td>图像函数库</td>
        <td><?php echo function_exists("imageline")==1?$this->okorno(function_exists("imageline")):$this->okorno(function_exists("imageline")) ; ?></td>
        <td>Session支持</td>
        <td><?php echo $this->okorno(function_exists("session_start")) ; ?></td>
    </tr>
    <tr>
        <td>脚本运行可占最大内存</td>
        <td><?php echo get_cfg_var("memory_limit")?get_cfg_var("memory_limit"):"无" ; ?></td>
        <td>脚本上传文件大小限制</td>
        <td><?php echo get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许上传附件" ; ?></td>
    </tr>
    <tr>
        <td>POST方法提交限制</td>
        <td><?php echo get_cfg_var("post_max_size") ; ?></td>
        <td>脚本超时时间</td>
        <td><?php echo get_cfg_var("max_execution_time") ; ?></td>
    </tr>
    <tr>
        <td>被屏蔽的函数</td>
        <td colspan="2"><?php echo get_cfg_var("disable_functions")?get_cfg_var("disable_functions"):"无" ; ?></td>
        <td></td>
    </tr>
</table>
<table class="adminlist" cellspacing="0">
    <thead>
        <tr>
            <th><img src="admin/images/info.gif" width="18" height="18" align="absmiddle" /> iCMS 开发信息</th>
        </tr>
    </thead>
    <tr>
        <td>版权所有：<span class="bold"><a href="http://www.idreamsoft.com" target="_blank">iDreamSoft</a></span></td>
    </tr>
    <tr>
        <td>开 发 者：<a href="http://www.idreamsoft.com/coolmoo" target="_blank">枯木 (coolmoo)</a></td>
    </tr>
    <tr>
        <td>相关链接：<a href="http://www.idreamsoft.com" target="_blank">iDreamSoft</a>, <a href="http://www.idreamsoft.com/forumdisplay.php?fid=6" target="_blank">iCMS</a>, <a href="http://www.idreamsoft.com/forumdisplay.php?fid=7" target="_blank">&#x6A21;&#x677F;</a>, <a href="http://www.idreamsoft.com/doc/iCMS/index.html" target="_blank">&#x6587;&#x6863;</a>, <a href="http://www.idreamsoft.com/forumdisplay.php?fid=6" target="_blank">&#x8BA8;&#x8BBA;&#x533A;</a></td>
    </tr>
</table>
<div id="!license" class="homepop">
    <table class="tb tb2 nobdb">
        <tr>
            <th colspan="15" ><span style="float:right;margin:2px 4px 0px 0px;;" class="close" onclick="$('#license').hide();"><img src="admin/images/close.gif" /></span>授权信息</th>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>
</div>
<div id="feedback" class="homepop">
    <table class="adminlist border" cellspacing="0">
        <form action="http://www.idreamsoft.com/cms/feedback.php" method="post" target="postfeedback">
            <input name="sname" type="hidden" value="<?php echo base64_encode($iCMS->config['name']) ; ?>" />
            <input name="url" type="hidden" value="<?php echo base64_encode($iCMS->config['url']) ; ?>" />
            <input name="host" type="hidden" value="<?php echo base64_encode($_SERVER['HTTP_HOST']) ; ?>" />
            <thead>
                <tr>
                    <th><span style="float:right;margin-top:4px;" class="close" onclick="$('#feedback').hide();"><img src="admin/images/close.gif" /></span>用户反馈</th>
                </tr>
            </thead>
            <tr>
                <td><p align="left"><strong>尊敬的用户：</strong><br />
                        ·如果您想了解如何使用iCMS，请参考<a href="http://www.idreamsoft.com/help.html" target="_blank">帮助中心</a>。<br />
                        ·如果您对我们的产品想提出意见或建议，请填写具体内容。<br />
                        ·如果您留下真实邮箱，将有机会获得我们送出的小礼品。</p></td>
            </tr>
            <tr>
                <td><strong>您遇到的问题类型：（必填）</strong></td>
            </tr>
            <tr>
                <td><select name="type">
                        <option value="bug" selected="selected">程序bug或问题</option>
                        <option value="advice">新建议或改进</option>
                        <option value="other">其他</option>
                    </select></td>
            </tr>
            <tr>
                <td><strong>问题描述：（建议填写） </strong></td>
            </tr>
            <tr>
                <td><textarea name="msg"  style="width:98%" onkeyup="textareasize(this)" class="tarea"></textarea></td>
            </tr>
            <tr>
                <td><strong>您的邮箱：（建议填写）</strong></td>
            </tr>
            <tr>
                <td><input name="email" type="text" class="txt" style="width:98%"/></td>
            </tr>
            <tr>
                <td align="center"><input name=""  type="submit" class="submit" value="提交"/></td>
            </tr>
        </form>
    </table>
    <iframe width="100%" height="100" style="display:none" id="postfeedback" name="postfeedback"></iframe>
</div>
<?php $this->footer($a,$c); ?></body></html>