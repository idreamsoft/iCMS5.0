<?php
/*
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 *	================================
 *	Plugin Name: Calendar/����
 *	Plugin URI: http://www.idreamsoft.com
 *	Description: Calendar/����
 *	Version: 1.0
 *	Author: ��ľ
 *	Author URI: http://G.idreamsoft.com
 *	TAG:<!--{iCMS:plugins name='calendar'}-->
*/
!defined('iPATH') && exit('What are you doing?');
function iCMS_plugins_calendar($vars,&$iCMS) {
    $y=$_GET['y'];
    $m=$_GET['m'];
    list($nowy,$nowm) = explode('-',get_date('','Y-m'));
    $calendar = array();
    $calendar['year']       = $y ? $y : $nowy;
    $calendar['month']      = $m ? $m : $nowm;
    $calendar['days']       = calendar($calendar['month'],$calendar['year']);
    $calendar['nextmonth']  = ($calendar['month']+1)>12 ? 1 : $calendar['month']+1;
    $calendar['premonth']   = ($calendar['month']-1)<1 ? 12 : $calendar['month']-1;
    $calendar['nextyear']   = $calendar['year']+1;
    $calendar['preyear']    = $calendar['year']-1;
    $calendar['cur_date']   = get_date('','Y n.j D');
    $iCMS->value('SELF',__SELF__);
    $iCMS->value('calendar',$calendar);
    $iCMS->output('calendar',plugin::tpl('calendar'));
}
function calendar($m,$y) {
    $today	= get_date('','j');
    $weekday	= get_date(mktime(0,0,0,$m,1,$y),'w');
    $totalday	= Days4month($y,$m);
    $start	= strtotime($y.'-'.$m.'-1');
    $end	= strtotime($y.'-'.$m.'-'.$totalday);
//    $rs=iCMS_DB::getArray("SELECT A.*,F.name,F.dir FROM `#iCMS@__article` AS A,#iCMS@__forum AS F WHERE A.status='1' AND A.fid=F.fid AND F.status='1' AND pubdate>='$start' AND pubdate<='$end'");
//    for ($i=0;$i<count($rs);$i++) {
//        $pubdate=get_date($rs[$i]['pubdate'],'Y-n-j');
//        $dates[$pubdate]
//        //$postdates .= ($postdates ? ',' : '').get_date($rs[$i]['pubdate'],'Y-n-j');
//    }
    $br = 0;
    $days = '<tr>';
    for ($i=1; $i<=$weekday; $i++) {
        $days .= '<td>&nbsp;</td>';
        $br++;
    }
    for ($i=1; $i<=$totalday; $i++) {
        $br++;
        //$td = (strpos(",$postdates,",','.$y.'-'.$m.'-'.$i.",") !== false) ? '<a href="index.php?date='.$y.'_'.$m.'_'.$i.'"><b>'.$i.'</b></a>' :$i;
        $days .= '<td>'.$i.'</td>';
        if ($br>=7) {
            $days .= '</tr><tr>';
            $br = 0;
        }
    }
    if ($br!=0) {
        for ($i=$br; $i<7;$i++) {
            $days .= '<td>&nbsp;</td>';
        }
    }
    return $days;
}
function Days4month($year,$month) {
    if (!function_exists('cal_days_in_month')) {
        return date('t',mktime(0,0,0,$month+1,0,$year));
    } else {
        return cal_days_in_month(CAL_GREGORIAN,$month,$year);
    }
}
