<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='dragopsh') {
	
	include('_incl_data/class/__dungeon.php');
	
	$lab = mysql_fetch_array(mysql_query('SELECT * FROM `laba_now` WHERE `id` = "'.$u->info['dnow'].'" LIMIT 1')); 
	if( !isset($lab['id']) ) {		
		die('Поход в подземелье не найден...');	
	}
	
				$varsitmsund = array( 
					//Вещи в сундуках
					array(4391,1),
					array(1043,1),
					array(3106,1),
					array(2544,2),
					//array(2545,1),
					array(724,1),
					array(1187,1),
					array(1043,1),
					array(3106,1),
					array(2544,2),
					//array(2545,1),
					array(724,1),
					array(1187,1),
					array(1043,1),
					array(3106,1),
					array(2544,2),
					//array(2545,1),
					array(724,1),
					array(1187,1),
					array(1043,1),
					array(3106,1),
					array(2544,2),
					//array(2545,1),
					array(724,1),
					array(1187,1)
				);
				
				$varsitm = array( 
					//Слабые ресурсы
					array(4373,1),
					array(4374,1),
					array(4375,1),
					array(4376,1),
					array(4377,1),
					array(4378,1),
					array(4379,1),
					array(4380,1),
					array(4381,1),
					array(4382,1),
					array(4383,1),
					array(4384,1),
					array(4385,1),
					//
					array(4373,1),
					array(4374,1),
					array(4375,1),
					array(4376,1),
					array(4377,1),
					array(4378,1),
					array(4379,1),
					array(4380,1),
					array(4381,1),
					array(4382,1),
					array(4383,1),
					array(4384,1),
					array(4385,1),
					//
					array(4373,1),
					array(4374,1),
					array(4375,1),
					array(4376,1),
					array(4377,1),
					array(4378,1),
					array(4379,1),
					array(4380,1),
					array(4381,1),
					array(4382,1),
					array(4383,1),
					array(4384,1),
					array(4385,1)
				);
				$varsitmart = array( 
					//Артефакты
					array(2109,1),
					array(2111,1),
					array(2099,1),
					array(2105,1),
					array(2122,1),
					array(2101,1),
					array(2114,1),
					array(2107,1),
					array(2108,1)
				);
	
	$map = mysql_fetch_array(mysql_query('SELECT `id`,`data`,`update` FROM `laba_map` WHERE `id` = "'.$u->info['dnow'].'" LIMIT 1'));
	if( !isset($map['id']) ) {		
		die('Карта подземелий не найдена...');
	}
	
	$dies = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `vars` = "dielaba" LIMIT 1'));
	$dies = $dies[0];
	
	$map_d = json_decode($map['data']);
	
	$varos = array();
	$varos['trap1'] = mysql_fetch_array(mysql_query('SELECT `vals`,`time` FROM `laba_act` WHERE `uid` = "'.$u->info['id'].'" AND `lib` = "'.$lab['id'].'" AND `time` > "'.time().'" AND `vars` = "trap1" ORDER BY `time` DESC LIMIT 1'));
		
	$errors = '';
	$effed = '';
		
	if( isset($_GET['takeitm']) ) {
		$var = array(
			'obj' => mysql_fetch_array(mysql_query('SELECT `i`.*,`m`.`name`,`m`.`inslot` FROM `laba_itm` AS `i` LEFT JOIN `items_main` AS `m` ON `m`.`id` = `i`.`itm` WHERE `i`.`id` = "'.mysql_real_escape_string($_GET['takeitm']).'" AND `i`.`lib` = "'.$lab['id'].'" AND `i`.`x` = "'.$u->info['x'].'" AND `i`.`y` = "'.$u->info['y'].'" LIMIT 1'))
		);
		if(isset($var['obj']['id'])) {
			if( $var['obj']['take'] == 0 ) {
				$var['sex'] = ''; if($u->info['sex'] == 1) { $var['sex'] = 'а'; }
				$var['text'] = '<b>'.$u->info['login'].'</b> поднял'.$var['sex'].' предмет &quot;'.$var['obj']['name'].'&quot;';
				$errors .= '<b><font color=red>Вы подняли предмет &quot;'.$var['obj']['name'].'&quot;</font></b>';
				mysql_query('UPDATE `laba_itm` SET `take` = "'.$u->info['id'].'" WHERE `id` = "'.$var['obj']['id'].'" LIMIT 1');
				if( $var['obj']['inslot'] > 0 ) {
					$u->addItem($var['obj']['itm'],$u->info['id'],'|fromlaba=1|nosavelaba=1|nosale=1');
				}else{
					$u->addItem($var['obj']['itm'],$u->info['id'],'|fromlaba=1');
				}
				mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$var['text']."','".time()."','6','0','1','1')");									
			}else{
				$errors .= '<b><font color=red>Предмет кто-то поднял раньше Вас...</font></b>';
			}
		}else{
			$errors = '<font color=red><b>Предмет не найден...</b></font>';
		}
	}elseif( isset($_GET['useobj']) ) {
		$var = array(
			'obj' => mysql_fetch_array(mysql_query('SELECT * FROM `laba_obj` WHERE `id` = "'.mysql_real_escape_string($_GET['useobj']).'" AND `lib` = "'.$lab['id'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" LIMIT 1'))
		);
		if(isset($var['obj']['id'])) {
			if( $var['obj']['use'] == 0 ) {
				if( $var['obj']['type'] == 1 ) {
					//сунудук										
					$var['sex'] = ''; if($u->info['sex'] == 1) { $var['sex'] = 'а'; }
					
					//Выдаем ресурсы, свитки
					if( rand(0,100) < 25 ) {
						//Выдаем арт varsitmart
						$var['itm'] = $varsitm[rand(0,count($varsitm)-1)];
						$var['itm'] = $var['itm'][0];
						$var['itm'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$var['itm'].'" LIMIT 1'));
						if( isset($var['itm']['id']) ) {
							mysql_query('INSERT INTO `laba_itm` (`uid`,`lib`,`time`,`itm`,`x`,`y`,`take`) VALUES (
								"'.$u->info['id'].'","'.$lab['id'].'","'.time().'","'.$var['itm']['id'].'","'.$u->info['x'].'","'.$u->info['y'].'","0"
							)');
						}else{
							$var['itm']['name'] = 'Предмет рассыпался на глазах...';
						}
					}
					//Выдаем ресурсы, свитки
					$var['itm'] = $varsitmsund[rand(0,count($varsitmsund)-1)];
					$var['itm'] = $var['itm'][0];
					$var['itm'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$var['itm'].'" LIMIT 1'));
					if( isset($var['itm']['id']) ) {
						$u->addItem($var['itm']['id'],$u->info['id'],'|fromlaba=1');
					}else{
						$var['itm']['name'] = 'Предмет рассыпался на глазах...';
					}
					$var['text'] = '<img width=40 height=25 src=http://img.xcombats.com/i/items/event_sunduk.gif> <b>'.$u->info['login'].'</b> открыл'.$var['sex'].' сундук...и забрал &quot;'.$var['itm']['name'].'&quot;';
					mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$var['text']."','".time()."','6','0','1','1')");								
					$errors .= '<img width="40" height="25" src="http://img.xcombats.com/i/items/event_sunduk.gif"> '.$u->info['login'].' открыл'.$var['sex'].' сундук...и забрал &quot;'.$var['itm']['name'].'&quot;';
					$var['obj']['use'] = $u->info['id'];
				}elseif( $var['obj']['type'] == 5 ) {
					//Пандору										
					$var['sex'] = ''; if($u->info['sex'] == 1) { $var['sex'] = 'а'; }
					
					//Выдаем ресурсы, свитки
					if( rand(0,100) < 70 ) {
						//Выдаем арт varsitmart
						$var['itm'] = $varsitmart[rand(0,count($varsitmart)-1)];
						$var['itm'] = $var['itm'][0];
						$var['itm'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$var['itm'].'" LIMIT 1'));
						if( isset($var['itm']['id']) ) {
							mysql_query('INSERT INTO `laba_itm` (`uid`,`lib`,`time`,`itm`,`x`,`y`,`take`) VALUES (
								"'.$u->info['id'].'","'.$lab['id'].'","'.time().'","'.$var['itm']['id'].'","'.$u->info['x'].'","'.$u->info['y'].'","0"
							)');
						}else{
							$var['itm']['name'] = 'Предмет рассыпался на глазах...';
						}
					}
					$var['itm'] = $varsitm[rand(0,count($varsitm)-1)];
					$var['itm'] = $var['itm'][0];
					$var['itm'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$var['itm'].'" LIMIT 1'));
					if( isset($var['itm']['id']) ) {
						//$u->addItem($var['itm']['id'],$u->info['id'],'|fromlaba=1');
						mysql_query('INSERT INTO `laba_itm` (`uid`,`lib`,`time`,`itm`,`x`,`y`,`take`) VALUES (
							"'.$u->info['id'].'","'.$lab['id'].'","'.time().'","'.$var['itm']['id'].'","'.$u->info['x'].'","'.$u->info['y'].'","0"
						)');
					}else{
						$var['itm']['name'] = 'Предмет рассыпался на глазах...';
					}
					$var['text'] = '<img width=40 height=25 src=http://img.xcombats.com/i/items/event_pandbox.gif> <b>'.$u->info['login'].'</b> открыл'.$var['sex'].' Ящик Пандоры...';
					mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$var['text']."','".time()."','6','0','1','1')");								
					$errors .= '<img width="40" height="25" src="http://img.xcombats.com/i/items/event_pandbox.gif"> '.$u->info['login'].' открыл'.$var['sex'].' Ящик Пандоры...';
					$var['obj']['use'] = $u->info['id'];
				}elseif( $var['obj']['type'] == 3 ) {
					//Хилка										
					$var['sex'] = ''; if($u->info['sex'] == 1) { $var['sex'] = 'а'; }
					$var['hpp'] = rand(2,5)*10;
					$var['hp'] = round($u->stats['hpAll']/100*$var['hpp']);
					$u->stats['hpNow'] += $var['hp'];
					if( $u->stats['hpNow'] > $u->stats['hpAll'] ) {
						$u->stats['hpNow'] = $u->stats['hpAll'];
					}
					$var['text'] = '<img width=40 height=25 src=http://img.xcombats.com/i/items/event_heal.gif> <b>'.$u->info['login'].'</b> пополнил'.$var['sex'].' здоровье, Уровень Жизни +'.$var['hpp'].'% (+'.$var['hp'].'HP)';
					mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$var['text']."','".time()."','6','0','1','1')");								
					$errors .= '<img width="40" height="25" src="http://img.xcombats.com/i/items/event_heal.gif"> '.$u->info['login'].' пополнил'.$var['sex'].' здоровье, Уровень Жизни +'.$var['hpp'].'% (+'.$var['hp'].'HP)';
					$var['obj']['use'] = $u->info['id'];
				}				
				if( $var['obj']['use'] > 0 ) {
					mysql_query('UPDATE `laba_obj` SET `use` = "'.$var['obj']['use'].'" WHERE `id` = "'.$var['obj']['id'].'" LIMIT 1');
				}
			}else{
				$errors = '<font color=red><b>Кто-то уже использовал это до Вас...</b></font>';
			}
		}else{
			$errors = '<font color=red><b>Обьект не найден...</b></font>';
		}
		
		unset($var);
	}
		
	//Генирация карты
	$mapsee = '';
	$real_x = $u->info['x'];
	$real_y = $u->info['y'];
	
	if( $real_y < 6 ) {
		$real_y = 6;
	}
	if( $real_x < 6 ) {
		$real_x = 6;
	}
	if( $real_y > count($map_d) - 7 ) {
		$real_y = count($map_d) - 7;
	}
	if( $real_x > count($map_d) - 7 ) {
		$real_x = count($map_d) - 7;
	}
	
	$objs = array( );
	
	$sp = mysql_query('SELECT * FROM `laba_obj` WHERE `lib` = "'.$lab['id'].'" AND `x` > '.($real_x - 7).' AND `x` < '.($real_x + 7).' AND `y` > '.($real_y - 12).' AND `y` < '.($real_y + 12).' LIMIT 144');
	while( $pl = mysql_fetch_array($sp) ) {
		$objs[$pl['x']][$pl['y']] = $pl;
	}
	
	$i = 1;
	$goodgoo = array( 1 => 0 , 2 => 0 , 3 => 0 , 4 => 0 );
		while( $i <= 4 ) {
			$goto = array( 'x' => $u->info['y'] , 'y' => $u->info['x'] );
			if( $i == 1 ) {
				$goto['x']--;
			}elseif( $i == 2 ) {
				$goto['y']--;
			}elseif( $i == 3 ) {
				$goto['x']++;
			}elseif( $i == 4 ) {
				$goto['y']++;
			}
			if( $map_d[$goto['y']][$goto['x']] == 0 && $goto['x'] > 0 && $goto['x'] < count($map_d)-1 && $u->info['timeGo'] <= time() ) {
				$goodgoo[$i] = 1;
			}
		$i++;
	}
	
	if( isset($_GET['goto']) ) {
		$goto = array( 'x' => $u->info['y'] , 'y' => $u->info['x'] );
		if( $_GET['goto'] == 1 ) {
			$goto['x']--;
		}elseif( $_GET['goto'] == 2 ) {
			$goto['y']--;
		}elseif( $_GET['goto'] == 3 ) {
			$goto['x']++;
		}elseif( $_GET['goto'] == 4 ) {
			$goto['y']++;
		}
		if( $goodgoo[$_GET['goto']] == 1 && $u->info['timeGo'] <= time() ) {
			//переходим
			$u->info['y'] = $goto['x'];
			$u->info['x'] = $goto['y'];
			$real_x = $u->info['x'];
			$real_y = $u->info['y'];
			$varos['timego'] = 5;
			if( isset($varos['trap1']['time']) ) {
				$varos['timego'] += 3;
			}
			if( isset($u->stats['speed_dungeon']) ) {
				$varos['timego'] = $varos['timego']-floor($varos['timego']/100*$u->stats['speed_dungeon']);
				if( $varos['timego'] < 1 ) {
					$varos['timego'] = 1;
				}
			}
			$u->info['timeGo'] = time()+$varos['timego'];
			$u->info['timeGoL'] = time();
			mysql_query('UPDATE `stats` SET `x` = "'.$u->info['x'].'" ,`y` = "'.$u->info['y'].'",`timeGoL` = "'.$u->info['timeGoL'].'",`timeGo` = "'.$u->info['timeGo'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}
	}
	
	$tmdg  = ($u->info['timeGo']-time());
	$tmdgl = ($u->info['timeGo']-$u->info['timeGoL']);
	$tmdgp = floor(100-$tmdg/$tmdgl*100); if( $tmdgp < 1 ) { $tmdgp = 1; }elseif( $tmdgp > 100 ) { $tmdgp = 100; }
	$tmdgm = 25*$tmdgl;
	
	$tmdg = floor(40/100*$tmdgp);
	
	if( $tmdg < 1 ) {
		$tmdg = 1;
	}elseif( $tmdg > 40 ) {
		$tmdg = 40;
	}
	
	if( $real_y < 6 ) {
		$real_y = 6;
	}
	if( $real_x < 6 ) {
		$real_x = 6;
	}
	if( $real_y > count($map_d) - 7 ) {
		$real_y = count($map_d) - 7;
	}
	if( $real_x > count($map_d) - 7 ) {
		$real_x = count($map_d) - 7;
	}
		
	if( isset($_POST['exit']) ) {
		if( $lab['users'] < 2 ) {
			//Удаляем подземелье
			mysql_query('DELETE FROM `laba_now` WHERE `id` = "'.$lab['id'].'" LIMIT 1');
			mysql_query('DELETE FROM `laba_map` WHERE `id` = "'.$lab['id'].'" LIMIT 1');
			mysql_query('DELETE FROM `laba_obj` WHERE `lib` = "'.$lab['id'].'"');
			mysql_query('DELETE FROM `laba_act` WHERE `lib` = "'.$lab['id'].'"');
			mysql_query('DELETE FROM `laba_itm` WHERE `lib` = "'.$lab['id'].'"');
		}else{
			$lab['users']--;
			mysql_query('UPDATE `laba_now` SET `users` = "'.$lab['users'].'" WHERE `id` = "'.$lab['id'].'" LIMIT 1');
		}
		mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` < 1234567890 AND `inShop` = "0" AND `data` LIKE "%fromlaba=1%"');
		mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` < 1234567890 AND `inShop` = "0" AND `data` LIKE "%nosavelaba=1%"');
		mysql_query('UPDATE `users` SET `room` = "369" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		mysql_query('UPDATE `stats` SET `dnow` = "0",`x`="0",`y`="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		//Выбрасываем предметы которые из подземелья
		
		die('<script>location.href="main.php";</script>');
	}
	
	//Предметы в локации
	$itms = '';
	
	if(isset($objs[$u->info['x']][$u->info['y']]['id'])) {
		$var = array();
		$mitm = $objs[$u->info['x']][$u->info['y']];
		if( $mitm['type'] == 6 ) {
			//Случайный предмет
			if( $mitm['vars'] < 25 ) {
				$i = 0;
				while( $i < $mitm['vars'] ) {
					$var['add'] = $varsitm[rand(0,count($varsitm)-1)];
					if( $var['add'][1] > 0 ) {
						$j = 0;
						while( $j < $var['add'][1] ) {
							mysql_query('INSERT INTO `laba_itm` (`uid`,`lib`,`time`,`itm`,`x`,`y`,`take`) VALUES (
								"'.$u->info['id'].'","'.$lab['id'].'","'.time().'","'.$var['add'][0].'","'.$u->info['x'].'","'.$u->info['y'].'","0"
							)');
							$j++;
						}
					}
					$i++;
				}
			}else{
				//Конкретный предмет
				
			}
			mysql_query('DELETE FROM `laba_obj` WHERE `id` = "'.$mitm['id'].'" LIMIT 1');
		}elseif( $mitm['type'] == 2 ) {
			if( $mitm['use'] == 0 ) {
				$tbtl = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `team_win` = -1 AND `dn_id` = "'.$lab['id'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" LIMIT 1'));
				if( isset($tbtl['id']) ) {
					//вступаем в поединок
					mysql_query('UPDATE `users` SET `battle` = "'.$tbtl['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->error = 'Нападаем ... <script>location="main.php?rnd='.$code.'";</script>';
				}else{
					$var['bots'] = array( 
						array(357,5),
						array(358,5),
						array(359,5),
						array(360,5),
						array(361,3),
						array(362,3),
						array(363,3),
						array(364,2),
						array(365,5),
						array(366,5),
						array(367,3)
					);
					//Создаем новый бой
					if( $mitm['vars'] != NULL ) {
						//Боты уже есть
					}else{
						//Новый список составляем
						$i = 0;
						while( $i <= $u->info['level'] ) {
							$var['ab'] = $var['bots'][rand(0,count($var['bots'])-1)];
							$mitm['vars'] .= '|'.$var['ab'][0];
							$i += $var['ab'][1];
						}
						//
						$mitm['vars'] = ltrim($mitm['vars'],'|');
						//
						mysql_query('UPDATE `laba_obj` SET `vars` = "'.$mitm['vars'].'" WHERE `id` = "'.$mitm['id'].'" LIMIT 1');
					}
					//
					$mitm['vars'] = explode('|',$mitm['vars']);
					//
					if( count($mitm['vars']) > 0 ) {
						$btl_id = 0;
						$expB = 0;
						$btld = array(
							'players'=>'',
							'timeout'=>180,
							'type'=>0,
							'invis'=>0,
							'noinc'=>0,
							'travmChance'=>0,
							'typeBattle'=>0,
							'addExp'=>$expB,
							'money'=>0
						);
		
						$ins = mysql_query('INSERT INTO `battle` (`dungeon`,`dn_id`,`x`,`y`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
															"102",
															"'.$lab['id'].'",
															"'.$u->info['x'].'",
															"'.$u->info['y'].'",
															"'.$u->info['city'].'",
															"'.time().'",
															"'.$btld['players'].'",
															"'.$btld['timeout'].'",
															"'.$btld['type'].'",
															"'.$btld['invis'].'",
															"'.$btld['noinc'].'",
															"'.$btld['travmChance'].'",
															"'.$btld['typeBattle'].'",
															"'.$btld['addExp'].'",
															"'.$btld['money'].'")');
						$btl_id = mysql_insert_id();
					}
					if( $btl_id > 0 ) {
						//
						$i = 0;
						while( $i < count($mitm['vars']) ) {
							
							$k = $u->addNewbot($mitm['vars'][$i],NULL,NULL,$logins_bot);
							$logins_bot = $k['logins_bot'];
							if($k!=false)
							{
								$upd = mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'",`room` = "-100" WHERE `id` = "'.$k['id'].'" LIMIT 1');
								if($upd)
								{
									$upd = mysql_query('UPDATE `stats` SET `x`="'.$u->info['x'].'",`y`="'.$u->info['y'].'",`team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
									if($upd)
									{
										$j++;
									}
								}
							}
								
							$i++;
						}
						unset($logins_bot);
						if($j>0)
						{
							mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');							
							$u->error = 'Нападаем ... <script>location="main.php?rnd='.$code.'";</script>';
						}else{
							$u->error = 'Не удалось напасть, ошибка обьекта нападения ...';	
						}
					}
					//
				}
			}
		}elseif( $mitm['type'] == 1 ) {
			//Сундук
			if( $mitm['use'] == 0 ) {
				$itms .= '<a title="Открыть" href="main.php?useobj='.$mitm['id'].'"><img src="http://img.xcombats.com/i/items/use_sunduk_on.gif" width="60" height="60"></a>';
			}else{
				$itms .= '<img title="Сундук был открыт" src="http://img.xcombats.com/i/items/use_sunduk_off.gif" width="60" height="60">';
			}
		}elseif( $mitm['type'] == 3 ) {
			//хилка
			if( $mitm['use'] == 0 ) {
				$itms .= '<a title="Выпить" href="main.php?useobj='.$mitm['id'].'"><img src="http://img.xcombats.com/i/items/use_heal_on.gif" width="60" height="60"></a>';
			}else{
				$itms .= '<img title="Эликсир был выпит" src="http://img.xcombats.com/i/items/openHeal.gif" width="60" height="60">';
			}
		}elseif( $mitm['type'] == 4 ) {
			if( $mitm['use'] == 0 ) {
				//Ловушка
				$var['trap1'] = mysql_fetch_array(mysql_query('SELECT `id`,`vals`,`time` FROM `laba_act` WHERE `uid` = "'.$u->info['id'].'" AND `lib` = "'.$lab['id'].'" AND `time` > "'.time().'" AND `vars` = "trap1" ORDER BY `time` DESC LIMIT 1'));
				$var['time'] = rand(1,60);
				if( isset($var['trap1']['id']) ) {
					mysql_query('UPDATE `laba_act` SET `vals` = "'.( $var['trap1']['vals'] + $var['time'] ).'",`time` = "'.( $var['trap1']['time'] + $var['time']*60 ).'" WHERE `id` = "'.$var['trap1']['id'].'" LIMIT 1');
				}else{
					mysql_query('INSERT INTO `laba_act` (`uid`,`time`,`lib`,`vars`,`vals`) VALUES (
						"'.$u->info['id'].'","'.(time()+$var['time']*60).'","'.$lab['id'].'","trap1","'.$var['time'].'"
					)');
				}
				mysql_query('UPDATE `laba_obj` SET `use` = "'.$u->info['id'].'" WHERE `id` = "'.$mitm['id'].'" LIMIT 1');
				
				$var['sex'] = ''; if($u->info['sex'] == 1) { $var['sex'] = 'а'; }
				$var['text'] = '<img width=40 height=25 src=http://img.xcombats.com/i/items/event_timer_trap.gif> <b>'.$u->info['login'].'</b> угодил'.$var['sex'].' в ловушку...';
				
				$varos['trap1'] = mysql_fetch_array(mysql_query('SELECT `vals`,`time` FROM `laba_act` WHERE `uid` = "'.$u->info['id'].'" AND `lib` = "'.$lab['id'].'" AND `time` > "'.time().'" AND `vars` = "trap1" ORDER BY `time` DESC LIMIT 1'));
	
				mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$var['text']."','".time()."','6','0','1','1')");								
				$errors .= '<img width="40" height="25" src="http://img.xcombats.com/i/items/event_timer_trap.gif"> '.$u->info['login'].' угодил'.$var['sex'].' в ловушку... Время перехода +3 секунды (Длительность: +'.$var['time'].' мин.)';
			}
		}elseif( $mitm['type'] == 5 ) {
			//Пандора
			if( $mitm['use'] == 0 ) {
				$itms .= '<a title="Открыть" href="main.php?useobj='.$mitm['id'].'"><img src="http://img.xcombats.com/i/items/panbox_on.gif" width="60" height="60"></a>';
			}else{
				$itms .= '<img title="Сундук был открыт" src="http://img.xcombats.com/i/items/panbox_off.gif" width="60" height="60">';
			}
		}
		unset($var);
	}
	
	$sp = mysql_query('SELECT `i`.*,`m`.`name`,`m`.`img` FROM `laba_itm` AS `i` LEFT JOIN `items_main` AS `m` ON `m`.`id` = `i`.`itm` WHERE `i`.`lib` = "'.$lab['id'].'" AND `i`.`x` = "'.$u->info['x'].'" AND `i`.`y` = "'.$u->info['y'].'" AND `i`.`take` = "0"');
	while( $pl = mysql_fetch_array($sp) ) {
		$itms .= ' <a href="main.php?takeitm='.$pl['id'].'"><img src="http://img.xcombats.com/i/items/'.$pl['img'].'" title="Поднять &quot;'.$pl['name'].'&quot;"></a>';
	}
	
	if( $itms != '' ) {
		$itms = '<u>В этой комнате находится:</u><br /><br />'.$itms.'<br />';
	}elseif( $u->info['y'] == count($map_d)-2 ) {
		//Выход нашелся!
		if( $lab['users'] < 2 ) {
			//Удаляем подземелье
			mysql_query('DELETE FROM `laba_now` WHERE `id` = "'.$lab['id'].'" LIMIT 1');
			mysql_query('DELETE FROM `laba_map` WHERE `id` = "'.$lab['id'].'" LIMIT 1');
			mysql_query('DELETE FROM `laba_obj` WHERE `lib` = "'.$lab['id'].'"');
			mysql_query('DELETE FROM `laba_act` WHERE `lib` = "'.$lab['id'].'"');
			mysql_query('DELETE FROM `laba_itm` WHERE `lib` = "'.$lab['id'].'"');
		}else{
			$lab['users']--;
			mysql_query('UPDATE `laba_now` SET `users` = "'.$lab['users'].'" WHERE `id` = "'.$lab['id'].'" LIMIT 1');
		}
		$u->addItem(4392,$u->info['id'],'|fromlaba=1|nosale=1');
		mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` < 1234567890 AND `inShop` = "0" AND `data` LIKE "%fromlaba=1%" AND `data` LIKE "%nosavelaba=1%"');
		mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` < 1234567890 AND `inShop` = "0" AND `data` LIKE "%nosavelaba=1%"');
		mysql_query('UPDATE `users` SET `room` = "369" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		mysql_query('UPDATE `stats` SET `dnow` = "0",`x`="0",`y`="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$r = '<img src=http://img.xcombats.com/i/items/paper100.gif width=40 height=25 /> Вы получили награду &quot;Чек на предъявителя (50кр.)&quot;';
		mysql_query("INSERT INTO `chat` (`typeTime`,`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$r."','".time()."','6','0')");
		$r = '<img src=http://img.xcombats.com/i/items/lmap.gif width=40 height=25 /> Вы прошли лабиринт &quot;Подземелья Драконов&quot; и сохранили предметы из лабиринта!';
		mysql_query("INSERT INTO `chat` (`typeTime`,`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$r."','".time()."','6','0')");
		//Выбрасываем предметы которые из подземелья		
		die('<script>location.href="main.php";</script>');
	}
	
	$sp = mysql_query('SELECT `s`.`x`,`s`.`y`,`u`.`id`,`u`.`login`,`u`.`level` FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `s`.`dnow` = "'.$lab['id'].'" AND `s`.`id` != "'.$u->info['id'].'" LIMIT 5');
	$pi = 1;
	while( $pl = mysql_fetch_array($sp) ) {
		$objs[$pl['x']][$pl['y']] = array(2 => '<div title="Игрок: '.$pl['login'].'" class="ddp1ee'.$pi.'"></div>'); //Персонаж 1
		$pi++;
	}
	
	$objs[$u->info['x']][$u->info['y']] = array(2 => '<div title="Я" class="ddp1me"></div>'); //Персонаж 1
	$i = 0;
	while( $i <= count($map_d) ) {
		$j = 0;
		while( $j < count($map_d[$i]) ) {
			if( $i > $real_x - 6 && $i < $real_x + 6 && $j > $real_y - 6 && $j < $real_y + 6 ) {
				if( $map_d[$i][$j] == 1 ) {
					$mapsee .= '<div class="ddp1">'.$objs[$i][$j][2].'</div>';
				}else{
					if( !isset($objs[$i][$j]['id']) ) {
						if( isset($objs[$i][$j][2]) ) {
							//
						}elseif( $j == 1 ) {
							$objs[$i][$j][2] = '<div title="Вход в лабиринт" class="ddpStart"></div>';
						}elseif( $j == count($map_d)-2 ) {
							$objs[$i][$j][2] = '<div title="Выход из лабиринта!" class="ddpExit"></div>';
						}
					}else{
						if( $objs[$i][$j]['use'] == 0 ) {
							$objs[$i][$j][2] = '<div class="'.$objs[$i][$j]['img'].'"></div>';
						}else{
							$objs[$i][$j][2] = '';
						}
					}
					$mapsee .= '<div class="ddp0">'.$objs[$i][$j][2].'</div>';
				}
			}
			$j++;
		}
		if( $i > $real_x - 6 && $i < $real_x + 6 ) {
			$mapsee .= '<br>';	
		}
		$i++;
	}
	$mapsee = '<div style="width:165px;height:165px;padding:10px;">'.$mapsee.'</div>';
	
	
	//Эффекты на персонаже
	if( isset($varos['trap1']['vals']) && $varos['trap1']['vals'] > 0 ) {
		$effed .= '<div><img width=40 height=25 src=http://img.xcombats.com/i/items/event_timer_trap.gif> - Время перехода +3 секунды (Осталось: '.$u->timeOut($varos['trap1']['time']-time()).')</div>';
	}
	unset($varos);
?>
<style>
.ddp0 { 
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/o.gif");
}
.ddp1 { 
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/m.gif");
}
.ddpStart {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/os.gif");
}
.ddpExit {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/of.gif");
}
.ddp1s {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/s.gif");
}
.ddp1m {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/r.gif");
}
.ddp1h {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/h.gif");
}
.ddp1l {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/b.gif");
}
.ddp1p {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/p.gif");
}
.ddp1me {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/u.gif");
}
.ddp1ee1 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/e1.gif");
}
.ddp1ee2 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/e2.gif");
}
.ddp1ee3 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/e3.gif");
}
.ddp1ee4 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/e4.gif");
}
.ddp1ee5 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/e5.gif");
}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
$('body').keydown(function( event ) {
  if( event.which == 38 || event.which == 87  ) {
  	 location.href="main.php?goto=2";
  }else if( event.which == 37 || event.which == 65  ) {
  	 location.href="main.php?goto=1";
  }else if( event.which == 39 || event.which == 68  ) {
  	 location.href="main.php?goto=3";
  }else if( event.which == 40 || event.which == 83  ) {
  	 location.href="main.php?goto=4";
  }
});
</script>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E2E0E0">
  <tbody>
    <tr>
      <td></td>
      <td width="307"></td>
      <td width="300"></td>
    </tr>
    <tr>
      <td height="409" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="5">&nbsp;</td>
            <td width="99%">
            <div><?=$u->error?></div>
            <div>Карта: <?=$lab['id']?></div>
			<script language="javascript" type="text/javascript">
            function confirmSubmit(mes)
            {
            var agree=confirm(mes);
            if (agree)
                return true ;
            else
                return false ;
            }
            </script>
              <br />
              <form method="post">
                <div>
                    <?=$d->usersDng($lab['id']);?>
                </div>
                <input type="submit" name="exit" value="Выйти и потерять все найденное!" onclick="return confirmSubmit('Действительно хотите Выйти и потерять все найденное?')" />
              </form>
              <br />
              <? if( $effed != '' ) { echo $effed; } ?>
              <br />
              <?
			  if( $dies > 0 ) {
				echo '<b>Кол-во смертей: '.$dies.'/3</b><br>';
			  }
			  ?>
              координаты : X=<?=$u->info['y']?>  Y=<?=$u->info['x']?><br /></td>
            <td width="5">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
            <? if( $errors != '' ) { echo $errors.'<br>'; } ?>
            <?=$itms?>
            </td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table></td>
      <td style="background-repeat:repeat; width:300px; height:410px" align="right">&nbsp;</td>
      <td height="409" width="300" valign="top" align="center"><table width="100%" height="396" border="0" cellpadding="0" cellspacing="0" style="background-position: top right; background-repeat: no-repeat; width: 300px; height: 410px; background: url('http://img.xcombats.com/drgn/navbg_big.gif'); ">
        <tbody>
          <tr>
            <td height="34"><table align="center" height="25" border="0" style="background:url(http://img.xcombats.com/drgn/bg/ramka_s2.gif); background-repeat:no-repeat; background-position:left;">
              <tbody>
                <tr valign="middle">
                  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td><div id="showbar" style="font-size: 2pt; padding: 2px; border: 0px solid black; visibility: visible;"> <span id="progress1" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress2" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress3" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress4" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress5" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress6" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress7" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress8" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress9" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress10" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress11" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress12" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress13" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress14" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress15" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress16" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress17" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress18" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress19" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress20" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress21" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress22" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress23" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress24" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress25" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress26" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress27" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress28" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress29" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress30" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress31" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress32" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress33" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress34" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress35" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress36" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress37" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress38" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress39" style="background-color: green;">&nbsp;&nbsp;</span> <span id="progress40" style="background-color: green;">&nbsp;&nbsp;</span> </div></td>
                  <td>&nbsp;&nbsp;</td>
                </tr>
              </tbody>
            </table>
			<script language="javascript">
            var progressEnd = 40; // set to number of progress <span>'s.
            var progressColor = 'green'; // set to progress bar color
            var progressInterval = <?=$tmdgm?>;
            var progressAt = <?=$tmdg?>;
            var progressTimer;
            
            
            function progress_set(too) {
            for (var i = 1; i <= too; i++) document.getElementById('progress'+i).style.backgroundColor = progressColor;
            }
            
            function progress_none() {
            for (var i = 1; i <= 40; i++) document.getElementById('progress'+i).style.backgroundColor = progressColor;
            }
            
            function progress_clear() {
            for (var i = <?=$tmdg?>; i <= progressEnd; i++) document.getElementById('progress'+i).style.backgroundColor = 'transparent';
            progressAt = <?=$tmdg?>;
            }
            function progress_update() {
            document.getElementById('showbar').style.visibility = 'visible';
            progressAt++;
                    if (progressAt > progressEnd)
                        {
                        clearTimeout(progressTimer);
                        return;
                        }
                    else document.getElementById('progress'+progressAt).style.backgroundColor = progressColor;
            progressTimer = setTimeout('progress_update()',progressInterval);
            }
            
            
            progress_clear(); 
            progress_set(<?=$tmdg?>); 
            progress_update(); 
            </script>
            <div align="right">&nbsp;</div></td>
          </tr>
          <tr>
            <td height="17"></td>
          </tr>
          <tr>
            <td height="102" valign="top" align="center"><table width="100%" height="102" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="95" height="102"></td>
                  <td width="103" style="background:url(http://img.xcombats.com/drgn/in_nav_bg.gif); width:103px; height:102px; background-repeat: no-repeat;"><table width="103" height="102" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="26" height="26"></td>
                        <td width="12"></td>
                        <td width="26" height="26"><a href="http://xcombats.com/main.php?goto=2"><img src="http://img.xcombats.com/drgn/arr1.gif" border="0" title="Вверх" alt="Вверх" /></a></td>
                        <td width="13"></td>
                        <td width="26"></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                        <td height="11"></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td width="26" height="26"><a href="http://xcombats.com/main.php?goto=1"><img src="http://img.xcombats.com/drgn/arr4.gif" border="0" title="Влево" alt="Влево" /></a></td>
                        <td></td>
                        <td width="26" height="26"	><a href="http://xcombats.com/main.php?refresh"><img src="http://img.xcombats.com/drgn/refresh.gif" border="0" title="Обновить" alt="Обновить" /></a></td>
                        <td></td>
                        <td width="26" height="26"><a href="http://xcombats.com/main.php?goto=3"><img src="http://img.xcombats.com/drgn/arr2.gif" border="0" title="Вправо" alt="Вправо" /></a></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td height="13"></td>
                        <td height="13"></td>
                        <td height="13"></td>
                        <td height="13"></td>
                        <td height="13"></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                        <td width="26" height="26"><a href="http://xcombats.com/main.php?goto=4"><img src="http://img.xcombats.com/drgn/arr3.gif" border="0" title="Вниз" alt="Вниз" /></a></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td width="105" height="5"></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="5"></td>
          </tr>
          <tr valign="top">
            <td height="165"><table width="303" height="165" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="67" height="74"></td>
                  <td width="165" height="165">
                  	<div>
                    <?
					echo $mapsee;
					?>
                    </div>
                  </td>
                  <td width="64"></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="25"></td>
          </tr>
          <tr>
            <td height="25"></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<?
}
?>