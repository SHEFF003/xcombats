<?
if( isset($s[1]) && $s[1] == '3/chest_trap' ) {
	/*
		Сундук:
		* Можно получить Рандомно вещь 4-8 лвл
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
		$vad['items'] = array();
		$vad['sp'] = mysql_query('SELECT `id` FROM `items_main` WHERE `type` >= 1 AND `type` <= 22 AND `id` IN (SELECT `item_id` FROM `items_shop` WHERE `sid` = 1 AND `kolvo` > 0) AND `id` IN (SELECT `items_id` FROM `items_main_data` WHERE `data` NOT LIKE "%tr_lvl=1%" AND `data` NOT LIKE "%tr_lvl=2%" AND `data` NOT LIKE "%tr_lvl=3%")');
		while( $vad['pl'] = mysql_fetch_array($vad['sp']) ) {
			$vad['items'][] = $vad['pl']['id'];
		}
		$vad['items'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($vad['items'][rand(0,count($vad['items'])-1)]).'" LIMIT 1'));
		$r = 'Обыскав &quot;'.$obj['name'].'&quot; вы обнаружили &quot;'.$vad['items']['name'].'&quot;';
		$this->pickitem($obj,$vad['items']['id'],$u->info['id'],'');
	}
	unset($vad);
}
?>