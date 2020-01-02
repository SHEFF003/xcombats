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
include('_incl_data/class/__dungeon.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

function send_chat($type,$from,$text,$time) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`login`,`to`,`type`,`new`,`time`,`room`) VALUES ("'.mysql_real_escape_string($text).'","capitalcity","'.mysql_real_escape_string($from).'","","'.$type.'","1","'.mysql_real_escape_string($time).'","3")');
}

//Розыгрыш предметов
$sp = mysql_query('SELECT * FROM `dungeon_items` WHERE `user` = 0 AND `take` = 0');
while( $pl = mysql_fetch_array($sp) ) {
		$fxv = array(
			'itm' => mysql_fetch_array(mysql_query('SELECT `im`.*,`ish`.* FROM `dungeon_items` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`id` = "'.mysql_real_escape_string($pl['id']).'" AND `ish`.`take` = "0" AND `ish`.`delete` = "0" LIMIT 1')),
			'luck_count' => mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$pl['dn'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($pl['id']).'" LIMIT 1')),
			'user_count' => mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `dnow` = "'.$pl['dn'].'" LIMIT 1'))
		);
		$fxv['luck_count'] = $fxv['luck_count'][0];
		$fxv['user_count'] = $fxv['user_count'][0];
		
		if( $fxv['itm']['user'] > 0 || $fxv['luck_count'] < 1 ) {
			
		}elseif( $fxv['luck_count'] >= $fxv['user_count'] || $fxv['itm']['time']+300 < time() ) {
			$fxv['sp'] = mysql_query('SELECT * FROM `dungeon_actions` WHERE `dn` = "'.$pl['dn'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($pl['id']).'" ORDER BY `vals` DESC LIMIT '.$fxv['luck_count']);
			$fxv['winner'] = array();
			$fxv['win_val'] = 0;
			while( $fxv['pl'] = mysql_fetch_array($fxv['sp']) ) {
				if( $fxv['pl']['vals'] > $fxv['win_val'] ) {
					//Победитель
					unset($fxv['winner']);
					$fxv['winner'][] = $fxv['pl']['uid'];
					$fxv['win_val'] = $fxv['pl']['vals'];
				}elseif( $fxv['pl']['vals'] > 0 && $fxv['pl']['vals'] == $fxv['win_val'] ) {
					//ничья
					$fxv['winner'][] = $fxv['pl']['uid'];
				}
			}
			unset($fxv['pl'],$fxv['sp']);
			if( count($fxv['winner']) > 1 ) {
				//Розыгрыш еще раз между победителями
				$fxv['text'] = 'test2';
			}elseif(count($fxv['winner']) == 1) {
				$fxv['user_win'] = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`sex`,`city`,`room` FROM `users` WHERE `id` = "'.$fxv['winner'][0].'" LIMIT 1'));
				$fxv['text'] = '<b>'.$fxv['user_win']['login'].'</b> выигрывает в споре за предмет &quot;'.$fxv['itm']['name'].'&quot;';
				mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$pl['dn']."','".$fxv['user_win']['city']."','".$fxv['user_win']['room']."','','','".$fxv['text']."','".time()."','6','0','1','1')");
				mysql_query('UPDATE `dungeon_items` SET `time` = "'.time().'",`user` = "'.$fxv['user_win']['id'].'" WHERE `id` = "'.$fxv['itm']['id'].'" LIMIT 1');
			}else{
				//Любой может подобрать предмет
				mysql_query('UPDATE `dungeon_items` SET `user` = "1" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			}
	}
	unset($fxv);
}
?>