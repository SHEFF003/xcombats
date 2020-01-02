<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'yarostzvezd' ) {
	
	
	
	$pvr = array();
	
	//Действие при клике
	$pvr['trs7'] = 5;
	if( $u->stats['hpNow'] < 1 ) {
		$u->error = '<font color=red><b>Вы поглибли и не можете воспользоваться свитком...</b></font>';
	}elseif( $u->info['tactic7'] < $pvr['trs7'] ) {
		$u->error = '<font color=red><b>Недостаточно духа, необходимо '.$pvr['trs7'].'...</b></font>';
	}elseif( isset($btl->info['id']) ) {
		/*
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
			'',
			'{tm1} {u1} использовал заклятие &quot;<b>'.$itm['name'].'</b>&quot;.',
			($btl->hodID)
		);
		*/
		
		mysql_query('UPDATE `stats` SET `tactic7` = `tactic7` - "'.$pvr['trs7'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['tactic7'] = $u->info['tactic7']-$pvr['trs7'];
		$u->stats['tactic7'] = $u->stats['tactic7']-$pvr['trs7'];
		
		mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `id_eff` = 22 AND `v1` = "priem" AND `v2` = 325');
		mysql_query("
			INSERT INTO `eff_users` ( `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
			( 22, '".$u->info['id']."', 'Ярость Холодных Звезд', 'add_acestar=1', 0, 77, 0, '".$u->info['id']."', 0, 'priem', 325, 'elementalcrit.gif', 1, -1, 'яростьхолодныхзвезд', 0, 0, '', 0, 0, 0, 1, 0);
		");
		
		$u->error = '<font color=red><b>Вы почувствовали силу... </b></font>';		
		mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		
	}else{
		$u->error = '<font color=red><b>Свиток возможно использовать только в бою</b></font>';
	}
	
	//Отнимаем тактики
	//$this->mintr($pl);
	
	unset($pvr);
}
?>