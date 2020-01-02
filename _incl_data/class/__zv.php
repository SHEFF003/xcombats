<?
if(!defined('GAME')) { die(); }

	class Balancer
    {
            public static function balance($items, $key)
            {
                    $result = array();
                    $maxWeight = floor(self::sum($items, $key) / 2);
                    $numItems = count($items);
                   
                    $sack = self::buildSack($numItems, $maxWeight);
                   
                    for ($n = 1; $n <= $numItems; $n++)
                    {
                            // loop all items
                            for ($weight = 1; $weight <= $maxWeight; $weight++)
                            {
                                    $a = $sack[$n - 1][$weight]['value'];
                                    $b = null;
                                    $value = $items[$n - 1][$key];
                                    if ($value <= $weight)
                                    {
                                            $b = $value + $sack[$n - 1][$weight - $value]['value'];
                                    }
                                    $sack[$n][$weight]['value'] = ($b === null ? $a : max($a, $b));
                                    $sack[$n][$weight]['take'] = ($b === null ? false : $b > $a);
                            }
                    }
                   
                    $setA = array();
                    $setB = array();
                   
                    for ($n = $numItems, $weight = $maxWeight; $n > 0; $n--)
                    {
                            $item = $items[$n - 1];
                            $value = $item[$key];
                            if ($sack[$n][$weight]['take'])
                            {
                                    $setA[] = $item;
                                    $weight = $weight - $value;
                            }
                            else
                            {
                                    $setB[] = $item;
                            }
                    }
                   
                    return array($setA, $setB);
            }
           
            protected static function sum($items, $key)
            {
                    $sum = 0;
                    foreach ($items as $item)
                    {
                            $sum += $item[$key];
                    }
                    return $sum;
            }
           
            protected static function buildSack($width, $height)
            {
                    $sack = array();
                    for ($x = 0; $x <= $width; $x++)
                    {
                            $sack[$x] = array();
                            for ($y = 0; $y <= $height; $y++) {
                                    $sack[$x][$y] = array(
                                            'value' => 0,
                                            'take' => false
                                    );
                            }
                    }
                    return $sack;
            }
    }

unset($_POST['kingfight'],$_POST['nobot'],$_POST['noatack'],$_POST['mut_clever']);

session_start();

//if( $u->info['id'] == 1008000 || $u->info['admin'] > 0 || $u->stats['silver'] > 0 ) {
$u->info['no_zv_key'] = true;
//}
$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
if(isset($_POST['code21'])) { }

