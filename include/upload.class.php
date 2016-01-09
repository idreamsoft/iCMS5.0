<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
//上传
class iUpload {
    function __construct($ikey) {
        global $iCMS;
//		if($iCMS->config['remoteKey']!=$ikey){
//			exit("KEY Error!");
//		}
    }
    function FILES($field,$intro="",$_dir="",$FileName="",$type="upload") {
        global $iCMS;
        $RootPath = FS::path_join(iPATH,$iCMS->config['uploadfiledir']).'/';//绝对路径

        if($_FILES[$field]['name']) {
            $tmp_name = $_FILES[$field]['tmp_name'];
            !is_uploaded_file($tmp_name) && exit("What are you doing?");
            if($_FILES[$field]['error'] > 0) {
                switch((int)$_FILES[$field]['error']) {
                    case UPLOAD_ERR_NO_FILE:
                        @unlink($tmp_name);
                        javascript::alert('请选择上传文件!');
                        return false;
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        @unlink($tmp_name);
                        javascript::alert('上传的文件超过大小!');
                        return false;
                        break;
                }
                return false;
            }
            $_FileSize = @filesize($tmp_name);
            //文件类型
	        $oFileName	= $_FILES[$field]['name'];
//            preg_match("/\.([a-zA-Z0-9]{2,4})$/",$oFileName,$exts);
            $FileExt=strtolower(FS::getExt($oFileName));//&#316;&#701;
            self::CheckValidExt($oFileName);//判断文件类型
            //过滤文件;
            strstr($FileExt, 'ph') && $FileExt="phpfile";
            in_array($FileExt,array('cer','htr','cdx','asa','asp','jsp','aspx','cgi'))&& $FileExt.="file";

            $FileMd5	= md5_file($tmp_name);
            $rs			= iCMS_DB::getRow("SELECT * FROM #iCMS@__file WHERE `filename`='$FileMd5' LIMIT 1");
            if(empty($rs) || $type=="reupload"){
	            empty($FileName) && $FileName = $FileMd5.".".$FileExt;
	            // 文件保存目录方式
	            $FileDir = "";
	            if(empty($_dir)) {
	                if($iCMS->config['savedir']) {
	                    $FileDir = str_replace(array('Y','y','m','n','d','j','H','EXT'),
	                            array(get_date('','Y'),get_date('','y'),get_date('','m'),get_date('','n'),get_date('','d'),get_date('','j'),get_date('','H'),$FileExt),
	                            $iCMS->config['savedir']);
	                }
	            }else {
	                $FileDir=$_dir;
	            }
	            $RootPath		= $RootPath.$FileDir.'/';
	            //创建目录
	            FS::mkdir($RootPath);
	            //文件名
	            $FilePath		= $FileDir.'/'.$FileName;
	            $FileRootPath	= $RootPath.$FileName;
	            self::saveUpload($tmp_name,$FileRootPath);

	            if(in_array($FileExt,array('gif','jpg','jpeg','png'))) {
	                if($iCMS->config['isthumb'] &&($iCMS->config['thumbwidth']||$iCMS->config['thumbhight'])) {
	                    FS::mkdir($RootPath."thumb");
	                    $Thumb=self::thumbnail($RootPath, $FileRootPath, $FileMd5);
	                    (!empty($Thumb['src']) && $iCMS->config['thumbwatermark']) &&  self::watermark($Thumb['src']);
	                }
	                self::watermark($FileRootPath);
	            }
	            // 写入数据库
	            empty($_FileSize) && $_FileSize = 0;
	            if($type=="upload"){
		            iCMS_DB::query("INSERT INTO `#iCMS@__file` (`filename`,`ofilename`,`path`,`intro`,`ext`,`size`,`time`,`type`) VALUES ('$FileMd5', '$oFileName', '$FileDir','$intro', '$FileExt', '$_FileSize', '".time()."', '0') ");
	            	$fid=iCMS_DB::$insert_id;
	            }
            }else{
            	$fid=$rs->id;
                $FilePath = $rs->path."/".$rs->filename.".".$rs->ext;
                $FileName = $rs->filename.".".$rs->ext;
                unlink($tmp_name);
            }
            return array('fid'=>$fid,'FilePath'=>$FilePath,'OriginalFileName'=>$oFileName,'FileName'=>$FileName);
        }else {
            return;
        }
    }
    //保存文件
    function saveUpload($tn,$fp) {
        if (function_exists('move_uploaded_file') && @move_uploaded_file($tn, $fp)) {
            @chmod ($fp, 0777);
        }elseif (@copy($tn, $fp)) {
            @chmod ($fp, 0777);
        }elseif (@is_readable($tn)) {
            if ($fp = @fopen($tn,'rb')) {
                @flock($fp,2);
                $filedata = @fread($fp,@filesize($tn));
                @fclose($fp);
            }
            if ($fp = @fopen($fp, 'wb')) {
                @flock($fp, 2);
                @fwrite($fp, $filedata);
                @fclose($fp);
                @chmod ($fp, 0777);
            } else {
                exit("Upload Unknown Error (fopen)");
                return false;
            }
        }else {
            exit("Upload Unknown Error");
            return false;
        }
    }
    function CheckValidExt($value) {
        global $iCMS;
        $FileExt=strtolower(FS::getExt($value));
        $aExt = explode(',',strtoupper($iCMS->config['fileext']));
        if(!in_array(strtoupper($FileExt),$aExt)) {
            javascript::alert('['.$value.'] 不支持上传此类扩展名的附件');
        }
    }

