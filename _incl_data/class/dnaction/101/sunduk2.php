<?
if( isset($s[1]) && $s[1] == '101/sunduk2' ) {
	/*
		Сундук: Сундук
		* Можно найти Отворяющая печать №3	1175
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) { 
		$r = 'Кто-то обыскал &quot;'.$obj['name'].'&quot; раньше вас...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		//Выкидываем Отворяющая печать №3
		$vad['items'] = array(1175);
		
		$vad['items'] = $vad['items'][rand(0,count($vad['items'])-1)];
		if( $vad['items'] != 0 && rand(1,100) < 80) {
			// Выбрасываем предмет
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'",""
			)');

			/*
			//откуда тут сущности? обломятся!
			if( rand(0,100) < 10 ) {
				//Сущность сапог
				$this->pickitem($obj,4279,0);
			}
			if( rand(0,100) < 10 ) {
				//Сущность щита
				$this->pickitem($obj,4269,0);
			}
			*/
			if( $this->pickitem($obj,$vad['items'],0) ) {
				$r = 'Вы обнаружили предметы...';
			}else{
				$r = 'Что-то пошло не так, предметы растворились...';
			}
		} else {
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","false"
			)');
			$r = 'Вы не нашли ничего полезного...';
		}
	}
	
	unset($vad);
}
?>