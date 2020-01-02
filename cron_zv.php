<?php

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# Получаем IP
function getIPblock() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# Выполняем проверку безопасности. 

if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }
if(getIPblock() != $_SERVER['SERVER_ADDR'] && getIPblock() != '127.0.0.1' && getIPblock() != '' && getIPblock() != '91.228.154.180') {
	die(getIPblock().'<br>'.$_SERVER['SERVER_ADDR']);
}


define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
include('_incl_data/class/__zv.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

function send_chat($type,$from,$text,$time) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`login`,`to`,`type`,`new`,`time`,`room`) VALUES ("'.mysql_real_escape_string($text).'","capitalcity","'.mysql_real_escape_string($from).'","","'.$type.'","1","'.mysql_real_escape_string($time).'","3")');
}

function inuser_go_btl($id) {
	if(isset($id['id'])) {
		echo file_get_contents('http://xcombats.com/jx/battle/refresh.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass']);
		echo '<hr>';
	}
}

//
$sp = mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `team_win` = "-1" AND `time_over` = "0" AND `time_start` < "'.(time()-3600).'" LIMIT 100');
while($pl = mysql_fetch_array($sp)) {
	$user1 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `battle` = "'.$pl['id'].'" LIMIT 1'));
	inuser_go_btl($user1);
}

//Проверка боев
/*
$sp = mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `team_win` = -1');
while( $pl = mysql_fetch_array($sp) ) {
	$test = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$pl['id'].'" ORDER BY `id` DESC LIMIT 1'));
	$end = 0;
	if(!isset($test['id']) && $pl['time_start'] < time() - 3600 ) {
		$end = 1;
	}elseif( $test['time'] < time() - 3600 ) {
		$end = 1;
	}
	e($pl['id']);
	if( $end == 1 ) {
		mysql_query('UPDATE `battle` SET `team_win` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		mysql_query('UPDATE `users` SET `battle` = "0" WHERE `battle` = "'.$pl['id'].'" LIMIT 1');
	}
}
*/

$zv->testCronZv();
?>