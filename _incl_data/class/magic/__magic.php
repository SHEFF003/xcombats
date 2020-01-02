<?php
if(!defined('GAME'))
{
	die();
}

class Magic
{
	
	public $youuse = 0;
	
	//Ослабление после боя
	public function oslablenie($uid)
	{
		$ins = mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`timeUse`) VALUES ("5","'.$uid.'","Ослабление после боя","add_m10=-1000|add_m11=-1000","'.time().'")');
		if($ins)
		{
			return true;
		}else{
			return false;
		}
	}
	
	//Использование предмета
	public function useItems($id)
	{
		global $u,$c,$code;
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid` = "'.$u->info['id'].'" AND `iu`.`id` = "'.mysql_real_escape_string((int)$id).'" LIMIT 1'));
		if(isset($itm['id']))
		{
			$st = $u->lookStats($itm['data']);
			if(isset($st['usefromfile']) && $st['usefromfile']==1)
			{
				if(file_exists('_incl_data/class/magic/'.$itm['magic_inci'].'.php'))
				{
					require('_incl_data/class/magic/'.$itm['magic_inci'].'.php');
				}else{
					$u->error = '7Не удалось использовать ('.$itm['magic_inci'].')';
				}
			}elseif($itm['type']==30)
			{
				//Эликсиры
				$goodUse = 0; $use = array();
				if(isset($st['moment']))
				{
					//Эликсир используется моментально (Восстановление НР или МР)
					if(isset($st['moment_hp']))
					{
						//Восстанавливаем здоровье
						if($u->stats['hpNow']<$u->stats['hpAll'])
						{
							$goodUse = 1;
							$use['moment_hp'] = $st['moment_hp'];
							
							if($u->stats['hpNow']+$use['moment_hp']>$u->stats['hpAll'])
							{
								$use['moment_hp'] = ceil($u->stats['hpAll']-$u->stats['hpNow']);							
							}
							
							$u->error .= 'Вы восстановили '.($use['moment_hp']).' HP.<br>';
						}else{
							$u->error = 'Ваше здоровье и так полностью восстановлено';
							$goodUse = 0;
						}
					}	
					
					if(isset($st['moment_mp']))
					{
						//Восстанавливаем здоровье
						if($u->stats['mpNow']<$u->stats['mpAll'])
						{
							$goodUse = 1;
							$use['moment_mp'] = $st['moment_mp'];
							
							if($u->stats['mpNow']+$use['moment_mp']>$u->stats['mpAll'])
							{
								$use['moment_mp'] = ceil($u->stats['mpAll']-$u->stats['mpNow']);							
							}
							
							$u->error .= 'Вы восстановили '.($use['moment_mp']).' MP.<br>';
						}else{
							$u->error = 'Ваша манна и так полностью восстановлена';
							$goodUse = 0;
						}
					}	
					
					if($itm['iznosNOW']>=$itm['iznosMAX'])
					{
						$u->error = 'Эликсир был испорчен...';
						$goodUse = 0;
					}
						
					if(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
					{
						$goodUse = 0;
						$u->error = 'Хаосники не могут использовать данный эликсир';
					}
								
					//Заносим данные в БД
					if($goodUse==1)
					{
						$itm['iznosNOW']++;
						$upd = mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
						if($upd)
						{
							$u->stats['hpNow'] += $use['moment_hp'];
							$u->info['hpNow'] += $use['moment_hp'];
							mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->info['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал эликсир &quot;'.$itm['name'].'&quot; (+'.$use['moment_hp'].' HP) [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
							$this->youuse++;
							$u->error = 'Вы успешно использовали эликсир &quot;'.$itm['name'].'&quot;<br>'.$u->error.'';
						}else{
							$u->error = 'Не удалось использовать эликсир...';
						}
					}
				}else{
					//Эликсиры с продолжительным эффектом
					$goodUse = 1;
					if(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
					{
						$goodUse = 0;
						$u->error = 'Хаосники не могут использовать данный эликсир';
					}
					if($goodUse==1)
					{
						$upd1 = 1;
						$upd2 = 1;
						//добавляем эффект персонажу
						if(isset($st['onlyOne']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd1 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `id_eff` = "'.$itm['magic_inc'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						if(isset($st['oneType']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd2 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `overType` = "'.$itm['overType'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						if($goodUse == 1)
						{
							$us = $this->add_eff($u->info['id'],$itm['magic_inc']);
							if($us[0]==1)
							{
								$itm['iznosNOW']++;
								mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
								$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал эликсир &quot;'.$itm['name'].'&quot; ('.$us[1].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
								$this->youuse++;
								$u->error = 'Вы успешно использовали эликсир &quot;'.$itm['name'].'&quot;<br>'.$us[1].'';
							}else{
								$u->error = '6Не удалось использовать "'.$itm['name'].'"';
							}
						}else{
							$u->error = '5Не удалось использовать "'.$itm['name'].'"';
						}
					}
				}
				//---------------
			}elseif($itm['type']==29)
			{
				//используем заклятие
				$st = $u->lookStats($itm['data']);
				$jl = $_GET['login'];
				$_GET['login'] = urlencode($_GET['login']);
				//используем на персонажа (все кроме себя)	
				$_GET['login'] = str_replace('%',' ',$_GET['login']);
				$_GET['login'] = str_replace('25','',$_GET['login']);
				$jl = str_replace('%',' ',$jl);
				$jl = str_replace('25','',$jl);
				if(isset($st['useOnLogin']) && $st['useOnLogin']==1)
				{
					$usr = mysql_fetch_array(mysql_query('SELECT `st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") LIMIT 1'));
					if(isset($usr['id']))
					{
						if($itm['iznosNOW']>=$itm['iznosMAX'])
						{
							$u->error = 'Свиток был исполчен...';
						}elseif($itm['magic_inci']=='snowball')
						{
							 if($usr['id']==$u->info['id'])
							{
								$u->error = 'Нельзя кидаться в самого себя';
							}elseif($usr['online']<time()-120)
							{
								$u->error = 'Персонаж находится в реальном мире ;)';
							}elseif($usr['room']!=$u->info['room'])
							{
								$u->error = 'Персонаж находится в другой комнате ['.$usr['room'].' '.$u->info['room'].']';
							}elseif($usr['admin']>0 && $u->info['admin']==0)
							{
								$u->error = 'Нельзя кидаться в Ангелов';
							}elseif($usr['battle']>0 && $u->info['battle']!=$usr['battle'])
							{
								$u->error = 'Персонаж находится в бою';
							}else{
								$usr['hpNow'] -= 10;
								if($usr['hpNow']<0)
								{
									$usr['hpNow'] = 0;
								}
								$upd = mysql_query('UPDATE `stats` SET `hpNow` = "'.$usr['hpNow'].'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
								if($upd)
								{
									$sx = 'ый'; $sx2 = '';
									if($u->info['sex']==1)
									{
										$sx = 'ая'; $sx2 = 'а';
									}
									$itm['iznosNOW']++;
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									$rtxt = '[img[items/snowball1.gif]] Удачлив'.$sx.' &quot;'.$u->info['login'].'&quot; бросил'.$sx2.' кусок снега в &quot;'.$usr['login'].'&quot;. <font color=red><b>-10</b></font> ['.$usr['hpNow'].'/??]';
									mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','11','0','1')");	
									unset($sx,$sx2);
								}else{
									$u->error = 'Не удалось кинуть снежок...';
								}								
							}
						}elseif($itm['magic_inci']=='atack')
						{
						//заклятье нападения
						$usta = $u->getStats($usr['id'],0); // статы цели
		                $minHp = $usta['hpAll']/100*33; // минимальный запас здоровья цели при котором можно напасть

							if($u->info['dnow']!=$usr['dnow']){
								$u->error = 'Персонаж находится в другой комнате [пещера]';
							}elseif($u->info['battle']>0){
								$u->error = 'Вы уже находитесь в бою';
							}elseif($usr['id']==$u->info['id']){
								$u->error = 'Нельзя нападать на самого себя';
							}elseif($usr['online']<time()-120){
								$u->error = 'Персонаж находится в реальном мире';
							}elseif($usr['room']!=$u->info['room']){
								$u->error = 'Персонаж находится в другой комнате ['.$usr['room'].' '.$u->info['room'].']';
							}elseif($usr['admin']>0 && $u->info['admin']==0){
								$u->error = 'Нельзя нападать на Ангелов';
							}elseif($minHp>$usta['hpNow']){
							//мало хп
								$u->error = 'Персонаж имеет слишком малый уровень жизней.';
							}else{
								$atc = $this->atackUser($u->info['id'],$usr['id'],$usr['team'],$usr['battle']);
								if($atc==1 && $u->info['align'] != 2){
									//отправляем системку в чат
									$sx = '';
									if($u->info['sex']==1){
										$sx = 'а';
									}
									$itm['iznosNOW']++;
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									$rtxt = '[img[items/pal_button8.gif]] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' магию нападения на персонажа &quot;'.$usr['login'].'&quot;.';
									mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','11','0','1')");	
									//напали, обновляем экран
									die('<script>top.frames[\'main\'].location = "main.php";</script>');
								}else{
									$u->error = 'Не удалось напасть на персонажа...';
								}
							}
						}else{
							//просто используем на персонажа
							if($u->info['dnow']!=$usr['dnow'])
							{
								$u->error = 'Персонаж находится в другой комнате [пещера]';
							}elseif($usr['id']==$u->info['id'] && isset($st['useOnlyUser']))
							{
								$u->error = 'Нельзя использовать это заклятие на самого себя';
							}elseif($usr['online']<time()-120)
							{
								$u->error = 'Персонаж находится в реальном мире ;)';
							}elseif($usr['room']!=$u->info['room'])
							{
								$u->error = 'Персонаж находится в другой комнате ['.$usr['room'].' '.$u->info['room'].']';
							}elseif($usr['admin']>0 && $u->info['admin']==0 && isset($st['useNoAdmin']))
							{
								$u->error = 'Нельзя использовать данное заклятие на Ангелов';
							}elseif($usr['battle']>0 && $u->info['battle']!=$usr['battle'])
							{
								$u->error = 'Персонаж находится в бою';
							}elseif(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
							{
								$u->error = 'Хаосники не могут использовать данное заклятие';
							}else{
								//добавляем эффект персонажу
								$goodUse = 1;
								if(isset($st['onlyOne']))
								{
									//убираем прошлые эффекты
									$goodUse = 0;
									$upd1 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$usr['id'].'" AND `delete` = "0" AND `id_eff` = "'.$itm['magic_inc'].'"');
									if($upd1)
									{										
										$goodUse = 1;
									}
								}
								if(isset($st['oneType']))
								{
									//убираем прошлые эффекты									
									$goodUse = 0;
									$upd2 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$usr['id'].'" AND `delete` = "0" AND `overType` = "'.$itm['overType'].'"');
									if($upd1)
									{
										$goodUse = 1;
									}
								}
								if($goodUse == 1)
								{
									$us = $this->add_eff($usr['id'],$itm['magic_inc']);
									if($us[0]==1)
									{
										$itm['iznosNOW']++;
										mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
										if($u->info['id']!=$usr['id'])
										{
											$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') на персонажа &quot;'.$usr['login'].'&quot; (id'.$usr['id'].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
											$u->addDelo(1,$usr['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж &quot;'.$u->info['login'].'&quot; (id'.$u->info['id'].') использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') на персонажа [itm:'.$itm['id'].'].',time(),$usr['city'],'System.inventory',0,0);
											$u->error = 'Вы успешно использовали заклинание &quot;'.$itm['name'].'&quot; на персонажа &quot;'.$usr['login'].'&quot;<br>'.$us[1].'';
											$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' заклинание &quot;'.$itm['name'].'&quot; на персонажа &quot;'.$usr['login'].'&quot;.';
											mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','11','0','1')");	
										}else{
											$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') на персонажа самого себя [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
											$u->error = 'Вы успешно использовали заклинание &quot;'.$itm['name'].'&quot; на самого себя<br>'.$us[1].'';
											$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' заклинание &quot;'.$itm['name'].'&quot; на себя.';
											mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','11','0','1')");	
										}
										$this->youuse++;
									}else{
										$u->error = '1Не удалось использовать "'.$itm['name'].'"';
									}
								}else{
									$u->error = '2Не удалось использовать "'.$itm['name'].'"';
								}
							}
						}
					}else{
						$u->error = 'Персонаж "'.$jl.'" не найден в этом городе ('.$u->info['city'].')';
					}
				}elseif(isset($st['useOnItem']) && $st['useOnItem']==1)
				{
					//используем на предмет
					
				}else{
					//на себя
					$goodUse = 1;
					if(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
					{
						$goodUse = 0;
						$u->error = 'Хаосники не могут использовать данное заклятие';
					}
					if($goodUse==1)
					{
						$upd1 = 1;
						$upd2 = 1;
						//добавляем эффект персонажу
						if(isset($st['onlyOne']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd1 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `id_eff` = "'.$itm['magic_inc'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						if(isset($st['oneType']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd2 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `overType` = "'.$itm['overType'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						
						if($itm['magic_inci']=='add_animal')
						{
							if($u->info['animal']>0)
							{
								$u->error = 'Не удалось использовать "'.$itm['name'].'", у Вас уже есть зверь.';
							}else{
								$anm = array('type'=>1,'name'=>'','obraz'=>'','stats'=>'','sex'=>0);
								if($anm['type']==1)
								{
									$anm['name'] = 'Кот';
									$anm['sex'] = 0;
									$anm['obraz'] = array(1=>'20132.gif',2=>'21139.gif',3=>'20864.gif',4=>'21301.gif');
									$anm['stats'] = 's1=2|s2=2|s3=2|s4=5|rinv=40|m9=5|m6=10';									
								}
								$anm['obraz'] = $anm['obraz'][rand(1,count($anm['obraz']))];
								$ins = mysql_query('INSERT INTO `users_animal` (`type`,`name`,`uid`,`obraz`,`stats`,`sex`) VALUES ("'.$anm['type'].'","'.$anm['name'].'","'.$u->info['id'].'","'.$anm['obraz'].'","'.$anm['stats'].'","'.$anm['sex'].'")');
								if($ins)
								{
									
									$u->info['animal'] = mysql_insert_id();
									mysql_query('UPDATE `users` SET `animal` = "'.$u->info['animal'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									$u->error = 'Вы успешно использовали "'.$itm['name'].'" и помните - &quot;Мы в ответе за тех, кого приручили&quot;.';
									$itm['iznosNOW']++;
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
								}else{
									$u->error = 'Не удалось использовать "'.$itm['name'].'", что-то здесь не так ...';
								}
							}
						}elseif($goodUse == 1)
						{
							$us = $this->add_eff($u->info['id'],$itm['magic_inc']);
							if($us[0]==1)
							{
								$itm['iznosNOW']++;
								mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
								$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
								$this->youuse++;
								$u->error = 'Вы успешно использовали заклинание &quot;'.$itm['name'].'&quot;<br>'.$us[1].'';
								$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' заклинание &quot;'.$itm['name'].'&quot; на себя.';
								mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','11','0','1')");	
							}else{
								$u->error = '3Не удалось использовать "'.$itm['name'].'"';
							}
						}else{
							$u->error = '4Не удалось использовать "'.$itm['name'].'"';
						}
					}
				 //------------------------------
				}				
			}		
		}else{
			$u->error = 'Предмет не найден в инвентаре';
		}
	}


	public function add_eff($uid,$id)
	{
		$g = array(0=>0,1=>'');
		$eff = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$id.'" LIMIT 1'));	
		if(isset($eff['id2']))
		{
			$n = $eff['mname'];
			$d = $eff['mdata'];
			$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`id_eff`,`uid`,`name`,`timeUse`,`data`) VALUES ("'.$eff['oneType'].'","'.$eff['id2'].'","'.$uid.'","'.$n.'","'.time().'","'.$d.'")');
			if($ins)
			{
				$g[0] = 1;
				$g[1] = '...';
			}
		}
		return $g;
	}
	
	//создаем нападение на персонажа
	public function atackUser($uid1,$uid2,$tm,$btl,$addExp = 0)
	{
		$good = 0;
		if($btl==0)
		{
			//нападаем на персонажа
			$ins = mysql_query('INSERT INTO `battle` (`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
				"'.$u->info['city'].'",
				"'.time().'",
				"'.$u->info['login'].','.$usr['login'].'",
				"180",
				"0",
				"0",
				"0",
				"50",
				"9",
				"'.$addExp.'",
				"0")');
			if($ins)
			{
				$btl_id = mysql_insert_id();
				$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$uid1.'" OR `id` = "'.$uid2.'" LIMIT 2');
						 mysql_query('UPDATE `stats` SET `team`="1",`zv` = "0" WHERE `id` = "'.$uid1.'" LIMIT 1');
						 mysql_query('UPDATE `stats` SET `team`="2",`zv` = "0" WHERE `id` = "'.$uid2.'" LIMIT 1');
				$good = 1;
			}
		}else{
			//вмешиваемся в бой
			$upd = mysql_query('UPDATE `users` SET `battle`="'.$btl.'" WHERE `id` = "'.$uid1.'" LIMIT 1');
			if($upd)
			{
				$ltm = array(1=>2,2=>1);
				mysql_query('UPDATE `stats` SET `team`="'.$ltm[$tm].'" WHERE `id` = "'.$uid1.'" LIMIT 1');
				$good = 1;
			}
		}
		return $good;
	}
	
	//Нападение на центральной площади
	public function magicCentralAttack()
	{
		global $c,$code,$u,$re;
		
	}
}

$magic = new Magic;

?>