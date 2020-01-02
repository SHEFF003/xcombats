<?
if( isset($s[1]) && $s[1] == '106/door3' ) {
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
		
		$vad['itm'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = 724 OR `item_id` = 4704) LIMIT 1'));
		
		if(!isset($vad['qst']['id']) || !isset($vad['itm']['id'])) {
			$r = 'Дверь закрыта. Сначала выполните задания Учителя и купите Футболку с Эликсиром.';
		}else{
			//Начало обучения
			$humor = array(
				0 => array(
					':maniac: Сильно не бейте ;)',':beggar: Будет попрошайничать - бейте!',':pal: Возможно светлый!',
					':vamp: Возможно темный!',':susel: Судя по здоровенному бицепсу - это мужик!',':duel: И сразу же кинулся в бой!',
					':friday: Не долго думав он начал искать собутыльника!',':doc: Лекарь: Новичок! Да, да! Ты! Если тебя сломают - у знахаря тебя соберут обратно!'
				),
				1 => array(
					':maniac: Помните! Девочек не бьем ;)',':nail: Она красит ногти, не отвлекайте все сразу ;)',':pal: Возможно светлая!',
					':vamp: Возможно темная!',':rev: Судя по красивой одежде - это женщина!',':hug: И сразу же кинулась всех целовать!',
					':angel2: Ангел сошел с небес...'
				)
			);
			$humor = $humor[$u->info['sex']];
			//$u->info['fnq'] = 1;
			//mysql_query('UPDATE `users` SET `fnq` = "'.$u->info['fnq'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			//Отправляем сообщение в чат о новичке
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `item_id` = 4703');
			mysql_query('UPDATE `users` SET `room` = 4 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `hpNow` = 1000,`mpNow` = 1000,`dn` = 0 , `dnow` = 0 , `x` = 0 , `y` = 0 , `s` = 0 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->send('','','','','','В нашем мире появился новый игрок &quot;<b>' . $u->info['login'] . '</b>&quot;! '.$humor[rand(0,count($humor)-1)].'',time(),6,0,0,0,1,0);
			//mysql_query('UPDATE `stats` SET `x` = 0,`y` = 5 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			header('location: main.php');
			die();
		}
	}
	
	unset($vad);
}
?>