<?
if(!defined('GAME'))
{
	die();
}

/*
- доделать добавление приема в $btl->users[]['eff'] после использования, в противном случаи некотрые приемы используются через 1 ход
*/

class priems
{	

	public function mg2static_points($uid,$st) {	
		global $u;
		if(isset($st['mg2static_points'])) {
			$mg = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$uid.'" AND `data` LIKE "%add_mg2static_points%" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
			if(isset($mg['id'])) {
				$mg['data'] = $u->lookStats($mg['data']);
				$mg['data']['add_mg2static_points']++;
				$mg['data']['add_zm2proc']--;
				$mg['x'] = $mg['data']['add_mg2static_points'];
				$mg['data'] = $u->impStats($mg['data']);
				mysql_query('UPDATE `eff_users` SET `data` = "'.$mg['data'].'",`x` = "'.$mg['x'].'" WHERE `id` = "'.$mg['id'].'" LIMIT 1');
			}
		}	
	}

	//отнимаем ману
	public function minMana($uid,$mp,$tp)
	{
		global $u,$btl;
		$r = true;
		/* уменьшаем расход маны, если $mp > 0 */
		//с вычетом уменьшения разсхода маныss
		$mp -= round($mp/100*$btl->stats[$btl->uids[$uid]]['min_use_mp']);
		$btl->stats[$btl->uids[$uid]]['mpNow'] -= $mp;
		if($btl->stats[$btl->uids[$uid]]['mpNow']<0)
		{
			$btl->stats[$btl->uids[$uid]]['mpNow'] = 0;
			$r = false;
		}elseif($btl->stats[$btl->uids[$uid]]['mpNow']>$btl->stats[$btl->uids[$uid]]['mpAll'])
		{
			$btl->stats[$btl->uids[$uid]]['mpNow'] = $btl->stats[$btl->uids[$uid]]['mpAll'];
		}
		
		mysql_query('UPDATE `stats` SET `mpNow` = "'.($btl->stats[$btl->uids[$uid]]['mpNow']).'" WHERE `id` = "'.((int)$uid).'" LIMIT 1');
		return $r;
	}
	
	//используем прием каждый ход	
	public function hodUsePriem($eff,$pr)
	{
		global $u,$btl,$c,$code;
		$return_main = true;
		$ue = mysql_fetch_array(mysql_query('SELECT
		
				`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`online`,`u`.`admin`,`u`.`city`,`u`.`cityreg`,`u`.`align`,`u`.`clan`,
				`u`.`level`,`u`.`money`,`u`.`money3`,`u`.`money4`,`u`.`battle`,`u`.`sex`,`u`.`obraz`,`u`.`win`,`u`.`win_t`,
				`u`.`lose`,`u`.`lose_t`,`u`.`nich`,`u`.`timeMain`,`u`.`invis`,`u`.`bot_id`,`u`.`animal`,`u`.`type_pers`,
				`u`.`notrhod`,`u`.`bot_room`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`activ`,`u`.`stopexp`,`u`.`real`,
										
				`st`.*
		
		FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id`=`st`.`id`) WHERE `u`.`id`="'.$eff['uid'].'" AND `u`.`battle`="'.$btl->info['id'].'" AND `st`.`hpNow` > 0 LIMIT 1'));
		if($pr['file']!='0')
		{
			if(file_exists('../../_incl_data/class/priems/'.$pr['file'].'.php'))
			{
				$hod = $eff['hod'];
				require('priems/'.$pr['file'].'.php');
			}
			if(!isset($cup))
			{
				//отнимаем тактики от приема
				//$this->mintr($pl);
			}
		}elseif($pr['file3']!='0')
		{
			if(file_exists('../../_incl_data/class/priems/'.$pr['file3'].'.php'))
			{
				$hod = $eff['hod'];
				require('priems/'.$pr['file3'].'.php');
			}
			if(!isset($cup))
			{
				//отнимаем тактики от приема
				//$this->mintr($pl);
			}
		}else{
			//какие-то другие эффекты
			
		}
		return $return_main;
	}
	
	public function redate($pl,$uid)
	{
		global $u,$btl;
		$i = 0;
		if($pl!='')
		{
			$e = explode('|',$pl);
			while($i<count($e))
			{
				$f = explode('=',$e[$i]);
				$f[1] = getdr($f[1],array(0=>'lvl1',1=>'ts5',2=>'mpAll'),array(0=>$btl->users[$btl->uids[$uid]]['level'],1=>$btl->stats[$btl->uids[$uid]]['s5'],2=>$btl->stats[$btl->uids[$uid]]['mpAll']));
				if($f[0]!='' && $f[1]!='')
				{
					$e[$i] = implode('=',$f);
				}
				$i++;	
			}
			$pl = implode('|',$e);
		}
		return $pl;
	}
	
	/* uid - на кого кастуем
	   pr - id приема
	   data - дата, если -1, то добавляем дату3
	   d2 - добавляем дату3
	   tm - время использования, 77 - вечно
	   h - кол-во "вечных" ходов
	   uu - id юзера который использовал
	   tp - тип приема
	*/
	public function addPriem($uid,$pr,$data,$d2,$tm,$h,$uu,$max,$bj,$tp = 0,$ch = 0,$rdt = 0,$tr_life_user = 0,$noupdatebtl = 0,$noplus = 0)
	{
		global $u,$btl;
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.((int)$pr).'" LIMIT 1'));
		//if($uid=='399105'){
		//print_r($pl);
		//}
		$r = false;
		if(isset($pl['id']))
		{
			if($data==-1)
			{				
				$data = $this->redate($pl['date3'],$u->info['id']);
			}elseif($d2==1)
			{
				$data .= '|'.$this->redate($pl['date3'],$u->info['id']);
			}

			if($pl['cancel_eff2']!='')
			{
				$i = 0; 
				$e = explode(',',$pl['cancel_eff2']);
				while($i<count($e))
				{
					if($e[$i]>0)
					{
						$nem = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$uid.'" AND `v1` = "priem" AND `v2` = "'.$e[$i].'" AND `delete` = "0" LIMIT 1'));
						if(isset($nem['id']))
						{
							$nem['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$e[$i].'" LIMIT 1'));
							if(isset($nem['id']))
							{
								$btl->delPriem($nem,$btl->users[$btl->uids[$uid]],2);
							}
						}
					}
				$i++;
				}
			}
			if($max>0)
			{
				if( $noplus == 0 ) {
					if($pl['zmu'] == 1) {
						$num = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `bj` = "'.$bj.'" AND `user_use` = "'.$u->info['id'].'" AND `uid` = "'.$uid.'" AND `delete` = "0" LIMIT 1'));
					}else{
						$num = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `bj` = "'.$bj.'" AND `uid` = "'.$uid.'" AND `delete` = "0" LIMIT 1'));				
					}
				}
				
				if(isset($num['id']) && ($num['user_use']!=$u->info['id'] && $pl['zmu'] != 2))
				{
					// удаляем эффект
					mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$num['id'].'" LIMIT 1');
					if(isset($num['id']))
					{
						$i = 0;
						while($i<count($btl->stats[$btl->uids[$uid]]['effects']))
						{
							if($btl->stats[$btl->uids[$uid]]['effects'][$i]['id']==$num['id'])
							{
								//обновляем
								$btl->stats[$btl->uids[$uid]]['effects'][$i]['delete'] = time();
							}
							$i++;
						}
					}
					unset($num);
				}
				
				if(!isset($num['id']))
				{
					$ins = mysql_query('INSERT INTO `eff_users` (`tr_life_user`,`bj`,`user_use`,`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`) VALUES ("'.floor($tr_life_user).'","'.$bj.'","'.$uu.'","'.$h.'",'.$pl['id'].',"'.$pl['img'].'.gif",22,"'.$uid.'","'.$pl['name'].'","'.$data.'","0","'.$tm.'","priem")');
					if($ins)
					{
						$r = true;	
						$lid = mysql_insert_id();
					}
					/* добавляем данные к $btl->eff */
					if( $noupdatebtl == 0 ) {
						$btl->stats[$btl->uids[$uid]] = $u->getStats($uid,0);
					}
					
				}elseif($num['x']<$max)
				{
					//Добавляем еще и обновляем заряды
					$num['x']++; $num['hod'] = $h;
					if( $data != -1 && $data != '' && $d2 == 2 ) {
						$num['data'] .= '|'.$data.'';
						$upd = mysql_query('UPDATE `eff_users` SET `x` = `x` + 1,`hod` = "'.$h.'",`data` = "'.$num['data'].'" WHERE `id` = "'.$num['id'].'" LIMIT 1');	
					}else{
						$upd = mysql_query('UPDATE `eff_users` SET `x` = `x` + 1,`hod` = "'.$h.'" WHERE `id` = "'.$num['id'].'" LIMIT 1');	
					}
					if($upd)
					{
						$r = true;
					}	
				}else{
					//обновляем заряды
					$num['hod'] = $h;
					if( $data != -1 && $data != '' && $d2 == 2 ) {
						$num['data'] .= '|'.$data.'';
						$upd = mysql_query('UPDATE `eff_users` SET `hod` = "'.$h.'",`data` = "'.$num['data'].'" WHERE `id` = "'.$num['id'].'" LIMIT 1');	
					}else{
						$upd = mysql_query('UPDATE `eff_users` SET `hod` = "'.$h.'" WHERE `id` = "'.$num['id'].'" LIMIT 1');	
					}					if($upd)
					{
						$r = true;	
					}
				}
				
