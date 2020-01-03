<?php

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}



if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }

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
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
//include('_incl_data/class/__user.php');

function send($text) {
	return mysql_query('INSERT INTO `chat` (`new`,`type`,`to`,`text`,`time`) VALUES (1,6,"Игромир","'.$text.'",'.time().')');
}

$i = 7;
while($i <= 11) {
	$t = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `city` = "capitalcity" AND `level` = "'.$i.'" LIMIT 1'));
	if(isset($t['id'])) {
		//send('Турнир ['.$i.'] - status'.$t['status'].'');
		if($t['status'] == 1) {
			//Турнир идет сейчас
			$users = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `room` = "264" AND `login` NOT LIKE "%клон%" AND `lose` = "0" AND `nich` = "0" LIMIT 1'));
			if($users[0] < 2) {
				//Турнир завершился
				//завершаем турнир
				mysql_query('UPDATE `bs_turnirs` SET `status` = "0",`users_finish` = "0",`money` = "0",`time_start` = "'.(time()+$t['time_out']*60).'",`users` = "0" WHERE `id` = "'.$t['id'].'" LIMIT 1');
				
				//удаляем ботов в которых вселились
				$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$t['id'].'" AND `time` = "'.$t['time_start'].'" AND `finish` = "0" ORDER BY `money` DESC LIMIT 100');	
				while($pl = mysql_fetch_array($sp))
				{
					$ur = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
					if(isset($ur['id']))
					{
						//del
						mysql_query('DELETE FROM `users` WHERE `id` = "'.$ur['inUser'].'" LIMIT 1');
						mysql_query('DELETE FROM `stats` WHERE `id` = "'.$ur['inUser'].'" LIMIT 1');	
						mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$ur['inUser'].'" LIMIT 1');	
						mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$ur['inUser'].'" LIMIT 1');	
						//upd
						mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$pl['uid'].'" LIMIT 1');					
					}
				}
				mysql_query('DELETE FROM `dungeon_now` WHERE `bsid` = "'.$t['inUser'].'" AND `time_start` = "'.$t['time_start'].'" LIMIT 1');
				//Визуальные обновления
				unset($tz);
				$t['status'] = 0;
				$t['money'] = 0;
				$t['users'] = 0;
				$t['time_start'] = (time()+$t['time_out']*60);	
				send('Турнир ['.$i.'] - '.$users[0].' - Турнир завершился');
			}else{
				//Турнир еще идет
				
			}
		}else{
			//Ожидаем начала турнира
				
		}
	}
	$i++;
}
?>