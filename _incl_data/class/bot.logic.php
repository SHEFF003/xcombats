<?php
if( !defined('GAME') ) {
	die();
}

class botLogic {	
	
	static $bot = array(),
		   $st	= array(),
		   $btl = array(),
		   $pr	= false;
	
	static function inuser_go_btl( $id , $txt = '' ) {
		if(isset($id['id'])) {
			$ctx = stream_context_create(array('http'=>
				array(
					'timeout' => 1
				)
			));
			$val = file_get_contents('http://xcombats.com/jx/battle/refresh_bot.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass'].'&'.$txt,false,$ctx);
			echo '['.$val.']<hr>';
			unset( $val );
		}
	}
	
	static function battle_priems() {
		
		//используем приемы в бою	
		if(self::$st['hpNow'] > 0 && self::$bot['battle'] > 0 && self::$bot['level'] >= 4) {
			
			$pr = explode('|',self::$bot['priems']);
			$rz = explode('|',self::$bot['priems_z']);
			$i = 0;
			while($i < count($pr)) {
				if($pr[$i] > 0) {
					self::$pr[$pr[$i]] = $rz[$i];					
				}
				$i++;
			}
			$i = 0;
			while($i < count($pr)) {
				if($rz[$i] < 1 && $pr[$i] > 0) {
					//Можно использовать прием, подключаем логику
					botPriemLogic::start( $i, $pr[$i] );					
				}
				$i++;
			}
			self::$pr = false;
			
		}
		
	}
	
	static function clear_bot() {
		
		//Очистка бота, обнуляем его до [0], удаляем эффекты, предметы и т.д, а текущему ставим логин delete
		
		//Удаляем сообщения в чате
		mysql_query('DELETE FROM `chat` WHERE `to` = "'.self::$bot['login'].'"');
		//Удаляем шмотки и эффекты
		mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.self::$bot['id'].'"');
		mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.self::$bot['id'].'"');
		
		//Удаляем статы и поле в юзерс
		mysql_query('DELETE FROM `users` WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
		mysql_query('DELETE FROM `stats` WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
		mysql_query('DELETE FROM `online` WHERE `uid` = "'.self::$bot['id'].'" LIMIT 1');
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.self::$bot['id'].'" LIMIT 1');
		//Добавляем статы и юзерс
		//mysql_query('UPDATE `users` SET `login` = "delete",`login2` = `login` WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
		//self::createNewBot(self::$bot['login'],self::$bot['sex']);
		/*
		$ins = mysql_query("INSERT INTO `users` (`fnq`,`id`,`host_reg`,`room`,`login`,`pass`,`ipreg`,`ip`,`city`,`cityreg`,`a1`,`q1`,`mail`,`name`,`bithday`,`sex`,`city_real`,`icq`,`icq_hide`,`deviz`,`chatColor`,`timereg`) VALUES (
					'0',
					".self::$bot['id'].",
					'0',
					'0',
					'".self::$bot['login']."',
					'".self::$bot['pass']."',
					'0',
					'".self::$bot['ip']."',
					'capitalcity',
					'capitalcity',
					'0',
					'0',
					'".self::$bot['mail']."',
					'".self::$bot['name']."',
					'".self::$bot['bithday']."',
					'".self::$bot['sex']."',
					'".self::$bot['city_real']."',
					'0',
					'1',
					'".self::$bot['deviz']."',
					'".self::$bot['chatColor']."',
					'".time()."')");
		if($ins) {
			$uid = self::$bot['id'];
			mysql_query("INSERT INTO `online` (`uid`,`timeStart`) VALUES ('".$uid."','".time()."')");
			mysql_query("INSERT INTO `stats` (`id`,`stats`) VALUES ('".$uid."','s1=3|s2=3|s3=3|s4=3|rinv=40|m9=5|m6=10')");
		}
		*/
	}
	
	static function inuser_go_main( $id , $txt = '' ) {
		if(isset($id['id'])) {
			$ctx = stream_context_create(array('http'=>
				array(
					'timeout' => 1
				)
			));
			file_get_contents('http://xcombats.com/main_bot.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass'].'&'.$txt,false,$ctx);
		}
	}
	
	static function inuser_go_zv( $id , $txt = '' ) {
		if(isset($id['id'])) {
			$ctx = stream_context_create(array('http'=>
				array(
					'timeout' => 1
				)
			));
			file_get_contents('http://xcombats.com/main_bot.php?zayvka=1&r=4&uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass'].'&'.$txt,false,$ctx);
		}
	}
	
	//ПРоверка на файтрум
	static function test_fr( $id , $city = 'capitalcity' ) {
		$r = true;		
		if( $city == 'capitalcity' ) {
			if( $id != 0 && $id != 2 && $id != 4 && $id != 5 && $id != 7 && $id != 377 ) {
				$r = false;
			}
		}		
		return $r;
	}
	
	//Действия бота вне боя
	static function actions() {
		
		global $u;
		
		if( self::$bot['battle'] == 0 && self::$bot['zv'] == 0 && self::$bot['pass'] == 'saintlucia' ) {
			
			//Можно: сменить фулл, перейти в другую комнату, входить наймом, делать бафы
					
			//Переходим в другую комнату
			if( true == false && self::$bot['a1'] != 0 && self::$bot['a1'] != self::$bot['room'] ) {
				
				self::_loc( self::$bot['a1'] );
				
			}else{
				
				self::update('a1',0);
				
				//CAPITAL
				if( self::$bot['city'] == 'capitalcity' ) {
					
					
					if( self::test_fr(self::$bot['room']) == false ) {
							
						if( self::$bot['exp'] > 400000 && self::$bot['level'] == 8 ) {
							self::$bot['exp'] = 400000;
							mysql_query('UPDATE `stats` SET `exp` = "400000" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
						}elseif( self::$bot['exp'] > 3500000 && self::$bot['level'] == 9 ) {
							self::$bot['exp'] = 3500000;
							mysql_query('UPDATE `stats` SET `exp` = "3500000" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
						}
							
						//Действие в комнате
						//Магазин
						if( self::$bot['room'] == 10 ) {

							//Покупаем кристалл вечности
							if( self::$bot['level'] == 5 && self::$bot['exp'] >= 12499) {
								$cr = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = "1204" AND `uid` = "'.self::$bot['id'].'" AND `delete` = "0" LIMIT 1'));
								if( !isset($cr['id']) ) {	
									//Покупаем кристалл
									$u->addItem(1204,self::$bot['id']);
								}
							}
							
						}
						
						//Переходим в комнату для сражений
						self::_loc( self::_loc_zv() );
						
					}else{
						
						//Стоим на месте, бо сражается ))
						
						
					}
					
				}
				//CAPITAL
				
			}
			
			//Если бот уже набрал опыт для перехода на 6-ой
			if( self::$bot['level'] == 5 && self::$bot['exp'] >= 12499 ) {					
				$cr = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = "1204" AND `uid` = "'.self::$bot['id'].'" AND `delete` = "0" LIMIT 1'));
				if( !isset($cr['id']) ) {				
					self::update('a1',10);	
				}
			}
			
			//Похоже что боту пора сменить комплект ))
			if( self::$bot['clss'] == 0 ) {
				
				//Выбираем новый класс и шмотки :)

				//Меняем класс
				self::$bot['clss'] = rand( 1, 4 );
								
				$x = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `a_bot_tree` WHERE `level` = "'.(self::$bot['level']).'" '));
				
				if( $x[0] > 0 ) {
					
					if( $x[0] > 1 ) {
						
						$x = rand( 1 , $x[0] );
						//выбираем 1 из нескольких
						$da = array('ASC','DESC','DESC','ASC');
						$da = $da[rand(0,5)];
						$com = mysql_fetch_array(mysql_query('SELECT * FROM `a_bot_tree` WHERE `level` = "'.(self::$bot['level']).'" ORDER BY `id` '.$da.' LIMIT '.($x-1).',1'));
					}else{
						$com = mysql_fetch_array(mysql_query('SELECT * FROM `a_bot_tree` WHERE `level` = "'.(self::$bot['level']).'" LIMIT 1'));
					}
					
					if(!isset($com['id'])) {
						
						$com = mysql_fetch_array(mysql_query('SELECT * FROM `a_bot_tree` WHERE `level` < "'.(self::$bot['level']).'" ORDER BY `id` DESC LIMIT 1'));
						
					}
					
					if(isset($com['id'])) {
						
						mysql_query('UPDATE `users` SET `clss` = "'.self::$bot['clss'].'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
						
						/* Забираем старые шмотки и эффекты */
						mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `gift` = "" AND `item_id` != "1204" AND `uid` = "'.self::$bot['id'].'" AND `delete` = "0"');
						mysql_query('UPDATE `items_users` SET `iznosNOW` = "0" WHERE `uid` = "'.self::$bot['id'].'" AND `delete` = "0"');
						mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.self::$bot['id'].'" AND `delete` = "0"');
						
						/* Обновляем статы и приемы */
						
						if($com['pr'] == '') {
							$i = 1;
							while( $i <= 18 ) {
								$com['pr'] .= $com['p'.$i].'|';
								$i++;
							}
							$com['pr'] .= '0';
						}
						
						mysql_query('UPDATE `stats` SET `stats` = "'.$com['stats'].'",`priems` = "'.$com['pr'].'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
						
						/* Выдаем новые предметы и эффекты */
						/*
						$e = explode(',',$com['eff']);
						$i = 0;
						while($i < count($e)) {
							if( $e[$i] > 0 ) {
								//Кастуем эффект на персонажа без срока годности
															
							}
							$i++;
						}
						*/
						
						$i = 1;
						while($i <= 20) {
							if($com['e'.$i] > 0) {
								//Выдаем и надеваем предмет
								//$it = $u->addItem($com['e'.$i],self::$bot['id']);
								$eff = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$com['e'.$i].'" LIMIT 1'));	
								mysql_query('INSERT INTO `eff_users` (`overType`,`id_eff`,`uid`,`name`,`timeUse`,`data`,`no_Ace`) VALUES ("'.$eff['oneType'].'","'.$eff['id2'].'","'.self::$bot['id'].'","'.$eff['mname'].'","'.(time()+9640000).'","'.$eff['mdata'].'","'.$eff['noAce'].'")');
							}
							$i++;
						}
						
						$i = 1;
						while($i <= 20) {
							if($com['w'.$i] > 0) {
								//Выдаем и надеваем предмет
								$it = $u->addItem($com['w'.$i],self::$bot['id']);
								if($it > 0) {
									mysql_query('UPDATE `items_users` SET `inOdet` = "'.$i.'",`delete` = "0" WHERE `uid` = "'.self::$bot['id'].'" AND `id` = "'.$it.'" LIMIT 1');
								}else{
									
								}
							}
							$i++;
						}
						
						
						
					}
					
				}else{
					//жопа, нет комплектов! ходим в старом
				}
				
			}
			
			//если бот уже 1-ый уровень, а сидит в новичках :) кидаем его в залы
			if( self::$bot['city'] == 'capitalcity' ) {
				
				if( self::$bot['level'] < 2 && self::$bot['room'] == 0 && self::$bot['a1'] == 0) {

					self::_loc( self::_loc_zv() );
				
				}else{
					
					//Если куда-то нужно - переходим
					
					if( self::$bot['a1'] > 0 ) {
						
						//Топаем в эту комнату
						
						
					}
					
				}
				
			}
			//
			

			
			/* ЗАВЕРШЕНИЕ ДЕЙСТВИЙ ВНЕ БОЯ */
			
		}
		
	}
	
	public $bot_last_action = array();

	//Включаем логику бота
	static function start( $id ) {		
		if(!isset($bot_last_action[$id])) {
			global $u;
			$bot_last_action[$id]++;
			self::$bot = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `st`.`id` = `u`.`id` WHERE `u`.`id` = "'.mysql_real_escape_string( $id ).'" AND `u`.`banned` = "0" LIMIT 1'));
			
			if( isset( self::$bot['id'] ) ) {
				
				//self::e(self::$bot['id'].'<<['.$bot_last_action[$id].']');
				
				if( self::$bot['ipreg'] == 1 || self::$bot['ipreg'] == 2 ) {
					self::$bot['ipreg'] = 3;
				}
				
				self::$st = $u->getStats( self::$bot, 0 );
				
				//Заходим ботом в онлайн
				self::_online();			
				
				if( self::$bot['battle'] > 0 ) {
									
					//Действия бота в поединке
					self::$btl = mysql_fetch_array( mysql_query('SELECT * FROM `battle` WHERE `id` = "'.mysql_real_escape_string(self::$bot['battle']).'" AND `team_win` = "-1" LIMIT 1') );
					if( isset( self::$btl['id'] ) ) {
	
						$go_bot = false;
						$go_txt = '';
						
						$a1 = mysql_fetch_array(mysql_query('SELECT `id`,`uid1`,`uid2`,`time` FROM `battle_act` WHERE `battle` = "'.self::$btl['id'].'" AND `uid1` = "'.self::$bot['id'].'" ORDER BY `time` ASC LIMIT 1'));
												
						//Проверяем возможность использования приемов и делаем список что использовать					
						//используем приемы					
						self::battle_priems();
						
						if( isset( $a1['id'] ) ) {
							
							//Бот сделал удар, но никто не ответил, проверяем таймаут и если что заходим
							if( $a1['time'] + self::$btl['timeout'] < time() ) {
								$go_bot = true;
							}
							
						}
						
						
						$a2_sp = mysql_query('SELECT `id`,`uid1`,`uid2`,`time` FROM `battle_act` WHERE `battle` = "'.self::$btl['id'].'" AND `uid2` = "'.self::$bot['id'].'" ORDER BY `time` ASC LIMIT 5');
						while( $a2 = mysql_fetch_array( $a2_sp ) ) {
							
							//Бота ударили - делаем ответный удар и заходим
							if( $a2['time'] + self::$btl['timeout'] < time() ) {
								//Заходим на персонажа
								$go_bot = true;
							}else{
								mysql_query('UPDATE `stats` SET `enemy` = "'.$a2['uid1'].'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								$go_bot = true;
								$a = array(
									0 => rand(1,5),
									1 => rand(1,5),
									2 => rand(1,5),
									3 => rand(1,5),
									4 => rand(1,5)
								);
								$a = $a[0].'_'.$a[1].'_'.$a[2].'_'.$a[3].'_'.$a[4];
								$b = rand(1,5);
								$go_txt .= '&atack='.$a.'&block='.$b.'';
							}
							
						}
						
						
						//Размен с противником
						$a1 = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle_act` WHERE `battle` = "'.self::$btl['id'].'" AND `uid1` = "'.self::$bot['id'].'" AND `uid2` = "'.self::$bot['enemy'].'" LIMIT 1'));
						if( !isset( $a1['id'] ) ) {
							$go_bot = true;
							$a = array(
								0 => rand(1,5),
								1 => rand(1,5),
								2 => rand(1,5),
								3 => rand(1,5),
								4 => rand(1,5)
							);
							$a = $a[0].'_'.$a[1].'_'.$a[2].'_'.$a[3].'_'.$a[4];
							$b = rand(1,5);
							$go_txt .= '&atack='.$a.'&block='.$b.'';						
						}
						
						
						unset($a1,$a2_sp,$a2);					
						//Заходим на персонажа
						if( $go_bot == true ) {
							self::inuser_go_btl( self::$bot , $go_txt );
						}
						
					}else{
						
						//Заходим на персонажа
						self::inuser_go_btl( self::$bot );
						
						//Поединок уже завершился, выкидываем из боя
						self::$bot['battle'] = 0;
						mysql_query( 'UPDATE `users` SET `battle` = "'.self::$bot['battle'].'" WHERE `id` = "'.mysql_real_escape_string(self::$bot['id']).'" LIMIT 1 ' );
					}
					
				}else{
					
					//Действия бота вне поединка
					if( self::$bot['timeMain'] < time() ) {
						
						mysql_query('UPDATE `chat` SET `time` = "'.time().'" WHERE `to` = "'.self::$bot['login'].'" AND `time` = "-1"');
						
						//Действие возможно произвести
						if( self::$bot['ipreg'] == 1 || self::$bot['ipreg'] == 2 || self::$bot['ipreg'] == 4 ) {
							//Принимаем только хаоты
							self::$bot['ipreg'] = 3;
						}
						if( self::$bot['ipreg'] == 5 ) {
							//Подаем только хаоты
							self::$bot['ipreg'] = 7;
						}					
						//
						if( self::$bot['ipreg'] == 0 || self::test_fr(self::$bot['room']) == false ) {
							
							/*if( self::$bot['exp'] > 0 && self::$bot['exp'] < 30000 ) {
								$u->addItem(1204,self::$bot['id']);
								mysql_query('UPDATE `stats` SET `exp` = "270000" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
							}*/
							
							//Удаляем травму и ослабу
							//mysql_query('UPDATE `eff_users` SET `timeUse` = "'.(time()-86400*3).'" WHERE `uid` = "'.self::$bot['id'].'" AND `id_eff` = "4" LIMIT 100');
							
							//Обновляем эффекты
							$com = mysql_fetch_array(mysql_query('SELECT * FROM `a_bot_tree` WHERE `level` = "'.(self::$bot['level']).'" LIMIT 1'));
							if(isset($com['id'])) {
								$eft = mysql_fetch_array(mysql_query('SELECT `id` FROM `eff_users` WHERE `uid` = "'.self::$bot['id'].'" AND `delete` = "0" LIMIT 1'));
								if(!isset($eft['id'])) {
									mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.self::$bot['id'].'" AND `delete` = "0"');
									$i = 1;
									while($i <= 20) {
										if($com['e'.$i] > 0) {
											//Выдаем и надеваем предмет
											//$it = $u->addItem($com['e'.$i],self::$bot['id']);
											$eff = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$com['e'.$i].'" LIMIT 1'));	
											mysql_query('INSERT INTO `eff_users` (`overType`,`id_eff`,`uid`,`name`,`timeUse`,`data`,`no_Ace`) VALUES ("'.$eff['oneType'].'","'.$eff['id2'].'","'.self::$bot['id'].'","'.$eff['mname'].'","'.(time()+86400*7).'","'.$eff['mdata'].'","'.$eff['noAce'].'")');
										}
										$i++;
									}
								}
							}
							
							if( self::$bot['level'] >= 12 && self::$bot['clan'] == 0 && self::$bot['align'] == 0 ) {
								//обнуляем бота
								self::clear_bot();
							}
							
							//Только-что из поединка, хиляется
							if( self::test_fr(self::$bot['room']) == false ) {
								
								//В какой-то локации, видимо что-то делаем
								
							}elseif( self::$st['hpNow'] >= self::$st['hpAll'] ) {
								echo 1;											
								//Приступаем к активной деятельности :)
								mysql_query('UPDATE `stats` SET `zv`= "0",`team`= "0",`hpNow` = "'.self::$st['hpNow'].'",`mpNow` = "'.self::$st['mpNow'].'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								mysql_query('UPDATE `users` SET `ipreg` = "'.self::new_action().'",`mod_zvanie` = "Стажер" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								
							}else{
							
								if(self::$bot['mod_zvanie'] == 'Стажер100500') {
									//Надеваем комплект + обновляем эффекты
									mysql_query('UPDATE `eff_users` SET `timeUse` = "'.( time() + 7200 ) .'" WHERE `uid` = "'.self::$bot['id'].'" AND `delete` = "0" AND `v1` != "priem" LIMIT 12');
									mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `uid` = "'.self::$bot['id'].'" AND `inOdet` > 0 AND `delete` = "0"');
									$sp = mysql_query('SELECT `u`.`id`,`st`.`inslot`,`st`.`2too` FROM `items_users` AS `u` LEFT JOIN `items_main` AS `st` ON `st`.`id` = `u`.`item_id` WHERE `u`.`inOdet` = 0 AND `st`.`inslot` > 0 AND `st`.`inSlot` <= 20');
									$in = array();
									while($pl = mysql_fetch_array($sp)) {
										$od = $pl['inslot'];
										
										if($od == 10 && $in[10] > 0) {
											if($in[11] > 0) {
												if($in[12] == 0) {
													$od = 12;
												}
											}else{
												$od = 11;
											}
										}
										
										if($od == 3 && $in[3] > 0 && $pl['2too'] > 0) {
											if( $in[14] > 0 ) {
												mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `uid` = "'.self::$bot['id'].'" AND `inOdet` = "14" AND `delete` = "0" LIMIT 1');
												$in[14] = 0;
											}
											$od = 14;										
										}
										
										if( $in[$od] == 0 ) {
											$in[$od] = $pl['id'];
											mysql_query('UPDATE `items_users` SET `inOdet` = "'.$od.'" WHERE `id` = "'.$pl['id'].'" AND `uid` = "'.self::$bot['id'].'" LIMIT 1');
										}
									}
									mysql_query('UPDATE `items_users` SET `mod_zvanie` = "Cтaжер" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								}
								
								//Хиляемся дальше
								if( self::$bot['regHP'] == 0 || self::$bot['regMP'] == 0 ) {								
									mysql_query('UPDATE `stats` SET `regHP` = "'.time().'", `regMP` = "'.time().'",`hpNow` = "'.self::$st['hpNow'].'",`mpNow` = "'.self::$st['mpNow'].'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								}
															
								$reg = $u->regen( self::$bot['id'] , self::$st , 0 );
							}
							
							self::actions();
							
							self::update( 'timeMain', self::rnd() );
							
						}elseif( self::$bot['ipreg'] == 1 ) {
	
							//Принять заявку на бой (физ.)
							if( self::$bot['zv'] == 0 ) {
								
								//Выделяем подходящую заявку в физ. поединке
								$rz = 2;
								$zv = 0;
								$nozv = 0;
															
								if( self::$bot['level'] == 0 ) {
									$rz = 1;
								}
	
								$zv = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `city` = "'.self::$bot['city'].'" AND `otk` < "'.rand( 2, 6 ).'" AND `time` < "'.( time() - rand( 15, 25 ) ).'" AND `bcs` < "'.time().'" AND `start` = "0" AND `razdel` = "'.$rz.'" AND `cancel` = "0" AND `time` > "'.( time() - 1111).'" AND `money3` = 0 ORDER BY `time` DESC  LIMIT 1'));
								
								if( isset( $zv['id'] ) ) {
									
									$uz1 = mysql_fetch_array(mysql_query('SELECT `u`.`sex`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zv['id'].'" AND `st`.`team`="1" LIMIT 1'));
									$uz2 = mysql_fetch_array(mysql_query('SELECT `u`.`sex`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zv['id'].'" AND `st`.`team`="2" LIMIT 1'));
									
									if( isset( $uz1['id'] ) && !isset( $uz2['id'] ) ) {
										
										$uz1st = $u->getStats($uz1,0);
										
										if( $uz1st['reting'] <= floor(self::$st['reting']*1.27) ) {
											
											//Принимаем заявку
											$sa = '';
											if( self::$bot['sex'] == 2 ) {
												$sa = 'а';
											}
										
											$text = ' [login:'.self::$bot['login'].'] принял'.$sa.' вашу заявку на бой.[reflesh_main_zv_priem:'.self::$bot['id'].']';
											mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$uz1['city']."','','','".$uz1['login']."','".$text."','".time()."','6','0')");
											mysql_query('UPDATE `stats` SET `zv` = "'.$zv['id'].'",`team` = "2" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
											mysql_query('UPDATE `users` SET `ipreg` = "8",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
										
										}else{
											$nozv = 1;
										}
										
										unset ($uz1st); 
										
									}else{
										$nozv = 1;
									}
								}else{
									$nozv = 1;								
								}
								
								if( $nozv == 1 ) {
									
									if( self::$bot['timeMain'] < time() - rand(1,3)*60 ) {
										mysql_query('UPDATE `users` SET `ipreg` = "0",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
									}
									
								}
								
								unset($rz);
							}
							
							
						}elseif( self::$bot['ipreg'] == 2 ) {
							
							//Принять заявку на бой (груп.)
							self::bot_group_haot_zv( 6 );
						}elseif( self::$bot['ipreg'] == 3 ) {
							
							//Принять заявку на бой (хаот.)
							self::bot_group_haot_zv( 7 );
						}elseif( self::$bot['ipreg'] == 4 ) {
							
							//Принять заявку на бой (турнир.)
							self::e(''.self::$bot['login'].', я хочу принять турнир...');
						}elseif( self::$bot['ipreg'] == 5 ) {
							
							//Подать заявку (физ.)
							if( self::$bot['zv'] == 0 ) {
								
								$rz = 2;
								if( self::$bot['level'] == 0 ) {
									$rz = 1;
								}
								
								$ins = mysql_query('INSERT INTO `zayvki` (`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
																		"0",
																		"0",
																		"'.time().'",
																		"'.self::$bot['city'].'",
																		"'.self::$bot['id'].'",
																		"0",
																		"0",
																		"'.( 60 * rand(1,3) ).'",
																		"0",
																		"21",															
																		"0",
																		"21",
																		"1",
																		"1",
																		"0",
																		"0",
																		"'.$rz.'",
																		"",
																		"",
																		"","'.( 0 + self::$bot['reting'] ).'","0")');
								$zid = mysql_insert_id();
								mysql_query('UPDATE `stats` SET `zv` = "'.$zid.'", `team` = "1" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								mysql_query('UPDATE `users` SET `ipreg` = "8",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
							}
							
						}elseif( self::$bot['ipreg'] == 6 || self::$bot['ipreg'] == 7 ) {
							
							self::bot_group_haot_zv( self::$bot['ipreg'] );
							
							//Подать заявку (груп.) или хаотов
							//$test_zv = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `creator` = '.self::$bot['id'].' AND `cancel` = 0 AND `start` = 0 LIMIT 1'));
							
							//self::e('SELECT * FROM `zayvki` WHERE `creator` = '.self::$bot['id'].' AND `cancel` = 0 AND `start` = 0 LIMIT 1');
							
							if( self::$bot['zv'] == 0 ) {
								
								$rz = 4;
								
								if( self::$bot['ipreg'] == 7 ) {
									$rz = 5;
								}
								
								$rz = 5;
								
								$zv_c = array(
									
									'time_start' => ( 60 * 5 * rand( 1, 2 ) ),
									'tm1'	=> rand( 2, 6 ),
									'tm2'	=> rand( 2, 6 ),
									'l1min'	=> 0,
									'l1max'	=> 21,
									'l2min'	=> 0,
									'l2max'	=> 21,
									'timeout'	=> ( 60 * rand( 1, 3 ) )
																	
								);
								
								if( self::$bot['ipreg'] == 7 ) {
									$zv_c['tm1'] = 99;
								}
								
								$zv_c['tm2'] = $zv_c['tm1'];
								$zv_c['l1min'] = self::$bot['level'];
								$zv_c['l1max'] = self::$bot['level'];
								
								if($zv_c['l1min'] < 2) {
									$zv_c['l1min'] = 2;
								}
								if($zv_c['l1max'] >21) {
									$zv_c['l1max'] = 21;
								}
								
								$zv_c['l2min'] = $zv_c['l1min'];
								$zv_c['l2max'] = $zv_c['l1max'];
								
								$ins = mysql_query('INSERT INTO `zayvki` (`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
																		"0",
																		"0",
																		"'.time().'",
																		"'.self::$bot['city'].'",
																		"'.self::$bot['id'].'",
																		"0",
																		"'.$zv_c['time_start'].'",
																		"'.$zv_c['timeout'].'",
																		"'.$zv_c['l1min'].'",
																		"'.$zv_c['l1max'].'",															
																		"'.$zv_c['l2min'].'",
																		"'.$zv_c['l2max'].'",	
																		"'.$zv_c['tm1'].'",
																		"'.$zv_c['tm2'].'",	
																		"0",
																		"0",
																		"'.$rz.'",
																		"",
																		"",
																		"","'.( 0 + self::$bot['reting'] ).'","0")');
								$zid = mysql_insert_id();
								mysql_query('UPDATE `stats` SET `zv` = "'.$zid.'", `team` = "1" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								mysql_query('UPDATE `users` SET `ipreg` = "8",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
							}
							
						}elseif( self::$bot['ipreg'] == 8 ) {
							
							//Ожидание начала поединка
							if( self::$bot['zv'] == 0 ) {
								
								//Поединок не удалось начать
								mysql_query('UPDATE `users` SET `ipreg` = "0",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
							}else{
								$zv = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id` = "'.self::$bot['zv'].'" AND `cancel` = "0" AND `start` = "0" LIMIT 1'));
								
								if( !isset( $zv['id'] ) ) {
									
									//Обнуляем действия
									mysql_query('UPDATE `users` SET `ipreg` = "0",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
									
								}elseif($zv['razdel'] == 4 || $zv['razdel'] == 5) {
									
									//хаот или группа
									if( $zv['time_start'] + $zv['time'] <= time() ) {
										
										self::inuser_go_zv( self::$bot );										
									}
									
								}else{
									//физ
									if( $zv['creator'] == self::$bot['id'] ) {
										
										$uz2 = mysql_fetch_array(mysql_query('SELECT `u`.`sex`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zv['id'].'" AND `st`.`team`="2" LIMIT 1'));
										if( isset( $uz2['id'] ) ) {
											//Заявку кто-то принял, реагируем! :)
											$pr = -1;
											
											$uz2st = $u->getStats($uz2,0);
											
											//btl-cof
											if( $uz2st['reting'] > floor(self::$st['reting']*1.27)) {
												
												//Отказываем, в 95% случаев, противник слишком силен
												if( rand( 0, 100 ) > 95 ) {
													//отправляем бота на избиение :D
													$pr = 1;
												}else{
													//отказ
													$pr = 0;
												}
												
											}else{
												$pr = 1;
											}
											
											//Можно принять заявку
											//$pr = 0;
											
											if( $pr == 1 ) {
												
												//Прием заявки
												//создаем поединок с ботом
												$expB = 0;
												$btl = array('players'=>'','timeout'=>$zv['timeout'],'type'=>$zv['type'],'invis'=>$zv['invis'],'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>$expB,'money'=>0);
												$ins = mysql_query('INSERT INTO `battle` (`time_over`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`,`team_win`) VALUES (
																					"0",
																					"'.self::$bot['city'].'",
																					"'.time().'",
																					"'.$btl['players'].'",
																					"'.$btl['timeout'].'",
																					"'.$btl['type'].'",
																					"'.$btl['invis'].'",
																					"'.$btl['noinc'].'",
																					"'.$btl['travmChance'].'",
																					"'.$btl['typeBattle'].'",
																					"'.$btl['addExp'].'",
																					"'.$btl['money'].'",
																					"-1")');
												if($ins)
												{
													$btl_id = mysql_insert_id();
													//обновляем данные о поединке						
													mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$uz2['id'].'" LIMIT 1');
													mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'",`ipreg` = "0" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
													mysql_query('UPDATE `stats` SET `zv` = "0",`team`="1" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
													mysql_query('UPDATE `stats` SET `zv` = "0",`team`="2" WHERE `id` = "'.$uz2['id'].'" LIMIT 1');
													
													//Если бой кулачный, то снимаем вещи
													if($btl['type']==1)
													{
														mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.self::$bot['id'].'" AND `inOdet`!=0');
														mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$uz2['id'].'" AND `inOdet`!=0');
													}
													
													mysql_query('UPDATE `zayvki` SET `start` = "'.time().'",`btl_id` = "'.$btl_id.'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
													
													//обновляем заявку, что бой начался
													self::$bot['battle'] = $btl_id;
													
													//Отправляем сообщение в чат всем бойцам
													mysql_query("INSERT INTO `chat` (`city`,`room`,`to`,`time`,`type`,`toChat`,`sound`) VALUES ('".$u->info['city']."','".$u->info['room']."','".$uz2['login']."','".time()."','11','0','117')");
												}
												
											}elseif( $pr == 0 ) {
												
												//Отказ
												$sa = '';
												if( self::$bot['sex'] == 2 ) {
													$sa = 'а';
												}
												$text = ' [login:'.self::$bot['login'].'] отказал'.$sa.' вам в поединке.';
												mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$uz2['city']."','','','".$uz2['login']."','".$text."','".time()."','6','0')");
												mysql_query('UPDATE `stats` SET `zv` = "0",`team` = "1" WHERE `id` = "'.$uz2['id'].'" LIMIT 1');
												mysql_query('UPDATE `zayvki` SET `otk` = ( `otk` + 1 ),`bcs` = "'.( time() + rand( 30, rand( 60, 180 ) ) ).'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
											}else{
												//Чего-то ждем...
											}
											
										}else{
											
											//Заявку никто не принял, возможно стоит отменить заявку вообще!
											if( self::$bot['timeMain'] < time() - 30 - rand((7 / $zv['otk'] ), (3*49 / $zv['otk'] )) ) {
												
												mysql_query('UPDATE `stats` SET `zv` = "0" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
												mysql_query('UPDATE `users` SET `ipreg` = "0",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
												mysql_query('UPDATE `zayvki` SET `cancel` = "'.time().'"  WHERE `id` = "'.$zv['id'].'" LIMIT 1');
												
											}
											
										}
										
									}
									
								}
								
								//Бот уже слишком долго ждет ответа игрока, отказываемся от заявки
								if( self::$bot['timeMain'] < time() - rand((30 / $zv['otk'] ), (135 / $zv['otk'] ))  && $zv['creator'] != self::$bot['id'] && ( $zv['razdel'] == 1 || $zv['razdel'] == 2 ) ) {
									
									$uz1 = mysql_fetch_array(mysql_query('SELECT `u`.`sex`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zv['id'].'" AND `st`.`team`="1" LIMIT 1'));
									if( isset( $uz1['id'] ) ) {
										
										$sa = '';
										if( self::$bot['sex'] == 2 ) {
											$sa = 'а';
										}
										
										$text = ' [login:'.self::$bot['login'].'] отозвал'.$sa.' свой запрос на бой.';
										mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$uz1['city']."','','','".$uz1['login']."','".$text."','".time()."','6','0')");
									}
									
									mysql_query('UPDATE `zayvki` SET `otk` = ( `otk` + 1 ),`bcs` = "'.( time() + rand( 30, rand( 60, 180 ) ) ).'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `zv` = "0",`team` = "1" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
									mysql_query('UPDATE `users` SET `ipreg` = "1",`timeMain` = "'.self::rnd().'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
								}
								
							}
							
						}elseif( self::$bot['ipreg'] == 9 ) {
							
							//Подать заявку (хаот.)
							
						}elseif( self::$bot['ipreg'] == 10 ) {
							
							//Свободные характеристики или умения
							
						}elseif( self::$bot['ipreg'] == 11 ) {
							
							//Меняем комплект
							
						}elseif( self::$bot['ipreg'] == 12 ) {
							
							//Получили новый уровень
							
						}elseif( self::$bot['ipreg'] == 13 ) {
							
							//Помочь персонажу в бою
							
						}elseif( self::$bot['ipreg'] == 14 ) {
							
							//Вылечить персонажа от травм
							
						}elseif( self::$bot['ipreg'] == 15 ) {
							
							//Сделать каст персонажу (Сокрушение, Холодный разум, Защита от магии, Защита от оружия)
							
						}elseif( self::$bot['ipreg'] == 16 ) {
							
							//Выпить эликсиры
							
						}else{
							self::update('ipreg',0);
						}
											
					}
					
				}
				
				return true;
				
			}else{
				
				return false;
				
			}
		}
	}
	
	static function team_zv_cf( $zv , $tm ) {
		$r = mysql_fetch_array(mysql_query('SELECT SUM(`btl_cof`) FROM `stats` WHERE `zv` = "'.$zv['id'].'" AND `team` = "'.$tm.'" LIMIT 1'));
		$r = 0+round($r[0]);		
		return $r;		
	}
	
	static function new_action() {
		
		$r = rand( 1 , 7 );
		
		if( self::$bot['level'] < 2 ) {
			if( $r == 2 || $r == 3 || $r == 6 || $r == 7 || $r == 8 ) {
				if( rand(0,1) == 1 ) {
					$r = 1; //принимаем физ
				}elseif( rand(0,1) == 0 ){
					$r = 4; //принимаем турнир
				}else{
					$r = 5; //подаем физ
				}
			}
		}elseif( self::$bot['level'] == 0 ) {
			if( rand(0,1) == 1 ) {
				$r = 1; //принимаем физ
			}else{
				$r = 5; //подаем физ
			}
		}else{
			//Доступны любые заявки
			
		}
		
		if( $r == 4 ) {
			$r = 1;
		}

		return $r;
		
	}
	
	
	/*/
	Базовые функции обучения бота
	/*/
		//Бот ищет заявку в группы или хаот для своего уровня и подходящую ему
		static function bot_group_haot_zv( $id ) {
			
			if($id == 6) {
				//группы
				$rz = 4;
			}elseif($id == 7) {
				//хаоты
				$rz = 5;
			}
			
			//
			$rz = 5;
			//
			
			
			$sp = mysql_query('SELECT * FROM `zayvki` WHERE `razdel` = "'.$rz.'" AND `cancel` = "0" AND `start` = "0" AND `invise` = "0" AND `money3` = 0 AND (
				( `min_lvl_1` <= '.self::$bot['level'].' AND  `max_lvl_1` >= '.self::$bot['level'].' ) OR ( `min_lvl_2` <= '.self::$bot['level'].' AND  `max_lvl_2` >= '.self::$bot['level'].' )
			)');
			
			$pr = 0;
			
			while($pl = mysql_fetch_array( $sp )) {
				
				if( $pr == 0 ) {
				
					$go = 1;
					$tm = array(0,0,0);
					
					if( $rz == 4 ) {
						
						$tm1c = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$pl['id'].'" AND `team` = "1" LIMIT 1'));
						$tm2c = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$pl['id'].'" AND `team` = "2" LIMIT 1'));
						
						if($tm1c[0] < $pl['tm1max']) {						
							if( $pl['min_lvl_1'] <= self::$bot['level'] && $pl['max_lvl_1'] >= self::$bot['level']) {							
								$tm[1] = 1;							
							}																		
						}
						
						if($tm2c[0] < $pl['tm2max']) {						
							if( $pl['min_lvl_1'] <= self::$bot['level'] && $pl['max_lvl_1'] >= self::$bot['level']) {							
								$tm[2] = 1;							
							}																		
						}	
						
						$atm = 1;
						$tmr = 0;						
						if( $tm[1] == 1 && $tm[2] == 0 ) {
							$tmr = 1;
						}elseif( $tm[1] == 0 && $tm[2] == 1 ) {
							$tmr = 2;
						}else{
							$tmr = rand(1,2);
						}
						
						if($tmr > 0) {
							if($tmr == 1) {
								$atm = 2;
							}						
							
												
							//Логика приема заявки
							if( self::team_zv_cf($pl,$atm) > ( self::team_zv_cf($pl,$tmr) + self::$st['reting'] )*1.67 ||  ($zv['tm2max'] < $zv['tm1max']/2) || ($zv['tm1max'] < $zv['tm2max']/2) ) {
								//self::e(self::$bot['login'].', я очкую '.$pl['id'].' , '.self::team_zv_cf($pl,$atm).' VS '.(self::team_zv_cf($pl,$tm) + self::$st['reting'] ).' ...');
								if(rand(0,100) < 90) {
									$go = 0;
								}
							}
						}
										
					}elseif( $rz == 5 ) {					
						/*
						if( $pl['min_lvl_1'] <= self::$bot['level'] && $pl['max_lvl_1'] >= self::$bot['level']) {							
							$tm[1] = 1;							
						}
						*/	
						//Только 8-ки
						/*if( self::$bot['level'] <= 8 ) {
							if( $pl['min_lvl_1'] <= 8 && $pl['max_lvl_1'] <= 8) {							
								$tm[1] = 1;							
							}	
						}else{*/
							if( $pl['min_lvl_1'] == self::$bot['level'] && $pl['max_lvl_1'] == self::$bot['level'] ) {							
								$tm[1] = 1;							
							}	
						//}
					}
					
					
					if($go == 1 && ( $tm[1] != 0 || $tm[2] != 0 )) {
										
						if( $tm[1] == 1 && $tm[2] == 0 ) {
							$tm = 1;
						}elseif( $tm[1] == 0 && $tm[2] == 1 ) {
							$tm = 2;
						}else{
							$tm = rand(1,2);
						}
							
						if( $rz == 5 ) {
							$tm = 1;
						}
												
						//self::e(self::$bot['login'].', принял участие в заявке #'.$pl['id'].', за команду №'.$tm.' ');
							
						if( $rz == 5 ) {
							/* считаем баланс */
							if($pl['tm1'] > $pl['tm2'])
							{
								$tm = 2;
							}elseif($z['tm1']<$z['tm2'])
							{
								$tm = 1;
							}else{
								$tm = rand(1,2);
							}
							
							$tm = rand(1,2);
							
							if($pl['invise']==0)
							{
								$nxtID = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$pl['id'].'"'));
								$nxtID = $nxtID[0];
								//$u->info['login2'] = 'Боец ('.($nxtID+1).')';
								self::$bot['login2'] = '';
							}else{
								self::$bot['login2'] = '';
							}
							
							$blnc = 100*self::$bot['level']+self::$st['reting'];
					
							$pl['tm'.$tm] += $blnc;
							
							mysql_query('UPDATE `zayvki` SET `tm1` = "'.$pl['tm1'].'", `tm2` = "'.$pl['tm2'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								
						}
												
						if( $tm > 0 || $rz == 5 ) {
							
							//Принимаем участие в заявке
					    	mysql_query('UPDATE `stats` SET `zv` = "'.$pl['id'].'",`team` = "'.$tm.'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
					 	    mysql_query('UPDATE `users` SET `login2` = "'.self::$bot['login2'].'",`ipreg` = "8" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
							self::$bot['zv'] = $pl['id'];
						    $pr = $pl['id'];
							
						}
						
					} //if
					
				} // while
			
			}
			
		}
		
		//Добавляем нового бота
		static function createNewBot($login,$sex) {
			if($sex != 1) {
				$sex = 0;
			}else{
				$se = 1;
			}
			
			$r = array(
				'name'		=>	'_',
				'city_real'	=>	'',
				'deviz'		=>	'',
				'chatColor'	=>	'Black'
			);
			
			$ins = mysql_query("INSERT INTO `users` (`fnq`,`host_reg`,`room`,`login`,`pass`,`ipreg`,`ip`,`city`,`cityreg`,`name`,`sex`,`city_real`,`deviz`,`chatColor`,`timereg`) VALUES (
						'0',
						'real_bot_user',
						'0',
						'".mysql_real_escape_string($login)."',
						'".md5('regnxt#$%^а0.'.time())."',
						'127.0.0.1',
						'127.0.0.1',
						'capitalcity',
						'capitalcity',
						'".$r['name']."',
						'".mysql_real_escape_string($sex)."',
						'".$r['city_real']."',
						'".$r['deviz']."',
						'".$r['chatColor']."',
						'".time()."')");
			if($ins){
				$uid = mysql_insert_id();
				mysql_query("INSERT INTO `online` (`uid`,`timeStart`) VALUES ('".$uid."','".time()."')");
				mysql_query("INSERT INTO `stats` (`id`,`stats`) VALUES ('".$uid."','s1=3|s2=3|s3=3|s4=3|rinv=40|m9=5|m6=10')");
				mysql_query("UPDATE `users` SET `online`='".time()."' WHERE `uid` = '".$uid."' LIMIT 1");				
			}
			
		}
		
		//Бот находится в онлайне
		static function _online() {
			if( self::$bot['online'] < time() - 60 ) {
				//уровень/апп
				if( self::$bot['battle'] == 0 && self::$bot['zv'] == 0) {			
					self::_level();
				}
				self::update( 'online', time() );
				//self::$bot['online'] = time();
				//mysql_query( 'UPDATE `users` SET `online` = "'.self::$bot['online'].'" WHERE `id` = "'.mysql_real_escape_string(self::$bot['id']).'" LIMIT 1 ' );
				
			}			
		}
		
		//Бот меняет локацию
		static function _loc_A( $a, $b ) {
			$r = $b;
			
			return $r;	
		}
		
		static function _loc_zv() {
			
			if( rand(0,100) < 5 ) {
				
				$r = 1;
				
			}else{
				
				$r = rand(1,4);
				
			}
			
			if( $r == 4 ) {
				
				//будуар
				if( self::$bot['sex'] != 1 ) {
					$r = rand(1,3);
				}
				
			}
			
			if( self::$bot['city'] == 'capitalcity' ) {
				if($r == 4) {
					//будуар
					$r = 7;
				}elseif( $r == 3 ) {
					//ЗВ 3
					$r = 5;
				}elseif( $r == 2 ) {
					//ЗВ 2
					$r = 2;
				}else{
					//ЗВ 1
					$r = 4;
				}
				
				if( self::$bot['level'] == 0 ) {
					//Новички
					$r = 0;
				}
				
			}
			
			return $r;
		}
		
		static function _loc( $id ) {
			
			if( $id == self::$bot['room'] ) {
				
				//ничего, уже пришли
				self::update('a1',0);
				
			}else{
				
				//Прокладываем маршрут из текущей комнаты
				$rid_next = self::_loc_A(self::$bot['room'],$id);
				if( $rid_next > 0 ) {
					//Идем туда
					//$rid_next = 377;
					mysql_query('UPDATE `users` SET `room` = "'.$rid_next.'" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
					return true;
				}else{
					//Невозможно дойти туда
					return false;
				}
								
			}
			
		}
		
		//Бот получил уровень, либо апп
		static function _level() {	
			global $u;
			$lvl = mysql_fetch_array(mysql_query('SELECT `upLevel`,`nextLevel`,`exp`,`money`,`money_bonus1`,`money_bonus2`,`ability`,`skills`,`nskills`,`sskills`,`expBtlMax`,`hpRegen`,`mpRegen`,`money2` FROM `levels` WHERE `upLevel`="'.(self::$bot['upLevel']+1).'" LIMIT 1'));			
			if( isset($lvl['upLevel']) ) {
				
				if( self::$bot['level'] <= 5 && self::$bot['exp'] >= 12499 ) {					
					$cr = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = "1204" AND `uid` = "'.self::$bot['id'].'" AND `delete` = "0" LIMIT 1'));
					if( !isset($cr['id']) ) {
						$u->addItem(1204,self::$bot['id']);
						self::$bot['exp'] = 12500;
					}				
				}
				//self::$bot['exp'] = 300000;
				if($lvl['exp'] <= self::$bot['exp']) {
					//mysql_query('UPDATE `stats` SET `exp` = "300000" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
					//self::e('test');
					//Получаем уровень
					self::inuser_go_main( self::$bot );
					mysql_query('UPDATE `users` SET `clss` = "0" WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
					
				}
				
			}else{
				self::e(self::$bot['login']);
			}
		
		}
		
	//Вспомогательные функции
	static function e( $t ) {
		
		mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity",
		"LEL","6","1","-1")');
	
	}
	
	static function rnd() {
		return time() + rand(3,14) + rand(0,14) + rand(7,21);
	}
	
	static function update( $n, $v, $t = 'users' ) {
		self::$bot[$n] = $v;
		mysql_query('UPDATE `'.$t.'` SET `'.$n.'` = "'.self::$bot[$n].'"  WHERE `id` = "'.self::$bot['id'].'" LIMIT 1');
	}
	
}
?>