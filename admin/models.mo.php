<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/model.class.php';
class models extends AdminCP {
    function domanage() {
        Admin::MP("menu_models_manage");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__model`");
        $_count=count($rs);
        include admincp::tpl();
    }
    function doadd(){
    	$id	= $_GET['id'];
    	if($id){
	    	$rs	= iCMS_DB::getRow("SELECT * FROM `#iCMS@__model` where `id`='$id'",ARRAY_A);
    	}
    	if(empty($rs)){
    		$rs['position']='tools';
    		$rs['position2']='sub';
    	}
        include admincp::tpl();
    }
    function doSave(){
	    $id			= (int)$_POST['id'];
	    $name		= dhtmlspecialchars($_POST['name']);
	    $table		= dhtmlspecialchars($_POST['table']);
	    $description= dhtmlspecialchars($_POST['desc']);
	    $position2	= $_POST['pos'];
	    $position 	= $_POST['position'];
        $binding	= isset($_POST['binding'])?1:0;
	    empty($name) && javascript::alert('模块名称不能为空！');
		empty($table) && $binding && javascript::alert('模块名不能为空！');
	    if(!$binding && empty($id)){
	        if(empty($table)) {
	            include iPATH.'include/cn.class.php';
	            $table=CN::pinyin($name);
	        }
	    	$table=$table.'_content';
	    }
	    if($id){
	    	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__model` where `table` = '$table' and `id`!='$id'") && javascript::alert('该模块已经存在!请检查是否重复');
	    	iCMS_DB::query("UPDATE `#iCMS@__model` SET `name` = '$name', `table` = '$table', `binding` = '$binding', `description` = '$description', `position` = '$position', `position2` = '$position2' WHERE `id` = '$id';");
	    }else{
	    	iCMS_DB::query("INSERT INTO `#iCMS@__model`(`name`, `table`, `binding`, `description`, `position`,`position2`, `addtime`)VALUES ('$name', '$table', '$binding', '$description', '$position','$position2', '".time()."');");
	    	$id=iCMS_DB::$insert_id;
	    }
	    model::cache();
        $moreaction=array(
                array("text"=>"下一步添加字段","url"=>__SELF__."?mo=models&do=addfield&id=<?php echo $id;?>"),
                array("text"=>"返回模块列表","url"=>__SELF__."?mo=models&do=manage"),
        );
        javascript::dialog('模块添加完成!<br />10秒后返回模块列表',"url:".__SELF__."?mo=models&do=manage",$moreaction,10);
    }
    function doAddfield(){
    	$id	= (int)$_GET['id'];
        include admincp::tpl();
    }
    function dodel(){
    }
    function dodefault(){
	   	$this->domanage();
    }
  
}
