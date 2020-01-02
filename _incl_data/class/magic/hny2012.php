<?
if(!defined('GAME'))
{
	die();
}

//Выдаем предметы, но не раньше 00:00:00 01-01-2013
if(date('Y')==2013 || $u->info['id'] == 1) {
	/*

2. Новогодний Шоколад (полное вост. хп) 
3. Новогодняя Открытка 
4. Зелье -Бойцовский Дух- (мощ урона/магии +10, хп +60) 

7. Новогодний Эликсир
	*/	
	//Снежок 0/13 (x1)
	$idit = $u->addItem(1000,$u->info['id']);
	if($idit > 0) {
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год",`iznosMAX` = "13" WHERE `id` = "'.$idit.'" LIMIT 1');
		
		//Новогодний эликсир (x1)
		$idit = $u->addItem(997,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год",`iznosMAX` = "7" WHERE `id` = "'.$idit.'" LIMIT 1');
		
		//Новогодний эликсир (x1)
		$idit = $u->addItem(2870,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год",`iznosMAX` = "7" WHERE `id` = "'.$idit.'" LIMIT 1');
		
		//Звездное сияние (x1)
		$idit = $u->addItem(1462,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год",`iznosMAX` = "7" WHERE `id` = "'.$idit.'" LIMIT 1');
				
		//Открытка (x1)
		$idit = $u->addItem(996,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Новый Год" , `gtxt1` = "Администрация проекта поздравляет Вас с Новым, 2013, Годом!" WHERE `id` = "'.$idit.'" LIMIT 1');	
		
		//Нападалка 0/13 (x1)
		$idit = $u->addItem(874,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год",`iznosMAX` = "13" WHERE `id` = "'.$idit.'" LIMIT 1');
		
		//Кольцо жизни (x1)
		$idit = $u->addItem(2101,$u->info['id'],'|noremont=1|srok=1209600|sudba='.$u->info['login']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год",`iznosMAX` = "13" WHERE `id` = "'.$idit.'" LIMIT 1');
		
		//Восстановление Энергии 900НР (x3)
		$idit = $u->addItem(2710,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год" WHERE `id` = "'.$idit.'" LIMIT 1');
		$idit = $u->addItem(2710,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год" WHERE `id` = "'.$idit.'" LIMIT 1');
		$idit = $u->addItem(2710,$u->info['id']);
		mysql_query('UPDATE `items_users` SET `gift` = "Старый Год" WHERE `id` = "'.$idit.'" LIMIT 1');
		
		$u->error = 'Вы успешно использовали &quot;'.$itm['name'].'&quot;. В инвентарь добавлены подарки. С Новым, 2013, Годом!';
		mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE (`item_id` = "2763" OR `id` = "'.$itm['id'].'") AND `uid` = "'.$u->info['id'].'" LIMIT 10');
	}
}else{
	$u->error = 'Не удалось использовать &quot;'.$itm['name'].'&quot;. Подарок возможно использовать не раньше 01.01.2013';
}

?>