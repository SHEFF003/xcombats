<?
if( isset($s[1]) && $s[1] == '1/vods1' ) {
	/*
		Сундук: Водосток
		* падает гайка
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = 'Кто-то обыскал &quot;'.$obj['name'].'&quot; до вас...';
		$vad['go'] = false;
	}
		
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		//mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$vad['itm']['id'].'" LIMIT 1');
		$this->pickitem($obj,1002,0,'',false);
		$r = 'Вы обнаружили предмет &quot;Гайка&quot;.';
	}
	
	unset($vad);
}
?>