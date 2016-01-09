<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');

function iCMS_advertise($vars,&$iCMS){
	if(empty($vars['name'])){
		echo $iCMS->language('empty:TPL_advName');
		return false;
	}
	$rs	= $iCMS->getCache('system/adm/'.md5($vars['name']));
	if($rs->status!='1')return false;
	$file =FS::path($iCMS->config['htmlURL'].'/!adm').'/';
	switch ($rs->load){
		case "js":
			$file .= "{$rs->style}-{$rs->id}.js";
			echo "<script src=\"".$file."\" language=\"javascript\"></script>";
		break;
		case "iframe":
			$file .= "{$rs->style}-{$rs->id}.html";
			if($rs->style=="image"){
				$rs->code['image']['width'] && $width=" width=\"{$rs->code['image']['width']}\"";
				$rs->code['image']['height'] &&$height=" height=\"{$rs->code['image']['height']}\"";
			}elseif($rs->style=="flash"){
				$rs->code['flash']['width'] && $width=" width=\"{$rs->code['flash']['width']}\"";
				$rs->code['flash']['height'] &&$height=" height=\"{$rs->code['flash']['height']}\"";
			}
			echo '<iframe src="'.$file.'" name="'.$rs->style.'-id-'.$rs->id.'"'.$width.$height.' frameborder="0"scrolling="no" style="overflow: visible;"></iframe>';
		break;
		case "shtml":
			$file .= "{$rs->style}-{$rs->id}.shtml";
			$uri =	parse_url($file);
			echo '<!--#include virtual="'.$uri['path'].'"-->';
		break;
		default:
			if(time() >= $rs->starttime && (time() <= $rs->endtime||empty($rs->endtime))){
				echo adm($rs);
			}
	}
}
function adm($rs){
	if(empty($rs->code)) return '';
	switch ($rs->style) {
	case "code":
		$html=$rs->code["code"]['html'];
		break;
	case "image":
		$rs->code['image']['width'] &&$width=" width=\"{$rs->code['image']['width']}\"";
		$rs->code['image']['height'] &&$height=" height=\"{$rs->code['image']['height']}\"";
		$html="<a href=\"{$rs->code['image']['link']}\" target=\"_blank\" title=\"{$rs->code['image']['alt']}\"><img src=\"{$rs->code['image']['url']}\" alt=\"{$rs->code['image']['alt']}\"{$width}{$height} alt=\"{$rs->code['image']['alt']}\" border=\"0\"></a>";
		break;
	case "flash":
		$rs->code['flash']['width'] &&$width=" width=\"{$rs->code['flash']['width']}\"";
		$rs->code['flash']['height'] &&$height=" height=\"{$rs->code['flash']['height']}\"";
		$html="<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0\" {$width}{$height}><param name=\"movie\" value=\"{$rs->code['flash']['url']}\" /><param name=\"quality\" value=\"high\" /><embed src=\"{$rs->code['flash']['url']}\" quality=\"high\" pluginspage=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\"{$width}{$height}></embed></object>";
		break;
	case "text":
		$rs->code['text']['size'] &&$style=" style=\"font-size:{$rs->code['text']['size']};\"";
		$html="<a href=\"{$rs->code['text']['link']}\" target=\"_blank\" title=\"{$rs->code['text']['title']}\"{$style}>{$rs->code['text']['title']}</a>";
		break;
	}
	return $html;
}
?>