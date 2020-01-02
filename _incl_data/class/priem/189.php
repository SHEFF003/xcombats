<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Ошеломить
	
*/
$pvr = array();
//Действие при клике

$prv['text'] = $btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
$pvr['x5'] = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`hod` FROM `eff_users` WHERE `uid` = "'.$u->info['enemy'].'" AND `v2` = 191 AND `delete` = 0 LIMIT 1'));

if( $pvr['x5']['x'] > 0 ) {
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
		'Ошеломить',
		'{tm1} '.$prv['text'].' на {u2}.',
		($btl->hodID + 1)
	);	
	//Отнимаем тактики
	$this->mintr($pl);
}else{
	if( isset($btl->stats[$btl->uids[$u->info['enemy']]]['antishock']) && $btl->stats[$btl->uids[$u->info['enemy']]]['antishock'] > 0 && $pvr['x5']['x'] >= 2 ) {
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
			'Ошеломить',
			'{tm1} '.$prv['text'].' на {u2}. (Цель полностью защищена от шока)',
			($btl->hodID + 1)
		);
		if( isset($pvr['x5']['id']) ) {
			mysql_query('UPDATE `eff_users` SET `hod` = 5,`x` = ( `x` + 1 ) WHERE `id` = "'.$pvr['x5']['id'].'" LIMIT 1');
		}
	}else{
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
			'Ошеломить',
			'{tm1} '.$prv['text'].' на {u2}.',
			($btl->hodID + 1)
		);
		$pvr['x4'] = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`hod` FROM `eff_users` WHERE `uid` = "'.$u->info['enemy'].'" AND `v2` = 275 AND `delete` = 0 LIMIT 1'));
		mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$pvr['x4']['id'].'" LIMIT 1');
		$this->addPriem($u->info['enemy'],275,'add_notactic=1|add_nousepriem=1',0,77,(2-$pvr['x5']['x']),$u->info['id'],5,'ошеломить');
		if( !isset($pvr['x5']['id']) ) {
			$this->addPriem($u->info['enemy'],191,'add_antishock=1',0,77,5,$u->info['id'],5,'иммунитеткошеломить');	
		}else{
			mysql_query('UPDATE `eff_users` SET `hod` = 5,`x` = ( `x` + 1 ) WHERE `id` = "'.$pvr['x5']['id'].'" LIMIT 1');
		}
	}
	
	//Отнимаем тактики
	$this->mintr($pl);
}

unset($pvr);
?>