<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'dispell' ) {
	//mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
	/*mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND (
		
		`id_eff` = 320 OR `id_eff` = 319 OR `id_eff` = 318 OR `id_eff` = 317 OR `id_eff` = 316 
		
	)');*/
	/*
		Прием: Очиститься кровью
	*/
	$pvr = array();
	
	//Действие при клике
	
	if( isset($btl->info['id']) ) {
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
			'',
			'{tm1} {u1} использовал заклятие &quot;<b>'.$itm['name'].'</b>&quot;.',
			($btl->hodID)
		);
	}
	
	$pvr['nego'] = '';
	$pvr['nego'] .= ' `id_eff` = 320 OR `id_eff` = 319 OR `id_eff` = 318 OR `id_eff` = 317 OR `id_eff` = 316 ';
	
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
	
	$pvr['sp'] = mysql_query('SELECT `a`.* FROM `eff_users` AS `a` LEFT JOIN `priems` AS `b` ON `b`.`id` = `a`.`v2` WHERE `a`.`uid` = "'.$u->info['id'].'" AND `a`.`delete` = 0 AND ( `a`.`v1` = "priem" '.$pvr['no'].' OR ( `a`.`v1` = 0 AND ('.$pvr['nego'].') ) ) AND ( `b`.`neg` > 0 OR `a`.`v2` = 191 OR `a`.`v2` = 0 ) ORDER BY `a`.`id` DESC LIMIT 1');
	$pvr['pl'] = mysql_fetch_array($pvr['sp']);
	if(!isset($pvr['pl']['id'])) {
		$u->error = '<font color=red><b>Не удалось использовать &quot;'.$itm['name'].'&quot;, на вас нет проклятий!</b></font>';
	}elseif( isset($btl->info['id']) ) {
		$pvr['pl']['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pvr['pl']['v2'].'" LIMIT 1'));
		//if( isset($pvr['pl']['priem']) ) {
		//	$btl->delPriem($pvr['pl'],$btl->users[$btl->uids[$u->info['id']]],100);
		$u->error = '<font color=red><b>Вы успешно использовали &quot;'.$itm['name'].'&quot; и сняли эффект &quot;'.$pvr['pl']['name'].'&quot;.</b></font>';		
		mysql_query('UPDATE `eff_users` SET `timeUse` = 1224562000 WHERE `id` = "'.$pvr['pl']['id'].'" LIMIT 1');
		mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		//}
	}else{
		mysql_query('UPDATE `eff_users` SET `timeUse` = 1224562000 WHERE `id` = "'.$pvr['pl']['id'].'" LIMIT 1');
		$u->error = '<font color=red><b>Вы успешно использовали &quot;'.$itm['name'].'&quot;</b></font>';
		mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
	}
	
	//Отнимаем тактики
	//$this->mintr($pl);
	
	unset($pvr);
}
?>