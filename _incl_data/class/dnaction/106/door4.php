<?
if( isset($s[1]) && $s[1] == '106/door4' ) {
	/*
		Сундук: Кровавый подземник (4561)
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
		
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		$vad['qst'] = mysql_fetch_array(mysql_query('SELECT * FROM `dialog_act` WHERE `uid` = "'.$u->info['id'].'" AND `var` = "noobqst1" AND `val` = 1 LIMIT 1'));
		$vad['qst2'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "4703" AND `delete` = 0 LIMIT 1'));
		if(!isset($vad['qst']['id']) && $vad['qst2'][0] > 0) {
			$r = 'Дверь закрыта. Сначала выполните задания Учителя и потратьте все свои Жетоны.';
		}else{
			mysql_query('UPDATE `stats` SET `x` = 0,`y` = 5 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			header('location: main.php');
			die();
		}
	}
	
	unset($vad);
}
?>