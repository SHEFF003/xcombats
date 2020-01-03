<?php

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}


define('GAME',true);

setlocale(LC_CTYPE ,"ru_RU.CP1251");

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
include('_incl_data/class/bot.priem.php');
include('_incl_data/class/bot.logic.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","Арбидол","6","1","-1")');
}

function inuser_go_btl($id) {
	if(isset($id['id'])) {
		echo '[go]';
		echo '!'.file_get_contents('http://xcombats.com/jx/battle/refresh.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass']);
		echo '<br>';
	}
}

$btl1 = array();
$sp = mysql_query('SELECT `u`.* , `s`.* FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `s`.`bot` > 0 AND `u`.`battle` > 0');
while($pl = mysql_fetch_array($sp)) {	
	echo '<b>|'.$pl['battle'].'|'.$pl['id'].'|inBATTLE</b> | ';
	if(!isset($btl1[$pl['battle']])) {
		inuser_go_btl($pl);	
		$btl1[$pl['battle']]++;
	}
}
?>