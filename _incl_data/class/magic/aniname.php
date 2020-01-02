<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'aniname' ) {	
	$u->error = 'Теперь вы можете переименовать своего зверя';
	mysql_query('UPDATE `users_animal` SET `rename` = 0 WHERE `uid` = '.$u->info['id'].' AND `delete` = 0 AND `pet_in_cage` = 0 LIMIT 1');
	mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
}
?>