if(isset($_GET['del_z_time']) && $_GET['del_z_time'] != NULL) {
  $zay = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id` = "'.$u->info['zv'].'" AND `creator` = "'.$u->info['id'].'" AND `start` = 0 AND `cancel` = 0 AND `btl_id` = 0 ORDER BY `id` DESC LIMIT 1'));
  if(isset($zay['id']) && $zay['priz'] == 0) {
	  $colls = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$zay['id'].'"'));
	  $cs = $colls[0];
	  if(isset($zay['id'])) {
		if($u->info['zv'] == $zay['id'] && ($zay['creator'] == $u->info['id'])) {
		  if($cs == 1) {
			mysql_query('UPDATE `stats` SET `zv` = 0 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('DELETE FROM `zayvki` WHERE `id` = "'.$zay['id'].'" LIMIT 1');
			$test_s = 'Заявка удалена...';
		  } else {
			$test_s = 'Кто-то кроме вас уже участвует в данной заявке.';
		  }
		} else {
		  $test_s = 'Вы не в этой заявке , либо не вы её создали.';	
		}
	  } else {
		$test_s = 'Заявка не найдена...';
	  }
  }
}

class zayvki {
	public $zv_see,$error,$z1n = array(4=>'групповые',5=>'хаотичные'),$z2n = array(4=>'группового',5=>'хаотичного');
	
	public function testTravm() {
		global $u;
		$r = 0;
		$tr_pl = mysql_fetch_array(mysql_query('SELECT `id`,`v1` FROM `eff_users` WHERE `id_eff` = 4 AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" ORDER BY `v1` DESC LIMIT 1'));
		if( isset($tr_pl['id']) ) {
			//Проверяем костыли
			if( $tr_pl['v1'] == 1 ) {
				//все ок
			}elseif( $tr_pl['v1'] == 2 ) {
				$r = 1;
			}elseif( $tr_pl['v1'] == 3 ) {
				$r = 2;
			}
		}
		return $r;
	}
	
	public function test()
	{
		global $code,$c,$u;
		
		if( $u->info['zv'] > 0 ) {
			$test_zv = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id` = "'.$u->info['zv'].'" LIMIT 1'));
			if(!isset($test_zv['id'])) {
				$u->info['zv'] = 0;	
			}else{
				if( $test_zv['cancel'] > 0 || $test_zv['btl_id'] > 0 ) {
					$u->info['zv'] = 0;	
				}
				if( $test_zv['time'] < time() - 3600 ) {
					$u->info['zv'] = 0;	
				}
			}
			if( $u->info['zv'] == 0 ) {
				mysql_query('UPDATE `stats` SET `zv` = 0 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}
		}else{
			mysql_query('UPDATE `stats` SET `zv_enter` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}
		
		
		//Проверяем турниры в этом городе
		$sp = mysql_query('SELECT * FROM `turnirs` WHERE `status` != "-1"');
		while($pl = mysql_fetch_array($sp)) {
			
			//Начало турнира
			if($pl['status'] == 0 && $pl['time'] > time() ) {
				if( floor(($pl['time']-time())/60) <= 2 && $pl['chat'] > 0 ) {
					//Осталось 1 мин.
					//$r = '<font color=red><b>Турниры:</b> До начала турнира осталась 1 минута.</font> ';				
					//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','','','','".$r."','".time()."','6','0')");
					mysql_query('UPDATE `turnirs` SET `chat` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}elseif( floor(($pl['time']-time())/60) <= 5 && $pl['chat'] > 1 ) {
					//Осталось 5 мин.
					$r = '<font color=red><b>Турниры:</b> До начала турнира осталось 5 минут.</font> ';				
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','','','','".$r."','".time()."','6','0')");
					mysql_query('UPDATE `turnirs` SET `chat` = "1" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}elseif( floor(($pl['time']-time())/60) <= 10 && $pl['chat'] > 2 ) {
					//Осталось 10 мин.
					//$r = '<font color=red><b>Турниры:</b> До начала турнира осталось 10 минут.</font> ';				
					//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','','','','".$r."','".time()."','6','0')");
					mysql_query('UPDATE `turnirs` SET `chat` = "2" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}elseif( floor(($pl['time']-time())/60) <= 15 && $pl['chat'] > 3 ) {
					//Осталось 15 мин.
					//$r = '<font color=red><b>Турниры:</b> До начала турнира осталось 15 минут.</font> ';				
					//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','','','','".$r."','".time()."','6','0')");
					mysql_query('UPDATE `turnirs` SET `chat` = "3" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
			}
			if($pl['status'] == 0 && $pl['time'] < time()) {
				if($pl['users_in'] > 1) {
					//Начало турнира
					mysql_query('UPDATE `turnirs` SET `time` = "'.(time() + $pl['time3']).'",`status` = "1" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					//mysql_query('UPDATE `users` SET `inTurnirnew` = "0" WHERE `inTurnirnew` = "'.$pl['id'].'"');
					
					$usp = mysql_query('SELECT * FROM `users` WHERE `inTurnirnew` = "'.$pl['id'].'" LIMIT '.$pl['users_in']);
					while($ur = mysql_fetch_array($usp))
					{
							mysql_query('INSERT INTO `users` (`login`,`room`,`sex`,`level`,`inTurnirnew`,`bithday`,`activ`) VALUES ("'.$ur['login'].'","318","'.$ur['sex'].'","'.$t['level'].'","'.$pl['id'].'","01.01.2001","0")');
							$uri = mysql_insert_id();
							mysql_query('INSERT INTO `users_turnirs` (`uid`,`bot`,`turnir`) VALUES ("'.$ur['id'].'","'.$uri.'","'.$pl['id'].'")');
							$zid = 0;
							$x1 = 0;
							$y1 = 0;
							mysql_query('INSERT INTO `stats` (`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`) VALUES ("98","'.$zid.'","'.$uri.'","s1=3|s2=3|s3=3|s4=3|s5=0|s6=0|rinv=40|m9=5|m6=10","0","0","0",'.$x1.','.$y1.')');
							mysql_query('UPDATE `users` SET `inUser` = "'.$uri.'" WHERE `id` = "'.$ur['id'].'" LIMIT 1');	
							//Добавляем эффекты скорость регена и запрет передвижения
							
					}
					
				}else{
					//Отмена турнира
					mysql_query('UPDATE `turnirs` SET `time` = "'.(time() + $pl['time2']).'",`users_in` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `inTurnirnew` = "0" WHERE `inTurnirnew` = "'.$pl['id'].'"');
				}
			}
			
		}
		
		//Проверяем хаотичные и групповые бои в этом городе		
		$sp = mysql_query('SELECT * FROM `zayvki` AS `z` WHERE /*`z`.`city` = "'.$u->info['city'].'" AND*/ `z`.`btl_id` = "0" AND `z`.`cancel` = "0" AND `z`.`start` = "0" AND (`z`.`razdel` = 4 OR `z`.`razdel` = 5) ORDER BY `z`.`id` DESC LIMIT 11');
		while($pl = mysql_fetch_array($sp))
		{
			$uz = mysql_query('SELECT `u`.`sex`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$pl['id'].'"');
			$tm1 = array();
			$tm2 = array();
			$i = array();
			$toChat = '';
			$toChat2 = '';
			$toWhere = '';
			while($t = mysql_fetch_array($uz))
			{
				if(!isset(${'tm'.$t['team']})){ ${'tm'.$t['team']} = array(); }
				if(!isset($i[$t['team']])){ $i[$t['team']] = 0; }
				${'tm'.$t['team']}[$i[$t['team']]] = $t;
				$toChat .= ''.$t['login'].',';
				$toWhere .= 'OR `id` = "'.$t['id'].'" ';
				if($pl['razdel'] == 5 && $pl['time_start']-180 < time()-$pl['time'] && $pl['send'] == 0) {
					$toChat2 .= ''.$u->microLogin2($t).', ';	
				}
				$i[$t['team']]++;
			}
			//
			/*if($pl['razdel'] == 5 && $pl['time_start']-180 < time()-$pl['time'] && $pl['send'] == 0) {
				if( $toChat2 != '' ) {
					$toChat2 = rtrim($toChat2,', ');
					$text = '<font color=red >Внимание!</font> ( '.$toChat2.' ) ('.$pl['min_lvl_1'].'-'.$pl['max_lvl_1'].') <img src=http://img.xcombats.com/i/fighttype'.$pl['type'].'.gif width=20 height=20 > <font color=grey > Хаотичный бой начнется через <b>3.0</b> мин., таймаут 3 мин.</font>';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
					'1','".$zv['city']."','','','','".$text."','".time()."','6','0')");
				}
				mysql_query('UPDATE `zayvki` SET `send` = 1 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			}*/
			//
			if($pl['time_start'] < time()-$pl['time'] || ($pl['razdel']==4 && $i[1]>=$pl['tm1max'] && $i[2]>=$pl['tm2max']))
			{
				$toChat = rtrim($toChat,',');
				$toWhere = ltrim($toWhere,'OR ');
				if($pl['razdel']==4)
				{
					//группы
					if(!isset($i[1]) || !isset($i[2]) || (!isset($i[3]) && $pl['teams'] == 3))
					{
						//группа не набрана
						$this->cancelGroup($pl,$toChat);
					}else{
						//Начинаем поединок
						$this->startBattle($pl['id'],$toChat.'|-|'.$toWhere);
					}
				}elseif($pl['razdel']==5)
				{
					//хаоты
					if( $pl['max_lvl_1'] <= $c['haotbot'] ) {
						$i = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$pl['id'].'" LIMIT 1'));
						if($i[0] < 8) {
							//Добавляем временных ботов для хаотов
							$j = $i[0];
							while( $j < 8) {
								//
								//$botlg = mysql_fetch_array(mysql_query('SELECT * FROM `a_bot_tree` WHERE `level` = 4 ORDER BY RAND() LIMIT 1'));
								$bta = $this->addBotTree( $pl['max_lvl_1'] , $pl['id'] , $pl['type'] );
								if( $bta > 0 ) {
									$toChat .= ''.$bta['login'].',';
									$toWhere .= 'OR `id` = "'.$bta['id'].'" ';
									if($pl['razdel'] == 5 && $pl['time_start']-180 < time()-$pl['time'] && $pl['send'] == 0) {
										$toChat2 .= ''.$u->microLogin2($bta).', ';	
									}
									$i[$t['team']]++;
								}
								//
								$j++;
							}
						}
					}
					$i = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$pl['id'].'" LIMIT 1'));
					if($i[0] < 4 && ($i[0] < 2 || $pl['fastfight'] == 0))
					{
						//группа не набрана
						$this->cancelGroup($pl,$toChat);
					}else{
						//Начинаем поединок
						$this->startBattle($pl['id'],$toChat.'|-|'.$toWhere);
					}
				}
			}
		}
	}
	
	public function addBotTree( $lvl , $zvid , $type ) {
		global $u,$c;
		$botlg = mysql_fetch_array(mysql_query('SELECT * FROM `a_bot_tree` WHERE `level` = "'.$lvl.'" ORDER BY RAND() LIMIT 1'));
		if(isset($botlg['id'])) {
			//
			$botlg['align'] = 0;
			$botlg['clan'] = 0;
			$botlg['login'] = 'Боец';
			$bxlg = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `id` IN (SELECT `id` FROM `stats` WHERE `zv` = "'.$zvid.'") AND (`login` = "'.$botlg['login'].'" OR `login` LIKE "'.$botlg['login'].' (%") LIMIT 1'));
			$bxlg = 0+$bxlg[0];
			if( $bxlg > 0 ) {
				$botlg['login'] = $botlg['login'].' (копия '.$bxlg.')';
			}
			$botlg['city_reg'] = $u->info['city'];
			$botlg['sex'] = 0;
			$botlg['time_reg'] = time()-rand(10,70)*8640;
			$botlg['obraz'] = '0.gif';
			$botlg['priems'] = '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0';
			//
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
					`bot_id`,
					`inTurnir`
				) VALUES (
					"'.$botlg['align'].'",
					"'.$botlg['login'].'",
					"'.$botlg['level'].'",
					"'.md5('bot_pass_masterpass'.$botlg['login'].'_').'",
					"'.$u->info['city'].'",
					"'.$botlg['city_reg'].'",
					"'.$botlg['login'].'",
					"'.$botlg['sex'].'",
					"",
					"",
					"'.$botlg['time_reg'].'",
					"'.$botlg['obraz'].'",
					"-1",
					"0"
			)');
			if($ins1) {
				$uid = mysql_insert_id();
				$statss = $u->lookStats($botlg['stats']);
				$ins2 = mysql_query('INSERT INTO `stats` (
					`id`,`stats`,`hpNow`,`upLevel`,`bot`,`priems`,`zv`
				) VALUES (
					"'.$uid.'","'.$botlg['stats'].'","1000000","'.$botlg['up'].'","1","'.$botlg['priems'].'","'.$zvid.'"
				)');
				if($ins2) {							
					//копируем предметы
					$i = 0;
					$irk = '';
					$irk1 = 0;
					$irk2 = 0;
					while( $i < 20 ) {
						if( $botlg['w'.$i] > 0 ) {
							if( $i > 0 && $i < 18 ) {
								$irk .= '`id` = "'.$botlg['w'.$i].'" OR';
							}
							$itma = $u->addItem($botlg['w'.$i],$uid);
							mysql_query('UPDATE `items_users` SET `inOdet` = "'.$i.'" WHERE `id` = "'.$itma.'" LIMIT 1');
						}
						$i++;
					}
					$botlg['btl_cof'] = 0;
					if( $irk != '' || $type == 1 ) {	
						if( $type == 1 ) {
							$irk1 = 1;
						}else{
							$irk = rtrim($irk,' OR');
							$irk1 = mysql_fetch_array(mysql_query('SELECT SUM(`price1`) FROM `items_main` WHERE '.$irk.''));
							$irk1 = $irk1[0];
						}
						if( $irk1 > 0 ) {
							mysql_query('UPDATE `stats` SET `btl_cof` = "'.$irk1.'" WHERE `id` = "'.$uid.'" LIMIT 1');
							$botlg['btl_cof'] += $irk1;
						}
					}
					//			
					$r = array(
						'id'	=> $uid,
						'login'	=> $botlg['login'],
						'level'	=> $botlg['level'],
						'align'	=> $botlg['align'],
						'clan'	=> $botlg['clan'],
						'btl_cof'	=> $botlg['btl_cof']
					);
				}
			}
		}
		return $r;
	}
	
	public function testCronZv()
	{
		global $code,$c,$u;
		
		$back_test = false;
		
		//Проверяем турниры в этом городе
		$sp = mysql_query('SELECT * FROM `turnirs` WHERE `status` != "-1"');
		while($pl = mysql_fetch_array($sp)) {
			
			//Начало турнира
			if($pl['status'] == 0 && $pl['time'] < time()) {
				if($pl['users_in'] > 1) {
					//Начало турнира
					mysql_query('UPDATE `turnirs` SET `time` = "'.(time() + $pl['time3']).'",`status` = "1" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					//mysql_query('UPDATE `users` SET `inTurnirnew` = "0" WHERE `inTurnirnew` = "'.$pl['id'].'"');
					
					$usp = mysql_query('SELECT * FROM `users` WHERE `inTurnirnew` = "'.$pl['id'].'" LIMIT '.$pl['users_in']);
					while($ur = mysql_fetch_array($usp))
					{
							mysql_query('INSERT INTO `users` (`login`,`room`,`name`,`sex`,`level`,`inTurnirnew`,`bithday`,`activ`) VALUES ("'.$ur['login'].'","318","'.$ur['name'].'","'.$ur['sex'].'","'.$t['level'].'","'.$pl['id'].'","01.01.2001","0")');
							$uri = mysql_insert_id();
							mysql_query('INSERT INTO `users_turnirs` (`uid`,`bot`,`turnir`) VALUES ("'.$ur['id'].'","'.$uri.'","'.$pl['id'].'")');
							$zid = 0;
							$x1 = 0;
							$y1 = 0;
							mysql_query('INSERT INTO `stats` (`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`) VALUES ("98","'.$zid.'","'.$uri.'","s1=3|s2=3|s3=3|s4=3|s5=0|s6=0|rinv=40|m9=5|m6=10","0","0","0",'.$x1.','.$y1.')');
							mysql_query('UPDATE `users` SET `inUser` = "'.$uri.'" WHERE `id` = "'.$ur['id'].'" LIMIT 1');	
							//Добавляем эффекты скорость регена и запрет передвижения
							
					}
					
				}else{
					//Отмена турнира
					mysql_query('UPDATE `turnirs` SET `time` = "'.(time() + $pl['time2']).'",`users_in` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `inTurnirnew` = "0" WHERE `inTurnirnew` = "'.$pl['id'].'"');
				}
			}
			
		}
		
		//Проверяем хаотичные и групповые бои в этом городе		
		$sp = mysql_query('SELECT * FROM `zayvki` AS `z` WHERE `z`.`btl_id` = "0" AND `z`.`cancel` = "0" AND `z`.`start` = "0" AND (`z`.`razdel` = 4 OR `z`.`razdel` = 5) ORDER BY `z`.`id` DESC LIMIT 100');
		while($pl = mysql_fetch_array($sp))
		{
			$uz = mysql_query('SELECT `u`.`sex`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$pl['id'].'"');
			$tm1 = array();
			$tm2 = array();
			$i = array();
			$toChat = '';
			$toWhere = '';
			while($t = mysql_fetch_array($uz))
			{
				if(!isset(${'tm'.$t['team']})){ ${'tm'.$t['team']} = array(); }
				if(!isset($i[$t['team']])){ $i[$t['team']] = 0; }
				${'tm'.$t['team']}[$i[$t['team']]] = $t;
				$toChat .= ''.$t['login'].',';
				$toWhere .= 'OR `id` = "'.$t['id'].'" ';
				$i[$t['team']]++;
			}
			if($pl['time_start'] <= time()-$pl['time'] || ($pl['razdel']==4 && $i[1]>=$pl['tm1max'] && $i[2]>=$pl['tm2max']))
			{
				$toChat = rtrim($toChat,',');
				$toWhere = ltrim($toWhere,'OR ');
				if($pl['razdel']==4)
				{
					//группы
					if(!isset($i[1]) || !isset($i[2]))
					{
						//группа не набрана
						$this->cancelGroup($pl,$toChat);
					}else{
						//Начинаем поединок
						$this->startBattle($pl['id'],$toChat.'|-|'.$toWhere);
					}
				}elseif($pl['razdel']==5)
				{
					//хаоты
					$i = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$pl['id'].'" LIMIT 1'));
					if($i[0] < 4 && ($pl['fastfight'] == 0 || $i[0] < 2))
					{
						$rcf = mysql_fetch_array(mysql_query('SELECT `id`,`btl_cof` FROM `stats` WHERE `zv` = "'.$pl['id'].'" ORDER BY `btl_cof` DESC LIMIT 1'));
						$rcf = $rcf['btl_cof'];
						//группа не набрана
						//Добавляем недостающих игроков
						$lvl_btl_exp = array(
							0 =>           0,
							1 =>         110,
							2 =>         420,
							3 =>        1300,
							4 =>        2500,
							5 =>        5000,
							6 =>       12500,
							7 =>       30000,
							8 =>      300000,
							9 =>     3000000,
							10 =>   10000000,
							11 =>   52000000,
							12 =>   63000000,
							13 =>  182000000,
							14 =>  212000000,
							15 =>  352000000,
							16 =>  504000000,
							17 => 1187000000,
							18 => 2455000000,
							19 => 4387000000,
							20 => 6355000000,							
							21 =>15500000000,							
							22 =>755500000000
						);
						$bot_users = array();
						
						if( $pl['min_lvl_1'] <= 8 && $pl['max_lvl_1'] <= 8 && $pl['nobot'] == 0) {
							$bsp = mysql_query('SELECT
								`u`.`id`,
								`u`.`login`,
								`u`.`level`,
								`s`.`stats`,
								`u`.`cityreg`,
								`u`.`sex`,
								`u`.`obraz`,
								`s`.`upLevel`,
								`s`.`priems`,
								`s`.`btl_cof`
							FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `s`.`exp` >= '.$lvl_btl_exp[$pl['min_lvl_1']].' AND `s`.`exp` < '.$lvl_btl_exp[$pl['max_lvl_1']+1].' AND `s`.`bot` = "0" ORDER BY `s`.`btl_cof` DESC LIMIT 25');
							while( $bpl = mysql_fetch_array($bsp) ) {
								$bot_users[] = $bpl;
							}
						}
							
						$mincs = 4;
						if( $pl['fastfight'] > 0 ) {
							$mincs = 2;
						}
											
						if( count($bot_users) == 0 ) {
							if($i[0] < 4 && ($pl['fastfight'] == 0 || $i[0] < 2)) {
								$text = ' Не удалось начать поединок по причине: Группа не набрана. ('.$pl['id'].': '.count($bot_users).' '.$lvl_btl_exp[$pl['min_lvl_1']].'-'.$lvl_btl_exp[$pl['max_lvl_1']+1].')';
								mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$zv['city']."','','','LEL','".$text."','".time()."','6','0')");
								$this->cancelGroup($pl,$toChat);
							}
						}else{							
							$j = 0; $k = 0;
							$bot_users_new = array();
							while( $j < $mincs-$i[0] ) {
								$botlg = $bot_users[rand(0,count($bot_users)-1)];
								$j++;
								$clone = array(
									'id' => $botlg['id'],
									'login' => 'Боец (клон '.$j.')',
									'level' => $botlg['level'],
									'city' => $pl['city'],
									'cityreg' => $pl['city'],
									'name' => 'Боец',
									'sex' => $botlg['sex'],
									'deviz' => '',
									'hobby' => '',
									'time_reg' => time(),
									'obraz' => $botlg['obraz'],
									'stats' => $botlg['stats'],
									'upLevel' => $botlg['upLevel'],
									'priems' => $botlg['priems'],
									'loclon' => true
								);
								$bot = $u->addNewbot(1,NULL,$clone,NULL,true);
								if( $bot > 0 ) {
									mysql_query('UPDATE `stats` SET `btl_cof` = "'.$botlg['btl_cof'].'",`zv` = "'.$pl['id'].'",`hpNow` = "100000",`mpNow` = "100000" WHERE `id` = "'.$bot.'" LIMIT 1');
									mysql_query('UPDATE `users` SET `room` = "303",`battle` = "0" WHERE `id` = "'.$bot.'" LIMIT 1');
									$k++;
								}
							}
							unset($bot_users,$bpl,$bsp,$bot);
							//$this->cancelGroup($pl,$toChat);
							if( $k+$i[0] >= 4 || ($pl['fastfight'] == 0 || $k+$i[0] >= 2) ) {
								$back_test = true;
								//$this->startBattle($pl['id'],$toChat.'|-|'.$toWhere);
							}
						}
					}else{
						//Начинаем поединок
						$this->startBattle($pl['id'],$toChat.'|-|'.$toWhere);
					}
				}
			}
		}
		
		if( $back_test == true ) {
			$this->testCronZv();
		}
		
	}
	
	public function userInfo()
	{
		global $u,$c;
			$r = '';
			if($u->stats['mpAll']>0)
			{
				$pm = $u->stats['mpNow']/$u->stats['mpAll']*100;
			}
			$ph = $u->stats['hpNow']/$u->stats['hpAll']*100;
			$dp = '';
			if($u->stats['mpAll']<=0)
			{
				$dp = 'margin-top:13px;';
			}
			$r .= '<table border="0" cellspacing="0" cellpadding="0" height="20">
<tr><td valign="middle"> &nbsp; <font>'.$u->microLogin($u->info['id'],1).'</font> &nbsp; </td>
<td valign="middle" width="120">
<div style="position:relative;'.$dp.'"><div id="vhp'.($u->info['id']).'" title="Уровень жизни" align="left" class="seehp" style="position:absolute; top:-10px; width:120px; height:10px; z-index:12;"> '.floor($u->stats['hpNow']).'/'.$u->stats['hpAll'].'</div>
<div title="Уровень жизни" class="hpborder" style="position:absolute; top:-10px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div class="hp_3 senohp" style="height:9px; width:'.floor(120/100*$ph).'px; position:absolute; top:-10px; z-index:11;" id="lhp'.($u->info['id']).'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div title="Уровень жизни" class="hp_none" style="position:absolute; top:-10px; width:120px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div>
';

if($u->stats['mpAll']>0)
{
	$r .= '<div id="vmp'.($u->info['id']).'" title="Уровень маны" align="left" class="seemp" style="position:absolute; top:0px; width:120px; height:10px; z-index:12;"> '.floor($u->stats['mpNow']).'/'.$u->stats['mpAll'].'</div>
<div title="Уровень маны" class="hpborder" style="position:absolute; top:0px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div class="hp_mp senohp" style="height:9px; position:absolute; top:0px; width:'.floor(120/100*$pm).'px; z-index:11;" id="lmp'.($u->info['id']).'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div title="Уровень маны" class="hp_none" style="position:absolute; top:0px; width:120px; height:10px; z-index:10;"></div>';
}
$r .= '</div></td></tr></table>';
		unset($stt,$ph,$pm);
		return $r;
	}
	
	public function cancelGroup($zv,$uids)
	{
		global $u;
		if( $zv['priz'] > 0 ) {
			$sp = mysql_query('SELECT `id` FROM `stats` WHERE `zv` = "'.$zv['id'].'"');
		}
		$upd = mysql_query('UPDATE `stats` SET `zv` = "0" WHERE `zv` = "'.$zv['id'].'"');
		if($upd)
		{
			$upd = mysql_query('UPDATE `zayvki` SET `cancel` = "'.time().'" WHERE `id` = "'.$zv['id'].'"');
			if($upd && $uids != '')
			{
				if( $zv['priz'] > 0 ) {
					while( $pl = mysql_fetch_array($sp) ) {
						//Выдаем по 1 жетону
						$u->addItem(4754,$pl['id'],'');
					}
					$text = ' Не удалось начать поединок по причине: Группа не набрана. Вы получаете Призовой Жетон (х1)';
				}else{
					$text = ' Не удалось начать поединок по причине: Группа не набрана.';
				}
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$zv['city']."','','','".$uids."','".$text."','".time()."','6','0')");
			}
		}
	}
	
	public function add()
	{
		global $u,$c,$code;
		if(isset($_GET['r']) && $u->info['inTurnirnew']==0)
		{
			$r = round(intval($_GET['r']));
			if($r>=1 && $r<=5)
			{
				$az = 1;
				if($r==1 && $u->info['level']>0){	$az = 0; $this->error = 'Вы уже выросли из ползунков ;)';	}
				if(($r==2 || $r==3)  && $u->info['level']<1){	$az = 0; $this->error = 'Вы еще не выросли из ползунков ;)';	}
				if(($r==4 || $r==5)  && $u->info['level']<2){	$az = 0; $this->error = 'В '.$this->z1n[$r].' бои только с второго уровня.';	}
				if(!isset($_POST['stavkakredit'])){ $_POST['stavkakredit'] = 0; }
				$money = (int)($_POST['stavkakredit']*100);
				$money = round(($money/100),2);
				                
                if( $this->testTravm() == 1 &&  $_POST['k'] != 1 ) {
					$this->error = 'Вы травмированы. С такими увечьями доступны кулачные бои.';
					$az = 0;
				}elseif( $this->testTravm() == 2 ) {
					$this->error = 'Вы травмированы. С такими увечьями невозможно сражаться.';
					$az = 0;
				}elseif($u->info['hpNow']<$u->stats['hpAll']/100*30 && ($r>=1 || $r<=3)) {
					$this->error = 'Вы еще слишком ослаблены чтобы начать новый бой';
					$az = 0;
				} elseif($r==3 && $money>0 && $u->info['level']<4) {
					$this->error = 'Бои на деньги проводятся с 4-го уровня';
					$az = 0;
				} elseif($r==3 && $money<0.5 && $money>0) {
					$this->error = 'Минимальная ставка 0.50 кр.';
					$az = 0;
				} elseif($r==3 && $money>30) {
					$this->error = 'Максимальная ставка 30.00 кр.';
					$az = 0;
				} elseif($r==3 && $money>$u->info['money']) {
					$this->error = 'У Вас недостаточно денег, чтобы подать заявку';
					$az = 0;
				}
				if($u->info['zv']>0){ $az = 0; $this->error = 'Вы уже находите в заявке.'; }
				if($az==1)
				{
					$nz = array();
					$nz['city'] = $u->info['city'];
					$nz['creator'] = $u->info['id'];
					$nz['type'] = 0;
					if($_POST['k']==1){	$nz['type'] = 1; }
					$_POST['timeout'] = round(intval(mysql_real_escape_string($_POST['timeout'])));
					if($_POST['timeout']==1 || $_POST['timeout']==2 || $_POST['timeout']==3 || $_POST['timeout']==4 || $_POST['timeout']==5)
					{
						$nz['timeout'] = $_POST['timeout']*60;
					}else{
						$nz['timeout'] = 3*60;
					}
					if($r==3)
					{
						if($_POST['onlyfor']!='')
						{
							$nz['withUser'] = mysql_real_escape_string($_POST['onlyfor']);
						}
					}
					$nz['razdel'] = $r;
					$nz['time_start'] = 0;
					$nz['min_lvl_1'] = 0;
					$nz['min_lvl_2'] = 0;
					$nz['max_lvl_1'] = 21;
					$nz['max_lvl_2'] = 21;
					$nz['tm1max'] = 0;
					$nz['tm2max'] = 0;
					$nz['travmaChance'] = 0;
					$nz['invise'] = 0;
					$nz['money'] = 0;
					$nz['comment'] = '';
					$nz['tm1'] = 0;
					$nz['tm2'] = 0;
					$nz['otmorozok'] = 0;
					$gad = 1;
					if($r==3)
					{
						$nz['money'] = $money;
					}
					if($r==5 && $u->info['level']>1)
					{
						//хаотичный бой
						if($_POST['startime2'])
						{
							$nz['time_start'] = (int)$_POST['startime2'];
							$nz['comment'] = substr($_POST['cmt'], 0, 40);
							$nz['comment'] = str_replace('"','&quot;',$nz['comment']);
							$nz['comment'] = htmlspecialchars($nz['comment'],NULL,'cp1251');
							if($nz['time_start']!=180 && $nz['time_start']!=300 && $nz['time_start']!=600 && $nz['time_start']!=900 && $nz['time_start']!=1200 && $nz['time_start']!=1800)
							{
								$nz['time_start'] = 600;
							}
							
							if(isset($_POST['mut_hidden']))
							{
								$nz['invise'] = 1;
							}
							if(isset($_POST['noinc']))	{
								$nz['noinc'] = 1;
							}
							if(isset($_POST['fastfight']))	{
								$nz['fastfight'] = 1;
							}
							if(isset($_POST['otmorozok'])) {
								$nz['otmorozok'] = 1;
							}
							if(isset($_POST['nobot']))	{
								$nz['nobot'] = 1;
							}
							if(isset($_POST['kingfight']))	{
								$nz['kingfight'] = 1;
							}
							if(isset($_POST['arand']))	{
								$nz['arand'] = 1;
							}
							if(isset($_POST['noatack']))	{
								$nz['noatack'] = 1;
							}
							if(isset($_POST['noeff']))	{
								$nz['noeff'] = 1;
							}
							if(isset($_POST['smert']))	{
								$nz['smert'] = 1;
							}
							if(isset($_POST['noart']))	{
								$nz['noart'] = 1;
							}
							if( $nz['kingfight'] == 1 && $nz['fastfight'] == 1 ) {
								$nz['kingfight'] = 0;
							}
							
							$nz['timeout'] = (int)$_POST['timeout'];
							if($nz['timeout']!=1 && $nz['timeout']!=2 && $nz['timeout']!=3 && $nz['timeout']!=4 && $nz['timeout']!=5)
							{
								$nz['timeout'] = 3;
							}
							
							//Генерируем уровни союзника
							$lvl = (int)$_POST['levellogin1'];
							if($lvl == 0)
							{
								$nz['min_lvl_1'] = 2;
								$nz['max_lvl_1'] = 21;
							}elseif($lvl == 3)
							{
								$nz['min_lvl_1'] = $u->info['level'];
								$nz['max_lvl_1'] = $u->info['level'];
							}elseif($lvl == 6)
							{
								$nz['min_lvl_1'] = $u->info['level']-1;
								$nz['max_lvl_1'] = $u->info['level']+1;
							}else{
								$nz['min_lvl_1'] = 2;
								$nz['max_lvl_1'] = 2;
							}
							
							if((int)$_POST['k']==1)
							{
								//кулачный бой
								$nz['type'] = 1;
							}
							
							$nz['timeout'] = $nz['timeout']*60;
							
							$nz['tm1'] = $u->stats['reting'];
							
							if( $u->info['no_zv_key'] != true ) { 
								if( $_POST['code21'] == 0 || $_POST['code21'] != $_SESSION['code2'] || $_SESSION['code2'] == 0 || !isset($_SESSION['code2']) ) {
									$this->error = 'Неправильный код подтверждения';
									$gad = 0;
								}
							}
							
						}else{
							$gad = 0; $this->error = 'Что-то не так...<br>';
						}
					}elseif($r==4 && $u->info['level']>1)
					{
						//групповой бой
						//'Array ( [startime] => 300 [timeout] => 1 [nlogin1] => 11 [levellogin1] => 0 [nlogin2] => 11 [levellogin2] => 0 [k] => 1 [travma] => on [mut_clever] => on [cmt] => тест [open] => Начнем месилово! :) )';
						//здесь заносим и проверяем данные на гурпповой бой
						if($_POST['startime'])
						{
							$nz['time_start'] = (int)$_POST['startime'];
							$nz['comment'] = substr($_POST['cmt'], 0, 40);
							$nz['comment'] = str_replace('"','&quot;',$nz['comment']);
							if($nz['time_start']!=300 && $nz['time_start']!=600 && $nz['time_start']!=900 && $nz['time_start']!=1200 && $nz['time_start']!=1800)
							{
								$nz['time_start'] = 600;
							}
							
							$nz['timeout'] = (int)$_POST['timeout'];
							if($nz['timeout']!=1 && $nz['timeout']!=2 && $nz['timeout']!=3 && $nz['timeout']!=4 && $nz['timeout']!=5)
							{
								$nz['timeout'] = 3;
							}
							
							$nz['timeout'] = $nz['timeout']*60;
							
							$nz['tm1max'] = (int)$_POST['nlogin1'];
							if($nz['tm1max']<1 || $nz['tm1max']>99)
							{
								$this->error .= 'Неверное кол-во союзников<br>';
								$gad = 0;
							}
							
							$nz['tm2max'] = (int)$_POST['nlogin2'];
							if($nz['tm2max']<1 || $nz['tm2max']>99)
							{
								$this->error .= 'Неверное кол-во противников<br>';
								$gad = 0;
							}
							
							if( $this->testTravm() == 1 &&  $_POST['k'] != 1 ) {
								$this->error = 'Вы травмированы. С такими увечьями доступны кулачные бои.';
								$gad = 0;
							}elseif( $this->testTravm() == 2 ) {
								$this->error = 'Вы травмированы. С такими увечьями невозможно сражаться.';
								$gad = 0;
							}elseif($nz['tm1max']+$nz['tm2max']<3)
							{
								$this->error .= 'Заявки 1 на 1 подаются в разделе физические или договорные бои<br>';
								$gad = 0;
							}
														
							//Генерируем уровни союзника
							$lvl = (int)$_POST['levellogin1'];
							if($lvl == 0)
							{
								$nz['min_lvl_1'] = 2;
								$nz['max_lvl_1'] = 21;
							}elseif($lvl == 1)
							{
								$nz['min_lvl_1'] = 2;
								$nz['max_lvl_1'] = $u->info['level'];
							}elseif($lvl == 2)
							{
								$nz['min_lvl_1'] = 2;
								$nz['max_lvl_1'] = $u->info['level']-1;
							}elseif($lvl == 3)
							{
								$nz['min_lvl_1'] = $u->info['level'];
								$nz['max_lvl_1'] = $u->info['level'];
							}elseif($lvl == 4)
							{
								$nz['min_lvl_1'] = $u->info['level'];
								$nz['max_lvl_1'] = $u->info['level']+1;
							}elseif($lvl == 5)
							{
								$nz['min_lvl_1'] = $u->info['level']-1;
								$nz['max_lvl_1'] = $u->info['level'];
							}elseif($lvl == 6)
							{
								$nz['min_lvl_1'] = $u->info['level']-1;
								$nz['max_lvl_1'] = $u->info['level']+1;
							}elseif($lvl == 6){
								$nz['min_lvl_1'] = 99;
							}else{
								$this->error = 'Что-то не так...<br>';
								$gad = 0;
							}
							
							//Генерируем уровни противника
							$lvl = (int)$_POST['levellogin2'];
							if($lvl == 0)
							{
								$nz['min_lvl_2'] = 2;
								$nz['max_lvl_2'] = 21;
							}elseif($lvl == 1)
							{
								$nz['min_lvl_2'] = 2;
								$nz['max_lvl_2'] = $u->info['level'];
							}elseif($lvl == 2)
							{
								$nz['min_lvl_2'] = 2;
								$nz['max_lvl_2'] = $u->info['level']-1;
							}elseif($lvl == 3)
							{
								$nz['min_lvl_2'] = $u->info['level'];
								$nz['max_lvl_2'] = $u->info['level'];
							}elseif($lvl == 4)
							{
								$nz['min_lvl_2'] = $u->info['level'];
								$nz['max_lvl_2'] = $u->info['level']+1;
							}elseif($lvl == 5)
							{
								$nz['min_lvl_2'] = $u->info['level']-1;
								$nz['max_lvl_2'] = $u->info['level'];
							}elseif($lvl == 6)
							{
								$nz['min_lvl_2'] = $u->info['level']-1;
								$nz['max_lvl_2'] = $u->info['level']+1;
							}elseif($lvl == 6){
								$nz['min_lvl_2'] = 99;
							}else{
								$this->error = 'Что-то не так...<br>';
								$gad = 0;
							}
							
							if($nz['min_lvl_1']<2){ $nz['min_lvl_1'] = 2; }
							if($nz['max_lvl_1']>21){ $nz['max_lvl_1'] = 21; }
							if($nz['min_lvl_2']<2){ $nz['min_lvl_2'] = 2; }
							if($nz['max_lvl_2']>21){ $nz['max_lvl_2'] = 21; }
														
							if((int)$_POST['k']==1)
							{
								//кулачный бой
								$nz['type'] = 1;
							}
							
						}else{
							$gad = 0;
							$this->error = 'Что-то не так...<br>';
						}
					}
					
					$test = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `zayvki` WHERE
					
						`min_lvl_1` = "'.$nz['min_lvl_1'].'" AND
						`min_lvl_2` = "'.$nz['min_lvl_2'].'" AND
						`max_lvl_1` = "'.$nz['max_lvl_1'].'" AND
						`max_lvl_2` = "'.$nz['max_lvl_2'].'" AND
						`type` = "'.$nz['type'].'" AND `start` = "0" AND `cancel` = "0"
					
					'));
					
					if($c['max_zv_analog'] > 0 && $test['id'] >= $c['max_zv_analog'] && $r == 5) {
						$gad = 0;
						$this->error = '<div>Аналогичная заявка уже подана, примите её...</div>';
					}
					
					$bt2 = (int)$_POST['bots2'];
					if($bt2!=0 && $r==4 && $u->info['level']>1){ $bt2 = 1; $nz['min_lvl_2'] = $u->info['level']; $nz['max_lvl_2'] = $u->info['level']; $nz['min_lvl_1'] = $u->info['level']; $nz['max_lvl_1'] = $u->info['level'];  }else{ $bt2 = 0; }				
					/*if( ($u->info['level'] == 8 || $u->info['level'] == 9) && $r == 5 ) {
						$test_zv_lvl = mysql_fetch_array(mysql_query('SELECT `id` FROM `zayvki` WHERE `city` = "'.$u->info['city'].'" AND `cancel` = "0" AND `min_lvl_1` = '.$u->info['level'].' AND `max_lvl_1` = '.$u->info['level'].' AND `btl_id` = 0 AND `razdel` = 5 LIMIT 1'));
						if( isset($test_zv_lvl['id']) ) {
							$gad = 0;
							$this->error = 'Заявка для вашего уровня уже есть. Примите ее.';
						}
					}*/
					//$test_zvl = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `creator` = "'.$u->info['id'].'" AND `start` = 0 AND `cancel` = 0 LIMIT 1'));										
					//if(isset($test_zvl['id'])) {
					//	$gad = 0;
					//	$this->error = 'Вы уже подали заявку... № '.$test_zvl['id'].'<br>';
					//}
					if($gad==1)
					{
						if(!isset($nz['withUser'])){ $nz['withUser'] = ''; }
						$nz['time_create_zv'] = time();
						if( $nz['razdel'] == 4 || $nz['razdel'] == 5 ) {
							//Округляем время для кроны
							$nz['time_create_zv'] = strtotime(date('d.m.Y H:i',$nz['time_create_zv']).':00',$nz['time_create_zv']);
						}elseif( $nz['razdel'] == 3 ) {
							$nz['noinc'] = 1;	
						}
						$nz['teams'] = 2;
						$nz['align1'] = 0;
						$nz['align2'] = 0;
						$nz['align3'] = 0;
						if( isset($_POST['3align']) ) {
							$nz['teams'] = 3;
							$nz['min_lvl_1'] = 2;
							$nz['min_lvl_2'] = 2;
							$nz['max_lvl_1'] = 21;
							$nz['max_lvl_2'] = 21;
							//
							if( floor($u->info['align']) == 3 ) {
								$nz['align1'] = 3;
								$nz['align2'] = 1;
								$nz['align3'] = 7;
							}elseif( floor($u->info['align']) == 7 ) {
								$nz['align1'] = 7;
								$nz['align2'] = 1;
								$nz['align3'] = 3;
							}else{
								$nz['align1'] = 1;
								$nz['align2'] = 3;
								$nz['align3'] = 7;
							}
							//
						}
						if( $nz['razdel'] == 5 ) {
							mysql_query('UPDATE `stats` SET `zv_enter` = "'.time().'" WHERE `id` = "'.$u->info['id'].'"');
						}
						$ins = mysql_query('INSERT INTO `zayvki` (`otmorozok`,`align1`,`align2`,`align3`,`teams`,`smert`,`noart`,`noeff`,`noatack`,`arand`,`kingfight`,`nobot`,`fastfight`,`noinc`,`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
																"'.$nz['otmorozok'].'",
																"'.$nz['align1'].'",
																"'.$nz['align2'].'",
																"'.$nz['align3'].'",																
																"'.$nz['teams'].'",
																"'.$nz['smert'].'",
																"'.$nz['noart'].'",
																"'.$nz['noeff'].'",
																"'.$nz['noatack'].'",
																"'.$nz['arand'].'",
																"'.$nz['kingfight'].'",
																"'.$nz['nobot'].'",
																"'.$nz['fastfight'].'",
																"'.$nz['noinc'].'",
																"0",
																"'.((int)$bt2).'",
																"'.$nz['time_create_zv'].'",
																"'.$nz['city'].'",
																"'.$nz['creator'].'",
																"'.$nz['type'].'",
																"'.$nz['time_start'].'",
																"'.mysql_real_escape_string($nz['timeout']).'",
																"'.mysql_real_escape_string($nz['min_lvl_1']).'",
																"'.mysql_real_escape_string($nz['min_lvl_2']).'",															
																"'.mysql_real_escape_string($nz['max_lvl_1']).'",
																"'.mysql_real_escape_string($nz['max_lvl_2']).'",
																"'.mysql_real_escape_string($nz['tm1max']).'",
																"'.mysql_real_escape_string($nz['tm2max']).'",
																"'.$nz['travmaChance'].'",
																"'.$nz['invise'].'",
																"'.$nz['razdel'].'",
																"'.mysql_real_escape_string($nz['comment']).'",
																"'.mysql_real_escape_string($nz['money']).'",
																"'.$nz['withUser'].'","'.$nz['tm1'].'","'.$nz['tm2'].'")');
						$zid = mysql_insert_id();
						if($ins)
						{
							mysql_query('UPDATE `stats` SET `zv`="'.$zid.'",`team`="1" WHERE `id`="'.$u->info['id'].'" LIMIT 1');
							$u->info['zv'] = $zid;
							$this->error = 'Заявка на бой подана';
						}else{
							$this->error = 'Вы не смогли подать заявку...';
						}
					}
				}
			}
		}
	}

	//тренеровочный бой
	public function addBot()
	{
		global $u,$c,$code;
		/*$trEn = 1;
		
		if($u->info['level'] == 0) {
			/*
				14 опыта за бой
				8 побед
			*/
			//$trEn = 0;
		//}elseif($u->info['level'] == 1) {
			/*
				27 опыта за бой
				12 побед
			*/
			//$trEn = 1;
		//}elseif($u->info['level'] == 2) {
			/*
				27 опыта за бой
				12 побед
			*/
			//$trEn = 1;
		//}elseif($u->info['level'] == 3) {
			/*
				27 опыта за бой
				12 побед
			*/
			//$trEn = 1;
		//}elseif($u->info['level'] == 4) {
			/*
				27 опыта за бой
				12 побед
			*/
			//$trEn = 1;
		//}else{
		//	$trEn = floor($u->info['level']+(1.25*$u->info['level']));
		//}
		
		//if($u->info['level']>5 && $u->info['admin']==0) {
		if(($u->info['level'] <= $c['bot_level'] || $u->info['admin'] > 0) && ($u->info['exp'] != 12499 || $u->info['admin'] > 0)) {
		//if($trEn > $u->info['enNow']) {
			$bot = $u->addNewbot($id['id'],NULL,$u->info['id'],NULL,true);
		}else{
			$bot = false;
		}
		if($bot==false)
		{
			//if($trEn > $u->info['enNow']) {
			//	$this->error = 'Недостаточный уровень энергии для начала поединка. Требуется: '.$trEn.' ед., у вас ['.floor(0+$u->info['enNow']).'/'.(0+$u->stats['enAll']).']<br>'.
			//					'<small>Для увеличения уровня энергии - увеличьте характеристику "Энергия", либо воспользуйтесь эликсирами и заклятиями!</small>';
			//}else{
				$this->error = 'Бои с монстрами, нежитью, клонами и прочими вурдалаками проводятся только для персонажей младше 8 уровня...<br>Со стороны посматривает Общий Враг, ему явно что-то не понравилось...<br>';
			//}
		}elseif($u->info['hpNow']<$u->stats['hpAll']/100*30 && ($r>=1 || $r<=3))
		{
			$this->error = 'Вы еще слишком ослаблены чтобы начать новый бой';
			$az = 0;
		}elseif($u->info['align'] == 2)
		{
			$this->error = 'Хаосники не могут сражаться здесь';
			$az = 0;
		}elseif($bot==false)
		{
			echo '<br><font color=red>Cannot start battle (no prototype "ND0Clone")</font><br>';
		}else{
			//создаем поединок с ботом
			$expB = 0;
			$btl = array('smert' => 0,'noart' => 0,'noeff' => 0,'otmorozok'=>0,'noatack' => 0,'priz' => 0 , 'arand' => 0,'kingfight' => 0,'nobot' => 0,'fastfight' => 0,'players'=>'','timeout'=>60,'type'=>0,'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>$expB,'money'=>0,'money3'=>0);
			//
			$btl['type'] = 28;
			//
			$ins = mysql_query('INSERT INTO `battle` (`otmorozok`,`smert`,`noart`,`noeff`,`noatack`,`arand`,`kingfight`,`nobot`,`fastfight`,`clone`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`,`priz`) VALUES (
												"'.$btl['otmorozok'].'",
												"'.$btl['smert'].'",
												"'.$btl['noart'].'",
												"'.$btl['noeff'].'",
												"'.$btl['noatack'].'",
												"'.$btl['arand'].'",
												"'.$btl['kingfight'].'",
												"'.$btl['nobot'].'",
												"'.$btl['fastfight'].'",												
												"1",
												"'.$u->info['city'].'",
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
												"'.$btl['priz'].'")');
			if($ins)
			{
				$btl_id = mysql_insert_id();
				//обновляем данные о поединке	
				$u->info['enNow'] -= $trEn;					
				$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" OR `id` = "'.$bot.'" LIMIT 2');
				mysql_query('UPDATE `stats` SET `team`="1",`enNow` = "'.$u->info['enNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpAll'].'",`team`="2" WHERE `id` = "'.$bot.'" LIMIT 1');
				//Если бой кулачный, то снимаем вещи
				if($btl['type']==1)
				{
					mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$u->info['id'].'" AND `inOdet`!=0');
					mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$bot.'" AND `inOdet`!=0');
				}
				
				//обновляем заявку, что бой начался
				$u->info['battle'] = $btl_id;
				//Отправляем сообщение в чат всем бойцам
				mysql_query("INSERT INTO `chat` (`city`,`room`,`to`,`time`,`type`,`toChat`,`sound`) VALUES ('".$u->info['city']."','".$u->info['room']."','".$u->info['login']."','".time()."','11','0','117')");
				die('<script>location="main.php?battle_id='.$btl_id.'";</script>');
			}else{
				$this->error = 'Cannot start battle (no prototype "ABD0Clone")';
			}	
		}
	}

	//тренеровочный бой
	public function addBotClone($uid)
	{
		global $u,$c,$code;
		
		//if($u->info['level']>5 && $u->info['admin']==0) {
		if($u->info['online'] > 0) {
		//if($trEn > $u->info['enNow']) {
			$bot = $u->addNewbot($id['id'],NULL,$uid,NULL,false);
		}else{
			$bot = false;
		}
		if($bot==false)
		{
			//if($trEn > $u->info['enNow']) {
			//	$this->error = 'Недостаточный уровень энергии для начала поединка. Требуется: '.$trEn.' ед., у вас ['.floor(0+$u->info['enNow']).'/'.(0+$u->stats['enAll']).']<br>'.
			//					'<small>Для увеличения уровня энергии - увеличьте характеристику "Энергия", либо воспользуйтесь эликсирами и заклятиями!</small>';
			//}else{
				//$this->error = 'Бои с монстрами, нежитью, клонами и прочими вурдалаками проводятся только для персонажей младше 8 уровня...<br>Со стороны посматривает Общий Враг, ему явно что-то не понравилось...<br>';
				$this->error = 'Не получилось начать поединок';
			//}
		}elseif($u->info['hpNow']<$u->stats['hpAll']/100*30 && ($r>=1 || $r<=3))
		{
			$this->error = 'Вы еще слишком ослаблены чтобы начать новый бой';
			$az = 0;
		}elseif($u->info['align'] == 2)
		{
			$this->error = 'Хаосники не могут сражаться здесь';
			$az = 0;
		}elseif($bot==false)
		{
			echo '<br><font color=red>Cannot start battle (no prototype "ND0Clone")</font><br>';
		}else{
			//создаем поединок с ботом
			$expB = 0;
			$btl = array('priz' => 0 , 'smert' => 0,'noart' => 0,'noeff' => 0,'noatack' => 0,'arand' => 0,'kingfight' => 0,'nobot' => 0,'fastfight' => 0,'players'=>'','timeout'=>60,'type'=>0,'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>$expB,'money'=>0,'money3'=>0);
			$ins = mysql_query('INSERT INTO `battle` (`otmorozok`,`priz`,`smert`,`noart`,`noeff`,`noatack`,`arand`,`kingfight`,`nobot`,`fastfight`,`clone`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
												"'.$btl['otmorozok'].'",
												"'.$btl['priz'].'",
												"'.$btl['smert'].'",
												"'.$btl['noart'].'",
												"'.$btl['noeff'].'",
												"'.$btl['noatack'].'",
												"'.$btl['arand'].'",
												"'.$btl['kingfight'].'",
												"'.$btl['nobot'].'",
												"'.$btl['fastfight'].'",												
												"1",
												"'.$u->info['city'].'",
												"'.time().'",
												"'.$btl['players'].'",
												"'.$btl['timeout'].'",
												"564",
												"'.$btl['invis'].'",
												"'.$btl['noinc'].'",
												"'.$btl['travmChance'].'",
												"'.$btl['typeBattle'].'",
												"'.$btl['addExp'].'",
												"'.$btl['money'].'")');
			if($ins)
			{
				$btl_id = mysql_insert_id();
				//обновляем данные о поединке	
				$u->info['enNow'] -= $trEn;					
				$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" OR `id` = "'.$bot.'" LIMIT 2');
				mysql_query('UPDATE `stats` SET `team`="1",`enNow` = "'.$u->info['enNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot.'" LIMIT 1');
				//Если бой кулачный, то снимаем вещи
				if($btl['type']==1)
				{
					mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$u->info['id'].'" AND `inOdet`!=0');
					mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$bot.'" AND `inOdet`!=0');
				}
				
				//обновляем заявку, что бой начался
				$u->info['battle'] = $btl_id;
				//Отправляем сообщение в чат всем бойцам
				mysql_query("INSERT INTO `chat` (`city`,`room`,`to`,`time`,`type`,`toChat`,`sound`) VALUES ('".$u->info['city']."','".$u->info['room']."','".$u->info['login']."','".time()."','11','0','117')");
				die('<script>location="main.php?battle_id='.$btl_id.'";</script>');
			}else{
				$this->error = 'Cannot start battle (no prototype "ABD0Clone")';
			}	
		}
	}

	
	//Изломы
	public function startIzlom($id2,$lvl)
	{
		global $u,$c,$code;
			$lvl = (int)$lvl;
			
			if( $lvl == 8 ) {
				/*
				Пылающий Паразит
				Кольчатый Страхочервь
				Хлюп
				Яростная Мокрица
				*/
				$bots = array( 'Литейщик','Проклятие Глубин','Пустынник Маньяк','Пустынник Убийца','Рабочий Мглы','Смотритель Мглы','Сторож Мглы' );
			}
			
			$id2 = rand(0,(count($bots)-1));			
			$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `level` <= "'.$u->info['level'].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
			$logins_bot = array();
			$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL);
			
			if(isset($id['id']) && $bot != false)
			{
				$logins_bot = $bot['logins_bot'];
				//создаем поединок с ботом
				$expB = -$bot['expB'];
				$btl = array('priz'=>'','players'=>'','otmorozok'=>0,'timeout'=>60,'type'=>9,'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>$expB,'money'=>0,'izlom'=>(int)$id2,'izlomLvl'=>(int)$lvl);
				$ins = mysql_query('INSERT INTO `battle` (`otmorozok`,`priz`,`smert`,`noart`,`noeff`,`noatack`,`arand`,`kingfight`,`nobot`,`fastfight`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`,`izlom`,`izlomLvl`) VALUES (
													"'.$btl['otmorozok'].'",
													"'.$btl['priz'].'",
													"'.$btl['smert'].'",
													"'.$btl['noart'].'",
													"'.$btl['noeff'].'",
													"'.$btl['noatack'].'",
													"'.$btl['arand'].'",
													"'.$btl['kingfight'].'",
													"'.$btl['nobot'].'",
													"'.$btl['fastfight'].'",
													"'.$u->info['city'].'",
													"'.time().'",
													"'.$btl['players'].'",
													"'.$btl['timeout'].'",
													"'.$btl['type'].'",
													"'.$btl['invis'].'",
													"'.$btl['noinc'].'",
													"'.$btl['travmChance'].'",
													"'.$btl['typeBattle'].'",
													"'.$btl['addExp'].'",
													"'.$btl['money'].'","'.$btl['izlom'].'","'.$btl['izlomLvl'].'")');
				if($ins)
				{
					$btl_id = mysql_insert_id();
					//обновляем данные о поединке						
					$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" OR `id` = "'.$bot['id'].'" LIMIT 2');
					mysql_query('UPDATE `stats` SET `team`="1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
					//Если бой кулачный, то снимаем вещи
					if($btl['type']==1)
					{
						mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$u->info['id'].'" AND `inOdet`!=0');
						mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$bot['id'].'" AND `inOdet`!=0');
					}
					
					//обновляем заявку, что бой начался
					$u->info['battle'] = $btl_id;
					
					//Добавляем еще 2 бота
					$id2 = rand(0,(count($bots)-1));			
					$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `level` <= "'.$u->info['level'].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
					$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL);
					if(isset($id['id']) && $bot != false) {
						$logins_bot = $bot['logins_bot'];
						mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
					}
					$id2 = rand(0,(count($bots)-1));			
					$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `level` <= "'.$u->info['level'].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
					$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL);
					if(isset($id['id']) && $bot != false) {
						$logins_bot = $bot['logins_bot'];
						mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
					}
					
					//Отправляем сообщение в чат всем бойцам
					mysql_query("INSERT INTO `chat` (`city`,`room`,`to`,`time`,`type`,`toChat`,`sound`) VALUES ('".$u->info['city']."','".$u->info['room']."','".$u->info['login']."','".time()."','11','0','117')");
					die('<script>location="main.php?battle_id='.$btl_id.'";</script>');
				}else{
					$this->error = 'Cannot start battle (no prototype "ABD0'.$id['id'].'")';
				}				
			}else{
				echo '<br><font color=red>Cannot start battle (no prototype "ND0IZ'.$lvl.'")</font><br>';
			}
	}

	public function startBattle($id,$vars = NULL)
	{
		global $c,$code,$u;
		mysql_query('START TRANSACTION');
		mysql_query("LOCK TABLES
		`aaa_monsters` WRITE,
		`actions` WRITE,
		`bank` WRITE,
		
		`battle` WRITE,
		`battle_act` WRITE,
		`battle_actions` WRITE,
		`battle_cache` WRITE,
		`battle_end` WRITE,
		`battle_last` WRITE,
		`battle_logs` WRITE,
		`battle_logs_save` WRITE,
		`battle_stat` WRITE,
		`battle_users` WRITE,
		
		`bs_actions` WRITE,
		`bs_items` WRITE,
		`bs_items_use` WRITE,
		`bs_logs` WRITE,
		`bs_map` WRITE,
		`bs_statistic` WRITE,
		`bs_trap` WRITE,
		`bs_turnirs` WRITE,
		`bs_zv` WRITE,
		
		`clan` WRITE,
		`clan_wars` WRITE,
		
		`dungeon_actions` WRITE,
		`dungeon_bots` WRITE,
		`dungeon_items` WRITE,
		`dungeon_map` WRITE,
		`dungeon_now` WRITE,
		`dungeon_zv` WRITE,
		
		`eff_main` WRITE,
		`eff_users` WRITE,
		
		`items_img` WRITE,
		`items_local` WRITE,
		`items_main` WRITE,
		`items_main_data` WRITE,
		`items_users` WRITE,
		
		`izlom` WRITE,
		`izlom_rating` WRITE,
		
		`laba_act` WRITE,
		`laba_itm` WRITE,
		`laba_map` WRITE,
		`laba_now` WRITE,
		`laba_obj` WRITE,
		
		`levels` WRITE,
		`levels_animal` WRITE,
		
		`online` WRITE,
		
		`priems` WRITE,
		
		`quests` WRITE,
		`reimage` WRITE,
		
		`reg` WRITE,
		
		`stats` WRITE,
		`test_bot` WRITE,
		`turnirs` WRITE,
		`users` WRITE,
		`users_animal` WRITE,
		`user_ico` WRITE,
		`users_twink` WRITE,
		`zayvki` WRITE;");
		$z = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id`="'.$id.'" AND `start` = "0" AND `cancel` = "0" AND (`time` > "'.(time()-60*60*2).'" OR `razdel` > 3) LIMIT 1'));
		if(isset($z['id']))
		{
			$vars = explode('|-|',$vars);
			if($z['razdel']>=4 && $z['razdel']<=5)
			{
				//начало группового или хаотичного боя
				$btl_id = 0;
				//$txtz = '';
				if($z['razdel']==5) {
					//Хаот, раскидка по балансу и шмоткам
					$tm_kr = array(0,0,0);
					$tsr = rand(0,2000);
					if($tsr >= 1000) {
						$tsr = 'DESC';
					}else{
						$tsr = 'ASC';
					}
					
					$players = array();
					$kix = 0;	
					$tmsk = rand(1,2);
					$setA = array();
					$setB = array();			
					$sp = mysql_query('SELECT `s`.`id`,`s`.`zv_enter` FROM `stats` AS `s` WHERE `s`.`zv` = "'.$z['id'].'" ORDER BY RAND()');
					while($pl = mysql_fetch_array($sp)) {
						//
						/*$svbl1 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `battle_last` WHERE `uid` = "'.$pl['id'].'" AND `team_win` = `team` AND `team_win` != 0 AND `time` > 1443476291 LIMIT 1'));
						$svbl2 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `battle_last` WHERE `uid` = "'.$pl['id'].'" AND `team_win` != `team` AND `team_win` != 0 AND `time` > 1443476291 LIMIT 1'));
						//
						$pl['btl_cof'] = ($svbl1[0] - $svbl2[0]);
						if( $pl['btl_cof'] > 0 ) {
							$pl['btl_cof'] = $pl['btl_cof']*10;
						}elseif( $pl['btl_cof'] < 0 ) {
							$pl['btl_cof'] = $pl['btl_cof']*5;
						}else{
							$pl['btl_cof'] = 5;
						}*/
						//
						/*if( $z['arand'] == 1 ) {
							//$pl['btl_cof'] = $pl['level'];
							$pl['btl_cof'] = 1;
						}
						$players[] = array(
							'id' => $pl['id'],
							'skill' => ceil($pl['btl_cof'])
						);*/
						//
						//if(isset($pl['id'])) {
							if( $pl['zv_enter'] > 0 && $pl['zv_enter'] < time() ) {
								$pl['zv_enter'] = time() - $pl['zv_enter'];
								if( $pl['zv_enter'] > $z['time_start'] ) {
									 $pl['zv_enter'] = $z['time_start'];
								}
								$pl['zv_enter'] -= 60;
								if( $pl['zv_enter'] < 0 ) {
									$pl['zv_enter'] = 0;
								}
								mysql_query('UPDATE `eff_users` SET `timeUse` = `timeUse` + "'.$pl['zv_enter'].'" WHERE `uid` = "'.$pl['id'].'" AND `delete` = 0');
							}
							//
							if( $tmsk == 1 ) {
								$tmsk = 2;
								$setA[] = array('id'=>$pl['id']);
							}else{							
								$tmsk = 1;
								$setB[] = array('id'=>$pl['id']);
							}
						//}
					}
					
					//if( $z['arand'] == 1 ) {
					//shuffle($players);
					//}
					/*$sets = Balancer::balance($players, 'skill');
					$setA = $sets[0];
					$setB = $sets[1];
					//
					if( ( count($setA) == 0 && count($setB) == 2 ) ) {
						$setA = array(0=>$sets[1][0]);
						$setB = array(0=>$sets[1][1]);
					}elseif( ( count($setB) == 0 && count($setA) == 2 ) ) {
						$setA = array(0=>$sets[0][0]);
						$setB = array(0=>$sets[0][1]);
					}*/
					//
					$i = 0;
					while( $i <= count($setA) ) {
						if(isset($setA[$i])) {
							mysql_query('UPDATE `stats` SET `team` = "1",`zv_enter` = "0" WHERE `id` = "'.$setA[$i]['id'].'" LIMIT 1');
						}
						$i++;
					}
					//
					$i = 0;
					while( $i <= count($setB) ) {
						if(isset($setB[$i])) {
							mysql_query('UPDATE `stats` SET `team` = "2",`zv_enter` = "0" WHERE `id` = "'.$setB[$i]['id'].'" LIMIT 1');
						}
						$i++;
					}
					//
				}
				
				//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','','','','[battle_type[".$z['razdel']."]]".$txtz."','".time()."','6','0')");
				
				$btl = array(
				'otmorozok' => $z['otmorozok'],
				'priz' => $z['priz'],'smert' => $z['smert'],'noart' => $z['noart'],'noeff' => $z['noeff'],'noatack' => $z['noatack'],'arand' => $z['arand'],'kingfight' => $z['kingfight'],
				'players'=>'','timeout'=>$z['timeout'],'type'=>$z['type'],'invis'=>$z['invise'],'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>$z['exp'],'money'=>0,'money3'=>0);
				$ins = mysql_query('INSERT INTO `battle` (`otmorozok`,`priz`,`smert`,`noart`,`noeff`,`noatack`,`arand`,`kingfight`,`nobot`,`fastfight`,`razdel`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
													"'.$z['otmorozok'].'",
													"'.$z['priz'].'",
													"'.$z['smert'].'",
													"'.$z['noart'].'",
													"'.$z['noeff'].'",
													"'.$z['noatack'].'",
													"'.$z['arand'].'",
													"'.$z['kingfight'].'",
													"'.$z['nobot'].'",
													"'.$z['fastfight'].'",												
													"'.$z['razdel'].'",
													"'.$z['city'].'",
													"'.time().'",
													"'.mysql_real_escape_string($btl['players']).'",
													"'.mysql_real_escape_string($btl['timeout']).'",
													"'.mysql_real_escape_string($btl['type']).'",
													"'.mysql_real_escape_string($btl['invis']).'",
													"'.mysql_real_escape_string($btl['noinc']).'",
													"'.mysql_real_escape_string($btl['travmChance']).'",
													"'.mysql_real_escape_string($btl['typeBattle']).'",
													"'.mysql_real_escape_string($btl['addExp']).'",
													"'.mysql_real_escape_string($btl['money'],2).'")');
				$btl_id = mysql_insert_id();
				if($btl_id>0)
				{
					
					//Если бой кулачный, то снимаем вещи
					if($z['type']==1)
					{
						$sp = mysql_query('SELECT `id` FROM `stats` WHERE `zv` = "'.$z['id'].'"');
						while($pl = mysql_fetch_array($sp)) {
							mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$pl['id'].'" AND `inOdet`!=0');
						}
					}
					
					//обновляем данные о поединке						
					$upd1  = mysql_query('UPDATE `stats` SET `zv`="0" WHERE `zv` = "'.$z['id'].'"');
					$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE '.$vars[1].'');
										
					//обновляем заявку, что бой начался
					$upd = mysql_query('UPDATE `zayvki` SET `start` = "'.time().'",`btl_id` = "'.$btl_id.'" WHERE `id` = "'.$z['id'].'" LIMIT 1');
					//Отправляем сообщение в чат всем бойцам
					mysql_query("INSERT INTO `chat` (`city`,`room`,`to`,`time`,`type`,`toChat`,`sound`) VALUES ('".$u->info['city']."','-1','".$vars[0]."','".time()."','11','0','117')");
					if( $u->info['zv'] == $z['id'] ) {
						$u->info['battle'] = $btl_id;
					}
					/*
					die('<script>location="main.php?battle_id='.$btl_id.'";</script>');
					*/
				}
			}elseif($z['razdel']>=1 && $z['razdel']<=3)
			{
				//начало PvP
				if($u->info['team']==1 && $u->info['zv']==$z['id'])
				{
					$zu = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `zv`="'.$z['id'].'" AND `team` = "2" LIMIT 1'));
					if(isset($zu['id']))
					{
						$uz = mysql_fetch_array(mysql_query('SELECT `login`,`money` FROM `users` WHERE `id`="'.$zu['id'].'" LIMIT 1'));
						if($zu['clone'] > 0) {
							//обновляем клона
							$bot = $u->addNewbot(1,NULL,$zu['clone'],NULL,true);
							if($bot > 0) {
								mysql_query('DELETE FROM `users` WHERE `id` = "'.$zu['id'].'" LIMIT 1');
								mysql_query('DELETE FROM `stats` WHERE `id` = "'.$zu['id'].'" LIMIT 1');
								mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$zu['id'].'" LIMIT 100');
								mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$zu['id'].'" LIMIT 100');
								
								mysql_query('UPDATE `stats` SET `zv` = "'.$z['id'].'",`team` = 2 WHERE `id` = "'.$bot.'" LIMIT 1');
																							
								$zu = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `zv`="'.$z['id'].'" AND `team` = "2" LIMIT 1'));
								$uz = mysql_fetch_array(mysql_query('SELECT `login`,`money` FROM `users` WHERE `id`="'.$zu['id'].'" LIMIT 1'));
							}
						}
						
						//создаем поединок						
						$btl_id = 0;
						if($uz['money']<$z['money'] || $u->info['money']<$z['money'])
						{
							$z['money'] = 0;
						}
						$btl = array('otmorozok' => $z['otmorozok'],'players'=>'','timeout'=>$z['timeout'],'type'=>$z['type'],'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>0,'money'=>round($z['money'],2),'money3'=>0);
						$ins = mysql_query('INSERT INTO `battle` (`otmorozok`,`smert`,`noart`,`noeff`,`noatack`,`arand`,`kingfight`,`nobot`,`fastfight`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
															"'.mysql_real_escape_string($btl['otmorozok']).'",
															"'.mysql_real_escape_string($btl['smert']).'",
															"'.mysql_real_escape_string($btl['noart']).'",
															"'.mysql_real_escape_string($btl['noeff']).'",
															"'.mysql_real_escape_string($btl['noatack']).'",
															"'.mysql_real_escape_string($btl['arand']).'",
															"'.mysql_real_escape_string($btl['kingfight']).'",
															"'.mysql_real_escape_string($btl['nobot']).'",
															"'.mysql_real_escape_string($btl['fastfight']).'",	
															"'.$u->info['city'].'",
															"'.time().'",
															"'.mysql_real_escape_string($btl['players']).'",
															"'.mysql_real_escape_string($btl['timeout']).'",
															"'.mysql_real_escape_string($btl['type']).'",
															"'.mysql_real_escape_string($btl['invis']).'",
															"'.mysql_real_escape_string($btl['noinc']).'",
															"'.mysql_real_escape_string($btl['travmChance']).'",
															"'.mysql_real_escape_string($btl['typeBattle']).'",
															"'.mysql_real_escape_string($btl['addExp']).'",
															"'.mysql_real_escape_string($btl['money']).'")');
						$btl_id = mysql_insert_id();
						if($ins)
						{
							//обновляем данные о поединке						
							$upd1  = mysql_query('UPDATE `stats` SET `zv`="0" WHERE `zv` = "'.$z['id'].'" LIMIT 2');
							$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" OR `id` = "'.$zu['id'].'" LIMIT 2');
							
							//Если бой кулачный, то снимаем вещи
							if($z['type']==1)
							{
								mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$u->info['id'].'" AND `inOdet`!=0');
								mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$zu['id'].'" AND `inOdet`!=0');
							}
							
							//обновляем заявку, что бой начался
							$upd = mysql_query('UPDATE `zayvki` SET `start` = "'.time().'",`btl_id` = "'.$btl_id.'" WHERE `id` = "'.$z['id'].'" LIMIT 1');

							$u->info['battle'] = $btl_id;
							
							//Отправляем сообщение в чат всем бойцам
							mysql_query("INSERT INTO `chat` (`city`,`room`,`to`,`time`,`type`,`toChat`,`sound`) VALUES ('".$u->info['city']."','".$u->info['room']."','".$uz['login']."','".time()."','11','0','117')");
							die('<script>location="main.php?battle_id='.$btl_id.'";</script>');
						}else{
							$this->error = 'Ошибка создания битвы.';
						}	
					}else{
						$this->error = 'Вы не можете начать поединок, вашу заявку никто не принял.';
					}
				}else{
					$this->error = 'Вы не можете начать поединок.';
				}
			}
		}	
		mysql_query('UNLOCK TABLES');
		mysql_query('COMMIT');
	}

	public function cancelzv()
	{
		global $u,$c,$code,$zi;
		if(isset($_GET['cancelzv'],$zi['id']) && $zi['razdel']>=1 && $zi['razdel']<=3)
		{
			$enemy = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zi['id'].'" AND `st`.`team` = "2" LIMIT 1'));
			if(isset($enemy['id']))
			{
				if($zi['razdel']>=1 && $zi['razdel']<=3)
				{
					if($u->info['team']==1)
					{
						//выкидываем из заявки + пишем сообщение в чат
						$upd = mysql_query('UPDATE `stats` SET `zv` = "0",`team`="0" WHERE `id` = "'.$enemy['id'].'" LIMIT 1');
						if($upd)
						{
							mysql_query('UPDATE `users` SET `otk` = (`otk` + 1) WHERE `id` = "'.$zi['id'].'" LIMIT 1');
							$this->error = 'Вы отказали '.$enemy['login'].' в поединке';
							//отправляем сообщение в чат
							$sa = '';
							if($u->info['sex']==2)
							{
								$sa = 'а';
							}
							$text = ' [login:'.$u->info['login'].'] отказал'.$sa.' вам в поединке.';
							mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$enemy['city']."','','','".$enemy['login']."','".$text."','".time()."','6','0')");
						}
					}elseif($u->info['id']==$enemy['id'] && $zi['start']==0)
					{
						//выкидываем из заявки + пишем сообщение в чат
						$upd = mysql_query('UPDATE `stats` SET `zv` = "0",`team`="0" WHERE `id` = "'.$enemy['id'].'" LIMIT 1');
						if($upd)
						{
							$uz = mysql_fetch_array(mysql_query('SELECT `u`.`sex`,`u`.`login`,`u`.`city`,`u`.`room`,`u`.`id`,`st`.`zv`,`st`.`team` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zi['id'].'" AND `st`.`team` = "1" LIMIT 1'));
							if(isset($uz['id']))
							{
								$this->error = 'Вы отозвали свой запрос на бой.';
								//отправляем сообщение в чат
								$sa = '';
								if($u->info['sex']==2)
								{
									$sa = 'а';
								}
								$text = ' [login:'.$u->info['login'].'] отозвал'.$sa.' свой запрос на бой.';
								mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$uz['city']."','','','".$uz['login']."','".$text."','".time()."','6','0')");
							}
							$u->info['zv'] = 0;
							$u->info['team'] = 0;
						}
					}
					if($enemy['bot'] == 1) {
						//удаляем бота , предметы и эффекты
						mysql_query('DELETE FROM `users` WHERE `id` = "'.$enemy['id'].'" LIMIT 1');
						mysql_query('DELETE FROM `stats` WHERE `id` = "'.$enemy['id'].'" LIMIT 1');
						mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$enemy['id'].'" LIMIT 100');
						mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$enemy['id'].'" LIMIT 100');
					}
				}
			}else{
				if($zi['razdel']>=1 && $zi['razdel']<=3 && $u->info['team']==1)
				{
					//удаляем заявку на бой
					$upd = mysql_query('UPDATE `zayvki` SET `cancel` = "'.time().'" WHERE `id` = "'.$zi['id'].'" LIMIT 1');
					if($upd)
					{
						//mysql_query('UPDATE `eff_users` SET `zv_enter` = "0" WHERE `uid` = "'.$u->info['id'].'"');
						mysql_query('UPDATE `stats` SET `zv` = "0",`zv_enter` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						$this->error = 'Вы отозвали свою заявку';
						$zi = false;
						$u->info['zv'] = 0;
					}
				}
			}
			
		}
	}
	
	public function see()
	{
		global $u,$c,$code,$zi,$cron;
		if(isset($_GET['r']) && ((!isset($_GET['new_group']) && !isset($_POST['groupClick'])) || isset($zi['id'])) )
		{
			$r = round(intval($_GET['r']));
			if(($r>=1 && $r<=5) || $r==8)
			{
				$this->zv_see = 1;
				if($u->room['FR']==0 && $u->room['zvsee']==0)
				{
					echo '<br><br><br><b><font color="black"><center>Подать заявку можно только в комнатах бойцовского клуба</center></font></b>'; $this->zv_see = 0;
				}elseif($r==1 && $u->info['level']>0)
				{
					echo '<br><br><br><b><font color="black"><center>Вы уже выросли из ползунков ;)</center></font></b>'; $this->zv_see = 0;
				}elseif($r>1 && $r<6 && $u->info['level']<1)
				{
					echo '<br><br><br><b><font color="black"><center>Вы еще не выросли из ползунков ;)</center></font></b>'; $this->zv_see = 0;
				}elseif($r>3 && $r<6 && $u->info['level']<2)
				{
					echo '<br><br><br><b><font color="black"><center>В '.$this->z1n[$r].' бои только с второго уровня.</center></font></b>'; $this->zv_see = 0;
				}elseif($r==1 && $u->info['level']>0)
				{
					echo '<br><br><br><b><font color="black"><center>Вы уже выросли из ползунков ;)</center></font></b>'; $this->zv_see = 0;
				}elseif($r==8 && $u->info['level'] < 1)
				{
					echo '<br><br><br><b><font color="black"><center>Принимать участие в турнире только с первого уровня.</center></font></b>'; $this->zv_see = 0;
				}elseif($u->info['zv']>0 && $u->info['battle']==0 && $r != 8)
				{
					if($zi['razdel']==1 || $zi['razdel']==2 || $zi['razdel']==3)
					{
						echo '
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td valign="top">';
							
							if($u->info['team']==1)
							{
								$uz = mysql_fetch_array(mysql_query('SELECT `u`.`sex`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zi['id'].'" AND `st`.`team`="2" LIMIT 1'));
								if(!isset($uz['id']))
								{
									//если никто не принял
									echo '<div style="float:left;"><div style="float:left;">Вы уже подали заявку на бой  <INPUT class="btnnew" onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&cancelzv\';" TYPE=submit name=close value="Отозвать заявку"></div>';
								}else{
									//если кто-то принял
									$sa = '';
									if($uz['sex']==2)
									{
										$sa = 'а';
									}
									echo '<script> zv_Priem = '.(0+$uz['id']).';</script><font color="red"><b>Вашу заявку принял'.$sa.' '.$ca.'</font></b> '.$u->microLogin($uz['id'],1).'</a><font color="red"><b> Хотите подтвердить бой? </b></font><INPUT class="btnnew" onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&startBattle\';" TYPE=submit name=close value="Подтвердить"> <INPUT class="btnnew" onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&cancelzv\';" TYPE=submit name=close value="Отказать">';
								}
							}else{
								$uz = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zi['id'].'" AND `st`.`team`="1" LIMIT 1'));
								if(isset($uz['id']))
								{
									echo 'Ожидаем подтверждения боя от '.$u->microLogin($uz['id'],1).' <INPUT class="btnnew" onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&cancelzv\';" TYPE=submit name=close value="Отозвать запрос">';
								}else{
									//удаляем заявку
									
								}
							}
							
							echo '</td>
							<td align="right" valign="top">
								<div style="float:right;"><INPUT class="btnnew" onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></div>
							</td>
						  </tr>
						</table></div>';						
					}else{
						$tm_start = floor(($zi['time']+$zi['time_start']-time())/6)/10;
						$tm_start = $this->rzv($tm_start);
						echo '<div style="float:right;"><INPUT class="btnnew" onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></div>
						<b>Ожидаем начала '.$this->z2n[$zi['razdel']].' боя</b>';
						$sv0 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$zi['id'].'" LIMIT 100'));
						if($sv0[0] <= 1)
						{
							if(isset($_GET['cancelzvnow']))
							{
								echo ' <b><font color="red">Заявка на бой отменена</font></b>';
								mysql_query('UPDATE `zayvki` SET `cancel` = "'.time().'" WHERE `id` = "'.$u->info['zv'].'" LIMIT 1');
								$u->info['zv'] = 0;
								//mysql_query('UPDATE `eff_users` SET `zv_enter` = "0" WHERE `uid` = "'.$u->info['id'].'"');
								mysql_query('UPDATE `stats` SET `zv` = "0",`team` = "0",`zv_enter` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');								
							}else{
								echo ' <a href="main.php?zayvka&r='.$_GET['r'].'&cancelzvnow&rnd='.$code.'" title="Отменить заявку">Отменить</a>';
							}
						}
						unset($sv0);
						echo '<br>Ваш бой начнется через '.$tm_start.' мин.';
					}
				}elseif($r==8) {
					//Турниры
					
					$ttur = array(
						0 => 'Выжить любой ценой!',
						1 => 'Каждый сам за себя!',
						2 => 'Захват ключа!'				
					);
										
					if(isset($_POST['trn1']) && $u->room['zvsee']==0) {
						if($u->info['inTurnirnew'] == 0) {
							$totr = mysql_fetch_array(mysql_query('SELECT * FROM `turnirs` WHERE `id` = "'.mysql_real_escape_string($_POST['trn1']).'" AND `status` = "0" LIMIT 1'));
							if(isset($totr['id'])) {
								mysql_query('UPDATE `users` SET `inTurnirnew` = "'.$totr['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								mysql_query('UPDATE `turnirs` SET `users_in` = `users_in` + 1 WHERE `id` = "'.$totr['id'].'" LIMIT 1');
								$u->info['inTurnirnew'] = $totr['id'];
								$this->error = 'Вы записались на участие в турнире.';
							} else {
								$this->error = 'Заявка на турнир не найдена.';
							}
						}else{
							$this->error = 'Вы уже находитесь в заявке на турнир.';
						}
					}elseif(isset($_GET['cancel13']) && $u->room['zvsee']==0) {
						if($u->info['inTurnirnew'] > 0) {
							$totr = mysql_fetch_array(mysql_query('SELECT * FROM `turnirs` WHERE `id` = "'.mysql_real_escape_string($u->info['inTurnirnew']).'" AND `status` = "0" LIMIT 1'));
							if(isset($totr['id'])) {
								mysql_query('UPDATE `users` SET `inTurnirnew` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								mysql_query('UPDATE `turnirs` SET `users_in` = `users_in` - 1 LIMIT 1');
								$u->info['inTurnirnew'] = 0;
								$this->error = 'Вы отказались от заявки на турнир.';
							}else{
								$this->error = 'Нельзя отказаться от заявки находясь в турнире.';
							}
						}else{
							$this->error = 'Вы не принимаете участия ни в одном из турниров.';
						}
					}
					
					$dv = '';
					$trse = '';
					
					if($u->info['inTurnirnew'] > 0) {
						$pl = mysql_fetch_array(mysql_query('SELECT * FROM `turnirs` WHERE `id` = "'.$u->info['inTurnirnew'].'" LIMIT 1'));
						if(!isset($pl['id'])) {
							mysql_query('UPDATE `users` SET `inTurnirnew` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							echo 'Что-то не так... Обновите страницу.';
						}else{
							$dv = '<b><u>Участники турнира ['.$pl['users_in'].']</u></b>:<br>';
							$spu = mysql_query('SELECT `u`.`id`,`u`.`align`,`u`.`login`,`u`.`clan`,`u`.`level`,`u`.`city`,`u`.`online`,`u`.`sex`,`u`.`cityreg`,`u`.`palpro`,`u`.`invis` FROM `users` AS `u` WHERE `u`.`inTurnirnew` = "'.$pl['id'].'" LIMIT '.$pl['users_in']);
							$i = 1;
							while($plu = mysql_fetch_array($spu)) {
								$dv .= '<div style="padding:3px;">'.$i.'. '.$u->microLogin($plu,2).'</div>';
								$i++;
							}
							echo '
							<script type="text/javascript">
							function MM_jumpMenu(targ,selObj,restore){ //v3.0
							  eval("location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
							  if (restore) selObj.selectedIndex=0;
							}
							</script>
							<FORM style="margin:0px; padding:0px; border:0px;" METHOD=\'POST\' ACTION=\'main.php?zayvka=1&r='.$r.'&rnd='.$code.'\'>
							<input type="hidden" name="add_new_zv" id="add_new_zv" value="'.floor(time()/3).'" />
							<TABLE width=100% cellspacing=0 cellpadding=0>
								  <TR>
									<TD valign=top>
										<font color="red"><b>'.$this->error.'</b></font>
										<div style="border-bottom:#b2b2b2 solid 1px;padding:5px;">
										Начало турнира через '.$u->timeOut($pl['time']-time()).' <INPUT class="btnnew" onClick="location=\'main.php?zayvka&r=8&cancel13&tlvl='.$pl['level'].'&rnd='.$code.'\';" TYPE=button name=tmp value="Отказаться">
										</div>
										<div style="border-bottom:#b2b2b2 solid 1px;padding:5px;margin-bottom:5px;">
										'.$dv.'
										</div>
										</TD>
									<TD align=right valign=top><INPUT class="btnnew" onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&tlvl='.$tlvl.'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></TD>
								  </TR>
								</TABLE>
								</FORM>';
						}
					}else{
						$tlvl = 4;
						$i = 4;
						$trnmz = array(4=>'Физический',5=>'Магический',6=>'Физ.\Маг.');
						while($i <= 6) {
							if($_GET['tlvl'] == $i) {
								$trse .= '<option value="http://xcombats.com/main.php?zayvka&r=8&tlvl='.$i.'" selected="selected">'.$trnmz[$i].'</option>';
								$tlvl = $i;
							}else{
								$trse .= '<option value="http://xcombats.com/main.php?zayvka&r=8&tlvl='.$i.'">'.$trnmz[$i].'</option>';
							}
							$i++;
						}						
						$prb = '<INPUT class="btnnew" TYPE="submit" name=open value="Принять участие">';										
						echo '<style>.zvnkj { padding:5px; }</style>';						
						$sp = mysql_query('SELECT * FROM `turnirs` WHERE `status` = "0" AND `level` = "'.$tlvl.'"');
						$j = 0;
						while($pl = mysql_fetch_array($sp)) {
							$j++;
							$dinf = 'Начало через '.$u->timeOut($pl['time']-time()).'';
							$dv .= '<label><div class="zvnkj">';							
							if($u->room['zvsee']==0) {
								$dv .= '<input type="radio" name="trn1" id="trn1_'.$j.'" value="'.$pl['id'].'">';
							}
							$dv .= ' Физический турнир. Участников турнира: '.$pl['users_in'].' чел. | '.$dinf.'</div></label>';
						}											
						if($dv == '') {
							$dv = 'Список турниров для данного типа пуст...';
						}										
						echo '
						<script type="text/javascript">
						function MM_jumpMenu(targ,selObj,restore){ //v3.0
						  eval("location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
						  if (restore) selObj.selectedIndex=0;
						}
						</script>
						<FORM style="margin:0px; padding:0px; border:0px;" METHOD=\'POST\' ACTION=\'main.php?zayvka=1&r='.$r.'&rnd='.$code.'\'>
						<input type="hidden" name="add_new_zv" id="add_new_zv" value="'.floor(time()/3).'" />
						<TABLE width=100% cellspacing=0 cellpadding=0>
							  <TR>
								<TD valign=top>
									<font color="red"><b>'.$this->error.'</b></font>
									<div style="border-bottom:#b2b2b2 solid 1px;padding:5px;">
									Тип турнира:
										  <SELECT NAME=turlevel onChange="MM_jumpMenu(null,this,0)">
											'.$trse.'
										 </SELECT>
										 '.$prb.'
									</div>
									<div style="border-bottom:#b2b2b2 solid 1px;padding:5px;margin-bottom:5px;">
									'.$dv.'
									</div>
									'.$prb.'
									</TD>
								<TD align=right valign=top><INPUT class="btnnew" onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&tlvl='.$tlvl.'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></TD>
							  </TR>
							</TABLE>
							</FORM>';
					}
				}elseif($r==1 || $r==2 || $r==3)
				{
					//новички,физические,договорные
					$zi = array(1=>'Если вы не достигли первого уровня, то для вас это единственный способ для проведения битв.',2=>'Здесь вы можете найти себе достойного противника для сражения.',3=>'Если вы предварительно с кем-то договорились о поединке, то лучше здесь подать заявку.');
					$dv = '';
					if($u->room['zvsee']==0) {
					if($r==3) {
						$dv = '<br>Логин противника
									  <INPUT TYPE=text NAME=onlyfor maxlength=30 size=12>
									  <BR>
									  Бой на деньги, ставка
									  <INPUT TYPE=text NAME=stavkakredit size=6 maxlength=10> &nbsp; <INPUT class="btnnew" TYPE=submit name=open value="Подать заявку">';
					}else{
						$dv = '<INPUT class="btnnew" TYPE=submit name=open value="Подать заявку">';
						//if($u->info['level'] <= 9 || $u->info['admin']>0 /*|| ($u->stats['silver']>0 && $u->info['level']<8) || ($u->stats['silver'] >= 2 && $u->info['level']<9)*/ )
						if(($u->info['level'] <= $c['bot_level'] || $u->info['admin'] > 0) && ($u->info['exp'] != 12499 || $u->info['admin'] > 0))
						{
							$dv .= ' <INPUT class="btnnew" onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&bot='.$u->info['nextAct'].'\';" TYPE=button name=clone value="Бой с ботом">';
						}
						if( $u->info['admin'] > 0 ) {
							$dv .= ' <INPUT class="btnnew" onClick="console_clonelogin();" TYPE=button name=clone value="Тестовый бой (Без награды)">';
						}
						if( $u->info['admin'] > 0 ) {
							
							if( isset($_GET['adminbotatack']) ) {
								$bot_atack = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.mysql_real_escape_string($_GET['adminbotatack']).'" LIMIT 1'));
								if( isset($bot_atack['id']) ) {
									$logins_bot = array();
									$k = $u->addNewbot($bot_atack['id'],NULL,NULL,$logins_bot);
									if( isset($k['id']) ) {
										$expB = 0;
										$btl = array(
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
														"0",
														"0",
														"0",
														"0",
														"'.$u->info['city'].'",
														"'.time().'",
														"'.$btl['players'].'",
														"'.$btl['timeout'].'",
														"'.$btl['type'].'",
														"'.$btl['invis'].'",
														"'.$btl['noinc'].'",
														"'.$btl['travmChance'].'",
														"'.$btl['typeBattle'].'",
														"'.$btl['addExp'].'",
														"'.$btl['money'].'")');
										$btl_id = mysql_insert_id();
										mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$k['id'].'" OR `id` = "'.$u->info['id'].'" LIMIT 2');
										mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
										mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
										header('location: main.php');
										die();	
									}
								}
							}
							
							$dv .= '<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval("location=\'main.php?zayvka=1&r=2&rnd=1&adminbotatack="+selObj.options[selObj.selectedIndex].value+"\'");
  if (restore) selObj.selectedIndex=0;
}
</script><form name="form55" id="form55">';
							
							$dv .= '<hr>Бой с монстром: <select style="font-size:12px;" onChange="MM_jumpMenu(\'parent\',this,0)" name="botadminatack"><option value="0">------ Выберите монстра из списка ------</option>';
							
							$sp_m = mysql_query('SELECT * FROM `test_bot` WHERE `pishera` != 0 ORDER BY `id` ASC');
							while($pl_m = mysql_fetch_array($sp_m) ) {
								$dv .= '<option value="'.$pl_m['id'].'">'.$pl_m['id'].' [ '.$pl_m['align'].' ] - '.$pl_m['login'].' ['.$pl_m['level'].'] '.$pl_m['pishera'].'</option>';
							}
							$dv .= '</select></form>';
						}
					}
					echo '
					<FORM style="margin:0px; padding:0px; border:0px;" METHOD=\'POST\' ACTION=\'main.php?zayvka=1&r='.$r.'&rnd='.$code.'\'>
					<input type="hidden" name="add_new_zv" id="add_new_zv" value="'.floor(time()/3).'" />
					<TABLE width=100% cellspacing=0 cellpadding=0>
						  <TR>
							<TD valign=top>'.$zi[$r].'<BR>
								<table cellspacing=0 cellpadding=0>
								  <tr>
									<td><FIELDSET>
									  <LEGEND><B>Подать заявку на бой</B> </LEGEND>
									  Таймаут
									  <SELECT NAME=timeout>
										<OPTION value=1>1 мин.
										<OPTION value=2>2 мин.
										<OPTION value=3 SELECTED>3 мин.
										<OPTION value=4>4 мин.
										<OPTION value=5>5 мин.
									 </SELECT>
									  Тип боя
									  <SELECT NAME=k>
										<OPTION value=0>с оружием
										<OPTION value=1>кулачный
									</SELECT>
									  '.$dv.'
									</FIELDSET></td>
								  </tr>
								</table></TD>
							<TD align=right valign=top><INPUT class="btnnew" onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></TD>
						  </TR>
						</TABLE>
						</FORM>';
						}
				}elseif($r==4)
				{
					if($u->room['zvsee']==0) {
					//групповые
					echo '<INPUT class="btnnew" onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&new_group&rnd='.$code.'\';" TYPE=button name=tmp value="Подать новую заявку"  style="margin:3px;">
						  <INPUT class="btnnew" onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'&sort=\'+document.all.value+\'\';" TYPE=button name=tmp value="Обновить"  style="float:right;">';
					}
				}elseif($r==5)
				{
					if($u->room['zvsee']==0) {
					echo 'Хаотичный бой - разновидность группового, где группы формируются автоматически.<br>При старте поединка все эффекты увеличиваются на время ожидания в заявке.';
					if( $u->info['level'] < 4 ) {
						echo '<br>Хаоты для 2-4 уровней начинаются с ботами, если заявка не смогла набрать 8 персонажей.';
					}
					echo '<br>
					<!-- Так-же в хаотичных боях возможно заработать <b>воинственность</b> <a href="http://events.xcombats.com/?page_id=1&paged=&st=25" target="_blank">подробнее</a>.<br> -->
						  <a href="#" onclick="if(document.getElementById(\'haot\').style.display==\'\'){ document.getElementById(\'haot\').style.display=\'none\' }else{ document.getElementById(\'haot\').style.display=\'\'; } return false;">Подать заявку на хаотичный бой</a>
						  <form action="main.php?zayvka=1&r='.$_GET['r'].'&start_haot&rnd='.$code.'" method="post" style="margin:0px; padding:0px;">
						  <div style="display:none;" id="haot">
										  <br>
										  <FIELDSET>
											<LEGEND><strong>Подать заявку на хаотичный бой</strong> </LEGEND>
											Начало боя   через
											<SELECT name="startime2">
											  <OPTION selected value="180">3 минуты
											  <OPTION selected value="300">5 минут
											  <OPTION value="600">10 минут
											  <!--<OPTION value="900">15 минут
											  <OPTION value="1200">20 минут
											  <OPTION value="1800">30 минут</OPTION>-->
											</SELECT>
											Таймаут
											<SELECT name="timeout">
											  <OPTION selected value="1">1 мин.
											  <OPTION value="2">2 мин.
											  <OPTION value="3">3 мин.
											  <!--<OPTION value="4">4 мин.
											  <OPTION value="5">5 мин.</OPTION>-->
											</SELECT>
											<BR>
											Уровни бойцов     
											<SELECT name="levellogin1">
											  <OPTION value="0">любой
											  <OPTION value="3">только моего   уровня
											  <OPTION selected value="6">мой уровень +/- 1</OPTION>
											</SELECT>
											<BR>
											<BR>
											Тип боя
											<SELECT name="k">
											  <OPTION selected value="0">с оружием
											  <OPTION value="1">кулачный</OPTION>
											</SELECT>
											<BR>
											<INPUT type="checkbox" name="travma">
											Бой без   правил (проигравшая сторона получает   инвалидность)<BR>
											<!--<INPUT type="checkbox" name="mut_clever">Смертельные Раны   (увеличенный урон при повторных попаданиях)<BR>-->
											<!--<INPUT type="checkbox" name="noart">-->
											<!--Поединок без артефактов (Допускаются персонажи максимум с одним артефактом)<BR>-->
											<!--<INPUT type="checkbox" name="noeff">-->
											<!--Без екровых обкастов (Заклятия купленные в &quot;Березке&quot; не действуют в этом бою)<BR>-->
											<!--<INPUT type="checkbox" name="noatack">Закрытый поединок (В поединок невозможно вмешаться)<BR>-->
											<!--<INPUT type="checkbox" name="arand"> Абсолютный рандом (Абсолютно случайное распределение игроков)<BR>-->
											<!--<INPUT type="checkbox" name="kingfight">Призовой поединок (<b>Не действует с быстрым поединком</b>)<BR>-->
											<!--<INPUT type="checkbox" name="nobot">Поединок без ботов<BR>-->
											<!--<INPUT type="checkbox" name="fastfight">-->
											<!--Быстрый поединок (Для старта поединка требуется минимум два игрока)<BR>-->
											';
											if( date('m') == 12 || date('m') == 1 || date('m') == 2 ) {
												//Отморозки
												//echo '<INPUT type="checkbox" name="otmorozok"> <img src="http://img.xcombats.com/snow.gif" width="12" height="12"> Бой с Отморозками (За случайную команду вмешается Отморозок, +1.00 кр. награды)<BR>';	
											}
											if( $u->info['no_zv_key'] != true ) { 
												echo '<img src="http://xcombats.com/show_reg_img/security2.php?id='.time().'" width="70" height="20"> Код подтверждения: <input style="width:40px;" type="text" value="" name="code21">';
											}
											/*echo '<INPUT type="checkbox" name="mut_hidden">
											Невидимый Бой (не видно   противников ни в заявке, ни в бою. +5% опыта)					 
									  		<BR>';*/
											
											echo'
											Комментарий   к бою
											<INPUT maxLength="40" size="40" name="cmt"><BR>
											<INPUT class="btnnew" value="Подать заявку" type="submit" name="open">
										  </FIELDSET>
										</DIV>
						  </div></form>';
					}
				}				
			}elseif($r==6)
			{
				//текущие
				$x = 1;
				$html = '';
				$p = 0;
				$_GET['from'] = round((int)$_GET['from']);
				if($_GET['from']>1 && $_GET['from']<50)
				{
					$p = $_GET['from']-1;
				}
				$xx = mysql_num_rows(mysql_query('SELECT `id` FROM `battle` WHERE `type` != 329 AND /*`city` = "'.$u->info['city'].'" AND*/ `team_win` = "-1" AND `time_over` = "0" AND `start1` > 0'));
				$px = $p*15;
				if($p>ceil($xx/15))
				{
					$p = ceil($xx/15);
				}
				$sp = mysql_query('SELECT * FROM `battle` WHERE `type` != 329 AND /*`city` = "'.$u->info['city'].'" AND */`team_win` = "-1" AND `time_over` = "0" AND `start1` > 0 ORDER BY  `time_start` DESC  LIMIT '.((int)$px).',15');
				while($pl = mysql_fetch_array($sp))
				{
					//<SPAN style=\'color: red; font-weight: bold;\'>против</SPAN>
					$tm = ''; $tmu = array(); $tms = array();
					$spi = mysql_query('SELECT `u`.`login`,`st`.`id`,`st`.`team`,`u`.`id` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$pl['id'].'"');
					while($pli = mysql_fetch_array($spi))
					{
						if(!isset($tmu[$pli['team']]))
						{
							$tms[count($tms)] = $pli['team'];
						}
						$tmu[$pli['team']][count($tmu[$pli['team']])] = $pli['id'];
					}
					$i = 0;
					while($i<count($tms))
					{
						$tmsu = '';
						$j = 0;
						while($j<count($tmu[$tms[$j]]))
						{
							if($tmu[$tms[$i]][$j]>0)
							{
								$tmsu .= $u->microLogin($tmu[$tms[$i]][$j],1).', ';
							}
							$j++;
						}
						$tmsu = rtrim($tmsu,', ');
						$tm .= $tmsu;
						if($i+1!=count($tms))
						{
							$tm .= ' <SPAN style=\'color: red; font-weight: bold;\'>против</SPAN> ';
						}
						$i++;
					}
					if( $tm != '' ) {
						$html .= ($p+$x).'. <font class=date>'.date('d.m.y H:i',$pl['time_start']).'</font> <img src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif" title="'.$u->city_name[$pl['city']].'"> '.$tm.' <IMG SRC="http://img.xcombats.com/i/fighttype'.$pl['typeBattle'].'.gif" WIDTH=20 HEIGHT=20 ALT="Физический бой"> <A HREF="logs.php?log='.$pl['id'].'&rnd='.$code.'" target=_blank>»»</A><BR>';
					}
					$x++;
				}
				?>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" align="center"><h3>Записи текущих боев на <?=date('d.m.Y');?> (всего <?=$xx;?>)</h3></td>
    <td valign="top" align="right"><input class="btnnew" type="submit" value="Обновить" onClick="" name="tklogs" /></td>
  </tr>
</table>
<? if($html==''){ echo '<div align="center">К сожалению сейчас боев нет...</div>'; }else{ echo '<div>'.$html.'</div>'; } ?>
<TABLE width=100% cellspacing=0 cellpadding=0><TR>
<TD align=left><? if($p>0 && $xx>15){ ?><A HREF="?zayvka=1&r=6&from=<?=($p-1);?>">«« предыдущая страница</A><? } ?>&nbsp;</TD>
<TD align=right><? if($p*15-$xx>0){ ?><A HREF="?zayvka=1&r=6&from=<?=($p+1);?>">следующая страница »»</A><? } ?>&nbsp;</TD>
</TR></TABLE>
<?
			}elseif($r==7)
			{
				//завершенные
				$btl = '';
				$dt = time();
				if(isset($_GET['logs2']))
				{
					$dt = round((int)$_GET['logs2']);
				}
				$dt = strtotime(date('d F Y',$dt).' 00:00:00');
				$slogin = $u->info['login'];
				if(isset($_GET['filter']))
				{
					$slogin = $_GET['filter'];
				}
				if(isset($_POST['filter']))
				{
					$slogin = $_POST['filter'];
				}
				$slogin = str_replace('"','',$slogin);
				$slogin = str_replace("'",'',$slogin);
				$slogin = str_replace('\\','',$slogin);
				$see = '<TABLE width=100% cellspacing=0 cellpadding=0><TR>
<TD valign=top>&nbsp;<A HREF="?filter='.$slogin.'&zayvka=1&r=7&logs2='.($dt-86400).'">« Предыдущий день</A></TD>
<TD valign=top align=center><H3>Записи о завершенных боях за '.date('d.m.Y',$dt).'</H3></TD>
<TD  valign=top align=right><A HREF="?filter='.$slogin.'&zayvka=1&r=7&logs2='.($dt+86400).'">Следующий день »</A>&nbsp;</TD>
</TR><TR><TD colspan=3 align=center>
<form method="POST" action="main.php?zayvka=1&r=7&rnd='.$code.'">
Показать только бои персонажа: <INPUT TYPE=text NAME=filter value="'.$slogin.'"> за <INPUT TYPE=text NAME=logs size=12 value="'.date('d.m.Y',$dt).'"> <INPUT class="btnnew" TYPE=submit value="фильтр!">
</form>
</TD>
</TR></TABLE>';
				$usr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`city` FROM `users` WHERE `login` = "'.mysql_real_escape_string($slogin).'" LIMIT 1'));
				if(isset($usr['id']))
				{
					$tms = $dt;
					$tmf = $dt+86400; 
					$sp = mysql_query('SELECT * FROM `battle_last` WHERE `time` >= '.$tms.' AND `time` < '.$tmf.' AND `uid` = "'.$usr['id'].'" ORDER BY `id` DESC');
					$j = 1;
					$jk = 0;
					$btl_lst = array();
					while($pl = mysql_fetch_array($sp))
					{
						$b = mysql_fetch_array(mysql_query('SELECT * FROM `battle_end` WHERE `battle_id` = "'.$pl['battle_id'].'" LIMIT 1'));
						$tm = '';
						if( isset($btl_lst[$b['id']]) ) {
							
						}elseif(isset($b['id']))
						{
							$tms = array(); $ts = array();
							$spi = mysql_query('SELECT * FROM `battle_last` WHERE `battle_id` = "'.$pl['battle_id'].'"');
							while($pli = mysql_fetch_array($spi))
							{
								if(!isset($tms[$pli['team']]))
								{
									$ts[count($ts)] = $pli['team'];
								}
								$tms[$pli['team']][count($tms[$pli['team']])] = $pli;
							}
							$k = 0;
							while($k<count($ts))
							{
								$g = $ts[$k];
								$h = 0;
								$tm2 = '';
								while($h<count($tms[$g]))
								{
									if($tms[$g][$h]['uid']>0)
									{
										if($tms[$g][$h]['align']>0)
										{
											$tm2 .= '<img src="http://img.xcombats.com/i/align/align'.$tms[$g][$h]['align'].'.gif">';
										}
										$tm2 .= '<b>'.$tms[$g][$h]['login'].'</b> ['.$tms[$g][$h]['lvl'].']<a href="info/'.$tms[$g][$h]['uid'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif"></a>, ';
									}
									$h++;
								}
								$tm2 = rtrim($tm2,', ');
								$btlg = mysql_fetch_array(mysql_query('SELECT `id`,`team_win` FROM `battle` WHERE `id` = "'.$pl['battle_id'].'" LIMIT 1'));
								if(isset($btlg['id']) && $g == $btlg['team_win']) {
									$tm2 .= ' <img width="20" height="20" src="http://img.xcombats.com/i/flag.gif" title="Победа"> ';
								}
								$tm .= $tm2;
								if($k+1<count($ts) && $tm2!='' && $ts[$k+1]>0)
								{
									$tm .= ' <font color=red><b>против</b></font> ';
								}
								$k++;
							}
						}
						if( !isset($btl_lst[$b['id']]) ) {
							$btl_lst[$b['id']] = true;
							if($tm == '')
							{
								$tm = 'Данные поединка потеряны';	
							}
							$jk++;
							$btl .= $jk.'. <font class=date>'.date('d.m.y H:i',$pl['time']).'</font> <img src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif" title="'.$u->city_name[$pl['city']].'"> '.$tm.' <A HREF="logs.php?log='.$pl['battle_id'].'&rnd='.$code.'" target=_blank>»»</A><br>';
						}
						$j++;
					}
				}
				if($btl=='')
				{
					$see .= '<CENTER><BR><BR><B>В этот день не было боев, или же, летописец опять потерял свитки...</B><BR><BR><BR></CENTER><HR><BR>';
				}else{
					$see .= $btl;
				}
				
				echo $see;
			}else{
				if((!isset($_GET['new_group']) && !isset($_POST['groupClick'])) || isset($zi['id']))
				{
					echo '<BR><BR><CENTER><B>Выберите раздел</B></CENTER>';
				}
			}
		}else{
			if((!isset($_GET['new_group']) && !isset($_POST['groupClick'])) || isset($zi['id']))
			{
				echo '<BR><BR><CENTER><B>Выберите раздел</B></CENTER>';
			}
		}
	}
	
	public function rzv($v)
	{
		$v = explode('.',$v);
		if(!isset($v[1]))
		{
			$v = $v[0].'.0';
		}else{
			$v = $v[0].'.'.$v[1];
		}
		return $v;
	}
	
	public function rzInfo($id)
	{
		global $u;
		$r = '';
		$w = mysql_num_rows(mysql_query('SELECT * FROM `zayvki` WHERE `time` > '.(time()-7200).' /*AND `city` = "'.$u->info['city'].'"*/ AND `cancel` = "0" AND `start` = "0" AND `razdel` = "'.$id.'" AND (`min_lvl_1` <= '.$u->info['level'].' OR `min_lvl_2` <= '.$u->info['level'].') AND (`max_lvl_1` >= '.$u->info['level'].' OR `max_lvl_2` >= '.$u->info['level'].')'));
		if($w>0)
		{
			$r = ' <small><font color="grey">('.$w.')</font></small>';
		}
		return $r;
	}
	
	public function testzvu($id,$tm,$bt)
	{
		$r = 0;
		if($bt==0)
		{
			$r = mysql_num_rows(mysql_query('SELECT `id` FROM `stats` WHERE `zv` = "'.$id.'" AND `team` = "'.$tm.'"'));
		}else{
			$r = mysql_num_rows(mysql_query('SELECT `id` FROM `stats` WHERE `zv` = "'.$id.'" AND `team` = "'.$tm.'" AND `bot` = "2"'));
		}
		return $r;
	}
	
	public function seeZv()
	{
		global $u,$c,$code,$zi;
		if(isset($_GET['r']) && $this->zv_see==1)
		{
			$r = round(intval($_GET['r']));
			if($r>=1 && $r<=5)
			{
				//Список заявок
				$i = 0;
				$cl = mysql_query('SELECT * FROM `zayvki` WHERE `razdel` = "'.mysql_real_escape_string($r).'" AND `start` = "0" AND `cancel` = "0" AND `time` > "'.(time()-60*60*2).'" /*AND `city` = "'.$u->info['city'].'"*/ ORDER BY `id` DESC');
				$zvb = '';
				if($r==4 || $r==5)
				{
						/*echo '<table cellspacing="0" cellpadding="0" align="right"><tr><td>
						<FIELDSET><LEGEND>Показывать заявки</LEGEND>
						&nbsp;<INPUT TYPE=radio ID=A1 name="all" value=0 checked> <LABEL FOR=A1>моего уровня</LABEL><BR>
						&nbsp;<INPUT TYPE=radio ID=A2 name="all" value=1> <LABEL FOR=A2>все</LABEL>
						</FIELDSET>
						</td></tr></table><br>';*/
				}
				while($pl = mysql_fetch_array($cl))
				{
					if($pl['razdel']==5)
					{
						if( $pl['min_lvl_1'] < 2 ) {
							$pl['min_lvl_1'] = 2;
						}
						if( $pl['max_lvl_1'] > 21 ) {
							$pl['max_lvl_1'] = 21;
						}
						//Заявки хаотичного боя
						$tm = '';
						$tmStart = floor(($pl['time']+$pl['time_start']-time())/6)/10;
						//if( $u->info['admin'] > 0 ) {
							if((($pl['time']+$pl['time_start'])/10) != (int)(($pl['time']+$pl['time_start'])/10)) {
								$pl['time'] = ceil($pl['time']/60)*60;
								mysql_query('UPDATE `zayvki` SET `time` = "'.$pl['time'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							}
						//}
						$tmStart = $this->rzv($tmStart);
						
						$users = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`u`.`admin`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `u`.`id` = `st`.`id` WHERE `st`.`zv` = "'.$pl['id'].'"');
						$col_p = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$pl['id'].'"'));
						$cols = $col_p[0];
						while($s = mysql_fetch_array($users))
						{
							$tm .= $u->microLogin($s['id'],1).', ';
						}
						
						$rad = '';
						
						$tm = rtrim($tm,', ');
						
						if(!isset($zi['id']) && $u->room['zvsee'] == 0 && $u->info['inTurnirnew'] == 0) {
							$rad = '<input type="radio" name="btl_go" id="btl_go'.$pl['id'].'" value="'.$pl['id'].'"> ';
						}
						
						$n1tv = '';
						$unvs = '';
						if($pl['invise']==1)
						{
							//невидимый бой
							$tm = '<i>невидимый</i>';
							$unvs = 0;
							
								$usrszv = '';
								//if( $u->info['admin'] > 0 ) {
									$spzm = mysql_query('SELECT `id`,`team` FROM `stats` WHERE `zv` = "'.$pl['id'].'" AND `id` != "'.$pl['creator'].'"');
									while( $plzm = mysql_fetch_array($spzm) ) {
										if($u->info['admin'] > 0 || ($u->info['align'] > 1 && $u->info['align'] < 2) || ($u->info['align'] > 3 && $u->info['align'] < 4) ) {
											$usrszv .= ','.$u->microLogin($plzm['id'],1).'';
										}
										$unvs++;
									}
								//}
								$tm = '<font color=grey><span style="color:maroon">'.$u->microLogin($pl['creator'],1).'</span>'.$usrszv.'</font> - '.$tm;
							
							$unvs = ' Участников: '.(1+$unvs).' чел. ';
							$n1tv = ' <img src="http://img.xcombats.com/i/fighttypehidden0.gif" title="Невидимый">';
						}
						//
						if( $pl['kingfight'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/king.gif" title="Призовой поединок">';
						}
						if( $pl['noart'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/noart.gif" title="Бой без артефактов">';
						}
						if( $pl['noeff'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/noeff.gif" title="Екровые касты не действуют">';
						}
						if( $pl['noatack'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/closefight.gif" title="В бой нельзя вмешаться">';
						}
						if( $pl['nobot'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/nobot.gif" title="В бой не вступают боты">';
						}
						if( $pl['fastfight'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/fastfight.gif" title="Для начала боя необходимо минимум 2 игрока">';
						}
						if( $pl['arand'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/arand.gif" title="Игроки разделяются абсолютно случайным образом">';
						}
						if( $pl['otmorozok'] == 1 ) {
							$n1tv .= ' <img src="http://img.xcombats.com/snow.gif" width="20" height="20" title="В бой могут вмешаться Отморозки">';
						}
						//
						if($pl['comment'] != '') {
						  $dl = '';
						  if(($moder['boi'] == 1 || $u->info['admin'] > 0) && $pl['dcom']==0) {
						    $dl .= ' (<a href="main.php?zayvka=1&r=5&delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">удалить комментарий</a>)';          if(isset($_GET['delcom']) && $_GET['delcom'] == $pl['id'] && $u->newAct($_GET['key']) == true) {
				              mysql_query('UPDATE `zayvki` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				              $pl['dcom'] = $u->info['id'];
			                }
						  }
						  if($pl['dcom'] > 0) { $dl = '<font color="grey"><i>Комментарий удален модератором</i></font>'; }
						  if($pl['dcom'] > 0) {
							if($moder['boi'] == 1 || $u->info['admin'] > 0) {
				              $pl['comment'] = '[ Текст комментария : <font color="red">'.$pl['comment'].'</font>]&nbsp;';
			                } else {
				              $pl['comment'] = '';
			                 }
		                   }
						$zv_comm = '('.$pl['comment'].''.$dl.')';
						} else {
						  $zv_comm = '';
						}
						if( $pl['priz'] == 1 ) {
							$imn = '';
							if( $pl['min_lvl_1'] == 10 ) {
								$imn .= ' им. Jora Kardan';
							}
							$zv_comm = ' <a href="http://xcombats.com/lib/prizovoi-haot/" target="_blank"><span style="color:#e65700;" title="Участники получают жетоны, чем больше призовых хаотов за сутки, тем больше падает жетонов за победу "><b>(Призовой хаот'.$imn.')</b></span></a>';
						}
						if($r == 5 && ($pl['creator'] == $u->info['id']) && $cols < 2 && $pl['priz'] == 0) {
						  $del_q = '&nbsp;&nbsp;<a href="main.php?zayvka=1&r=5&del_z_time='.$pl['id'].'&rnd='.$code.'"><img src="http://img.xcombats.com/i/clear.gif" title="Удалить заявку" /></a>';
						} else {
						  $del_q = '';
						}
						$zvb .= ''.$rad.'<font class="date">'.date('H:i',$pl['time']).'</font> <img src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif" title="'.$u->city_name[$pl['city']].'"> ('.$tm.') ('.$pl['min_lvl_1'].'-'.$pl['max_lvl_1'].') <IMG SRC="http://img.xcombats.com/i/fighttype'.$pl['type'].'.gif" WIDTH="20" HEIGHT="20" title="Хаотичный бой">'.$n1tv.' <font class="dsc"><i>'.$unvs.'Бой начнется через <B>'.$tmStart.'</B> мин., таймаут '.($pl['timeout']/60).' мин. '.$zv_comm.'</font></i> '.$mon.' '.$del_q.'<br />';						
						
					}elseif($pl['razdel']==4)
					{
						if( $pl['min_lvl_1'] < 2 ) {
							$pl['min_lvl_1'] = 2;
						}
						if( $pl['max_lvl_1'] > 21 ) {
							$pl['max_lvl_1'] = 21;
						}
						if( $pl['min_lvl_2'] < 2 ) {
							$pl['min_lvl_2'] = 2;
						}
						if( $pl['max_lvl_2'] > 21 ) {
							$pl['max_lvl_2'] = 21;
						}
						//Заявки группового боя
						$tm1 = '';
						$tm2 = '';
						$tmStart = floor(($pl['time']+$pl['time_start']-time())/6)/10;
						$tmStart = $this->rzv($tmStart);
						
						//Персонаж в заявке, подключаем ему противника
						//Ищем апонента для групповых
						$xx2 = $this->testzvu($pl['id'],2,0);
						if($pl['bot2']>0 && $xx2 < $pl['tm2max'])
						{
							//Добавляем ботов за вторую команду
							$spb = mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `u`.`id` = `st`.`id` WHERE `st`.`bot` = 3 AND `u`.`level` = "'.$pl['min_lvl_2'].'" AND `u`.`battle` = 0 AND `st`.`zv` = 0 LIMIT 100');
							$logins_bot = array();
							while($plb = mysql_fetch_array($spb))
							{
								if($xx2 < $pl['tm2max'] && rand(0,10000)<5000 && rand(0,10000)>5000)
								{
									$bt = $u->addNewbot(0,'',$plb['id']);
									$logins_bot = $bt['logins_bot'];
									if($bt>0)
									{
										mysql_query('UPDATE `stats` SET `zv` = "'.$pl['id'].'",`team` = "2" WHERE `id` = "'.$bt.'" LIMIT 1');
										$xx2++;
									}
								}
							}
							unset($plb,$spb,$logins_bot,$bt);
						}
						unset($xx2);						
						
						//генерируем команды
						$users = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`u`.`admin`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `u`.`id` = `st`.`id` WHERE `st`.`zv` = "'.$pl['id'].'"');
						while($s = mysql_fetch_array($users))
						{
							${'tm'.$s['team']} .= $u->microLogin($s['id'],1).', ';
						}					
						
						if($tm1=='')
						{
							$tm1 = 'группа пока не набрана';
						}else{
							$tm1 = rtrim($tm1,', ');
						}
						
						if($tm2=='')
						{
							$tm2 = 'группа пока не набрана';
						}else{
							$tm2 = rtrim($tm2,', ');
						}
						//
						if( $pl['teams'] == 3 ) {							
							if($tm3=='')
							{
								$tm3 = 'группа пока не набрана';
							}else{
								$tm3 = rtrim($tm3,', ');
							}
							
							$ttl1 = '';
							$ttl2 = '';
							$ttl3 = '';
							
							if( $pl['align1'] == 3 ) { $ttl1 = 'Тьма';
							}elseif( $pl['align1'] == 7 ) { $ttl1 = 'Нейтралы'; }else{
								$ttl1 = 'Свет';
							}
							if( $pl['align2'] == 3 ) { $ttl2 = 'Тьма';
							}elseif( $pl['align2'] == 7 ) { $ttl2 = 'Нейтралы'; }else{
								$ttl2 = 'Свет';
							}
							if( $pl['align3'] == 3 ) { $ttl3 = 'Тьма';
							}elseif( $pl['align3'] == 7 ) { $ttl3 = 'Нейтралы'; }else{
								$ttl3 = 'Свет';
							}
							
							$tm1 = '<img src="http://img.xcombats.com/i/align/align'.$pl['align1'].'.gif"> '.$ttl1.': ' . $tm1;
							$tm2 = '<img src="http://img.xcombats.com/i/align/align'.$pl['align2'].'.gif"> '.$ttl2.': ' . $tm2;
							$tm3 = '<img src="http://img.xcombats.com/i/align/align'.$pl['align3'].'.gif"> '.$ttl3.': ' . $tm3;
							
						}
						//
						$rad = '';
						if(!isset($zi['id']) && $u->room['zvsee']==0)
						{
							$rad = '<input type="radio" name="groupClick" id="groupClick" value="'.$pl['id'].'"> ';
						}
						if($pl['comment']!=''){
						$dl = '';
						  if(($moder['boi'] == 1 || $u->info['admin'] > 0) && $pl['dcom']==0) {
						    $dl .= ' (<a href="main.php?zayvka=1&r=4&delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">удалить комментарий</a>)';          if(isset($_GET['delcom']) && $_GET['delcom'] == $pl['id'] && $u->newAct($_GET['key']) == true) {
				              mysql_query('UPDATE `zayvki` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				              $pl['dcom'] = $u->info['id'];
			                }
						  }
						  if($pl['dcom'] > 0) { $dl = '<font color="grey"><i>Комментарий удален модератором</i></font>'; }
						  if($pl['dcom'] > 0) {
							if($moder['boi'] == 1 || $u->info['admin'] > 0) {
				              $pl['comment'] = '[ Текст комментария : <font color="red">'.$pl['comment'].'</font>]&nbsp;';
			                } else {
				              $pl['comment'] = '';
			                 }
		                   }
						$zv_comm = '('.$pl['comment'].''.$dl.')';
						}else{$zv_comm='';}
						if( $pl['teams'] == 3 ) {
							$zv_comm .= ' <a href="http://xcombats.com/lib/turnir-sklonnostei/" target="_blank"><span style="color:#543666;" title="В турнире участвует три склонности: Свет, Тьма, Нейтралы. Победившая склонность получает особенное благословение на протяжении дня."><b>(Турнир трех склонностей)</b></span></a>';
						}
						$zvb .= ''.$rad.'<font class="date">'.date('H:i',$pl['time']).'</font> <img src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif" title="'.$u->city_name[$pl['city']].'"> <B>'.$pl['tm1max'].' (</b>'.$pl['min_lvl_1'].'-'.$pl['max_lvl_1'].'<b>) на '.$pl['tm2max'].' (</b>'.$pl['min_lvl_2'].'-'.$pl['max_lvl_2'].'<b>)';
						if( $pl['teams'] == 3 ) {
							$zvb .= ' на '.$pl['tm2max'].' (</b>'.$pl['min_lvl_2'].'-'.$pl['max_lvl_2'].'<b>)';
						}
						$zvb .= '</B> ('.$tm1.') <font class="dsc"><i><span style=\'color:red; font-weight:bold;\'>против</span></font></i> ('.$tm2.')';
						if( $pl['teams'] == 3 ) {
							$zvb .= ' <font class="dsc"><i><span style=\'color:red; font-weight:bold;\'>против</span></font></i> ('.$tm3.')';
						}
						$zvb .= ' <IMG SRC="http://img.xcombats.com/i/fighttype'.$pl['type'].'.gif" WIDTH="20" HEIGHT="20" title="Групповой бой"> <font class="dsc"><i>Бой начнется через <B>'.$tmStart.'</B> мин., таймаут '.($pl['timeout']/60).' мин. '.$zv_comm.'</font></i>'.$mon.'<BR>';
					}elseif($pl['razdel']>=1 && $pl['razdel']<=3)
					{
						$uz = mysql_fetch_array(mysql_query('SELECT `u`.`banned`,`u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$pl['id'].'" AND `st`.`team`="1" LIMIT 1'));
						if(isset($uz['id']))
						{
							$uze = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$pl['id'].'" AND `st`.`team` = "2" LIMIT 1'));
							$d1 = '';
							if($uz['id']==$u->info['id'] || $uze['id']==$u->info['id'])
							{
								$d1 = 'disabled="disabled"';
							}
							if($uz['clan'] == $u->info['clan'] && $u->info['clan'] != 0) { $d1 = 'disabled="disabled"'; }
							if(!isset($uze['id']) || $u->info['zv'] == $pl['id'])
							{
								$enm = '';
								
								if(isset($uze['id']))
								{									
									$enm = ' против '.$u->microLogin($uze['id'],1).'';
								}
								if($uz['banned']>0)
								{
									$pl['id'] = 0;
									$d1 = 'disabled="disabled"';
									$zvb .= '<span style="text-decoration:line-through;">';
								}
								$dp1 = '';
								if($pl['money']>0)
								{
									$dp1 = ' Бой на деньги, ставка: <b>'.$u->round2($pl['money']).' кр.</b>';
								}
								if($u->room['zvsee'] == 0) {
									$zvb .= '<input name="btl_go" '.$d1.' type="radio" value="'.$pl['id'].'" />';
								}
								$zvb .= '<font class="date">'.date('H:i',$pl['time']).'</font> <img src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif" title="'.$u->city_name[$pl['city']].'"> '.$u->microLogin($uz['id'],1).' '.$enm.'  тип боя: <img src="http://img.xcombats.com/i/fighttype'.($pl['type']).'.gif"> (таймаут '.round($pl['timeout']/60).' мин.'.$dp1.' '.$mon.')<br>';
								if($uz['banned']>0) { $zvb .= '</span>'; }
							}
						}
					}
					$i++;
				}
				if($i==0)
				{
					//заявок нет
					if($u->room['zvsee'] > 0) {
						echo '<br><br><br><div align="center"><b>В данном разделе нет ни одной заявки</b></div>';
					}
				}else{
					if(!isset($zi['id']) && $u->room['zvsee']==0)
					{
						if($_GET['r'] == 5) {
							if( $u->info['no_zv_key'] != true ) { 
								echo '<div style="float:left;"><form method="post" style="margin:0px;padding:0px;" action="main.php?zayvka=1&r='.$r.'&rnd='.$code.'"><br><img src="http://xcombats.com/show_reg_img/security2.php?id='.time().'" width="70" height="20"> Код подтверждения: <input style="width:40px;" type="text" value="" name="code21"> <input class="btnnew" name="" type="submit" value="Принять участие в мясорубке" /><br>'.$zvb.' <img src="http://xcombats.com/show_reg_img/security2.php?id='.time().'" width="70" height="20"> Код подтверждения: <input style="width:40px;" type="text" value="" name="code22"> <input class="btnnew" style="margin-top:1px;" type="submit" value="Принять участие в мясорубке" /></form></div>';
							}else{
								echo '<div style="float:left;"><form method="post" style="margin:0px;padding:0px;" action="main.php?zayvka=1&r='.$r.'&rnd='.$code.'"><br> <input class="btnnew" name="" type="submit" value="Принять участие в мясорубке" /><br>'.$zvb.' <input class="btnnew" style="margin-top:1px;" type="submit" value="Принять участие в мясорубке" /></form></div>';
							}
						}else{
							if( $zvb != '' ) {
								echo '<div style="float:left;"><form method="post" style="margin:0px;padding:0px;" action="main.php?zayvka=1&r='.$r.'&rnd='.$code.'"><br><input class="btnnew" name="" type="submit" value="Принять вызов" /><br>'.$zvb.'<input class="btnnew" style="margin-top:1px;" type="submit" value="Принять вызов" /></form></div>';
							}
						}
					}else{
						echo $zvb;
					}
				}
			}
		}
	}
	
	public function go($id)
	{
		global $u,$c,$code,$zi,$filter;
		if(!isset($zi['id']))
		{
			if($u->info['battle']==0 && $u->info['inTurnirnew']==0)
			{
				$z = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id`="'.mysql_real_escape_string(intval($id)).'" /*AND `city` = "'.$u->info['city'].'"*/ AND `start` = "0" AND `cancel` = "0" AND `time` > "'.(time()-60*60*2).'" LIMIT 1'));
				if(isset($z['id']))
				{
					if($z['razdel']>=1 && $z['razdel']<=3)
					{
						//новички, физы, договорные
						$uz1 = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$z['id'].'" AND `st`.`team`="1" LIMIT 1'));
						if(isset($uz1['id']))
						{
							$uz2 = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$z['id'].'" AND `st`.`team`="2" LIMIT 1'));
							if( $this->testTravm() == 1 &&  $z['type'] != 1 ) {
								$this->error = 'Вы травмированы. С такими увечьями доступны кулачные бои.';
								$az = 0;
							}elseif( $this->testTravm() == 2 ) {
								$this->error = 'Вы травмированы. С такими увечьями невозможно сражаться.';
								$az = 0;
							}elseif($u->info['hpNow']<$u->stats['hpAll']/100*30 && ($z['razdel']>=1 || $z['razdel']<=3)) {
								$this->error = 'Вы еще слишком ослаблены чтобы начать новый бой';
								$az = 0;
							} elseif($uz1['clan']==$u->info['clan'] && $u->info['clan']!=0 && $u->info['admin'] == 0) {
								$this->error = 'Вы не можете сражаться против сокланов';
							} elseif($z['withUser']!='' && $filter->mystr($u->info['login'])!=$filter->mystr($z['withUser']) && $z['razdel']==3) {
								$this->error = 'Вы не можете принять эту заявку';
							} elseif($z['money'] > 0 && $z['money'] > $u->info['money']) {
								$this->error = 'У Вас недостаточно денег, чтобы принять эту заявку';
							}elseif($u->stats['hpNow']<ceil($u->stats['hpMax']/100*30))
							{
								$this->error = 'Вы слишком ослаблены, восстановитесь';
							}elseif(!isset($uz2['id']))
							{
								$upd = mysql_query('UPDATE `stats` SET `zv` = "'.$z['id'].'",`team` = "2" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								if($upd)
								{
									$ca = '';
									if($uz1['clan']!=0)
									{
										$pc = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id`="'.$uz1['clan'].'" LIMIT 1'));
										if(isset($pc['id']))
										{
											$pc['img'] = $pc['name_mini'].'.gif';
											$ca = '<img title="'.$pc['name'].'" src="http://img.xcombats.com/i/clan/'.$pc['name_mini'].'.gif">';
										}
									}
									if($uz1['align']!=0)
									{
										$ca = '<img src="http://img.xcombats.com/i/align/align'.$uz1['align'].'.gif">'.$ca;
									}
									$this->error = 'Ожидаем подтверждения боя от '.$ca.' '.$uz1['login'].' ['.$uz1['level'].']<a href="info/'.$uz1['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif"></a>';
									$sa = '';
									if($u->info['sex']==2)
									{
										$sa = 'а';
									}
									$text = ' [login:'.$u->info['login'].'] принял'.$sa.' вашу заявку на бой.[reflesh_main_zv_priem:'.$u->info['id'].']';
									mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$uz1['city']."','','','".$uz1['login']."','".$text."','".time()."','6','0')");
									$zi = $z;
									$u->info['zv'] = $z['id'];
									$u->info['team'] = 2;
								}else{
									$this->error = 'Невозможно принять заявку.';
								}
							}else{
								$this->error = 'Заявку уже кто-то принял до вас.';
							}
						}else{
							$this->error = 'Заявка на бой заблокирована.';
						}
					}elseif($z['razdel']==4 && $u->info['level']>1)
					{
						$tm = 0;
						//групповые
						if(isset($_GET['tm1']))
						{
							$tm = 1;
						}elseif(isset($_GET['tm2']))
						{
							$tm = 2;
						}elseif(isset($_GET['tm3']) && $z['teams'] > 2)
						{
							$tm = 3;
						}else{
							$this->error = 'Что-то здесь не так';	
						}
						
						if($tm!=0)
						{
							$t1 = $tm;
							$t2 = 1;
							$t1sz = $t1;
							if( $t1sz > 2 ) {
								$t1sz = 2;
							}
							$tmmax = 0;
							if($tm==1){ $t2 = 2; }
							$cl111 = mysql_query('SELECT `u`.`clan`,`st`.`team`,`st`.`id`,`st`.`zv` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv` = "'.$z['id'].'" LIMIT 200');
							$cln = 0;
							while($pc111 = mysql_fetch_array($cl111))
							{
								if($pc111['clan']==$u->info['clan'] && $u->info['clan']!=0 && $pc111['team']!=$t1)
								{
									$cln++;
								}
								if($pc111['team']==$t1)
								{
									$tmmax++;
								}
							}
							$algnt = mysql_fetch_array(mysql_query('SELECT * FROM `users_align` WHERE `uid` = "'.$inf['id'].'" AND (`delete` = 0 OR `delete` > "'.time().'") LIMIT 1'));
							if( $z['teams'] == 3 && $z['align'.$t1] != floor($u->info['align']) && ($u->info['align'] != 0 || ($z['align'.$t1] != floor($algnt['align']) && isset($algnt['id']))) ) {
								//Бой 3-х сторон
								$this->error = 'Вы выбрали не ту сторону! (align'.$z['align'.$t1].')';
							}elseif( $this->testTravm() == 1 &&  $z['k'] != 1 ) {
								$this->error = 'Вы травмированы. С такими увечьями доступны кулачные бои.';
							}elseif( $this->testTravm() == 2 ) {
								$this->error = 'Вы травмированы. С такими увечьями невозможно сражаться.';
							}elseif($cln>0)
							{
								$this->error = 'Вы не можете сражаться против сокланов';
							}elseif($z['bot2']==1 && $t1==2) {
								$this->error = 'Вы не можете сражаться на стороне ботов';
							} elseif($z['money'] > 0 && $u->info['level'] < 4) {
								$this->error = 'Бои на деньги проводятся с 4-го уровня';
							}elseif($z['tm'.$t1sz.'max'] > $tmmax)
							{
								if($z['min_lvl_'.$t1sz]>$u->info['level'] || $z['max_lvl_'.$t1sz]<$u->info['level'])
								{
									$this->error = 'Вы не подходите по уровню, за эту команду могут зайти персонажи '.$z['min_lvl_'.$t1sz].' - '.$z['max_lvl_'.$t1sz].' уровня';
								}elseif($u->stats['hpNow']<ceil($u->stats['hpMax']/100*30))
								{
									$this->error = 'Вы слишком ослаблены, восстановитесь';
								}else{
									$upd = mysql_query('UPDATE `stats` SET `zv` = "'.$z['id'].'",`team` = "'.mysql_real_escape_string((int)$t1).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									if(!$upd)
									{
										$this->error = 'Ошибка приема заявки...';
									}else{
										$this->error = 'Вы приняли групповой бой...';
										$zi = $z;
										$u->info['zv'] = $z['id'];
										$u->info['team'] = mysql_real_escape_string((int)$t1);
									}
								}
							}else{
								$this->error = 'Группа уже набрана ('.($z['tm'.$t1sz.'max']-$tmmax).')';
							}
						}
					}elseif($z['razdel']==5 && $u->info['level']>1)
					{
						//хаотичные
						if( $this->testTravm() == 1 &&  $z['k'] != 1 ) {
							$this->error = 'Вы травмированы. С такими увечьями доступны кулачные бои.';
						}elseif( $this->testTravm() == 2 ) {
								$this->error = 'Вы травмированы. С такими увечьями невозможно сражаться.';
						}elseif( $u->info['no_zv_key'] != true && (!isset($_SESSION['code2']) || $_SESSION['code2'] < 1 || ($_POST['code21'] != $_SESSION['code2'] && $_POST['code22'] != $_SESSION['code2'])) )
						{
							$this->error = 'Неправильный код подтверждения';
						}elseif($z['min_lvl_1']>$u->info['level'] || $z['max_lvl_1']<$u->info['level'])
						{
							$this->error = 'Вы не подходите по уровню, за эту команду могут зайти персонажи '.$z['min_lvl_1'].' - '.$z['max_lvl_1'].' уровня';
						}elseif($u->stats['hpNow']<ceil($u->stats['hpMax']/100*30)) {
							$this->error = 'Вы слишком ослаблены, восстановитесь';
						} elseif($z['money'] > 0 && $u->info['level'] < 4) {
						  $this->error = 'Бои на деньги проводятся с 4-го уровня';
						}else{
							$t1 = 0;
							
							/* считаем баланс */
							/*if($z['tm1'] > $z['tm2'])
							{
								$t1 = 2;
							}elseif($z['tm1'] < $z['tm2'])
							{
								$t1 = 1;
							}else{
								$t1 = rand(1,2);
							}*/
							
							/*
							$tmtest1 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$z['id'].'" AND `team` = 1 LIMIT 1'));
							$tmtest2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$z['id'].'" AND `team` = 2 LIMIT 1'));
							
							if( $t1 == 1 && $tmtest1[0]-2 > $tmtest2[0] ) {
								$t1 = 2;
							}elseif( $t1 == 2 && $tmtest2[0]-2 > $tmtest1[0] ) {
								$t1 = 1;
							}
							*/
							
							if($z['invise']==1)
							{
								$nxtID = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$z['id'].'"'));
								$nxtID = $nxtID[0];
								//$u->info['login2'] = 'Боец ('.($nxtID+1).')';
								$u->info['login2'] = '';
							}else{
								$u->info['login2'] = '';
							}
							
							$blnc = $u->stats['reting'];
					
							$z['tm'.$t1] += $blnc;
													
							$upd = mysql_query('UPDATE `stats` SET `zv` = "'.$z['id'].'",`team` = "'.$t1.'",`zv_enter` = "'.time().'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if(!$upd)
							{
								$this->error = 'Ошибка приема заявки...';
							}else{
								//mysql_query('UPDATE `eff_users` SET `zv_enter` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'"');
								mysql_query('UPDATE `users` SET `login2` = "'.$u->info['login2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								mysql_query('UPDATE `zayvki` SET `tm1` = "'.$z['tm1'].'", `tm2` = "'.$z['tm2'].'" WHERE `id` = "'.$z['id'].'" LIMIT 1');
								$this->error = 'Вы приняли хаотичный бой...';
								$zi = $z;
								$u->info['zv'] = $z['id'];
								$u->info['team'] = mysql_real_escape_string((int)$t1);
							}
						}
					}
				}else{
					$this->error = 'Заявка на бой не найдена.';
				}						
			}
		}else{
			$this->error = 'Вы не можете принять бой. Сначала отзовите свою заявку.';
		}
	}	
}
$zv = new zayvki;
$zv->test(); //проверяем заявки
?>