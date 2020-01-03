<?php
# Скрипт отвечает за
# перемещения ботов по пещере
# а так-же напедения, когда игрок рядом


# Получаем IP
function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# Выполняем проверку безопасности. 
if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }
if(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '91.228.154.180') {
	die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
}

define('GAME', true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
//include('_incl_data/class/__dungeon.php');	

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

# Совершаем действие -> Нападение на игрока.
function botAttack ( $bot, $user ){
	if( $user['userBattle'] > 0 ) {
		$battleID = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$user['userBattle'].'" AND `team_win` = "-1" LIMIT 1'));
	}
	if( !isset($battleID['id']) ) { //Создаем поединок
		$btl_id = 0;
		$expB = 0;
		$btl = array('players'=>'', 'timeout'=>180, 'type'=>0, 'invis'=>0, 'noinc'=>0, 'travmChance'=>0, 'typeBattle'=>0, 'addExp'=>$expB, 'money'=>0 );
		
		$ins = mysql_query(
			'INSERT INTO `battle`
				(`dungeon`,`dn_id`,`x`,`y`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`)
			VALUES (
				"'.$bot['dn_id'].'",
				"'.$bot['this_dn'].'",
				"'.$bot['x'].'",
				"'.$bot['y'].'",
				"'.$bot['userCity'].'",
				"'.time().'",
				"'.$btl['players'].'",
				"'.$btl['timeout'].'",
				"'.$btl['type'].'",
				"'.$btl['invis'].'",
				"'.$btl['noinc'].'",
				"'.$btl['travmChance'].'",
				"'.$btl['typeBattle'].'",
				"'.$btl['addExp'].'",
				"'.$btl['money'].'"
			)'
		);
		$btl_id = mysql_insert_id();

		if( $btl_id > 0 ) { //Добавляем ботов
			$j = 0;
			$logins_bot = array(); 
			mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "'.$btl_id.'" WHERE `id2` = "'.$bot['id2'].'" LIMIT 1');
			$jui = 1;
			while( $jui <= $bot['colvo'] ) {
				$k = botAddBattle( $bot, $logins_bot );
				$logins_bot = $k['logins_bot'];
				if( $k != false ) {
					$upd = mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
					if( $upd ) {
						$upd = mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
						if( $upd ) {
							$j++;
						}
					}
				} 
				$jui++; 
			} 
			unset( $logins_bot ); 
			if( $j > 0 ) {
				mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$user['userId'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$user['userId'].'" LIMIT 1');
			}
		}
	} else { # Вмешиваемся в поединок.
		$j = 0;
		$logins_bot = array();
		$logins_bot_text =array(); 
		$logins_bot_vars =array('time1='.time().'');
		$logins_bot_inBattle = mysql_query('SELECT SUBSTRING_INDEX(`login`, " (", 1) as login2, count(`login`) as count, `login` FROM `battle_users` WHERE `battle` = "'.$battleID['id'].'" AND `team`=2 GROUP BY `login2`');
		while($row = mysql_fetch_array($logins_bot_inBattle) ) {
				$logins_bot[$row['login2']] = (int)$row['count'];
		}
		mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "'.$battleID['id'].'" WHERE `id2` = "'.$bot['id2'].'" LIMIT 1');
		$jui = 1;
		while( $jui <= $bot['colvo'] ) {
			$k = botAddBattle( $bot, $logins_bot );
			$logins_bot = $k['logins_bot'];
			
			$logins_bot_text[] = ' <strong>'.$k['login'].'</strong>'; 
			if( $k != false ) { 
				$upd = mysql_query('UPDATE `users` SET `battle` = "'.$battleID['id'].'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
				if( $upd ) {
					$upd = mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
					if( $upd ) {
						$j++;
					}
				}
			}
			$jui++;
		}
		if( $j > 0 ) {
			$logins_bot_text = '{tm1} В поединок вмешались: '.implode(', ',$logins_bot_text).'.';
			$logins_bot_vars = implode('||',$logins_bot_vars); 
			$battle_log  =  mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle`='.$battleID['id'].' ORDER BY `id_hod` DESC LIMIT 1'));
			if( $battle_log['id_hod'] > 0 ) {
				mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$battleID['id'].'","'.($battle_log['id_hod']+1).'","'.$logins_bot_text.'","'.$logins_bot_vars.'","","","","",1)');
			}
		}
		unset($logins_bot);
		if( $j > 0 ) {
			mysql_query('UPDATE `users` SET `battle` = "'.$battleID['id'].'" WHERE `id` = "'.$user['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$user['id'].'" LIMIT 1');
		}
		unset($logins_bot_inBattle);
	}
}

# Совершаем нападение -> Добавляем Ботов в поединок
function botAddBattle( $bot, $logins_bot ) {
	$add_bot = mysql_fetch_array(
		mysql_query('SELECT
				`id`, `login`, `stats`, `obraz`, `level`, `sex`, `name`, `deviz`, `hobby`, `type`, `itemsUse`, `priemUse`, `align`, `clan`, `align_zvanie`, `bonus`, `clan_zvanie`, `time_reg`, `city_reg`, `upLevel`, `active`, `expB`, `p_items`, `agressor`, `priems`, `priems_z`, `award`
			FROM `test_bot`
			WHERE `id` = "'.$bot['id_bot'].'"
			LIMIT 1'
		)
	);
	
	if( isset($add_bot['id']) ) {
		if( isset($logins_bot[$add_bot['login']]) ) {
			$logins_bot[$add_bot['login']]++;
			$add_bot['login'] = $add_bot['login'].' ('.$logins_bot[$add_bot['login']].')';											
		} else {
			$logins_bot[$add_bot['login']] = 1;
		}
		$ret = true;
		if( $add_bot['time_reg'] == 100 ) {
			$add_bot['time_reg'] = time();
		}
		if( $add_bot['city_reg'] == '{thiscity}' ) {
			$add_bot['city_reg'] = $bot['userCity'];
		}
		$ins1 = mysql_query('INSERT INTO `users` (
			`align`,
			`login`,
			`level`,
			`pass`,
			`city`,
			`cityreg`,
			`name`,
			`sex`, 
			`deviz`,
			`hobby`,
			`timereg`,
			`obraz`,
			`bot_id`
		) VALUES (
			"'.$add_bot['align'].'",
			"'.$add_bot['login'].'",
			"'.$add_bot['level'].'",
			"'.md5('bot_pass_'.$add_bot['login'].'_').'",
			"'.$bot['userCity'].'",
			"'.$add_bot['city_reg'].'",
			"'.$add_bot['name'].'",
			"'.$add_bot['sex'].'",
			"'.$add_bot['deviz'].'",
			"'.$add_bot['hobby'].'",
			"'.$add_bot['time_reg'].'",
			"'.$add_bot['obraz'].'",
			"'.$bot['id_bot'].'"
		)');
		
		# Если бот успешно создан.
		if( $ins1 ) {
			$uid = mysql_insert_id();
			$ins2 = mysql_query('INSERT INTO `stats` (`id`,`stats`,`hpNow`,`upLevel`,`bot`) VALUES ("'.$uid.'","'.$add_bot['stats'].'","1000000","'.$add_bot['upLevel'].'","1")');
			if( $ins2 ) {
				$add_bot['id'] = $uid; 
				$add_bot['logins_bot'] = $logins_bot;
				$ret = $add_bot;
				
				//Выдаем предметы
				//$this->addItem($item_id,$uid);
				$iu = explode(',',$add_bot['itemsUse']);
				$i = 0;
				$w3b = 0;
				while($i<count($iu)) {
					if($iu[$i]>0) {
						$idiu = botAddItem($iu[$i],$add_bot['id'], $bot['userCity']);
						$islot = mysql_fetch_array(mysql_query('SELECT `id`,`inslot` FROM `items_main` WHERE `id` = "'.$iu[$i].'" LIMIT 1'));
						if( isset($islot['id']) ) {
							if( $islot['inslot'] == 3 ) {
								if( $w3b == 1 ) {
									$islot = 14;
								} else {
									$islot = 3;
									$w3b = 1;
								}
							} else {
								$islot = $islot['inslot'];
							}
						} else {
							$islot = 2000;
						}
						if( isset($idiu, $islot) ) mysql_query('UPDATE `items_users` SET `inOdet` = "'.$islot.'" WHERE `id` = "'.$idiu.'" LIMIT 1');
					}
					$i++;
				}
				
			}else{
				$ret = false;
			}
		}else{
			$ret = false;
		}
		return $ret;
	}
}

#Выдаем предметы Боту.
function botAddItem($item_id, $bot_uid, $city) {
	$i = mysql_fetch_array(mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp` FROM `items_main` AS `im` WHERE `im`.`id` = "'.mysql_real_escape_string($item_id).'" LIMIT 1'));
	if(isset($i['id'])){
		$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));		
		//новая дата
		$data = $d['data'];
		$ins = mysql_query('
			INSERT INTO `items_users` (
				`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`,`dn_delete`
			) VALUES (
				"'.$i['overTypei'].'",
				"'.$i['id'].'",
				"'.$bot_uid.'",
				"'.$data.'",
				"'.$i['iznosMAXi'].'",
				"'.$i['geni'].'",
				"'.$i['magic_inci'].'",
				"'.$city.'",
				"'.time().'",
				"'.time().'",
				"'.$i['dn_delete'].'"
			)');

		# Если предмет успешно добавлен в базу данных.
		if( $ins ){
			$rt = mysql_insert_id();
			# отключена запись получения предмета в Дело.
		} else {
			$rt = 0;
		}			
	}
	return $rt;
}
function moveBots($direction, $b){
	$toGoX = 0;
	$toGoY = 0;
	
	if( isset($b['noBot']) && $b['noBot'] != '0000' ) {
		if( $b['noBot'][0] != '0' ) $b['goTop'] = 0;
		if( $b['noBot'][1] != '0' ) $b['goLeft'] = 0;
		if( $b['noBot'][2] != '0' ) $b['goBottom'] = 0;
		if( $b['noBot'][3] != '0' ) $b['goRight'] = 0;
	}
	$go = array(
		1 => array ('d'=>(int)$b['goTop'], 'go1'=>(int)$b['goLeft'], 'go2'=>(int)$b['goRight'], 'x' => (int)$b['x'], 'y' => (int)$b['y'], 's' => (int)$b['s']),
		2 => array ('d'=>(int)$b['goLeft'], 'go1'=>(int)$b['goBottom'], 'go2'=>(int)$b['goTop'], 'x' => (int)$b['x'], 'y' => (int)$b['y'], 's' => (int)$b['s']),
		3 => array ('d'=>(int)$b['goBottom'], 'go1'=>(int)$b['goRight'], 'go2'=>(int)$b['goLeft'], 'x' => (int)$b['x'], 'y' => (int)$b['y'], 's' => (int)$b['s']),
		4 => array ('d'=>(int)$b['goRight'], 'go1'=>(int)$b['goTop'], 'go2'=>(int)$b['goBottom'], 'x' => (int)$b['x'], 'y' => (int)$b['y'], 's' => (int)$b['s'])
	);
	$dir = array(
		1 => array('moveForward' => array( 'x' => '0', 'y' => '1' ), 'moveBack' => array( 'x' => '0', 'y' => '-1' ),'moveGo1' => array( 'x' => '-1', 'y' => '0' ),'moveGo2' => array( 'x' => '1', 'y' => '0' )),
		2 => array('moveForward' => array( 'x' => '-1', 'y' => '0' ),'moveBack' => array( 'x' => '1', 'y' => '0' ),'moveGo1' => array( 'x' => '0', 'y' => '-1' ),'moveGo2' => array( 'x' => '0', 'y' => '1' )),
		3 => array('moveForward' => array( 'x' => '0', 'y' => '-1' ),'moveBack' => array( 'x' => '0', 'y' => '1' ),'moveGo1' => array( 'x' => '1', 'y' => '0' ),'moveGo2' => array( 'x' => '-1', 'y' => '0' )),
		4 => array('moveForward' => array( 'x' => '1', 'y' => '0' ),'moveBack' => array( 'x' => '-1', 'y' => '0' ),'moveGo1' => array( 'x' => '0', 'y' => '1' ),'moveGo2' => array( 'x' => '0', 'y' => '-1' ))
	);
	$go = $go[$direction];
	$dir = $dir[$direction];
	if($go['d'] == 1 ) {
		$toGoY = $dir['moveForward']['y'];
		$toGoX = $dir['moveForward']['x'];
		if(rand(1,100)>66){
			if( $go['go1'] ==1 &&  $go['go2'] == 0) {
				$toGoY = $dir['moveGo1']['y'];
				$toGoX = $dir['moveGo1']['x']; 
			} elseif( $go['go1'] ==0 &&  $go['go2'] == 1) {
				$toGoY = $dir['moveGo2']['y'];
				$toGoX = $dir['moveGo2']['x']; 
			} elseif( $go['go1'] ==1 &&  $go['go2'] == 1) {
				$a = rand(1,2);
				$toGoY = $dir['moveGo'.$a]['y'];
				$toGoX = $dir['moveGo'.$a]['x'];
			}
		} elseif(rand(1,100)>96){
			$toGoY = $dir['moveBack']['y'];
			$toGoX = $dir['moveBack']['x']; 
		}
	} elseif( $go['d'] == 0 ) {
		if( $go['go1'] ==1 && $go['go2'] == 1 ){
			if(rand(0,1) == 1) {
				$toGoY = $dir['moveGo1']['y'];
				$toGoX = $dir['moveGo1']['x']; 
			} else {
				$toGoY = $dir['moveGo2']['y'];
				$toGoX = $dir['moveGo2']['x'];
			}
		} elseif( $go['go1'] ==1 && $go['go2'] == 0 ) {
			$toGoY = $dir['moveGo1']['y'];
			$toGoX = $dir['moveGo1']['x']; 
		} elseif( $go['go1'] ==0 && $go['go2'] == 1 ) {
			$toGoY = $dir['moveGo2']['y'];
			$toGoX = $dir['moveGo2']['x'];
		} elseif( $go['go1'] == 0 && $go['go2'] == 0 ){
			$toGoY = $dir['moveBack']['y'];
			$toGoX = $dir['moveBack']['x']; 
		}
	}
	unset($dir, $go, $direction, $a);
	return array( 'x'=>(int)$toGoX, 'y'=>(int)$toGoY );
}


# запуск скрипта.
function start(){ 
	# Страница создана 0.0000
	$mtime = microtime();$mtime = explode(" ",$mtime);$tstart = $mtime[1] + $mtime[0];

	# Выбираем всех ботов.
	# В выборку включено: Позиция бота, Направление куда он может идти, Существует ли рядом Игрок, его координаты и в поединке ли он.
	$query = mysql_query(
	"SELECT
		`dn`.`id` as `this_dn`, `dn`.`id2` as `dn_id`, `db`.`id2`, `db`.`id_bot`, `tb`.`login` as login, `db`.`colvo`, `db`.`go_bot`, `db`.`x`, `db`.`y`, `db`.`s`, `db`.`atack`, `tb`.`agressor`,
		`dm`.`go_1` as `goRight`, `dm`.`go_2` as `goLeft`, `dm`.`go_3` as `goTop`, `dm`.`go_4` as `goBottom`, `dm`.`no_bot` as `noBot`,
		`user_info`.`id` as `userId`,
		`user_info`.`login` as `userLogin`,
		`user_stats`.`hpNow` as `userHP`,
		`user_stats`.`x` as `userPosX`,
		`user_stats`.`y` as `userPosY`,
		`user_info`.`battle` as `userBattle`
	FROM `dungeon_now` as `dn`
		LEFT JOIN `dungeon_bots` as `db` ON `db`.`dn` = `dn`.`id`
		LEFT JOIN `dungeon_map` as `dm` ON ( `dm`.`x` = `db`.`x` AND `dm`.`y` = `db`.`y` AND `dm`.`id_dng` = `dn`.`id2`  )
		LEFT JOIN `test_bot` AS `tb` ON `db`.`id_bot` = `tb`.`id`
		LEFT JOIN `stats` AS `user_stats` ON  ( ( `user_stats`.`x`+1 >= `db`.`x` AND  `user_stats`.`x`-1 <= `db`.`x` ) AND ( `user_stats`.`y`+1 >= `db`.`y` AND `user_stats`.`y`-1 <= `db`.`y`) AND `user_stats`.`dnow` = `dn`.`id` )
		LEFT JOIN `users` AS `user_info` ON ( `user_stats`.`id` = `user_info`.`id` )
	
	WHERE
		`dn`.`time_finish` = '0' AND 
		`db`.`atack` = '0' AND
		`db`.`delete` = '0' AND
		`db`.`for_dn` = '0' AND
		( ( `db`.`go_bot` > '".(time()-32400)."' AND `db`.`go_bot` < '".(time())."') OR `db`.`go_bot`='1') AND
		`db`.`inBattle`='0'
	GROUP BY `db`.`id2`
	ORDER BY `db`.`go_bot` ASC"
	);
 
	while( $bot = mysql_fetch_assoc( $query ) ) {
		if( $bot['go_bot'] > 0 && $bot['go_bot'] <= time() ) {
			$sNext = true;
			$sTo=$bot['s'];
			$xFrom = $bot['x']; # текущие координаты X
			$yFrom = $bot['y']; # текущие координаты Y

			$return = moveBots($bot['s'],$bot);
			$xTo = $bot['x']+$return['x'];
			$yTo = $bot['y']+$return['y'];
			# Проверяем, имеется ли переход по клетке.
			$inSight = 0;
			if( isset($bot['id2']) ) {
				if($xFrom==$xTo && $yFrom==$yTo) { # Если остаемся на месте.
					$inSight = 1;
				} elseif( isset($bot['goRight']) && $xFrom == $xTo-1 && $bot['goRight'] == 1 ) { //право
					$sTo=4;
					$inSight = 1; 
				} elseif( isset($bot['goLeft']) && $xFrom == $xTo+1 && $bot['goLeft'] == 1 ) { //лево
					$sTo=2;
					$inSight = 1; 
				} elseif( isset($bot['goTop']) && $yFrom == $yTo-1 && $bot['goTop'] == 1 ) { //верх
					$sTo=1;
					$inSight = 1; 
				} elseif( isset($bot['goBottom']) && $yFrom == $yTo+1 && $bot['goBottom'] == 1 ) { //низ
					$sTo=3;
					$inSight = 1; 
				}
			}
			
			if( isset($bot['userId'],$bot['userPosY'],$bot['userPosX']) && $bot['userId'] != '' && $bot['agressor']==1 && (
				($bot['userPosY']==$bot['y']+1 && $bot['userPosX']==$bot['x']) OR
				($bot['userPosY']==$bot['y']-1 && $bot['userPosX']==$bot['x']) OR
				($bot['userPosY']==$bot['y'] && $bot['userPosX']==$bot['x']-1) OR
				($bot['userPosY']==$bot['y'] && $bot['userPosX']==$bot['x']+1)
			) ) {
				botAttack($bot,$bot); 
			} elseif( isset($bot['userId'],$bot['userPosY'],$bot['userPosX']) && $bot['userId'] != '' && $inSight == 1 && $yTo == $bot['userPosY'] && $xTo == $bot['userPosX'] && $bot['atack']==1) {
				botAttack($bot,$bot); 
			} elseif( $inSight == 1 ) { // Передвижение ботов. 
				$bot['go_bot'] = time()+rand(7,15);
				mysql_query('UPDATE `dungeon_bots` SET `x` = "'.$xTo.'",`y` = "'.$yTo.'", `s` = "'.$sTo.'", `go_bot` = "'.$bot['go_bot'].'" WHERE `id2` = "'.$bot['id2'].'" LIMIT 1 ');
			}
			unset($xFrom,$yFrom,$xTo,$yTo,$inSight,$sNext,$sTo);
		}
		unset($bot);
	}
	unset($query,$bot);
	
	$mtime = microtime();
	$mtime = explode(" ",$mtime);$mtime = $mtime[1] + $mtime[0];$totaltime = ($mtime - $tstart);
	printf ("Страница сгенерирована за %f секунд !", $totaltime); 
}

# Запускаем выполнение процесса.
start();



/* Для оптимизации запроса обновлений позиций

UPDATE dungeon_bots SET
x = CASE
WHEN id2 = '.$bot['id2'].' THEN "test2"
WHEN id2 = '.$bot['id2'].' THEN "test1" END
WHERE id2 IN ('.$bot['id2'].', 2, 3, 4)

*/
