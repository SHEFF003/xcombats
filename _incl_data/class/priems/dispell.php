<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'dispell' ) {
	//mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
	$u->error = 'Свиток использован! (в бою)';
}
?>