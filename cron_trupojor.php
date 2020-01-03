<?php

/*

	Ядро для обработки данных.
	Обработка поединков, обработка заявок, обработка ботов, обработка пещер, обработка турниров, обработка временных генераций
	root /bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;/bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;/bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;/bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;/bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;/bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;/bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;/bin/sleep 7;php -f /var/www/xcombats.com/data/www/xcombats.com/cron_trupojor.php;
	
*/

die('Что-то тут не так...');

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

define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","Мусорщик","6","1","-1")');
}

if(!isset($_GET['test']) && getIPblock() != '') {
	if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }
	if(getIPblock() != $_SERVER['SERVER_ADDR'] && getIPblock() != '127.0.0.1' && getIPblock() != '' && getIPblock() != '5.187.7.71') {
		die(getIPblock().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}

function testMonster( $mon , $type ) {
	$r = true;
	if(isset($mon['id'])) {
		//
		if($type == 'start') {
			//День недели
			if( $mon['start_day'] != -1 ) {
				if( $mon['start_day'] != date('w') ) {
					$r = false;
				}
			}
			//Число
			if( $mon['start_dd'] != -1 ) {
				if( $mon['start_dd'] != date('j') ) {
					$r = false;
				}
			}
			//месяц
			if( $mon['start_mm'] != -1 ) {
				if( $mon['start_mm'] != date('n') ) {
					$r = false;
				}
			}
			//час
			if( $mon['start_hh'] != -1 ) {
				if( $mon['start_hh'] != date('G') ) {
					$r = false;
				}
				if( $mon['start_min'] != -1 ) {
					if( $mon['start_min'] < (int)date('i') ) {
						$r = false;
					}
				}
			}
			//
		}elseif($type == 'back') {
			//День недели
			if( $mon['back_day'] != -1 ) {
				if( ($mon['back_day'] < 7 && $mon['back_day'] != date('w')) || $mon['back_day'] != 7 ) {
					$r = false;
				}
			}
			//Число
			if( $mon['back_dd'] != -1 ) {
				if( $mon['back_dd'] != date('j') ) {
					$r = false;
				}
			}
			//месяц
			if( $mon['back_mm'] != -1 ) {
				if( $mon['back_mm'] != date('n') ) {
					$r = false;
				}
			}
			//час
			if( $mon['back_hh'] != -1 ) {
				if( $mon['back_hh'] != date('G') ) {
					$r = false;
				}
				if( $mon['back_min'] != -1 ) {
					if( $mon['back_min'] < (int)date('i') ) {
						$r = false;
					}
				}
			}
		}else{
			//что-то другое
			$r = false;
		}
		//
	}
	return $r;
}

/*
if(isset($_GET['test'])) {
	echo '[День недели w '.date('w').']<br>';
	echo '[Число j '.date('j').']<br>';
	echo '[Месяц n '.date('n').']<br>';
	echo '[Час G '.date('G').']<br>';
	echo '[Минуты i '.date('i').']<br>';
	
	$mon = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_monsters` WHERE `uid` = "'.mysql_real_escape_string($_GET['test']).'" LIMIT 1'));
	if( testMonster($mon,'start') == true ) {
		echo 'true';
	}else{
		echo 'false';
	}
	
	die();
}
*/

$sp = mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `st`.`id` = `u`.`id` WHERE `u`.`no_ip` = "trupojor" LIMIT 100');
while($pl = mysql_fetch_array($sp)) {
	$act = 0;
	if($pl['online'] < time()-60) {
		$pl['online'] = time();
		mysql_query('UPDATE `users` SET `online` = "'.$pl['online'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	}
	if($pl['res_x'] < time()) {
		//Можно действовать!
		$mon = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_monsters` WHERE `uid` = "'.$pl['id'].'" LIMIT 1'));
		if( isset($mon['id']) ) {
			if( testMonster($mon,'start') == true && $pl['room'] == 303 ) {
				$pl['room'] = $mon['start_room'];
				mysql_query('UPDATE `users` SET `room` = "'.$pl['room'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `hpNow` = "1000000000000",`mpNow` = "1000000000000" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				if( $mon['start_text'] != '' ) {
					mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>Внимание!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$pl['login'].'</b> ['.$pl['level'].']<a target=_blank href=info/'.$pl['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>',$mon['start_text'])).'","'.$pl['city'].'","","6","1","'.time().'")');
				}
				$act = 1;
			}
		}else{
			mysql_query('UPDATE `stats` SET `res_x` = "'.(time()+3600).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
	}
	if( $act == 0 && $pl['room'] != 303 && $pl['battle'] == 0 ) {
		if(!isset($mon['id'])) {
			$mon = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_monsters` WHERE `uid` = "'.$pl['id'].'" LIMIT 1'));
		}
		if( isset($mon['id']) ) {
			if( testMonster($mon,'back') == true ) {
				$pl['room'] = 303;
				mysql_query('UPDATE `users` SET `room` = "'.$pl['room'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `hpNow` = "1000000000000",`mpNow` = "1000000000000" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				if( $mon['back_text'] != '' ) {
					mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>Внимание!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$pl['login'].'</b> ['.$pl['level'].']<a target=_blank href=info/'.$pl['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>',$mon['back_text'])).'","'.$pl['city'].'","","6","1","'.time().'")');
				}
				$act = 2;
			}
		}
	}
	/*if($pl['battle'] > 0) {
		//inuser_go_atack($pl);
	}else{
		if($pl['room'] == 303 && $pl['timeGo'] < time()) {
			if($pl['res_x'] < time()) {
				$pl['room'] = $pl['invBlock'];
				mysql_query('UPDATE `users` SET `room` = "'.$pl['room'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `hpNow` = "1000000000000",`mpNow` = "1000000000000" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>Внимание!</font> <b>'.$pl['login'].'</b> ['.$pl['level'].']<a target=_blank href=info/'.$pl['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a> выбрался на охоту, будьте осторожны!","'.$pl['city'].'","","6","1","'.time().'")');
			}
		}
	}*/
}
?>