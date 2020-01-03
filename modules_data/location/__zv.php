<?
if(!defined('GAME'))
{
	die();
}

class zayvki
{
	public $zv_see,$error,$z1n = array(4=>'групповые',5=>'хаотичные'),$z2n = array(4=>'группового',5=>'хаотичного');
	
	public function test()
	{
		global $code,$c,$u;
		//Проверяем хаотичные и групповые бои в этом городе		
		$sp = mysql_query('SELECT * FROM `zayvki` AS `z` WHERE `z`.`city` = "'.$u->info['city'].'" AND `z`.`btl_id` = "0" AND `z`.`cancel` = "0"  AND `z`.`start` = "0" AND (`z`.`razdel` = 4 OR `z`.`razdel` = 5) ORDER BY `z`.`id` DESC LIMIT 11');
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
			if($pl['time_start'] < time()-$pl['time'] || ($pl['razdel']==4 && $i[1]>=$pl['tm1max'] && $i[2]>=$pl['tm2max']))
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
					if($i[1]+$i[2]<4)
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
		$upd = mysql_query('UPDATE `stats` SET `zv` = "0" WHERE `zv` = "'.$zv['id'].'"');
		if($upd)
		{
			$upd = mysql_query('UPDATE `zayvki` SET `cancel` = "'.time().'" WHERE `id` = "'.$zv['id'].'"');
			if($upd)
			{
				$text = ' Не удалось начать поединок по причине: Группа не набрана.';
				mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('".$zv['city']."','','','".$uids."','".$text."','".time()."','11','0')");
			}
		}
	}
	
