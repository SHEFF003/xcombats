<?
if( isset($s[1]) && $s[1] == '101/laba1' ) {
	/*
		Сундук: Лаболатория
		* Можно собрать случайную тактику, но не более 3 на человека за поход и не более 10 на команду
		* 897 - Слиток пустынной руды
		* 903 - Тысячелетний камень
		* 888 - Шепот гор
		* 892 - Эссенция чистоты
		* 950 - Кожа Общего Врага
		* 904 - Кристалл времен
		* 878 - Лучистый топаз
		* 880 - Эссенция глубины
		* 879 - Ралиэль
		* 899 - Корень змеиного дерева
		* 882 - Глубинный камень
		* 908 - Камень затаенного солнца
		* 909 - Эссенция праведного гнева
		* 902 - Плод змеиного дерева
		* 881 - Лучистый Рубин
		* 893 - Эссенция лунного света
		* 898 - Троекорень
		* 890 - Сгусток астрала
		* 907 - Кристалл стабильности
		* 905 - Стихиалия
		-- Боя
		4243 - 897 х3
		4244 - 903 х2
		4245 - 888 х2
		4246 - 892 х1
		4247 - 879 х1 , 892 х1
		-- Защиты
		4248 - 950 х3
		4249 - 904 х2
		4250 - 878 х2
		4251 - 880 х1
		4252 - 880 х1 , 892 х1
		-- Крови
		4253 - 899 х3
		4254 - 882 х2
		4255 - 908 х2
		4256 - 909 х1
		4257 - 909 х1 , 892 х1
		-- Ответа
		4258 - 899 х3
		4259 - 902 х2
		4260 - 881 х2
		4261 - 893 х1
		4262 - 893 х1 , 892 х1
		-- Отражения
		4263 - 898 х3
		4264 - 890 х2
		4265 - 907 х2
		4266 - 905 х1
		4267 - 905 х1 , 892 х1
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	
	$vad['recept'] = array(
		//Б
		array( 897, 3 ),
		array( 903, 2 ),
		array( 888, 2 ),
		array( 892, 1 ),
		array( 892, 1, 892, 1 ),
		//З
		array( 950, 3 ),
		array( 904, 2 ),
		array( 878, 2 ),
		array( 880, 1 ),
		array( 880, 1, 892, 1 ),
		//К
		array( 899, 3 ),
		array( 882, 2 ),
		array( 908, 2 ),
		array( 909, 1 ),
		array( 909, 1, 892, 1 ),
		//Ответа
		array( 899, 3 ),
		array( 902, 2 ),
		array( 881, 2 ),
		array( 893, 1 ),
		array( 893, 1, 892, 1 ),
		//Отражения
		array( 898, 3 ),
		array( 890, 2 ),
		array( 907, 2 ),
		array( 905, 1 ),
		array( 905, 1, 892, 1 )
	);
	
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_lab" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	$vad['test2'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_lab" LIMIT 1'));
	
	$vad['i'] = 0;
	while( $vad['i'] < count($vad['recept']) ) {
		//4243 + $vad['i']
		$vad['tr_itm'] = $vad['recept'][$vad['i']][0]; 
		if( $vad['tr_itm'] > 0 ) {
			$vad['tr_itm'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.$vad['recept'][$vad['i']][0].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inTransfer` = "0" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
			if( $vad['tr_itm'][0] >= $vad['recept'][$vad['i']][1] ) {
				$vad['tr_itm'] = true;
			}else{
				$vad['tr_itm'] = false;
			}
		}
		if( $vad['recept'][$vad['i']][2] > 0 && $vad['tr_itm'] == true ) {
			$vad['tr_itm'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.$vad['recept'][$vad['i']][2].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inTransfer` = "0" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
			if( $vad['tr_itm'][2] >= $vad['recept'][$vad['i']][3] ) {
				//все ок
			}else{
				$vad['tr_itm'] = false;
			}
		}
		if( $vad['tr_itm'] == true ) {
			$vad['itm'][] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.(4243 + $vad['i']).'" LIMIT 1'));
			$vad['tr'][(4243 + $vad['i'])] = array( $vad['recept'][$vad['i']][0] , $vad['recept'][$vad['i']][1] , $vad['recept'][$vad['i']][2] , $vad['recept'][$vad['i']][3] );
		}
		$vad['i']++;
	}
	
	$vad['itm'] = $vad['itm'][rand(0,count($vad['itm'])-1)];
	
	if( $vad['test2'][0] >= 10 ) {
		$r = 'Не удалось воспользоваться лабораторией, не более 10 раз на команду за один поход';
		$vad['go'] = false;
	}elseif( $vad['test1'][0] >= 3 ) {
		$r = 'Не удалось воспользоваться лабораторией, не более 3 раз на персонажа за один поход';
		$vad['go'] = false;
	}elseif(!isset($vad['itm']['id'])) {
		$r = 'Недостаточно ингридиентов...';
		$vad['go'] = false;
	}
	
	
	
	if( $vad['go'] == true ) {
		//Выдаем предмет
		if( $vad['tr'][$vad['itm']['id']][1] > 0 ) {
			$u->deleteItemID($vad['tr'][$vad['itm']['id']][0],$u->info['id'],$vad['tr'][$vad['itm']['id']][1]);
		}
		if( $vad['tr'][$vad['itm']['id']][3] > 0 ) {
			$u->deleteItemID($vad['tr'][$vad['itm']['id']][2],$u->info['id'],$vad['tr'][$vad['itm']['id']][3]);
		}
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`time`,`vars`,`x`,`y`) VALUES (
			"'.$u->info['dnow'].'","'.$u->info['id'].'","'.time().'","obj_act'.$obj['id'].'_lab","'.$obj['x'].'","'.$obj['y'].'"
		)');
		$u->addItem($vad['itm']['id'],$u->info['id'],'|frompisher=101');
		$r = 'Вы создали предмет &quot;'.$vad['itm']['name'].'&quot;! Расплавив ресурсы ...';
		if($u->info['sex'] == 0) {
			$vad['text'] = '<b>'.$u->info['login'].'</b> создал предмет &quot;'.$vad['itm']['name'].'&quot; при помощи &quot;'.$obj['name'].'&quot;.';
		}else{
			$vad['text'] = '<b>'.$u->info['login'].'</b> создала предмет &quot;'.$vad['itm']['name'].'&quot; при помощи &quot;'.$obj['name'].'&quot;.';
		}
		$this->sys_chat($vad['text']);
	}	
}
?>