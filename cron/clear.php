<?php
define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('/var/www/.com/data/www/.com/_incl_data/__config.php');
include('/var/www/.com/data/www/.com/_incl_data/class/__db_connect.php');
include('/var/www/.com/data/www/.com/_incl_data/class/__user.php');

if( isset($_GET['actions']) ) {
	//
	$data = array();
	//
	$sp = mysql_query('SELECT * FROM `actions`');
	while( $pl = mysql_fetch_array($sp) ) {
		$pl['vars'] = str_replace(0,'%',$pl['vars']);
		$pl['vars'] = str_replace(1,'%',$pl['vars']);
		$pl['vars'] = str_replace(2,'%',$pl['vars']);
		$pl['vars'] = str_replace(3,'%',$pl['vars']);
		$pl['vars'] = str_replace(4,'%',$pl['vars']);
		$pl['vars'] = str_replace(5,'%',$pl['vars']);
		$pl['vars'] = str_replace(6,'%',$pl['vars']);
		$pl['vars'] = str_replace(7,'%',$pl['vars']);
		$pl['vars'] = str_replace(8,'%',$pl['vars']);
		$pl['vars'] = str_replace(9,'%',$pl['vars']);
		$data[$pl['vars']]++;
	}
	//
	print_r($data);
	//
	die();
}

/*

	CRON Очистки сервера от ненужной информации
	Действия:
	1.  Очистка чата
	2.	Очистка заявок в поединки
	3.	Очистка заявок в пещеры
	4.	Очистка походов

*/

function delete_user_all( $uid , $login ) {
			mysql_query('DELETE FROM `aaa_birthday` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `aaa_bonus` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `aaa_dialog_vars` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `aaa_znahar` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `add_smiles` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `an_data` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `a_com_act` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `a_noob` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `a_system` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `a_vaucher` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `a_vaucher_active` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `bandit` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `bank` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `bank_alh` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `battle_act` WHERE `uid1` = "'.$uid.'" OR `uid2` = "'.$uid.'"');
			mysql_query('DELETE FROM `battle_actions` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `battle_cache` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `battle_last` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `battle_stat` WHERE `uid1` = "'.$uid.'" OR `uid2` = "'.$uid.'"');
			mysql_query('DELETE FROM `battle_users` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `bid` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `bs_actions` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `bs_zv` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `building` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `buy_ekr` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `chat_ignore` WHERE `uid` = "'.$uid.'" OR `login` = "'.$login.'"');
			mysql_query('DELETE FROM `complects_priem` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `dialog_act` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `dump` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `dungeon_actions` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `ekr_sale` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `feerverks` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `fontan` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `fontan_hp` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `fontan_text` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `friends` WHERE `user` = "'.$uid.'"
			OR `friend` = "'.$uid.'"
			OR `enemy` = "'.$uid.'"
			OR `notinlist` = "'.$uid.'"
			OR `ignor` = "'.$uid.'"
			OR `login_ignor` = "'.$login.'"
			OR `user_ignor` = "'.$login.'"');
			mysql_query('DELETE FROM `house` WHERE `owner` = "'.$uid.'"');
			mysql_query('DELETE FROM `items_img` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `izlom_rating` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `laba_act` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `laba_itm` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `lastnames` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `logs_auth` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `loto_win` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `mults` WHERE `uid` = "'.$uid.'" OR `uid2` = "'.$uid.'"');
			mysql_query('DELETE FROM `notepad` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$uid.'" OR `login` = "'.$login.'"');
			mysql_query('DELETE FROM `online` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `pirogi` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `post` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `reimage` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `rep` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `repass` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `ruletka_coin` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `save_com` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `stats` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `transfers` WHERE `uid1` = "'.$uid.'" OR `uid2` = "'.$uid.'"');
			mysql_query('DELETE FROM `users` WHERE `id` = "'.$uid.'"');
			mysql_query('DELETE FROM `stats` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `users_animal` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `users_gifts` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `users_reputation` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `users_turnirs` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `users_twink` WHERE `uid` = "'.$uid.'"');
			mysql_query('DELETE FROM `zayavki` WHERE `creator` = "'.$uid.'"');
			mysql_query('DELETE FROM `_clan` WHERE `uid` = "'.$uid.'"');
}

//1. Очистка чата, остается чат только за последние 3 дня
mysql_query('DELETE FROM `chat` WHERE `time` < '.(time()-86400*3).'');

//2. Очистка заявок в поединки
mysql_query('DELETE FROM `zayvki` WHERE `start` > 0 OR `cancel` > 0 OR `time` < "'.(time()-86400*1).'"');

//3. Очистка заявок в пещеры
mysql_query('DELETE FROM `dungeon_zv` WHERE `delete` > 0 OR `time` < "'.(time()-86400*1).'"');

//4. Очистка походов
$sp = mysql_query('SELECT * FROM `dungeon_now` WHERE `time_start` < "'.(time()-86400*1).'" OR `time_finish` > 0');
while( $pl = mysql_fetch_array($sp) ) {
	mysql_query('DELETE FROM `dungeon_actions` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_bots` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_items` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_obj` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_now` WHERE `id` = "'.$pl['id'].'"');
}

//5. Очистка монстров
$i = 0;
$sp = mysql_query('SELECT * FROM `users` WHERE `real` = 0 ORDER BY `id` ASC LIMIT 100');
while( $pl = mysql_fetch_array($sp) ) {
	$btl = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$pl['battle'].'" LIMIT 1'));
	$clon = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `inUser` = "'.$pl['id'].'" LIMIT 1'));
	if( (!isset($btl['id']) || $btl['team_win'] >= 0) && !isset($clon['id']) ) {
		//Очищаем бота
		delete_user_all( $pl['id'] , $pl['login'] );
		$i++;
	}
}
$x = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `real` = 0 LIMIT 1'));
$x = $x[0];

mysql_query('DELETE FROM `stats` WHERE `id` NOT IN (SELECT `id` FROM `users`);');
mysql_query('DELETE FROM `items_users` WHERE `delete` > `time_create` AND `delete` > 0');

//6. Очистка личного дела
mysql_query('DELETE FROM `users_delo` WHERE `time` < "'.(time()-86400*30).'" LIMIT 1000');

//echo '<div>Очищено ботов\монстров: '.$i.'/'.$x.'</div>';
/*if( $i > 0 ) {
	die('<script>function test(){ top.location = top.location; } setTimeout("test()",1000);</script>');
}*/
?>