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

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

function send_chat($type,$from,$text,$time) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`login`,`to`,`type`,`new`,`time`,`room`) VALUES ("'.mysql_real_escape_string($text).'","capitalcity","'.mysql_real_escape_string($from).'","","'.$type.'","1","'.mysql_real_escape_string($time).'","3")');
}

$kp = array(
	0 => 1,
	1 => 1,
	2 => 3,
	3 => 3,
	4 => 3,
	5 => 7,
	6 => 7,
	7 => 7,
	8 => 14,
	9 => 14,
	10 => 30,
	11 => 30,
	12 => 30,
	13 => 30,
	14 => 30,
	15 => 30,
	16 => 60,
	17 => 60,
	18 => 60,
	19 => 60,
	20 => 60,
	21 => 60
);

$kp = array(
	0 => 1,
	1 => 1,
	2 => 3,
	3 => 3,
	4 => 3,
	5 => 7,
	6 => 7,
	7 => 7,
	8 => 90,
	9 => 90,
	10 => 90,
	11 => 90,
	12 => 90,
	13 => 90,
	14 => 90,
	15 => 90,
	16 => 90,
	17 => 90,
	18 => 90,
	19 => 90,
	20 => 90,
	21 => 90
);

function addUser($userData) {
	$query = "INSERT INTO `users_kill` ( ".
          mysql_real_escape_string(implode(' , ', array_keys($userData))).
          " ) VALUES ( '".
          (implode("' , '", $userData)).
          "' )";

  return $query;
}

//Удаляем эффекты и предметы (не нужные)
mysql_query('DELETE FROM `eff_users` WHERE `delete` > "1392211522" AND `delete` < "'.time().'"');
mysql_query('DELETE FROM `items_users` WHERE `delete` > "1392211522" AND `delete` < "'.time().'"');

$lvl = 0;
while( $lvl <= 21 ) {
	$last_time = round( time() - ( 2 * $kp[$lvl] * 86400 ) );
	$sp = mysql_query('SELECT `u`.*,`s`.*,`u`.`id` AS `id1`,`s`.`id` AS `id2` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON ( `s`.`id` = `u`.`id` AND `s`.`bot` = 0 ) WHERE `u`.`admin` = 0 AND `u`.`pass` != "" AND `u`.`no_ip` = "" AND `u`.`level` = "'.$lvl.'" AND `align` != 50 AND `u`.`online` < '.$last_time.' LIMIT 1000');
	while($pl = mysql_fetch_array($sp)) {
		
		if( $pl['id'] < 1 ) {
			if( $pl['id1'] > 0 ) {
				$pl['id'] = $pl['id1'];
			}elseif( $pl['id2'] > 0 ) {
				$pl['id'] = $pl['id2'];
			}
		}
		
		//Собираем данные сколько ценностей было на персонаже
		$pl['bank'] = mysql_fetch_array(mysql_query('SELECT SUM(`money1`),SUM(`money2`) FROM `bank` WHERE `uid` = "'.$pl['id'].'" LIMIT 1'));
		$pl['money'] += $pl['bank'][0];
		$pl['money2'] += $pl['bank'][1];
		$sitm = mysql_query('SELECT `id`,`item_id`,`1price`,`2price` FROM `items_users` WHERE (`delete` = 1000 OR `delete` = 0) AND `delete` = "0" AND `data` NOT LIKE "%|frompisher=%" AND `uid` = "'.$pl['id'].'"');
		while($pitm = mysql_fetch_array($sitm)) {
			if( $pitm['1price'] != 0 ) {
				$pl['money'] += $pitm['1price'];
			}elseif( $pitm['2price'] != 0 ) {
				$pl['money'] += $pitm['2price'];
			}else{
				$pitems = mysql_fetch_array(mysql_query('SELECT `price1`,`price2` FROM `items_main` WHERE `id` = "'.$sitm['item_id'].'" LIMIT 1'));
				if( $pitems['price2'] > 0 ) {
					$pl['money'] += $pitems['price2'];
				}else{
					$pl['money2'] += $pitems['price1'];
				}
			}
		}
		if($pl['id'] > 0) {
			//Удаляем все данные о персонаже на проекте
			mysql_query('DELETE FROM `aaa_znahar` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `add_smiles` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_com_act` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_noob` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_system` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_vaucher` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_vaucher_active` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bank` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bank_alh` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_cache` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bid` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bs_actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bs_zv` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `complects_priem` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `dump` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `dungeon_actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `dungeon_now` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `dungeon_zv` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `feerverks` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `fontan` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `fontan_hp` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `friends` WHERE `user` = "'.$pl['id'].'" OR `friend` = "'.$pl['id'].'" OR `enemy` = "'.$pl['id'].'" OR `notinlist` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `house` WHERE `owner` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `items_img` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `izlom_rating` WHERE `uid` = "'.$pl['id'].'"');
			//mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `online` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `post` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `reimage` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `rep` WHERE `id` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `repass` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `ruletka_coin` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `save_com` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `stats` WHERE `id` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `telegram` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `transfers` WHERE `uid1` = "'.$pl['id'].'" OR `uid2` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users` WHERE `id` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_animal` WHERE `uid` = "'.$pl['id'].'"');
			//mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_turnirs` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `zayvki` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `_clan` WHERE `uid` = "'.$pl['id'].'"');
			echo '&bull;'.$pl['login'].'<br>';
		}
		
		//Заносим данные в базу
		$usrData = array(
			'`uid`' => $pl['id'],
			'`money1`' => $pl['money'],
			'`money2`' => $pl['money2'],
			'`money4`' => $pl['money4'],
			'`ip`' => $pl['ip'],
			'`timereg`' => $pl['timereg'],
			'`ipreg`' => $pl['ipreg'],
			'`sex`' => $pl['sex'],
			'`login`' => $pl['login'],
			'`pass`' => $pl['pass'],
			'`mail`' => $pl['mail'],
			'`level`' => $pl['level'],
			'`exp`' => $pl['exp'],
			'`online`' => $pl['online'],
			'`time_kill`' => time(),
			'`align`' => $pl['align'],
			'`clan`' => $pl['clan'],
			'`banned`' => $pl['banned'],
			'`win`' => $pl['win'],
			'`lose`' => $pl['lose'],
			'`nich`' => $pl['nich'],
			'`marry`' => $pl['marry'],
			'`send`' => $pl['send'],
			'`activ`' => $pl['activ'],
			'`name`' => $pl['name'],
			'`obraz`' => $pl['obraz'],
			'`bithday`' => $pl['bithday'],
			'`host_reg`' => $pl['host_reg']
		);
		mysql_query(addUser($usrData));
	}
	$lvl++;
}
?>