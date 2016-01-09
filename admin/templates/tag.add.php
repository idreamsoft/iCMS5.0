<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;TAG管理&nbsp;&raquo;&nbsp;编辑TAG</div>
<form action="<?php echo __ADMINCP__; ?>=tag" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <input type="hidden" name="id" value="<?php echo $id ; ?>"  />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="3">编辑标签</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">TAG:</td>
      <td class="rowform"><input name="name" id="name" value="<?php echo $rs->name ; ?>" type="text" class="txt"  /></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">归类:</td>
      <td class="rowform"><select name="sortid" style="width:auto;">
          <option value="0"> == 暂无归类 == </option>
          <?php echo $forum->select($rs->sortid,0,1,NULL,NULL,true) ; ?>
        </select></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">属性：</td>
      <td class="rowform"><select name="type" id="type">
            <option value="0">普通标签[type='0']</option>
            <?php echo contentype("tag",$rs->type) ; ?>
          </select></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    
    <tr>
      <td class="td80">关键字:</td>
      <td class="rowform"><input name="keywords" id="keywords" value="<?php echo $rs->keywords ; ?>" type="text" class="txt" style="width:560px"/></td>
      <td class="tips2">多个关键字请用;格开</td>
    </tr>
    <tr>
      <td class="td80">SEO标题:</td>
      <td class="rowform"><input name="seotitle" id="seotitle" value="<?php echo $rs->seotitle ; ?>" type="text" class="txt" style="width:560px"/></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">副标题:</td>
      <td class="rowform"><input name="subtitle" id="subtitle" value="<?php echo $rs->subtitle ; ?>" type="text" class="txt" style="width:560px"/></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">介绍:</td>
      <td class="rowform"><textarea name="description" id="description" onKeyUp="textareasize(this)" class="tarea" style="width:560px; height:120px;"><?php echo $rs->description ; ?></textarea></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">自定链接:</td>
      <td class="rowform"><input name="link" id="link" value="<?php echo $rs->link ; ?>" type="text" class="txt" style="width:560px"/></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">使用数:</td>
      <td class="rowform"><input name="count" id="count" value="<?php echo $rs->count ; ?>" type="text" class="txt"  /></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">模板:</td>
      <td class="rowform"><input name="tpl" id="tpl" value="<?php echo $rs->tpl ; ?>" type="text" class="txt" style="width:360px"/></td>
      <td class="tips2"><input type="button" id="selecttpl" class="submit" value="浏览" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=template&click=file&type=htm','tpl','选择模板');" hidefocus=true /></td>
    </tr>
    <tr>
      <td class="td80">权重:</td>
      <td class="rowform"><input name="weight" id="weight" value="<?php echo _int($rs->weight) ; ?>" type="text" class="txt"  /></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">排序:</td>
      <td class="rowform"><input name="ordernum" id="ordernum" value="<?php echo _int($rs->ordernum) ; ?>" type="text" class="txt"  /></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80">状态:</td>
      <td class="rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($rs->status=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="status" value="1" <?php if($rs->status=='1') echo 'checked'  ?>>
            启用</li>
          <li<?php if($rs->status=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="status" value="0" <?php if($rs->status=='0') echo 'checked'  ?>>
            禁用</li>
        </ul></td>
      <td class="tips2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
</body></html>