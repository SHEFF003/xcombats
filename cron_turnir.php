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

define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
include('_incl_data/class/__magic.php');
//
$sp = mysql_query('SELECT * FROM `battle` WHERE `otmorozok` = 1 AND `team_win` = -1 AND `otmorozok_use` = 0');
while( $pl = mysql_fetch_array($sp) ) {
	if( rand( 0 , 100 ) < 11 ) {
		//
		mysql_query('UPDATE `battle` SET `otmorozok_use` = 1 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		//
		$usr = mysql_fetch_array(mysql_query('SELECT `level`,`city` FROM `users` WHERE `battle` = "'.$pl['id'].'" ORDER BY `level` DESC LIMIT 1'));
		$bot = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` LIKE "%Отморозок [%'.$usr['level'].'%]%" LIMIT 1'));
		//
		$tmr = rand(1,2);
		//
		$logins_bot = array();
		$bot = $u->addNewbot($bot['id'],NULL,NULL,$logins_bot,NULL);
		$otmz = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `login` LIKE "%Отморозок (%" AND `battle` = "'.$pl['id'].'" LIMIT 1'));
		//
		$otmz[0]++;
		//
		mysql_query('UPDATE `users` SET `city` = "'.$usr['city'].'",`login` = "Отморозок ('.$otmz[0].')",`battle` = "'.$pl['id'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
		//
		mysql_query('UPDATE `stats` SET `team` = "'.$tmr.'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
		//
		$vtvl = '{tm1} {u1} вмешался в поединок. Хо! хо! хо!';
		$last_hod = mysql_fetch_array(mysql_query('SELECT `id_hod` FROM `battle_logs` WHERE `battle` = "'.$pl['id'].'" ORDER BY `id_hod` DESC LIMIT 1'));
		$last_hod = $last_hod['id_hod'];
		//
		$mass = array(
			'time' 		=> time(),
			'battle' 	=> $pl['id'],
			'id_hod' 	=> ($last_hod+1),
			'vars' 		=> '||time1='.time().'||time2=0||s1=0||t1='.$tmr.'||login1=Отморозок ('.$otmz[0].')',
			'type' 		=> 1			
		);
		//
		$ins = mysql_query('INSERT INTO `battle_logs` (
			`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`
		) VALUES (
			"'.$mass['time'].'",
			"'.$mass['battle'].'",
			"'.$mass['id_hod'].'",
			"'.$vtvl.'",
			"'.$mass['vars'].'",
			"",
			"",
			"",
			"",
			"'.$mass['type'].'"
		)');
	}
}
//

die();

//Подаем турнир для 2-3 уровней
$inc = mysql_query('INSERT INTO `zayvki` (
	`arand`,`noatack`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`noinc`,`razdel`,`time`,`fastfight`,`priz`
) VALUES (
	"1","1","capitalcity","0","0","300","120","2","2","3","3","1","5","'.time().'","1","1"
), (
	"1","1","capitalcity","0","0","300","120","4","4","6","6","1","5","'.time().'","1","1"
), (
	"1","1","capitalcity","0","0","300","120","7","7","7","7","1","5","'.time().'","1","1"
), (
	"1","1","capitalcity","0","0","300","120","8","8","8","8","1","5","'.time().'","1","1"
), (
	"1","1","capitalcity","0","0","300","120","9","9","9","9","1","5","'.time().'","1","1"
), (
	"1","1","capitalcity","0","0","300","120","10","10","10","10","1","5","'.time().'","1","1"
)');

if($inc) { 
	echo 'true';
}else{
	echo 'false';
}
?>