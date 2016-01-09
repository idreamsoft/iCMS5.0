<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;会员管理&nbsp;&raquo;&nbsp;编辑会员资料</div>
<form action="<?php echo __ADMINCP__; ?>=user" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <input type="hidden" name="uid" value="<?php echo $rs->uid ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="3">个人资料</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">用户名:</td>
      <td class="rowform"><input name="name" type="text" id="name" value="<?php echo $rs->username ; ?>" readonly="true" class="txt"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">新密码:</td>
      <td class="rowform"><input name="pwd1" type="password" id="pwd1" class="txt"/></td>
      <td class="tips2">不更改请留空</td>
    </tr>
    <tr>
      <td class="td80">确认密码:</td>
      <td class="rowform"><input name="pwd2" type="password" id="pwd2" class="txt"/></td>
      <td class="tips2">不更改请留空</td>
    </tr>
    <tr>
      <td class="td80">注册时间:</td>
      <td class="rowform"><input type="text" disabled class="txt" value="<?php echo get_date($rs->info['regtime'],"Y-m-d H:i:s") ; ?>" readonly="true"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">最后登陆IP:</td>
      <td class="rowform"><input type="text" disabled class="txt" value="<?php echo $rs->lastip ; ?>" readonly="true"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">最后登陆时间:</td>
      <td class="rowform"><input type="text" disabled class="txt" value="<?php echo get_date($rs->lastlogintime,"Y-m-d H:i:s") ; ?>" readonly="true"/></td>
      <td class="tips2"></td>
    </tr>
    <thead>
      <tr>
        <th colspan="3"> 以下资料选填 </th>
      </tr>
    </thead>
    <tr>
      <td class="td80">昵称:</td>
      <td class="rowform"><input name="nickname" type="text" id="nickname" value="<?php echo $rs->info['nickname'] ; ?>" maxlength="12" class="txt"/></td>
      <td class="tips2">发表文章时显示的名字,留空显示用户名</td>
    </tr>
    <tr>
      <td class="td80">QQ/MSN:</td>
      <td class="rowform"><input name="icq" type="text" id="icq" value="<?php echo $rs->info['icq'] ; ?>" maxlength="12" class="txt"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">E-mail:</td>
      <td class="rowform"><input name="email" type="text" id="email" value="<?php echo $rs->email ; ?>" class="txt"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">主页/博客:</td>
      <td class="rowform"><input name="home" type="text" id="home" value="<?php echo $rs->info['home'] ; ?>" class="txt"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">性别:</td>
      <td class="rowform"><select name="gender" id="gender">
          <option value="2">保密</option>
          <option value="1">男</option>
          <option value="0">女</option>
        </select></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">生日:</td>
      <td class="rowform"><select name="year" id="year" style="width:60px;">
          <option value=""></option>
          <option value="1970">1970</option>
          <option value="1971">1971</option>
          <option value="1972">1972</option>
          <option value="1973">1973</option>
          <option value="1974">1974</option>
          <option value="1975">1975</option>
          <option value="1976">1976</option>
          <option value="1977">1977</option>
          <option value="1978">1978</option>
          <option value="1979">1979</option>
          <option value="1980">1980</option>
          <option value="1981">1981</option>
          <option value="1982">1982</option>
          <option value="1983">1983</option>
          <option value="1984">1984</option>
          <option value="1985">1985</option>
          <option value="1986">1986</option>
          <option value="1987">1987</option>
          <option value="1988">1988</option>
          <option value="1989">1989</option>
          <option value="1990">1990</option>
          <option value="1991">1991</option>
          <option value="1992">1992</option>
          <option value="1993">1993</option>
          <option value="1994">1994</option>
          <option value="1995">1995</option>
          <option value="1996">1996</option>
          <option value="1997">1997</option>
          <option value="1998">1998</option>
          <option value="1999">1999</option>
          <option value="2000">2000</option>
          <option value="2001">2001</option>
          <option value="2002">2002</option>
        </select>
        年
        <select name="month" id="month"  style="width:60px;">
          <option value=""></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        </select>
        月
        <select name="day" id="day"  style="width:60px;">
          <option value=""></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        日</td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">来自:</td>
      <td class="rowform"><input name="from" type="text" id="from" value="<?php echo $rs->info['from'] ; ?>" class="txt"/></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">签名:</td>
      <td class="rowform"><textarea name="signature" id="signature" cols="45" rows="5" onkeyup="textareasize(this)" class="tarea"><?php echo $rs->info['signature'] ; ?></textarea></td>
      <td class="tips2">不更改请留空</td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
$("#gender").val("<?php echo $rs->gender ; ?>");
$("#year").val("<?php echo $rs->info['year'] ; ?>");
$("#month").val("<?php echo $rs->info['month'] ; ?>");
$("#day").val("<?php echo $rs->info['day'] ; ?>");
</script>
</body></html>