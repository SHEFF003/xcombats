<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'exitbtl' ) {
	
	
	
	$pvr = array();
	
	//Действие при клике
	if( $u->stats['hpNow'] < 1 ) {
		$u->error = '<font color=red><b>Вы поглибли и не можете воспользоваться свитком...</b></font>';
	}elseif( isset($btl->info['id']) ) {
		
		if( $btl->info['dn_id'] > 0 || $btl->info['izlom'] > 0 ) {
			$u->error = '<font color=red><b>Магия не действует в пещерах и подобных локациях...</b></font>';	
		}elseif( $btl->info['noinc'] > 0 ) {
			$u->error = '<font color=red><b>Бой изолирован и вы не можете его покинуть</b></font>';	
		}else{			
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
				'',
				'{tm1} {u1} сбежал с поля боя... ',
				($btl->hodID)
			);			
			$u->error = '<font color=red><b>Вы сбежали с поля боя и потеряли всю энергию...</b></font>';
			//		
			mysql_query('UPDATE `stats` SET `battle_yron` = 0, `battle_exp` = 0, `hpNow` = 0, `mpNow` = 0 , `tactic1` = 0 , `tactic2` = 0 , `tactic3` = 0 , `tactic4` = 0 , `tactic5` = 0 , `tactic6` = 0 , `tactic7` = -1 , `last_pr` = 0 , `last_hp` = -1 WHERE `id` = '.$u->info['id'].' LIMIT 1');
			mysql_query('UPDATE `users` SET `battle` = 0, `lose` = `lose` + 1 WHERE `id` = '.$u->info['id'].' LIMIT 1');
			//
			mysql_query('DELETE FROM `eff_users` WHERE `v1` = "priem" AND `uid` = "'.$u->info['id'].'" AND `delete` = 0');
			//
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		}
		
	}else{
		$u->error = '<font color=red><b>Свиток возможно использовать только в бою</b></font>';
	}
	
	//Отнимаем тактики
	//$this->mintr($pl);
	
	unset($pvr);
}
?>