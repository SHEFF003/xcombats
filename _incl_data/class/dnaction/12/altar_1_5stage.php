<?
if( isset($s[1]) && $s[1] == '12/altar_1_5stage' ) {
	/*
		Сундук: Алтарь Осколков
		# '4443', '4444', '4445' = 4446
		# '4517', '4518', '4519', '4520', '4521' = 4522
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go_p' => true,
		'go_r' => true,
		'portal' => array(0=>false, 1=>false, 2=>false),
		'portal_items' => array(0=>false, 1=>false, 2=>false),
		'rune' => array(0=>false, 1=>false, 2=>false, 3=>false, 4=>false),
		'rune_items' => array(0=>false, 1=>false, 2=>false, 3=>false, 4=>false)
	);
	 
	$vad['portal_q'] = mysql_query('SELECT * FROM `items_users` WHERE (`item_id` = "4443" OR `item_id` = "4444" OR `item_id` = "4445") AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" GROUP BY item_id LIMIT 3');
	while($vad['sp'] = mysql_fetch_array($vad['portal_q']) ){
		if($vad['sp']['item_id'] == '4443'){
			$vad['portal'][0] = true;
			$vad['portal_items'][0] = $vad['sp']['id'];
		} elseif($vad['sp']['item_id'] == '4444'){
			$vad['portal'][1] = true;
			$vad['portal_items'][1] = $vad['sp']['id'];
		} elseif($vad['sp']['item_id'] == '4445'){
			$vad['portal'][2] = true;
			$vad['portal_items'][2] = $vad['sp']['id'];
		}
	}
	
	$vad['rune_q'] = mysql_query('SELECT * FROM `items_users` WHERE (`item_id` = "4517" OR `item_id` = "4518" OR `item_id` = "4519" OR `item_id` = "4520" OR `item_id` = "4521") AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" GROUP BY item_id LIMIT 5');
	while($vad['sp'] = mysql_fetch_array($vad['rune_q'])){
		if($vad['sp']['item_id'] == '4517'){
			$vad['rune'][0] = true;
			$vad['rune_items'][0] = $vad['sp']['id'];
		} elseif($vad['sp']['item_id'] == '4518'){
			$vad['rune'][1] = true;
			$vad['rune_items'][1] = $vad['sp']['id'];
		} elseif($vad['sp']['item_id'] == '4519'){
			$vad['rune'][2] = true;
			$vad['rune_items'][2] = $vad['sp']['id'];
		}  elseif($vad['sp']['item_id'] == '4520'){
			$vad['rune'][3] = true;
			$vad['rune_items'][3] = $vad['sp']['id'];
		}  elseif($vad['sp']['item_id'] == '4521'){
			$vad['rune'][4] = true;
			$vad['rune_items'][4] = $vad['sp']['id'];
		} 
	}
	if( isset($vad['portal']) AND $vad['portal'][2]==true AND $vad['portal'][1]==true AND $vad['portal'][0]==true ){
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","altar_keyportal'.$obj['id'].'","'.$vad['bad'].'"
		)');
		foreach($vad['portal_items'] as $vad['row']){
			$u->deleteItem($vad['row'],$u->info['id'],1);
		}
		$this->pickitem($obj,4446,$u->info['id'],'|sudba=-1'); 
		$r = 'Вы использовали &quot;'.$obj['name'].'&quot; и собрали &quot;Ключ Портала&quot; из трех частей...';
	} else {
		$vad['go_p'] == false;
	} 
	if( isset($vad['rune']) AND $vad['rune'][4]==true AND $vad['rune'][3]==true AND $vad['rune'][2]==true AND $vad['rune'][1]==true AND $vad['rune'][0]==true ){
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","altar_keyrune'.$obj['id'].'","'.$vad['bad'].'"
		)');
		foreach($vad['rune_items'] as $vad['row']){ 
			$u->deleteItem($vad['row'],$u->info['id'],1);
		} 
		$this->pickitem($obj,4522,$u->info['id'],'|sudba=-1', true);
		if( !isset($r) ) $r ='';
		$r .= 'Вы использовали &quot;'.$obj['name'].'&quot; и собрали &quot;Отпирающая руна&quot; из пяти частей...'; 
	} else {
		$vad['go_r'] == false;
	}
	if( $vad['go_r']==false && $vad['go_p']==false ) {
		$r = 'Ничего не получилось... ';
	}
	
	unset($vad);
}
?>