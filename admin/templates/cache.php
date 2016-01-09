<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;更新缓存</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>清除模板缓存：影响清理后第一次访问速度，建议在未对模板进行修改时请勿进行清除模板缓存操作</li>
      </ul></td>
  </tr>
</table>
<form action="<?php echo __ADMINCP__; ?>=cache" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="update" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>更新缓存</th>
      </tr>
    </thead>
    <tr>
      <td><input name="config" type="checkbox" class="checkbox" id="config" value="1" />
        更新系统配置</td>
    </tr>
    <tr>
      <td><input name="forum" type="checkbox" class="checkbox" id="forum" value="1" />
        更新版块缓存</td>
    </tr>
    <!--<tr>
      <td><input name="model" type="checkbox" class="checkbox" id="model" value="1" />
        更新模型缓存</td>
    </tr>
    <tr>
      <td><input name="field" type="checkbox" class="checkbox" id="field" value="1" />
        更新字段缓存</td>
    </tr>-->
    <tr>
      <td><input name="keywords" type="checkbox" class="checkbox" id="keywords" value="1" />
        更新关键字缓存</td>
    </tr>
    <tr>
      <td><input name="tags" type="checkbox" class="checkbox" id="tags" value="1" />
        更新标签缓存</td>
    </tr>
    <tr>
      <td><input name="adm" type="checkbox" class="checkbox" id="adm" value="1" />
        更新广告缓存</td>
    </tr>
    <tr>
      <td><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>
    <thead>
      <tr>
        <th>更新统计</th>
      </tr>
    </thead>
    <tr>
      <td><input name="Re-Article-Count" type="checkbox" class="checkbox" id="Re-Article-Count" value="1" />
        栏目文章数重新统计</td>
    </tr>
    <tr>
      <td><input name="Re-Tag-Count" type="checkbox" class="checkbox" id="Re-Tag-Count" value="1" />
        TAG使用数重新统计</td>
    </tr>
        <tr>
      <td><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>

    <thead>
      <tr>
        <th>清除缓存</th>
      </tr>
    </thead>
    <tr>
      <td><input name="tpl" type="checkbox" class="checkbox" id="tpl" value="1" />
        清除模板缓存</td>
    </tr>
    <tr>
      <td><input name="iCMS_list" type="checkbox" class="checkbox" id="iCMS_list" value="1" />
        清除&lt;!--{iCMS:list}--&gt;数据缓存</td>
    </tr>
    <tr>
      <td><input name="iCMS_forum" type="checkbox" class="checkbox" id="iCMS_forum" value="1" />
        清除&lt;!--{iCMS:forum}--&gt;s数据缓存</td>
    </tr>
    <tr>
      <td><input name="iCMS_tag" type="checkbox" class="checkbox" id="iCMS_tag" value="1" />
        清除&lt;!--{iCMS:tag}--&gt;数据缓存</td>
    </tr>
    <tr>
      <td><input name="iCMS_ALL" type="checkbox" class="checkbox" id="iCMS_ALL" value="1" />
        (!)清除所有前台数据缓存</td>
    </tr>
    <tr>
      <td><input type="submit" class="submit" name="cleanupsubmit" value="提交" /></td>
    </tr>
  </table>
</form>
</body></html>