<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class Admin {
    public static $uId;
    public static $Rs;
    public static $group;
    public static $cpower;
    public static $power;
    function __construct() {

    }
    //检验用户
    function checkadmin($a,$p,$Ret=false) {
        //验证用户 账号/密码
        self::$Rs = iCMS_DB::getRow("SELECT * FROM `#iCMS@__admin` WHERE `username`='{$a}' AND `password`='{$p}'");
        if(empty(self::$Rs)) {
            //记录
            $a && runlog('login', 'username='.$a.'&password='.$_POST['password']);
            return $Ret ? 'Bad' :self::LoginPage();
        }else {
            self::$uId=self::$Rs->uid;
            self::$Rs->info && self::$Rs->info=unserialize(self::$Rs->info);
            self::$group = iCMS_DB::getRow("SELECT * FROM `#iCMS@__group` WHERE `gid`='".self::$Rs->groupid."'");//用户组
            self::$power = explode(',',self::merge(self::$group->power,self::$Rs->power));
            $cpower      = self::merge(self::$group->cpower,self::$Rs->cpower);
            self::$cpower= empty($cpower)?array(0):explode(',',$cpower);
            self::$Rs->groupid=="1" && self::$cpower=NULL;
        }
    }
    //检查栏目权限
    function CP($p=NULL,$T="F",$url=__REF__) {
        if(self::$Rs->groupid=="1")
            return TRUE;

        if(is_array($p)?array_intersect($p,self::$cpower):in_array($p,self::$cpower)) {
            return TRUE;
        }else {
            if($T=='F') {
                return FALSE;
            }else {
                echo UI::lang($T);
                exit();
            }
        };
    }
    //检查后台权限
    function MP($p=NULL,$T="Permission_Denied",$url=__REF__) {
        if(self::$Rs->groupid=="1")
            return TRUE;

        if(is_array($p)?array_intersect($p,self::$power):in_array($p,self::$power)) {
            return TRUE;
        }else {
            if($T=='F') {
                return FALSE;
            }else {
                echo UI::lang($T);
                exit();
            }
        }
    }
    //登陆验证
    function checklogin($a,$p,$Ret=false) {
        $ip = getip();
        if(empty($a) && empty($p)) {
            $auth	= get_cookie('auth');
            list($a,$p)	= explode('#=iCMS['.$ip.']=#',authcode($auth,'DECODE'));
            return self::checkadmin($a,$p,$Ret);
        }else {
            $crs=self::checkadmin($a,$p,$Ret);
            set_cookie('auth',authcode($a.'#=iCMS['.$ip.']=#'.$p,'ENCODE'));
            iCMS_DB::query("UPDATE `#iCMS@__admin` SET `lastip`='".$ip."',`lastlogintime`='".time()."',`logintimes`=logintimes+1 WHERE `uid`='".self::$uId."'");
            !$Ret && javascript::dialog("登陆成功！",'url:'.__SELF__);//UI::redirect('登陆成功, 请稍候......', __SELF__);
            return $crs;
        }
    }
    //登陆页
    function LoginPage() {
        include AdminCP::tpl('login');
    }
    //注销
    function logout($url) {
        self::cleancookie();
        //javascript::dialog("注销成功, 请稍后......",'url:'.$url);
    }
    function cleancookie() {
        set_cookie("auth", '',-31536000);
        set_cookie("seccode",'',-31536000);
    }
    function merge($G,$A) {
        $G && $tmp[]=$G;
        $A && $tmp[]=$A;
        return implode(',',(array)$tmp);
    }
}
