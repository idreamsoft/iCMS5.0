<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class iCMS extends Template {
    public $config='';
    public $id='';
    public $title='';
    public $cacheID='';
    public $compileID='';
    public $firstcount=0;
    public $pagenav='';
    public $pagesize=0;
    public $date=array();
    public $mode='';
    public $dir='';
    public $url='';
    public $action='';
    public $actionSQL='';
    public $result='';
    public $get=array();

    function __construct() {
        global $config,$_iGLOBAL;
        $this->config        = $config;
        $this->version        = Version;
        $this->template_dir    = iPATH.'templates';
        $this->compile_dir    = iPATH.'cache/templates';
        $this->cache_dir    = iPATH.'cache/templates';
//    $this->plugins_dir    = array("plugins",iPATH.'include/modifier');
        $this->cache        = false;
        $this->debugging    = false;
        $this->left_delimiter    = '<!--{';
        $this->right_delimiter    = '}-->';
        $this->assign("version", iCMS_VER);
        $this->assign("poweredby", '<a href="http://www.idreamsoft.com" target="_blank">iCMS</a> '.iCMS_VER);
        $this->assign('site',array("title"=>$this->config['name'],"seotitle"=>$this->config['seotitle'],
                "keywords"    =>$this->config['keywords'],
                "description"=>$this->config['description'],
                "domain"    =>$this->config['domain'],
                "url"        =>$this->config['url'],
                "dir"        =>substr($this->config['dir'],0,-1),
                "publicURL"    =>$this->config['publicURL'],
                "tpl"        =>$this->config['template'],
                "tplpath"    =>$this->config['dir']."templates/".$this->config['template'],
                "tplurl"    =>$this->config['url']."/templates/".$this->config['template'],
                "email"        =>$this->config['masteremail'],
                "icp"        =>$this->config['icp']));
        $this->assign("cookie", $_iGLOBAL['cookie']);
        $this->dir        = $this->config['dir'];
        $this->register_modifier("date",     "get_date");
        $this->register_modifier("cut",     "format_string");
        $this->register_modifier("htmlcut", "htmlSubString");
        $this->register_modifier("count",     "cstrlen");
        $this->register_modifier("html2txt", "HtmToText");
        $this->register_modifier("pinyin",     "GetPinyin");
        $this->register_modifier("unicode", "getUNICODE");
        $this->register_modifier("small","gethumb");
        $this->initCache();
    }
    function initCache() {
        if(!isset($this->iCache)) {
            if($this->config['cacheEngine']=='memcached') {
                $_servers = explode("\n",str_replace(array("\r"," "),"",$this->config['cacheServers']));
                $this->iCache = new memcached(array(
                                'servers' =>$_servers,
                                'compress_threshold' => 10240,
                                'persistant' => false,
                                'debug' => false,
                                'compress'    => $this->config['iscachegzip']
                ));
                unset($_servers);
            }else {
                $this->iCache = new FileCache(array(
                                'dirs'    => iPATH.$this->config['cachedir'],
                                'level'    => $cacheLevel?$cacheLevel:$this->config['cachelevel'],
                                'compress'=> $this->config['iscachegzip']
                ));
            }
        }
    }
    public function getCache($cacheName,$ckey=NULL) {
        $_cName=implode('',(array)$cacheName);
        if(!$this->config['iscache']&&strstr($_cName,'system')===false) {
            return NULL;
        }
        if(!isset($GLOBALS['iCMS.cache'][$_cName])) {
            $GLOBALS['iCMS.cache'][$_cName]=is_array($cacheName)?
                    $this->iCache->get_multi($cacheName):
                    $this->iCache->get($cacheName);
        }
        return $ckey?$GLOBALS['iCMS.cache'][$_cName][$ckey]:$GLOBALS['iCMS.cache'][$_cName];
    }
    public function setCache($cacheName,$rs,$cachetime="-1") {
        if(!$this->config['iscache']&&strstr($cacheName,'system')===false) {
            return NULL;
        }
        if($this->config['cacheEngine']=='memcached') {
            $this->iCache->delete($cacheName);
        }
        $this->iCache->add($cacheName,$rs,($cachetime!="-1"?$cachetime:$iCMS->config['cachetime']));
        return $this;
    }
    //Index
    public function Index($indexTPL='',$indexname='') {
        empty($indexname)&& $indexname=$this->config['indexname'];
        empty($indexTPL)&& $indexTPL=$this->config['indexTPL'];
        $url=$indexname.$this->config['htmlext'];
        $this->gotohtml(iPATH.$url,$this->config['htmlURL'].FS::path($this->dir.$url));
        return $this->iPrint($indexTPL);
    }
    //List
    public function iList($id,$tpl='index',$act=true) {
        $cache    = $this->getCache(array('system/forum.cache','system/forum.rootid'));
        $rs        = $cache['system/forum.cache'][$id];
        empty($rs) && $this->error('error:page');
        if($tpl && $rs['url']) return $this->go($rs['url']);

        $iurl			= $this->iurl('forum',$rs);
        $rs['url']    	= $iurl->href;
        $rs['link']		= "<a href='{$rs['url']}'>{$rs['name']}</a>";
        $rs['nav']		= $this->shownav($rs['fid']);
        $rs['subid']    = $cache['system/forum.rootid'][$id];
        $rs['subids']   = implode(',',(array)$rs['subid']);
        $rs['parent']	= array();
        if($rs['rootid']){
        	$rs['parent']		= $cache['system/forum.cache'][$rs['rootid']];
	        $rs['parent']['url']= $rs['parent']['iurl']->href;
	        $rs['parent']['link']= "<a href='{$rs['parent']['url']}'>{$rs['parent']['name']}</a>";
        }
        $this->assign('forum',$rs);
        if($tpl) {
            $act && $this->gotohtml($iurl->path,$iurl->href,$rs['mode']);
            $GLOBALS['page']>1 && $tpl='list';
            return $this->iPrint($rs[$tpl.'TPL'],'forum.'.$tpl);
        }
    }
    //Show
    public function Show($id,$page=1,$tpl=true) {
        $rs=iCMS_DB::getRow("SELECT a.*,d.tpl,d.body,d.subtitle FROM #iCMS@__article as a LEFT JOIN #iCMS@__article_data AS d ON a.id = d.aid WHERE a.id='".(int)$id."' AND a.status ='1'");
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//var_dump($explain);
        empty($rs) && $this->error('error:page');
        $F    = $this->getCache('system/forum.cache',$rs->fid);
        if($F['status']==0)return false;
        if($rs->url) {
            if($this->mode=="CreateHtml") {
                return false;
            }else {
                $this->go($rs->url);
            }
        }
        if($this->mode=="CreateHtml" && (strstr($F['contentRule'],'{PHP}')||$F['url']||$F['mode']==0))return false;
        
        $_iurlArray    = array((array)$rs,$F);
        $rs->iurl    = $this->iurl('show',$_iurlArray,$page);
        $rs->url    = $rs->iurl->href;
        $tpl && $this->gotohtml($rs->iurl->path,$rs->iurl->href,$F['mode']);
        $this->commentInfo    = array('title'=>$rs->title,'indexId'=>$rs->id,'mId'=>0,'sortId'=>$rs->fid);
        $this->iList($rs->fid,false);
        preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$rs->body,$picArray);
        $pA = array_unique($picArray[1]);
        foreach($pA as $key =>$pVal) {
            $ipVal = uploadpath($pVal,'http2iPATH');
            file_exists($ipVal) && $rs->photo[]=trim($pVal);
        }
        $body    =explode('<!--iCMS.PageBreak-->',$rs->body);
        $rs->pagetotal=count($body);
        $rs->body=$this->keywords($body[intval($page-1)]);
        $rs->pagecurrent=$page;
        if($rs->pagetotal>1) {
            $ppHref=$this->iurl('show',$_iurlArray,(($page-1>1)?$page-1:1))->href;
            $rs->pagebreak='<a href="'.$ppHref.'" class="prevpagebreak" target="_self">'.$this->language('page:prev').'</a> ';
            for($i=1;$i<=$rs->pagetotal;$i++) {
                $cls=($i==$page)?"pagebreaksel":"pagebreak";
                $rs->pagebreak.='<a href="'.$this->iurl('show',$_iurlArray,$i)->href.'" class="'.$cls.'" target="_self">'.$i.'</a>';
            }
            $npHref=$this->iurl('show',$_iurlArray,(($rs->pagetotal-$page>0)?$page+1:$page))->href;
            $rs->pagebreak.='<a href="'.$npHref.'" class="nextpagebreak" target="_self">'.$this->language('page:next').'</a>';
            
            if($page<$rs->pagetotal){
                $imgA = array_unique($picArray[0]);
                foreach($imgA as $key =>$img){
                    $rs->body =str_replace($img,'<p align="center"><a href="'.$npHref.'"><b>'.$this->language('show:PicGotoNext').'</b></a></p>
                    <a href="'.$npHref.'" title="'.$rs->title.'">'.$img.'</a><br/>',$rs->body);
                }
            }
        }
        $rs->page=array('total'=>$rs->pagetotal,'current'=>$rs->pagecurrent,'break'=>$rs->pagebreak,'prev'=>$ppHref,'next'=>$npHref);

        if($rs->tags) {
            $tagarray=explode(',',$rs->tags);
            foreach($tagarray AS $tk=>$tag) {
                $t = $this->getTag($tag);
                if($t){
                    $rs->tag[$tk]['name']=$tag;
                    $rs->tag[$tk]['url']=$t['url'];
                    $rs->taglink.='<a href="'.$rs->tag[$tk]['url'].'" class="tag" target="_self" title="'.$t['count'].$this->language('page:list').'">'.$rs->tag[$tk]['name'].'</a> ';
                }
            }
        }

        $rs->rel=$rs->related;
        $rs->prev=$this->language('show:first');
        $prers=iCMS_DB::getRow("SELECT * FROM `#iCMS@__article` WHERE `id` < '{$rs->id}' AND `fid`='{$rs->fid}' AND `status`='1' order by id DESC Limit 1");
        $prers && $rs->prev='<a href="'.$this->iurl('show',array((array)$prers,$F))->href.'" class="prev" target="_self">'.$prers->title.'</a>';
        $rs->next=$this->language('show:last');
        $nextrs = iCMS_DB::getRow("SELECT * FROM `#iCMS@__article` WHERE `id` > '{$rs->id}'  and `fid`='{$rs->fid}' AND `status`='1' order by id ASC Limit 1");
        $nextrs && $rs->next='<a href="'.$this->iurl('show',array((array)$nextrs,$F))->href.'" class="next" target="_self">'.$nextrs->title.'</a>';
        $rs->link="<a href='{$rs->url}'>{$rs->title}</a>";
        $this->result    = $rs;
        if($F['mode']) {
            $rs->hits    = "<script src=\"".$this->config['publicURL']."/action.php?do=hits&id={$rs->id}&action=show\" type=\"text/javascript\"></script>";
            $rs->digg    = "<script src=\"".$this->config['publicURL']."/action.php?do=digg&id={$rs->id}&action=show\" type=\"text/javascript\"></script>";
            $rs->comments="<script src=\"".$this->config['publicURL']."/action.php?do=comment&id={$rs->id}\" type=\"text/javascript\"></script>";
        }else {
            $this->mode!='CreateHtml'&&iCMS_DB::query("UPDATE `#iCMS@__article` SET hits=hits+1 WHERE `id` ='{$rs->id}' LIMIT 1");
        }
        $this->assign('show',(array)$rs);
        if($tpl) {
            $tpl=empty($rs->tpl)?$F['contentTPL']:$rs->tpl;
            return $this->iPrint($tpl,'show');
        }
    }
    function tag($name) {
        empty($name) && alert($this->language('tag:empty'));
        $c = $this->getTag($name);
        $this->assign("tagRs",$c);
        return $this->iPrint(empty($c['tpl'])?"iTPL":$c['tpl'],"tag");
    }
    //search
    function search($q='') {
        $q==''&& javascript::alert($this->language('search:keywordempty'));
        empty($type) && $type='title';
        $keyword=$q;
        $q=str_replace(array('%','_'),array('\%','\_'),$q);
        $this->actionSQL=" And CONCAT(title,keywords,description,author) like '%{$q}%' ";
        if($id=iCMS_DB::getValue("SELECT id FROM `#iCMS@__search` where `search`='{$q}'")) {
            iCMS_DB::query("UPDATE `#iCMS@__search` SET `times`=times+1 WHERE `id`='$id'");
        }else {
            iCMS_DB::query("INSERT INTO `#iCMS@__search` (`search`,`times`,`addtime`) VALUES ('{$q}','0','".time()."')");
        }
        $this->assign("search",array('keyword'=>$keyword));
        $this->iPrint("iTPL","search");
    }
    function comment($indexId=0,$mId=0,$sortId=0) {
        if(empty($indexId)) {
            $this->iList($sortId,false);
            $this->iPrint("iTPL","comment.sort");
        }else {
            $total=iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__comment` WHERE `mid`='$mId' and `sortId`='$sortId' and `status`='1' AND indexId='$indexId'");
            $this->assign("total",$total);
            if(empty($mId)) {
                $this->Show($indexId,1,false);
                $this->iPrint("iTPL","comment.article");
            }else {
                $this->content($mId,$indexId,false);
                $this->iPrint("iTPL","comment.content");
            }
        }
    }
    //tag
    function getTagKey($name) {
        if(strpos($name,',')===false) {
            $key = substr(md5($name),8,16);
            $tp     = 'system/TAG/data/'.substr($key,0,3).'/'.substr(md5($name),8,16);
        }else{
            $TN = explode(',',$name);
            foreach($TN AS $n) {
                $key = substr(md5($n),8,16);
                $tp[]= 'system/TAG/data/'.substr($key,0,3).'/'.substr(md5($n),8,16);
            }
        }
        return $tp;
    }
    function getTag($name) {
        $c = $this->getCache($this->getTagKey($name));
        return $c['status']?$c:false;
    }
//-------------------------------------------------------------------------------------------------
    Function value($key,$value) {
        $this->assign($key,$value);
    }
    Function clear($key) {
        $this->clear_assign($key);
    }
    Function output($tpl,$td='',$res='') {
        $res=='' && $res='file:';
        $this->display($res.$td.'/'.$tpl.".htm",$this->cacheID,$this->compileID);
    }
    //------------------------------------
    Function tpl($tpl) {
        if($this->mode=='CreateHtml') {
            return $this->fetch($tpl,$this->cacheID,$this->compileID);
        }else {
            $this->display($tpl,$this->cacheID,$this->compileID);
        }
    }
    Function iPrint($tpl,$p='index') {
        empty($tpl) && $this->error('error:notpl',$tpl);
        strpos($tpl,'{TPL}')!==false && $tpl = str_replace('{TPL}',$this->config['template'],$tpl);
        if(file_exists($this->template_dir."/".$tpl)) {
            return $this->tpl($tpl);
        }elseif($this->config['template'] && file_exists($this->template_dir."/".$this->config['template']."/{$p}.htm")) {
            return $this->tpl($this->config['template']."/{$p}.htm");
        }elseif($tpl=='iTPL') {
            return $this->tpl("system/{$p}.htm");
        }else {
            $this->error('error:notpl',$tpl);
        }
    }
    function gotohtml($fp,$url='',$fmode='0') {
        $fmode && $this->mode!='CreateHtml' && file_exists($fp) && stristr($fp, '.php?') === FALSE && $this->go($url);
    }
    function language($string) {
        $langFile=$this->template_dir.'/'.$this->config['template'].'/'.$this->config['language'].'.php';
        if(!file_exists($langFile)) {
            $langFile=$this->template_dir.'/system/'.$this->config['language'].'.php';
        }
        if(!isset($GLOBALS['_iLang'])) $GLOBALS['_iLang']=include($langFile);
        if(strpos($string,':')!==false) {
            list($s,$k)=explode(':',$string);
            return isset($GLOBALS['_iLang'][$s][$k])?$GLOBALS['_iLang'][$s][$k]:$string;
        }else {
            return isset($GLOBALS['_iLang'][$string])?$GLOBALS['_iLang'][$string]:$string;
        }
    }
    Function error($string,$tpl="") {
 //       header('HTTP/1.1 404 Not Found');
//        trigger_error('模板:' . $this->config['dir']."templates/".$tpl. ' 错误信息:' . $this->language($string). '1003');
        $this->assign('TPL_PATH',$this->config['dir']."templates/".$tpl);
        $this->assign('error',$this->language($string));
        $this->output('error','system');
        exit;
    }
    function linkmodeSQL($ifield,$tfield,$argv) {
        if($this->config['linkmode']=='id') {
            $sql="{$ifield}='{$argv}'";
        }elseif($this->config['linkmode']=='title') {
            $sql="{$tfield}='{$argv}'";
        }
        return $sql;
    }
    function FPDIR($fid="0") {
        $F    = $this->getCache('system/forum/'.$fid);
        $F['rootid'] && $dir.=$this->FPDIR($F['rootid']);
        $dir.='/'.$F['dir'];
        return $dir;
    }

    function domain($fid="0",$akey='dir') {
        $ii     = new stdClass();
        $F    = $this->getCache('system/forum/'.$fid);
        $rootid = $F['rootid'];
        $ii->sdir= $F[$akey];
        if($rootid && empty($F['domain'])) {
            $dm = $this->domain($rootid);
            $ii->pd    = $dm->pd;
            $ii->domain    = $dm->domain;
            $ii->pdir    = $dm->pdir.'/'.$F[$akey];
            $ii->dmpath    = $dm->dmpath.'/'.$F[$akey];
        }else {
            $ii->pd    = $ii->pdir   = $ii->sdir;
            $ii->dmpath    = $ii->domain = $F['domain']?'http://'.$F['domain']:'';
        }
        return $ii;
    }

    function irule($a,$b,$c,$p) {
        switch($b) {
            case 'PHP':
                switch($a) {
                    case 'forum':$e='list.php?id='.$c['fid'];
                        break;
                    case 'show':$e='show.php?id='.$c['id'];
                        break;
                    case 'content':$e='content.php?id='.$c['id'].'&mid='.$a['mId'];
                        break;
                    case 'tag':$e=$this->config['publicURL'].'/tag.php?name='.irawurldecode($c['tName']);
                        break;
                }
                break;
            case 'FID':$e=$c['fid'];
                break;
            case '0xFID':$e=sprintf("%08s",abs(intval($c['fid'])));
                break;
            case 'FDIR':$e=$c['dir'];
                break;
            case 'FPDIR':$e=substr($this->FPDIR($c['fid']), 1);
                break;
            case 'LINK':$e=$c['clink'];
                $a=='tag' && $e=$c['link'];
                break;
            case 'P':$e=$p;
                break;
            case 'MD5':
                $e=md5($c['id']);
                $a=='tag' && $e=md5($c['tName']);
                $e=substr(md5($e),8,16);
                break;
            case 'TIME':$e=$c['pubdate'];
                break;
            case 'YY':$e=get_date($c['pubdate'],'y');
                break;
            case 'YYYY':$e=get_date($c['pubdate'],'Y');
                break;
            case 'M':$e=get_date($c['pubdate'],'n');
                break;
            case 'MM':$e=get_date($c['pubdate'],'m');
                break;
            case 'D':$e=get_date($c['pubdate'],'j');
                break;
            case 'DD':$e=get_date($c['pubdate'],'d');
                break;
            case 'AID':$e=$c['id'];
                break;
            case '0xID':$e=sprintf("%08s",abs(intval($c['id'])));
                break;
            case '0x3ID':$e=substr(sprintf("%08s",abs(intval($c['id']))), 0, 4);
                break;
            case '0x3,2ID':$e=substr(sprintf("%08s",abs(intval($c['id']))), 4, 2);
                break;
            case 'MID':$e=$c['mId'];
                break;
            case 'MNAME':$e=$c['mName'];
                break;
            case 'ZH_CN':$e=$c['tName'];
                break;
            case 'TNAME':$e=rawurlencode($c['tName']);
                break;
            case 'TID':$e=$c['id'];
            case 'TID500':$e=ceil($c['id']/500).'/'.$c['id'];
                break;
            case 'SID':$e=$c['sortid'];
                break;
            case 'EXT':$e=empty($c['htmlext'])?$this->config['htmlext']:$c['htmlext'];
                break;
        }
        return $e;
    }
    function iurl($uri,$a=array(),$p=1) {
        $i 		= new stdClass();
        $htmldir= $this->config['htmldir'];
        $htmlURL= $this->config['htmlURL'];
        switch($uri) {
            case 'forum':
                $rule   = $a['mode']==0?'{PHP}':$a['forumRule'];
                $i->href= $a['url'];
                break;
            case 'show':
                unset($a[1]['url']);
                $Furl   = $this->iurl('forum',$a[1]);
                $rule   = $a[1]['mode']==0?'{PHP}':$a[1]['contentRule'];
                $i->href= $this->config['publicURL']."/link.php?id=".$a[0]['id']."&url=".irawurldecode($a[0]['url']);
                $a      = array_merge($a[0], (array)$a[1]);
                break;
            case 'content':
                $rule   = $this->config['ctRule'];
                $i->href= $this->config['publicURL']."/link.php?id=".$a['id']."&url=".irawurldecode($a['url']);
                break;
            case 'tag':
            //$Furl   = $this->iurl('forum',$a[1]);
            //$rule   = $a[1]['mode']==0?'{PHP}':($a[1]['tagRule']?$a[1]['tagRule']:$this->config['tagRule']);
                $a[0]['tName']=$a[0]['name'];
                $a      = array_merge($a[0], (array)$a[1]);
                $rule   = $this->config['tagRule'];
                $htmldir=$this->config['tagDir'];
                $htmlURL=$this->config['tagURL'];
                break;
        }
        if($a['url']) return $i;
        $url    = preg_replace ("/\{(.*?)\}/e",'$this->irule($uri,"\\1",$a,$p)',$rule);

        if(strstr($rule,'{PHP}')===false) {
            $i->path    = FS::path(iPATH.$htmldir.'/'.$url);
            $i->href    = FS::path($htmlURL.'/'.$url);
            if(in_array($uri,array('show','content'))) {
                $i->path    = FS::path($Furl->dmdir.'/'.$url);
                $i->href    = FS::path($Furl->domain.'/'.$url);
            }
            $i->hdir    = dirname($i->href);
            $i->file    = basename($i->path);
            $i->name    = substr($i->file,0,strrpos($i->file,'.'));
            empty($i->name) && $i->name=$i->file;
            $i->ext    = strrchr($i->file, ".");
            if(empty($i->file)||substr($url,-1)=='/'||empty($i->ext)) {
                $i->name    = 'index';
                $i->ext     = empty($a['htmlext'])?$this->config['htmlext']:$a['htmlext'];
                $i->file    = $i->name.$i->ext;
                $i->path    = $i->path.'/'.$i->file;
                $i->hdir    = dirname($i->href.'/'.$i->file);
            }
 //           var_dump($i);
            $i->dir        = dirname($i->path);
            if(in_array($uri,array('show','content'))) {
                if(strstr($rule,'{P}')===false && $p>1) {
                    $fn    = $i->name.'_'.$p.$i->ext;
                    $i->path = $i->dir.'/'.$fn;
                    $i->href = $i->hdir.'/'.$fn;
                }
            }
            if($uri=='forum') {
                $m    = $this->domain($a['fid']);
                if($m->domain) {
                    $i->href = str_replace($i->hdir,$m->dmpath,$i->href);
                    $i->hdir = $m->dmpath;
                    $__dir__ = $i->dir.'#iCMS#'.$m->pdir;
                    $i->path = str_replace($i->dir,$__dir__,$i->path);
                    $i->dir  = str_replace($m->sdir.'#iCMS#'.$m->pdir,$m->pdir,$__dir__);
                    $i->path = str_replace($__dir__,$i->dir,$i->path);
                    $i->dmdir   = FS::path(iPATH.$htmldir.'/'.$m->pd.'/');
                    $bits       = parse_url($i->href);
                    $i->domain  = $bits['scheme'].'://'.$bits['host'];
                }else {
                    $i->dmdir   = FS::path(iPATH.$htmldir.'/');
                    $i->domain  = $htmlURL;
                }

            }
//            var_dump($i);
        }else {
            $i->href    = $url;
            $p>1 && $i->href.='&p='.$p;
        }
        return $i;
    }
    //翻页函数
    function multi($array) {
        include_once iPATH.'include/multi.class.php';
        $multi=new multi($array);
        if($multi->totalpage>1) {
            $this->assign($array['pagenav'],$multi->show($pnstyle));
            $this->assign('pageA',array('total'=>$multi->totalpage,'current'=>$multi->nowindex,'break'=>$multi->show($pnstyle)));
            $this->assign('multi',$multi);
        }
        $offset    =$multi->offset;
        unset($multi);
        return $offset;
    }
    function shownav($fid="0") {
        $cache= $this->getCache('system/forum.cache');
        $F    = $cache[$fid];
        if($F) {
            $_nav = '<a href="'.$this->iurl('forum',$F)->href.'">'.$F['name'].'</a>';
            $F['rootid'] && $nav.=$this->shownav($F['rootid']).$this->language('navTag');
            $nav.= $_nav;
        }
        return $nav;
    }
    function keywords($a) {
        if($this->config['kwCount']==0) return $a;
        $kw    = $this->getCache('system/keywords');
        if($kw){
        	foreach($kw AS $i=>$val) {
	            if($val['status']) {
	                $s[]=$val['keyword'];
	                $r[]=$val['replace'];
	            }
           }
            return $this->str_replace_limit($s, $r, stripslashes($a),$this->config['kwCount']);
        }
        return $a;
    }
    function str_replace_limit($search, $replace, $subject, $limit=-1) {
        preg_match_all ("/<a[^>]*?>(.*?)<\/a>/si", $subject, $matches);//链接不替换
        $linkArray    = array_unique($matches[0]);
        $linkflip    = array_flip($linkArray);
        foreach($linkflip AS $linkHtml=>$linkkey){
            $linkA[$linkkey]='###iCMS_link'.mt_rand(1,1000).'_'.$linkkey.'###';
        }
        $subject = str_replace($linkArray,$linkA,$subject);

        preg_match_all ("/<[\/\!]*?[^<>]*?>/si", $subject, $matches);
        $htmArray    = array_unique($matches[0]);
        $htmflip    = array_flip($htmArray);
        foreach($htmflip AS $kHtml=>$vkey){
            $htmA[$vkey]="###iCMS_html".mt_rand(1,1000).'_'.$vkey.'###';
        }
        $subject = str_replace($htmArray,$htmA,$subject);

        // constructing mask(s)...
        if (is_array($search)) {
            foreach ($search as $k=>$v) {
                $search[$k] = '`' . preg_quote($search[$k],'`') . '`i';
            }
        }else {
            $search = '`' . preg_quote($search,'`') . '`';
        }
        // replacement
        $subject = preg_replace($search, $replace, $subject, $limit);
        $subject = str_replace($htmA,$htmArray,$subject);
        $subject = str_replace($linkA,$linkArray,$subject);
        return $subject;
    }
    function go($URL='') {
        empty($URL)&&$URL=__REF__;
        if(!headers_sent()) {
            header("Location: $URL");
            exit;
        }else {
            echo '<meta http-equiv=\'refresh\' content=\'0;url='.$URL.'\'><script type="text/JavaScript">window.location.replace(\''.$URL.'\');</script>';
            exit;
        }
    }
}
