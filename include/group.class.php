<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class group{
	var $group=array();
	var $all=array();
	
	function __construct($type=NULL){
    	$type && $sql=" and `type`='$type'";
		$rs=iCMS_db::getArray("SELECT * FROM `#iCMS@__group` where 1=1{$sql} ORDER BY `order` , `gid` ASC",ARRAY_A);
		$_count=count($rs);
		for ($i=0;$i<$_count;$i++){
			$this->all[$rs[$i]['gid']]=$this->group[$rs[$i]['type']][]=$rs[$i];
		}
	}
	function select($currentid=NULL,$type="u"){
		if($this->group[$type])foreach($this->group[$type] AS $G){
			$selected=($currentid==$G['gid'])?" selected='selected'":'';
			$option.="<option value='{$G['gid']}'{$selected}>".$G['name']."[GID:{$G['gid']}] </option>";
		}
		return $option;
	}
}
?>