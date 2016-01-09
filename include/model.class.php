<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

/**
 * 自定义模型model
 *
 * @author coolmoo
 */
//$FieldArray=array(
//	'TYPE'=>array(
//		"number"=>'数字(number)',
//		"text"=>'字符串(text)',
//		"radio"=>'单选(radio)',
//		"checkbox"=>'多选(checkbox)',
//		"textarea"=>'文本(textarea)',
//		"editor"=>'编辑器(editor)',
//		"select"=>'选择(select)',
//		"calendar"=>'日历(calendar)',
//		"email"=>'电子邮件(email)',
//		"url"=>'超级链接(url)',
//		"image"=>'图片(image)',
//		"upload"=>'上传(upload)',
//	),
//	'VALIDATE'=>array(
//        "N"=>'不验证',
//        "0"=>'不能为空',
//        "1"=>'匹配字母',
//        "2"=>'匹配数字',
//        "4"=>'Email验证',
//        "5"=>'url验证',
//	),
//);
class model {
    function isSysTable($table) {
        return in_array($table,array('forum','article','article_data'));
    }
    function table($id) {
        if($id) {
            $rs	= iCMS_DB::getRow("SELECT * FROM `#iCMS@__model` where id='$id'",ARRAY_A);
            if(!isSysTable($rs['table'])) {
                $rs['table'] = $rs['table'].'_content';
            }
            return $rs;
        }else {
            return false;
        }
    }
    //数据类型
    function SqlType($type,$len,$default) {//getSqlType
        switch($type) {
            case "number":
                (empty($len)||$len>10) &&$len='11';
                $default=='' && $default='0';
                $sql =" int($len) unsigned NOT NULL  default '".$default."'";
                break;
            case "calendar":
                (empty($len)||$len>10) &&$len='10';
                $default=='' && $default='0';
                $sql =" int($len) unsigned NOT NULL  default '".$default."'";
                break;
            case in_array($type,array('text','checkbox','radio','select','multiple','email','url','image','upload')):
                (empty($len)||$len>255) &&$len='255';
                $sql =" varchar($len) NOT NULL  default '".$default."'";
                break;
            case in_array($type,array('textarea','editor')):
                $sql =" mediumtext NOT NULL";
                break;
        }
        return 	$sql;
    }
    function FieldType($type) {//getFieldType
        switch($type) {
            case "number":	$text='数字(number)';
                break;
            case "text":	$text='字符串(text)';
                break;
            case "radio":	$text='单选(radio)';
                break;
            case "checkbox":$text='多选(checkbox)';
                break;
            case "textarea":$text='文本(textarea)';
                break;
            case "editor":	$text='编辑器(editor)';
                break;
            case "select":	$text='选择(select)';
                break;
            case "multiple":$text='多选选择(multiple)';
                break;
            case "calendar":$text='日历(calendar)';
                break;
            case "email":	$text='电子邮件(email)';
                break;
            case "url":	$text='超级链接(url)';
                break;
            case "image":	$text='图片(image)';
                break;
            case "upload":	$text='上传(upload)';
                break;
        }
        return 	$text;
    }
    function Fieldvalidate($type) {//getFieldvalidate
        switch($type) {
            case "N":$text='不验证';
                break;
            case "0":$text='不能为空';
                break;
            case "1":$text='匹配字母';
                break;
            case "2":$text='匹配数字';
                break;
            case "4":$text='Email验证';
                break;
            case "5":$text='url验证';
                break;
            default: $text='自定义正则';
        }
        return 	$text;
    }
    //选项 choice
    function FieldChoices($choices) {//getFieldChoices
        foreach(explode("\n",$choices) as $item) {
            list($index, $choice) = explode('=', $item);
            $option[trim($index)] = trim($choice);
        }
        return $option;
    }
    function select($id) {//getModelselect
        global $iCMS;
        $model=$iCMS->getCache('system/model.cache');
        $_mCount=count($model);
        $opt='<option value="0"'.($id=="0" ? ' selected="selected"':'').'>文章模型</option>';
        for($i=0;$i<$_mCount;$i++) {
            if($model[$i]) {
                $selected= ($model[$i]['id']==$id) ? ' selected="selected"':'';
                $opt.="<option value='{$model[$i]['id']}'{$selected}>{$model[$i]['name']}</option>";
            }
        }
        return $opt;
    }
}