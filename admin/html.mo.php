<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
set_time_limit(0);
include iPATH.'include/forum.class.php';
include iPATH.'include/iHtml.class.php';
class html extends AdminCP {
    function doIndex() {
        Admin::MP(array("menu_html_all","menu_html_index"));
        include admincp::tpl();
    }
    function doAll() {
        Admin::MP("menu_html_all");
        include admincp::tpl();
    }
    function doforum() {
        Admin::MP(array("menu_html_all","menu_html_forum"));
        $forum =new forum();
        include admincp::tpl();
    }
    function doArticle() {
        Admin::MP(array("menu_html_all","menu_html_article"));
        $forum =new forum();
        include admincp::tpl();
    }
    function doTag() {
        Admin::MP(array("menu_html_all","menu_html_tag"));
        $forum =new forum();
        include admincp::tpl();
    }
    function doPage() {
        Admin::MP(array("menu_html_all","menu_html_page"));
        $forum =new forum();
        include admincp::tpl();
    }
    function PG($k,$def='') {
        $val=$_POST[$k]?$_POST[$k]:$_GET[$k];
        empty($val) && $val=$def;
        return $val;
    }
    function isAll() {
        $this->cTime=$this->PG('time');
        empty($this->cTime) && $this->cTime=1;
        return isset($_GET['all'])?'&all=true':false;
    }
    function doCreateAll() {
        javascript::dialog("全站更新，开始生成文章.....",'src:'.__SELF__.'?mo=html&do=CreateArticle&fid=all&all=true');
    }
    function doCreatePage($a=false) {
        empty($_POST['pagetpl']) && javascript::alert("请选择模板!");
        empty($_POST['filename']) && javascript::alert("请填写文件路径!");
        iHtml::Page($_POST['pagetpl'],$_POST['filename']);
        javascript::dialog("独立页面更新完毕!");
    }
    function doCreateIndex() {
        $isA = $this->isAll();
        if($isA) {
            $_POST['indexTPL']=$this->iCMS->config['indexTPL'];
            $_POST['indexname']=$this->iCMS->config['indexname'];
        }else {
            updateConfig($_POST['indexTPL'],'indexTPL');
            updateConfig($_POST['indexname'],'indexname');
            CreateConfigFile();
        }
        iHtml::Index($_POST['indexTPL'],$_POST['indexname']);
        if($isA) {
            javascript::dialog("全站更新完成!",'url:'.__SELF__.'?mo=html&do=all');
        }else {
            javascript::dialog("网站首页更新完成!");
        }
    }
    function doCreateForum() {
        $fids		= $this->PG('fid');
        $cpageNum	= $this->PG('cpn');
        empty($fids) && javascript::alert("请选择版块");
        is_array($fids) && $fids = implode(",", $fids);
        $isA = $this->isAll();
        if(strstr($fids,'all')) {
            $forum	= new forum();
            $fids	= substr($forum->fid(),0,-1);
            _header(__SELF__.'?mo=html&do=CreateForum&time='.$this->cTime.'&cpn='.$cpageNum.'&fid='.$fids.$isA);
        }else {
            $cArray	=explode(',',$fids);
            $cCount	=count($cArray);
            $cpage	=isset($_GET['cpage'])?$_GET['cpage']:1;
            $k		=isset($_GET['k'])?$_GET['k']:0;
            $loop	=isset($_GET['loop'])?$_GET['loop']:0;
            $c		=iHtml::forum($cArray[$k],$cpage,$loop,$cpageNum);

//Array ( [name] => 版块6 [page] => 51 [loop] => 3 [pagesize] => 101 ) 
//print_r($c);
            $text=empty($cpageNum) ?'':"指定生成".$cpageNum."页，";
            if($c['loop']>0 && $c['page']<=$c['pagesize']) {
//				if($c['page']>$cpageNum && !empty($cpageNum)){
//					redirect($c['name']."共".$c['pagesize']."页，指定生成".$cpageNum."页，已生成".$c['page']."页",__SELF__.'?mo=html&do=CreateForum&time='.$this->cTime.'&cpn='.$cpageNum.'&fid='.$fids.'&k='.($k+1).$QUERY_STRING,$this->cTime);
//				}else{
                javascript::dialog($c['name']."共".$c['pagesize']."页，{$text}已生成".$c['page']."页",'src:'.__SELF__.'?mo=html&do=CreateForum&time='.$this->cTime.'&cpn='.$cpageNum.'&fid='.$fids.'&k='.$k.'&cpage='.$c['page'].'&loop='.($c['loop']-1).$isA,'ok',$this->cTime);

//				}
                //_header('admincp.php?mo=html&do=CreateForum&cid='.$fids.'&k='.$k.'&cpage='.$c['page'].'&loop='.($c['loop']-1).$QUERY_STRING);
            }elseif($cCount>1 && $k<$cCount) {
                if($c===false) {
                    $msg="版块FID:{$cArray[$k]}..未能成功生成静态<br />请检查是否以下原因:<br />1.本版块为虚拟版块<br />2.本版块URL规则带有{PHP}<br />3.本版块是外部链接版块<br />4.本版块访问模式为动态";
                }else {
                    $msg=$c['name']." {$text}更新完成";
                }
                javascript::dialog($msg,'src:'.__SELF__.'?mo=html&do=CreateForum&time='.$this->cTime.'&cpn='.$cpageNum.'&fid='.$fids.'&k='.($k+1).$isA,'ok',$this->cTime);
            }
            $isA && javascript::dialog("版块更新完成!<br />开始生成标签...",'src:'.__SELF__.'?mo=html&do=CreateTag&sortid=all&all=true');
            javascript::dialog("版块更新完成!");
        }
    }
    function doCreateArticle() {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $time_start = $mtime[1] + $mtime[0];

        $speed		= $this->PG('speed',25);//生成速度
        $fids		= $this->PG('fid');
        $startid	= (int)$this->PG('startid');
        $endid		= (int)$this->PG('endid');
        $starttime	= $this->PG('starttime');
        $endtime	= $this->PG('endtime');
        $atime		= isset($_GET['atime'])?$_GET['atime']:0;
        $attime		= isset($_GET['attime'])?$_GET['attime']:0;
        $totle		= isset($_GET['totle'])?$_GET['totle']:0;
        $loop		= isset($_GET['loop'])?$_GET['loop']:1;
        $i			= isset($_GET['i'])?$_GET['i']:0;
        if($fids) {
            empty($fids) && javascript::alert("请选择版块");
            is_array($fids) && $fids = implode(",", $fids);
            $isA = $this->isAll();
            if(strstr($fids,'all')) {
                $forum	= new forum();
                $fids=substr($forum->fid(),0,-1);
                _header(__SELF__.'?mo=html&do=CreateArticle&fid='.$fids.$isA);
            }else {
                $cArray	=explode(',',$fids);
                $cCount	=count($cArray);
                $k		=isset($_GET['k'])?$_GET['k']:0;
                $fidsql=strstr($fids,',')?" fid in ($fids)":" fid='$fids'";
                empty($totle)&& $totle = iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__article` WHERE {$fidsql} and `status`='1'");
                $offset	= ($loop-1)*$speed;
                $totlepg= ceil($totle/$speed);
                $loop 	= min($totlepg,$loop);
                $offset<0 && $offset=0;
                $rs		= iCMS_DB::getArray("SELECT id FROM #iCMS@__article WHERE {$fidsql} and `status`='1' order by id DESC LIMIT {$offset} , {$speed}");
                $max=count($rs);
                if($loop<=$totlepg && $max>0) {
                    $msg="共有文章{$totle}篇，已生成{$offset}篇，完成".(round($offset/$totle,3)*100)."%<br />预计用时".$this->Hms(ceil(($totlepg*$atime)/60)*60)."，生成{$speed}篇用时".$this->Hms($atime)."，已用时".$this->Hms($attime)."<br />";
                    for($j=0;$j<$max;$j++) {
                        $Art=iHtml::Article($rs[$j]['id']);
                        $msg.="文章ID:".$rs[$j]['id']."生成…<span style='color:green;'>".($Art==false?'×':'√')."</span>  ";
                        if($j % 2) $msg.='<br />';
                    }
                    $mtime = microtime();
                    $mtime = explode(' ', $mtime);
                    $time_end = $mtime[1] + $mtime[0];
                    $atime	=round($time_end - $time_start, 2);
                    javascript::dialog($msg,'src:'.__SELF__.'?mo=html&do=CreateArticle&speed='.$speed.'&atime='.$atime.'&attime='.($atime+$attime).'&fid='.$fids.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j.$isA,'ok',0);
                }else {
                    $msg="共生成文章{$totle}篇，用时".$this->Hms($attime)."<br />文章更新完毕!";
                    $isA && javascript::dialog($msg."<br />开始生成列表...",'src:'.__SELF__.'?mo=html&do=CreateForum&fid=all&all=true');
                    javascript::dialog($msg);
                }
            }
        }elseif($startid && $endid) {
            ($startid>$endid &&!isset($_GET['g'])) && javascript::alert("开始ID不能大于结束ID");
            empty($totle)&&$totle=($endid-$startid)+1;
            empty($i)&&$i=$startid;
            $tloop=ceil($totle/$speed);
            if($loop<=$tloop) {
                $max=$i+$speed>$endid?$endid:$i+$speed;
                for($j=$i;$j<=$max;$j++) {
                    $Art=iHtml::Article($j);
                    $msg.="文章ID:".$j."生成…<span style='color:green;'>".($Art==false?'×':'√')."</span>  ";
                    if(($j-1) % 2) $msg.='<br />';
                }
                javascript::dialog($msg,'src:'.__SELF__.'?mo=html&do=CreateArticle&startid='.$startid.'&endid='.$endid.'&g&loop='.($loop+1).'&i='.$j,'ok',0);
            }else {
                javascript::dialog("文章更新完毕！");
            }
        }elseif($starttime) {
            $s	= strtotime($starttime);
            $e	= empty($endtime)?time()+86400:strtotime($endtime);
            empty($totle)&& $totle = iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__article` WHERE `pubdate`>='$s' and `pubdate`<='$e' and `status`='1'");
            $offset	= ($loop-1)*$speed;
            $totlepg= ceil($totle/$speed);
            $loop 	= min($totlepg,$loop);
            $offset<0 && $offset=0;
            $rs = iCMS_DB::getArray("SELECT id FROM #iCMS@__article WHERE `pubdate`>='$s' and `pubdate`<='$e' and `status`='1' order by id DESC LIMIT {$offset} , {$speed}");
            $max=count($rs);
            if($loop<=$totlepg && $max>0) {
                $msg="共有文章{$totle}篇，已生成{$offset}篇，完成".(round($offset/$totle,3)*100)."%<br />预计用时".$this->Hms(ceil(($totlepg*$atime)/60)*60)."，生成{$speed}篇用时".$this->Hms($atime)."，已用时".$this->Hms($attime)."<br />";
                for($j=0;$j<$max;$j++) {
                    $Art=iHtml::Article($rs[$j]['id']);
                    $msg.="文章ID:".$rs[$j]['id']."生成…<span style='color:green;'>".($Art==false?'×':'√')."</span>  ";
                    if($j % 2) $msg.='<br />';
                }
                $mtime = microtime();
                $mtime = explode(' ', $mtime);
                $time_end = $mtime[1] + $mtime[0];
                $atime	=round($time_end - $time_start, 2);
                javascript::dialog($msg,'src:'.__SELF__.'?mo=html&do=CreateArticle&speed='.$speed.'&atime='.$atime.'&attime='.($atime+$attime).'&starttime='.$starttime.'&endtime='.$endtime.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j,'ok',0);
            }else {
                javascript::dialog("共生成文章{$totle}篇，用时".$this->Hms($attime)."<br />文章更新完毕!");
            }
        }else {
            javascript::dialog("请选择方式");
        }
    }
    function doCreateTag() {
        $speed		=25;//生成速度
        $sids		=$this->PG('sortid');
        $startid	=(int)$this->PG('startid');
        $endid		=(int)$this->PG('endid');
        $starttime	=$this->PG('starttime');
        $endtime	=$this->PG('endtime');
        $cpageNum	=0; //$_GET['cpn'];
        $totle		=isset($_GET['totle'])?$_GET['totle']:0;
        $loop		=isset($_GET['loop'])?$_GET['loop']:1;
        $i			=isset($_GET['i'])?$_GET['i']:0;
        $isA 		=$this->isAll();
        if($sids) {
            empty($sids) && javascript::alert("请选择分类");
            is_array($sids) && $sids = implode(",", $sids);
            if(strstr($sids,'all')) {
                $forum	= new forum();
                $sids=substr($forum->fid(),0,-1);
                _header(__SELF__.'?mo=html&do=CreateTag&time='.$this->cTime.'&cpn='.$cpageNum.'&sortid='.$sids.$isA);
            }else {
                $sArray	=explode(',',$sids);
                $sCount	=count($sArray);
                $cpage	=isset($_GET['cpage'])?$_GET['cpage']:1;
                $k		=isset($_GET['k'])?$_GET['k']:0;
                $rs		=iCMS_DB::getArray("SELECT `id`,`name` FROM #iCMS@__tags WHERE `sortid` in ($sids) and `status`='1' order by id DESC");
                empty($totle)&&$totle=count($rs);
                $tloop=ceil($totle/$speed);
                if($loop<=$tloop) {
                    $max=$i+$speed>$totle?$totle:$i+$speed;
                    for($j=$i;$j<$max;$j++) {
                        $c	=iHtml::Tag($rs[$j]['name'],$cpage,$loop,$cpageNum);
                        $msg.="标签: [".$c['name']."] 生成…<span style='color:green;'>√</span><br />";
                    }
                    javascript::dialog($msg,'src:'.__SELF__.'?mo=html&do=CreateTag&sortid='.$sids.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j.$isA,'ok',0);
                }else {
                    $isA && javascript::dialog("标签更新完毕!<br />开始生成首页",'url:'.__SELF__.'?mo=html&do=index&all=true');
                    javascript::dialog("标签更新完毕");
                }
            }
//		}elseif($startid && $endid){
//			($startid>$endid &&!isset($_GET['g'])) && javascript::alert("开始ID不能大于结束ID");
//			empty($totle)&&$totle=($endid-$startid)+1;
//			empty($i)&&$i=$startid;
//			$tloop=ceil($totle/$speed);
//			if($loop<=$tloop){
//				$max=$i+$speed>$endid?$endid:$i+$speed;
//				for($j=$i;$j<=$max;$j++){
//					iHtml::Tag($j);
//					$msg.="标签ID:{$j}生成…<span style='color:green;'>√</span><br />";
//				}
// 				javascript::dialog($msg,'src:'.__SELF__.'?mo=html&do=CreateTag&startid='.$startid.'&endid='.$endid.'&g&loop='.($loop+1).'&i='.$j.$isA,'ok',0);
//			}else{
//				javascript::dialog("标签更新完毕");
//			}
        }elseif($starttime) {
            $s	= strtotime($starttime);
            $e	= empty($endtime)?time()+86400:strtotime($endtime);
            $rs=iCMS_DB::getArray("SELECT id,name FROM #iCMS@__tags WHERE `updatetime`>='$s' and `updatetime`<='$e' and `status`='1' order by id DESC");
            empty($totle)&&$totle=count($rs);
            $tloop=ceil($totle/$speed);
            if($loop<=$tloop) {
                $max=$i+$speed>$totle?$totle:$i+$speed;
                for($j=$i;$j<$max;$j++) {
                    iHtml::Tag($rs[$j]['name']);
                    $msg.="标签:[".$rs[$j]['name']."]生成…<span style='color:green;'>√</span><br />";
                }
                javascript::dialog($msg,'src:'.__SELF__.'?mo=html&do=CreateTag&starttime='.$starttime.'&endtime='.$endtime.'&totle='.$totle.'&loop='.($loop+1).'&i='.$j,'ok',0);
            }else {
                javascript::dialog("标签更新完毕");
            }
        }else {
            javascript::alert("请选择方式");
        }
    }
    function Hms($s) {
        $h=floor($s/3600);
        $h && $txt.=$h.'小时';
        $m=floor(($s-$h*3600)/60);
        $m && $txt.=$m.'分钟';
        $ss=$s-$h*3600-$m*60;
        $ss && $txt.=$ss.'秒';
        return $txt;
    }
}
