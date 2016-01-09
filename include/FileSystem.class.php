<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
//文件操作类
class FS {
    function check($fn) {
        strpos($fn,'..')!==false && exit('What are you doing?');
    }
    function del($fn,$check=1) {
        $check && self::check($fn);
        @chmod ($fn, 0777);
        return @unlink($fn);
    }
    function read($fn,$check=1,$method="rb") {
        $check && self::check($fn);
        if(function_exists('file_get_contents') && $method!="rb") {
            $filedata = file_get_contents($fn);
        }else {
            if($handle=@fopen($fn,$method)) {
                flock($handle,LOCK_SH);
                $filedata=@fread($handle,filesize($fn));
                fclose($handle);
            }
        }
        return $filedata;
    }
    function write($fn,$data,$check=1,$method="rb+",$iflock=1,$chmod=1) {
        $check && self::check($fn);
        touch($fn);
        $handle=fopen($fn,$method);
        if($iflock) {
            flock($handle,LOCK_EX);
        }
        fwrite($handle,$data);
        if($method=="rb+") ftruncate($handle,strlen($data));
        fclose($handle);
        $chmod && @chmod($fn,0777);
    }
    //创建目录
    function mkdir($d) {
        $d = str_replace( '//', '/', $d );
        if ( file_exists($d) )
            return @is_dir($d);

        // Attempting to create the directory may clutter up our display.
        if ( @mkdir($d) ) {
            $stat = @stat(dirname($d));
            $dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
            @chmod($d, $dir_perms );
            return true;
        } elseif (is_dir(dirname($d))) {
            return false;
        }

        // If the above failed, attempt to create the parent node, then try again.
        if ( ( $d != '/' ) && ( self::mkdir(dirname($d))))
            return self::mkdir( $d );

        return false;
    }
    //删除目录
    function rmdir($dir,$df=true) {
        if ($dh=@opendir($dir)) {
            while (($file=readdir($dh))!== false) {
                if ($file != "." && $file != "..") {
                    $path = $dir.'/'.$file;
                    is_dir($path) ? self::rmdir($path,$df) : ($df ? @unlink($path) : null);
                }
            }
            closedir($dh);
        }
        return @rmdir($dir);
    }
    function info($path){
    	return (OBJECT)pathinfo($path);
    }
    function path($p='') {
        $a=explode('/', $p);
        $o=array();
        $c=count($a);
        for ($i=0; $i<$c; $i++) {
            if ($a[$i]=='.'||$a[$i]=='') continue;
            if ($a[$i]=='..' && $i>0 && end($o)!='..') {
                array_pop($o);
            }else {
                $o[]=$a[$i];
            }
        }
        $o['0']=='http:' && $o['0']='http:/';
        return ($p{0}=='/'?'/':'').implode('/', $o);
    }
    function path_is_absolute( $path ) {
        // this is definitive if true but fails if $path does not exist or contains a symbolic link
        if ( realpath($path) == $path )
            return true;

        if ( strlen($path) == 0 || $path{0} == '.' )
            return false;

        // windows allows absolute paths like this
        if ( preg_match('#^[a-zA-Z]:\\\\#', $path) )
            return true;

        // a path starting with / or \ is absolute; anything else is relative
        return (bool) preg_match('#^[/\\\\]#', $path);
    }
    function path_join( $base, $path ) {
        if ( !self::path_is_absolute($path) )
            $path = rtrim($base, '/') . '/' . ltrim($path, '/');

        return self::path($path);
    }

