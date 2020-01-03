<?php

/*

	Ядро для обработки данных.
	Обработка поединков, обработка заявок, обработка ботов, обработка пещер, обработка турниров, обработка временных генераций

*/

if(isset($_GET['test'])) {
	/*
	define('GAME',true);

	setlocale(LC_CTYPE ,"ru_RU.CP1251");
	
	include('_incl_data/__config.php');
	include('_incl_data/class/__db_connect.php');
	include('_incl_data/class/__user.php');
	
	if($u->info['admin'] > 0) {
		$sp = mysql_query('SELECT `id` FROM `users` WHERE `real` > 0 AND `banned` = 0 AND `level` > 3');
		while( $pl = mysql_fetch_array($sp) ) {
			$u->addItem(4532,$pl['id']);
		}
	}
	*/
}

die('Отсутствует подключение к базе.');

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

if(isset($_GET['robot'])) {
	
}elseif(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '5.187.7.71') {
	if( !isset($_GET['test'])) {
		die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}


define('GAME',true);

setlocale(LC_CTYPE ,"ru_RU.CP1251");

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

if(isset($_GET['mail'])) {
	if(isset($_GET['ok'])) {
		mysql_query('UPDATE `users_mail1` SET `login2` = "1" WHERE `login2` != "1" ORDER BY `online` DESC LIMIT 1');
		header('location: /AI.php?robot&mail');
		die();
	}
	$sp = mysql_query('SELECT * FROM `users_mail1` WHERE `login2` != "1" ORDER BY `online` DESC LIMIT 1');
	while( $pl = mysql_fetch_array($sp)) {
		echo 'E-mail:<br><br><b>'.$pl['mail'].'</b><br><br><br><a href="/AI.php?robot&mail&ok">СЛЕДУЮЩИЙ E-MAIL</a>';
	}
	$xx = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users_mail1` WHERE `login2` = 1 LIMIT 1'));
	$xx = $xx[0];
	$xx2 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users_mail1` LIMIT 1'));
	$xx2 = $xx2[0] + 7588;
	echo '<Br><br>(Отправлено писем: '.$xx.' / '.$xx2.')';
}

include('_incl_data/class/__user.php');
include('_incl_data/class/bot.priem.php');
include('_incl_data/class/bot.logic.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

$count = array(
	0,
	0,
	0,
	0,
	0,
	0
);

function inuser_go_btl($id) {
	if(isset($id['id'])) {
		file_get_contents('http://xcombats.com/jx/battle/refresh.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass']);
	}
}

$sp = mysql_query('SELECT `u`.* , `s`.* FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `u`.`pass` = "botforpeople" ORDER BY `s`.`nextAct` ASC LIMIT 100');

$btltest = array();

while($pl = mysql_fetch_array($sp)) {
	
	$i++;

	if( $pl['zv'] == 0 && ($pl['battle'] == 0 || !isset($btltest[$pl['battle']]) || $btltest[$pl['battle']] < 10)) {
		
		$btltest[$pl['battle']]++;
		
		if( $pl['timereg'] == 0 ) {
			mysql_query('UPDATE `users` SET `timereg` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}else{
			mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		
		if( $pl['bot'] == 0 ) {
			mysql_query('UPDATE `stats` SET `bot` = "2" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		
		mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		
		botLogic::start( $pl['id'] );
		
	}else{
		
		if( $pl['zv'] > 0 ) {
			botLogic::start( $pl['id'] );
		}
		
		if( $pl['timereg'] == 0 ) {
			mysql_query('UPDATE `users` SET `timereg` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}else{
			mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		
		if( $pl['bot'] == 0 ) {
			mysql_query('UPDATE `stats` SET `bot` = "2" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		mysql_query('UPDATE `stats` SET `nextAct` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		
		echo '*';
	}
	echo '+';
	echo '['.$pl['login'].'] -> Действие: '.$pl['ipreg'].' , ожидаем: '.($pl['timeMain']-time()).' сек., заявка: '.$pl['zv'].', поединок: '.$pl['battle'].'';
	echo '<hr>';
}
?>