				if($r==true)
				{
					//cancel_eff был здесь
					if($pl['cancel_eff']!='')
					{
						$i = 0; 
						$e = explode(',',$pl['cancel_eff']);
						while($i<count($e))
						{
							if($e[$i]>0)
							{
								$nem = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$uid.'" AND `v1` = "priem" AND `v2` = "'.$e[$i].'" AND `delete` = "0" LIMIT 1'));
								if(isset($nem['id']))
								{
									$nem['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$e[$i].'" LIMIT 1'));
									if(isset($nem['id']))
									{
										$btl->delPriem($nem,$btl->users[$btl->uids[$uid]],2);
									}
								}
							}
						$i++;
						}
					}
				}
				
				/*if($ch==1)
				{
					$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$uid]]['sex'].'||t2='.$btl->users[$btl->uids[$uid]]['team'].'||login2='.$btl->users[$btl->uids[$uid]]['login'].'';
					$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
					if($tp > 0) {
						$tco = array(1=>'006699',2=>'006699',3=>'006699',4=>'006699'); //не крит
						$tcl = array(1=>'A00000',2=>'008080',3=>'0000FF',4=>'A52A2A'); //не крит
						$tco = $tco[$tp];
						$tcl = $tcl[$tp];
						$nmz = array(
							0=>array(0=>'хаоса',1=>'хаос'),
							1=>array(0=>'огня',1=>'огненный'),
							2=>array(0=>'воздуха',1=>'электрический'),
							3=>array(0=>'воды',1=>'холод'),
							4=>array(0=>'земли',1=>'земляной'),
							5=>array(0=>'Света',1=>'свет'),
							6=>array(0=>'Тьмы',1=>'тьма'),
							7=>array(0=>'нейтралитета',1=>'серая&nbsp;магия')
						);
						$nmz = $nmz[$tp];
						$mas1['text'] = '{tm1} {u1} {1x16x0} заклинание магии '.$nmz[0].' &quot;<b><font color=#'.$tcl.'>'.$pl['name'].'</font></b>&quot;';	
					}else{
						//$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;';
						//$btl->priemAddLogFast($u->info['id'],0,$pl['name'],'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'',0,time());	
					}
					if($u->info['id']!=$uid)
					{
						$mas1['text'] .= ' на персонажа {u2}.';
					}else{
						$mas1['text'] .= '.';
					}
					$btl->add_log($mas1);
				}*/
				
				if(isset($num['id']))
				{
					$i = 0;
					while($i<count($btl->stats[$btl->uids[$uid]]['effects']))
					{
						if($btl->stats[$btl->uids[$uid]]['effects'][$i]['id']==$num['id'])
						{
							//обновляемss
							$btl->stats[$btl->uids[$uid]]['effects'][$i]['data'] = $num['data'];	
							$btl->stats[$btl->uids[$uid]]['effects'][$i]['hod'] = $num['hod'];
							$btl->stats[$btl->uids[$uid]]['effects'][$i]['x'] = $num['x'];
						}
						$i++;
					}
				}
			}
		}
		return $r;
	}
	
	public function lookStatsArray($m)
	{
		$ist = array();
		$di = explode('|',$m);
		$i = 0; $de = false;
		while($i<count($di))
		{
			$de = explode('=',$di[$i]);
			if(isset($de[0],$de[1]))
			{
				if(!isset($ist[$de[0]])) {
					$ist[$de[0]] = array();
				}
				$ist[$de[0]][] = $de[1];
			}
			$i++;
		}
		return $ist;
	}

	public function magicRegen($ue,$hpmin,$tmp,$pl,$eff,$rp = 0,$dp = 0,$krituet=true,$dopyrn=0)
	{
		global $u,$c,$code,$btl;
		$rr = array();
		$uen = $ue['id'];
		$usu = $eff['user_use'];
		if($eff['user_use']<1)
		{
			$usu = $u->info['id'];
		}
		$k = $btl->magKrit($ue['level'],$btl->stats[$btl->uids[$usu]]['mg'.$tmp]);
		
		if($krituet==false){$k=0;}
		
		$hpmin = $this->testPower($btl->stats[$btl->uids[$usu]],$btl->stats[$btl->uids[$uen]],$hpmin,$tmp,2);
		$hpmin = round($hpmin);
		
		$dopyrn = $this->testPower($btl->stats[$btl->uids[$usu]],$btl->stats[$btl->uids[$uen]],$dopyrn,$tmp,2);
		$dopyrn = round($dopyrn);
		
		if($btl->users[$btl->uids[$uen]]['tactic7']<=0 && $dp==0)
		{
			$hpmin = 0; $k = -1;
			$dopyrn = 0;
		}
		if($k==1 && $hpmin!=0 && $krituet==true)
		{
			//крит
			$hpmin = $hpmin*2; 
		}elseif($k==-1 && $hpmin!=0)
		{
			//промах
			$hpmin = $hpmin/2; 
			$dopyrn = $dopyrn/2;
		}
		if($hpmin<1){ $hpmin = 0; }else{
			$hpmin = rand(($hpmin*0.97),$hpmin);	
		}
		
		$hpmin += floor($dopyrn);
		
		if(isset($btl->stats[$btl->uids[$uen]]['min_heal_proc'])) {
			if($btl->stats[$btl->uids[$uen]]['min_heal_proc'] > 100) {
				$btl->stats[$btl->uids[$uen]]['min_heal_proc'] = 100;
			}
			$hpmin = round($hpmin/100*(100+$btl->stats[$btl->uids[$uen]]['min_heal_proc']));
		}
		
		if($btl->users[$btl->uids[$uen]]['tactic7']>0 && $dp==0)
		{
			//Отнимаем тактики, если это возможно
			$btl->users[$btl->uids[$uen]]['tactic7'] -= $hpmin/$btl->stats[$btl->uids[$uen]]['hpAll'];
			$btl->users[$btl->uids[$uen]]['tactic7'] = round($btl->users[$btl->uids[$uen]]['tactic7'],2);
			$btl->stats[$btl->uids[$uen]]['tactic7'] = $btl->users[$btl->uids[$uen]]['tactic7'];
			if($uen==$u->info['id'])
			{
				$u->info['tactic7'] = $btl->users[$btl->uids[$uen]]['tactic7'];
				$u->stats['tactic7'] = $btl->users[$btl->uids[$uen]]['tactic7'];
			}
			if($btl->users[$btl->uids[$uen]]['tactic7']<0)
			{
				$btl->users[$btl->uids[$uen]]['tactic7']  = 0;
			}
		}
		$hp2 = floor($btl->stats[$btl->uids[$uen]]['hpNow'] + $hpmin);
		
		if($hp2 > $btl->stats[$btl->uids[$uen]]['hpAll'])
		{
			$hpmin = floor($hp2-$btl->stats[$btl->uids[$uen]]['hpAll']);
			$hp2 = $btl->stats[$btl->uids[$uen]]['hpAll'];
		}elseif($hp2<0)
		{
			$hp2 = 0;
		}
		$rr[0] = $hpmin; //урон
		$rr[1] = $k; //тип
		/* проверяем приемы защиты */
			//получаем массив с приемами противника
			$miny = 0; //на сколько едениц урон буде меньше (защита приема)
			$minu = 0;
			$sp1 = mysql_query('SELECT `e`.* FROM `eff_users` AS `e` WHERE `e`.`uid` = "'.$uen.'" AND `e`.`id_eff` = "22" AND `e`.`delete` = "0" AND `e`.`v1` = "priem" LIMIT 25');
			while($pl2 = mysql_fetch_array($sp1))
			{
				$pl2['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pl2['v2'].'" LIMIT 1'));
				if(isset($pl2['priem']['id']))
				{
					$dt1 = $u->lookStats($pl2['priem']['date2']);
					if(isset($dt1['yron_u2']))
					{
						$minu = getdr($dt1['yron_u2'],array(0=>'lvl1',1=>'yr1',2=>'ts5',3=>'ts6'),array(0=>$btl->users[$btl->uids[$level]],1=>$hpmin,2=>0,3=>0));
						$miny -= $minu;
						$hpmin += $minu;
						$btl->delPriem($pl2,$btl->users[$btl->uids[$uen]]);	
					}
				}
				
			}
					
		/* проверяем приемы ослабления */
		
		//отнимаем НР
		$btl->users[$btl->uids[$uen]]['hpNow'] = $hp2;
		$btl->stats[$btl->uids[$uen]]['hpNow'] = $hp2;
		$upd = mysql_query('UPDATE `stats` SET `hpNow` = '.$hp2.',`tactic7` = '.$btl->users[$btl->uids[$uen]]['tactic7'].' WHERE `id` = "'.$uen.'" LIMIT 1');
		
		//заносим в лог боя
		$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$uen]]['sex'].'||t2='.$btl->users[$btl->uids[$uen]]['team'].'||login2='.$btl->users[$btl->uids[$uen]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		if($rp==1)
		{
			$mas1['id_hod']--;
		}
		//$btl->takeExp($u->info['id'],$hpmin,$u->info['id'],$uen);
		
		$btl->takeExp($u->info['id'],($hpmin*0.33),$u->info['id'],$uen,true);
		
		if($hpmin>0)
		{
			$hpmin = '+'.ceil($hpmin);
		}else{
			$hpmin = '--';
		}
		$tco = array(1=>'006699',2=>'006699',3=>'006699',4=>'006699'); //не крит
		$tcl = array(1=>'A00000',2=>'008080',3=>'0000FF',4=>'A52A2A'); //не крит
		$tco = $tco[$tmp];
		$tcl = $tcl[$tmp];
		if($k==1)
		{
			//крит
			$tco = 'FF0000';
			$tcl = 'FF0000';
		}elseif($k==-1)
		{
			//промах
			$tco = '979797';
			$tcl = '979797'; 
		}
		$nmz = array(
			1=>array(0=>'огня',1=>'огненная'),
			2=>array(0=>'воздуха',1=>'электрическая'),
			3=>array(0=>'воды',1=>'водная'),
			4=>array(0=>'земли',1=>'земляная')
			);
		$nmz = $nmz[$tmp];
		
		if($rp==1)
		{
			if($k==0)
			{
				//$tcl = '000000';
				//$tco = '008000';
			}
			$sx = array(0=>'',1=>'а');
			$mas1['text'] = '{tm1} Заклинание &quot;<b><font color=#'.$tcl.'>'.$pl['name'].'</font></b>&quot; восстановило здоровье персонажа {u2}. <b><font title=Тип&nbsp;регенерации:&nbsp;'.$nmz[1].' color=#'.$tco.'>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$btl->stats[$btl->uids[$uen]]['hpAll'].']';
		}else{
			$mas1['text'] = '{tm1} {u1} {1x16x0} заклинание &quot;<b><font color=#'.$tcl.'>'.$pl['name'].'</font></b>&quot; и восстановил здоровье персонажа {u2} магией '.$nmz[0].'. <b><font title=Тип&nbsp;регенерации:&nbsp;'.$nmz[1].' color=#'.$tco.'>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$btl->stats[$btl->uids[$uen]]['hpAll'].']';	
		}
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;	
		return $rr;
	}

	public $cof_mag = array(
		0  => 250,
		1  => 250,
		2  => 250,
		3  => 250,
		4  => 250,
		5  => 250,
		6  => 250,
		7  => 250,
		8  => 250,
		9  => 300,
		10 => 360,
		11 => 475,
		12 => 520,
		13 => 625,
		14 => 750,
		15 => 895,
		16 => 1075,
		17 => 1290,
		18 => 1550,
		19 => 1860,
		20 => 2230,
		21 => 2675
	);
	public function magatack( $u1, $u2, $yron, $type, $krit ) {
		global $btl;
		$r = $yron;
		//
		$prm = array(
			'y' => $btl->stats[$btl->uids[$u1]]['mg'.$btl->mname[$type]], //умелки
			'yv' => 0, //умения, значение коф.
			'max_krit' => 0 //вероятность крита
		);
		//
		// (уровень цени)*2 - 7 - минимальное умелок, чтобы не было промахов
		/*
		Для магии Света/Тьмы по формуле: Уровень Цели * 2 – 9 
		каждая умелка выше этой нормы увеличивает маг крит на 3%. но не больше 30%
		*/
		//Рассчет урона от приема
		/*
		b - базовый урон
		m - мощь
		z - защита цели [ед.]
		p - подавление [ед.]
		k - коэффициент ; k=250 для 8ки, k=300 для 9ки и т.д. +20% на уровень
		*/
		$prm['b'] = round($r,2); //базовый урон
		$prm['m'] = $btl->stats[$btl->uids[$u1]]['pm'.$btl->mname[$type]]; //мощь
		$prm['z'] = $btl->stats[$btl->uids[$u2]]['zm'.$btl->mname[$type]]; //защита цели (ед.)
		if( $prm['z'] < 0 ) {
			$prm['z'] = 0;
		}
		$prm['p'] = $btl->stats[$btl->uids[$u1]]['pzm'.$btl->mname[$type]]+$btl->stats[$btl->uids[$u1]]['pzm']; //подавление (ед.)
		$prm['k'] = $this->cof_mag[$btl->users[$btl->uids[$u2]]['level']]; //коэффицент
		if( $prm['k'] == 0 ) {
			$prm['k'] = 1;
		}
		//
		/*if( $prm['p']*10 > $prm['k'] ) {
			$prm['p'] = round($prm['k']/10);
		}*/
		if( $prm['p']*10 > $prm['z']+$prm['k'] ) {
			$prm['p'] = round(($prm['z']+$prm['k'])/10);
		}
		
		//echo '[Мощность '.$prm['m'].'%, Подавление '.$prm['p'].' ед., Защита цели '.$prm['z'].' ед., Коэффицент '.$prm['k'].']';
		
		//$prm['p'] = round($prm['p']*2);
		
		//$r = $prm['b']*(1+$prm['m']/100)*pow(2,(($prm['p']*10-$prm['z'])/$prm['k'])); (верная старая)
		//$r = $prm['b']*(1+$prm['m']/100)*pow(2,((0-($prm['z']-$prm['p']*10))/$prm['k'])); (хз какая, старая)
		//
		$prm['znew'] = ( ( $prm['z'] / 100) * ( 100 - $prm['p'] ) ) - 5 * $prm['p'];
		//
		//Занижаем мощность на 10% - убрал временно занижение.
		$r = ($prm['b']*((1+$prm['m']/100)))/100*(100-$btl->zmgo($prm['znew']));
		//echo '['.$prm['b'].'*(1+'.$prm['m'].'/100)*pow(2,(('.$prm['p'].'*10-'.$prm['z'].')/'.$prm['k'].'));]';
		
		//$r += floor($btl->stats[$btl->uids[$u1]]['s5']*0.25);
		if( $r < floor($prm['b']*0.2) ) {
			//$r = floor($prm['b']*0.2);
		}elseif( $r > floor($prm['b']*10) ) {
			//$r = floor($prm['b']*10);
		}
		//
		//$prm['y'] -= 5;
		if( $type < $btl->mname[$type] ) {
			$prm['yv'] = ($btl->users[$btl->uids[$u2]]['level'] * 2 - 7);
		}else{
			$prm['yv'] = ($btl->users[$btl->uids[$u2]]['level'] * 2 - 9);
		}
		//
		if( $prm['y'] >= $prm['yv'] || $btl->stats[$btl->uids[$u1]]['acestar'] > 0 ) {
			if( $krit == 1 ) {
				$prm['max_krit'] = 3 * ( $prm['y'] - $prm['yv'] );
				if( $prm['max_krit'] < 0 ) {
					$prm['max_krit'] = 0;
					//Утсноавил макс крит 25 процентов
				}elseif( $prm['max_krit'] > 25 ) {
					$prm['max_krit'] = 25;
				}
				//$prm['max_krit'] = round($prm['max_krit']/2);
				//Крит возможен
				
				if( $btl->stats[$btl->uids[$u1]]['acestar'] ) {
					//Крит 100%
					$prm['max_krit'] = 100;
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$u1.'" AND `data` LIKE "%add_acestar=%" AND `delete` = 0 LIMIT 1');
				}
				
				if( $btl->get_chanse($prm['max_krit']) == true ) {
					$krit = true;
				}else{
					$krit = false;
				}
			}else{
				$krit = false;
			}
			$promah = false;
		}else{
			$krit = false;
			//Вероятность промоха
			$prm['promah'] = 3 * ( $prm['yv'] - $prm['y'] );
			if( $prm['promah'] < 0 ) {
				$prm['promah'] = 0;
			}elseif( $prm['promah'] > 30 ) {
				$prm['promah'] = 30;
			}
			if( $btl->get_chanse($prm['promah']) == true ) {
				$promah = true;
			}else{
				$promah = false;
			}
		}
		//
		if( $krit == true ) {
			$r = $r*2;
			$promah_type = 0;
		}elseif( $promah == true ) {
			$r = rand(1,floor($r/4));
			$promah_type = 1;
			if( rand(0,100) < 50 ) {
				$r = 0;
				$promah_type = 2;
			}
		}
		//$r = floor(1+$r*0.90);
		if($r < 1 ) {
			$r = 1;
		}
		//
		unset($prm);
		//
		return array( floor($r) , $krit , $promah , $promah_type );
	}
	
	public function magatackfiz( $u1, $u2, $yron, $type, $krit , $ymelki ) {
		global $btl;
		$r = $yron;
		//
		if( !isset($ymelki) || $ymelki == '0' ) {
			$ymelki = $type;
		}
		//
		$prm = array(
			'ym' => $btl->stats[$btl->uids[$u1]]['mg'.$btl->mname[$ymelki]], //умелки (магические умелки)
			'y' => $btl->stats[$btl->uids[$u1]]['a'.$btl->mname[$ymelki]], //умелки
			'yv' => 0, //умения, значение коф.
			'max_krit' => 0 //вероятность крита
		);
		//
		// (уровень цени)*2 - 7 - минимальное умелок, чтобы не было промахов
		/*
		Для магии Света/Тьмы по формуле: Уровень Цели * 2 – 9 
		каждая умелка выше этой нормы увеличивает маг крит на 3%. но не больше 30%
		*/
		//Рассчет урона от приема
		/*
		b - базовый урон
		m - мощь
		z - защита цели [ед.]
		p - подавление [ед.]
		k - коэффициент ; k=250 для 8ки, k=300 для 9ки и т.д. +20% на уровень
		*/
		$prm['b'] = $r; //базовый урон
		$prm['m'] = $btl->stats[$btl->uids[$u1]]['pa'.$btl->mname[$type]]; //мощь
		$prm['z'] = $btl->stats[$btl->uids[$u2]]['za'.$btl->mname[$type]]; //защита цели (ед.)
		$prm['p'] = $btl->stats[$btl->uids[$u1]]['pza'.$btl->mname[$type]]; //подавление (ед.)
		$prm['k'] = $this->cof_mag[$btl->users[$btl->uids[$u1]]['level']]; //коэффицент
		//
		if( $prm['p']*10 > $prm['k'] ) {
			$prm['p'] = floor($prm['k']/10);
		}
		//
		$r = $prm['b']*(1+$prm['m']/100)*pow(2,(($prm['p']*10-$prm['z'])/$prm['k']));
		if( $r < floor($prm['b']*0.2) ) {
			$r = floor($prm['b']*0.2);
		}elseif( $r > floor($prm['b']*10) ) {
			$r = floor($prm['b']*10);
		}
		//
		//$prm['y'] -= 5;
		if( $type < $btl->mname[$type] ) {
			$prm['yv'] = ($btl->users[$btl->uids[$u2]]['level'] * 2 - 7);
		}else{
			$prm['yv'] = ($btl->users[$btl->uids[$u2]]['level'] * 2 - 9);
		}
		//
		if( $prm['y'] >= $prm['yv'] || (isset($btl->mname[$ymelki]) && $prm['ym'] >= $prm['yv']) ) {
			if( $krit == 1 ) {
				if( isset($btl->mname[$ymelki]) ) {
					$prm['max_krit'] = 3 * ( $prm['ym'] - $prm['yv'] );
				}else{
					$prm['max_krit'] = 3 * ( $prm['y'] - $prm['yv'] );
				}
				//echo '[Magical crit: '.$prm['max_krit'].'%]';
				if( $prm['max_krit'] < 0 ) {
					$prm['max_krit'] = 0;
				}elseif( $prm['max_krit'] > 30 ) {
					$prm['max_krit'] = 30;
				}
				//$prm['max_krit'] = round($prm['max_krit']/2);
				//Крит возможен
				if( rand( 0 , 100 ) <= $prm['max_krit'] ) {
					$krit = true;
				}else{
					$krit = false;
				}
			}else{
				$krit = false;
			}
			$promah = false;
		}else{
			$krit = false;
			//Вероятность промоха
			$prm['promah'] = 3 * ( $prm['yv'] - $prm['ym'] );
			if( $prm['promah'] < 0 ) {
				$prm['promah'] = 0;
			}elseif( $prm['promah'] > 30 ) {
				$prm['promah'] = 30;
			}
			if( rand( 0 , 100 ) <= $prm['promah'] ) {
				$promah = true;
			}else{
				$promah = false;
			}
		}
		//
		if( $krit == true ) {
			$r = $r*2;
			$promah_type = 0;
		}elseif( $promah == true ) {
			$r = rand(1,floor($r/4));
			$promah_type = 1;
			if( rand(0,100) < 50 ) {
				$r = 0;
				$promah_type = 2;
			}
		}
		//
		unset($prm);
		//
		return array( floor($r) , $krit , $promah , $promah_type );
	}

	public function magicAtack($ue,$hpmin,$tmp,$pl,$eff,$rp = 0,$mxx = 0,$fiz = 0,$nomf = 0,$krituet=true,$heal =0,$namenew=NULL)
	{
		$trawm_off=false;
		global $u,$c,$code,$btl;
		if( $namenew != NULL ) {
			$pl['name'] = $namenew;
		}
		$rr = array();
		$nhpmin = $hpmin;
		$uen = $ue['id'];
		$usu = $eff['user_use'];
		if($eff['user_use']<1)
		{
			$usu = $u->info['id'];
		}
		if($nomf==0)
		{
			$k = $btl->magKrit($ue['level'],$btl->stats[$btl->uids[$usu]]['mg'.$tmp]);
			if($krituet==false){$k=0;}
			if($fiz==0)
			{
		
				//магический урон
				if($nomf == 0) {
					$hpmin = $this->testPower($btl->stats[$btl->uids[$usu]],$btl->stats[$btl->uids[$uen]],$hpmin,$tmp,2);
				}
			}else{
				//физический урон
				$wAp += $btl->stats[$btl->uids[$usu]]['pa'.$tmp.''];
				$wAp += $btl->stats[$btl->uids[$usu]]['m10'];
				$wAp -= $btl->stats[$btl->uids[$uen]]['antpa'.$tmp.'']*1.75;
				$wAp -= $btl->stats[$btl->uids[$uen]]['antm10']*1.75;
				$hpmin += ceil((0.01+$hpmin/100)*(0.01+0.98*$wAp))-1;
				
				$hpmin -= round(  $hpmin/100*(35*($btl->stats[$btl->uids[$uen]]['za']+$btl->stats[$btl->uids[$uen]]['za'.$tmp])/1200) );
				$hpmin = round($hpmin);
				
				if(isset($btl->stats[$btl->uids[$uen]]['zaproc']) || isset($btl->stats[$btl->uids[$uen]]['za'.$fiz.'proc'])) //защита от урона (призрачки)
				{
					$hpmin = floor($hpmin/100*(100-$btl->stats[$btl->uids[$uen]]['zaproc']-$btl->stats[$btl->uids[$uen]]['za'.$fiz.'proc']));
					if($hpmin<0)
					{
						$hpmin = 0;
					}
				}
			}
		}
		$hpmin = round($hpmin);
		if($k==1 and $krituet==true)
		{
			//крит
			$hpmin = $hpmin*2; 
		}elseif($k==-1)
		{
			//промах
			$hpmin = $hpmin/2; 
		}
		if($hpmin<$nhpmin*0.2) {
			$hpmin = $nhpmin*0.2;
		}
		if($hpmin<1){ $hpmin = 0; }else{
			if($nomf == 0) {
				$hpmin = rand(($hpmin*0.97),$hpmin);
			}
		}
		if($mxx>0 && $hpmin > $mxx)
		{
			if($k==0)
			{
				$hpmin = $mxx;
			}elseif($k==1 && $hpmin/2 > $mxx)
			{
				$hpmin = $mxx*2;	
			}
		}
		$rr[0] = $hpmin; //урон
		$rr[1] = $k; //тип
		/* проверяем приемы защиты */
			//получаем массив с приемами противника
			$miny = 0; //на сколько едениц урон буде меньше (защита приема)
			$minu = 0;
			$sp1 = mysql_query('SELECT `e`.* FROM `eff_users` AS `e` WHERE `e`.`uid` = "'.$uen.'" AND `e`.`id_eff` = "22" AND `e`.`delete` = "0" AND `e`.`v1` = "priem" LIMIT 25');
			while($pl2 = mysql_fetch_array($sp1))
			{
				$pl2['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pl2['v2'].'" LIMIT 1'));
				if(isset($pl2['priem']['id']))
				{
					$dt1 = $u->lookStats($pl2['priem']['date2']);
					if(isset($dt1['yron_u2']))
					{
						$minu = getdr($dt1['yron_u2'],array(0=>'lvl1',1=>'yr1',2=>'ts5',3=>'ts6'),array(0=>$btl->users[$btl->uids[$level]],1=>$hpmin,2=>1,3=>0));
						$miny -= $minu;
						$hpmin += $minu;
						if(isset($dt1['rzEndMg']) && $dt1['rzEndMg']==1)
						{
							$btl->delPriem($pl2,$btl->users[$btl->uids[$uen]]);	
						}
					}elseif(isset($dt1['rzEndMg']) && $dt1['rzEndMg']==1) {
						$btl->delPriem($pl2,$btl->users[$btl->uids[$uen]]);	
					}
				}
				
			}
			
		$hpmin = $btl->testPogB($uen,$hpmin);	
		
		$hp2 = floor($btl->stats[$btl->uids[$uen]]['hpNow'] - $hpmin);
		
		if($btl->stats[$btl->uids[$usu]]['yrnhealmpprocmg'.$tmp] > 0 && $fiz == 0) {
			//Часть урона восставнавливает ману
			$btl->stats[$btl->uids[$usu]]['mpNow'] += round($hpmin/100*$btl->stats[$btl->uids[$usu]]['yrnhealmpprocmg'.$tmp]);
			//if($btl->stats[$btl->uids[$usu]]['mpNow'] > $btl->stats[$btl->uids[$usu]]['mpAll']) {
				//$btl->stats[$btl->uids[$usu]]['mpNow'] = $btl->stats[$btl->uids[$usu]]['mpAll'];
			//}
			$btl->users[$btl->uids[$usu]]['mpNow'] = $btl->stats[$btl->uids[$usu]]['mpNow'];
			if($usu == $u->info['id']) {
				$u->info['mpNow'] = $btl->stats[$btl->uids[$usu]]['mpNow'];
				$u->stats['mpNow'] = $btl->stats[$btl->uids[$usu]]['mpNow'];
			}
		}
		
		if($hp2<0)
		{
			$hp2 = 0;	
		}elseif($hp2>$btl->stats[$btl->uids[$uen]]['hpAll'])
		{
			$hp2 = $btl->stats[$btl->uids[$uen]]['hpAll'];	
		}
		
		$btl->stats[$btl->uids[$uen]]['last_hp'] = -floor($hpmin);
			
		if($heal != 0) {
			if($heal == -1) {
				//хил на текущий урон с учетом мф
				$btl->stats[$btl->uids[$eff['user_use']]]['hpNow'] += $hpmin;
				if($btl->stats[$btl->uids[$eff['user_use']]]['hpNow'] < 0) {
					$btl->stats[$btl->uids[$eff['user_use']]]['hpNow'] = 0;
				}elseif($btl->stats[$btl->uids[$eff['user_use']]]['hpNow'] > $btl->stats[$btl->uids[$eff['user_use']]]['hpAll']) {
					$btl->stats[$btl->uids[$eff['user_use']]]['hpNow'] = $btl->stats[$btl->uids[$eff['user_use']]]['hpAll'];
				}
				
				if($eff['user_use'] == $u->info['id']) {
					$u->stats['hpNow'] = $btl->stats[$btl->uids[$eff['user_use']]]['hpNow'];
				}
				
				$btl->users[$btl->uids[$eff['user_use']]]['hpNow'] = $btl->stats[$btl->uids[$eff['user_use']]]['hpNow'];
				
				$upd = mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$eff['user_use']]]['hpNow'].'" WHERE `id` = "'.$eff['user_use'].'" LIMIT 1');
			}else{
				//хил на конкретное число
				
			}
		}
					
		/* проверяем приемы ослабления */

		//отнимаем НР
		$btl->users[$btl->uids[$uen]]['hpNow'] = $hp2;
		$btl->stats[$btl->uids[$uen]]['hpNow'] = $hp2;
		
		if($uen == $u->info['id']) {
			$u->stats['hpNow'] = $hp2;
		}
		
		// тяж травма для кровавых
		if($btl->info['type']==99 and $hp2==0 and $trawm_off==false){
		//$eff['user_use']
		//$sp1 = mysql_query('SELECT `e`.* FROM `eff_users` AS `e` WHERE `e`.`uid` = "'.$uen.'" AND `e`.`id_eff` = "22" AND `e`.`delete` = "0" AND `e`.`v1` = "priem" LIMIT 25');

								    $trawm_off=true;
								    //$at[2][$i]['ttravm']='получил <font color=red><b>Тяжелую травму</b></font>.';
									$btl->addTravm($btl->users[$btl->uids[$uen]]['id'],3,$btl->users[$btl->uids[$eff['user_use']]]['level']);
								}
		$upd = mysql_query('UPDATE `stats` SET `hpNow` = '.$hp2.',`last_hp` = "'.$btl->stats[$btl->uids[$uen]]['last_hp'].'" WHERE `id` = "'.$uen.'" LIMIT 1');
		
		//заносим в лог боя
		$vLog = 'time1='.time().'||s1='.$btl->users[$btl->uids[$usu]]['sex'].'||t1='.$btl->users[$btl->uids[$usu]]['team'].'||login1='.$btl->users[$btl->uids[$usu]]['login'].'||s2='.$btl->users[$btl->uids[$uen]]['sex'].'||t2='.$btl->users[$btl->uids[$uen]]['team'].'||login2='.$btl->users[$btl->uids[$uen]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		if($rp>0)
		{
			$mas1['id_hod']--;
		}
		$hpminkrit = 0;
		if( $k == 1 ) {
			$hpminkrit = $hpmin;
		}
		$btl->addNewStat( array(
			'battle'	=> $u->info['battle'],
			'uid1'		=> $u->info['id'],
			'uid2'		=> $uen,
			'time'		=> time(),
			'type'		=> 0,
			'a'			=> '10000',
			'b'			=> 0,
			'type_a'	=> 1,
			'type_b'	=> 0,
			'ma'		=> 1,
			'mb'		=> 1,
			'yrn'		=> $hpmin,
			'yrn_krit'	=> $hpminkrit,
			'tm1'		=> $u->info['team'],
			'tm2'		=> $btl->users[$btl->uid[$uen]]['team']
		) );
		
		$btl->takeExp($u->info['id'],$hpmin,$usu,$uen);
		
		if($hpmin>0)
		{
			$hpmin = '-'.ceil($hpmin);
		}else{
			$hpmin = '--';
		}
		$tco = array(1=>'006699',2=>'006699',3=>'006699',4=>'006699',5=>'006699',6=>'006699',7=>'006699'); //не крит
		$tcl = array(1=>'A00000',2=>'008080',3=>'0000FF',4=>'A52A2A',5=>'006699',6=>'006699',7=>'006699'); //не крит
		$tco = $tco[$tmp];
		$tcl = $tcl[$tmp];
		if($k==1)
		{
			//крит
			$tco = 'FF0000';
			$tcl = 'FF0000';
		}elseif($k==-1)
		{
			//промах
			$tco = 'CCCCCC';
			$tcl = 'CCCCCC'; 
		}
		$nmz = array(
			1=>array(0=>'огня',1=>'огненный'),
			2=>array(0=>'воздуха',1=>'электрический'),
			3=>array(0=>'воды',1=>'холод'),
			4=>array(0=>'земли',1=>'земляной'),
			5=>array(0=>'Свет',1=>'Свет'),
			6=>array(0=>'Тьма',1=>'Тьма'),
			7=>array(0=>'Серая&nbsp;магия',1=>'Серая&nbsp;магия')
			);
		$nmz = $nmz[$tmp];
		if($fiz>0)
		{
			$nmz = array(
			1=>array(0=>', колющая атака , ',1=>'колющий'),
			2=>array(0=>', рубящая атака , ',1=>'рубящий'),
			3=>array(0=>', дробящая атака , ',1=>'дробящий'),
			4=>array(0=>', режущая атака , ',1=>'режущий')
			);
			$nmz = $nmz[$fiz];
		}
		
		if($rp==1)
		{
			if($k==0)
			{
				$tcl = '000000';
				$tco = '008000';
			}
			$sx = array(0=>'',1=>'а');
			$mas1['text'] = '{tm1} {u2} утратил'.$sx[$btl->users[$btl->uids[$uen]]['sex']].' здоровье от &quot;<b><font color=#'.$tcl.'>'.$pl['name'].'</font></b>&quot;. <b><font title=Тип&nbsp;урона:&nbsp;'.$nmz[1].' color=#'.$tco.'>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$btl->stats[$btl->uids[$uen]]['hpAll'].']';
		}else{
			if( $fiz == 1 ) {
				$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b><font color=#'.$tcl.'>'.$pl['name'].'</font></b>&quot; и поразил {u2}. <b><font title=Тип&nbsp;урона:&nbsp;'.$nmz[1].' color=#'.$tco.'>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$btl->stats[$btl->uids[$uen]]['hpAll'].']';
			}else{
				$mas1['text'] = '{tm1} {u1} {1x16x0} заклинание &quot;<b><font color=#'.$tcl.'>'.$pl['name'].'</font></b>&quot; и поразил магией '.$nmz[0].' {u2}. <b><font title=Тип&nbsp;урона:&nbsp;'.$nmz[1].' color=#'.$tco.'>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$btl->stats[$btl->uids[$uen]]['hpAll'].']';	
			}
		}
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;	
		return $rr;
	}
	
	public function testActiv($id)
	{
		global $u;
		$r = 0;
		if($u->info['admin'] > 0 || $u->info['nadmin'] > 0) {
			$r = 1;
		}else{
			$tst = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` < '.time().' AND `vars` = "read" AND `vals` = "'.$id.'" LIMIT 1',1);
			if(isset($tst['id']))
			{
				$r = 1;
			}
			unset($tst);
		}
		return $r;
	}
	
	public function testRazmenOldUser( $u2 , $u1 ) {
		global $btl,$u;
		$r = 0;
		//Уровень -противника- ниже уровня -цели-
		if( $btl->users[$btl->uids[$u2]]['id'] != $u->info['id'] ) {
			if( $btl->users[$btl->uids[$u1]]['level'] < $btl->users[$btl->uids[$u2]]['level'] ) {
				$r = 1;
				echo '<center><b><font color=red>Нельзя кастовать через слабого противника в сильного</font></b></center>';
			}elseif( $btl->users[$btl->uids[$u1]]['bot'] > 0 && $btl->users[$btl->uids[$u2]]['bot'] == 0 ) {
				//echo '<center><b><font color=red>Нельзя кастовать через монстров или зверя</font></b></center>';
				//$r = 1;
			}
		}
		return $r;
	}
	
	public function testDie($u1) {
		global $btl;
		//Персонаж 1 погиб от рук персонаж 2
		if( isset($btl->stats[$btl->uids[$u1]]['id']) && floor($btl->stats[$btl->uids[$u1]]['hpNow']) < 1 ) {
			$vLog = 'at1=00000||at2=00000||zb1='.$btl->stats[$btl->uids[$u1]]['zonb'].'||zb2=||bl1=||bl2=||time1='.time().'||time2='.time().'||s2=||s1='.$btl->users[$btl->uids[$u1]]['sex'].'||t2=||t1='.$btl->users[$btl->uids[$u1]]['team'].'||login1='.$btl->users[$btl->uids[$u1]]['login2'].'||login2=';
			mysql_query('DELETE FROM `battle_act` WHERE `uid1` = "'.$u1.'" OR `uid2` = "'.$u1.'"');
			$mas = array(
				'text' => '',
				'time' => time(),
				'vars' => '',
				'battle' => $btl->info['id'],
				'id_hod' => $btl->hodID,
				'vars' => $vLog,
				'type' => 1
			);
			$mas['text'] = '{tm1} <b>'.$btl->stats[$btl->uids[$u1]]['login'].'</b> погиб.';
			$btl->add_log($mas);
		}
	}
	
	public function pruse($id)
	{
		global $u,$c,$code,$btl,$ue;
		if($id==100500 && $u->info['animal']>0)
		{
			$use_lst = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "animal_use'.$btl->info['id'].'" LIMIT 1',1);
			if(!isset($use_lst['id']))
			{				
				$a = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.$u->info['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
				if($u->stats['hpNow'] < 1) {
					echo 'Вы не можете выпустить зверя, вы потеряли все НР';
				}elseif(isset($a['id']) && $a['eda']<1) {
					echo 'Вы не накормили зверя...';
				}elseif(isset($a['id']))
				{
					//Добавляем зверя в бой
					$tp = array(1=>'Кот',2=>'Сова',3=>'Светляк',4=>'Чертяка',5=>'Пес',6=>'Свин',7=>'Дракон');
					$id = mysql_fetch_array(mysql_query('SELECT `id` FROM `test_bot` WHERE `login` = "'.$tp[$a['type']].' ['.$a['level'].']" LIMIT 1'));
					if(isset($id['id']))
					{
						$b = $u->addNewbot($id['id'],NULL,NULL);
						if($b>0 && $b!=false)
						{
							$a['eda'] -= 4;
							if($a['eda'] < 0) {
								$a['eda'] = 0;
							}
							
							//Добавляем эффект
							//$anl = mysql_fetch_array(mysql_query('SELECT `bonus` FROM `levels_animal` WHERE `type` = "'.$a['type'].'" AND `level` = "'.$a['level'].'" LIMIT 1'));
							//$anl = $anl['bonus'];							
							//mysql_query('INSERT INTO `eff_users` (`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`,`user_use`) VALUES ("-1","201","pet_unleash.gif",22,"'.$u->info['id'].'","Эффект от зверя","'.$anl.'","0","77","priem","'.$u->info['id'].'")');
							
							//$anl = $u->lookStats($anl);
							$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
							$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
							/*$ba = '';
							$i = 0;
							while($i<count($u->items['add'])) {
								if(isset($anl['add_'.$u->items['add'][$i]])) {
									if($anl['add_'.$u->items['add'][$i]] > 0) {
										$ba .= $u->is[$u->items['add'][$i]].': +'.$anl['add_'.$u->items['add'][$i]].', ';
									}
								}
								$i++;
							}
							$ba = trim($ba,', ');
							if($ba == '') {
								$ba = 'Отсутсвует';
							}*/
							
							if($u->info['sex'] == 1) {
								$mas1['text'] = '{tm1} {u1} выпустила зверя &quot;<b>'.$a['name'].'&quot;</b>';
							}else{
								$mas1['text'] = '{tm1} {u1} выпустил зверя &quot;<b>'.$a['name'].'&quot;</b>';
							}
							$btl->add_log($mas1);
							
							mysql_query('UPDATE `users` SET `login` = "'.$a['name'].' (зверь '.$u->info['login'].')",`obraz` = "'.$a['obraz'].'.gif",`battle` = "'.$btl->info['id'].'" WHERE `id` = "'.$b['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `team` = "'.$u->info['team'].'" WHERE `id` = "'.$b['id'].'" LIMIT 1');
							mysql_query('UPDATE `users_animal` SET `eda` = "'.$a['eda'].'" WHERE `id` = "'.$a['id'].'" LIMIT 1');
							$u->addAction(time(),'animal_use'.$btl->info['id'],$a['level']);
						}else{
							echo 'Не удалось выпустить зверя...';
						}
					}else{
						//Бот не найден
						echo 'Не удалось выпустить зверя ...';
					}
				}else{
					//зверь не найден
					echo 'У Вас нет зверя ...';
				}
			}else{
				//зверь уже выпущен
				echo 'Вы уже выпускали зверя в этом бою ...';
			}
		}else{
			
			$p = explode('|',$u->info['priems']);
			$pz = explode('|',$u->info['priems_z']);
			if($p[(int)$id]>0 && $pz[(int)$id]<=0 && $u->info['hpNow']>=1)
			{
				$pl = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `level`<="'.$u->info['level'].'" AND `id` = "'.mysql_real_escape_string($p[(int)$id]).'" LIMIT 1'));
				if(isset($pl['id']) && $pl['activ']!=1)
				{
					if($pl['activ']==0)
					{
						unset($pl);
					}elseif($pl['activ']>1)
					{
						//Книжный прием
						if($this->testActiv($pl['activ'])==0)
						{
							unset($pl);
						}
					}
				}
				if(isset($pl['id']))
				{
					$notr = 0;
					$pl['useon_user'] = $u->info['enemy'];
					if(isset($_POST['useon']) && $_POST['useon']!='' && $_POST['useon']!='none')
					{
						$_POST['useon'] = iconv('UTF-8', 'windows-1251', $_POST['useon']);
						$this->ue = mysql_fetch_array(mysql_query('SELECT
						
						`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`online`,`u`.`admin`,`u`.`city`,`u`.`cityreg`,`u`.`align`,`u`.`clan`,
						`u`.`level`,`u`.`money`,`u`.`money3`,`u`.`money4`,`u`.`battle`,`u`.`sex`,`u`.`obraz`,`u`.`win`,`u`.`win_t`,
						`u`.`lose`,`u`.`lose_t`,`u`.`nich`,`u`.`timeMain`,`u`.`invis`,`u`.`bot_id`,`u`.`animal`,`u`.`type_pers`,
						`u`.`notrhod`,`u`.`bot_room`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`activ`,`u`.`stopexp`,`u`.`real`,
												
						`st`.*
						
						FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id`=`st`.`id`) WHERE (`u`.`login`="'.mysql_real_escape_string($_POST['useon']).'" OR (`u`.`login2` = "'.mysql_real_escape_string($_POST['useon']).'" AND `u`.`login2` != "")) AND (`u`.`inUser` > 0 OR (`u`.`battle`="'.$btl->info['id'].'" AND `st`.`hpNow` > 0)) ORDER BY `u`.`id` DESC LIMIT 1'));
						if(isset($this->ue['id']) && $this->ue['inUser']>0)
						{
							$this->ue = mysql_fetch_array(mysql_query('SELECT
							
							`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`online`,`u`.`admin`,`u`.`city`,`u`.`cityreg`,`u`.`align`,`u`.`clan`,
							`u`.`level`,`u`.`money`,`u`.`money3`,`u`.`money4`,`u`.`battle`,`u`.`sex`,`u`.`obraz`,`u`.`win`,`u`.`win_t`,
							`u`.`lose`,`u`.`lose_t`,`u`.`nich`,`u`.`timeMain`,`u`.`invis`,`u`.`bot_id`,`u`.`animal`,`u`.`type_pers`,
							`u`.`notrhod`,`u`.`bot_room`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`activ`,`u`.`stopexp`,`u`.`real`,
													
							`st`.*
							
							FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id`=`st`.`id`) WHERE `u`.`battle`="'.$btl->info['id'].'" AND `st`.`hpNow` > 0) AND `u`.`id` = "'.$this->ue['inUser'].'" ORDER BY `u`.`id` ASC LIMIT 1'));
						}
						if(!isset($this->ue['id']) && $pl['trUser']>0)
						{
							$notr++;
						}
						if($pl['team'] == 1) {
							//свои
							if($u->info['team'] != $this->ue['team']) {
								$notr++;
							}
						}elseif($pl['team'] == 2) {
							//противники
							if($u->info['team'] == $this->ue['team']) {
								$notr++;
							}
						}elseif($pl['team'] == 0) {
							//любая команда
							
						}
					}else{
						//$this->ue = $btl->users[$btl->uids[$u->info['enemy']]];
						$ga = mysql_fetch_array(mysql_query('SELECT * FROM `battle_act` WHERE `battle` = "'.$btl->info['id'].'" AND `uid1` = "'.$u->info['id'].'" AND `uid2` = "'.$u->info['enemy'].'" LIMIT 1'));
						if(($u->info['enemy']==0 || isset($ga['id'])) && ($pl['tr_hod']>0 || $pl['trUser']>0))
						{
							$notr++;
						}
					}

					$notr += $this->testpriem($pl,1,$this->ue['id']);	
					
					/*if( $u->info['admin'] == 0 ) {
						$notr++;
					}*/
					
					if( $this->ue['id'] > 0 ) {
						$notr += $this->testRazmenOldUser($this->ue['id'],$u->info['enemy']);
					}
					
					if($notr==0)
					{					
						mysql_query('UPDATE `stats` SET `last_pr` = "'.$pl['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						
						//Приемы на персонажах
						if( $this->ue['id'] > 0 ) {
							$btl->priemsRazmen(array($u->info['id'],$this->ue['id']),'fast');
							mysql_query('UPDATE `eff_users` SET `mark` = 1 WHERE `uid` = "'.$this->ue['id'].'" AND `delete` = 0');
						}else{
							$btl->priemsRazmen(array($u->info['id'],$u->info['enemy']),'fast');
							mysql_query('UPDATE `eff_users` SET `mark` = 1 WHERE `uid` = "'.$u->info['enemy'].'" AND `delete` = 0');
						}
						mysql_query('UPDATE `eff_users` SET `mark` = 1 WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0');
						
						if(file_exists('../../_incl_data/class/priem/'.$pl['id'].'.php')) {
							require('../../_incl_data/class/priem/'.$pl['id'].'.php');
							$this->testDie($this->ue['id']);
						}else{
							echo 'useSkill'.$pl['id'].'';
						}
						
						/*echo 'combo::'.$pl['type'].'->';
						if($pl['type']==1)
						{*/
							//используется моментально
							/*$pz[(int)$id] = 0;
							if($pl['file']!='0')
							{							
								if(file_exists('../../_incl_data/class/priems/'.$pl['file'].'.php'))
								{
									echo 'test1';
								}
							}else{*/
								//всякие цели и т.д.
								/*echo 'test2';
							}
							if(!isset($cup))
							{
								$this->uppz($pl,$id);
								if($pl['tr_hod']>0)
								{
									$this->trhod($pl);
								}
							}
						}elseif($pl['type']==2)
						{*/
							//Используется на себя (не моментально)
							//$this->addEffPr($pl,$id);
							/*echo 'test3->';
							if(file_exists('../../_incl_data/class/priem/'.$pl['id'].'.php')) {
								require('../../_incl_data/class/priem/'.$pl['id'].'.php');
							}else{
								echo 'useSkill'.$pl['id'].'';
							}*/
							/*echo 'test3';
							if($pl['file2']!='0')
							{							
								$fast_use_priem = 1;
								if(file_exists('../../_incl_data/class/priems/'.$pl['file2'].'.php'))
								{
									echo '->file';
								}
							}*/
						/*}elseif($pl['type']==3)
						{
							echo 'Использовать приемы данного типа временно запрещено';
						}
						*/
						
						if(!isset($cup)) {
							//
							mysql_query('UPDATE `dailybonus` SET `usepriem` = `usepriem` + 1 WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
							//
							$this->uppz($pl,$id);
							//Отнимаем тактики
							//$this->mintr($pl);
							if($pl['tr_hod']>0) {
								$this->trhod($pl);
							}
							if( $pl['id'] != 258 ) { 
								if( $pl['cancel_eff'] == '' ) {
									$pl['cancel_eff'] = '258';
								}else{
									$pl['cancel_eff'] .= ',258';
								}
							}
							if($pl['cancel_eff']!='')
							{
								$i = 0; 
								$e = explode(',',$pl['cancel_eff']);
								while($i<count($e))
								{
									if($e[$i]>0)
									{
										if( $e[$i] == 258 ) {
											$nem = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `v1` = "priem" AND `v2` = "'.$e[$i].'" AND `delete` = "0" AND `mark` = 1 LIMIT 1'));
										}else{
											$nem = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$this->ue['id'].'" AND `v1` = "priem" AND `v2` = "'.$e[$i].'" AND `delete` = "0" AND `mark` = 1 LIMIT 1'));
										}
										if(isset($nem['id']))
										{
											$nem['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$e[$i].'" LIMIT 1'));
											if(isset($nem['id']))
											{
												$btl->delPriem($nem,$btl->users[$btl->uids[$this->ue['id']]],500);
											}
										}
									}
								$i++;
								}
							}
						}
						
					}
				}
			}
		}
	}
	
	private function rezadEff($uid,$mg)
	{
		global $u,$btl,$c,$code;
		//$this->rezadEff($u->info['id'],'wis_fire_');
		$md = ''; $md2 = '';
		$ex = explode('|',$btl->users[$btl->uids[$uid]]['priems']);
		$ex2 = explode('|',$btl->users[$btl->uids[$uid]]['priems_z']);
		$i = 0; $ty = array();
		while($i<count($ex))
		{
			if($ex[$i]>0)
			{
				$md .= '`id` = "'.((int)$ex[$i]).'" OR ';
				$ty[$ex[$i]] = $i;
			}
			$i++;
		}
		$md = rtrim($md,' OR ');
		if( $md != '' ) {
			$md = '( '.$md.' ) AND ';
		}
		$sp = mysql_query('SELECT * FROM `priems` WHERE '.$md.' `img` LIKE "%'.$mg.'%"');
		while($pl = mysql_fetch_array($sp))	{
			$ex2[$ty[$pl['id']]] = 0;
		}
		$md2 = implode('|',$ex2);
		$btl->users[$btl->uids[$uid]]['priems_z'] = $md2;
		$u->info['priems_z'] = $md2;
		$upd = mysql_query('UPDATE `stats` SET `priems_z` = "'.$md2.'" WHERE `id` = "'.((int)$uid).'" LIMIT 1');
		unset($md,$md2,$ty);
		if($upd)
		{
			$upd = true;
		}else{
			$upd = false;	
		}
		return $upd;
	}
	
	private function trhod($pl)
	{
		global $u,$btl;
		if($u->info['notrhod'] == -1) {
			$u->info['notrhod'] = 0;
			if($u->stats['magic_cast'] > 0) {
				$u->info['notrhod'] = $u->stats['magic_cast'];
			}
			mysql_query('UPDATE `users` SET `notrhod` = "'.$u->info['notrhod'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}
		if($u->info['notrhod'] > 0) {
			if( $pl['tr_hod'] > 0 ) {
				$u->info['notrhod']--;
				mysql_query('UPDATE `users` SET `notrhod` = "'.$u->info['notrhod'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}
		}else{
			$a1 = mysql_fetch_array(mysql_query('SELECT * FROM `battle_act` WHERE `battle` = "'.$btl->info['id'].'" AND `uid2` = "'.$u->info['id'].'" AND `uid1` = "'.$u->info['enemy'].'" LIMIT 1'));
			if(isset($a1['id']))
			{
				//противник ударил, пишем что игрок 2 пропустил ход
				mysql_query('UPDATE `battle_act` SET `out2` = "1",`tpo2` = "2" WHERE `id` = "'.$a1['id'].'" LIMIT 1');
				$a1['out2'] = 1;
				$a1['tpo2'] = 2;
				$btl->atacks[$a1['id']] = $a1;
				$btl->users[$u->info['id']]['priems_z'] = $u->info['priems_z'];
				$btl->startAtack($a1['id']);
			}else{
				//бьем противника с пропуском хода
				mysql_query('INSERT INTO `battle_act` (`battle`,`uid1`,`uid2`,`time`,`out1`,`type`,`tpo1`) VALUES ("'.$btl->info['id'].'","'.$u->info['id'].'","'.$u->info['enemy'].'","'.time().'","1","1","2")');			
			}
		}
	}
	
	public function plusData( $d1, $d2 ) {
		global $u;
		$j1 = $u->lookStats($d1);
		$j2 = $u->lookStats($this->redate($d2,$u->info['id']));
		$v = $u->lookKeys($this->redate($d2,$u->info['id']),0); // ключи 2
		//добавляем данные друг к другу
		$i = 0; $inf = '';
		while($i<count($v))
		{
			$j1[$v[$i]] += $j2[$v[$i]];
			$vi = str_replace('add_','',$v[$i]);
						if($u->is[$vi]!='')
			{
				if($j2[$v[$i]]>0)
				{
					$inf .= $u->is[$vi].': +'.($j2[$v[$i]]*(1+$mpr['x'])).', ';
				}elseif($j2[$v[$i]]<0){
					$inf .= $u->is[$vi].': '.($j2[$v[$i]]*(1+$mpr['x'])).', ';	
				}
			}
			$i++;	
		}
		$inf = rtrim($inf,', ');
		$j1 = $u->impStats($j1);
		return $j1;
	}
	
	private function addEffPr($pl,$id,$redus)
	{
		global $u,$btl;
		$rcu = false;
		$j = $u->lookStats($pl['date2']);		
		$mpr = false; $addch = 0;
		$uid = $u->info['id'];
		if(isset($this->ue['id']))
		{
			$uid = $this->ue['id'];
		}
		if(isset($j['onlyOne']))
		{
			$mpr = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `v2` = "'.$pl['id'].'" AND `uid` = "'.$uid.'" AND `delete` = "0" AND `mark` = 1 LIMIT 1'));
		}
		
		if($pl['cancel_eff2']!='')
		{
			$i = 0; 
			$e = explode(',',$pl['cancel_eff2']);
			while($i<count($e))
			{
				if($e[$i]>0)
				{
					$nem = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$uid.'" AND `v1` = "priem" AND `v2` = "'.$e[$i].'" AND `delete` = "0" AND `mark` = 1 LIMIT 1'));
					if(isset($nem['id']))
					{
						$nem['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$e[$i].'" LIMIT 1'));
						if(isset($nem['id']))
						{
							$btl->delPriem($nem,$btl->users[$btl->uids[$uid]],2);
							if( $nem['id'] == $mpr['id'] ) {
								unset($mpr);
							}
						}
					}
				}
			$i++;
			}
		}
		
		$pld = array(0=>''); $nc = 0;
		if(isset($mpr['id']) && $j['onlyOne']==1)
		{
			//отнимаем тактики
			$addch = 1;
			$this->mintr($pl);
			$this->uppz($pl,$id);
			//добавляем прием в эффекты
			if(isset($this->ue['id']))
			{
				$btl->stats[$btl->uids[$uid]] = $u->getStats($this->ue,0);
			}else{
				$btl->stats[$btl->uids[$uid]] = $u->getStats($u->info,0);	
			}
			$nc = 1;
		}elseif(!isset($mpr['id']))
		{
			$data = '';
			if(isset($j['date3Plus']))
			{
				$data = $this->redate($pl['date3'],$u->info['id']);
			}
			if( isset($redus) ) {
				$data .= '|'.$redus;
			}
			$hd1 = -1;
			if($pl['limit']>0)
			{
				$tm = 77;
				$hd1 = $pl['limit'];
			}else{
				$tm = 77;
			}
			if($pl['limit'] == -2) {
				$hd1 = $pl['limit'];
			}
			mysql_query('INSERT INTO `eff_users` (`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`,`user_use`) VALUES ("'.$hd1.'","'.$pl['id'].'","'.$pl['img'].'.gif",22,"'.$uid.'","'.$pl['name'].'","'.$data.'","0","'.$tm.'","priem","'.$u->info['id'].'")');
			unset($hd1);
			//отнимаем тактики
			$addch = 1;
			$rcu = true;
			$nc = 1;
			$this->mintr($pl);
			//$this->uppz($pl,$id);
			//добавляем прием в эффекты
			if(isset($this->ue['id']))
			{
				$btl->stats[$btl->uids[$uid]] = $u->getStats($this->ue,0);
			}else{
				$btl->stats[$btl->uids[$uid]] = $u->getStats($u->info,0);	
			}
		}elseif($j['onlyOne']>1)
		{
			if($mpr['x']<$j['onlyOne'])
			{				
				if(isset($j['date3Plus']))
				{
					$j1 = $u->lookStats($mpr['data']);
					$j2 = $u->lookStats($this->redate($pl['date3'],$u->info['id']));
					$v = $u->lookKeys($this->redate($pl['date3'],$u->info['id']),0); // ключи 2
					//добавляем данные друг к другу
					$i = 0; $inf = '';
					while($i<count($v))
					{
						$j1[$v[$i]] += $j2[$v[$i]];
						$vi = str_replace('add_','',$v[$i]);
						if($u->is[$vi]!='')
						{
							if($j2[$v[$i]]>0)
							{
								$inf .= $u->is[$vi].': +'.($j2[$v[$i]]*(1+$mpr['x'])).', ';
							}elseif($j2[$v[$i]]<0){
								$inf .= $u->is[$vi].': '.($j2[$v[$i]]*(1+$mpr['x'])).', ';	
							}
						}
						$i++;	
					}
					$inf = rtrim($inf,', ');
					$j1 = $u->impStats($j1);
					$pld[0] = ' x'.($mpr['x']+1);
					if($j['refHod']==1) {
						$mpr['hod'] = $pl['limit'];
					}
					$upd = mysql_query('UPDATE `eff_users` SET `hod` = "'.$mpr['hod'].'",`data` = "'.$j1.'",`x` = `x`+1 WHERE `id` = "'.$mpr['id'].'" LIMIT 1');
					if($upd)
					{
						//отнимаем тактики
						$this->mintr($pl);
						$this->uppz($pl,$id);
						//добавляем прием в эффекты
						if(isset($this->ue['id']))
						{
							$btl->stats[$btl->uids[$uid]] = $u->getStats($this->ue,0);
						}else{
							$btl->stats[$btl->uids[$uid]] = $u->getStats($u->info,0);	
						}
						$addch = 1;
						$rcu = true;
						$nc = 1;
					}
				}				
			}
		}
		/* тратим свой ход */
		if($nc==1 && $pl['tr_hod']>0)
		{
			$this->trhod($pl);
		}
		return $rcu;
	}
	
	public function mintr($pl)
	{
		global $u,$btl;
		$x = 1; $rt = '';
		while($x<=7)
		{
			if( $pl['ndt'.$x] == 0 ) {
				$u->info['tactic'.$x] -= $pl['tt'.$x];
				$btl->users[$btl->uids[$u->info['id']]]['tactic'.$x] -= $pl['tt'.$x];
			}
			if($u->info['tactic'.$x]<0)
			{
				$u->info['tactic'.$x] = 0;
			}
			if($btl->users[$btl->uids[$u->info['id']]]['tactic'.$x]<0)
			{
				$btl->users[$btl->uids[$u->info['id']]]['tactic'.$x] = 0;
			}
			//$rt .= ',`tactic'.$x.'`="'.$u->info['tactic'.$x].'"';
			$rt .= ',`tactic'.$x.'`="'.$btl->users[$btl->uids[$u->info['id']]]['tactic'.$x].'"';
			$x++;
		}
		if($pl['xuse']>0)
		{
			$u->addAction(time(),'use_priem_'.$btl->info['id'].'_'.$u->info['id'],$pl['id']);
		}
		$rt = ltrim($rt,',');
		mysql_query('UPDATE `stats` SET '.$rt.' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
	
	public function maxtr($x,$val)
	{
		global $u,$btl;
		$u->info['tactic'.$x] += $val;
		$btl->users[$btl->uids[$u->info['id']]]['tactic'.$x] += $val;
		if($u->info['tactic'.$x]<0) {
			$u->info['tactic'.$x] = 0;
		}
		if($btl->users[$btl->uids[$u->info['id']]]['tactic'.$x] < 0) {
			$btl->users[$btl->uids[$u->info['id']]]['tactic'.$x] = 0;
		}
		$rt .= '`tactic'.$x.'`="'.$u->info['tactic'.$x].'"';
		mysql_query('UPDATE `stats` SET '.$rt.' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
	
	public function actpridMax($pl)
	{
		global $u,$btl;		
		if($pl['actprid2']>0 || $pl['actprid3']>0)
		{
			$i = 0;
			$pe = explode('|',$u->info['priems']);
			$piz = array();
			while($i<count($pe))
			{
				if($pl['sbr'] == 0) {
					//все блокируем
					$psp = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.((int)$pe[$i]).'" LIMIT 1'));
				}else{
					//Только текущую школу магии
					$imgnm = '';
					$nm = explode('_',$pl['img']);
					if($nm[0] == 'wis') { //магия
					$imgnm = $nm[0].'_'.$nm[1].'%';
					}else{
						$imgnm = $nm[0].'%';						
					}
					//только данной школы
					$psp = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.((int)$pe[$i]).'" AND `img` LIKE "'.$imgnm.'" LIMIT 1'));
				}
				if( $pl['noprid'] == 0 ) {
					if(isset($psp['id']) && $psp['tr_hod']==0 && $psp['type_pr']==1 && $psp['noprid'] == 0)
					{
						if($pl['actprid2']>0)
						{
							$piz[$pe[$i]] = (int)$pl['actprid2'];
						}elseif($pl['actprid3']>0)
						{
							$piz[$pe[$i]] = $psp['zad'];
						}
					}
				}
				$i++;
			}
			$pz = explode('|',$u->info['priems_z']);
			$p = explode('|',$u->info['priems']);
			$i = 0;
			while($i<count($p))
			{
				if($p[$i]>0 && isset($piz[$p[$i]]))
				{
					if($pz[$i]==0)
					{
						$pz[$i] = $piz[$p[$i]];
					}
				}
				$i++;
			}
			$pz = implode('|',$pz);
			$u->info['priems_z'] = $pz;
			$btl->users[$btl->uids[$u->info['id']]]['priems_z'] = $pz;
			$btl->stats[$btl->uids[$u->info['id']]]['priems_z'] = $pz;
		}
	}
	
	public function uppz($pl,$id)
	{
		global $u,$btl;
		$this->actpridMax($pl);
		$p = explode('|',$u->info['priems']);
		$pz = explode('|',$u->info['priems_z']);
		$pz[(int)$id] = $pl['zad'];
		$i = 0; $pe = explode(',',$pl['actprid']);
		$piz = array();
		while($i<count($pe))
		{
			$piz[$pe[$i]] = 1;
			$i++;
		}
		$i = 0; $pe = explode(',',$pl['actprid_one']);
		$piz2 = array();
		while($i<count($pe))
		{
			$piz2[$pe[$i]] = 1;
			$i++;
		}
		$i = 0;
		while($i<count($p))
		{
			if($p[$i]>0)
			{
				if(isset($piz[$p[$i]]))
				{
					if( $pl['id'] == 281 ) {
						//Жертва воде + воздуху дает 5 ед. задержки на землю и огонь
						if($p[(int)$i] == 246 || $p[(int)$i] == 186) {
							$pz[(int)$i] = 5;
						}else{
							$pz[(int)$i] = $pl['zad'];
						}
					}else{
						$pz[(int)$i] = $pl['zad'];
					}
				}
				if(isset($piz2[$p[$i]]))
				{
					if( $pz[(int)$i] == 0 ) {
						$pz[(int)$i] = 1;
					}
				}
			}
			$i++;
		}
		$pz = implode('|',$pz);
		$u->info['priems_z'] = $pz;
		$btl->users[$btl->uids[$u->info['id']]]['priems_z'] = $pz;
		$btl->stats[$btl->uids[$u->info['id']]]['priems_z'] = $pz;
		$tr = $u->lookStats($pl['tr']);
		if(isset($tr['tr_mpNow']))
		{
			$tr['tr_mpNow'] = round($tr['tr_mpNow']/100*(100-$u->stats['min_use_mp']));
			$btl->users[$btl->uids[$u->info['id']]]['mpNow'] -= $tr['tr_mpNow'];
			$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] -= $tr['tr_mpNow'];
			if($btl->stats[$btl->uids[$u->info['id']]]['mpNow']<$btl->users[$btl->uids[$u->info['id']]]['mpNow'])
			{
				$btl->users[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
			}
		}
		$u->info['mpNow'] = $btl->users[$btl->uids[$u->info['id']]]['mpNow'];
		mysql_query('UPDATE `stats` SET `mpNow` = "'.$u->info['mpNow'].'",`priems_z` = "'.$pz.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
	
	public function reuns($id)
	{
		global $u,$c,$code;
		$p = explode('|',$u->info['priems']);
		if($p[(int)$id]>0)
		{
			//снимаем прием
			$p[(int)$id] = 0;
			$p = implode('|',$p);
			$upd = mysql_query('UPDATE `stats` SET `priems` = "'.mysql_real_escape_string($p).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['priems'] = $p;
		}					
	}
	
	public function uns($id)
	{
		global $u,$c,$code;
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `level`<="'.$u->info['level'].'" AND `activ` > "0" AND `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(isset($pl['id']))
		{
			$notr = $this->testpriem($pl,1);
			if($notr==0)
			{
				$yes = -1; $non = -1;
				$i = 0; $p = explode('|',$u->info['priems']);
				while($i < $u->info['priemslot'])
				{
					if($non==-1 && $p[$i]==0)
					{
						$non = $i;
					}
					if($p[$i]==$pl['id'])
					{
						$yes = $i;
					}
					$i++;
				}			
				
				if($yes==-1)
				{
					if($non!=-1)
					{
						//одеваем прием
						$p[$non] = $pl['id'];
						$p = implode('|',$p);
						$upd = mysql_query('UPDATE `stats` SET `priems` = "'.$p.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if($upd)
						{
							$u->info['priems'] = $p;							
						}
					}else{
						//снимаем последний прием
						echo 'Снимаем последний прием...';
					}
				}else{
					//такой прием уже стоит, ничего не делаем
				}	
								
			}
		}
	}
	
	//выводим приемы $id - 1 (вне боя), 2 - в бою
	public function seeMy($t)
	{
		global $u,$c,$code,$btl;
		if( $u->info['inTurnir'] == 0 || true == true ) {
			$i = 0; $p = explode('|',$u->info['priems']); $lvar = ''; $pr = '';
			while($i<$u->info['priemslot'])
			{			
				if($p[$i]>0)
				{
					$pl = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `level`<="'.$u->info['level'].'" AND `activ` > "0" AND `id` = "'.mysql_real_escape_string($p[$i]).'" LIMIT 1'));
					$lvar = $this->priemInfo($pl,$t,$i);
					$pz   = $lvar[1];
					$lvar = $lvar[0];
					if($t==1)
					{
						if(isset($_GET['inv'])) {
							//$cl = 'href="main.php?skills=1&rz=4"';
							$cl = 'href="javascript:void(0)" onclick="location.href=\'main.php?all='.((int)$_GET['all']).'&skills=1&rz=4&p_raz=all\'"';
						}else{
							//$cl = 'href="main.php?skills=1&rz=4&unuse_priem='.$i.'&rnd='.$code.'"';
							$cl = 'href="javascript:void(0)" onclick="location.href=\'main.php?all='.((int)$_GET['all']).'&skills=1&unuse_priem='.$i.'&rz=4&p_raz=\' + p_raz"';
						}
					}else{
						if($pl['type']==1)
						{
							//моментально
							if($pl['onUser']==1)
							{
								$oninuser = '';
								if( $pl['team'] == 1 ) {
									if( $u->info['login2'] != '' ) {
										$oninuser = $u->info['login2'];
									}else{
										$oninuser = $u->info['login'];
									}
								}else{
									if( $btl->users[$btl->uids[$u->info['enemy']]]['login2'] != '' ) {
										$oninuser = $btl->users[$btl->uids[$u->info['enemy']]]['login2'];
									}else{
										$oninuser = $btl->users[$btl->uids[$u->info['enemy']]]['login'];
									}
								}
								$cl = 'href="javascript:void(0);" onClick="top.priemOnUser('.$i.',1,\''.$pl['name'].'\',\''.$oninuser.'\');"';
								unset($oninuser);
							}else{
								$cl = 'href="javascript:void(0);" onClick="usepriem('.$i.',1);"';
							}
						}elseif($pl['type']==2)
						{
							//длительное
							$cl = 'href="javascript:void(0);" onClick="usepriem('.$i.',1);"';
						}elseif($pl['type']==3)
						{
							$cl = 'href="javascript:void(0);" onClick="alert(\'Возможно используем?\');"';
						}
					}
					
	
					$notr = $this->testpriem($pl,$t);
					
					
					$cl2 = '';
					$cli2 = '';
					if( ( ($pz[$i]>0 || $notr>0) && $t==2 ) || (isset($u->stats['nopriems']) && $pl['nosh'] == 0) || $u->stats['notuse_last_pr'] == $pl['id'])
					{
						//$cl2 = 'filter: alpha(opacity=15); -moz-opacity: 0.15; -khtml-opacity: 0.15; opacity: 0.15;';
						$cli2  = ' class="nopriemuse" ';
					}
					
					$pr .= '<a onMouseOver="top.hi(this,\'<b>'.$pl['name'].'</b><Br>'.$lvar.'\',event,3,0,1,1,\'width:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();" '.$cl.'><img '.$cli2.' style="margin-top:3px; '.$cl2.' margin-left:4px;" src="http://img.xcombats.com/i/eff/'.$pl['img'].'.gif" width="40" height="25" /></a>';
				}else{
					if($t==1)
					{
						if(isset($_GET['inv'])) {
							$pr .= '<a title="Перейти к настройкам приемов" href="javascript:void(0)" onclick="location.href=\'main.php?all='.((int)$_GET['all']).'&skills=1&rz=4&p_raz=all\'"><img style="margin-top:4px; margin-left:4px;" src="http://img.xcombats.com/i/items/w/clearPriem.gif" width="40" height="25" /></a>';
						}else{
							$pr .= '<img style="margin-top:4px; margin-left:4px;" src="http://img.xcombats.com/i/items/w/clearPriem.gif" width="40" height="25" />';
						}
					}
				}
				$i++;
			}
			if($u->info['animal']>0 && $t==2)
			{
				$use_lst = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "animal_use'.$btl->info['id'].'" LIMIT 1',1);
				if(!isset($use_lst['id']))
				{
					$cl2 = '';
					$pr .= '<a onMouseOver="top.hi(this,\'<b>Выпустить зверя</b><Br>Ваш зверь вмешивается в поединок. Можно применять один раз за бой.\',event,3,0,1,1,\'width:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();" href="javascript:void(0);" onClick="usepriem(100500,1);"><img style="margin-top:1px; '.$cl2.' margin-left:3px;" src="http://img.xcombats.com/i/eff/pet_unleash.gif" width="40" height="25" /></a>';
				}else{
					$cl2 = '" class="nopriemuse';
					$pr .= '<img onMouseOver="top.hi(this,\'<b>Выпустить зверя</b><Br>Ваш зверь вмешивается в поединок. Можно применять один раз за бой.\',event,3,0,1,1,\'width:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();" style="margin-top:1px; margin-left:2px;'.$cl2.'" src="http://img.xcombats.com/i/eff/pet_unleash.gif" width="40" height="25" />';
	
				}
			}
			if($t==1)
			{
				echo '<div style="width:230px;">'.$pr.'</div>';
			}elseif($t==2)
			{
				$pr = str_replace('"','\\"',$pr);
				return $pr;
			}
		}
	}
	
	public function testpriem($pl,$t = 1,$o = 0)
	{
		global $c,$u,$code,$btl;
		$tr = $u->lookStats($pl['tr']);
		$d2 = $u->lookStats($pl['date2']);
		$x = 1;
		$notr = 0;
		
		if($t==2 && $pl['id']==181){		
		    $imun = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['enemy'].'" and `v2`="191" and `delete`="0" LIMIT 1'));
		    if($imun){
				$notr++;
			}
		}
		
		if(isset($btl->stats[$btl->uids[$u->info['id']]]['nousepriem']) && $btl->stats[$btl->uids[$u->info['id']]]['nousepriem'] > 0 && $pl['nosh'] == 0) {
			if( $btl->stats[$btl->uids[$u->info['id']]]['noshock_voda'] > 0 && substr($pl['img'],0,10) == 'wis_water_' ) {
				//вода
			}else{
				$notr++;
			}
		}
		
		if( $pl['id'] == $btl->stats[$btl->uids[$u->info['id']]]['notuse_last_pr'] ) {
			$notr++;
		}
				
		while($x<=7)
		{
			if(isset($btl->uids[$u->info['id']],$btl->users[$btl->uids[$u->info['id']]]))
			{
				if($btl->users[$btl->uids[$u->info['id']]]['tactic'.$x] < $pl['tt'.$x] && $x!=7 && $pl['tt'.$x] > 0)
				{
					$notr++;
				}elseif($x==7)
				{
					if($pl['tt'.$x]>0 && $btl->users[$btl->uids[$u->info['id']]]['tactic'.$x]<=0)
					{
						$notr++;
					}
				}
			}
			$x++;
		}


		if($pl['xuse']>0)
		{
			$xu = $u->testAction('`vars` = "use_priem_'.$btl->info['id'].'_'.$u->info['id'].'" AND `vals` = "'.$pl['id'].'" LIMIT '.$pl['xuse'].'',2);
			if($xu[0]>=$pl['xuse'])
			{
				$notr++;
			}
		}

		$x = 0;
		$t = $u->items['tr'];
		while($x < count($t))
		{
			$n = $t[$x];
			if(isset($tr['tr_'.$n]))
			{
				if($n=='lvl')
				{
					if($tr['tr_'.$n] > $u->info['level'])
					{
						$notr++;
					}
				}elseif($tr['tr_'.$n] > $u->stats[$n])
				{
					$notr++;
				}
			}
			$x++;
		}
		
		
		
		if($pl['activ']==0 || ($this->testActiv($pl['activ'])==0 && $pl['activ']>1))
		{
			$notr++;
		}
		
		
		//if($t==2)
		//{
			if($d2['onlyOne']>1 || $d2['onlyOneX1'] == 1)
			{
				if( $d2['onlyOneX1'] == 1 ) {
					$pru = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `v2` = "'.$pl['id'].'" AND `delete` = "0" AND `x` >= 1 LIMIT 1'));
				}else{
					$pru = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `v2` = "'.$pl['id'].'" AND `delete` = "0" AND `x` > 1 LIMIT 1'));
				}
				if(isset($pru['id']) && $pru['x']>=$d2['onlyOne']) {				
					$notr++;				
				}
			}
		//}
		
		//Требует чтобы не было
		if(isset($tr['tr_nousepriem'])) {
			$x = 0;
			$nouse = explode(',',$tr['tr_nousepriem']);
			while($x < count($nouse)) {
				$nousev = explode('.',$nouse[$x]);
				if(isset($btl->stats[$btl->uids[$u->info['id']]]['prsu'][$nousev[0]]) && $btl->stats[$btl->uids[$u->info['id']]]['prsu'][$nousev[0]] >= 0) {
					if($nousev[2] > 1) {
						if($nousev[2] <= $btl->stats[$btl->uids[$u->info['id']]]['prsu'][$nousev[0]]) {
							$notr++;
						}
					}else{
						$notr++;
					}
				}
				$x++;
			}
			unset($nouse,$nousev);
		}
		
		if(isset($tr['tr_type_itm1'])) {
			//требует наличие предмета определенного типа
			$itmt = mysql_fetch_array(mysql_query('SELECT `u`.`id` FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `m`.`id` = `u`.`item_id` WHERE `m`.`type` = "'.$tr['tr_type_itm1'].'" AND `u`.`inOdet` > 0 AND `u`.`uid` = "'.$u->info['id'].'" AND `u`.`delete` = "0" LIMIT 1'));
			if(!isset($itmt['id'])) {
				$notr++;
			}
		}
		
		if(isset($tr['tr_mpNow']))
		{
			if(isset($btl->stats[$btl->uids[$u->info['id']]]))
			{
				if($btl->stats[$btl->uids[$u->info['id']]]['mpNow'] < round($tr['tr_mpNow']/100*(100-$btl->stats[$btl->uids[$u->info['id']]]['min_use_mp'])))
				{
					$notr++;
				}
			}else{
				if($u->info['mpNow'] < $tr['tr_mpNow'])
				{
					$notr++;
				}
			}
		}
		
		if(isset($btl->uids[$u->info['id']],$btl->stats[$btl->uids[$u->info['id']]]))
		{
			if($pl['trUser']==1)
			{
				//требует чтобы пользователь с кем-то разменивался (при ожидании прием гаснит)
				if(isset($btl->ga[$u->info['id']][$u->info['enemy']]))
				{
					$notr++;
				}
			}elseif($pl['trUser']==2 && $o > 0)
			{
				//требует чтобы пользователь с кем-то разменивался (при ожидании не пропадает, но не используется)
				$ga = mysql_fetch_array(mysql_query('SELECT * FROM `battle_act` WHERE `battle` = "'.$btl->info['id'].'" AND `uid1` = "'.$u->info['id'].'" AND `uid2` = "'.$btl->users[$btl->uids[$u->info['id']]]['enemy'].'" LIMIT 1'));
				if(isset($ga['id']))
				{
					$notr++;
				}
			}			
		}
		

		
		return $notr;
	}
	
	public function priemInfo($pl,$t,$id = false)
	{
		global $u,$c,$code,$btl;
		$pz = explode('|',$u->info['priems_z']);
		$tr = $u->lookStats($pl['tr']);
		$trs = '';
		$x = 0;
		$notr = 0;
		$t = $u->items['tr'];
		while($x<count($t))
		{
			$n = $t[$x];
			if(isset($tr['tr_'.$n]))
			{
				if($tr['tr_'.$n] > $u->stats[$n])
				{
					$trs .= '<font color=red>'; $notr++;
				}
				$trs .= '<br>• ';
				$trs .= $u->is[$n].': '.$tr['tr_'.$n];
				if($tr['tr_'.$n] > $u->stats[$n])
				{
					$trs .= '</font>';
				}
			}
			$x++;
		}
			
		$lvar = '';
		$j = 1;
		$nm = array(1=>'hit',2=>'krit',3=>'counter',4=>'block',5=>'parry',6=>'hp',7=>'spirit');
		while($j<=6)
		{
			if($pl['tt'.$j]>0)
			{
				$lvar .= '<img src=http://img.xcombats.com/i/micro/'.$nm[$j].'.gif width=8 height=8 /> '.round($pl['tt'.$j],2).' &nbsp; ';
			}
			$j++;
		}
		if($pl['tt7']>0)
		{
			if($lvar!='')
			{
				$lvar .= '<br>';
			}
			$lvar .= 'Сила духа: '.round($pl['tt'.$j],2).'<br>';
		}
		$lvar .= '<br>';
		if($pl['zad']>0)
		{
			$lvar .= 'Задержка: '.$pl['zad'];
			if($pz[$id]>0)
			{
				$lvar .= ' (еще '.$pz[$id].')';
			}
			$lvar .= '<br>';
		}
		if(isset($tr['tr_mpNow']) && $tr['tr_mpNow']>0)
		{
			$tr['tr_mpNow'] -= round($tr['tr_mpNow']/100*$u->stats['min_use_mp']);
			if($u->info['mpNow']<$tr['tr_mpNow'] || (isset($btl->stats[$btl->uids[$u->info['id']]]) && $btl->stats[$btl->uids[$u->info['id']]]['mpNow']<$tr['tr_mpNow']))
			{
				$lvar .= '<font color=red>• Расход маны: '.$tr['tr_mpNow'].'</font><br>';
			}else{
				$lvar .= '• Расход маны: '.$tr['tr_mpNow'].'<br>';	
			}
		}
		if($pl['tr_hod']>0)
		{
			$lvar .= '• Прием тратит ход<br>';
		}
		if($trs!='')
		{
			$lvar .= '<b>Требования:</b>'.$trs.'<br><br>';
		}else{
			$lvar .= '<br>';
		}
		$pl['info'] = preg_replace("!(\#)(.*?)(\#)!ise","getdr('\\2',array(0=>'lvl1',1=>'ts5',2=>'mpAll'),array(0=>'".$u->info['level']."',1=>'".$u->stats['s5']."',2=>'".$u->stats['mpAll']."'))",$pl['info']);
		$lvar .= $pl['info'];
		$lvar = array(0=>$lvar,1=>$pz);
		return $lvar;
	}
	
	//Мощность / подавление / сопротивление и т.д.
		public function testPower($s1,$s2,$y,$t,$t2)
		{
			global $u,$btl;
			
			$r = 0;
			if($t2==2)
			{
				//урон магией
				$pm = array(0=>0,1=>0,2=>0,3=>0);
				if($t<5)
				{
					$pm[0] = $s1['m11'];
					$pm[1] = $s2['zm'];
					$pm[2] = $s2['antm11'];
				}
				
				if(isset($btl->info['id']))
				{
					$pm[3] = $btl->zmgo( $s2['zm'.$t] );					
					$pm[3] = round($pm[3]);
				}

				//$p += $p/100*($s1['pm'.$t]*0.75+$pm[0]*1.01+$s1['m11a']*0.75-($s2['antpm'.$t]+$s2['antm11a']+$pm[2])) - $p/75*$pm[3]; //от мощностей и защита противника
				
				//$kfl = 250;
				
				//$p = $y*(1+ ( $s1['pm'.$t]-$s2['antpm'.$t]-$s2['antm11a']-$pm[2] ) /100)*pow(2,(( ( $s2['pzm'.$t]  ) * 10-(($s2['zma']+$pm[1]) + $s2['zm'.$t]) )/$kfl));
				
				//урон = b*(1+m/100)*2^((p*10-z)/k)
				$fx_vl = array(
					250,250,250,250,250,250,250,250,250,300,350,400,450,500,550,600,650,700,750,800,850,900
				);
				
				$fx = array(
					'b' => $y, //базовый урон
					'm' => round( $s1['pm'.$t] * 1 - $s2['antpm'.$t] ), //мощь
					'z' => round( $s2['zm'.$t] ), //защита цели ед.
					'p' => round( $s1['pzm'] + $s1['pzm'.$t] ), //подавление
					'k' => $fx_vl[(0+$s1['lvl'])] //коэффициент ; k=250 для 8ки, k=300 для 9ки и т.д. +20% на уровень
				);				
				if( ($fx['z']+250) - $fx['p']*10 < 0 ) { //защита не может уйти больше, чем в 250 ед.
					$fx['p'] = ($fx['z']+250)/10;
				}
				$fx['p'] = 0;
				//
				$p = $fx['b'] * ( 1 + $fx['m'] / 100 ) * pow( 2, ( ( $fx['z'] - $fx['p'] * 10 ) / $fx['k'] ) );
				//$p += $p/100*10;
				$p -= $p/100*$pm[3];
				//$p += floor($s1['s5']*0.25);
				
				if($p < round($y*0.1)) {
					$p = round($y*0.1);
				}elseif($p > round($y*10)) {
					$p = $y*10;
				}
				
				//$p += $p/100*($s1['pzm']+$s1['pzm'.$t]); //от подавления маг.защиты	
				if(isset($s2['zmproc']) || isset($s2['zm'.$t.'proc'])) //защита от магии стихий (призрачки)
				{
			        
					$p = floor($p/100*(100/*-$s2['zmproc']*/-$s2['zm'.$t.'proc']));
					if($p<0)
					{
						$p = 0;
					}
					
				}
				
				$p = round($p/100*rand(97,100));
				$r = $p;

			}else{
				//урон оружием
				
			}	
			
			$r = round($r/100*70);

			return $r;
			
			
			/*		//if($u->info['id']==340379 or $u->info['id']==399105){
					//    echo '$y '.$y.'<br>';
					//}
			$r = 0;
			if($t2==2)
			{
				//урон магией
				$pm = array(0=>0,1=>0,2=>0,3=>0);
				if($t<5)
				{
					$pm[0] = $s1['m11'];
					$pm[1] = $s2['zm'];
					$pm[2] = $s2['antm11'];
				}
				$p = $y;
				
				//$p += ($s1['s5']*($p/100*0.52)); //от интелекта
				$p += 0; //от умений
				
				if(isset($btl->info['id']))
				{
					//$pm[3] = ($p/100*((($s2['zma']+$pm[1])+$s2['zm'.$t])*1.25))*0.20;
					$pm[3] = $btl->zmgo( ($s2['zma']+$pm[1]) + $s2['zm'.$t] );
					$pm[3] = round($pm[3]);
				}

				$p += $p/100*($s1['pm'.$t]*0.75+$pm[0]*1.01+$s1['m11a']*0.75-($s2['antpm'.$t]+$s2['antm11a']+$pm[2])) - $p/75*$pm[3]; //от мощностей и защита противника
				
				
				$p += $p/100*($s1['pzm']+$s1['pzm'.$t]); //от подавления маг.защиты	
				if(isset($s2['zmproc']) || isset($s2['zm'.$t.'proc'])) //защита от магии стихий (призрачки)
				{
			        
					$p = floor($p/100*(100-$s2['zmproc']-$s2['zm'.$t.'proc']));
					if($p<0)
					{
						$p = 0;
					}
					
				}
				$p = round($p/100*rand(90,100));
				$r = $p;

			}else{
				//урон оружием
				
			}	
			//if($u->info['id']==340379 or $u->info['id']==399105){
				//	    echo '$r '.$r.'<br>';
				//	}
			return $r;*/
		}
	
	private function pyes($id)
	{
		global $u;
		$p = explode('|',$u->info['priems']);
		$r = false;
		$i = 0;
		while($i<count($p))
		{
			if($p[$i]==$id)
			{
				$r = true;
			}
			$i++;
		}
		return $r;
	}
	
	//выводим все доступные приемы игроку на его уровне - 1, выводим все доступные приемы только игроку - 2
	public function seePriems($mt)
	{
		global $u,$c,$code;
		if( $u->info['inTurnir'] == 0 || true == true ) {
			$t = $u->items['tr'];
			$nm = array(1=>'hit',2=>'krit',3=>'counter',4=>'block',5=>'parry',6=>'hp',7=>'spirit');
			$sp = mysql_query('SELECT * FROM `priems` WHERE `level`<="'.$u->info['level'].'" AND `activ` > "0" ORDER BY `img`,`level` ASC');
			$u->info['lvl'] = $u->info['level']; $lvar = '';
			while($pl = mysql_fetch_array($sp))
			{
				$noaki = 0;
				if($pl['activ']==1 || $this->testActiv($pl['activ'])==1)
				{
					$lvar = $this->priemInfo($pl,1);
					$lvar = $lvar[0];
					$cl = ''; $a1 = '<a href="main.php?skills=1&all='.((int)$_GET['all']).'&rz=4&use_priem='.$pl['id'].'&rnd='.$code.'">'; $a2 = '</a>';
					
					//$cl = 'href="javascript:void(0)" onclick="location.href=\'main.php?skills=1&unuse_priem='.$i.'&rz=4&p_raz=\' + p_raz"';
					$a1 = '<a href="javascript:void(0)" onclick="location.href=\'main.php?skills=1&all='.((int)$_GET['all']).'&rz=4&use_priem='.$pl['id'].'&rnd='.$code.'&p_raz=\' + p_raz;">'; $a2 = '</a>';
					
					if($this->pyes($pl['id'])==true || $this->testpriem($pl,1)>0)
					{
						if((isset($_GET['all']) && $_GET['all'] == 1) || $this->pyes($pl['id'])==true) {
							$cl = 'filter: alpha(opacity=35); -moz-opacity: 0.35; -khtml-opacity: 0.35; opacity: 0.35;';
							$a1 = '';
							$a2 = '';
						}else{
							$noaki = 1;
						}
					}
					if($noaki == 0) {
						$mtnu = explode('_',$pl['img']);
						if($mtnu[0] != 'wis') {
							$mtnu = $mtnu[0];
						}else{
							$mtnu = 'wis_'.$mtnu[1];
						}
						echo $a1.'<img class="pwq'.$mtnu.' pwqall" onMouseOver="top.hi(this,\'(#'.$pl['id'].') <b>'.$pl['name'].'</b><Br>'.$lvar.'\',event,3,0,1,1,\'width:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();" style="margin-top:2px; '.$cl.' margin-left:1px;" src="http://img.xcombats.com/i/eff/'.$pl['img'].'.gif" width="40" height="25" />'.$a2;
					}
				}
			}
		}
	}
}

$priem = new priems;

?>