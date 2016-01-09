<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class home extends AdminCP {
    function doDefault() {
        //数据统计
        $rs=iCMS_DB::getArray("SHOW FULL TABLES FROM `".DB_NAME."` WHERE table_type = 'BASE TABLE';");
        foreach($rs as $k=>$val) {
            if(strstr(DB_PREFIX, $val['Tables_in_'.DB_NAME])===false) {
                $iCMSTable[]=$val['Tables_in_'.DB_NAME];
            }else {
                $oTable[]=$val['Tables_in_'.DB_NAME];
            }
        }
        $content_datasize = 0;
        $tables = iCMS_DB::getArray("SHOW TABLE STATUS");
        $_count	=count($tables);
        $tableStr=strtoupper(implode(",",$iCMSTable));
        for ($i=0;$i<$_count;$i++) {
            $tableName	= strtoupper($tables[$i]['Name']);
            if(stristr($tableStr,$tableName)) {
                $datasize += $tables[$i]['Data_length'];
                $indexsize += $tables[$i]['Index_length'];
                if (stristr(strtoupper(DB_PREFIX."article,".DB_PREFIX."forum,".DB_PREFIX."comment,".DB_PREFIX."article_data"),$tableName)) {
                    $content_datasize += $tables[$i]['Data_length']+$tables[$i]['Index_length'];
                }
            }
        }
        $c=iCMS_DB::getValue("SELECT count(*) FROM #iCMS@__forum");
        $a=iCMS_DB::getValue("SELECT count(*) FROM #iCMS@__article");
        file_exists(iPATH.'license.php') && $license	= include iPATH.'license.php';
        include $this->tpl("home");
    }
    function okORno($o) {
        return $o?'<font color=green>支持<b>√</b></font>':'<font color=red>不支持<b>×</b></font>';
    }
}
?>
