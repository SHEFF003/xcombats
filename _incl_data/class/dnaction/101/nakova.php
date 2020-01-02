<?
if( isset($s[1]) && $s[1] == '101/nakova' ) {
	/*
		Сундук: Наковальня
		* Можно создать сущность х2 с судьбой
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = 'Кто-то уже разбил сущность за этот поход, наковальня сломана...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		$vad['sp'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "1035" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
		if( isset($vad['sp']['id']) ) {
			$vad['pl'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$vad['sp']['item_id'].'" LIMIT 1'));
		}else{
			$vad['go'] = false;
		}
		if( $vad['go'] == true ) {
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
			)');
			//Награда
			$u->deleteItem($vad['sp']['id'],$u->info['id'],1);
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$vad['sp']['id'].'" LIMIT 1');
			$this->pickitem($obj,1035,$u->info['id'],'|sudba=-1');
			$this->pickitem($obj,1035,$u->info['id'],'|sudba=-1');
			$r = 'Вы использовали &quot;'.$obj['name'].'&quot; и разбили &quot;Сущность&quot; на две части';
		}elseif( !isset($vad['sp']['id']) ) {
			$r = 'Для использования необходим предмет &quot;Сущность Ресурса&quot;';
		}
	}
	
	unset($vad);
}
?>