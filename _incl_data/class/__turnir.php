<?
if(!defined('GAME'))
{
	die();
}

class turnir {
	
	public $info,$user,$name = array(
						0 => 'Выжить любой ценой',
						1 => 'Каждый сам за себя',
						2 => 'Захват ключа'				
					);
	
	public function start() {
		global $c,$u;
		$this->info = mysql_fetch_array(mysql_query('SELECT * FROM `turnirs` WHERE `id` = "'.$u->info['inTurnirnew'].'" LIMIT 1'));
		$this->user = mysql_fetch_array(mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$u->info['inTurnirnew'].'" AND `bot` = "'.$u->info['id'].'" LIMIT 1'));
	}
	
	public function startTurnir() {	
		global $c,$u;	
		$row = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `win` = "0" AND `lose` = "0" AND `nich` = "0"'));
		if($row[0] > 0 && $this->info['status'] != 3) {
			mysql_query('UPDATE `turnirs` SET `status` = "3" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			//Создание поединка
			mysql_query('INSERT INTO `battle` (`city`,`time_start`,`timeout`,`type`,`turnir`) VALUES ("'.$u->info['city'].'","'.time().'","60","1","'.$this->info['id'].'")');
			$uri = mysql_insert_id();
			//Закидываем персонажей в поединок
			mysql_query('UPDATE `users` SET `battle` = "'.$uri.'" WHERE `inUser` = "0" AND `inTurnirnew` = "'.$this->info['id'].'"');
			//Обозначаем завершение турнира при выходе
			die('Перейтиде в раздел "поединки"...');
		}else{
			if($this->info['status'] == 3) {
				$this->finishTurnir();
			}
		}
	}
	
	public function finishTurnir() {
		global $c,$u;
		$this->info = mysql_fetch_array(mysql_query('SELECT * FROM `turnirs` WHERE `id` = "'.$u->info['inTurnirnew'].'" LIMIT 1'));
		//mysql_query('UPDATE `users` SET `inUser` = 0, `inTurnirnew` = 0 WHERE `inTurnirnew` = '.$this->info['id'].' AND `inUser` > 0 LIMIT '.$this->info['users_in']);
		if($this->info['status'] == 3) {
			$win = '';
			$lose = '';
			$sp = mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'" ORDER BY `points` DESC');
			while($pl = mysql_fetch_array($sp)) {	
				mysql_query('DELETE FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'"');
				$inf = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				$bot = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['bot'].'" LIMIT 1'));
				if(isset($inf['id'],$bot['id'])) {
					
					mysql_query('UPDATE `dailybonus` SET `turnirgo` = `turnirgo` + 1 WHERE `uid` = "'.$inf['id'].'" LIMIT 1');
					
					//выдаем призы и т.д	
					mysql_query('DELETE FROM `users` WHERE `id` = "'.$bot['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `stats` WHERE `id` = "'.$bot['id'].'" LIMIT 1');	
					mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$bot['id'].'" LIMIT 1000');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$bot['id'].'" LIMIT 1000');										
				}
				
				if($pl['team'] == $this->info['winner'] && $this->info['winner'] != 0) {
					
					$inf['add_expn'] = 3000;
					
					$inf['add_expp'] = array(
						0,
						1,
						5,
						10,
						15,
						25,
						35,
						70,
						100,
						150,
						200,
						300,
						500,
						700,
						1000
					);
					
					//$inf['add_expn'] = floor($inf['add_expn']/100*$inf['add_expp'][$inf['level']]);
					//$inf['add_expn'] = floor($inf['add_expn']/100*$inf['add_expp'][7]);
					//получаем опыт (с 0 по 12 лвл)					
					$inf['add_expn'] = array( 10 , 30 , 55 , 62 , 92 , 180 , 350 , 1350 , 4500 , 7000 , 21000 , 30000 , 35000 );
					$inf['add_expn'] = $inf['add_expn'][$inf['level']];
					
					mysql_query('UPDATE `users` SET `win` = `win` + 1,`win_t` = `win_t` + 1 WHERE `id` = "'.$inf['id'].'" LIMIT 1');
					
					mysql_query('UPDATE `dailybonus` SET `turnirwin` = `turnirwin` + 1 WHERE `uid` = "'.$inf['id'].'" LIMIT 1');
					
					mysql_query('UPDATE `stats` SET `exp` = `exp` + '.$inf['add_expn'].' WHERE `id` = "'.$inf['id'].'" LIMIT 1');
					$win .= '<b>'.$inf['login'].'</b>, ';
					
					$r = 'Турнир завершен. Вы являетесь победителем турнира, получено опыта: <b>'.$inf['add_expn'].'</b>.';
					
					/*if( $this->info['users_in'] >= 10 ) {
						$r .= ' В турнире собралось 10 или более человек: <b>Чек на 1 екр.</b> добавлен к Вам в инвентарь.';
						$u->addItem(4393,$inf['id'],'');
					}*/
					
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','".$inf['room']."','','".$inf['login']."','".$r."','".time()."','6','0')");
				}elseif($pl['team'] != $this->info['winner'] && $this->info['winner'] != 0) {
					mysql_query('UPDATE `users` SET `lose` = `lose` + 1,`lose_t` = `lose_t` + 1 WHERE `id` = "'.$inf['id'].'" LIMIT 1');
					
					$lose .= '<b>'.$inf['login'].'</b>, ';
					
				}else{
					mysql_query('UPDATE `users` SET `nich` = `nich` + 1 WHERE `id` = "'.$inf['id'].'" LIMIT 1');
				}
				mysql_query('DELETE FROM `users_turnirs` WHERE `uid` = "'.$inf['id'].'" LIMIT 1');
			}
			mysql_query('UPDATE `users` SET `inUser` = "0",`inTurnirnew` = "0" WHERE `inTurnirnew` = "'.$this->info['id'].'" LIMIT '.$this->info['users_in']);
			mysql_query('UPDATE `turnirs` SET `chat` = 4 , `winner` = -1,`users_in` = 0,`status` = 0,`winner` = -1,`step` = 0,`time` = "'.(time()+$this->info['time2']).'",`count` = `count` + 1 WHERE `id` = '.$this->info['id'].' LIMIT 1');
			
			if($win != '') {
				$win = rtrim($win,', ');
				$lose = rtrim($lose,', ');
				$win = 'Победители турнира: '.$win.'. Проигравшая сторона: '.$lose.'. Следующий турнир начнется через '.$u->timeOut($this->info['time2']).' ('.date('d.m.Y H:i',(time()+$this->info['time2'])).').';
			}else{
				$win = 'Победители турнира отсутствует. Следующий турнир начнется через '.$u->timeOut($this->info['time2']).' ('.date('d.m.Y H:i',(time()+$this->info['time2'])).').';
			}
			$r = '<font color=black><b>Турнир завершен.</b></font> '.$win;				
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','','','','".$r."','".time()."','6','0')");
		}
	}
	
	public function locationSee() {
		global $c,$u;
		
		$r = '';
		
		$tm1 = '';
		
		$tm2 = '';
		
		$noitm = array(
			869		=> 1,
			1246	=> 1,
			155		=> 1,
			1245	=> 1,
			678		=> 1
		);
		
		if($this->info['step'] != 3 && $this->info['step'] != 0) {
			//получение комплекта
			if(isset($_GET['gocomplect']) && $this->user['points'] < 2) {
				$aso = explode(',',$this->user['items']);				
				$ast = explode('-',$_GET['gocomplect']);
				$asg = array();
				$asj = array();
				$asgp = array();
				
				$i = 0;
				while($i < count($aso)) {
					if($aso[$i] > 0) {
						$asg[$aso[$i]] = true;
					}
					$i++;
				}
				
				$i = 0; $j = 0; $noitm = 0;
				$addi = 1;
				while($i < count($ast)) {
					if($ast[$i] > 0) {
						if($asg[$ast[$i]] != true) {
							$noitm++;
						}
						$itm = mysql_fetch_array(mysql_query('SELECT `id`,`inSlot`,`price1` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($ast[$i]).'" LIMIT 1'));
						if(isset($itm['id'])) {
							$itm2 = mysql_fetch_array(mysql_query('SELECT `iid`,`price_1` FROM `items_shop` WHERE `item_id` = "'.mysql_real_escape_string($ast[$i]).'" AND `kolvo` > 0 LIMIT 1'));
							if($itm2['price_1'] > $itm['price1']) {
								$itm['price1'] = $itm2['price_1'];
							}
							if($itm['inSlot'] == 3) {
								$asg[$itm['inSlot']][count($asg[$itm['inSlot']])] = $itm['id'];
								$asgp[$itm['inSlot']][count($asgp[$itm['inSlot']])] = $itm['price1'];
								$j++;
							}elseif($itm['inSlot'] == 10) {
								$asg[$itm['inSlot']][count($asg[$itm['inSlot']])] = $itm['id'];
								$asgp[$itm['inSlot']][count($asgp[$itm['inSlot']])] = $itm['price1'];
								$j++;
							}else{
								$asg[$itm['inSlot']] = $itm['id'];
								$asp[$itm['inSlot']] = $itm['price1'];
								$j++;
							}
						}
					}
					$i++;
				}
				
				if($noitm > 0) {
					echo 'Использование багов карается законом!';
					$addi = 0;
				}elseif(count($asg[3]) > 2) {
					echo 'Вы выбрали слишком много предметов, выберите только два оружия и один щит';
					$addi = 0;
				}elseif(count($asg[10]) > 3) {
					echo 'Вы выбрали слишком много предметов, выберите только три кольца';
					$addi = 0;
				}elseif($j > 16) {
					echo 'Вы выбрали слишком много предметов';
					$addi = 0;
				}elseif($j < 1) {
					echo 'Выберите хотя бы один предмет';
					$addi = 0;
				}
				
				//$addi = 0;
				
				if($addi == 1) {
					
					$i = 0;
					while($i <= 17) {
						if($i == 10) {
							if($asg[$i][0] > 0) {
								$u->addItem($asg[$i][0],$u->info['id']);
								$this->user['points'] += 1+round($asgp[$i][0]);
							}
							if($asg[$i][1] > 0) {
								$u->addItem($asg[$i][1],$u->info['id']);
								$this->user['points'] += 1+round($asgp[$i][1]);
							}
							if($asg[$i][2] > 0) {
								$u->addItem($asg[$i][2],$u->info['id']);
								$this->user['points'] += 1+round($asgp[$i][2]);
							}
						}elseif($i == 3) {
							if($asg[$i][0] > 0) {
								$u->addItem($asg[$i][0],$u->info['id']);
								$this->user['points'] += 1+round($asgp[$i][0]);
							}
							if($asg[$i][1] > 0) {
								$u->addItem($asg[$i][1],$u->info['id']);
								$this->user['points'] += 1+round($asgp[$i][1]);
							}
						}elseif($asg[$i] > 0) {
							$u->addItem($asg[$i],$u->info['id']);
							$this->user['points'] += 1+round($asgp[$i]);
						}
						$i++;
					}
					
					mysql_query('UPDATE `users_turnirs` SET `points` = "'.$this->user['points'].'",`items` = "0" WHERE `bot` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `ability` = "100",`skills` = "10" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `level` = "12" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `turnirs` SET `step` = "0" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					
					$this->info['step'] = 0;
					$this->info['items'] = '0';
				}
			}
		}
		
		if($this->info['step'] == 3) {
			$this->finishTurnir();
		}elseif($this->info['step'] == 0) {
			//распределяем команды
			$po = array(0,0);
			$sp = mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'" AND `points` > 3 ORDER BY `points` DESC LIMIT '.$this->info['users_in']);
			$tmr = rand(1,2);
			if($tmr == 1) {
				$tmr = array(2,1);
			}else{
				$tmr = array(1,2);
			}
			while($pl = mysql_fetch_array($sp)) {
				$inf = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				$bot = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['bot'].'" LIMIT 1'));
				if(isset($inf['id'],$bot['id'])) {
					if($po[1] == $po[2]) {
						$tm = rand(1,2);
					}elseif($po[1] > $po[2]) {
						$tm = 2;
					}else{
						$tm = 1;
					}
					//$tm = $tmr[$tm];
					$bot['team'] = $tm;
					$po[$bot['team']] += $pl['points'];
					mysql_query('UPDATE `stats` SET `team` = "'.$bot['team'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
					mysql_query('UPDATE `users_turnirs` SET `team` = "'.$bot['team'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
			}
			mysql_query('UPDATE `turnirs` SET `step` = "1" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
		}
	
		$sp = mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'" LIMIT '.$this->info['users_in']);
		$po = array(0,0);
		while($pl = mysql_fetch_array($sp)) {
			$inf = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
			$bot = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `u`.`id` = `st`.`id` WHERE `u`.`id` = "'.$pl['bot'].'" LIMIT 1'));
			if(isset($inf['id'],$bot['id'])) {
				$po[$bot['team']] += $pl['points'];
				//${'tm'.$bot['team']} .= '<b>'.$bot['login'].'</b> ['.$bot['level'].']<br>';
				${'tm'.$bot['team']} .= $u->microLogin($bot,2).'<br>';
			}
		}
		$r .= '<style>/* цвета команд */
.CSSteam0	{ font-weight: bold; cursor:pointer; }
.CSSteam1	{ font-weight: bold; color: #6666CC; cursor:pointer; }
.CSSteam2	{ font-weight: bold; color: #B06A00; cursor:pointer; }
.CSSteam3 	{ font-weight: bold; color: #269088; cursor:pointer; }
.CSSteam4 	{ font-weight: bold; color: #A0AF20; cursor:pointer; }
.CSSteam5 	{ font-weight: bold; color: #0F79D3; cursor:pointer; }
.CSSteam6 	{ font-weight: bold; color: #D85E23; cursor:pointer; }
.CSSteam7 	{ font-weight: bold; color: #5C832F; cursor:pointer; }
.CSSteam8 	{ font-weight: bold; color: #842B61; cursor:pointer; }
.CSSteam9 	{ font-weight: bold; color: navy; cursor:pointer; }
.CSSvs 		{ font-weight: bold; }</style>';
		$r 	.= '<h3>&laquo;'.$this->name[$this->info['type']].'&raquo;</h3><br>Начало турнира через '.$u->timeOut($this->info['time'] - time()).'! ';
		
		if(isset($_GET['hpregenNowTurnir'])) {
			if($u->stats['hpNow'] < $u->stats['hpAll'] || $u->stats['mpNow'] < $u->stats['mpAll']) {
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpAll'].'",`mpNow` = "'.$u->stats['mpAll'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}
		}
		
		if($this->user['points'] < 3) {
			//Еще не получили обмундирование
			if($this->user['points'] < 2) {
				$r .= '<INPUT class=\'btn_grey\' onClick="selectItmSave()" TYPE=button name=tmp value="Получить обмундирование">';
			}else{
				$r .= ' <INPUT class=\'btn_grey\' onClick="location=\'main.php\';" TYPE=button name=tmp value="Я готов';
				if($u->info['sex'] == 1) {
					$r .= 'а';
				}
				$r .= '!">';
			}
		}else{
			$r .= '<small><b>Вы участвуете в турнире!</b></small>';
			$r .= ' &nbsp; <INPUT class=\'btn_grey\' onClick="location.href=\'main.php?hpregenNowTurnir=1\'" TYPE=button name=tmp value="Восстановить HP и MP">';
		}
		
		$r	.= '<div style="float:right"><INPUT onClick="location=\'main.php\';" TYPE=button name=tmp value="Обновить"></div>';
		if($this->user['points'] < 3) {
			if($this->user['items'] != '0') {
				$r .= '<div align="left" style="height:1px; width:100%; margin:10px 0 10px 0; border-top:1px solid #999999;"></div>';
				if($this->user['items'] == '') {
					//Выдаем предметы для выбора
					$ai = '';
					
					$sp = mysql_query('SELECT `a`.*,`b`.* FROM `items_shop` AS `a` LEFT JOIN `items_main` AS `b` ON (`a`.`item_id` = `b`.`id`) WHERE `a`.`sid` = 1 AND
					(`a`.`r` != 5 AND `a`.`r` != 9 AND `a`.`r` <= 18 AND `a`.`kolvo` > 0 AND `cantBuy` = 0 AND `a`.`level` < 9 AND `b`.`level` < 9) AND
					`b`.`class` != 6');
					while($pl = mysql_fetch_array($sp)) {
						if(!isset($noitm[$pl['item_id']])) {
							$aso[$pl['inslot']][count($aso[$pl['inslot']])] = $pl;
						}
					}
	
					$j = 1;
					$com = array();
					while($j <= 5) {
						$i = 0;
						while($i <= 17) {
							if($i == 3) {
								//
								$com[$i] = $aso[$i][rand(0,count($aso[$i])-1)];
							}elseif($i == 14) {
								//правая рука
								$com[$i] = $aso[$i][rand(0,count($aso[$i])-1)];
							}else{
								//обмундирование
								$com[$i] = $aso[$i][rand(0,count($aso[$i])-1)];
								if($i == 10) {
									$ai .= $com[$i]['id'].',';
									$com[$i] = $aso[$i][rand(0,count($aso[$i])-1)];
									$ai .= $com[$i]['id'].',';
									//$com[$i] = $aso[$i][rand(0,count($aso[$i])-1)];
									//$ai .= $com[$i]['id'].',';
								}
							}
							if($com[$i]['id'] > 0 && $i != 10) {
								$ai .= $com[$i]['id'].',';
							}
							$i++;
						}
						$j++;
					}
					unset($com);
					
					$ai .= '0';
					$this->user['items'] = $ai;
					mysql_query('UPDATE `users_turnirs` SET `items` = "'.$ai.'" WHERE `id` = "'.$this->user['id'].'" LIMIT 1');
				}
				
				//Выводим предметы чтобы надеть их
				$ai = explode(',',$this->user['items']);
				$i = 0; $ia = array();
				while($i < count($ai)) {
					if($ai[$i] > 0) {
						$pli = mysql_fetch_array(mysql_query('SELECT `id`,`inSlot`,`name`,`type`,`img`,`level` FROM `items_main` WHERE `id` = "'.$ai[$i].'" LIMIT 1'));
						$ia[$pli['inSlot']][count($ia[$pli['inSlot']])] = $pli;
						unset($pli);
					}
					$i++;
				}
				unset($ai);
				
				$r .= '<b>Выберите предметы для турнира:</b><br>';
				?>
				<style>
				.its0 {
					margin:2px;
					cursor:pointer;
					filter:DXImageTransform.Microsoft.BasicImage(grayscale=1);
					-ms-filter:DXImageTransform.Microsoft.BasicImage(grayscale=1);
					-webkit-filter: grayscale(100%);
				}
				.its1 {
					background-color:#ee9898;
					margin:1px;
					border:1px solid #b16060;
				}
				</style>
				<script>
				var set = [
									
				];
				set[3] = [0,0,0];
				set[10] = [0,0,0,0];
				function selectItmAdd(x,y,id,s) {
					if(s != 10 && s != 3) {
						if(set[s] != undefined && set[s] != 0) {
							$('#it_'+set[s][1]+'_'+set[s][2]).attr('class','its0');
							set[s] = 0;
						}
						set[s] = [id,x,y];				
						$('#it_'+x+'_'+y).attr('class','its1');
					}else if(s == 10) {
						if(set[s][0] > 2) {
							$('#it_'+set[s][1][1]+'_'+set[s][1][2]).attr('class','its0');
							$('#it_'+set[s][2][1]+'_'+set[s][2][2]).attr('class','its0');
							$('#it_'+set[s][3][1]+'_'+set[s][3][2]).attr('class','its0');
							set[s] = [0,0,0,0];
						}
						
						if(set[s][1] == 0) {
							set[s][1] = [id,x,y];	
						}else if(set[s][2] == 0) {
							set[s][2] = [id,x,y];	
						}else if(set[s][3] == 0) {
							set[s][3] = [id,x,y];	
						}
						set[s][0]++;
						$('#it_'+x+'_'+y).attr('class','its1');
					}else if(s == 3) {
						if(set[s][0] > 1) {
							$('#it_'+set[s][1][1]+'_'+set[s][1][2]).attr('class','its0');
							$('#it_'+set[s][2][1]+'_'+set[s][2][2]).attr('class','its0');
							set[s] = [0,0,0];
						}
						
						if(set[s][1] == 0) {
							set[s][1] = [id,x,y];	
						}else if(set[s][2] == 0) {
							set[s][2] = [id,x,y];	
						}
						set[s][0]++;
						$('#it_'+x+'_'+y).attr('class','its1');
					}
				}
				function selectItmSave() {
					var i = 0;
					var r = '';
					while(i <= 17) {
						if(set[i] != undefined) {
							if(i == 10) {
								if(set[i][1][0] != undefined) {
									r += set[i][1][0]+'-';
								}
								if(set[i][2][0] != undefined) {
									r += set[i][2][0]+'-';
								}
								if(set[i][3][0] != undefined) {
									r += set[i][3][0]+'-';
								}
							}else if(i == 3) {
								if(set[i][1][0] != undefined) {
									r += set[i][1][0]+'-';
								}
								if(set[i][2][0] != undefined) {
									r += set[i][2][0]+'-';
								}
							}else{
								if(set[i][0] != undefined) {
									r += set[i][0]+'-';
								}
							}
						}
						i++;
					}
					location = "main.php?gocomplect="+r;
				}
				</script>
				<?
				$i = 0;
				while($i <= 17) {
					if(count($ia[$i]) > 0) {
						$j = 0;
						while($j < count($ia[$i])) {
							$r .= '<img id="it_'.$i.'_'.$j.'" onclick="selectItmAdd('.$i.','.$j.','.$ia[$i][$j]['id'].','.$ia[$i][$j]['inSlot'].');" class="its0" title="'.$ia[$i][$j]['name'].'" src="http://img.xcombats.com/i/items/'.$ia[$i][$j]['img'].'">';
							$j++;
						}
						$r .= '<br>';
					}
					$i++;
				}
				
			}
			
		}
		$r .= '<div align="left" style="height:1px; width:100%; margin:10px 0 10px 0; border-top:1px solid #999999;"></div>';
		//$r .= '<b class="CSSteam1">Команда №1</b>: '.rtrim($tm1,', ');		
		//$r .= '<br><b class="CSSteam2">Команда №2</b>: '.rtrim($tm2,', ');
		
		$r .= '<table style="border:1px solid #99cccc" width="700" bgcolor="#bbdddd" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td width="350" align="center" bgcolor="#99cccc"><b class="CSSteam1">Команда 1</b></td>
    <td align="center" bgcolor="#99cccc"><b class="CSSteam2">Команда 2</b></td>
  </tr>
  <tr>
    <td align="center" style="border-right:1px solid #99cccc">'.rtrim($tm1,', ').'</td>
    <td align="center">'.rtrim($tm2,', ').'</td>
  </tr>
  </table>';
		
		
		if( ($this->info['time'] - time() < 0) && $this->info['step'] == 1) {
			//начинаем турнир
			$this->startTurnir();
		}
		
		echo $r;
	}
	
}
$tur = new turnir;
$tur->start();
?>