<?
if( isset($s[1]) && $s[1] == '15/shaiba' ) {
	/*
		Сундук: Сундук Стража, можно найти
		"Верхняя Часть Ключа Портала"  - 4443
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	
	$r = 'Шайба у вас! Забейте её в ворота противника!';
	
	//Удаляем шайбу
	mysql_query('DELETE FROM `dungeon_obj` WHERE `id` = "'.$obj['id'].'" LIMIT 1');
	
	//Добавляем шайбу в инвентарь игрока
	$vad['itm'] = $u->addItem(4910,$u->info['id']);
	if($vad['itm'] > 0) {
		mysql_query('UPDATE `items_users` SET `gift` = "Шайба" WHERE `id` = "'.$vad['itm'].'" LIMIT 1');
	}
	$this->sys_chat('<b>'.$u->info['login'].'</b> перехватил Шайбу!');
	
	unset($vad);
}
?>