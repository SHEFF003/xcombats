<?php
/*

	Ядро для обработки данных.
	Обработка поединков, обработка заявок, обработка ботов, обработка пещер, обработка турниров, обработка временных генераций

*/


//if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

if(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '91.228.154.180') {
	if(!isset($_GET['test'])) {
		die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}


define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

$count = array(
	0, //завершенных поединков
	0,
	0,
	0,
	0,
	0
);

function clear_user($plid) {
		mysql_query('UPDATE `users` SET `login` = "delete",`login2` = `login` WHERE `id` = "'.$plid.'" LIMIT 1');
	/*	mysql_query('DELETE FROM `users` WHERE `id` = "'.$plid.'" LIMIT 1');
		mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$plid.'"');
		mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$plid.'"');
		mysql_query('DELETE FROM `bank` WHERE `uid` = "'.$plid.'"');*/
}

/*$sp = mysql_query('SELECT `id` FROM `users` WHERE `cityreg` = "" && `timereg` = "0" LIMIT 100');
while($pl = mysql_fetch_array($sp)) {
	$n_st = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `id` = "'.$pl['id'].'" LIMIT 1'));
	if(!isset($n_st['id'])) {
		clear_user($pl['id']);
	}
}*/

function inuser_go_btl($id) {
	if(isset($id['id'])) {
		echo file_get_contents('http://xcombats.com/jx/battle/refresh.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass']);
		echo '<hr>';
	}
}

/* считаем поединки */
//e('обработка отменена.');
$i = 0;
while( $i < 3 ) {
	$sp = mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `team_win` = "-1" AND `time_over` = "0" AND `type` = 329 LIMIT 100');
	while($pl = mysql_fetch_array($sp)) {
		$user1 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `battle` = "'.$pl['id'].'" LIMIT 1'));
		inuser_go_btl($user1);
	}
	$i++;
}

//$i = 0;
//while( $i < 3 ) {
/*	$sp = mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `team_win` = "-1" AND `time_over` = "0" AND `time_start` < "'.(time()-3600).'" LIMIT 100');
	while($pl = mysql_fetch_array($sp)) {
		$user1 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `battle` = "'.$pl['id'].'" LIMIT 1'));
		inuser_go_btl($user1);
	}*/
	//$i++;
//}
?>