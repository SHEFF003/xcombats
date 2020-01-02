<?
if( isset($s[1]) && $s[1] == '101/krovat' ) {
	/*
		Сундук: Кровать
		* Можно получить один из двух ресурсов
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
		$vad['items'] = array(2,4036,2413,2414,2415,2416,1189,2,4036,2413,2414,2415,2416,1189,2,4036,2413,2414,2415,2416,1189,2412);
		$vad['dn_delete'] = array(
			1189 => true
		);
		$vad['items'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($vad['items'][rand(0,count($vad['items'])-1)]).'" LIMIT 1'));
		$r = 'Обыскав &quot;'.$obj['name'].'&quot; вы обнаружили &quot;'.$vad['items']['name'].'&quot;';
		$this->pickitem($obj,$vad['items']['id'],$u->info['id'],'',$vad['dn_delete'][$vad['items']['id']]);
	}
	
	unset($vad);
}
?>