    function watermark($groundImage) {
        global $iCMS;
        if(empty($iCMS->config['iswatermark']))return;
        list($width, $height,$imagetype) = @getimagesize($groundImage);
        if ( $width < $iCMS->config['waterwidth'] || $height<$iCMS->config['waterheight'] ) {
            return FALSE;
        }
        $isWaterImage = FALSE;
        $formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG等格式。";
        //读取水印文件
        if(!empty($iCMS->config['waterimg']) && file_exists(iPATH."include/watermark/".$iCMS->config['waterimg'])) {
            $waterImage	= iPATH."include/watermark/".$iCMS->config['waterimg'];
            $isWaterImage = TRUE;
            $water_info = @getimagesize($waterImage);
            $water_w    = $water_info[0];//取得水印图片的宽
            $water_h    = $water_info[1];//取得水印图片的高
            switch($water_info[2]) {//取得水印图片的格式
                case 1:$water_im = imagecreatefromgif($waterImage);
                    break;
                case 2:$water_im = imagecreatefromjpeg($waterImage);
                    break;
                case 3:$water_im = imagecreatefrompng($waterImage);
                    break;
                default:die($formatMsg);
            }
        }else {
            //putenv('GDFONTPATH=' .iPATH.'include/');
            //$iCMS->config['watertext']=g2u($iCMS->config['watertext']);
            $fontfile=iPATH.'include/'.$iCMS->config['waterfont'];
        }

        //读取背景图片
        if(!empty($groundImage) && file_exists($groundImage)) {
            $ground_info = @getimagesize($groundImage);
            $ground_w    = $ground_info[0];//取得背景图片的宽
            $ground_h    = $ground_info[1];//取得背景图片的高
			$ground_im	 = self::imagecreate($ground_info[2],$groundImage);//取得背景图片的格式
        }else {
            die("需要加水印的图片不存在！");
        }

        //水印位置
        if($isWaterImage) { //图片水印
            $w = $water_w;
            $h = $water_h;
        }else { //文字水印
            if($iCMS->config['waterfont']) {
                $temp = imagettfbbox($iCMS->config['waterfontsize'],0,$fontfile,$iCMS->config['watertext']);//取得使用 TrueType 字体的文本的范围
                $w = $temp[2] - $temp[6];
                $h = $temp[3] - $temp[7];
                unset($temp);
            }else {
                $w = $iCMS->config['waterfontsize']*cstrlen($iCMS->config['watertext']);
                $h = $iCMS->config['waterfontsize']+5;
            }
        }
        if( ($ground_w<$w) || ($ground_h<$h) ) {
            //       echo "需要加水印的图片的长度或宽度比水印".$label."还小，无法生成水印！";
            return;
        }
        switch($iCMS->config['waterpos']) {
            case 0://随机
                $posX = rand(0,($ground_w - $w));
                $posY = rand($h,($ground_h - $h));
                break;
            case 1://1为顶端居左
                $posX = 0;
                $posY = 0;
                break;
            case 2://2为顶端居中
                $posX = ($ground_w - $w) / 2;
                $posY = 0;
                break;
            case 3://3为顶端居右
                $posX = $ground_w - $w;
                $posY = 0;
                break;
            case 4://4为中部居左
                $posX = 0;
                $posY = ($ground_h - $h) / 2;
                break;
            case 5://5为中部居中
                $posX = ($ground_w - $w) / 2;
                $posY = ($ground_h - $h) / 2;
                break;
            case 6://6为中部居右
                $posX = $ground_w - $w;
                $posY = ($ground_h - $h) / 2;
                break;
            case 7://7为底端居左
                $posX = 0;
                $posY = $ground_h - $h;
                break;
            case 8://8为底端居中
                $posX = ($ground_w - $w) / 2;
                $posY = $ground_h - $h;
                break;
            case 9://9为底端居右
                $posX = $ground_w - $w;
                $posY = $ground_h - $h;
                break;
            default://随机
                $posX = rand(0,($ground_w - $w));
                $posY = rand($h,($ground_h - $h));
                break;
        }

        //设定图像的混色模式
        imagealphablending($ground_im, true);

        if($isWaterImage) { //图片水印
            imagecopymerge($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h,$iCMS->config['waterpct']);//拷贝水印到目标文件
        }else {//文字水印
            if(empty($iCMS->config['watercolor']))$iCMS->config['watercolor']="#FFFFFF";
            if(!empty($iCMS->config['watercolor']) && (strlen($iCMS->config['watercolor'])==7) ) {
                $R = hexdec(substr($iCMS->config['watercolor'],1,2));
                $G = hexdec(substr($iCMS->config['watercolor'],3,2));
                $B = hexdec(substr($iCMS->config['watercolor'],5));
                $textcolor = imagecolorallocate($ground_im, $R, $G, $B);
            }else {
                die("水印文字颜色格式不正确！");
            }
            if($iCMS->config['waterfont']) {
                imagettftext($ground_im,$iCMS->config['waterfontsize'], 0, $posX, $posY, $textcolor,$fontfile, $iCMS->config['watertext']);
            }else {
                imagestring ($ground_im, $iCMS->config['waterfontsize'], $posX, $posY, $iCMS->config['watertext'],$textcolor);
            }
        }

        //生成水印后的图片
        @unlink($groundImage);
        self::image($ground_im,$ground_info[2],$groundImage);
        //释放内存
        unset($water_info);
        isset($water_im) && imagedestroy($water_im);
        unset($ground_info);
    }
    function thumbnail($upfiledir,$src,$tName,$tw='',$th='',$scale=true,$tDir="thumb") {
        global $iCMS;
        $R 		= array();
        $tw		= empty($tw)?$iCMS->config['thumbwidth']:(int)$tw;
        $th		= empty($th)?$iCMS->config['thumbhight']:(int)$th;

        if ( $tw && $th ) {
            list($width, $height,$type) = @getimagesize($src);
            if ( $width < 1 && $height < 1 ) {
                $R['width']    = $tw;
                $R['height']   = $th;
                $R['src'] = $src;
                return $R;
            }

            if ( $width > $tw || $height >$th ) {
	            $R['src'] =$upfiledir.$tDir."/".$tName.'_'.$tw.'x'.$th.'.'.substr(strrchr($src, "."), 1);
				if ( in_array('Gmagick', get_declared_classes() ) ){
					$image = new Gmagick();
					$image->readImage($src);
					$im = self::scale(array("tw"  => $tw,"th" => $th,"w"  => $image->getImageWidth(),"h" => $image->getImageHeight()));
					$image->resizeImage($im['w'],$im['h'], null, 1);
					$image->cropImage($tw,$th, 0, 0);
					//$image->thumbnailImage($gm_w,$gm_h);
	                FS::mkdir($upfiledir.$tDir);
					$image->writeImage($R['src']);
					$image->destroy();
				}else{
	                $im = self::scale(array("tw"  => $tw,"th" => $th,"w"  => $width,"h" => $height ),$scale);
	                $R['width']   = $im['w'];
	                $R['height']  = $im['h'];
	                $res	= self::imagecreate($type,$src);
	                if ($res) {
	                    $thumb = imagecreatetruecolor($im['w'], $im['h']);
	                    imagecopyresampled($thumb,$res, 0, 0, 0, 0, $im['w'], $im['h'], $width, $height);
	                    //PHP_VERSION != '4.3.2' && self::UnsharpMask($thumb);
		                FS::mkdir($upfiledir.$tDir);
	                    self::image($thumb,$type,$R['src']);
	                } else {
	                    $R['src'] = $src;
	                }
                }
            } else {
                $R['width']    = $width;
                $R['height']   = $height;
                $R['src'] = $src;
            }
            return $R;
        }

    }
    function image($res,$type,$fn) {
    	switch($type){
    		case 1:imagegif($res,$fn);break;
    		case 2:imagejpeg($res,$fn);break;
    		case 3:imagepng($res,$fn);break;
    	}
        imagedestroy($res);
    }
    function imagecreate($type,$src) {
    	switch($type){
    		case 1:$res = imagecreatefromgif($src);break;
    		case 2:$res = imagecreatefromjpeg($src);break;
    		case 3:$res = imagecreatefrompng($src);break;
    	}
        return $res;
    }
    function scale($a,$reSize=true) {
    	if($reSize){
			if($a['w'] > $a['h'] ){
				$s = ($a['h'] > $a['th'])? $a['th']/$a['h'] : $a['h']/$a['th'];
				$a['w'] = ceil($s * $a['w']);
				$a['h'] = ($a['h'] > $a['th'])? $a['th'] : $a['h'];
			}else if($a['h'] > $a['w']){
				$s = ($a['w'] > $a['tw']) ? $a['tw']/$a['w'] : $a['w']/$a['tw'];
				$a['h'] = ceil($s * $a['h']);
				$a['w'] = ($a['w'] > $a['tw']) ? $a['tw'] : $a['w'];
			}
		}
        return $a;
    }
}
?>