<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.cn iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

/**
 * Description of 后面菜单
 *
 * @author coolmoo
 */
class menu {
    function load() {
    	global $iCMS;
        $mArray			= include iPATH.'admin/menu.array.php';
        $plugins_menu	= $iCMS->getCache('system/plugins.menu');
        if($plugins_menu)foreach($plugins_menu AS $key=>$submenu){
        	foreach($submenu AS $k=>$val){
        		$mArray[$key][$k]=$val;
        	}
		}
        $models_menu	= $iCMS->getCache('system/models.menu');
        if($models_menu)foreach($models_menu AS $mkey=>$mVal){
	        $mArray	= self::models($mArray,$mVal['menu'],$mVal['pos']);
 		}
		return $mArray;
    }
    function models($mArray,$mA,$p) {
	   	if($p[1]=='left'||$p[1]=='right'){
	    	$keys	= array_keys($mArray);
	    	$key 	= array_search($p[0], $keys);
    		$p[1]=='right' && $key++;
		   	$a		= array_slice($mArray, 0, $key);
		   	$c		= array_slice($mArray, $key);
			$mArray = array_merge($a, $mA,$c);
	   	}else{
			foreach($mArray AS $key=>$submenu){
	        	foreach($submenu AS $k=>$val){
	        		$mArray[$key][$k]=$val;
	        	}
	        	if($key==$p[0]){
	        		$mArray[$key] = array_merge($mArray[$key], $mA);
	        	}
			}
	   	}
	   	return $mArray;
	}
    function main() {
        $menuArray = self::load();
        echo '<dl>';
        foreach($menuArray AS $key=>$menus) {
        	$mKey='header_'.$key;
        	if(Admin::MP($mKey,'F')) {
        		echo '<dd class="active" id="'.$mKey.'" onClick="menuGroup(this)">'.UI::lang($mKey).'</dd>';
        	}
        }
        echo '</dl>';
    }
    function left() {
        $menuArray = self::load();
        foreach($menuArray AS $key=>$menus) {
            if(Admin::MP('header_'.$key,'F')) {
                echo '<ul class="header_'.$key.'" style="display: none">';
                foreach($menus as $k=>$val) {
					self::li($k,$val);
                }
                echo '</ul>';
            }
        }
    }
    function li($T,$A) {
		if(is_array($A)){
		        $i=0;
		        foreach($A AS $k=>$v) {
		        	if(Admin::MP($k,'F')){
			            $href=strstr($v,'http://') ===false ? __SELF__.'?mo='.$v : $v;
			            $onClick='main_frame_src(\''.$href.'\')';
			            if($i) {
			                echo '<li class="menu_title sub_right" onclick="'.$onClick.'"><b class="border"></b><span><a href="'.$href.'" target="main" hidefocus=true>'.UI::lang($k).'</a></span></li>';
			            }else {
			                echo '<li class="menu_title sub_left" onclick="'.$onClick.'"><span><a href="'.$href.'" target="main" hidefocus=true>'.UI::lang($k).'</a></span></li>';
			            }
			            $i++;
		            }
		        }
		}else{
	    	if(Admin::MP($T,'F')){
		        list($type,$url)=explode(':',$A);
		        if($type=="javascript") {
		            $onClick=$url;
		            $href='javascript:'.$onClick;
		            echo '<li class="menu_title" onclick="'.$onClick.'"><span>'.UI::lang($T).'</span></li>';
		        }else {
		            $href=strstr($A,'http://') ===false ? __SELF__.'?mo='.$A : $A;
		            $onClick='main_frame_src(\''.$href.'\')';
		            echo '<li class="menu_title" onclick="'.$onClick.'"><span><a href="'.$href.'" target="main" hidefocus=true>'.UI::lang($T).'</a></span></li>';
		        }
	        }
        }
    }
}

