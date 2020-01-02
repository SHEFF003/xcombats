<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Каменный страж
*/
$pvr = array();
//Действие при клике
$pvr['id'] = mysql_fetch_array(mysql_query('SELECT `id` FROM `test_bot` WHERE `login` = "Каменный Страж (Прием)" AND `level` = "'.$u->info['level'].'" LIMIT 1'));
if( isset($pvr['id']['id']) ) {
	$pvr['bot'] = $u->addNewbot($pvr['id']['id'],NULL,NULL);
	//
	$pvr['xznm'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `battle` = "'.$btl->info['id'].'" AND `login` LIKE "Каменный Страж%" LIMIT 1'));
	if($pvr['xznm'][0] > 0) {
		$pvr['xznm'] = ' ('.($pvr['xznm'][0]).')';
	}else{
		$pvr['xznm'] = '';
	}
	//
	mysql_query('UPDATE `users` SET `login` = "Каменный Страж'.$pvr['xznm'].'",`obraz` = "0.gif",`battle` = "'.$btl->info['id'].'" WHERE `id` = "'.$pvr['bot']['id'].'" LIMIT 1');
	mysql_query('UPDATE `stats` SET `team` = "'.$u->info['team'].'" WHERE `id` = "'.$pvr['bot']['id'].'" LIMIT 1');
	//
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
		'Каменный Страж',
		'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'.',
		($btl->hodID)
	);
	//
	$this->addPriem($u->info['id'],254,'add_yhod='.floor($pvr['bot']['id']).'',2,77,-2,$u->info['id'],1,'каменныйстражзащитить',0,0,1,floor($pvr['bot']['id']));
}

unset($pvr);
?>