<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

/**
 * 后台UI
 *
 * @author coolmoo
 */
class UI {
    function lang($name, $force = true) {
        global $APClang,$iCMS;
        $plugins_lang	= $iCMS->getCache('system/plugins.lang');
        if($plugins_lang)foreach($plugins_lang AS $key=>$val){
				$APClang[$key]=$val;
		}
        $models_lang	= $iCMS->getCache('system/models.lang');
        if($models_lang)foreach($models_lang AS $key=>$val){
				$APClang[$key]=$val;
		}
        return isset($APClang[$name]) ? $APClang[$name] : ($force ? $name : '');
    }
    function redirect($msg, $url="", $t='3',$more="") {
        include iPATH.'admin/templates/redirect.php';
    }
}

/**
 * 后台javascript
 *
 * @author coolmoo
 */
class javascript {
    //警告
    public function alert($str, $url="js:") {
        $A=explode(':',$url);
        $script='<script type="text/JavaScript">alert("'.$str.'");';
        if($A[0]=='js') {
            $A[1]=="1" && $A[1]='history.go(-1);';
            $script.=$A[1]?$A[1]:'';
        }elseif($A[0]=='url') {
            $A[1]=="1" && $A[1]=__REF__;
            $script.=empty($A[1])?"window.close();":"window.location.href='{$A[1]}';";
        }
        echo $script.'</script>';
        exit;
    }
    //窗口
    public function dialog($arg="",$js='js:',$callback="ok",$s=2) {
        $arg=(array)$arg;
        empty($arg[1]) && $arg[1]='提示信息';
        $script='<script type="text/JavaScript">';
        $A=explode(':',$js);
        if($A[0]=='js') {
            $A[1] && $fn=$A[1];
        }elseif($A[0]=='url') {
            $A[1]=="1" && $A[1]=__REF__;
            $fn=empty($A[1])?"parent.location.reload();":"parent.location.href='".$A[1]."';";
        }elseif($A[0]=='src') {//src:url
           $A[1]=="1" && $A[1]=__REF__;
           $fn="parent.$('#iCMS_FRAME').attr('src','".$A[1]."');";
        }
        if(is_array($callback)) {
            foreach($callback as $key=>$val) {
                $func=empty($val['url'])?'parent.iCMS.closeDialog();':"parent.location.href='{$val['url']}';";
                if($val['o']) {
                    $func="top.window.open('{$val['url']}','_blank');";
                }
                $b[]='"'.$val['text'].'": function(){'.$func.'}';//$(this).dialog("close");

            }
            empty ($fn) && $fn='parent.location.reload();';
            $buttons=implode(',',(array)$b);
            $script.='window.buttons={'.$buttons.'};';
            $script.='window.fn=function(){'.$fn.'};';
            $script.='parent.iCMS.CDB("'.$arg[0].'","iCMS - '.$arg[1].'",window);';
        }else {
            empty ($fn) && $fn='parent.iCMS.closeDialog();';
            $script.='window.fn=function(){'.$fn.'};parent.iCMS.'.$callback.'("'.$arg[0].'","iCMS - '.$arg[1].'",window);';
        }
        $s!='-1' && $script.='setTimeout(window.fn,'.$s.'*1000);';
        echo $script.'</script>';
        exit;
    }
    function json($state,$lang,$frame=false,$break=true) {
        global $iCMS;
        $msg=$iCMS->language($lang);
        if($frame) {
            echo '<script type="text/javascript">document.domain="'.$iCMS->config['domain'].'";alert("'.$msg.'");';
            if($state=="1")echo ' window.parent.location.reload();';
            echo '</script>';
        }else {
            echo "{state:'$state',msg:'$msg'}";
        }
        $break && exit();
    }
}

