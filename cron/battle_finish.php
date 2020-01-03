<?php

define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/__config.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__db_connect.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__user.php');

/*

	CRON Завершение поединка и удаление из заявок

*/
$hend = 1; //Через сколько часов проверять и время бездействия

$sp = mysql_query('SELECT * FROM `battle` WHERE `time_start` < "'.( time() - 3600*$hend ).'" AND `team_win` = -1 LIMIT 100');
while( $pl = mysql_fetch_array($sp) ) {
	$test = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$pl['id'].'" ORDER BY `time` DESC LIMIT 1'));
	if(!isset($test['id']) || $test['time'] < ( time() - 3600*$hend ) ) {
		mysql_query('UPDATE `battle` SET `team_win` = "0",`time_over` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		mysql_query('UPDATE `battle_users` SET `finish` = "1" WHERE `battle` = "'.$pl['id'].'"');
		mysql_query('UPDATE `stats` SET `battle_exp` = "0" , `battle_yron` = "0" WHERE `id` IN ( SELECT `id` FROM `users` WHERE `battle` = "'.$pl['id'].'" )');
		mysql_query('UPDATE `users` SET `battle` = "0" WHERE `battle` = "'.$pl['id'].'"');
	}
}



?>