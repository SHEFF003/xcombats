<?
if( isset($s[1]) && $s[1] == '104/sunduk1' ) {
	/*
		Сундук: Сундук
		* Можно найти Сталь , Мифрил , Пустые бутылки
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" LIMIT 1'));
	$vad['test2'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	if( $vad['test2'][0] > 0 ) {
		$r = 'Вы уже обыскали &quot;'.$obj['name'].'&quot;...';
		$vad['go'] = false;
	}elseif( $vad['test1'][0] > 1 ) {
		$r = 'Кто-то обыскал &quot;'.$obj['name'].'&quot; раньше вас...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		//Выкидываем бутылку, мифрил, либо сталь
		$vad['items'] = array(
		724,724,724,724,724,724,724,724,724,724,724,724,724,724,
		2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,
		4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,4709,
		4710,4710,4710,4710,4710,4710,4710,4710,4710,4710,
		4711,4711,4711,4711,4711,
		4712,4712,4712,4712,4713,
		724,724,724,724,724,724,724,724,724,724,724,724,724,724,724,724,
		2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390,2390		
		);
		
		$vad['items'] = $vad['items'][rand(0,count($vad['items'])-1)];
		if( $vad['items'] != 724 && rand(0,100) < 31 ) {
			$vad['items'] = 724;	
		}
		if( $vad['items'] != 0 ) {
			//Выбрасываем предмет
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'",""
			)');
			if( !isset($vad['dn_delete'][$vad['items']]) ) {
				$vad['dn_delete'][$vad['items']] = false;
			}
			if( $this->pickitem($obj,$vad['items'],$u->info['id'],'') ) {
				$r = 'Вы обнаружили предметы...';
			}else{
				$r = 'Что-то пошло не так, предметы растворились...';
			}
		}else{
			$r = 'Вы не нашли ничего полезного...';
		}
	}
	
	unset($vad);
}
?>