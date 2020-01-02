<?
if(!defined('GAME'))
{
	die();
}

	$add_zb = 0;
	$add_nas = 0;
	
	$refer = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned`,`admin`,`level` FROM `users` WHERE `id` = "'.mysql_real_escape_string($this->info['host_reg']).'" LIMIT 1'));
	
	
	if($tr['var_id'] == 1) {
		// Набор [0]
		$add_zb = 5;
		
			$add_nas = 1;
			
			//Накидка
			$i3 = $this->addItem(3200,$this->info['id'],'|nosale=1|noremont=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Эликсир
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Новый сундук
			$i3 = $this->addItem(3201,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}		
			
			if($io == '') {
				$io = 'Снабжение воспитанника: 1 уровень, Накидка воспитанника, Эликсир Восстановления';	
			}
		
	}elseif($tr['var_id'] == 2) {
		// Набор [1]
		$add_zb = 10;
		
			$add_nas = 1;
		
			//Рубаха 3209
			$i3 = $this->addItem(3209,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Штаны 3210
			$i3 = $this->addItem(3210,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Эликсир
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Новый сундук
			$i3 = $this->addItem(3202,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = 'Снабжение воспитанника: 2 уровень, Рубаха воспитанника, Штаны воспитанника, Эликсир Восстановления';	
			}
		
	}elseif($tr['var_id'] == 3) {
		// Набор [2]
		$add_zb = 20;
		
			$add_nas = 1;
		
			//Перчатки 3211
			$i3 = $this->addItem(3211,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Наручи 3212
			$i3 = $this->addItem(3212,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Эликсир
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Новый сундук
			$i3 = $this->addItem(3203,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = 'Снабжение воспитанника: 3 уровень, Наручи воспитанника, Перчатки воспитанника, Эликсир Восстановления';	
			}
		
	}elseif($tr['var_id'] == 4) {
		// Набор [3]
		$add_zb = 30;
		
			$add_nas = 1;		
		
			//Сапоги 3213
			$i3 = $this->addItem(3213,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Шлем 3214
			$i3 = $this->addItem(3214,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Пояс 3215
			$i3 = $this->addItem(3215,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Эликсир
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Новый сундук
			$i3 = $this->addItem(3204,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = 'Снабжение воспитанника: 4 уровень, Сапоги воспитанника, Шапка воспитанника, Пояс воспитанника, Эликсир Восстановления';	
			}
		
	}elseif($tr['var_id'] == 5) {
		// Набор [4]
		$add_zb = 40;
		
			$add_nas = 1;
		
			//Серьги 3216
			$i3 = $this->addItem(3216,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Амулет 3217
			$i3 = $this->addItem(3217,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Кольца 3218
			$i3 = $this->addItem(3218,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			$i3 = $this->addItem(3218,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			$i3 = $this->addItem(3218,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Эликсир
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Новый сундук
			$i3 = $this->addItem(3205,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = 'Снабжение воспитанника: 5 уровень, Серьги воспитанника, Амулет воспитанника, Кольцо воспитанника (х3), Эликсир Восстановления';	
			}
		
	}elseif($tr['var_id'] == 6) {
		// Набор [5]
		$add_zb = 50;
		
			$add_nas = 1;
		
			//Броня 4002
			$i3 = $this->addItem(4002,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Учебник воспитанника 4004
			$i3 = $this->addItem(4004,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Сумка воспитанника 4003
			$i3 = $this->addItem(4003,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Образы
			mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `img` = "ref_obr1.gif" LIMIT 2');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("0","ref_obr1.gif","5","'.$this->info['id'].'","'.time().'")');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("1","ref_obr1.gif","5","'.$this->info['id'].'","'.time().'")');
			
			//Новый сундук
			$i3 = $this->addItem(3206,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = 'Снабжение воспитанника: 6 уровень, Броня воспитанника, Образ воспитанника, Учебник воспитанника, Сумка воспитанника';	
			}
		
	}elseif($tr['var_id'] == 7) {
		// Набор [6]
		$add_zb = 60;
			
			$add_nas = 2;
			
			//Учебник воспитанника 4004
			$i3 = $this->addItem(4004,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Сумка воспитанника 4003
			$i3 = $this->addItem(4003,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Нападалка
			$i3 = $this->addItem(865,$this->info['id'],'|nosale=1',NULL,50);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
						
			//Новый сундук
			$i3 = $this->addItem(3207,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = 'Снабжение воспитанника: 7 уровень, Учебник воспитанника, Сумка воспитанника, Нападение';	
			}
		
	}elseif($tr['var_id'] == 8) {
		// Набор [7]
		$add_zb = 70;
			
			$add_nas = 3;
			
			//Учебник воспитанника 4004
			$i3 = $this->addItem(4004,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Сумка воспитанника 4003
			$i3 = $this->addItem(4003,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Нападалка
			$i3 = $this->addItem(865,$this->info['id'],'|nosale=1',NULL,50);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Восстановление
			$i3 = $this->addItem(2712,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			$i3 = $this->addItem(2712,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "Наставник" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//Образы
			mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `img` = "ref_obr2.gif" LIMIT 2');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("0","ref_obr2.gif","7","'.$this->info['id'].'","'.time().'")');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("1","ref_obr2.gif","7","'.$this->info['id'].'","'.time().'")');
			
			if($io == '') {
				$io = 'Учебник воспитанника, Сумка воспитанника, Нападение, Образ воспитанника, Восстановление энергии 900HP (х2)';	
			}
		
	}
	
	if($add_zb > 0 && $this->info['level'] < 8) {
		$this->info['money4'] += $add_zb;
		mysql_query('UPDATE `users` SET `money4` = "'.$this->info['money4'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$this->info['login']."',' В &quot;Снабжение воспитанника&quot; Вы обнаружили зубы: <small>".$this->zuby($add_zb,1)."</small>. ','-1','6','0')");
	}
	
	if($add_nas > 0 && $this->info['level'] < 8 && isset($refer['id'])) {
		$ino = 0;
		while($ino < $add_nas) {
			$this->addItem(4005,$refer['id']);
			$ino++;
		}
		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$refer['login']."',' Ваш воспитанник &quot;".$this->info['login']."&quot; приносит жетоны <b>Талант Наставника</b> (<small>x".(0+$add_nas)."</small>). ','-1','6','0')");
	}
	
	unset($i3,$add_zb,$refer,$add_nas);
?>