<?
if( isset($s[1]) && $s[1] == '106/door1' ) {
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
		if(!isset($vad['qst']['id'])) {
			$r = 'Дверь закрыта. Сначала выполните задания Учителя.';
		}else{
			mysql_query('UPDATE `stats` SET `x` = -2,`y` = 6,`s` = 1 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			header('location: main.php');
			die();
		}
	}
	
	unset($vad);
}
?>