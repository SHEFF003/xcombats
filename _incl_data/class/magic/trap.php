<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'trap' && $itm['iznosNOW'] < $itm['iznosMAX']) {
	if( $u->info['room'] >= 362 && $u->info['room'] <= 366 ) {
		$box = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `mid` = "'.$bs['type_map'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" LIMIT 1'));
		if( isset($box['id']) ) {
			$bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `id` = "'.$u->info['inTurnir'].'" LIMIT 1'));
			$real_u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`sex`,`align`,`clan` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" LIMIT 1'));
			if(!isset($real_u['id'])) {
				$real_u = $u->info;
			}
			mysql_query('INSERT INTO `bs_trap` (`sex`,`bid`,`count`,`x`,`y`,`chance`,`time`,`uid`,`login`,`level`,`align`,`clan`) VALUES (
				"'.$real_u['sex'].'","'.$bs['id'].'","'.$bs['count'].'","'.$box['x'].'","'.$box['y'].'","99","'.time().'",
				"'.$real_u['id'].'","'.$real_u['login'].'","'.$real_u['level'].'","'.$real_u['align'].'","'.$real_u['clan'].'"
			)');
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
			$u->error = 'Вы успешно расставили ловушку в локации &quot;'.$box['name'].'&quot;.';
			unset($real_u,$box,$bs);
		}else{
			$u->error = 'В этой комнате нельзя расставлять ловушки!';
		}
	}else{
		$u->error = 'Расставлять ловушки возможно только в турнирах Башни Смерти';
	}
}
?>