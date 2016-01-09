<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class ajax extends AdminCP {
	function doDefault($param=NULL){
		$defArray = $this->iCMS->getCache('system/default');
		$ul="<ul>";
		foreach((array)$defArray[$param] as $val){
			$ul.="<li onclick=\"indefault('$val','$param')\">$val</li>\n";
		}
		$ul.="</ul>";
		echo $ul;
	}
	function doGetsubforum(){
		include_once(iPATH.'include/forum.class.php');
		$forum =new forum();
	 	echo $forum->row($_POST["fid"]);
	}
	function doTag(){
		require_once(iPATH.'include/cn.class.php');
		require_once(iPATH.'include/snoopy.class.php');
		$title=urlencode(CN::u2g($_POST['title']));
	    $Snoopy = new Snoopy;
	    $Snoopy->agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5";
	    $Snoopy->accept = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
	    
		$baidu='http://www.baidu.com/s?wd='.$title;
		$Snoopy->fetch($baidu);
		preg_match_all("/<td\s*nowrap\s*class=\"f14\"><a\s*href=\".*?\">(.*?)<\/a><\/td>/is",CN::g2u($Snoopy->results),$match);
	    $baiduTag = (array)array_unique($match[1]);
	    
		$google='http://www.google.com.hk/search?hl=zh-CN&source=hp&q='.$title.'&aq=f&aqi=&aql=&oq=&gs_rfai=';
	 	$Snoopy->fetch($google);
		preg_match_all("/<p><a\s*href=\".*?\">(.*?)<\/a><\/p>/is",$Snoopy->results,$match);
	    $googleTag = (array)array_unique($match[1]);
	    $tagArray=array_merge($baiduTag,$googleTag);
	    $tagArray=array_unique($tagArray);
		$ul='<ul style="margin:0; padding:0;">';
		foreach((array)$tagArray as $key=> $tag){
			$ul.="<li><input onclick=\"inTag('$tag',$key)\" id=\"gt_{$key}\" class='checkbox' type=\"checkbox\" value=\"$tag\" />$tag</li>\n";
		}
		$ul.="</ul>";
		echo $ul;
	}
	function doforums($op='html'){
		include_once(iPATH.'include/forum.class.php');
		$forum =new forum();
	 	echo $forum->json($_GET["root"],$op);
	}
}
