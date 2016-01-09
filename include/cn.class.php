<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
/**
 * 汉字处理操作类
 *
 * @author coolmoo
 */
class CN {
    function jstr($string,$lang="UTF-8") {
        return str_replace(array('&#x', ';'),array('\u', ''),self::unicode($string,$lang));
    }
    function unicode($string,$lang="GB2312") {
        $utf="";
        $tmp = file(iPATH.'include/gb-unicode.table');
        $unicode_table = array();
        while(list($key,$value)=each($tmp)) {
            $unicode_table[hexdec(substr($value,0,6))]=substr($value,9,4);
        }
        while($string) {
            if(function_exists('iconv')) {
                if(ord(substr($string, 0, 1)) > 127) {
                    $utf .= "&#x".dechex(self::UTF8toUni(iconv($lang,"UTF-8", substr($string, 0, 2)))).";";
                    $string = substr($string, 2, strlen($string));
                } else {
                    $utf .= substr($string, 0, 1);
                    $string = substr($string, 1, strlen($string));
                }
            }elseif(ord(substr($string,0,1))>127) {
                if($lang=="GB2312")
                    $utf.="&#x".$unicode_table[hexdec(bin2hex(substr($string,0,2)))-0x8080].";";

                if($lang=="BIG5")
                    $utf.="&#x".$unicode_table[hexdec(bin2hex(substr($string,0,2)))].";";

                $string=substr($string,2,strlen($string));
            }else {
                $utf.=substr($string,0,1);
                $string=substr($string,1,strlen($string));
            }
        }
        return str_replace(array('&#x;', '&#x0;'),array('??', ''),$utf);
    }
    function UTF8toUni($c) {
        switch(strlen($c)) {
            case 1:
                return ord($c);
            case 2:
                $n = (ord($c[0]) & 0x3f) << 6;
                $n += ord($c[1]) & 0x3f;
                return $n;
            case 3:
                $n = (ord($c[0]) & 0x1f) << 12;
                $n += (ord($c[1]) & 0x3f) << 6;
                $n += ord($c[2]) & 0x3f;
                return $n;
            case 4:
                $n = (ord($c[0]) & 0x0f) << 18;
                $n += (ord($c[1]) & 0x3f) << 12;
                $n += (ord($c[2]) & 0x3f) << 6;
                $n += ord($c[3]) & 0x3f;
                return $n;
        }
    }
    function g2u($instr) {
        if(is_array($instr)) {
            return array_map(array("CN","g2u"),$instr);
        }else {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($instr,'UTF-8','GBK');
            } elseif (function_exists('iconv')) {
                return iconv('GBK','UTF-8',$instr);
            } else {
                $fp = fopen(iPATH.'include/gb-unicode.table', 'r');
                $len = strlen($instr);
                $outstr = '';
                for( $i = $x = 0 ; $i < $len ; $i++ ) {
                    $h = ord($instr[$i]);
                    if( $h > 160 ) {
                        $l = ( $i+1 >= $len ) ? 32 : ord($instr[$i+1]);
                        fseek( $fp, ($h-161)*188+($l-161)*2 );
                        $uni = fread( $fp, 2 );
                        $codenum = ord($uni[0])*256 + ord($uni[1]);
                        if( $codenum < 0x800 ) {
                            $outstr[$x++] = chr( 192 + $codenum / 64 );
                            $outstr[$x++] = chr( 128 + $codenum % 64 );
                            #				printf("[%02X%02X]<br>\n", ord($outstr[$x-2]), ord($uni[$x-1]) );
                        }
                        else {
                            $outstr[$x++] = chr( 224 + $codenum / 4096 );
                            $codenum %= 4096;
                            $outstr[$x++] = chr( 128 + $codenum / 64 );
                            $outstr[$x++] = chr( 128 + ($codenum % 64) );
                            #				printf("[%02X%02X%02X]<br>\n", ord($outstr[$x-3]), ord($outstr[$x-2]), ord($outstr[$x-1]) );
                        }
                        $i++;
                    }
                    else
                        $outstr[$x++] = $instr[$i];
                }
                fclose($fp);
                if( $instr != '' )
                    return join( '', $outstr);
            }
        }
    }
    function u2g( $instr ) {
        if(is_array($instr)) {
            return array_map(array("CN","u2g"),$instr);
        }else {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($instr,'GBK','UTF-8');
            } elseif (function_exists('iconv')) {
                return iconv('UTF-8','GBK',$instr);
            } else {
                $fp = fopen(iPATH.'include/unicode-gb.table', 'r' );
                $len = strlen($instr);
                $outstr = '';
                for( $i = $x = 0 ; $i < $len ; $i++ ) {
                    $b1 = ord($instr[$i]);
                    if( $b1 < 0x80 ) {
                        $outstr[$x++] = chr($b1);
                        #			printf( "[%02X]", $b1);
                    }
                    elseif( $b1 >= 224 ) {	# 3 bytes UTF-8
                        $b1 -= 224;
                        $b2 = ($i+1 >= $len) ? 0 : ord($instr[$i+1]) - 128;
                        $b3 = ($i+2 >= $len) ? 0 : ord($instr[$i+2]) - 128;
                        $i += 2;
                        $uc = $b1 * 4096 + $b2 * 64 + $b3 ;
                        fseek( $fp, $uc * 2 );
                        $gb = fread( $fp, 2 );
                        $outstr[$x++] = $gb[0];
                        $outstr[$x++] = $gb[1];
                        #			printf( "[%02X%02X]", ord($gb[0]), ord($gb[1]));
                    }
                    elseif( $b1 >= 192 ) {	# 2 bytes UTF-8
                        //			printf( "[%02X%02X]", $b1, ord($instr[$i+1]) );
                        $b1 -= 192;
                        $b2 = ($i+1>=$len) ? 0 : ord($instr[$i+1]) - 128;
                        $i++;
                        $uc = $b1 * 64 + $b2 ;
                        fseek( $fp, $uc * 2 );
                        $gb = fread( $fp, 2 );
                        $outstr[$x++] = $gb[0];
                        $outstr[$x++] = $gb[1];
                        #			printf( "[%02X%02X]", ord($gb[0]), ord($gb[1]));
                    }
                }
                fclose($fp);
                if( $instr != '' ) {
                    #		echo '##' . $instr . " becomes " . join( '', $outstr) . "<br>\n";
                    return join( '', $outstr);
                }
            }
        }
    }
    function pinyin($str,$split="",$pn=true) {
        if(!isset($GLOBALS["iCMS.PY"])) {
            $GLOBALS["iCMS.PY"]=unserialize(gzuncompress(FS::read(iPATH.'include/pinyin.table')));
        }
        preg_match_all(__CN__,trim($str),$match);
        $s = $match[0];
        $c = count($s);
        for ($i=0;$i<$c;$i++) {
            $uni	= strtoupper(dechex(self::UTF8toUni($s[$i])));
            if(strlen($uni)>2) {
                $pyArr	= $GLOBALS["iCMS.PY"][$uni];
                $py	= is_array($pyArr)?$pyArr[0]:$pyArr;
                $pn && $py=str_replace(array('1','2','3','4','5'), '', $py);
                $zh && $split && $R[]=$split;
                $R[]=strtolower($py);
                $zh=true;
                $az09=false;
            }else if(eregi("[a-z0-9]",$s[$i])) {
                $zh && $i!=0 && !$az09 && $split && $R[]=$split;
                $R[]=$s[$i];
                $zh=true;
                $az09=true;
            }else {
                $sp=true;
                if($split) {
                    if($s[$i]==' ') {
                        $R[]=$sp?'':$split;
                        $sp=false;
                    }else {
                        $R[]=$sp?$split:'';
                        $sp=true;
                    }
                }else {
                    $R[]='';
                }
                $zh=false;
                $az09=false;
            }
        }
        return implode('',(array)$R);
    }
}
?>