<?
if( isset($s[1]) && $s[1] == '1/hramstok1' ) {
	/*
		Сундук: Водосток для сбора проб
		* Заменяет предмет Пустая склянка (4693) на (4694) Склянка с пробами
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
	
	$vad['itm'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = 4693 AND `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
	if(!isset($vad['itm']['id'])) {
		$r = 'У вас нет подходящего предмета для хранения проб... Все руки в каком-то говне...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		mysql_query('UPDATE `items_users` SET `item_id` = 4694 WHERE `id` = "'.$vad['itm']['id'].'" LIMIT 1');
		$r = 'Вы успешно взяли пробы с водостока! Набранную склянку нужно отнести обратно, к Арквиерро в Храм Знаний.';
	}
	
	unset($vad);
}
?>