	public function add()
	{
		global $u,$c,$code;
		if(isset($_GET['r']))
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
				if($u->info['hpNow']<$u->stats['hpAll']/100*30 && ($r>=1 || $r<=3))
				{
					$this->error = 'Вы еще слишком ослаблены чтобы начать новый бой';
					$az = 0;
				}elseif($r==3 && $money>0 && $u->info['level']<4)
				{
					$this->error = 'Бои на деньги проводятся с 4-го уровня';
					$az = 0;
				}elseif($r==3 && $money<0.5 && $money>0)
				{
					$this->error = 'Минимальная ставка 0.50 кр.';
					$az = 0;
				}elseif($r==3 && $money>30)
				{
					$this->error = 'Максимальная ставка 30.00 кр.';
					$az = 0;
				}elseif($r==3 && $money>$u->info['money'])
				{
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
							$nz['comment'] = $_POST['cmt'];
							$nz['comment'] = str_replace('"','&quot;',$nz['comment']);
							if($nz['time_start']!=300 && $nz['time_start']!=600 && $nz['time_start']!=900 && $nz['time_start']!=1200 && $nz['time_start']!=1800)
							{
								$nz['time_start'] = 600;
							}
							
							if(isset($_POST['mut_hidden']))
							{
								$nz['invise'] = 1;
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
							
							$nz['tm1'] = 100*$u->info['level']+10*$u->info['upLevel']+$u->info['exp']+$u->stats['reting'];
							
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
							$nz['comment'] = $_POST['cmt'];
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
							
							if($nz['tm1max']+$nz['tm2max']<3)
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
					if($gad==1)
					{
						if(!isset($nz['withUser'])){ $nz['withUser'] = ''; }
						$bt1 = (int)$_POST['bot1'];
						$bt2 = (int)$_POST['bot2'];
						if($bt1>99){ $bt1 = 99; }
						if($bt2>99){ $bt2 = 99; }
						if($bt1<0){ $bt1 = 0; }
						if($bt2<0){ $bt2 = 0; }
						$ins = mysql_query('INSERT INTO `zayvki` (`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
																"'.((int)$bt1).'",
																"'.((int)$bt2).'",
																"'.time().'",
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
		if($u->info['level']>3 && $u->info['admin']==0 && $u->info['id']!=1011840)
		{
			$bot = false;	
		}else{
			$bot = $u->addNewbot($id['id'],NULL,$u->info['id']);
		}
		if($bot==false)
		{
			$this->error = 'Бои с монстрами, нежитью, клонами и прочими вурдалаками проводятся только для персонажей младше 4 уровня...<br>Со стороны посматривает Общий Враг, ему явно что-то не понравилось...<br>';
		}elseif($u->info['hpNow']<$u->stats['hpAll']/100*30 && ($r>=1 || $r<=3))
		{
			$this->error = 'Вы еще слишком ослаблены чтобы начать новый бой';
			$az = 0;
		}elseif($bot==false)
		{
			echo '<br><font color=red>Cannot start battle (no prototype "ND0Clone")</font><br>';
		}else{
			//создаем поединок с ботом
			$expB = 25;
			$btl = array('players'=>'','timeout'=>60,'type'=>0,'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>$expB,'money'=>0);
			$ins = mysql_query('INSERT INTO `battle` (`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
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
			if($ins)
			{
				$btl_id = mysql_insert_id();
				//обновляем данные о поединке						
				$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" OR `id` = "'.$bot.'" LIMIT 2');
				mysql_query('UPDATE `stats` SET `team`="1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
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
			$bots = array(1=>'Снеговик Меченосец');
			$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].' ['.$lvl.']" AND `active` = "1" LIMIT 1'));
			$bot = $u->addNewbot($id['id'],NULL);
			if(isset($id['id']) && $bot!=false)
			{
				//создаем поединок с ботом
				$expB = -$bot['expB'];
				$btl = array('players'=>'','timeout'=>60,'type'=>9,'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>$expB,'money'=>0,'izlom'=>(int)$id2,'izlomLvl'=>(int)$lvl);
				$ins = mysql_query('INSERT INTO `battle` (`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`,`izlom`,`izlomLvl`) VALUES (
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
		$z = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id`="'.$id.'" AND `start` = "0" AND `cancel` = "0" AND (`time` > "'.(time()-60*60*2).'" OR `razdel` > 3) LIMIT 1'));
		if(isset($z['id']))
		{
			$vars = explode('|-|',$vars);
			if($z['razdel']>=4 && $zv['razdel']<=5)
			{
				//начало группового или хаотичного боя
				$btl_id = 0;
				$btl = array('players'=>'','timeout'=>$z['timeout'],'type'=>$z['type'],'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>0,'money'=>0);
				$ins = mysql_query('INSERT INTO `battle` (`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
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
													"'.mysql_real_escape_string($btl['money'],2).'")');
				$btl_id = mysql_insert_id();
				if($btl_id>0)
				{
					//обновляем данные о поединке						
					$upd1  = mysql_query('UPDATE `stats` SET `zv`="0" WHERE `zv` = "'.$z['id'].'"');
					$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE '.$vars[1].'');
					
					//Если бой кулачный, то снимаем вещи
					if($z['type']==1)
					{
						//mysql_query('UPDATE `items_users` SET `inOdet`="0" WHERE `uid` = "'.$u->info['id'].'" AND `inOdet`!=0');
					}
					
					//обновляем заявку, что бой начался
					$upd = mysql_query('UPDATE `zayvki` SET `start` = "'.time().'",`btl_id` = "'.$btl_id.'" WHERE `id` = "'.$z['id'].'" LIMIT 1');
					$u->info['battle'] = $btl_id;
					//Отправляем сообщение в чат всем бойцам
					mysql_query("INSERT INTO `chat` (`city`,`room`,`to`,`time`,`type`,`toChat`,`sound`) VALUES ('".$u->info['city']."','-1','".$vars[0]."','".time()."','11','0','117')");
					die('<script>location="main.php?battle_id='.$btl_id.'";</script>');
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
						//создаем поединок						
						$btl_id = 0;
						if($uz['money']<$z['money'] || $u->info['money']<$z['money'])
						{
							$z['money'] = 0;
						}
						$btl = array('players'=>'','timeout'=>$z['timeout'],'type'=>$z['type'],'invis'=>0,'noinc'=>0,'travmChance'=>0,'typeBattle'=>0,'addExp'=>0,'money'=>round($z['money'],2));
						$ins = mysql_query('INSERT INTO `battle` (`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
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
							$this->error = 'Вы отказали '.$enemy['login'].' в поединке';
							//отправляем сообщение в чат
							$sa = '';
							if($u->info['sex']==2)
							{
								$sa = 'а';
							}
							$text = ' [login:'.$u->info['login'].'] отказал'.$sa.' вам в поединке.';
							mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('".$enemy['city']."','".$enemy['room']."','','".$enemy['login']."','".$text."','".time()."','10','0')");
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
								mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('".$uz['city']."','".$uz['room']."','','".$uz['login']."','".$text."','".time()."','10','0')");
							}
							$u->info['zv'] = 0;
							$u->info['team'] = 0;
						}
					}
				}
			}else{
				if($zi['razdel']>=1 && $zi['razdel']<=3 && $u->info['team']==1)
				{
					//удаляем заявку на бой
					$upd = mysql_query('UPDATE `zayvki` SET `cancel` = "'.time().'" WHERE `id` = "'.$zi['id'].'" LIMIT 1');
					if($upd)
					{
						mysql_query('UPDATE `stats` SET `zv` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
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
			if($r>=1 && $r<=5)
			{
				$this->zv_see = 1;
				if($u->room['FR']==0)
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
				}elseif($u->info['zv']>0 && $u->info['battle']==0)
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
									echo '<div style="float:left;"><div style="float:left;">Вы уже подали заявку на бой  <INPUT onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&cancelzv\';" TYPE=submit name=close value="Отозвать заявку"></div>';
								}else{
									//если кто-то принял
									$sa = '';
									if($uz['sex']==2)
									{
										$sa = 'а';
									}
									echo '<script> zv_Priem = '.(0+$uz['id']).';</script><font color="red"><b>Вашу заявку принял'.$sa.' '.$ca.'</font></b> '.$u->microLogin($uz['id'],1).'</a><font color="red"><b> Хотите подтвердить бой? </b></font><INPUT onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&startBattle\';" TYPE=submit name=close value="Подтвердить"> <INPUT onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&cancelzv\';" TYPE=submit name=close value="Отказать">';
								}
							}else{
								$uz = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$zi['id'].'" AND `st`.`team`="1" LIMIT 1'));
								if(isset($uz['id']))
								{
									echo 'Ожидаем подтверждения боя от '.$u->microLogin($uz['id'],1).' <INPUT onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&rnd='.$code.'&cancelzv\';" TYPE=submit name=close value="Отозвать запрос">';
								}else{
									//удаляем заявку
									
								}
							}
							
							echo '</td>
							<td align="right" valign="top">
								<div style="float:right;"><INPUT onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></div>
							</td>
						  </tr>
						</table></div>';						
					}else{
						$tm_start = floor(($zi['time']+$zi['time_start']-time())/6)/10;
						$tm_start = $this->rzv($tm_start);
						echo '<div style="float:right;"><INPUT onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></div>
						<b>Ожидаем начала '.$this->z2n[$zi['razdel']].' боя</b>';
						$sv0 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$zi['id'].'" LIMIT 100'));
						if($sv0[0] <= 1)
						{
							if(isset($_GET['cancelzvnow']))
							{
								echo ' <b><font color="red">Заявка на бой отменена</font></b>';
								$u->info['zv'] = 0;
								mysql_query('UPDATE `stats` SET `zv` = "0",`team` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							}else{
								echo ' <a href="main.php?zayvka&r='.$_GET['r'].'&cancelzvnow&rnd='.$code.'" title="Отменить заявку">Отменить</a>';
							}
						}
						unset($sv0);
						echo '<br>Ваш бой начнется через '.$tm_start.' мин.';
					}
				}elseif($r==1 || $r==2 || $r==3)
				{
					//новички,физические,договорные
					$zi = array(1=>'Если вы не достигли первого уровня, то для вас это единственный способ для проведения битв.',2=>'Здесь вы можете найти себе достойного противника для сражения.',3=>'Если вы предварительно с кем-то договорились о поединке, то лучше здесь подать заявку.');
					$dv = '';
					if($r==3)
					{
						$dv = '<br>Логин противника
									  <INPUT TYPE=text NAME=onlyfor maxlength=30 size=12>
									  <BR>
									  Бой на деньги, ставка
									  <INPUT TYPE=text NAME=stavkakredit size=6 maxlength=10>
									  <INPUT TYPE=submit name=open value="Подать заявку">
									  &nbsp;';
					}else{
						$dv = '<INPUT TYPE=submit name=open value="Подать заявку">';
						if($u->info['level']<4 || $u->info['admin']>0 || $u->info['id']==1011840)
						{
							$dv .= ' <INPUT onClick="location=\'main.php?zayvka=1&r='.$_GET['r'].'&bot='.$u->info['nextAct'].'\';" TYPE=button name=clone value="Тренировочный бой">';
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
							<TD align=right valign=top><INPUT onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'\';" TYPE=button name=tmp value="Обновить"></TD>
						  </TR>
						</TABLE>
						</FORM>';
				}elseif($r==4)
				{
					//групповые
					echo '<INPUT onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&new_group&rnd='.$code.'\';" TYPE=button name=tmp value="Подать новую заявку"  style="margin:3px;">
						  <INPUT onClick="location=\'main.php?zayvka&r='.$_GET['r'].'&rnd='.$code.'&sort=\'+document.all.value+\'\';" TYPE=button name=tmp value="Обновить"  style="float:right;">';
				}elseif($r==5)
				{
					//хаотичные
					echo 'Хаотичный бой - разновидность группового, где группы формируются автоматически. Бой не начнется, если собралось меньше 4-х человек.<br>
						  <a href="#" onclick="if(document.getElementById(\'haot\').style.display==\'\'){ document.getElementById(\'haot\').style.display=\'none\' }else{ document.getElementById(\'haot\').style.display=\'\'; } return false;">Подать заявку на хаотичный бой</a>
						  <form action="main.php?zayvka=1&r='.$_GET['r'].'&start_haot&rnd='.$code.'" method="post" style="margin:0px; padding:0px;">
						  <div style="display:none;" id="haot">
										  <br>
										  <FIELDSET>
											<LEGEND><strong>Подать заявку на хаотичный бой</strong> </LEGEND>
											Начало боя   через
											<SELECT name="startime2">
											<OPTION selected value="300">5 минут
											  <OPTION value="600">10 минут
											  <OPTION value="900">15 минут
											  <OPTION value="1200">20 минут
											  <OPTION value="1800">30 минут</OPTION>
											</SELECT>
											Таймаут
											<SELECT name="timeout">
											  <OPTION selected value="1">1 мин.
											  <OPTION value="2">2 мин.
											  <OPTION value="3">3 мин.
											  <OPTION value="4">4 мин.
											  <OPTION value="5">5 мин.</OPTION>
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
											<INPUT type="checkbox" name="mut_clever">
											Смертельные Раны   (увеличенный урон при повторных попаданиях)<BR>
											<INPUT type="checkbox" name="mut_hidden">
											Невидимый Бой (не видно   противников ни в заявке, ни в бою. +5% опыта)<BR>
											<INPUT value="Подать заявку" type="submit" name="open">
					 
									  <BR>
											Комментарий   к бою
											<INPUT maxLength="40" size="40" name="cmt">
										  </FIELDSET>
										</DIV>
						  </div></form>';
				}				
			}elseif($r==6)
			{
				//текущие
				echo 'Текущие бои...';
			}elseif($r==7)
			{
				//завершенные
				$btl = '';
				$dt = time();
				$slogin = $u->info['login'];
				$see = '<TABLE width=100% cellspacing=0 cellpadding=0><TR>
<TD valign=top>&nbsp;<A HREF="#">« Предыдущий день</A></TD>
<TD valign=top align=center><H3>Записи о завершенных боях за '.date('d.m.Y',$dt).'</H3></TD>
<TD  valign=top align=right><A HREF="#">Следующий день »</A>&nbsp;</TD>
</TR><TR><TD colspan=3 align=center>
<form method="POST" action="main.php?zayvka=1&r=7&rnd='.$code.'">
Показать только бои персонажа: <INPUT TYPE=text NAME=filter value="'.$slogin.'"> за <INPUT TYPE=text NAME=logs size=12 value="'.date('d.m.Y',$dt).'"> <INPUT TYPE=submit value="фильтр!">
</form>
</TD>
</TR></TABLE>';
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
		$w = mysql_num_rows(mysql_query('SELECT * FROM `zayvki` WHERE `time` > '.(time()-7200).' AND `city` = "'.$u->info['city'].'" AND `cancel` = "0" AND `start` = "0" AND `razdel` = "'.$id.'" AND (`min_lvl_1` <= '.$u->info['level'].' OR `min_lvl_2` <= '.$u->info['level'].') AND (`max_lvl_1` >= '.$u->info['level'].' OR `max_lvl_2` >= '.$u->info['level'].')'));
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
				$cl = mysql_query('SELECT * FROM `zayvki` WHERE `razdel` = "'.mysql_real_escape_string($r).'" AND `start` = "0" AND `cancel` = "0" AND `time` > "'.(time()-60*60*2).'" AND `city` = "'.$u->info['city'].'" ORDER BY `id` DESC');
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
						//Заявки хаотичного боя
						$tm = '';
						$tmStart = floor(($pl['time']+$pl['time_start']-time())/6)/10;
						$tmStart = $this->rzv($tmStart);
						
						$users = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`u`.`admin`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `u`.`id` = `st`.`id` WHERE `st`.`zv` = "'.$pl['id'].'"');
						while($s = mysql_fetch_array($users))
						{
							$tm .= $u->microLogin($uz['id'],1).', ';
						}
						
						$rad = '';
						
						$tm = rtrim($tm,', ');
						
						if(!isset($zi['id']))
						{
							$rad = '<input type="radio" name="btl_go" id="btl_go'.$pl['id'].'" value="'.$pl['id'].'"> ';
						}
						
						$n1tv = '';
						
						if($pl['invise']==1)
						{
							//невидимый бой
							$tm = '<i>невидимый</i>';
							$n1tv = ' <img src="http://img.xcombats.com/i/fighttypehidden0.gif" title="Невидимый">';
						}
						
						$zvb .= ''.$rad.'<font class="date">'.date('H:i',$pl['time']).'</font> ('.$tm.') ('.$pl['min_lvl_1'].'-'.$pl['max_lvl_1'].') <IMG SRC="http://img.xcombats.com/i/fighttype'.$pl['type'].'.gif" WIDTH="20" HEIGHT="20" title="Хаотичный бой">'.$n1tv.' <font class="dsc"><i>Бой начнется через <B>'.$tmStart.'</B> мин., таймаут '.($pl['timeout']/60).' мин. </font></i><BR>';						
						
					}elseif($pl['razdel']==4)
					{
						//Заявки группового боя
						$tm1 = '';
						$tm2 = '';
						$tmStart = floor(($pl['time']+$pl['time_start']-time())/6)/10;
						$tmStart = $this->rzv($tmStart);
						
						//Персонаж в заявке, подключаем ему противника
								//Ищем апонента для групповых
								$rndo = rand(0,1000);
								if($rndo < 250)
								{
									$apo = array();
									if(rand(0,100)<51)
									{
										$apo['team'] = 1;
									}else{
										$apo['team'] = 2;
									}
									if($this->testzvu($pl['id'],$apo['team'],0) < $pl['tm'.$apo['team'].'max'] && $this->testzvu($pl['id'],$apo['team'],1) < $pl['bot'.$apo['team']])
									{
										$spj = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE (`u`.`level` >= '.$pl['min_lvl_'.$apo['team']].' AND `u`.`level` <= '.$pl['max_lvl_'.$apo['team']].') AND `st`.`bot` = "2" AND `u`.`battle` = "0" AND `st`.`zv` = "0" LIMIT 1'));
										if(isset($spj['id']))
										{
											mysql_query('UPDATE `stats` SET `hpNow` = "3000",`mpNow` = "3000",`zv` = "'.$pl['id'].'",`team` = "'.$apo['team'].'" WHERE `id` = "'.$spj['id'].'" LIMIT 1');
										}
									}
								}
						
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
						$rad = '';
						if(!isset($zi['id']))
						{
							$rad = '<input type="radio" name="groupClick" id="groupClick" value="'.$pl['id'].'"> ';
						}
						$zvb .= ''.$rad.'<font class="date">'.date('H:i',$pl['time']).'</font> <B>'.$pl['tm1max'].' (</b>'.$pl['min_lvl_1'].'-'.$pl['max_lvl_1'].'<b>) на '.$pl['tm2max'].' (</b>'.$pl['min_lvl_2'].'-'.$pl['max_lvl_2'].'<b>)</B> ('.$tm1.') <font class="dsc"><i><span style=\'color:red; font-weight:bold;\'>против</span></font></i> ('.$tm2.') <IMG SRC="http://img.xcombats.com/i/fighttype'.$pl['type'].'.gif" WIDTH="20" HEIGHT="20" title="Групповой бой"> <font class="dsc"><i>Бой начнется через <B>'.$tmStart.'</B> мин., таймаут '.($pl['timeout']/60).' мин. </font></i><BR>';
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
							if(!isset($uze['id']) || $u->info['zv']==$pl['id'])
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
								$zvb .= '<input name="btl_go" '.$d1.' type="radio" value="'.$pl['id'].'" /> <font class="date">'.date('H:i',$pl['time']).'</font> '.$u->microLogin($uz['id'],1).' '.$enm.'  тип боя: <img src="http://img.xcombats.com/i/fighttype'.($pl['type']).'.gif"> (таймаут '.round($pl['timeout']/60).' мин.'.$dp1.')<br>';
								if($uz['banned']>0){	$zvb .= '</span>';	  }
							}
						}
					}
					$i++;
				}
				if($i==0)
				{
					//заявок нет
				}else{
					if(!isset($zi['id']))
					{
						echo '<div style="float:left;"><form method="post" style="margin:0px;padding:0px;" action="main.php?zayvka=1&r='.$r.'&rnd='.$code.'"><input name="" type="submit" value="Принять вызов" /><br>'.$zvb.'<input style="margin-top:1px;" type="submit" value="Принять вызов" /></form></div>';
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
			if($u->info['battle']==0)
			{
				$z = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id`="'.mysql_real_escape_string(intval($id)).'" AND `city` = "'.$u->info['city'].'" AND `start` = "0" AND `cancel` = "0" AND `time` > "'.(time()-60*60*2).'" LIMIT 1'));
				if(isset($z['id']))
				{
					if($z['razdel']>=1 && $z['razdel']<=3)
					{
						//новички, физы, договорные
						$uz1 = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$z['id'].'" AND `st`.`team`="1" LIMIT 1'));
						if(isset($uz1['id']))
						{
							$uz2 = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`align`,`u`.`clan`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`online`,`u`.`level`,`u`.`battle`,`u`.`money`,`st`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv`="'.$z['id'].'" AND `st`.`team`="2" LIMIT 1'));
							if($u->info['hpNow']<$u->stats['hpAll']/100*30 && ($z['razdel']>=1 || $z['razdel']<=3))
							{
								$this->error = 'Вы еще слишком ослаблены чтобы начать новый бой';
								$az = 0;
							}elseif($uz1['clan']==$u->info['clan'] && $u->info['clan']!=0)
							{
								$this->error = 'Вы не можете сражаться против сокланов';
							}elseif($z['money']>0 && $u->info['level']<4)
							{
								$this->error = 'Бои на деньги проводятся с 4-го уровня';
							}elseif($z['withUser']!='' && $filter->mystr($u->info['login'])!=$filter->mystr($z['withUser']) && $z['razdel']==3)
							{
								$this->error = 'Вы не можете принять эту заявку';
							}elseif($z['money']>0 && $z['money']>$u->info['money'])
							{
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
									$this->error = 'Ожидаем подтверждения боя от '.$ca.' '.$uz1['login'].' ['.$uz1['level'].']<a href="info/'.$uz1['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" title="Инф. о '.$uz1['login'].'"></a>';
									$sa = '';
									if($u->info['sex']==2)
									{
										$sa = 'а';
									}
									$text = ' [login:'.$u->info['login'].'] принял'.$sa.' вашу заявку на бой.[reflesh_main_zv_priem:'.$u->info['id'].']';
									mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('".$uz1['city']."','".$uz1['room']."','','".$uz1['login']."','".$text."','".time()."','10','0')");
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
						}else{
							$this->error = 'Что-то здесь не так';	
						}
						
						if($tm!=0)
						{
							$t1 = $tm;
							$t2 = 1;
							$tmmax = 0;
							if($tm==1){ $t2 = 2; }
							$cl111 = mysql_query('SELECT `u`.`clan`,`st`.`team`,`st`.`id`,`st`.`zv` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`zv` = "'.$z['id'].'" LIMIT 200');
							$cln = 0;
							while($pc111 = mysql_fetch_array($cl111))
							{
								if($pc111['clan']==$u->info['clan'] && $u->info['clan']!=0 && $pc111['team']==$t2)
								{
									$cln++;
								}
								if($pc111['team']==$t1)
								{
									$tmmax++;
								}
							}
							if($cln>0)
							{
								$this->error = 'Вы не можете сражаться против сокланов';
							}elseif($z['tm'.$t1.'max']>$tmmax)
							{
								if($z['min_lvl_'.$t1]>$u->info['level'] || $z['max_lvl_'.$t1]<$u->info['level'])
								{
									$this->error = 'Вы не подходите по уровню, за эту команду могут зайти персонажи '.$z['min_lvl_'.$t1].' - '.$z['max_lvl_'.$t1].' уровня';
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
								$this->error = 'Группа уже набрана ('.($z['tm'.$t1.'max']-$tmmax).')';
							}
						}
					}elseif($z['razdel']==5 && $u->info['level']>1)
					{
						//хаотичные
						if($z['min_lvl_1']>$u->info['level'] || $z['max_lvl_1']<$u->info['level'])
						{
							$this->error = 'Вы не подходите по уровню, за эту команду могут зайти персонажи '.$z['min_lvl_1'].' - '.$z['max_lvl_1'].' уровня';
						}elseif($u->stats['hpNow']<ceil($u->stats['hpMax']/100*30))
						{
							$this->error = 'Вы слишком ослаблены, восстановитесь';
						}else{
							$t1 = 1;
							
							/* считаем баланс */
							if($z['tm1']>$z['tm2'])
							{
								$t1 = 2;
							}elseif($z['tm1']<$z['tm2'])
							{
								$t1 = 1;
							}else{
								$t1 = rand(1,2);
							}
							
							if($z['invise']==1)
							{
								$nxtID = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$z['id'].'"'));
								$nxtID = $nxtID[0];
								$u->info['login2'] = 'Боец ('.($nxtID+1).')';
							}else{
								$u->info['login2'] = '';
							}
							
							$blnc = 100*$u->info['level']+10*$u->info['upLevel']+$u->info['exp']+$u->stats['reting'];
					
							$z['tm'.$t1] += $blnc;
													
							$upd = mysql_query('UPDATE `stats` SET `zv` = "'.$z['id'].'",`team` = "'.$t1.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if(!$upd)
							{
								$this->error = 'Ошибка приема заявки...';
							}else{
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