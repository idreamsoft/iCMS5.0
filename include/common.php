<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
if(!defined('iCMS')) {
    exit('Access Denied');
}
function stripslashes_deep($val) {
    return is_array($val)?array_map('stripslashes_deep', $val):stripslashes($val);
}
function irawurldecode($val) {
    $val = str_replace('%25','%',$val);
    return addslashes(rawurldecode($val));
}
function add_magic_quotes($array) {
	if(empty($array)) return NULL;
    foreach ( (array) $array as $k => $v ) {
        if ( is_array( $v ) ) {
            $array[$k] = add_magic_quotes( $v );
        } else {
//			$array[$k] = $db->escape( $v );
            $array[$k] = addslashes( $v );
        }
    }
    return $array;
}

// 格式化时间
function get_date($timestamp,$format='') {
    global $iCMS;
    empty($format) 		&& $format		= $iCMS->config['dateformat'];
    empty($timestamp) 	&& $timestamp 	= time();
    $timeoffset = $iCMS->config['ServerTimeZone'] == '111' ? 0 : $iCMS->config['ServerTimeZone'];
    $cvtime		= $iCMS->config['cvtime']?$iCMS->config['cvtime']*60:0;
    return gmdate($format,$timestamp+$timeoffset*3600+$cvtime);
}
// 获取客户端IP
function getip($format=0) {
    global $_iGLOBAL;
    if(empty($_iGLOBAL['ip'])) {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
        $_iGLOBAL['ip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
    }
    if($format) {
        $ips = explode('.', $_iGLOBAL['ip']);
        for($i=0;$i<3;$i++) {
            $ips[$i] = intval($ips[$i]);
        }
        return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
    } else {
        return $_iGLOBAL['ip'];
    }
}
//--------------------------TPL.FUNCTION-------------------------------------------------------------------
function getfids($fid = "0",$all=true) {
    global $iCMS;
    $fids=array();
    $cArray=$iCMS->getCache('system/forum.rootid',$fid);
    if($cArray)foreach($cArray AS $id) {
        $fids[]=$id;
        if($all) {
            $_fids	= getfids($id,$all);
            $_fids && $fids[]=$_fids;
        }
    }
    $fids=array_unique($fids);
    unset($cArray);
    return implode(',',$fids);
}
function getSQL($vars,$field,$not='') {
    $sql = "";
    if(strstr($vars,',')) {
        $ids=implode(',',array_map('intval',explode(',',$vars)));
        $sql.=$not=='not'?" AND $field NOT IN ($ids)":" AND $field IN ($ids) ";
    }else {
        $vars=intval($vars);
        $sql.=$not=='not'?" AND $field<>'$vars'  ":" AND $field='$vars' ";
    }
    return $sql;
}
function gethumb($sfp,$w='',$h='',$tdir=false,$scale=true,$callback=false) {
    global $iCMS;
    if(strpos($sfp,'thumb/')!==false)
        return $sfp;
	$info	= pathinfo($sfp);
    $tpf	= $info['dirname'].'/thumb/'.$info['filename'].'_';
    if($callback) {
        $rootpf	= uploadpath($tpf,'+iPATH');
        $tfArray= glob($rootpf."*");
        if($tfArray)foreach ($tfArray as $_tfp) {
                if(file_exists($_tfp)) {
                    $fn	= substr($_tfp,0,strrpos($_tfp,'.'));
                    $per= substr($fn,strrpos($fn,'_')+1);
                    $tfpList[$per]=uploadpath($_tfp,'-iPATH');
                }
            }
        return $tfpList;
    }else {
        $srfp	= uploadpath($sfp,'http2iPATH');
        $tdir && $tpf=$tdir.'/'.$info['filename'].'_';
        $rootpf	= uploadpath($tpf,'http2iPATH');
        if(file_exists($srfp)) {
            empty($w) && $w=$iCMS->config['thumbwidth'];
            empty($h) && $h=$iCMS->config['thumbhight'];
            $twh=$rootpf.$w.'x'.$h.'.'.FS::getext($sfp);
            if(file_exists($twh)) {
                return uploadpath($twh,'iPATH2http');
            }else {
                if($iCMS->config['issmall']) {
                    require_once(iPATH.'include/upload.class.php');
                    $Thumb=iUpload::thumbnail(dirname($tdir?$rootpf:$srfp).'/', $srfp,$info['filename'],$w,$h,$scale,($tdir?'':'thumb'));
                    return uploadpath($Thumb['src'],'iPATH2http');
                }else {
                    return $sfp;
                }
            }
        }else {
            return $iCMS->config['uploadURL'].'/nopic.gif';
        }
    }
}

function gethttpurl($url) {
    global $iCMS;
    return strstr($url,'http://')===false?$iCMS->config['url'].'/'.$url:$url;
}
//---------------------------------------------------------------------------------------------
//中文长度
Function cstrlen($str) {
    if (function_exists('mb_substr')) {
        return mb_strlen($str,iCMS_CHARSET);
    } elseif (function_exists('iconv_substr')) {
        return iconv_strlen($str,iCMS_CHARSET);
    } else {
        preg_match_all(CN_PATTERN, $str, $match);
        return count($match[0]);
    }
}
/*
 * @todo 中文截取，支持gb2312,gbk,utf-8,big5 
 * @param string $str 要截取的字串
 * @param int $length 截取长度
 * @param $suffix 是否加尾缀
 * @param int $start 截取起始位置
 * @param string $charset utf-8|gb2312|gbk|big5 编码
*/
function csubstr($str,$length,$suffix=FALSE,$start=0) {
    if (function_exists('mb_substr')) {
        $more = (mb_strlen($str) > $length) ? TRUE : FALSE;
        $text = mb_substr($str, $start, $length, iCMS_CHARSET);
    } elseif (function_exists('iconv_substr')) {
        $more = (iconv_strlen($str) > $length) ? TRUE : FALSE;
        $text = iconv_substr($str, $start, $length, iCMS_CHARSET);
    } else {
        preg_match_all(CN_PATTERN, $str, $match);
        $text = join("",array_slice($match[0], $start, $length));
        $more = (count($match[0])>$length)?TRUE:FALSE;
    }
    if($suffix && $more) $text.=" ...";
    return $text;
}
function format_string($str,$len,$end=''){
	$len=$len*2;
    //获取总的字节数  
    $ll = strlen($str);  
    //字节数  
    $i = 0;  
    //显示字节数  
    $l = 0;       
    //返回的字符串  
    $s = $str;  
    while ($i < $ll)  {
        //获取字符的asscii  
        $byte = ord($str{$i});  
        //如果是1字节的字符  
        if ($byte < 0x80)  {
            $l++;  
            $i++;  
        }elseif ($byte < 0xe0){  //如果是2字节字符
            $l += 2;  
            $i += 2;  
        }elseif ($byte < 0xf0){   //如果是3字节字符
            $l += 2;  
            $i += 3;  
        }else{  //其他，基本用不到
            $l += 2;  
            $i += 4;  
        }
        //如果显示字节达到所需长度  
        if ($l >= $len){
            //截取字符串
            $s = substr($str, 0, $i);  
            //如果所需字符串字节数，小于原字符串字节数  
            if($i < $ll){
                //则加上省略符号  
                $s = $s . $end; break;  
            }
            //跳出字符串截取 
            break;  
        }  
    }
    //返回所需字符串 
    return $s;
}
/**
 * 在数组的value里面搜索
 *
 * @param string $p1
 * @param array  $p2
 * @return bool
 */
function array_search_value($p1,$p2) {
    while (list($k,$v)=each($p2)) {
        if (strpos($v,$p1)!==false) {
            return $k;
        }
    }
    return false;
}
/**
 * 验证函数
 *
 * @param string $p1    需要验证的字符串
 * @param int    $p2    验证类型
 * @return bool
 */
function validate($p1,$p2) {
    switch((string)$p2) {
        case '0' : // 数字，字母，逗号，杠，下划线，[，]
            $p3 = '^[\w\,\/\-\[\]]+$';
            break;
        case '1' : // 字母
            $p3 = '^[A-Za-z]+$';
            break;
        case '2' : // 匹配数字
            $p3 = '^\d+$';
            break;
        case '3' : // 字母，数字，下划线，杠
            $p3 = '^[\w\-]+$';
            break;
        case '4' : // Email
            $p3 = '^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$';
            break;
        case '5' : // url
            $p3 = '^(http|https|ftp):(\/\/|\\\\)(([\w\/\\\+\-~`@:%])+\.)+([\w\/\\\.\=\?\+\-~`@\':!%#]|(&amp;)|&)+';
            break;
        case '6' : //
            $p3 = '^[\d\,\.]+$';
            break;
        default  : // 自定义正则
            $p3 = $p2;
            break;
    }
    return preg_match("/{$p3}/i",$p1);
}
//截取HTML
function htmlSubString($content,$maxlen=300,$suffix=FALSE) {
    $content = preg_split("/(<[^>]+?>)/si",$content, -1,PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE);
    $wordrows=0;
    $outstr="";
    $wordend=false;
    $beginTags=0;
    $endTags=0;
    foreach($content as $value) {
        if (trim($value)=="") continue;

        if (strpos(";$value","<")>0) {
            if (!preg_match("/(<[^>]+?>)/si",$value) &&cstrlen($value)<=$maxlen) {
                $wordend=true;
                $outstr.=$value;
            }
            if ($wordend==false) {
                $outstr.=$value;
                if (!preg_match("/<img([^>]+?)>/is",$value)&& !preg_match("/<param([^>]+?)>/is",$value)&& !preg_match("/<!([^>]+?)>/is",$value)&& !preg_match("/<[br|BR]([^>]+?)>/is",$value)&& !preg_match("/<hr([^>]+?)>/is",$value)&&!preg_match("/<\/([^>]+?)>/is",$value)) {
                    $beginTags++;
                }else {
                    if (preg_match("/<\/([^>]+?)>/is",$value,$matches)) {
                        $endTags++;
                    }
                }
            }else {
                if (preg_match("/<\/([^>]+?)>/is",$value,$matches)) {
                    $endTags++;
                    $outstr.=$value;
                    if ($beginTags==$endTags && $wordend==true) break;
                }else {
                    if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<[br|BR]([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)&& !preg_match("/<\/([^>]+?)>/is",$value)) {
                        $beginTags++;
                        $outstr.=$value;
                    }
                }
            }
        }else {
            if (is_numeric($maxlen)) {
                $curLength=cstrlen($value);
                $maxLength=$curLength+$wordrows;
                if ($wordend==false) {
                    if ($maxLength>$maxlen) {
                        $outstr.=csubstr($value,$maxlen-$wordrows,FALSE,0);
                        $wordend=true;
                    }else {
                        $wordrows=$maxLength;
                        $outstr.=$value;
                    }
                }
            }else {
                if ($wordend==false) $outstr.=$value;
            }
        }
    }
    while(preg_match("/<([^\/][^>]*?)><\/([^>]+?)>/is",$outstr)) {
        $outstr=preg_replace_callback("/<([^\/][^>]*?)><\/([^>]+?)>/is","strip_empty_html",$outstr);
    }
    if (strpos(";".$outstr,"[html_")>0) {
        $outstr=str_replace("[html_&lt;]","<",$outstr);
        $outstr=str_replace("[html_&gt;]",">",$outstr);
    }
    if($suffix&&cstrlen($outstr)>=$maxlen)$outstr.="．．．";
    return $outstr;
}
//去掉多余的空标签
function strip_empty_html($matches) {
    $arr_tags1=explode(" ",$matches[1]);
    if ($arr_tags1[0]==$matches[2]) {
        return "";
    }else {
        $matches[0]=str_replace("<","[html_&lt;]",$matches[0]);
        $matches[0]=str_replace(">","[html_&gt;]",$matches[0]);
        return $matches[0];
    }
}

function sechtml($string) {
    $search = array("/\s+/","/<(\/?)(script|iframe|style|object|html|body|title|link|meta|\?|\%)([^>]*?)>/isU","/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU");
    $replace = array(" ","&lt;\\1\\2\\3&gt;","\\1\\2",);
    $string = preg_replace ($search, $replace, $string);
    return $string;
}
//HTML TO TEXT
function HtmToText($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = HtmToText($val);
        }
    } else {
        $search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
        $replace = array ("", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)");
        $string = preg_replace ($search, $replace, $string);
    }
    return $string;
}
function HTML2JS($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = HTML2JS($val);
        }
    } else {
        $string = str_replace(array("\n","\r","\\","\""), array(' ',' ',"\\\\","\\\""), $string);
    }
    return $string;
}
function dhtmlspecialchars($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = dhtmlspecialchars($val);
        }
    } else {
    	$string = str_replace(array("\0","%00"),'',$string);
        $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
                str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
    }
    return $string;
}
function unhtmlspecialchars($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = unhtmlspecialchars($val);
        }
    } else {
        $string = str_replace (array('&amp;','&#039;','&quot;','&lt;','&gt;'), array('&','\'','\"','<','>'), $string );
    }
    return $string;
}
//-----------------------------------------------------------------------
//设置COOKIE
function set_cookie($name, $value = "", $cookiedate = 0) {
    global $_COOKIE,$_SERVER,$_iGLOBAL;
    $cookiedomain	= $_iGLOBAL['cookie']['domain'] == "" ? ""  : $_iGLOBAL['cookie']['domain'];
    $cookiepath		= $_iGLOBAL['cookie']['path']   == "" ? "/" : $_iGLOBAL['cookie']['path'];
    $cookiedate		= $cookiedate==0 ? $_iGLOBAL['cookie']['time']:$cookiedate;
    $name 			= $_iGLOBAL['cookie']['prename'].$name;
    $_COOKIE[$name] = $value;
    setcookie($name, $value, $cookiedate ? $_iGLOBAL['timestamp'] + $cookiedate : 0, $cookiepath, $cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}
//取得COOKIE
function get_cookie($name) {
    global $_COOKIE,$_iGLOBAL;
    if (isset($_COOKIE[$_iGLOBAL['cookie']['prename'].$name])) {
        return $_COOKIE[$_iGLOBAL['cookie']['prename'].$name];
    }
    return FALSE;
}

//关键字过滤器
Function WordFilter(&$content) {
    global $iCMS;
    $cache	= $iCMS->getCache(array('word.filter','word.disable'));
    $filter	= $cache['word.filter'];//filter过滤
    $disable    = $cache['word.disable'];//disable禁止
    //禁止关键词
    foreach ((array)$disable AS $val) {
        if ($val && preg_match("/".preg_quote($val, '/')."/i", $content)) {
            return $val;
        }
    }
    //过滤关键词
    foreach ((array)$filter AS $k =>$val) {
        empty($val[1]) && $val[1]='***';
        $val[0] && $content = preg_replace("/".preg_quote($val[0], '/')."/i",$val[1],$content);
    }
}

//新版
function _header($URL='') {
    empty($URL)&&$URL=__REF__;
    if(!headers_sent()) {
        header("Location: $URL");
        exit;
    }else {
        echo '<meta http-equiv=\'refresh\' content=\'0;url='.$URL.'\'><script type="text/JavaScript">window.location.replace(\''.$URL.'\');</script>';
        exit;
    }
}
function random($length, $numeric = 0) {
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if($numeric) {
        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}
//检查验证码
//function ckseccode($seccode,$alert="") {
function ckseccode($seccode) {
    $_seccode=get_cookie('seccode');
    $cookie_seccode = empty($_seccode)?'':authcode($_seccode, 'DECODE');
    set_cookie("seccode", '',-31536000);
    if(empty($cookie_seccode) || strtolower($cookie_seccode) != strtolower($seccode)) {
        //runlog('seccode','cookie='.strtolower($cookie_seccode).'&post='.strtolower($seccode).'&alert='.$alert);
        //$alert ? alert($alert);
        return true;
    }else {
        return false;
    }
}
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

    $ckey_length = 4;

    $key = md5($key ? $key : iCMSKEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }

}
function strexists($haystack, $needle) {
    return !(strpos($haystack, $needle) === FALSE);
}
//写运行日志
function runlog($file, $log, $halt=0) {
    global $_iGLOBAL,$Admin;
    $log = get_date('','Y-m-d H:i:s')."\t$type\t".getip()."\t".$Admin->uId."\t".__SELF__."\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($log))."\n";
    $yearmonth = get_date($_iGLOBAL['timestamp'],'Ym');
    $logdir = iPATH.'admin/logs/';
    if(!is_dir($logdir)) mkdir($logdir, 0777);
    $logfile = $logdir.$yearmonth.'_'.$file.'.php';
    if(@filesize($logfile) > 2048000) {
        $dir = opendir($logdir);
        $length = strlen($file);
        $maxid = $id = 0;
        while($entry = readdir($dir)) {
            if(strexists($entry, $yearmonth.'_'.$file)) {
                $id = intval(substr($entry, $length + 8, -4));
                $id > $maxid && $maxid = $id;
            }
        }
        closedir($dir);
        $logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
        @rename($logfile, $logfilebak);
    }
    if($fp = @fopen($logfile, 'a')) {
        @flock($fp, 2);
        fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>', "\r", "\n"), '', $log)."\n");
        fclose($fp);
    }
    if($halt) exit();
}
//-------------------------------------------------------
//SystemField
function getSystemField() {
    return array('fid','order','title','clink','editor','userid','tags','pubdate','hits','digg','comments','type','vlink','top','status','good','bad');
}
function getFieldValue($mid,$field,$v/*,&$rs,$page=0*/) {
    global $iCMS;
    $__FIELD__	= $iCMS->cache('field.model','include/syscache',0,true);
    $F			= $__FIELD__[$field][$mid]?$__FIELD__[$field][$mid]:$__FIELD__[$field][0];
    $rules		= $F['rules'];
    $__RETURN__	= Null;
    switch($Finfo['type']) {
        case "radio":
            $rules && $__RETURN__	= $rules[$v];
            break;
//		case "editor":
//			$body	=explode('<div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>',$v);
//			$total	=count($body);
//			$nBody	=$body[intval($page-1)];
//			$v		=$iCMS->keywords($nBody);
//			$rs->page=$page;
//			if($total>1){
//				$CLArray=array('id'=>$rs->id,'link'=>$rs->customlink,'dir'=>$rs->forumdir,'pubdate'=>$rs->pubdate);
//				$pagebreak=($page-1>1)?'<a href="'.$this->iurl('show',$CLArray,$page-1).'" class="pagebreak" target="_self">上一页</a> ':'<a href="'.$this->iurl('show',$CLArray).'" class="pagebreak" target="_self">'.$this->language('page:prev').'</a> ';
//				for($i=1;$i<=$total;$i++){
//					$cls=$i==$page?"pagebreaksel":"pagebreak";
//					$pagebreak.=$i==1?'<a href="'.$this->iurl('show',$CLArray).'" class="'.$cls.'" target="_self">'.$i.'</a>':'<a href="'.$this->iurl('show',$CLArray,$i).'" class="'.$cls.'" target="_self">'.$i.'</a>';
//				}
//				$np=($total-$page>0)?$page+1:$page;
//				$pagebreak.='<a href="'.$this->iurl('show',$CLArray,$np).'" class="pagebreak" target="_self">'.$this->language('page:next').'</a>';
//				$rs->pagebreak=$pagebreak;
//			}
//		break;
        case in_array($Finfo['type'],array('checkbox','select','multiple')):
            $vArray=explode(',',$v);
            if($rules)foreach($rules AS $value=>$text) {
                    $vArray=explode(',',$v);
                    in_array($value,$vArray) && $__RETURN__[$value]	= $text;
                }
            break;
    }
    return $__RETURN__;
}
function uploadUrl() {
    global $iCMS;
    if(empty($iCMS->config['uploadURL'])) {
        $url=$iCMS->config['url'].'/admin';
    }else {
        $url=$iCMS->config['uploadURL'];
    }
    $url.='/'.$iCMS->config['uploadScript'].'?ikey='.$iCMS->config['remoteKey'];
    return $url;
}
function uploadpath($f,$m='+http') {
    global $iCMS;
    switch($m) {
        case '+http':
            $fp = $iCMS->config['uploadURL'].'/'.$f;
            break;
        case '-http':
            $fp = str_replace($iCMS->config['uploadURL'].'/','',$f);
            break;
        case 'http2iPATH':
            $f	= str_replace($iCMS->config['uploadURL'].'/','',$f);
            $fp = FS::path_join(iPATH,$iCMS->config['uploadfiledir']).'/'.$f;
            break;
        case 'iPATH2http':
            $f = str_replace(FS::path_join(iPATH,$iCMS->config['uploadfiledir']).'/','',$f);
            $fp = $iCMS->config['uploadURL'].'/'.$f;
            break;
        case '+iPATH':
            $fp = FS::path_join(iPATH,$iCMS->config['uploadfiledir']).'/'.$f;
            break;
        case '-iPATH':
            $fp = str_replace(FS::path_join(iPATH,$iCMS->config['uploadfiledir']).'/','',$f);
            break;
    }
    return $fp;
}
?>