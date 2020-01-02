<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'addmoney' ) {
	if( $u->info['align'] != 2 ) {
		if( $itm['price2'] > 0 ) {
			$bnki = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `block` = "0" ORDER BY `id` DESC LIMIT 1'));
		}
		if( $itm['price4'] > 0 ) {
			mysql_query('UPDATE `users` SET `money4` = `money4` + "'.$itm['price4'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}elseif( $itm['price2'] == 0 ) {
			mysql_query('UPDATE `users` SET `money` = `money` + "'.$itm['price1'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}else{
			mysql_query('UPDATE `bank` SET `money2` = `money2` + "'.$itm['price2'].'" WHERE `id` = "'.$bnki['id'].'" LIMIT 1');	
		}
		if( $itm['price4'] > 0 ) {
			$u->error = 'Вы успешно обналичили чек на '.$u->zuby($itm['price4'],1).'';
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		}elseif( $itm['price2'] == 0 ) {
			$u->error = 'Вы успешно обналичили чек на '.$itm['price1'].' кр.';
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		}else{
			if( isset($bnki['id']) ) {
				$u->error = 'Вы успешно обналичили чек на '.$itm['price2'].' екр. (Банк: №'.$bnki['id'].' )';
				mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');	
			}else{
				$u->error = 'Чек на '.$itm['price2'].' екр. нельзя обналичить! У Вас нет банковского счета!';	
			}
		}
	}else{
		$u->error = 'Хаосники не могут обналичивать чек!';
	}
}
?>