    //获取远程页面的内容
    function remote($url) {
        set_time_limit(0);
        if (function_exists('curl_init')) {
			$uri=parse_url($url);
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, $url);
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
			curl_setopt($curl_handle, CURLOPT_REFERER,$uri['scheme'].'://'.$uri['host']);
	  		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.38 Safari/532.0');
			$file_content = curl_exec($curl_handle);
			curl_close($curl_handle);
        }elseif (ini_get('allow_url_fopen') && ($handle  = fopen($url, 'rb'))) {
            if(function_exists('stream_get_contents')) {
                $file_content= stream_get_contents($handle);
            }else {
                while(!feof($handle) && connection_status() == 0) {
                    $file_content.= fread($handle,8192);
                }
            }
            fclose($handle);
        }else {
            $file_content = file_get_contents(urlencode($url));
        }
        return $file_content;
    }
    function remotepic(&$content,$intro='',$autopic=false) {
        global $iCMS;
        $content = stripslashes($content);
        $img = array();
        preg_match_all("/<img.*?src\s*=[\"|'|\s]*((http|file):\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$content,$match);
        $_array = (array)array_unique($match[1]);
        $uri 	= parse_url($iCMS->config['uploadURL']);
        foreach($_array AS $_k=> $imgurl) {
            if(strstr(strtolower($imgurl),$uri['host'])) unset($_array[$_k]);
        }
        if(empty($_array)) return;
        set_time_limit(0);
        $RootPath		=self::path_join(iPATH,$iCMS->config['uploadfiledir']).'/';//绝对路径
        $FileDir 		= "";
        if($iCMS->config['savedir']) {
            $FileDir = str_replace(array('Y','y','m','n','d','j','H','EXT'),
                    array(get_date('','Y'),get_date('','y'),get_date('','m'),get_date('','n'),get_date('','d'),get_date('','j'),get_date('','H'),$FileExt),
                    $iCMS->config['savedir']);
        }
        $RootPath	= $RootPath.$FileDir."/";
        $milliSecond = 'remote_'.get_date('',"YmdHis").rand(1,99999);
       	self::mkdir($RootPath);
        require_once(iPATH.'include/snoopy.class.php');
        require_once(iPATH.'include/upload.class.php');
        $Snoopy = new Snoopy;
        $Snoopy->agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5";
        $Snoopy->accept = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        foreach($_array as $key =>$value) {
            $FileExt=strtolower(FS::getExt($value));//&#316;&#701;
            iUpload::CheckValidExt($value);//判断文件类型
            //过滤文件;
            strstr($FileExt, 'ph') && $FileExt="phpfile";
            in_array($FileExt,array('cer','htr','cdx','asa','asp','jsp','aspx','cgi')) && $FileExt.="file";

            $FileRootPathTmp= $RootPath.$milliSecond.$key.".".$FileExt;
            $Snoopy->fetch($value);
            if($Snoopy->results) {
                self::write($FileRootPathTmp,$Snoopy->results);
                $FileMd5	= md5_file($FileRootPathTmp);
                $rs			= iCMS_DB::getRow("SELECT * FROM #iCMS@__file WHERE `filename`='$FileMd5' LIMIT 1");
                if(empty($rs)){
	                $FileName 		= $FileMd5.".".$FileExt;
		            $FilePath		= $FileDir."/".$FileName;
		            $FileRootPath	= $RootPath.$FileName;
	                rename($FileRootPathTmp,$FileRootPath);
	                if(in_array($FileExt,array('gif','jpg','jpeg','png'))) {
	                    if($iCMS->config['isthumb'] &&($iCMS->config['thumbwidth']||$iCMS->config['thumbhight'])) {
	                        list($width, $height,$imagetype) = getimagesize($FileRootPath);
	                        if ( $width > $iCMS->config['thumbwidth'] || $height >$iCMS->config['thumbhight'] ) {
	                           self::mkdir($RootPath."thumb");
	                        }
	                        $Thumbnail=iUpload::thumbnail($RootPath, $FileRootPath, $FileMd5);
	                        (!empty($Thumbnail['filepath']) && $iCMS->config['thumbwatermark']) && iUpload::watermark($Thumbnail['filepath']);
	                    }
	                    iUpload::watermark($FileRootPath);
	                }
	                $_FileSize = @filesize($FileRootPath);
	                empty($_FileSize) && $_FileSize=0;
	                iCMS_DB::query("INSERT INTO `#iCMS@__file` (`filename`,`ofilename`,`path`,`intro`,`ext`,`size` ,`time`,`type`) VALUES ('$FileMd5', '$value', '$FileDir','$intro', '$FileExt', '$_FileSize', '".time()."', '1') ");
                }else{
                	$FilePath = $rs->path."/".$rs->filename.".".$rs->ext;
                	self::del($FileRootPathTmp);
                }
                $content = str_replace($value,uploadpath($FilePath,'+http'),$content);
                if($autopic && $key==0) break;
            }
        }
        $content = addslashes($content);
    }
    //文件名
    function name($fn) {
        $_fn = substr(strrchr($fn, "/"), 1);
        return array('name'=>substr($_fn,0,strrpos($_fn, ".")),
                'path'=>substr($fn,0,strrpos($fn, "."))
        );
    }
    // 获得文件扩展名
    function getExt($fn) {
        return substr(strrchr($fn, "."), 1);
    }
    // 获取文件大小
    function sizeUnit($filesize) {
        $SU = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $n = 0;
        while ($filesize >= 1024) {
            $filesize /= 1024;
            $n++;
        }
        return round($filesize,2).' '.$SU[$n];
    }
    //获取文件夹列表
    function folder($d,$dir='',$type='') {
        $sDir = trim($d);
        $type = strtolower($type);
        strpos($sDir,'.')!==false && exit('What are you doing?');
        $s_Url = "";
        $FDir = self::path(iPATH.$dir).'/';
        $sCurrDir = $FDir;
        if ($sDir != "") {
            if (is_dir($FDir.$sDir)) {
                $sCurrDir = $FDir.$sDir."/";
            }else {
                $sDir = "";
            }
            $s_Url =(strrpos($sDir, "/") !== false)?substr($sDir, 0, strrpos($sDir, "/")):"";
            $parentfolder=$s_Url;
        }
        if ($handle = opendir($sCurrDir)) {
            while (false !== ($file = readdir($handle))) {
                $sFileType = filetype($sCurrDir."/".$file);
                switch ($sFileType) {
                    case "dir":
                        if ($file!='.'&&$file!='..'&&$file!='admin') {
                            $oDirs[] = $file;
                        }
                        break;
                    case "file":
                        $oFiles[] = $file;
                        break;
                    default:
                }
            }
        }

        if (isset($oDirs))foreach( $oDirs as $oDir) {
                $s_Url = ($sDir == "")?$oDir:$sDir."/".$oDir;
                $folder[]=array('path'=>$s_Url,'dir'=>$oDir);
            }
        $nFileNum=count($oFiles);
        for($i=0;$i<$nFileNum;$i++) {
            $oFile=$oFiles[$i];
            $sFileName = $sCurrDir.$oFile;
            $s_Url = ($sDir == "")?$oFile:$sDir . "/" . $oFile;
            if($type && strstr($type,self::getExt($oFile))!==false) {
                $FileList[]=array('path'=>$s_Url,
                        'name'=>$oFile,
                        'time'=>get_date(filemtime($sFileName),"Y-m-d H:i"),
                        'icon'=>self::icon($oFile),
                        'ext'=>self::getExt($oFile),
                        'size'=>self::sizeUnit(filesize($sFileName))
                );
            }elseif(empty($type)) {
                $FileList[]=array('path'=>$s_Url,
                        'name'=>$oFile,
                        'time'=>get_date(filemtime($sFileName),"Y-m-d H:i"),
                        'icon'=>self::icon($oFile),
                        'ext'=>self::getExt($oFile),
                        'size'=>self::sizeUnit(filesize($sFileName))
                );
            }
        }
        $R['FileList']		=$FileList;
        $R['parentfolder']	=$parentfolder;
        $R['folder']		=$folder;
        return $R;
    }
    function icon($fn) {
        $ext = strtoupper(self::getExt($fn));
        switch ($ext) {
            case "TXT":$icon = "txt.gif";
                break;
            case "CHM":$icon = "hlp.gif";
                break;
            case "HLP":$icon = "hlp.gif";
                break;
            case "DOC":$icon = "doc.gif";
                break;
            case "PDF":$icon = "pdf.gif";
                break;
            case "MDB":$icon = "mdb.gif";
                break;
            case "GIF":$icon = "gif.gif";
                break;
            case "JPG":$icon = "jpg.gif";
                break;
            case "JPEG":$icon = "jpg.gif";
                break;
            case "BMP":$icon = "bmp.gif";
                break;
            case "PNG":$icon = "pic.gif";
                break;
            case "ASP":$icon = "code.gif";
                break;
            case "JSP":$icon = "code.gif";
                break;
            case "JS":$icon = "js.gif";
                break;
            case "PHP":$icon = "php.gif";
                break;
            case "PHP3":$icon = "php.gif";
                break;
            case "ASPX":$icon = "code.gif";
                break;
            case "HTM":$icon = "htm.gif";
                break;
            case "CSS":$icon = "code.gif";
                break;
            case "HTML":$icon = "htm.gif";
                break;
            case "SHTML":$icon = "htm.gif";
                break;
            case "ZIP":$icon = "zip.gif";
                break;
            case "RAR":$icon = "rar.gif";
                break;
            case "EXE":$icon = "exe.gif";
                break;
            case "AVI":$icon = "wmv.gif";
                break;
            case "MPG":$icon = "wmv.gif";
                break;
            case "MPEG":$icon = "wmv.gif";
                break;
            case "ASF":$icon = "mp.gif";
                break;
            case "RA":$icon = "rm.gif";
                break;
            case "RM":$icon = "rm.gif";
                break;
            case "MP3":$icon = "mp3.gif";
                break;
            case "MID":$icon = "wmv.gif";
                break;
            case "MIDI":$icon = "mid.gif";
                break;
            case "WAV":$icon = "audio.gif";
                break;
            case "XLS":$icon = "xls.gif";
                break;
            case "PPT":$icon = "ppt.gif";
                break;
            case "PPS":$icon = "ppt.gif";
                break;
            case "PHPFILE":$icon = "php.gif";
                break;
            case "FILE":$icon = "common.gif";
                break;
            case "SWF":$icon = "swf.gif";
                break;
            default:$icon = "unknow.gif";
                break;
        }
        return '<img border="0" src="admin/images/file/'.$icon.'" align="absmiddle" id="icon">';
    }
}
?>