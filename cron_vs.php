<?php

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

if(!isset($_GET['kill'])) {
	if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }
	if(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '91.228.154.180') {
		die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}

echo '#start#';
define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
include('_incl_data/class/__magic.php');
//
$cfg = array(
	'H' => 17 //время старта по серверу
);
//
function send_chat($type,$from,$text,$time) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`login`,`to`,`type`,`new`,`time`,`room`) VALUES ("'.mysql_real_escape_string($text).'","capitalcity","'.mysql_real_escape_string($from).'","","'.$type.'","1","'.mysql_real_escape_string($time).'","3")');
	echo '[SEND_CHAT]';
}
//
$bot1 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "Мусорщик" LIMIT 1'));
$bot2 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "Мироздатель" LIMIT 1'));
//
$st1 = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `id` = "'.$bot1['id'].'" LIMIT 1'));
$st2 = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `id` = "'.$bot2['id'].'" LIMIT 1'));
//
$td = mysql_fetch_array(mysql_query('SELECT * FROM `vs_stat` WHERE (`d` = "'.date('d').'" AND `m` = "'.date('m').'" AND `y` = "'.date('Y').'") OR `winner` = -1 ORDER BY `time` ASC LIMIT 1'));
//
if(!isset($td['id'])) {
	//Создаем
	$ins = mysql_query('INSERT INTO `vs_stat` (
		`time`,`d`,`m`,`y`,`act`,`type`,`uid`
	) VALUES (
		"'.time().'","'.date('d').'","'.date('m').'","'.date('Y').'","1","0","0"
	)');
	//
	if(!$ins) {
		echo '#ERROR_INSERT_DATA#';
	}else{
		$td = mysql_fetch_array(mysql_query('SELECT * FROM `vs_stat` WHERE `d` = "'.date('d').'" AND `m` = "'.date('m').'" AND `y` = "'.date('Y').'" ORDER BY `time` LIMIT 1'));
	}
	//
}
if(isset($td['id'])) {	
	//
	echo '#type'.$td['type'].'#';
	//
	if( $td['type'] == 0 ) {
		if( date('H') == $cfg['H'] ) {
			send_chat(1,'','<font color=green>Бой бессмертных проходит на Центральной Площади, займите свою сторону в этой битве и получите награду за победу!',time());
			send_chat(1,'','<font color=red><b>Мусорщик</b>: Что-то у меня душа, в последнее время, не на месте</font> :vamp:',time());
			send_chat(1,'','<font color=blue><b>Мироздатель</b>:Я явился чтобы спасти этот мир</font> :grace:',time());	
			//
			mysql_query('INSERT INTO `battle` (
				`city`,`time_start`,`timeout`,`type`,`noinc`
			) VALUES (
				"capitalcity","'.time().'","180","180","1"
			)');
			//
			$btl_id = mysql_insert_id();
			if( $btl_id > 0 ) {
				//
				mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'",`online` = "'.(time()+3600).'" WHERE `id` = "'.$bot1['id'].'" LIMIT 1');
				mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'",`online` = "'.(time()+3600).'" WHERE `id` = "'.$bot2['id'].'" LIMIT 1');
				//
				mysql_query('UPDATE `stats` SET `hpNow` = "1000000",`mpNow` = "1000000",`team` = 1 WHERE `id` = "'.$bot1['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `hpNow` = "1000000",`mpNow` = "1000000",`team` = 2 WHERE `id` = "'.$bot2['id'].'" LIMIT 1');
				//
				mysql_query('UPDATE `vs_stat` SET `type` = "1",`battle` = "'.$btl_id.'" WHERE `id` = "'.$td['id'].'" LIMIT 1');
				//
			}
			//
		}
	}elseif( $td['type'] == 1 ) {
		//
		$bt = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$bot1['battle'].'" AND `id` = "'.$bot2['battle'].'" LIMIT 1'));
		////
		mysql_query('UPDATE `users` SET `online` = "'.(time()+3600).'" WHERE `id` = "'.$bot1['id'].'" LIMIT 1');
		mysql_query('UPDATE `users` SET `online` = "'.(time()+3600).'" WHERE `id` = "'.$bot2['id'].'" LIMIT 1');
		//
		if( $st1['bot'] != 2 ) {
			mysql_query('UPDATE `stats` SET `bot` = "2" WHERE `id` = "'.$bot1['id'].'" LIMIT 1');
		}
		if( $st2['bot'] != 2 ) {
			mysql_query('UPDATE `stats` SET `bot` = "2" WHERE `id` = "'.$bot2['id'].'" LIMIT 1');
		}
		//
		if( $st1['hpNow'] < 1 || $st2['hpNow'] < 1 || $bot1['battle'] == 0 || $bot2['battle'] == 0 ) {
			//Завершаем поединок, кто-то победил
			
		}else{
			//Бой идет, стены гнутся 
			
		}
		//
	}
	//
}
//
//
echo '#finish#';
?>