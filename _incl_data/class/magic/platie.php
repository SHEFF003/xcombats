<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'platie' && $itm['iznosNOW'] < $itm['iznosMAX']) {
	//
	if( $itm['gift'] == '' || $itm['gift'] == '0' ) {
		$u->error = 'Использовать возможно, только если вам подарят этот предмет!';
	}else{
		//
		$itm['tpjjj'] = 1;
		$itm['tpiii'] = 389;
		if( $itm['item_id'] == 4907 || $itm['item_id'] == 4909 ) {
			$itm['tpjjj'] = 2;
			$itm['tpiii'] = $itm['tpiii'] = 390;
		}
		//
		mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `data` LIKE "%itempltype='.$itm['tpjjj'].'%"');
		//
		mysql_query('INSERT INTO `eff_users`
		(`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES (
			"0","'.time().'","-1",
			"Иллюзия: '.$itm['name'].'","itempl='.$itm['item_id'].'|itempltype='.$itm['tpjjj'].'","'.$u->info['id'].'",
			"'.$itm['tpiii'].'", "spell_item_illusion.gif","0", "0"
		)');
		//
		mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = "'.$itm['id'].'" LIMIT 1');
		$itm['iznosNOW']--;
		//
		$u->error = 'Вы использовали &quot;'.$itm['name'].'&quot; и облачились в красивые одеяния!';
	}
}
?>