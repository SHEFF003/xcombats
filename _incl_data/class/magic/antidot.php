<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'antidot' ) {	
	mysql_query('DELETE FROM `laba_act` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "trap1"');
	$u->error = 'Вы исцелились от ядов...';
	mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
}
?>