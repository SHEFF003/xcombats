<?
if(!defined('GAME'))
{
	die();
}

class dungeon
{
	public $bs,$info,$see,$error,$gs = 1,$information, $map = array(
				0 => array() //карта
			)	,$id_dng,$cord = array('x' => 0),$sg = array(1 => array(1=>1,2=>2,3=>3,4=>4),2 => array(1=>2,2=>3,3=>4,4=>1),3 => array(1=>3,2=>4,3=>1,4=>2),4 => array(1=>4,2=>1,3=>2,4=>3));
	public function start()
	{
		global $u,$c,$code;
		$this->info = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_now` WHERE `id` = "'.$u->info['dnow'].'" LIMIT 1'));
		$this->id_dng = $this->info['id2'];
		$this->gs = $u->info['s'];	
		if($this->gs<1 || $this->gs>4)
		{
			$this->gs = 1;
		}
		
		
		if($this->info['bsid']>0)
		{
			$this->bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `city` = "'.$u->info['city'].'" AND `id` = "'.$this->info['bsid'].'" AND `time_start` = "'.$this->info['time_start'].'" LIMIT 1'));
			if(isset($this->bs['id']))
			{
				//Если БС закончена
				/*if($this->bs['users']-$this->bs['users_finish'] < 2)
				{
					$u->bsfinish($this->bs,false,NULL);
				}*/
			}
		}
		
		
		
		if(isset($_GET['itm_luck']) && $this->info['bsid'] == 0) {
			$this->itm_luck((int)$_GET['itm_luck']);
		}elseif(isset($_GET['itm_unluck']) && $this->info['bsid'] == 0) {
			$this->itm_unluck((int)$_GET['itm_unluck']);
		}elseif(isset($_GET['atack'])){
			$this->atack((int)$_GET['atack']);
		}elseif(isset($_GET['take'])){
			$this->takeinv((int)$_GET['take']);
		}elseif(isset($_GET['take_obj'])){
			$this->takeit((int)$_GET['take_obj']);
		}elseif(isset($_GET['look'])){
			if((int)$_GET['look']==1){
				if($this->gs==1)
				{
					$this->gs = 2;
				}elseif($this->gs==2)
				{
					$this->gs = 3;
				}elseif($this->gs==3)
				{
					$this->gs = 4;
				}elseif($this->gs==4)
				{
					$this->gs = 1;
				}
			}elseif((int)$_GET['look']==2)
			{
				if($this->gs==1)
				{
					$this->gs = 4;
				}elseif($this->gs==2)
				{
					$this->gs = 1;
				}elseif($this->gs==3)
				{
					$this->gs = 2;
				}elseif($this->gs==4)
				{
					$this->gs = 3;
				}
			}
			mysql_query('UPDATE `stats` SET `s` = "'.((int)$this->gs).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['s'] = $this->gs;
		}elseif(isset($_GET['go']))
		{
			//перемещение
			$this->testGo((int)$_GET['go']);
		}
		
		/* генерируем вид персонажа (только карта)
			$this->gs = 1; //смотрим прямо
						2; //смотрим лево
						3; //смотрим вниз
						4; //смотрим право
						( ( ( `y` >= '.$u->info['y'].' && `y` <= '.($u->info['y']+4).' ) && ( `x` >= '.($u->info['x']-1).' && `x` <= '.($u->info['x']+1).' ) ) || ( (`x` = '.($u->info['x']+2).' || `x` = '.($u->info['x']-2).') && ( `y` = '.($u->info['y']+3).' || `y` = '.($u->info['y']+4).' ) ) )
		*/
		
		$whr = array(
					1 => ' ((`x` <= '.($u->info['x']+2).' && `x` >= '.($u->info['x']-2).') && (`y` >= '.$u->info['y'].' && `y` <= '.($u->info['y']+4).')) ', //прямо 
					3 => ' ((`x` <= '.($u->info['x']+2).' && `x` >= '.($u->info['x']-2).') && (`y` <= '.$u->info['y'].' && `y` >= '.($u->info['y']-4).')) ', //вниз
					2 => ' ((`x` <= '.$u->info['x'].' && `x` >= '.($u->info['x']-4).') && (`y` <= '.($u->info['y']+2).' && `y` >= '.($u->info['y']-2).')) ', //лево				
					4 => ' ((`x` >= '.$u->info['x'].' && `x` <= '.($u->info['x']+4).') && (`y` <= '.($u->info['y']+2).' && `y` >= '.($u->info['y']-2).')) ' //право
				);
		
		$i = 1;
		$sp = mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$this->id_dng.'" AND '.$whr[$this->gs].' ORDER BY `y` ASC , `x` ASC LIMIT 25');
		while($pl = mysql_fetch_array($sp))
		{
			$this->map[0][$pl['y'].'_'.$pl['x']] = $pl;
			$i++;
		}
		$this->map['good'] = $i; //целых клеток
		$this->map[1] = $this->genMatix();
		$this->lookDungeon();
	}
	
	public function pickitem($obj,$itm,$for, $data = '',$dn_delete = false) {
		global $u;
		$itm = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$itm.'" LIMIT 1'));
		if( isset($itm['id']) ) {
			$tou = 0; //какому юзеру предназначено
			/* выделяем случайного юзера из команды */
			$itmnm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$itmz[0].'" LIMIT 1'));
			$itmnm = $itmnm['name'];
			$asex = '';
			if( $u->info['sex'] == 1 ) {
				$asex = 'а';
			}
							
			if($for > 0 ) {
				$tou = $for;
				$rtxt = '<b>'.$u->info['login'].'</b> обнаружил'.$asex.' предмет &quot;'.$itm['name'].'&quot; в &quot;'.$obj['name'].'&quot;';
			}else{
				$rtxt = '<b>'.$u->info['login'].'</b> обнаружил'.$asex.' предмет &quot;'.$itm['name'].'&quot; в &quot;'.$obj['name'].'&quot;, и вы решили разыграть его';
			}
			if($dn_delete == true) { $dn_delete = 1; } else { $dn_delete = 0; }
			mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1','1')");								
			$ins = mysql_query('INSERT INTO `dungeon_items` (`dn_delete`,`data`,`dn`,`user`,`item_id`,`time`,`x`,`y`) VALUES (
								"'.$dn_delete.'",
								"'.mysql_real_escape_string($data).'",
								"'.$u->info['dnow'].'",
								"'.$tou.'",
								"'.$itm['id'].'",
								"'.time().'",
								"'.$u->info['x'].'",
								"'.$u->info['y'].'")');
			return $ins;
		}
	}
	
	public function usersDng($laba = false)
	{
		global $u,$c;
		$r = '';
		$stt = array();
		if( $laba == false ) {
			$sp = mysql_query('SELECT `u`.`id`,`st`.`id` FROM `stats` AS `u` LEFT JOIN `users` AS `st` ON (`st`.`id` = `u`.`id`) WHERE `u`.`dnow` = "'.$this->info['id'].'" LIMIT 10');
		}else{
			$sp = mysql_query('SELECT `u`.`id` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`st`.`id` = `u`.`id`) WHERE `u`.`room` = 370 AND `st`.`dnow` = "'.$laba.'" AND `st`.`bot` = 0 LIMIT 6');
		}
		while($pl = mysql_fetch_array($sp))
		{
			$stt = $u->getStats($pl['id'],0);
			if($stt['mpAll']>0)
			{
				$pm = $stt['mpNow']/$stt['mpAll']*100;
			}
			$ph = $stt['hpNow']/$stt['hpAll']*100;
			$r .= '<table border="0" cellspacing="0" cellpadding="0" height="20">
<tr><td valign="middle"> &nbsp; <font color="#990000">'.$u->microLogin($pl['id'],1).'</font> &nbsp; </td>
<td valign="middle" width="120" ';
			if( $stt['mpAll'] < 1 ) {
				$r .= 'style="padding-top:12px"';
			}
$r .= '>
<div style="position:relative;"><div id="vhp'.($pl['id']+1000000000000).'" title="Уровень жизни" align="left" class="seehp" style="position:absolute; top:-10px; width:120px; height:10px; z-index:12;"> '.floor($stt['hpNow']).'/'.$stt['hpAll'].'</div>
<div title="Уровень жизни" class="hpborder" style="position:absolute; top:-10px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div class="hp_3 senohp" style="height:9px; width:'.floor(120/100*$ph).'px; position:absolute; top:-10px; z-index:11;" id="lhp'.($pl['id']+1000000000000).'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div title="Уровень жизни" class="hp_none" style="position:absolute; top:-10px; width:120px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div>
';
if($stt['mpAll']>0)
{
	$r .= '<div id="vmp'.($pl['id']+1000000000000).'" title="Уровень маны" align="left" class="seemp" style="position:absolute; top:0px; width:120px; height:10px; z-index:12;"> '.floor($stt['mpNow']).'/'.$stt['mpAll'].'</div>
<div title="Уровень маны" class="hpborder" style="position:absolute; top:0px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div class="hp_mp senohp" style="height:9px; position:absolute; top:0px; width:'.floor(120/100*$pm).'px; z-index:11;" id="lmp'.($pl['id']+1000000000000).'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
<div title="Уровень маны" class="hp_none" style="position:absolute; top:0px; width:120px; height:10px; z-index:10;"></div>';
}
$r .= '</div></td><td>';
if( $this->info['uid'] == $pl['id'] ) {
	$r .= '<img src="http://img.xcombats.com/i/lead1.gif" title="Лидер группы" >';
}
$r .= '</td></tr></table><br>';
$r .= '<script>top.startHpRegen("main",'.($pl['id']+1000000000000).','.(0+$stt['hpNow']).','.(0+$stt['hpAll']).','.(0+$stt['mpNow']).','.(0+$stt['mpAll']).',0,0,0,0,1);</script>';
		}
		unset($stt,$ph,$pm);
		return $r;
	}
		
	public function atack($id) {
		global $u,$c,$code;
		$bot = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `id2` = "'.$id.'" AND `for_dn` = "0" AND `dn` = "'.$this->info['id'].'" AND `delete` = "0" LIMIT 1'));
		if(isset($bot['id2'])){
			if( ($u->info['x'] != $bot['x'] || $bot['y'] != $u->info['y']) && $this->testLike($u->info['x'],$u->info['y'],$bot['x'],$bot['y']) == 1 ){
				//Создаем подеинок
				$tbtl = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `dn_id` = "'.$this->info['id'].'" AND `team_win` = "-1" AND `x` = "'.$bot['x'].'" AND `y` = "'.$bot['y'].'" LIMIT 1'));
				if(isset($tbtl['id'])) {
					//вступаем в поединок
					mysql_query('UPDATE `users` SET `battle` = "'.$tbtl['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					/*#$this->error = 'Нападаем ... '; // <script>location="main.php?rnd='.$code.'";</script>*/
					header('location: main.php');
				} else {
					$btl_id = 0;
					//$expB = -77.77;
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
	
					if( $this->info['bsid'] > 0 ) {
						$btl['inTurnir'] = $this->info['bsid'];
					}
	
					$ins = mysql_query('INSERT INTO `battle` (`inTurnir`,`dungeon`,`dn_id`,`x`,`y`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
														"'.$btl['inTurnir'].'",
														"'.$this->info['id2'].'",
														"'.$this->info['id'].'",
														"'.$bot['x'].'",
														"'.$bot['y'].'",
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
					
							if( $this->info['bsid'] > 0 ) {
								
								$bott = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$bot['id_bot'].'" LIMIT 1'));
										
								$me_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" AND `login` = "'.$u->info['login'].'" LIMIT 1'));

								//Заносим в лог БС
								if( $u->info['sex'] == 0 ) {
									$text = '{u1} вмешался в поединок напав на {u2} <a target=_blank href=/logs.php?log='.$tbtl['id'].' >»»</a>';
								}else{
									$text = '{u1} вмешалася в поединок напав на {u2} <a target=_blank href=/logs.php?log='.$tbtl['id'].' >»»</a>';
								}								
								if( isset($usr_real['id'])) {
									$usrreal = '';
									if( $bott['align'] > 0 ) {
										$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$bott['align'].'.gif width=12 height=15 >';
									}
									if( $bott['clan'] > 0 ) {
										$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$bott['clan'].'.gif width=24 height=15 >';
									}
									$usrreal .= '<b>'.$bott['login'].'</b>['.$bott['level'].']<a target=_blank href=http://xcombats.com/info/'.$bott['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
								}else{
									$mereal = '<i>Невидимка</i>[??]';
								}
								if( isset($me_real['id']) ) {
									$mereal = '';
									if( $me_real['align'] > 0 ) {
										$mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >';
									}
									if( $me_real['clan'] > 0 ) {
										$mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >';
									}
									$mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
								}else{
									$mereal = '<i>Невидимка</i>[??]';
								}
								$text = str_replace('{u1}',$mereal,$text);
								$text = str_replace('{u2}',$usrreal,$text);
								//Добавляем в лог БС
								mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
									"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$this->bs['id'].'", "'.$this->bs['count'].'", "'.$this->bs['city'].'",
									"'.round($this->bs['money']*0.85,2).'","'.$i.'"
								)');
								//
							}
					
					if($btl_id>0) {
						//Добавляем ботов
						$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "0" AND `dn` = "'.$this->info['id'].'" AND `x` = "'.$bot['x'].'" AND `y` = "'.$bot['y'].'" AND `delete` = "0" LIMIT 50');
						$j = 0; $logins_bot = array();
						while($pl = mysql_fetch_array($sp)){
							$jui = 1;
							mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "'.$btl_id.'" WHERE `id2` = "'.$pl['id2'].'" LIMIT 1');
							while($jui<=$pl['colvo']){
								$k = $u->addNewbot($pl['id_bot'],NULL,NULL,$logins_bot);
								$logins_bot = $k['logins_bot'];
								if($k!=false){
									$upd = mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
									if($upd){
										$upd = mysql_query('UPDATE `stats` SET `x`="'.$bot['x'].'",`y`="'.$bot['y'].'",`team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
										if($upd){
											$j++;
										}
									}
								}
								$jui++;
							}
						}
						unset($logins_bot);
						if($j>0){
							mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							/*#$this->error = 'Нападаем ...';// <script>location="main.php?rnd='.$code.'";</script>*/
							//
							header('location: main.php'); 
						}else{
							$this->error = 'Не удалось напасть, ошибка обьекта нападения ...';	
						}
					}else{
						$this->error = 'Не удалось создать поединок ...';	
					}
				}
			}else{
				$this->error = 'Не удалось напасть ...';	
			}
		} else {
			if(isset($this->bs['id']) || $this->info['id2'] == 15) {
				$bot = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
				if(($u->info['x']!=$bot['x'] || $bot['y']!=$u->info['y']) && $this->testLike($u->info['x'],$u->info['y'],$bot['x'],$bot['y'])==1){
					//Создаем подеинок
					$tbtl = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `dn_id` = "'.$this->info['id'].'" AND `team_win` = "-1" AND `x` = "'.$bot['x'].'" AND `y` = "'.$bot['y'].'" LIMIT 1'));
					//die('Нападения временно запрещены. ['.$tbtl['id'].'] 5-10 мин.');
					if(isset($tbtl['id'])){
						//вступаем в поединок
						$lstm = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle`="'.mysql_real_escape_string($tbtl['id']).'" ORDER BY  `st`.`team` DESC LIMIT 1'));	
						mysql_query('UPDATE `users` SET `battle` = "'.$tbtl['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `team` = "'.($lstm['team']+1).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						//
							if( $this->info['bsid'] > 0 ) {
								
								$me_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" AND `login` = "'.$u->info['login'].'" LIMIT 1'));
								$usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$bot['id'].'" AND `login` = "'.$bot['login'].'" LIMIT 1'));

								//Заносим в лог БС
								if( $u->info['sex'] == 0 ) {
									$text = '{u1} вмешался в поединок напав на {u2} <a target=_blank href=/logs.php?log='.$tbtl['id'].' >»»</a>';
								}else{
									$text = '{u1} вмешалася в поединок напав на {u2} <a target=_blank href=/logs.php?log='.$tbtl['id'].' >»»</a>';
								}								
								if( isset($usr_real['id'])) {
									$usrreal = '';
									if( $usr_real['align'] > 0 ) {
										$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >';
									}
									if( $usr_real['clan'] > 0 ) {
										$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >';
									}
									$usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
								}else{
									$mereal = '<i>Невидимка</i>[??]';
								}
								if( isset($me_real['id']) ) {
									$mereal = '';
									if( $me_real['align'] > 0 ) {
										$mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >';
									}
									if( $me_real['clan'] > 0 ) {
										$mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >';
									}
									$mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
								}else{
									$mereal = '<i>Невидимка</i>[??]';
								}
								$text = str_replace('{u1}',$mereal,$text);
								$text = str_replace('{u2}',$usrreal,$text);
								//Добавляем в лог БС
								mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
									"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$this->bs['id'].'", "'.$this->bs['count'].'", "'.$this->bs['city'].'",
									"'.round($this->bs['money']*0.85,2).'","'.$i.'"
								)');
								//
							}
						//
						$this->error = 'Нападаем ... <script>location="main.php?rnd='.$code.'";</script>';
					}else{
						$btl_id = 0;
						//$expB = -77.77;
						if($this->info['id2'] == 1) {
							$expB = 200;	
						}
						$btl = array(
							'players'=>'',
							'timeout'=>40,
							'type'=>0,
							'invis'=>0,
							'noinc'=>0,
							'travmChance'=>0,
							'typeBattle'=>0,
							'addExp'=>$expB,
							'money'=>0,
							'inTurnir' => 0
						);
						
						if( $this->info['bsid'] > 0 ) {
							$btl['inTurnir'] = $this->info['bsid'];
						}
		
						$ins = mysql_query('INSERT INTO `battle` (`inTurnir`,`dungeon`,`dn_id`,`x`,`y`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
															"'.$btl['inTurnir'].'",
															"'.$this->info['id2'].'",
															"'.$this->info['id'].'",
															"'.$bot['x'].'",
															"'.$bot['y'].'",
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
						
						if($btl_id>0)
						{
							//Добавляем ботов 
							mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							
							mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
							
							if($u->stats['hpNow'] < 1) {
								$u->stats['hpNow'] = 1;
								mysql_query('UPDATE `stats` SET `hpNow` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								mysql_query('UPDATE `users` SET `lose` = `lose` + 1 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							}
							
							if($bot['hpNow'] < 1) {
								$bot['hpNow'] = 1;
								mysql_query('UPDATE `stats` SET `hpNow` = "1" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
								mysql_query('UPDATE `users` SET `lose` = `lose` + 1 WHERE `id` = "'.$bot['id'].'" LIMIT 1');
							}
							
							if( $this->info['bsid'] > 0 ) {
								
								$me_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" AND `login` = "'.$u->info['login'].'" LIMIT 1'));
								$usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$bot['id'].'" AND `login` = "'.$bot['login'].'" LIMIT 1'));

								//Заносим в лог БС
								if( $u->info['sex'] == 0 ) {
									$text = '{u1} напал на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
								}else{
									$text = '{u1} напала на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
								}								
								if( isset($usr_real['id'])) {
									$usrreal = '';
									if( $usr_real['align'] > 0 ) {
										$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >';
									}
									if( $usr_real['clan'] > 0 ) {
										$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >';
									}
									$usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
								}else{
									$mereal = '<i>Невидимка</i>[??]';
								}
								if( isset($me_real['id']) ) {
									$mereal = '';
									if( $me_real['align'] > 0 ) {
										$mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >';
									}
									if( $me_real['clan'] > 0 ) {
										$mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >';
									}
									$mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
								}else{
									$mereal = '<i>Невидимка</i>[??]';
								}
								$text = str_replace('{u1}',$mereal,$text);
								$text = str_replace('{u2}',$usrreal,$text);
								//Добавляем в лог БС
								mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
									"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$this->bs['id'].'", "'.$this->bs['count'].'", "'.$this->bs['city'].'",
									"'.round($this->bs['money']*0.85,2).'","'.$i.'"
								)');
								//
							}
							
							$this->error = 'Нападаем ... <script>location="main.php?rnd='.$code.'";</script>';
						}else{
							$this->error = 'Не удалось создать поединок ...';	
						}
					}
				}else{
					$this->error = 'Не удалось напасть ...';	
				}	
			}else{
				$this->error = 'Не удалось напасть, слишком далеко (id'.$id.')	...';	
			}
		}
	}
		
	public function testDie() {
		global $u,$c,$code;
		$dies = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `vars` = "die" LIMIT 1'));
		$dies = $dies[0];
		
		
		if( $u->stats['hpNow'] < 1 || $dies > 2 ) {
			if( $dies < 2 ) {
			    mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
				    "'.$u->info['dnow'].'","'.$u->info['id'].'","'.$u->info['x'].'","'.$u->info['y'].'","'.time().'","die",""
			    )');
			    //21:50 Ярополк трагически погиб и находится в комнате "Этаж 2 - Водосток"
				$dnow = mysql_fetch_array(mysql_query('SELECT id2 FROM `dungeon_now` WHERE  `id` = "'.$u->info['dnow'].'" LIMIT 1'));
				$room = mysql_fetch_array(mysql_query('SELECT name FROM `dungeon_map_info` WHERE `x` = "'.$u->info['res_x'].'" AND  `y` = "'.$u->info['res_y'].'" AND `id_dng` = "'.$dnow['id2'].'" LIMIT 1'));

			    if( $u->info['sex'] == 0 ) {
				    $this->sys_chat('<b>'.$u->info['login'].'</b> трагически погиб и находится в комнате &quot;'.$room['name'].'&quot;');
			    }else{
				    $this->sys_chat('<b>'.$u->info['login'].'</b> трагически погибла и находится в комнате &quot;'.$room['name'].'&quot;');
			    }
			    mysql_query('UPDATE `stats` SET `hpNow` = "1",`x` = "'.$u->info['res_x'].'",`y` = "'.$u->info['res_y'].'",`s` = "'.$u->info['res_s'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			    header('location: main.php');
		    }else{
			    mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
				    "'.$u->info['dnow'].'","'.$u->info['id'].'","'.$u->info['x'].'","'.$u->info['y'].'","'.time().'","die",""
			    )');
			    //21:50 Ярополк трагически погиб и находится в комнате "Этаж 2 - Водосток"
			    if( $u->info['sex'] == 0 ) {
				    $this->sys_chat('<b>'.$u->info['login'].'</b> трагически погиб без права на воскрешение');
			    }else{
				    $this->sys_chat('<b>'.$u->info['login'].'</b> трагически погибла без права на воскрешение');
			    }
			    $_GET['exitd'] = true;
			}
		}
	}
	
	//Предметы для БС
	public $itbs = array(
                0 => 1,1 => 131,2 => 130,3 => 6,4 => 7,5 => 129,6 => 128,7 => 127,8 => 126,9 => 125,10 => 124,11 => 123,12 => 122,13 => 121,14 => 120,15 => 119,16 => 118,17 => 117,18 => 116,19 => 115,20 => 114,21 => 113,22 => 112,23 => 111,24 => 110,25 => 109,26 => 108,27 => 107,28 => 106,29 => 105,30 => 104,31 => 103,32 => 102,33 => 101,34 => 100,35 => 99,36 => 98,37 => 97,38 => 96,39 => 95,40 => 94,41 => 93,42 => 92,43 => 91,44 => 90,45 => 89,46 => 88,47 => 87,48 => 84,49 => 85,50 => 86,51 => 73,52 => 83,55 => 82,56 => 81,57 => 132,58 => 133,59 => 134,60 => 135,61 => 136,62 => 137,63 => 138,64 => 139,65 => 140,66 => 141,67 => 142,68 => 143,69 => 144,70 => 145,71 => 146,72 => 147,73 => 148,74 => 149,75 => 150,76 => 151,77 => 152,78 => 153,79 => 154,80 => 155,81 => 156,82 => 157,83 => 158,84 => 159,85 => 160,86 => 161,87 => 162,88 => 163,89 => 164,90 => 165,91 => 166,92 => 167,93 => 168,94 => 169,95 => 170,96 => 171,97 => 172,98 => 173,99 => 174,100 => 175,101 => 176,102 => 177,103 => 178,104 => 179,105 => 180,106 => 181,107 => 182,108 => 183,109 => 184,110 => 185,111 => 186,112 => 187,113 => 188,114 => 189,115 => 190,116 => 191,117 => 192,118 => 193,119 => 194,120 => 195,121 => 196,122 => 197,123 => 198,124 => 199,125 => 200,126 => 201,127 => 202,128 => 203,129 => 204,130 => 205,131 => 206,132 => 207,133 => 208,134 => 209,135 => 210,136 => 211,137 => 212,138 => 213,139 => 214,140 => 215,141 => 216,142 => 217,143 => 218,144 => 219,145 => 220,146 => 221,147 => 222,148 => 223,149 => 224,150 => 225,151 => 226,152 => 227,153 => 228,154 => 229,155 => 230,156 => 231,157 => 232,158 => 233,159 => 234,160 => 235,161 => 236,162 => 237,163 => 238,164 => 239,165 => 240,166 => 241,167 => 242,168 => 243,169 => 244,170 => 245,171 => 246,172 => 247,173 => 248,174 => 249,175 => 250,176 => 251,177 => 252,178 => 253,179 => 254,180 => 255,181 => 256,182 => 257,183 => 258,184 => 259,185 => 260,186 => 261,187 => 262,188 => 263,189 => 264,190 => 265,191 => 266,192 => 267,193 => 268,194 => 269,195 => 270,196 => 271,197 => 272,198 => 273,199 => 274,200 => 275,201 => 276,202 => 277,203 => 278,204 => 279,205 => 280,206 => 281,207 => 282,208 => 283,209 => 284,210 => 285,211 => 286,212 => 287,213 => 288,214 => 289,215 => 290,216 => 291,217 => 292,218 => 293,219 => 294,220 => 295,221 => 296,222 => 297,223 => 298,224 => 299,225 => 300,226 => 301,227 => 302,228 => 304,229 => 305,230 => 306,231 => 307,232 => 308,233 => 309,234 => 310,235 => 311,236 => 312,237 => 313,238 => 314,239 => 315,240 => 316,241 => 317,242 => 318,243 => 319,244 => 320,245 => 321,246 => 322,247 => 323,248 => 324,249 => 325,250 => 326,251 => 327,252 => 328,253 => 329,254 => 330,255 => 331,256 => 332,257 => 333,258 => 334,259 => 335,260 => 336,261 => 337,262 => 338,263 => 339,264 => 340,265 => 341,266 => 342,267 => 343,268 => 344,269 => 345,270 => 346,271 => 347,272 => 348,273 => 349,274 => 350,275 => 351,276 => 352,277 => 353,278 => 354,279 => 355,280 => 356,281 => 357,282 => 358,283 => 359,284 => 360,285 => 361,286 => 362,287 => 363,288 => 364,289 => 365,290 => 366,291 => 367,292 => 368,293 => 369,294 => 370,295 => 371,296 => 372,297 => 373,298 => 374,299 => 375,300 => 376,301 => 377,302 => 378,303 => 379,304 => 380,305 => 381,306 => 382,307 => 383,308 => 384,309 => 385,310 => 386,311 => 387,312 => 388,313 => 389,314 => 390,315 => 391,316 => 392,317 => 393,318 => 394,319 => 395,320 => 396,321 => 397,322 => 398,323 => 399,324 => 400,325 => 401,326 => 402,327 => 403,328 => 404,329 => 405,330 => 406,331 => 407,332 => 408,333 => 409,334 => 410,335 => 411,336 => 412,337 => 413,338 => 414,339 => 415,340 => 416,341 => 417,342 => 418,343 => 419,344 => 420,345 => 421,346 => 422,347 => 423,348 => 424,349 => 425,350 => 426,351 => 427,352 => 428,353 => 429,354 => 430,355 => 431,356 => 432,357 => 433,358 => 434,359 => 435,360 => 436,361 => 437,362 => 438,363 => 439,364 => 440,365 => 441,366 => 442,367 => 443,368 => 444,369 => 445,370 => 446,371 => 447,372 => 448,373 => 449,374 => 450,375 => 451,376 => 452,377 => 453,378 => 454,379 => 455,380 => 456,381 => 457,382 => 458,383 => 459,384 => 460,385 => 461,386 => 462,387 => 463,388 => 464,389 => 465,390 => 466,391 => 467,392 => 468,393 => 469,394 => 470,395 => 471,396 => 472,397 => 473,398 => 474,399 => 475,400 => 476,401 => 477,402 => 478,403 => 479,404 => 480,405 => 481,406 => 482,407 => 483,408 => 484,409 => 485,410 => 486,411 => 487,412 => 488,413 => 489,414 => 490,415 => 491,416 => 492,417 => 493,418 => 494,419 => 495,420 => 496,421 => 497,422 => 498,423 => 499,424 => 500,425 => 501,426 => 502,427 => 503,428 => 504,429 => 505,430 => 506,431 => 507,432 => 508,433 => 509,434 => 510,435 => 511,436 => 512,437 => 513,438 => 514,439 => 515,440 => 516,441 => 517,442 => 518,443 => 519,444 => 520,445 => 521,446 => 522,447 => 523,448 => 524,449 => 525,450 => 526,451 => 527,452 => 528,453 => 529,454 => 530,455 => 531,456 => 532,457 => 533,458 => 534,459 => 535,460 => 536,461 => 537,462 => 538,463 => 539,464 => 540,465 => 541,466 => 542,467 => 543,468 => 544,469 => 545,470 => 546,471 => 547,472 => 548,473 => 549,474 => 550,475 => 1015,632            );
	
	
	public function sys_chat($rtxt) {
		global $u;
		mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1','1')");					
	}
	
	public function takeit($id) {
		global $u,$c,$code,$magic;
		$obj = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_obj` WHERE `id` = "'.$id.'" AND `for_dn` = "0" AND `dn` = "'.$this->info['id'].'" LIMIT 1'));
		if(isset($obj['id'])) {
			$tbot = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `x` = "'.$obj['x'].'" AND `y` = "'.$obj['y'].'" AND `dn` = "'.$this->info['id'].'" AND `for_dn` = "0" AND `delete` = "0" LIMIT 1'));
			

			$i = 0;
			# Создаем МАССИВ { $act_sl['save_pos'] = "save_pos" }или { $act_sl['port'] = "10:20" }
			$act_sl = array();
			$act_sm = explode('|',$obj['action']);
			while( $i < count($act_sm) ) {
				$s = explode(':',$act_sm[$i]);
				if(isset($s[1]) && $s[1] !='' ){
					$act_sl[$s[0]] = $s[1];
				} else {
					$act_sl[$s[0]] = $s[0];
				}
				$i++;
			}

			if( isset($tbot['id2']) ) {
				$this->error = 'Не удалось, что-то или кто-то мешает ...';
			} elseif($this->testLike($u->info['x'],$u->info['y'],$obj['x'],$obj['y'])!=1) {
				$this->error = 'Не удалось, слишком далеко ...';
			} else {
				$a = explode('|',$obj['action']);
				$r = '';
				$i = 0;
				while( $i < count($a) ) {
					$s = explode(':',$a[$i]);
					
					if( $s[0] == 'kill_bot' ) {
						//Требуется убить ботов
						$t = explode(',',$s[1]);
						$tr_gd = 0;
						
						//Проверяем кого нужно убить и убили-ли
						$j = 1; $jn = 0;
						while($j < count($t)) {
							$itm = explode('.',$t[$j]);
							//[0] - x , [1] - y
							$bot_itm = mysql_fetch_array(mysql_query('SELECT `u`.`id2`,`st`.`login` FROM `dungeon_bots` AS `u` LEFT JOIN `test_bot` AS `st` ON (`u`.`id_bot` = `st`.`id`) WHERE `u`.`dn` = "'.$u->info['dnow'].'" AND `u`.`x` = "'.$itm[0].'" AND `u`.`y` = "'.$itm[1].'" AND `u`.`delete` = "0" LIMIT 1'));
							if(isset($bot_itm['id2'])){
								$jn++;
							}
							$j++;
						}
						
						if($jn == 0) {
							$tr_gd = 1;
						}
						
						unset($itm,$bot_itm,$jn);	
						if($tr_gd == 0) {					
							if($t[0]=='0'){
								$r .= 'Не удалось, что-то или кто-то мешает ...';
							}else{
								$r .= $t[0];
							}
							$i = count($a);
						}
					}elseif($s[0]=='kill_bot_d') {
						//Требуется убить ботов (все боты нападают , если что-то не так )
						$t = explode(',',$s[1]);
						$tr_gd = 0;
						
						//Проверяем кого нужно убить и убили-ли
						$j = 1; $jn = 0;
						$tuz = mysql_fetch_array(mysql_query('SELECT `x`,`y`,`id`,`hpNow` FROM `stats` WHERE `dnow` = "'.$this->info['id'].'" AND ( (`x` = '.($pl['x']+1).' AND `y` = '.($pl['y']).') OR (`x` = '.($pl['x']-1).' AND `y` = '.($pl['y']).') OR (`x` = '.($pl['x']).' AND `y` = '.($pl['y']+1).') OR (`x` = '.($pl['x']).' AND `y` = '.($pl['y']-1).') ) LIMIT 1'));
						while($j < count($t)) {
							$itm = explode('.',$t[$j]);
							//[0] - x , [1] - y
							$bot_itm_sp = mysql_query('SELECT `u`.*,`st`.* FROM `dungeon_bots` AS `u` LEFT JOIN `test_bot` AS `st` ON (`u`.`id_bot` = `st`.`id`) WHERE `u`.`dn` = "'.$u->info['dnow'].'" AND `u`.`x` = "'.$itm[0].'" AND `u`.`y` = "'.$itm[1].'" AND `u`.`delete` = "0" LIMIT 25');
							while( $bot_itm = mysql_fetch_array($bot_itm_sp) ) {
								$jn++;
								$this->botAtack($bot_itm,$u->info,1);
							} 
							$j++;
						}
						
						if($jn == 0) {
							$tr_gd = 1;
						}
						
						unset($itm,$bot_itm,$jn);	
						if($tr_gd == 0) {					
							if($t[0]=='0'){
								$r .= 'Не удалось, что-то или кто-то мешает ...';
							}else{
								$r .= $t[0];
							}
							$r .= ' Монстры начали атаковать вас!';
							$i = count($a);
						}
					}elseif($s[0]=='ditm') {
						//требует предмет для действия
						$j = 0;
						$t = explode(',',$s[1]);
						$tr_gd = 1;
						while($j<count($t)) {
							$itm = explode('=',$t[$j]);
							$uitm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$itm[0].'" LIMIT 1'));
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id` = "'.$itm[0].'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 100) AND `inShop` = 0 AND `inTransfer` = 0 AND `inOdet` = 0 LIMIT '.$itm[1]);
							$r .= 'Предмет &quot;<b>'.$uitm['name'].'</b>&quot; (x'.$itm[1].') был утрачен...<br>';
							$j++;
						}
					}elseif($s[0]=='tritm') {
						//требует предмет для действия
						$j = 0;
						$t = explode(',',$s[1]);
						$j = 0;
						$tr_gd = 1;
						while($j<count($t)) {
							$itm = explode('=',$t[$j]);
							$uitm = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE `item_id` = "'.$itm[0].'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 100) AND `inShop` = 0 AND `inTransfer` = 0 AND `inOdet` = 0 LIMIT '.$itm[1]));
							$uitm = $uitm[0];
							if($uitm < $itm[1]){
								$tr_gd = 0;
								$uitm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$itm[0].'" LIMIT 1'));
								$r .= 'Требуется предмет &quot;<b>'.$uitm['name'].'</b>&quot; (x'.$itm[1].')<br>';
							}
							$j++;
						}
						if(rand(0,10000)>$itm[2]*100) {
							$tr_gd = 0;
							$r .= 'Странно, но ничего не произошло...<br>';
						}
						if($tr_gd == 1) {
							//все отлично
						}else{
							$i = count($a);
						}
                    } elseif($s[0] == 'repl_ptp') {
                      include('dnaction/_dungeon_replace.php');
                      die();
                      //header('Location: ../../modules_data/location/_dungeon_replace.php');
					}elseif($s[0]=='add_eff') {
						//Кастуем эффект
						$t = explode(',',$s[1]);
						$j = 0;
						while($j<count($t)) {
							$itm = explode('=',$t[$j]);
							$ch = $u->testAction('`vars` = "add_eff_'.$this->info['id'].'_'.$obj['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT '.(1+(int)$itm[2]).'',2); //кол-во прошлых попыток
							$ch = $ch[0];
							$ch2 = $u->testAction(' `vars` = "add_eff_'.$this->info['id'].'_'.$obj['id'].'" LIMIT '.(1+(int)$itm[4]).'',2); //кол-во прошлых попыток (все юзеры)
							$ch2 = $ch2[0];
							if(($ch2 < $itm[4] || $itm[4]==0) && $ch < $itm[2]) {
								if($itm[1]*100 >= rand(0,10000)) {
									//удачно
									$eff_d = mysql_fetch_array(mysql_query('SELECT `id2`,`mname` FROM `eff_main` WHERE `id2` = "'.$itm[0].'" LIMIT 1'));
									if(isset($eff_d['id2'])) {										
										//добавляем эффект
										$us = $magic->add_eff($u->info['id'],$itm[0],1);
										if($us[0]==1) {
											$r .= '<div>На Вас наложили заклятие &quot;'.$eff_d['mname'].'&quot;.</div>';
										}else{
											$r .= '<div>Что-то пошло не так... Ощущается чье-то присутствие...</div>';
										}
									}else{
										$r .= '<div>Что-то пошло не так... Слышен чей-то вой...</div>';
									}
									unset($eff_d,$us);
								}else{
									//не удачно
									$r .= '<div>Не удалось...</div>';
								}
								$u->addAction(time(),'add_eff_'.$this->info['id'].'_'.$obj['id'],$u->info['city']);
							}else{
								//уже нельзя юзать
								$r .= '<div>Что-то пошло не так...</div>';
							}
							
							unset($ch,$ch2);
							$j++;	
						}
					}elseif($s[0]=='mfast') {
						//Добавляем баф //Ловушки и бафы
						$j = 0;
						$t = explode(',',$s[1]);
						while($j<count($t))
						{
							$itm = explode('=',$t[$j]);
							$ch = $u->testAction('`vars` = "bafit_'.$this->info['id'].'_'.$obj['id'].'" LIMIT '.(1+(int)$itm[2]).'',2); //кол-во прошлых попыток
							$ch = $ch[0];
							if($ch<$itm[3])
							{
								if($itm[2]*1000>=rand(1,100000))
								{
									if($itm[0] == 'hpNow') {
										$mm = explode('r',$itm[1]);
										if($mm[1]!=0) {
											$itm[1] = rand($mm[0],$mm[1]);
										}
										if($itm[1]<0) {
											$r .= '<div>Вы попали в ловушку... Здоровье: <b>'.$itm[1].' HP</b></div>';
										}elseif($itm[1]>0){
											$r .= '<div>Вы прикоснулись к магии... Здоровье: <b>+'.$itm[1].' HP</b></div>';
										}
										$u->info['hpNow'] += $itm[1];
										if($u->info['hpNow']<1) {
											$r .= '<div>Вы перемещены в точку возрождения...</div>';
											mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->info['hpNow'].'",`x` = "'.$u->info['res_x'].'",`y` = "'.$u->info['res_y'].'",`s` = "'.$u->info['res_s'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
										}else{
											mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->info['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');	
										}
									}
								}
								$u->addAction(time(),'bafit_'.$this->info['id'].'_'.$obj['id'],$u->info['city']);
							}
							//записываем попытку							
							$j++;
						}
					}elseif($s[0]=='save_pos') {
						if( isset($act_sl['port']) ) {
							$itm = explode('=',$act_sl['port']);
							$obj['x'] = $itm[0];
							$obj['y'] = $itm[1];
						}
						#$r .= 'Позиция сохранена. Теперь после смерти вы оживете здесь.';
						mysql_query('UPDATE `stats` SET `res_x` = "'.$obj['x'].'",`res_y` = "'.$obj['y'].'",`res_s` = "'.$u->info['s'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					}elseif($s[0]=='look_text') {
						$itm = explode('=',$s[1]);
						$r .= $itm[rand(0,count($itm)-1)];
					}elseif($s[0]=='save_pos_xy'){
						$itm = explode('=',$s[1]);
						$u->info['res_x'] = $itm[0];
						$u->info['res_y'] = $itm[1];
						$upd = mysql_query('UPDATE `stats` SET `res_x` = "'.$u->info['x'].'",`res_y` = "'.$u->info['y'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if($upd){
							$r .= 'Вы куда-то переместились... на этот раз удачно...<br>';
						}else{
							$r .= 'Что-то здесь не так ...';	
						}
					}elseif($s[0]=='port'){
						//телепортирует пользователя
						$itm = explode('=',$s[1]);
						$u->info['x'] = $itm[0];
						$u->info['y'] = $itm[1];
						$upd = mysql_query('UPDATE `stats` SET						
						`x` = "'.$u->info['x'].'",`y` = "'.$u->info['y'].'"				
						WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if($upd)
						{
							$r .= 'Вы куда-то переместились... на этот раз удачно...<br>';
						}else{
							$r .= 'Что-то здесь не так ...';	
						}
                    } elseif($s[0] == 'save_port') {
                      $itm = explode('=', $s[1]);
					  $u->info['res_x'] = $itm[0];
					  $u->info['res_y'] = $itm[1];
                      $upd = mysql_query('UPDATE `stats` SET `res_x` = "'.$u->info['res_x'].'", `res_y` = "'.$u->info['res_y'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					} elseif($s[0]=='itm')
					{
						//Добавляем предмет
						$j = 0;
						$t = explode(',',$s[1]);
						while($j<count($t))
						{
							$itm = explode('=',$t[$j]);
							$ch = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "takeit_'.$this->info['id'].'_'.$obj['id'].'" LIMIT '.(1+(int)$itm[2]).'',2); //кол-во прошлых попыток
							$ch = $ch[0];
							if($ch>=$itm[2])
							{
								//закончились попытки
								$r = 'Не удалось найти что-либо еще ... <br>';
							}else{
								if($itm[1]*1000>=rand(1,100000))
								{
									
									//Случайный предмет (Башня смерти)
									if($itm[0] == 'random1') {
										$itm[0] = $this->itbs[rand(0,count($this->itbs))];
									}
									
									//удачная попытка
									$it = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id`="'.((int)$itm[0]).'" LIMIT 1'));
									if(isset($it['id']))
									{
										$r .= 'Вы обнаружили предмет &quot;<b>'.$it['name'].'</b>&quot;.<br>';
										$this->addItem(array('uid'=>$u->info['id'],'iid'=>$it['id'],'time'=>time(),'x'=>$u->info['x'],'y'=>$u->info['y'],'bid'=>0,'del'=>(int)$itm[4]));
									}
								}else{
									//неудачная попытка
									$r .= 'В этот раз не удалось найти что-либо еще ... <br>';
								}
								$u->addAction(time(),'takeit_'.$this->info['id'].'_'.$obj['id'],$u->info['city']);
							}
							//записываем попытку							
							$j++;
						}
					}elseif($s[0]=='itm1')
					{
							//Добавляем предмет , только 1 предмет из всех и все юзеры могут тоже
							$nj = 0;
							$t = explode(',',$s[1]);
							$j = rand(0,count($t));
							if($nj == 0) {
								$itm = explode('=',$t[$j]);
								$ch = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "takeit_'.$this->info['id'].'_'.$obj['id'].'" LIMIT '.(1+(int)$itm[2]).'',2); //кол-во прошлых попыток
								$ch = $ch[0];
								if($ch>=$itm[2])
								{
									//закончились попытки
									$r .= 'Не удалось найти что-либо еще ... <br>';
								}else{
									if($itm[1]*1000>=rand(1,100000))
									{
										
										//Случайный предмет (Башня смерти)
										if($itm[0] == 'random1') {
											$itm[0] = $this->itbs[rand(0,count($this->itbs))];
										}
										
										//удачная попытка
										$it = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id`="'.((int)$itm[0]).'" LIMIT 1'));
										if(isset($it['id']))
										{
											$r .= 'Вы обнаружили предмет &quot;<b>'.$it['name'].'</b>&quot;.<br>';
											$u->addAction(time(),'takeit_'.$this->info['id'].'_'.$obj['id'],$u->info['city']);
											$this->addItem(array('uid'=>$u->info['id'],'iid'=>$it['id'],'time'=>time(),'x'=>$u->info['x'],'y'=>$u->info['y'],'bid'=>0,'del'=>(int)$itm[4]));
											$nj++;
										}
									}else{
										//неудачная попытка
										$u->addAction(time(),'takeit_'.$this->info['id'].'_'.$obj['id'],$u->info['city']);
										$r .= 'В этот раз не удалось найти что-либо еще ... <br>';
									}
								}
							}
					}elseif($s[0]=='itm2')
					{
							//Добавляем предмет , только 1 предмет из всех и только 1 юзер может поднять
							$nj = 0;
							$t = explode(',',$s[1]);
							$j = rand(0,count($t)-1);
							if($nj == 0) {
								$itm = explode('=',$t[$j]);
								$ch = $u->testAction('`vars` = "takeit_'.$this->info['id'].'_'.$obj['id'].'" LIMIT '.(1+(int)$itm[2]).'',2); //кол-во прошлых попыток
								$ch = $ch[0];
								if($ch>=$itm[2])
								{
									//закончились попытки
									$r .= 'Не удалось найти что-либо еще ... <br>';
								}else{
									if($itm[1]*1000>=rand(1,100000))
									{
										
										//Случайный предмет (Башня смерти)
										if($itm[0] == 'random1') {
											$itm[0] = $this->itbs[rand(0,count($this->itbs))];
										}
										
										//удачная попытка
										$it = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id`="'.((int)$itm[0]).'" LIMIT 1'));
										if(isset($it['id']))
										{
											$r .= 'Вы обнаружили предмет &quot;<b>'.$it['name'].'</b>&quot;.<br>';
											$u->addAction(time(),'takeit_'.$this->info['id'].'_'.$obj['id'],$u->info['city']);
											$this->addItem(array('uid'=>$u->info['id'],'iid'=>$it['id'],'time'=>time(),'x'=>$u->info['x'],'y'=>$u->info['y'],'bid'=>0,'del'=>(int)$itm[4]));
											$nj++;
										}
									}else{
										//неудачная попытка
										$u->addAction(time(),'takeit_'.$this->info['id'].'_'.$obj['id'],$u->info['city']);
										$r .= 'В этот раз не удалось найти что-либо еще ... <br>';
									}
								}
							}
					}elseif($s[0]=='fileact') {
						require('dnaction/'.$s[1].'.php');
					}
					$i++;
				}	
				$r = rtrim($r,'\<br\>');	
				if($r=='')
				{		
					$r = 'Ничего не произошло';
				}
				$this->error = $r;
			}
		}else{
			$this->error = 'Предмет не найден ...';
		}
	}
	
	public function addItem($i)
	{
		//добавляем предмет в пещеру (возможно выпал из бота или из сундука)
		$ins = mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`,`bot`,`del`) VALUES ("'.$this->info['id'].'","'.$i['uid'].'","'.$i['iid'].'","'.$i['time'].'","'.$i['x'].'","'.$i['y'].'","'.$i['bid'].'","'.((int)$i['del']).'")');
		return $ins;
	}
	
	public function takeinv($id)
	{
		global $u,$c,$code;
		$obj = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_items` WHERE `id` = "'.$id.'" AND `for_dn` = "0" AND `dn` = "'.$this->info['id'].'" LIMIT 1'));
		if(isset($obj['id']))
		{
			$this->test_luck($id);
			$fxv = array(
				'luck_count' => mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" LIMIT 1')),
				'user_count' => mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `dnow` = "'.$this->info['id'].'" LIMIT 1'))
			);
			if($obj['user'] == 0 && $fxv['user_count'][0] > $fxv['luck_count'][0] && $fxv['user_count'][0] > 1 && $this->info['bsid'] == 0 ) {
				$this->error = 'Вы не можете сейчас поднять этот предмет, дождитесь завершения розыгрыша. Осталось '.$u->timeOut($obj['time']-time()+300);
			}elseif($u->info['x']!=$obj['x'] || $obj['y']!=$u->info['y'])
			{
				$this->error = 'Предмет не найден ...';
			}else{
				if($obj['take']>0)
				{
					$this->error = 'Кто-то опередил вас ...';
				}else{
					if($obj['user']>0 && $obj['user']!=$u->info['id'] && $obj['time']>time()-300)
					{
						$uo = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`sex` FROM `users` WHERE `id` = "'.$obj['user'].'" LIMIT 1'));
					}
					if(isset($uo['id']))
					{
						$this->error = 'Предмет предназначен для &quot;'.$uo['login'].'&quot;. Вы сможете забрать этот предмет, если ';
						if($uo['sex']==1)
						{
							$this->error .= 'она ';
						}else{
							$this->error .= 'он ';
						}
						$this->error .= ' не поднимет его в течении '.ceil(5-(time()-$obj['time'])/60).' мин.';
						unset($uo);
					}else{
						$upd = mysql_query('UPDATE `dungeon_items` SET `take` = "'.$u->info['id'].'" WHERE `id` = "'.$obj['id'].'" LIMIT 1');
						if($upd){
							$it = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$obj['item_id'].'" LIMIT 1'));
							if(isset($it['id'])){
								$data = '|noremont=1|frompisher='.$this->info['id2'];
								if($it['ts']!=0){
									$data .= '|sudba='.$u->info['login'];	
								}
								if($obj['data'] != '') {
									$data .= $obj['data'];
								}
								$data = str_replace('|sudba=-1','|sudba='.$u->info['login'].'',$data);
								
								$idl = $u->addItem($obj['item_id'],$u->info['id'],$data,$obj);
								if( $obj['del'] > 0 ) {
									mysql_query('UPDATE `items_users` SET `dn_delete` = 1 WHERE `id` = "'.$idl.'" LIMIT 1');
								}
								unset($idl);
								//
								mysql_query('UPDATE `dailybonus` SET `dun_res` = `dun_res` + 1 WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
								//
								if($this->info['bsid'] == 0) {
									$rtxt = '<b>'.$u->info['login'].'</b> поднял предмет &quot;'.$it['name'].'&quot;';
									if( $obj['quest'] > 0 ) {
										$rtxt .= ' (Квест)';
									}
									mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['dnow']."','".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1','1')");
								}
								
								$this->error = 'Вы подняли &quot;'.$it['name'].'&quot;';
							}else{
								$this->error = 'Не удалось найти предмет ...';
							}
						}else{
							$this->error = 'Не удалось добавить предмет в инвентарь ...';
						}
					}
				}
			}
		}
	}

	
	//Розыгрыш предмета
	public function test_luck($id) {
		global $u;
		$fxv = array(
			'itm' => mysql_fetch_array(mysql_query('SELECT `im`.*,`ish`.* FROM `dungeon_items` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`dn` = "'.$this->info['id'].'" AND `ish`.`id` = "'.mysql_real_escape_string($id).'" AND `ish`.`take` = "0" AND `ish`.`delete` = "0" AND `ish`.`x` = "'.$u->info['x'].'" AND `ish`.`y` = "'.$u->info['y'].'" LIMIT 1')),
			'luck_count' => mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" LIMIT 1')),
			'user_count' => mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `dnow` = "'.$this->info['id'].'" LIMIT 1'))
		);
		$fxv['luck_count'] = $fxv['luck_count'][0];
		$fxv['user_count'] = $fxv['user_count'][0];
		
		if( $fxv['itm']['user'] > 0 ) {
			
		}elseif( $fxv['luck_count'] >= $fxv['user_count'] || $fxv['itm']['time']+300 < time() ) {
			$fxv['sp'] = mysql_query('SELECT * FROM `dungeon_actions` WHERE `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" ORDER BY `vals` DESC LIMIT '.$fxv['luck_count']);
			$fxv['winner'] = array();
			$fxv['win_val'] = 0;
			while( $fxv['pl'] = mysql_fetch_array($fxv['sp']) ) {
				if( $fxv['pl']['vals'] > $fxv['win_val'] ) {
					//Победитель
					unset($fxv['winner']);
					$fxv['winner'][] = $fxv['pl']['uid'];
					$fxv['win_val'] = $fxv['pl']['vals'];
				}elseif( $fxv['pl']['vals'] > 0 && $fxv['pl']['vals'] == $fxv['win_val'] ) {
					//ничья
					$fxv['winner'][] = $fxv['pl']['uid'];
				}
			}
			unset($fxv['pl'],$fxv['sp']);
			if( count($fxv['winner']) > 1 ) {
				//Розыгрыш еще раз между победителями
				$this->error .= '<div>Розыгрыш завершен!</div>';
			}elseif(count($fxv['winner']) == 1) {
				$fxv['user_win'] = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`sex` FROM `users` WHERE `id` = "'.$fxv['winner'][0].'" LIMIT 1'));
				$fxv['text'] = '<b>'.$fxv['user_win']['login'].'</b> выигрывает в споре за предмет &quot;'.$fxv['itm']['name'].'&quot;';
				$this->sys_chat($fxv['text']);
				mysql_query('UPDATE `dungeon_items` SET `time` = "'.time().'",`user` = "'.$fxv['user_win']['id'].'" WHERE `id` = "'.$fxv['itm']['id'].'" LIMIT 1');
				$this->error .= '<div>Розыгрыш завершен! Победитель <b>'.$fxv['user_win']['login'].'</b>!</div>';
			}
		}else{
			$this->error .= '<div>У остальных участников осталось '.$u->timeOut($fxv['itm']['time']+300-time()).' мин. до конца розыгрыша</div>';
		}
		unset($fxv);
	}
	
	public function itm_luck($id) {
		global $u;
		$fxv = array(
			'itm' => mysql_fetch_array(mysql_query('SELECT `im`.*,`ish`.* FROM `dungeon_items` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`dn` = "'.$this->info['id'].'" AND `ish`.`id` = "'.mysql_real_escape_string($id).'" AND `ish`.`take` = "0" AND `ish`.`delete` = "0" AND `ish`.`x` = "'.$u->info['x'].'" AND `ish`.`y` = "'.$u->info['y'].'" LIMIT 1')),
			'luck' => mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" LIMIT 1')),
			'luck2' => mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" LIMIT 1'))
		);
		if( $u->stats['silver'] >= 3 ) { //дополнительный бросок
			$fxv['luck2'][0]--;
		}
		if( $fxv['itm']['user'] > 0 ) {
			$this->error = 'Розыгрыш предмет уже завершился...';
		}elseif( !isset($fxv['itm']['id']) ) {
			$this->error .= '<div>Предмет не найден</div>';
		}elseif( $fxv['luck2'][0] > 0 ) {
			$this->error .= '<div>Вы уже учавствуете в розыгрыше &quot;'.$fxv['itm']['name'].'&quot;, ожидаем других участников еще '.$u->timeOut($fxv['itm']['time']-time()+300).'</div>';
		}else{
			$luck_users = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" LIMIT 1'));
			$luck_users = $luck_users[0];
			if( $luck_users < 1 ) {
				mysql_query('UPDATE `dungeon_items` SET `time` = "'.time().'" WHERE `id` = "'.$fxv['itm']['id'].'" LIMIT 1');
			}
			$rndl = rand(1,100);
			mysql_query('INSERT INTO `dungeon_actions` (`uid`,`dn`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
				"'.$u->info['id'].'","'.$u->info['dnow'].'","'.$u->info['x'].'","'.$u->info['y'].'","'.time().'",
				"luck_itm'.mysql_real_escape_string($id).'","'.$rndl.'"
			)');
			if( $u->info['sex'] == 0 ) {
				$fxv['text'] = '<b>'.$u->info['login'].'</b> выбросил *'.$rndl.'* в споре за предмет &quot;'.$fxv['itm']['name'].'&quot;';
			}else{
				$fxv['text'] = '<b>'.$u->info['login'].'</b> выбросила *'.$rndl.'* в споре за предмет &quot;'.$fxv['itm']['name'].'&quot;';
			}
			$this->sys_chat($fxv['text']);
			$this->error .= '<div>Вы выбросили <b>'.$rndl.'</b> в споре за &quot;'.$fxv['itm']['name'].'&quot;</div>';
		}
		$this->test_luck($id);
		unset($fxv);
	}
	
	public function itm_unluck($id) {
		global $u;
		$fxv = array(
			'itm' => mysql_fetch_array(mysql_query('SELECT `im`.*,`ish`.* FROM `dungeon_items` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`dn` = "'.$this->info['id'].'" AND `ish`.`id` = "'.mysql_real_escape_string($id).'" AND `ish`.`take` = "0" AND `ish`.`delete` = "0" AND `ish`.`x` = "'.$u->info['x'].'" AND `ish`.`y` = "'.$u->info['y'].'" LIMIT 1')),
			'luck' => mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" LIMIT 1'))
		);
		if( $fxv['itm']['user'] > 0 ) {
			$this->error = 'Розыгрыш предмет уже завершился...';
		}elseif( !isset($fxv['itm']['id']) ) {
			$this->error .= '<div>Предмет не найден</div>';
		}elseif( isset($fxv['luck']['id']) ) {
			if( $fxv['luck']['vals'] == 0 ) {
				$this->error .= '<div>Вы уже отказались от участия в розыгрыше &quot;'.$fxv['itm']['name'].'&quot;</div>';
			}else{
				$this->error .= '<div>Вы уже учавствуете в розыгрыше &quot;'.$fxv['itm']['name'].'&quot;, ожидаем других участников еще '.$u->timeOut($fxv['itm']['time']-time()+300).'</div>';
			}
		}else{	
			$luck_users = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($id).'" LIMIT 1'));
			$luck_users = $luck_users[0];
			if( $luck_users < 1 ) {
				mysql_query('UPDATE `dungeon_items` SET `time` = "'.time().'" WHERE `id` = "'.$fxv['itm']['id'].'" LIMIT 1');
			}
			if( $u->info['sex'] == 0 ) {
				$fxv['text'] = '<b>'.$u->info['login'].'</b> отказался от спора за предмет &quot;'.$fxv['itm']['name'].'&quot;';
			}else{
				$fxv['text'] = '<b>'.$u->info['login'].'</b> отказалась от спора за предмет &quot;'.$fxv['itm']['name'].'&quot;';
			}
			$this->sys_chat($fxv['text']);
			mysql_query('INSERT INTO `dungeon_actions` (`uid`,`dn`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
				"'.$u->info['id'].'","'.$u->info['dnow'].'","'.$u->info['x'].'","'.$u->info['y'].'","'.time().'",
				"luck_itm'.mysql_real_escape_string($id).'","0"
			)');
			$this->error .= '<div>Вы отказались от участия в розыгрыше &quot;'.$fxv['itm']['name'].'&quot;</div>';
		}
		unset($fxv);
	}
	
	public function itemsMap()
	{
		global $u,$c,$code;
		$r = '';
		$live_users = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `stats` WHERE `dnow` = "'.$this->info['id'].'" LIMIT 1'));
		$live_users = $live_users[0];
		$sp = mysql_query('SELECT `im`.*,`ish`.* FROM `dungeon_items` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`dn` = "'.$this->info['id'].'" AND `ish`.`take` = "0" AND `ish`.`delete` = "0" AND ( `ish`.`onlyfor` = "0" OR `ish`.`onlyfor` = "'.$u->info['id'].'" ) AND `ish`.`x` = "'.$u->info['x'].'" AND `ish`.`y` = "'.$u->info['y'].'" LIMIT 100');
		while($pl = mysql_fetch_array($sp))
		{
			$action = 'main.php?take='.$pl['id'].'';
			$luck_users = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.mysql_real_escape_string($pl['id']).'" LIMIT 1'));
			$luck_users = $luck_users[0];
			if( $pl['user'] == 0 && $live_users > 1 && ( $pl['time']+300 > time() || $luck_users < 1 ) ) {
				$fxv =  mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$this->info['id'].'" AND `vars` = "luck_itm'.$pl['id'].'" LIMIT 1'));
				if( !isset($fxv['id']) ) {
					//Розыгрыш
					if( $this->info['bsid'] == 0 ) {
						$action = 'javascript:top.fartgame(\''.$pl['id'].'\',\''.$pl['img'].'\',\''.$pl['name'].'\',1,\'\');';
					}
				}else{
					//$action = 'javascript:alert(\'Вы уже учавствуете в розыгрыше данного предмета\');';
				}
			}
			$r .= '<a href="'.$action.'"><img style="padding:5px;cursor:pointer;" title="Взять &quot;'.$pl['name'].'&quot;" src="http://img.xcombats.com/i/items/'.$pl['img'].'" /></a>';
		}
		if($r!='')
		{
			$r = '<H4>В комнате разбросаны вещи:</H4>'.$r;
		}
		return $r;
	}
	
	public function testLike($x1,$y1,$x2,$y2)
	{
		//из $x1,$y1 в $x2,$y2
		//доступна-ли эта клетка для действий
		$r = 0;
		$c1 = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$x1.'" AND `y` = "'.$y1.'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
		$c2 = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$x2.'" AND `y` = "'.$y2.'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
		if(isset($c1['id']) && isset($c2['id']))
		{
			if($x1==$x2 && $y1==$y2)
			{
				$r = 1;
			}elseif($x1==$x2-1 && $c1['go_1']==1) //право
			{
				$r = 1;
			}elseif($x1==$x2+1 && $c1['go_2']==1) //лево
			{
				$r = 1;
			}elseif($y1==$y2-1 && $c1['go_3']==1) //верх
			{
				$r = 1;
			}elseif($y1==$y2+1 && $c1['go_4']==1) //низ
			{
				$r = 1;
			}	
		}
		return $r;
	}
	
	public function genObjects() {
		global $u,$c,$code;
		////i:{id,name,mapPoint,action,img,type},
		//'count':1,0:{0:1234,1:'Сундук',2:5,3:'',4:'test.gif',5:0,6:position,7:width,8:heigh,9:left,10:top},
		//psition 0 - по центру , 1- сверху, 2- слева, 3- снизу, 4- справа
		$r = ''; 
		$whr = array(
			1 => ' (((`u`.`x` <= '.($u->info['x']+2).' && `u`.`x` >= '.($u->info['x']-2).') && (`u`.`y` >= '.($u->info['y']+1).' && `u`.`y` <= '.($u->info['y']+4).')) OR (`u`.`y` = '.$u->info['y'].' && `u`.`x` = '.$u->info['x'].')) ', //прямо 
			3 => ' (((`u`.`x` <= '.($u->info['x']+2).' && `u`.`x` >= '.($u->info['x']-2).') && (`u`.`y` <= '.($u->info['y']-1).' && `u`.`y` >= '.($u->info['y']-4).')) OR (`u`.`y` = '.$u->info['y'].' && `u`.`x` = '.$u->info['x'].')) ', //вниз
			2 => ' (((`u`.`x` <= '.($u->info['x']-1).' && `u`.`x` >= '.($u->info['x']-4).') && (`u`.`y` <= '.($u->info['y']+2).' && `u`.`y` >= '.($u->info['y']-2).'))OR (`u`.`y` = '.$u->info['y'].' && `u`.`x` = '.$u->info['x'].')) ', //лево				
			4 => ' (((`u`.`x` >= '.($u->info['x']+1).' && `u`.`x` <= '.($u->info['x']+4).') && (`u`.`y` <= '.($u->info['y']+2).' && `u`.`y` >= '.($u->info['y']-2).')) OR (`u`.`y` = '.$u->info['y'].' && `u`.`x` = '.$u->info['x'].')) ' //право
		); 
		$sp = mysql_query('SELECT `u`.* FROM `dungeon_obj` AS `u` WHERE `u`.`dn` = "'.$u->info['dnow'].'" AND `u`.`for_dn` = "0" AND ((`u`.`s` = "0" OR `u`.`s` = "'.$this->gs.'") OR `u`.`s2` = "'.$this->gs.'") AND '.$whr[$this->gs].' LIMIT 76');
		$i = 0; $pos = array();
		while( $pl = mysql_fetch_array($sp) )  {
			if($pl['fix_x_y'] == 0 ||
			  ($pl['fix_x_y'] == 1 && $pl['x'] == $u->info['x']) ||
			  ($pl['fix_x_y'] == 2 && $pl['y'] == $u->info['y']) ||
			  ($pl['fix_x_y'] == 3 && $pl['x'] == $u->info['x'] && $pl['y'] == $u->info['y'])) {
				if(($pl['os1']==0 && $pl['os2']==0 && $pl['os3']==0 && $pl['os4']==0) || ($this->cord[$pl['y'].'_'.$pl['x']] == $pl['os1'] || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os2'] || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os3'] || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os4'])) { 
					$i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
					$r .= ','.($i-1).':{0:'.$pl['id'].',1:\''.$pl['name'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:\'action\',4:\''.$pl['img'].'\',5:'.$pl['type'].',6:0,7:'.$pl['w'].',8:'.$pl['h'].',9:'.$pl['left'].',10:'.$pl['top'].',11:'.$pl['date'].'}';
				}elseif( $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os1']-1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os2']-1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os3']-1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os4']-1 ) {
					$dt2 = explode(',',ltrim(rtrim($pl['date'],'\}'),'\{'));
					$da = array();
					$is = 0;
					while($is < count($dt2)) {
						$dt2[$is] = explode(':',$dt2[$is]);
						$da[$dt2[$is][0]] = $dt2[$is][1];
						$is++;
					}
					if(isset($da['use'])) unset($da['use']); // Справа
					if(isset($da['rl2']))$da['rl2'] = -round((int)$da['rl2'] * 1.55); // Слева
					if(isset($da['rl3']))$da['rl3'] = round((int)$da['rl3'] +160);
					if(isset($da['rl4']))$da['rl4'] = round((int)$da['rl4'] -120);
					$pl['date'] = str_replace('"', '', json_encode($da));
					
					
					$i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
					$r .= ','.($i-1).':{0:'.$pl['id'].',1:\''.$pl['name'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:\'\',4:\''.$pl['img'].'\',5:'.$pl['type'].',6:0,7:'.$pl['w'].',8:'.$pl['h'].',9:'.$pl['left'].',10:'.$pl['top'].',11:'.$pl['date'].'}';
				} else if( $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os1']+1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os2']+1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os3']+1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os4']+1 ) {
					
					$dt2 = explode(',',ltrim(rtrim($pl['date'],'\}'),'\{'));
					$da = array();
					$is = 0;
					while($is < count($dt2)) {
						$dt2[$is] = explode(':',$dt2[$is]);
						$da[$dt2[$is][0]] = $dt2[$is][1];
						$is++;
					} 
					if(isset($da['use'])) unset($da['use']); // Справа
					if(isset($da['rl2']))$da['rl2'] = 355-round((int)$da['rl2'] * 0.30); // Справа
					if(isset($da['rl3']))$da['rl3'] = round((int)$da['rl3'] -160);
					if(isset($da['rl4']))$da['rl4'] = round((int)$da['rl4'] +120);
					$pl['date'] = str_replace('"', '', json_encode($da));
					$i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++; 
					$r .= ','.($i-1).':{0:'.$pl['id'].',1:\''.$pl['name'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:\'\',4:\''.$pl['img'].'\',5:'.$pl['type'].',6:0,7:'.$pl['w'].',8:'.$pl['h'].',9:'.$pl['left'].',10:'.$pl['top'].',11:'.$pl['date'].'}';
				}
			}
		}	
		$r = 'count:'.$i.$r;
		return $r;
	}
	
	public function botAtack($bot,$uid,$bs) {
	    global $u,$c,$code;
	    $user = mysql_fetch_array(mysql_query('SELECT `id`,`battle` FROM `users` WHERE `id` = "'.$uid['id'].'" LIMIT 1'));

	    if($user['battle']>0){
			$btli = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$user['battle'].'" AND `team_win` = "-1" LIMIT 1'));
	    }

	    if( !isset($btli['id']) ) { //Создаем поединок
			$btl_id = 0;
			$expB = 0;
			$btl = array('players'=>'', 'timeout'=>180, 'type'=>0, 'invis'=>0, 'noinc'=>0, 'travmChance'=>0, 'typeBattle'=>0, 'addExp'=>$expB, 'money'=>0 );
			
			$ins = mysql_query('INSERT INTO `battle` (`dungeon`,`dn_id`,`x`,`y`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES ("'.$this->info['id2'].'", "'.$this->info['id'].'", "'.$bot['x'].'", "'.$bot['y'].'", "'.$u->info['city'].'", "'.time().'", "'.$btl['players'].'", "'.$btl['timeout'].'", "'.$btl['type'].'", "'.$btl['invis'].'", "'.$btl['noinc'].'", "'.$btl['travmChance'].'", "'.$btl['typeBattle'].'", "'.$btl['addExp'].'", "'.$btl['money'].'")');

			$btl_id = mysql_insert_id();
	
			if( $btl_id > 0 ) { //Добавляем ботов 
				$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "0" AND `delete` = "0" AND `dn` = "'.$this->info['id'].'" AND `id2` = "'.$bot['id2'].'" LIMIT 1'); // Только тот, который напал и жив ли он?
				$j = 0;
				$logins_bot = array();
				while($pl = mysql_fetch_array($sp)) {
					mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "'.$btl_id.'" WHERE `id2` = "'.$bot['id2'].'" LIMIT 1');
					$jui = 1;
					while($jui<=$pl['colvo']) {
						$k = $u->addNewbot($pl['id_bot'],NULL,NULL,$logins_bot);
						$logins_bot = $k['logins_bot'];
						if( $k!=false ) { 
							$upd = mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
							if($upd) {
								$upd = mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
								if($upd) {
								$j++;
								}
							}
						} 
						$jui++;
					}
				}
				unset($logins_bot); 
				if( $j>0 ) {
					mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$user['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$user['id'].'" LIMIT 1');
				}
			}
	    } else {
			$btl_id = $btli['id'];
			//Добавляем ботов
			$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "0" AND `delete` = "0" AND `dn` = "'.$this->info['id'].'" AND `id2` = "'.$bot['id2'].'" LIMIT 1'); 
			$j = 0; $logins_bot = array();
			$logins_bot_text =array(); 
			$logins_bot_vars =array('time1='.time().''); 
			while( $pl = mysql_fetch_array($sp) ) {
				mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "'.$btl_id.'" WHERE `id2` = "'.$bot['id2'].'" LIMIT 1'); 
				$jui = 1;
				while($jui<=$pl['colvo']){
					$k = $u->addNewbot($pl['id_bot'],NULL,NULL,$logins_bot);
					$logins_bot = $k['logins_bot'];
					$logins_bot_text[] = ' <strong>'.$k['login'].'</strong>'; 
					if($k!=false){ 
						$upd = mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
						if($upd){
							$upd = mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
							if($upd){
								$j++;
							}
						}
					}
					$jui++;
				}
				if( $j>0 ){
					$logins_bot_text = '{tm1} В поединок вмешались: '.implode(', ',$logins_bot_text).'.';
					$logins_bot_vars = implode('||',$logins_bot_vars); 
					$battle_log  =  mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle`='.$btl_id.' ORDER BY `id_hod` DESC LIMIT 1'));
					if($battle_log['id_hod']>0){
						mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$btl_id.'","'.($battle_log['id_hod']+1).'","'.$logins_bot_text.'","'.$logins_bot_vars.'","","","","",1)');
					}
				}
			}
			unset($logins_bot);
			if( $j > 0 ) {
				mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$user['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$user['id'].'" LIMIT 1');
			}
	    }
	}
	
	public function genUsers() {
		global $u,$c,$code;
		////i:{id,login,mapPoint,sex,obraz,type,users_p},
		//'count':1,0:{0:1015,1:'Test1',2:5,3:0,4:'1',5:'user',6:1},
		$r = '';
		$whr = array(
		    1 => ' ((`u`.`x` <= '.($u->info['x']+2).' && `u`.`x` >= '.($u->info['x']-2).') && (`u`.`y` >= '.$u->info['y'].' && `u`.`y` <= '.($u->info['y']+4).')) ', //прямо 
		    3 => ' ((`u`.`x` <= '.($u->info['x']+2).' && `u`.`x` >= '.($u->info['x']-2).') && (`u`.`y` <= '.$u->info['y'].' && `u`.`y` >= '.($u->info['y']-4).')) ', //вниз
		    2 => ' ((`u`.`x` <= '.$u->info['x'].' && `u`.`x` >= '.($u->info['x']-4).') && (`u`.`y` <= '.($u->info['y']+2).' && `u`.`y` >= '.($u->info['y']-2).')) ', //лево				
		    4 => ' ((`u`.`x` >= '.$u->info['x'].' && `u`.`x` <= '.($u->info['x']+4).') && (`u`.`y` <= '.($u->info['y']+2).' && `u`.`y` >= '.($u->info['y']-2).')) ' //право
		);
		//
		$tmsu = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$u->info['id'].'" LIMIT 1'));
		//
		$sp = mysql_query('SELECT `u`.*,`st`.* FROM `stats` AS `u` LEFT JOIN `users` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`dnow` = "'.$u->info['dnow'].'" AND '.$whr[$this->gs].' AND `u`.`id` != "'.$u->info['id'].'" LIMIT 50');
		$i = 0; $pos = array();
		while($pl = mysql_fetch_array($sp)){
		    $i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
		   	$noat = 0;
			if( $this->info['bsid'] > 0 ) {
				$noat = 1;
			}
			if( $this->info['id2'] == 15 ) {
				//Хоккей
		    	$r .= ','.($i-1).':{8:'.$noat.',0:'.$pl['id'].',1:\''.$pl['login'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:'.$pl['sex'].',4:\''.str_replace('.gif','',$pl['obraz']).'\',5:\'user\',6:'.$pos[$this->cord[$pl['y'].'_'.$pl['x']]].'';
				//
				$tms = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$pl['id'].'" LIMIT 1'));
				if($tms['team'] == $tmsu['team']) {
					$r .= ',9:1';
				}else{
					$r .= ',9:2';
				}
				$r .= '';
				//
				$r .= '}';
			}else{
		    	$r .= ','.($i-1).':{9:0,8:'.$noat.',0:'.$pl['id'].',1:\''.$pl['login'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:'.$pl['sex'].',4:\''.str_replace('.gif','',$pl['obraz']).'\',5:\'user\',6:'.$pos[$this->cord[$pl['y'].'_'.$pl['x']]].'}';
			}
		}	
		
		//отображаем ботов
		//
		//$sp = mysql_query('SELECT `u`.*,`st`.*  FROM `dungeon_bots` AS `u` LEFT JOIN `test_bot` AS `st` ON (`u`.`id_bot` = `st`.`id`) WHERE `u`.`dn` = "'.$u->info['dnow'].'" AND `u`.`atack` = "0" AND `u`.`delete` = "0" AND `u`.`inBattle` = "0" AND `u`.`go_bot` > 0 ORDER BY `u`.`go_bot` ASC LIMIT 35');
		
		// Выбираем Ботов в подземельи, которые Не в бою, Живые, и не дальше чем -\+30 по X и -\+35 по Y (дабы не гонять всех ботов, меньше выборка).
		/*
		$sp = mysql_query('SELECT `db`.*, `tb`.*  FROM `dungeon_bots` AS `db` LEFT JOIN `test_bot` AS `tb` ON (`db`.`id_bot` = `tb`.`id`) LEFT JOIN `stats` as `st` ON (`st`.`dnow` = `db`.`dn`) WHERE `db`.`dn` = "'.$u->info['dnow'].'" AND `db`.`x` > `st`.`x`-30 AND `db`.`x` < `st`.`x`+30 AND `db`.`y` > `st`.`y`-35 AND `db`.`y` < `st`.`y`+35 AND `db`.`atack` = "0" AND `db`.`delete` = "0" AND `db`.`inBattle` = "0" AND `db`.`go_bot` > 0 GROUP BY `db`.`id2` ORDER BY `db`.`go_bot` ASC LIMIT 50');
		while($pl = mysql_fetch_array($sp)){
		    //перемещение бота, каждые 3-10 сек.
		    if( $pl['go_bot'] > 0 && $pl['go_bot'] <= time() ) {
				$tgx = rand(-1,1);
				$tgy = rand(-1,1);
				if($tgx!=0 && $tgy!=0) {
					if(rand(0,1)==1){
					$tgy = 0;
					}else{
					$tgx = 0;
					}
				}
				$vlb = $this->testLike($pl['x'],$pl['y'],$pl['x']+$tgx,$pl['y']+$tgy);
				//Кто-то рядом
				$tuz = mysql_fetch_array(mysql_query('SELECT `x`,`y`,`id`,`hpNow` FROM `stats` WHERE `dnow` = "'.$this->info['id'].'" AND ( (`x` = '.($pl['x']+1).' AND `y` = '.($pl['y']).') OR (`x` = '.($pl['x']-1).' AND `y` = '.($pl['y']).') OR (`x` = '.($pl['x']).' AND `y` = '.($pl['y']+1).') OR (`x` = '.($pl['x']).' AND `y` = '.($pl['y']-1).') ) LIMIT 1'));
				
				if(isset($tuz['id']) && $this->testLike($pl['x'],$pl['y'],$tuz['x'],$tuz['y'])==1){ 
					$this->botAtack($pl,$tuz,1);
				}elseif($vlb == 1){ // Передвижение ботов. 
					$pl['go_bot'] = time()+10+rand(1,5);
					$pl['x'] += $tgx;
					$pl['y'] += $tgy;
					mysql_query('UPDATE `dungeon_bots` SET `x` = "'.$pl['x'].'",`y` = "'.$pl['y'].'",`go_bot` = "'.$pl['go_bot'].'" WHERE `id2` = "'.$pl['id2'].'" LIMIT 1');
				}
				unset($tgx,$tgy,$vlb,$tuz);
		    }
		}
		*/
		$sp = mysql_query('SELECT `u`.*,`st`.* FROM `dungeon_bots` AS `u` LEFT JOIN `test_bot` AS `st` ON (`u`.`id_bot` = `st`.`id`) WHERE `u`.`dn` = "'.$u->info['dnow'].'" AND '.$whr[$this->gs].' AND `u`.`delete` = "0" LIMIT 50');
		while($pl = mysql_fetch_array($sp)){
		    $i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
		    $dlg = 0;
		    if($pl['dialog']>0){
			$dlg = $pl['dialog'];	
		    }
			$noat = 1;
			if( $pl['noatack'] == 1 && $this->info['bsid'] == 0 ) {
				$noat = 0;
			}
		    $r .= ','.($i-1).':{9:0,0:'.$pl['id2'].',1:\''.$pl['login'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:'.$pl['sex'].',4:\''.str_replace('.gif','',$pl['obraz']).'\',5:\'bot\',6:'.$pos[$this->cord[$pl['y'].'_'.$pl['x']]].',7:'.$dlg.',8:'.$noat.'}';
		}	
		
		$r = 'count:'.$i.$r;
		//$wd = $this->cord['2_0'];
		return $r;
	}
	
	public function testGo($id)
	{
		global $u,$c,$code;
		$go = 0;
		if($id==1)
		{
			//вперед
			$go = $this->sg[$this->gs][1];
		}elseif($id==2)
		{
			//назад
			$go = $this->sg[$this->gs][3];
		}elseif($id==3)
		{
			//на право
			$go = $this->sg[$this->gs][4];
		}elseif($id==4)
		{
			//на лево
			$go = $this->sg[$this->gs][2];
		}
		$thp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
		$ng = array(
		4=>1,
		2=>2,
		1=>3,
		3=>4
		);
		if(isset($thp['id']) && $thp['go_'.$ng[$go]]==0)
		{
			$go = 0;
		}
		$tgo = array(0=>0,1=>0);	
		if($go==1)
		{
			$tgo[1] += 1;
		}elseif($go==2)
		{
			$tgo[0] -= 1;
		}elseif($go==3)
		{
			$tgo[1] -= 1;
		}elseif($go==4)
		{
			$tgo[0] += 1;
		}
		
		$tbot = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `x` = "'.($u->info['x']+(int)$tgo[0]).'" AND `y` = "'.($u->info['y']+(int)$tgo[1]).'" AND `dn` = "'.$this->info['id'].'" AND `for_dn` = "0" AND `delete` = "0" LIMIT 1'));
		if(isset($tbot['id2']) && $u->info['admin']==0)
		{
			$go = 0;	
		}
		
		$tmap = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
		//наличие предмета
		if($tmap['tr_items']!='')
		{
			$ti = explode(',',$tmap['tr_items']);
			$i = 0; $trnit = '';
			while($i<count($ti))
			{
				$ti2 = explode('=',$ti[$i]);
				if($ti2[0]>0 && $ti2[1]>0)
				{
					$num_rows = mysql_num_rows(mysql_query('SELECT * FROM `items_users` WHERE  `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inShop` = "0" AND `item_id` = "'.((int)$ti2[0]).'" LIMIT '.((int)$ti2[1]).''));
					if($num_rows < (int)$ti2[1])
					{
						$tgo = $ti2[2];
						if($tgo!='0000')
						{
							if($tgo[$ng[$go]-1]==1)
							{
								$go = 0;
								$trm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.((int)$ti2[0]).'" LIMIT 1'));
								$trnit .= '&quot;'.$trm['name'].'&quot;, ';	
							}
						}
					}
				}
				$i++;
			}
			if($trnit!='')
			{
				$trnit = rtrim($trnit,', ');	
				$this->error = 'У вас нет подходящего предмета. Требуется '.$trnit;
			}
		}
		
		$tmGo  = $u->info['timeGo']-time(); //сколько секунд осталось
		if($tmGo>0)
		{
			$go = 0;
			$this->error = 'Не так быстро...';	
		}
		
		if($u->aves['now']>=$u->aves['max'])
		{
			$go = 0;
			$this->error = 'Вы не можете перемещаться, рюкзак переполнен ...';
		}
		
		if($go>0)
		{
			if($go==1)
			{
				$u->info['y'] += 1;
			}elseif($go==2)
			{
				$u->info['x'] -= 1;
			}elseif($go==3)
			{
				$u->info['y'] -= 1;
			}elseif($go==4)
			{
				$u->info['x'] += 1;
			}
			if( $u->stats['speed_dungeon'] > 0 ) {
				$tmap['timeGO'] = round($tmap['timeGO']/100*(100-$u->stats['speed_dungeon']));
				if( $tmap['timeGO'] < 2 ) {
					$tmap['timeGO'] = 1;
				}
			}
			$u->info['timeGo'] = time()+$tmap['timeGO'];			
			$u->info['timeGoL'] = time();
			$tmap0 = mysql_fetch_array(mysql_query('SELECT `id`,`teleport` FROM `dungeon_map` WHERE `id_dng` = "'.$tmap['id_dng'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" LIMIT 1'));
			if( $tmap0['teleport'] > 0 ){
				$tmap1 = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`y` FROM `dungeon_map` WHERE `id` = "'.$tmap0['teleport'].'" LIMIT 1'));
				if( isset($tmap1['id']) ) {
					$u->info['x'] = $tmap1['x'];
					$u->info['y'] = $tmap1['y'];
					$this->error = 'Вы переместились в другую комнату...';
				}
			}
			$upd = mysql_query('UPDATE `stats` SET `x` = "'.$u->info['x'].'",`y` = "'.$u->info['y'].'",`timeGo` = "'.$u->info['timeGo'].'",`timeGoL` = "'.$u->info['timeGoL'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}		
	}
	
	public function testGone($id)
	{
		global $u,$c,$code;
		$go = 0;
		if($id==1)
		{
			//вперед
			$go = $this->sg[$this->gs][1];
		}elseif($id==2)
		{
			//назад
			$go = $this->sg[$this->gs][3];
		}elseif($id==3)
		{
			//на право
			$go = $this->sg[$this->gs][4];
		}elseif($id==4)
		{
			//на лево
			$go = $this->sg[$this->gs][2];
		}
		$thp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
		$ng = array(
		4=>1,
		2=>2,
		1=>3,
		3=>4
		);
		if(isset($thp['id']) && $thp['go_'.$ng[$go]]==0)
		{
			$go = 0;
		}
		$tgo = array(0=>0,1=>0);	
		if($go==1)
		{
			$tgo[1] += 1;
		}elseif($go==2)
		{
			$tgo[0] -= 1;
		}elseif($go==3)
		{
			$tgo[1] -= 1;
		}elseif($go==4)
		{
			$tgo[0] += 1;
		}
		
		$tbot = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `x` = "'.($u->info['x']+(int)$tgo[0]).'" AND `y` = "'.($u->info['y']+(int)$tgo[1]).'" AND `dn` = "'.$this->info['id'].'" AND `for_dn` = "0" AND `delete` = "0" LIMIT 1'));
		if(isset($tbot['id2']) && $u->info['admin']==0)
		{
			$go = 0;	
		}
		
		$tmap = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
		//наличие предмета
		/*
		if($tmap['tr_items']!='')
		{
			$ti = explode(',',$tmap['tr_items']);
			$i = 0; $trnit = '';
			while($i<count($ti))
			{
				$ti2 = explode('=',$ti[$i]);
				if($ti2[0]>0 && $ti2[1]>0)
				{
					$num_rows = mysql_num_rows(mysql_query('SELECT * FROM `items_users` WHERE  `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inShop` = "0" AND `item_id` = "'.((int)$ti2[0]).'" LIMIT '.((int)$ti2[1]).''));
					if($num_rows < (int)$ti2[1])
					{
						$tgo = $ti2[2];
						if($tgo!='0000')
						{
							if($tgo[$ng[$go]-1]==1)
							{
								$go = 0;
							}
						}
					}
				}
				$i++;
			}
		}
		*/
		
		return $go;		
	}
	
	public function testSt($id,$s)
	{
		$r = 0;
		//заменяем отображение стен в зависимости от угла обзора
		$s = $this->sg[$this->gs][$s];
		if(isset($this->map[1][$id]['id']))
		{
			$r = $this->map[1][$id]['st'][($s-1)];
		}
		return $r;
	}
	
	public function lookDungeon()
	{
		global $u,$c,$code,$pd;
		/* Генерируем изображение карты */
		/* LEVEL 1 */
		
		// Исправления от 29/10/2014 относительно D5, 2 этаж, ПТП. Покрутиться и все гуд.  и от 22/11/2014 для обратных стен. Если стена к нам задницей, мы её не видим.
		if($this->testSt(2,4)>0 /* || $this->testSt(3,2)>0 */){ $pd[28] = 1; }
		if(/*$this->testSt(1,4)>0 ||*/ $this->testSt(2,2)>0){ $pd[27] = 1; }
		if($this->testSt(2,1)>0 /*|| $this->testSt(5,3)>0*/){ $pd[26] = 1; } 
		if($this->testSt(3,1)>0/* || $this->testSt(6,3)>0*/){ $pd[25] = 1; }
		if($this->testSt(1,1)>0 /*|| $this->testSt(4,3)>0*/){ $pd[24] = 1; } 
		/* LEVEL 2 */
		if($this->testSt(5,4)>0 /*|| $this->testSt(6,2)>0*/){ $pd[23] = 1; }
		if(/*$this->testSt(4,4)>0 || */$this->testSt(5,2)>0){ $pd[22] = 1; }
		if($this->testSt(5,1)>0 /*|| $this->testSt(8,3)>0*/){ $pd[21] = 1; }
		if($this->testSt(6,1)>0 /*|| $this->testSt(7,3)>0*/){ $pd[20] = 1; }
		if($this->testSt(4,1)>0/* || $this->testSt(9,3)>0*/){ $pd[19] = 1; }
		 
		/* LEVEL 3 */
		if($this->testSt(8,4)>0 /*|| $this->testSt(7,2)>0*/){ $pd[18] = 1; }
		if(/*$this->testSt(9,4)>0 || */$this->testSt(8,2)>0){ $pd[17] = 1; }
		if($this->testSt(8,1)>0 /* || $this->testSt(12,3)>0*/){ $pd[16] = 1; }
		if($this->testSt(7,1)>0 /* || $this->testSt(13,3)>0*/){ $pd[15] = 1; }
		if($this->testSt(9,1)>0 /*|| $this->testSt(11,3)>0*/){ $pd[14] = 1; }
		
		/* LEVEL 4 */
	
		if($this->testSt(12,4)>0 || $this->testSt(13,2)>0){ $pd[13] = 1; }
		if($this->testSt(12,2)>0 || $this->testSt(11,4)>0){ $pd[12] = 1; }
		if($this->testSt(13,1)>0 || $this->testSt(17,3)>0){ $pd[11] = 1; } //8
		if($this->testSt(11,1)>0 || $this->testSt(16,3)>0){ $pd[10] = 1; } //7
		if($this->testSt(12,1)>0/* || $this->testSt(15,3)>0*/){ $pd[9] = 1; } 
		if($this->testSt(14,1)>0 || $this->testSt(18,3)>0){ $pd[6] = 1; } //2
		if($this->testSt(10,1)>0 || $this->testSt(19,3)>0){ $pd[5] = 1; } //1
		if($this->testSt(16,4)>0 || $this->testSt(15,2)>0){ $pd[4] = 1; }
		if($this->testSt(15,4)>0 || $this->testSt(17,2)>0){ $pd[3] = 1; }
	 
		/* Генерируем предметы на карте */
		
		/* Генерируем персонажей и ботов на карте */
		
	}

	public function getMatrix($y,$x)
	{
		global $u;
		$this->cord['x']++;
		$this->cord[($u->info['y']+$y).'_'.($u->info['x']+$x)] = $this->cord['x'];
		return $this->map[0][($u->info['y']+$y).'_'.($u->info['x']+$x)];
	}
	
	public function genMatix()
	{
		$r = array();
		if($this->gs == 1)
		{
			//1; //смотрим прямо
			$r[1]  = $this->getMatrix(0,-1); # слева от меня
			$r[2]  = $this->getMatrix(0,0); # подомной
			$r[3]  = $this->getMatrix(0,1); # справа от меня
			$r[4]  = $this->getMatrix(1,-1); # слева +1 вперед
			$r[5]  = $this->getMatrix(1,0); # передомной +1
			$r[6]  = $this->getMatrix(1,1); # справа +1 вперед
			$r[7]  = $this->getMatrix(2,1); # справа +2 вперед
			$r[8]  = $this->getMatrix(2,0); # передомной +2
			$r[9]  = $this->getMatrix(2,-1); # слева +2 вперед
			$r[10] = $this->getMatrix(3,-2); # слева через одну, +3 вперед
			$r[11] = $this->getMatrix(3,-1); # слева +3 вперед
			$r[12] = $this->getMatrix(3,0); # передомной +3
			$r[13] = $this->getMatrix(3,1); # справа +3 вперед
			$r[14] = $this->getMatrix(3,2); # справа через одну, +3 вперед
			$r[15] = $this->getMatrix(4,0); # передомной +4
			$r[16] = $this->getMatrix(4,-1); # слева +4 вперед
			$r[17] = $this->getMatrix(4,1); # справа +4 вперед
			$r[18] = $this->getMatrix(4,2); # справа через одну, +4 вперед
			$r[19] = $this->getMatrix(4,-2); # слева через одну, +4 вперед
			
		}elseif($this->gs == 2)
		{
			//2; //смотрим лево
			$r[1]  = $this->getMatrix(-1,0);
			$r[2]  = $this->getMatrix(0,0);
			$r[3]  = $this->getMatrix(1,0);
			$r[4]  = $this->getMatrix(-1,-1);
			$r[5]  = $this->getMatrix(0,-1);
			$r[6]  = $this->getMatrix(1,-1);
			$r[7]  = $this->getMatrix(1,-2);
			$r[8]  = $this->getMatrix(0,-2);
			$r[9]  = $this->getMatrix(-1,-2);
			$r[10] = $this->getMatrix(-2,-3);
			$r[11] = $this->getMatrix(-1,-3);
			$r[12] = $this->getMatrix(0,-3);
			$r[13] = $this->getMatrix(1,-3);
			$r[14] = $this->getMatrix(2,-3);
			$r[15] = $this->getMatrix(0,-4);
			$r[16] = $this->getMatrix(-1,-4);
			$r[17] = $this->getMatrix(1,-4);
			$r[18] = $this->getMatrix(2,-4);
			$r[19] = $this->getMatrix(-2,-4);
		}elseif($this->gs == 3)
		{
			//3; //смотрим вниз
			$r[1]  = $this->getMatrix(0,1);
			$r[2]  = $this->getMatrix(0,0);
			$r[3]  = $this->getMatrix(0,-1);
			$r[4]  = $this->getMatrix(-1,1);
			$r[5]  = $this->getMatrix(-1,0);
			$r[6]  = $this->getMatrix(-1,-1);
			$r[7]  = $this->getMatrix(-2,-1);
			$r[8]  = $this->getMatrix(-2,0);
			$r[9]  = $this->getMatrix(-2,1);
			$r[10] = $this->getMatrix(-3,2);
			$r[11] = $this->getMatrix(-3,1);
			$r[12] = $this->getMatrix(-3,0);
			$r[13] = $this->getMatrix(-3,-1);
			$r[14] = $this->getMatrix(-3,-2);
			$r[15] = $this->getMatrix(-4,0);
			$r[16] = $this->getMatrix(-4,1);
			$r[17] = $this->getMatrix(-4,-1);
			$r[18] = $this->getMatrix(-4,-2);
			$r[19] = $this->getMatrix(-4,2);
		}elseif($this->gs == 4)
		{
			//4; //смотрим право
			$r[1]  = $this->getMatrix(1,0);
			$r[2]  = $this->getMatrix(0,0);
			$r[3]  = $this->getMatrix(-1,0);
			$r[4]  = $this->getMatrix(1,1);
			$r[5]  = $this->getMatrix(0,1);
			$r[6]  = $this->getMatrix(-1,1);
			$r[7]  = $this->getMatrix(-1,2);
			$r[8]  = $this->getMatrix(0,2);
			$r[9]  = $this->getMatrix(1,2);
			$r[10] = $this->getMatrix(2,3);
			$r[11] = $this->getMatrix(1,3);
			$r[12] = $this->getMatrix(0,3);
			$r[13] = $this->getMatrix(-1,3);
			$r[14] = $this->getMatrix(-2,3);
			$r[15] = $this->getMatrix(0,4);
			$r[16] = $this->getMatrix(1,4);
			$r[17] = $this->getMatrix(-1,4);
			$r[18] = $this->getMatrix(-2,4);
			$r[19] = $this->getMatrix(2,4);
		}
		return $r;
	}
}

$d = new dungeon;
$d->start();
?>