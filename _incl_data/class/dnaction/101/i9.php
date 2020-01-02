<?
if( isset($s[1]) && $s[1] == '101/i9' ) {
	/*
		Сундук: ловушка
		* Снимает до 1000 НР один раз
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => false
	);
	
	$vad['test'] = mysql_fetch_array(mysql_query('SELECT `id`,`uid` FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( !isset($vad['test']['id']) ) {
		$vad['go'] = true;
	}else{
		$r = 'В сундуке была ловушка, её активировал персонаж '.$u->microLogin($vad['test']['uid'],1);
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'",""
		)');
		$r = 'В сундуке была ловушка установленная одним из обитателей подземелья!';
		$vad['min_hp'] = rand(100,1000);
		$u->stats['hpNow'] -= $vad['min_hp'];
		if( $u->stats['hpNow'] < 0 ) {
			$u->stats['hpNow'] = 0;
		}
		if($u->info['sex'] == 0) {
			$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> угодил в ловушку оставленную в &quot;'.$obj['name'].'&quot;. <b>-'.$vad['min_hp'].'</b> ['.floor($u->stats['hpNow']).'/'.round($u->stats['hpAll']).']';
		}else{
			$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> угодила в ловушку оставленную в &quot;'.$obj['name'].'&quot;. <b>-'.$vad['min_hp'].'</b> ['.floor($u->stats['hpNow']).'/'.round($u->stats['hpAll']).']';
		}
		$this->sys_chat($vad['text']);
		$u->info['hpNow'] = $u->stats['hpNow'];
		mysql_query('UPDATE `stats` SET `regHP` = "'.time().'",`hpNow` = "'.$u->stats['hpNow'].'" WHERE `id` = "'.$u->stats['id'].'" LIMIT 1');
		//
		$this->testDie();
	}
	unset($vad);
}
?>