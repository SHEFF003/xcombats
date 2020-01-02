<?php

define('GAME',true);

setlocale(LC_CTYPE ,"ru_RU.CP1251");

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

$sp = mysql_query('SELECT `id` FROM `battle` WHERE `team_win` = "-1" AND `time_start` < "'.(time()-1800).'" AND `izlom` = 0');
while( $pl = mysql_fetch_array($sp) ) {
	$log_last = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle_logs` WHERE `battle_id` = "'.$pl['id'].'" AND `time` > "'.(time()-1800).'" ORDER BY `id` DESC LIMIT 1'));
	if(!isset($log_last['id'])) {
		mysql_query('UPDATE `battle` SET `time_over` = "'.time().'" , `team_win` = 0 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		mysql_query('UPDATE `stats` SET `battle_yron` = "0" , `battle_exp` = "0" , `battle_text` = "0" WHERE `id` IN (SELECT `id` FROM `users` WHERE `battle` = "'.$pl['id'].'")');
		mysql_query('UPDATE `users` SET `battle` = "0" , `nich` = `nich` + 1 WHERE `battle` = "'.$pl['id'].'"');
		mysql_query('DELETE FROM `battle_users` WHERE `battle` = "'.$pl['id'].'"');
	}
}

?>