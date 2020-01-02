<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Очиститься кровью
*/
$pvr = array();

//Действие при клике
echo '<font color=red><b>Вы успешно использовали прием &quot;Очиститься кровью&quot;</b></font>';

$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
	'Очиститься кровью',
	'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'',
	($btl->hodID)
);

$pvr['no'] = ' AND `a`.`v2` != 201';
$pvr['no'] = ' AND `a`.`v2` != 31';
$pvr['no'] .= ' AND `a`.`v2` != 260';
$pvr['no'] .= ' AND `a`.`v2` != 191';
$pvr['no'] .= ' AND `a`.`v2` != 280';
$pvr['no'] .= ' AND `a`.`v2` != 201';
$pvr['no'] .= ' AND `a`.`v2` != 42 AND `a`.`v2` != 121 AND `a`.`v2` != 122 AND `a`.`v2` != 123 AND `a`.`v2` != 124 AND `a`.`v2` != 125';
$pvr['no'] .= ' AND `a`.`v2` != 186 AND `a`.`v2` != 246 AND `a`.`v2` != 257 AND `a`.`v2` != 281';
$pvr['no'] .= ' AND `a`.`v2` != 282';
$pvr['no'] .= ' AND `a`.`v2` != 21 AND `a`.`v2` != 73 AND `a`.`v2` != 74 AND `a`.`v2` != 75 AND `a`.`v2` != 76 AND `a`.`v2` != 77 AND `a`.`v2` != 78 AND `a`.`v2` != 79';

$pvr['no'] .= ' AND `a`.`name` NOT LIKE "Цель%" ';

$pvr['sp'] = mysql_query('SELECT `a`.* FROM `eff_users` AS `a` LEFT JOIN `priems` AS `b` ON `b`.`id` = `a`.`v2` WHERE `a`.`uid` = "'.$u->info['id'].'" AND `a`.`delete` = 0 AND `a`.`v1` = "priem" '.$pvr['no'].' AND ( `b`.`neg` > 0 OR `a`.`v2` = 191 ) ORDER BY `a`.`id` DESC LIMIT 1');
$pvr['pl'] = mysql_fetch_array($pvr['sp']);
$pvr['pl']['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pvr['pl']['v2'].'" LIMIT 1'));
if( isset($pvr['pl']['priem']) ) {
	$btl->delPriem($pvr['pl'],$btl->users[$btl->uids[$u->info['id']]],100);		
}

//Отнимаем тактики
//$this->mintr($pl);

unset($pvr);
?>