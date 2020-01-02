<?
if(!defined('GAME'))
{
	die();
}

ini_set('memory_limit','128M');

class battleClass
{
	
	public $e,                 //Ошибка (текст)
		   $cached = false,		//Кэширование данных
		   $expCoef = 100,       # % опыта в бою
		   $aBexp      = 0,    //Добавочный опыт в боях
		   $mainStatus = 1,    //Отображаем главное окно (1 - можно бить, 2 - ожидаем ход противника, 3 - Проиграли. Ожидаем завершения поединка)
		   $info = array(),    //Информация о поединке
		   $users = array(),   //Информация о пользователях в этом бою
		   $stats = array(),   //Информация о статах пользователей в этом бою
		   $uids = array(),    //Список пользователей и их id в stats или users пример id пользователя = 555 , то $uids[555] выдаст его порядковый номер в массиве users \ stats
		   $atacks = array(),  //Список ударов в этом бою (действующих)
		   $ga = array(),      //Список uid кто нанес удар и по кому  $ga[ {id кто ударил} ][ {id кого ударил} ]
		   $ag = array(),      //Список uid кто нанес удар и по кому  $ga[ {id кого ударили} ][ {id кто ударил} ] 
		   $na = 1,            //возможность использовать удар
		   $np = 1,            //возможность использовать приемы
		   $nm = 1,            //возможность использовать заклятия
		   $hodID = 0,
		   $stnZbVs = 0,
		   $bots = array(),    // ID ботов
		   $iBots = array(),   // i бота
		   $stnZb = array(),
		   $uAtc = array('id'=>0,'a'=>array(1=>0,2=>0,3=>0,4=>0,5=>0),'b'=>0), //Если игрок нанес удар
		   $lg_itm = array(0 => array('грудью','ребром руки','лбом','кулаком','ногой','левой ногой','правой ногой','коленом'),1 => array('ножом','тыльной стороной лезвия ножа','рукоятью ножа','лезвием ножа'),2 => array('сучковатой палкой','поленом','тяжелой дубиной','дубиной','рукоятью молота'),3 => array('секирой','топором','лезвием секиры','алебардой','тяжелым держаком','длинной секирой'),4 => array('ножнами','гардой','мечом','лезвием меча','рукоятью меча','тупым лезвием','острой стороной меча','огромным мечом'),5 => array('сучковатой палкой','посохом','тяжелой тростью','корявым посохом','основанием посоха'), 22=> array('костылем')), // Чем лупили
		   $lg_zon = array(1 => array ('в нос','в глаз','в челюсть','по переносице','в кадык','по затылку','в правый глаз','в левый глаз','в скулу'),2 => array ('в грудь','в корпус','в солнечное сплетение','в сердце','в область лопаток'),3 => array ('в бок','по желудку','по левой руке','по правой руке'),4 => array ('по <вырезано цензурой>','в пах','в промежность','по левой ягодице','по правой ягодице'),5 => array ('по ногам','в область правой пятки','в область левой пятки','по коленной чашечке','по икрам')); // Куда лупили
	public $is = array(),$items = array();
		
	//Очистка кэша для ...
		public $uclearc = array(),$ucleari = array();
		public function clear_cache($uid) {
			if( $uid > 0 && !isset($this->uclearc[$uid]) ) {
				$this->uclearc[$uid] = true;
				$this->ucleari[] = $uid;
			}
		}
		
		public function clear_cache_start() {
			$i = 0;
			while( $i < count($this->ucleari) ) {
				mysql_query('DELETE FROM `battle_cache` WHERE `uid` = "'.mysql_real_escape_string($this->ucleari[$i]).'"');
				$i++;
			}
		}
		
	//Расчет маг.крита
		public function magKrit($l2,$t)
		{
			$r = 0;
			$r = $l2*2-7;
			if($r>$t)
			{
				//магический промах (серый удар, в 2 раза меньше) 6%
				//250 ед. защиты от магии дает 1% шанса увернуться от магии
				//$r = -1; , промах --
				$r = 0;
			}else{
				//каждая владелка дает 3% шанс крита
				$r = ceil($t*0.75);
				if($r>30)
				{
					$r = 30;
				}
				if(rand(0,10000)<$r*100)
				{
					//крит удар
					$r = 1;
				}else{
					$r = 0;	
				}
			}
			return $r;
		}
		
		
	//Обновление НР
		public function hpRef() {
			
		}
		
	//Расчет опыта
		public function testExp($y,$s1,$s2,$id1,$id2)
		{
			if($y<0){ $y = 0; }
			$addExp = 0+($y/$s2['hpAll']*100);
			if($s2['hpNow']+10-$y<0)
			{
				$addExp = 0+(($s2['hpNow']+10)/$s2['hpAll']*100);
			}
			
			if($this->users[$this->uids[$s2['id']]]['host_reg'] == 'real_bot_user') {
				$addExp = floor($addExp*0.76);
			}
			
			if($addExp<0){ $addExp = 0; }						
			if($s2['levels']!='undefined')
			{
				$doexp = mysql_fetch_array(mysql_query('SELECT SUM(`items_main`.`price1`) FROM `items_users`,`items_main` WHERE `items_users`.`inOdet` > 0 AND `items_main`.`inSlot` < 50 AND `items_users`.`uid` = "'.$id2.'" AND `items_users`.`delete` = 0 AND `items_main`.`id` = `items_users`.`item_id` ORDER BY `items_main`.`inSlot` ASC LIMIT 50'));
				if($doexp[0]>0) {
					$doexp = floor($doexp[0]/15);
				}else{
					$doexp = 0;
				}
				$addExp = $addExp*(1+$s2['levels']['expBtlMax']+$doexp*1.47)/100;
				if( $this->users[$this->uids[$s2['id']]]['bot'] > 0 ) {
					$addExp = round($addExp/5);
				}
				unset($doexp);
			}else{
				$addExp = 0;
			}
					
			if($s1['level'] > $s2['level']){
				$minProc = 100 - 33*( $s1['level']-$s2['level'] );
				if($minProc < 1) {
					$minProc = 1;
				}
				$addExp = round($addExp/100*$minProc);
			}
			
			return $addExp;
		}
	
	//Добавляем опыт \ нанесенный урон
		public function takeExp($id,$y,$id1,$id2,$mgregen = false,$nobattle_uron = false)
		{
			global $u;
			if(isset($this->users[$this->uids[$id]]))
			{
				$s1 = $this->stats[$this->uids[$id1]];
				$s2 = $this->stats[$this->uids[$id2]];
				if($id1!=$id2)
				{
					$e = $this->testExp($y,$s1,$s2,$id1,$id2);
				}else{
					$e = 0;
				}
				
				$this->users[$this->uids[$id1]]['battle_exp'] += round($e*4,5);
				if($mgregen == false && $nobattle_uron == false) {
					$this->users[$this->uids[$id1]]['battle_yron'] += floor($y);
					if($this->stats[$this->uids[$id1]]['notactic'] != 1) {
						$this->users[$this->uids[$id1]]['tactic6'] += round(0.1*(floor($y)/$s2['hpAll']*100),5);
					}
				}
				
				if( $u->info['admin'] > 0 ) {
					echo '['.$id1.'.'.$id2.'. +'.round($e,5).']';
				}
				
				//if($y != 0) {
				//	$this->users[$this->uids[$id1]]['tactic6'] = -$y;
				//}
				//if($u->info['admin'] > 0 ) {
				//	echo '['.$id1.' ударил '.$id2.' и получил +'.$y.' к нанесенному урону]';
				//}
				
				$upd = mysql_query('UPDATE `stats` SET `last_hp` = "'.$this->users[$this->uids[$id1]]['last_hp'].'",`tactic6` = "'.$this->users[$this->uids[$id1]]['tactic6'].'",`battle_yron` = "'.$this->users[$this->uids[$id1]]['battle_yron'].'",`battle_exp` = "'.$this->users[$this->uids[$id1]]['battle_exp'].'" WHERE `id` = "'.((int)$id1).'" LIMIT 1');
				if(!$upd)
				{
					echo '[не удача при использовании приема]';	
				}else{
					$this->clear_cache( $id1 );
					$this->stats[$this->uids[$id1]]['tactic6'] = $this->users[$this->uids[$id1]]['tactic6'];
					if($id1==$u->info['id'])
					{
						$u->info['tactic6'] = $this->users[$this->uids[$id1]]['tactic6'];
						$u->stats['tactic6'] = $this->users[$this->uids[$id1]]['tactic6'];
					}
				}
				unset($s1,$s2);
			}
		}
		
	//JS информация о игроке
		public function myInfo($id,$t)
		{
			global $c,$u;
			if(isset($this->users[$this->uids[$id]]) || $u->info['id'] == $id)
			{
				if($u->info['id'] == $id || ($u->info['enemy'] == $id && $id > 0)) {
					//Всегда обновляем
					$this->users[$this->uids[$id]] = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id` = "'.$id.'" LIMIT 1'));
					$this->stats[$this->uids[$id]] = $u->getStats($this->users[$this->uids[$id]],0);
					$this->stats[$this->uids[$id]]['items'] = $this->stats[$this->uids[$id]]['items'];
					$this->stats[$this->uids[$id]]['effects'] = $this->stats[$this->uids[$id]]['effects'];
					/*
					$ur   = $this->users[$this->uids[$id]];
					$st   = $this->stats[$this->uids[$id]];
					$itm  = $this->stats[$this->uids[$id]]['items'];
					$eff  = $this->stats[$this->uids[$id]]['effects'];
					*/
				}
				$ur   = $this->users[$this->uids[$id]];
				$st   = $this->stats[$this->uids[$id]];
				$itm  = $this->stats[$this->uids[$id]]['items'];
				$eff  = $this->stats[$this->uids[$id]]['effects'];
				$ef   = '';
				$i    = 0;
				while($i!=-1)
				{
					$nseef = 0;
					if($this->users[$this->uids[$ur['id']]]['id'] != $u->info['id'] && $ur['id'] != 0)
					{
						if($this->stats[$this->uids[$ur['id']]]['seeAllEff'] != 1) {
							$nseef = 1;
							if($eff[$i]['v1']=='priem')
							{
								$eff[$i]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i]['v2'].'" LIMIT 1'));
							}
							if(isset($eff[$i]['priem']['id']) && $eff[$i]['priem']['neg']==1)
							{
								$nseef = 0;
							}
						}
					}
					
					if(isset($eff[$i]) && $eff[$i] != 'delete')
					{
						if($nseef == 0)
						{
							
							$ei = '<b><u>'.$eff[$i]['name'].'</u></b>';
							if($eff[$i]['x']>1)
							{
								//$ei .= ' [x'.$eff[$i]['x'].'] ';	
								$ei .= ' x'.$eff[$i]['x'].' ';	
							}
							if($eff[$i]['type1']>0 && $eff[$i]['type1']<7)
							{
								$ei .= ' (Эликсир)';
							}elseif(($eff[$i]['type1']>6 && $eff[$i]['type1']<11) || $eff[$i]['type1']==16)
							{
								$ei .= ' (Заклятие)';
							}elseif($eff[$i]['type1']==14)
							{
								$ei .= ' (Прием)';
							}elseif($eff[$i]['type1']==15)
							{
								$ei .= ' (Изучение)';
							}elseif($eff[$i]['type1']==17)
							{
								$ei .= ' (Проклятие)';
							}elseif($eff[$i]['type1']==18 || $e['type1']==19)
							{
								$ei .= ' (Травма)';
							}elseif($eff[$i]['type1']==20)
							{
								$ei .= ' (Пристрастие)';
							}elseif($eff[$i]['type1']==22)
							{
								$ei .= ' (Ожидание)';
							}else{
								$ei .= ' (Эффект)';
							}
							$ei .= '<br>';
							
							$out = '';
							$time_still = ($eff[$i]['timeUse']+($eff[$i]['timeAce']-$eff[$i]['timeUse'])+$eff[$i]['actionTime']);
							if($eff[$i]['timeAce'] == 0) {
								$time_still += $eff[$i]['timeUse'];
							}
							$time_still -= time();
							if($eff[$i]['bp']==0 && $eff[$i]['timeUse']!=77)
							{
								if($eff[$i]['type1']!=13)
								{
									/*$tmp = floor($time_still/2592000);
									$id=0;
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." мес. ";}
										$time_still = $time_still-$tmp*2592000;
									}
									$tmp = floor($time_still/604800);
									if ($tmp > 0) { 
									$id++;
									if ($id<3) {$out .= $tmp." нед. ";}
									$time_still = $time_still-$tmp*604800;
									}
									$tmp = floor($time_still/86400);
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." дн. ";}
										$time_still = $time_still-$tmp*86400;
									}
									$tmp = floor($time_still/3600);
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." ч. ";}
										$time_still = $time_still-$tmp*3600;
									}
									$tmp = floor($time_still/60);
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." мин. ";}
									}
									if($out=='')
									{
										$out = $time_still.' сек.';
									}*/
									$ei .= 'Осталось: '.$u->timeOut($time_still).'';
								}
							}else{
								if($eff[$i]['timeUse']!=77)
								{
									$ei .= 'Осталось: '.$u->timeOut($time_still).'';
									//$ei .= 'Зарядов: '.$out.'<br>';
								}elseif($eff[$i]['hod']>=0)
								{
									$ei .= 'Зарядов: '.$eff[$i]['hod'].'<br>';	
								}
							}
							
							if($eff[$i]['user_use']!='')
							{
								if($this->users[$this->uids[$eff[$i]['user_use']]]['login2']!='') {
									$ei .= 'Автор: <b>'.$this->users[$this->uids[$eff[$i]['user_use']]]['login2'].'</b>';
								}elseif($this->users[$this->uids[$eff[$i]['user_use']]]['login']!='') {
									$ei .= 'Автор: <b>'.$this->users[$this->uids[$eff[$i]['user_use']]]['login'].'</b>';
								}
							}
							
							//Действие эффекта
							$tr = '';
							$ti = $u->items['add'];
							$x = 0; $ed = $u->lookStats($eff[$i]['data']);	
							while($x<count($ti))
							{
								$n = $ti[$x];
								if(isset($ed['add_'.$n],$u->is[$n]) && $n!='pog')
								{
									$z = '';
									if($ed['add_'.$n]>0)
									{
										$z = '+';
									}
									$tr .= '<br>'.$u->is[$n].': '.$z.''.$ed['add_'.$n];
								}
								$x++;
							}
							if(isset($ed['add_pog']))
							{
								$tr .= '<br>Магический барьер способен поглотить еще <b>'.$ed['add_pog'].'</b> ед. урона';
							}
							if(isset($ed['add_pog2']))
							{
								$tr .= '<br>Магический барьер способен поглотить еще <b>'.$ed['add_pog2'].'</b> ед. урона <small>('.$ed['add_pog2p'].'%)</small>';
							}
							
							if($tr!='')
							{
								$ei .= $tr;
							}
							if($eff[$i]['info']!='')
							{
								$ei .= '<br><i>Информация:</i><br>'.$eff[$i]['info'];
							}

							//$ef .= '<img width=\"38\" style=\"margin:1px;display:block;float:left;\" src=\"http://img.xcombats.com/i/eff/'.$eff[$i]['img'].'\" onMouseOver=\"top.hi(this,\''.$ei.'\',event,0,1,1,1,\'\');\" onMouseOut=\"top.hic();\" onMouseDown=\"top.hic();\" />';
							$ef .= '<div class=\"pimg\" col=\"'.$eff[$i]['x'].'\" stl=\"0\" stt=\"'.$ei.'\"><img src=\"http://img.xcombats.com/i/eff/'.$eff[$i]['img'].'\"/></div>';
						}
					}elseif($eff[$i]!='delete')
					{
						$i = -2;
					}
					$i++;
				}
				
				$ca = '';
				if($ur['clan']>0)
				{
					$cl = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.$ur['clan'].'" LIMIT 1'));
					if(isset($cl['id']))
					{
						$ca = '<img src=\"http://img.xcombats.com/i/clan/'.$cl['name_mini'].'.gif\" title=\"'.$cl['name'].'\">';
					}
				}
				if($ur['align']>0)
				{
					$ca = '<img src=\"http://img.xcombats.com/i/align/align'.$ur['align'].'.gif\">'.$ca;
				}
				if($ur['login2']=='')
				{
					$ur['login2'] = $ur['login'];
				}
				if(floor($st['hpNow'])>$st['hpAll'])
				{
					$st['hpNow'] = $st['hpAll'];
				}
				if(floor($st['mpNow'])>$st['mpAll'])
				{
					$st['mpNow'] = $st['mpAll'];
				}
				$stsua  = '<b>'.$ur['login2'].'</b>';
				$stsua .= '<br>Сила: '.$st['s1'];
				$stsua .= '<br>Ловкость: '.$st['s2'];
				$stsua .= '<br>Интуиция: '.$st['s3'];
				$stsua .= '<br>Выносливость: '.$st['s4'];
				if($st['s5'] != 0) {
					$stsua .= '<br>Интелект: '.$st['s5'];
				}
				if($st['s6'] != 0) {
					$stsua .= '<br>Мудрость: '.$st['s6'];
				}
				
				$info = 'info_reflesh('.$t.','.$ur['id'].',"'.$ca.'<a href=\"javascript:void(0)\" onclick=\"top.chat.addto(\''.$ur['login2'].'\',\'to\');return false;\">'.$ur['login2'].'</a> ['.$ur['level'].']<a href=\"info/'.$ur['id'].'\" target=\"_blank\"><img src=\"http://img.xcombats.com/i/inf_'.$ur['cityreg'].'.gif\" title=\"Инф. о '.$ur['login2'].'\"></a>&nbsp;","'.$ur['obraz'].'",'.floor($st['hpNow']).','.floor($st['hpAll']).','.floor($st['mpNow']).','.floor($st['mpAll']).',0,'.$ur['sex'].',"'.$ef.'","'.$stsua.'");shpb();';
				$i = 0;
				while($i<count($itm))
				{
					//генерируем предметы
					$ttl = '<b>'.$itm[$i]['name'].'</b>';
					$td = $u->lookStats($itm[$i]['data']);				
					$lvar = '';
					if($td['add_hpAll']>0)
					{
						if($td['add_hpAll']>0)
						{
							$td['add_hpAll'] = '+'.$td['add_hpAll'];
						}
						$lvar .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
					}
					if($td['sv_yron_max']>0 || $td['sv_yron_min']>0)
					{
						$lvar .= '<br>Урон: '.(0+$td['sv_yron_min']).'-'.(0+$td['sv_yron_max']).'';
					}
					if($td['add_mab1']>0)
					{
						if($td['add_mib1']==$td['add_mab1'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
							$lvar .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
						}else{
							$lvar .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
						}
					}
					if($td['add_mab2']>0)
					{
						if($td['add_mib2']==$td['add_mab2'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
							$lvar .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
						}else{
							$lvar .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
						}
					}
					if($td['add_mab3']>0)
					{
						if($td['add_mib3']==$td['add_mab3'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
							$lvar .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
						}else{
							$lvar .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
						}
					}
					if($td['add_mab4']>0)
					{
						if($td['add_mib4']==$td['add_mab4'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab4']<0){ $m1l = ''; }
							$lvar .= '<br>Броня ног: '.$m1l.''.(0+$td['add_mab4']).'';
						}else{
							$lvar .= '<br>Броня ног: '.(0+$td['add_mib4']).'-'.(0+$td['add_mab4']).'';
						}
					}				
					if($itm[$i]['iznosMAX']>0)
					{
						if($itm[$i]['iznosMAXi'] == 999999999) {
							$lvar .= '<br>Долговечность: <font color=brown>неразрушимо</font>';
						}else{
							$lvar .= '<br>Долговечность: '.floor($itm[$i]['iznosNOW']).'/'.floor($itm[$i]['iznosMAX']);
						}
					}
					$ttl .= $lvar;
					$ccv = '';
					
					if($itm[$i]['magic_inci']!='' || $itm[$i]['magic_inc']!='') {
						if($itm[$i]['magic_inc'] == '') {
							$itm[$i]['magic_inc'] = $itm[$i]['magic_inci'];
						}
						$mgi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$itm[$i]['magic_inc'].'" AND `type1` = "12345" LIMIT 1'));
						if(isset($mgi['id2'])) {
							$mgilog = '';
							$ccv .= 'top.useMagicBattle(\''.$mgi['mname'].'\','.$itm[$i]['id'].',\''.$mgi['img'].'\',1,2);';
						}
					}
					
					$info .= 'abitms('.(0+$t).','.(0+$itm[$i]['uid']).','.(0+$itm[$i]['id']).','.(0+$itm[$i]['inOdet']).',"'.$itm[$i]['name'].'","'.$ttl.'","'.$itm[$i]['img'].'","'.$ccv.'");';
					$i++;
				}
				
				return $info;
			}else{
				return false;
			}
		}
				
	//Проверяем завершение боя
		public function testFinish()
		{
			global $u;

			if($this->info['team_win']==-1)
			{
				
				$hp = array();
				$tml = array();
				$tmv = array();
				$tl = 0;
				$i = 0;
				$j = 0;
				while($i<count($this->uids))
				{
					if($this->stats[$i]['id']>0)
					{
						$hp[$this->users[$i]['team']] += $this->stats[$i]['hpNow'];
						if(!isset($tml[$this->users[$i]['team']]) && $this->stats[$i]['hpNow']>=1)
						{
							$tml[$this->users[$i]['team']] = 1;
							$tmv[$j] = $this->users[$i]['team'];
							$tl++;
						}
					}
					$i++;
				}

				if($tl<=1)
				{
					//завершаем поединок, кто-то один победил, либо ничья
					
					$i = 0; $tmwin = 0;
					while($i<count($tmv))
					{
						if($tmv[$i]>=1 && $tml[$tmv[$i]]>0)
						{
							$tmwin = $tmv[$i];
						}
						$i++;
					}

					if($this->info['izlom'] == 0) {
						$rs = ''; $ts = array(); $tsi = 0;
						if($this->info['id']>0)
						{
							//данные о игроках в бою
							unset($this->users,$this->stats,$this->uids,$this->bots,$this->iBots);
							$trl = mysql_query('SELECT `u`.`no_ip`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`u`.`sex`,`u`.`money`,`u`.`bot_id` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'" ORDER BY `st`.`bot` DESC');
							$ir = 0; $bi = 0;
							$this->users = NULL;
							$this->stats = NULL;
							$this->uids = NULL;
							$this->bots = NULL;
							$this->iBots = NULL;
							while($pl = mysql_fetch_array($trl))
							{
								//записываем данные
								if($pl['login2']=='')
								{
									$pl['login2'] = $pl['login'];
								}
								$this->users[$ir] = $pl;
								$this->uids[$pl['id']] = $ir;
								if($pl['bot']>0)
								{
									$this->bots[$bi] = $pl['id'];
									$this->iBots[$pl['id']] = $bi;
									$bi++;
								}
								//записываем статы
								$this->stats[$ir] = $u->getStats($pl,0);
								$ir++;
							}
						}
					}elseif(!isset($this->uids[$u->info['id']])) {
						$rs = ''; $ts = array(); $tsi = 0;
						if($this->info['id']>0)
						{
							//данные о игроках в бою
							$trl = mysql_query('SELECT `u`.`no_ip`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`u`.`sex`,`u`.`money`,`u`.`bot_id` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id` = "'.$this->info['id'].'" LIMIT 1');
							$pl = mysql_fetch_array($trl);
								//записываем данные
								if($pl['login2']=='')
								{
									$pl['login2'] = $pl['login'];
								}
								$this->users[count($this->users)] = $pl;
								$this->uids[$pl['id']] = $ir;
								if($pl['bot']>0)
								{
									$this->bots[count($this->bots)] = $pl['id'];
									$this->iBots[$pl['id']] = $bi;
								}
								//записываем статы
								$this->stats[count($this->stats)] = $u->getStats($pl,0);
						}
					}
					

					
					if($this->info['izlom']>0 && $tmwin==1)
					{
						
						// выкидываем ботов из боя
						$i = 0; $dlt = ''; $dlt2 = '';
						$sp = mysql_query('SELECT `users`.`id`,`stats`.`bot`,`stats`.`team` FROM `users`,`stats` WHERE `users`.`battle` = "'.$this->info['id'].'" AND `stats`.`id` = `users`.`id` LIMIT 10000');
						while($pl = mysql_fetch_array($sp))
						{
							if($pl['bot']==1 &&$pl['team'] != $u->info['team'])
							{
								$dlt .= ' `id`="'.$pl['id'].'" OR';
								$dlt2 .= ' `uid`="'.$pl['id'].'" OR';
								$i++;
							}
							
						}
						
						if($i > 0) {
							$dlt = trim($dlt,'OR');
							$dlt2 = trim($dlt2,'OR');
							mysql_query('DELETE FROM `users` WHERE '.$dlt.' LIMIT '.$i);
							mysql_query('DELETE FROM `stats` WHERE '.$dlt.' LIMIT '.$i);
							mysql_query('DELETE FROM `items_users` WHERE '.$dlt2.' LIMIT '.($i*100));
							mysql_query('DELETE FROM `eff_users` WHERE '.$dlt2.' LIMIT '.($i*100));
						}
						
						unset($i,$dlt,$dlt2);
						
						//Это излом, добавляем еще ботов
						$mz = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `izlom` WHERE `izlom` = "'.$this->info['izlom'].'" AND `level` = "'.$this->info['izlomLvl'].'" LIMIT 50'));
						$mz = $mz[0];
						$pz = $this->info['izlomRound']+rand(1,3);
						if($pz/$mz>1)
						{
							$zz = floor($pz/$mz);
							$pz = $pz-($zz*$mz);
						}
						$iz = mysql_fetch_array(mysql_query('SELECT * FROM `izlom` WHERE `izlom` = "'.$this->info['izlom'].'" AND `level` = "'.$this->info['izlomLvl'].'" AND `round` = "'.$pz.'" LIMIT 1'));
						$i = 0; $bots = $iz['bots']; $bots = explode('|',$bots); $j = 0; $k = 0; $obr = 0;
						$logins_bot = array();
						while($i<count($bots))
						{
							if($bots[$i]>0)
							{
								$k = $u->addNewbot($bots[$i],NULL,NULL,$logins_bot);
								if($k!=false)
								{
									$logins_bot = $k['logins_bot'];
									$upd = mysql_query('UPDATE `users` SET `battle` = "'.$this->info['id'].'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
									if($upd)
									{
										$upd = mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
										if($upd)
										{
											$j++; if(rand(0,10000) < 1500){ $obr++; }
										}
									}
								}
							}
							$i++;
						}	
						unset($logins_bot);
						if($j==0)
						{
							//конец излома
							$this->finishBattle($tml,$tmv,NULL);	
							$fin1 = mysql_query('INSERT INTO `izlom_rating` (`uid`,`time`,`voln`,`level`,`bots`,`rep`,`obr`) VALUES ("'.$u->info['id'].'","'.time().'","'.$this->info['izlomRoundSee'].'","'.$this->info['izlomLvl'].'","0","0","'.($this->info['izlomObr']-$this->info['izlomObrNow']).'")');
						}else{
							$this->info['izlomRound'] = $iz['round'];
							mysql_query('UPDATE `battle` SET `izlomObrNow` = '.$obr.',`izlomObr` = `izlomObr` + '.$obr.',`timeout` = (`timeout`+5),`izlomRound` = "'.($this->info['izlomRound']+1).'",`izlomRoundSee` = `izlomRoundSee`+1 WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
							$this->stats[$this->uids[$u->info['id']]]['hpNow'] += $this->stats[$this->uids[$u->info['id']]]['hpAll']*0.25;
							$this->stats[$this->uids[$u->info['id']]]['mpNow'] += $this->stats[$this->uids[$u->info['id']]]['mpAll']*0.25;
							$this->users[$this->uids[$u->info['id']]]['hpNow'] = $this->stats[$this->uids[$u->info['id']]]['hpAll'];
							$this->users[$this->uids[$u->info['id']]]['mpNow'] = $this->stats[$this->uids[$u->info['id']]]['mpAll'];
							mysql_query('UPDATE `stats` SET `hpNow` = "'.($u->info['hpNow']+($u->stats['hpAll']*0.25)).'",`mpNow` = "'.($u->info['mpNow']+($u->stats['mpAll']*0.25)).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}				
					}else{
						//завершаем бой
						$this->finishBattle($tml,$tmv,NULL);	
						if($this->info['izlom']>0)
						{
							$fin1 = mysql_query('INSERT INTO `izlom_rating` (`uid`,`time`,`voln`,`level`,`bots`,`rep`,`obr`) VALUES ("'.$u->info['id'].'","'.time().'","'.$this->info['izlomRoundSee'].'","'.$this->info['izlomLvl'].'","0","0","'.($this->info['izlomObr']-$this->info['izlomObrNow']).'")');
						}
					}
					if(isset($fin1))
					{
						mysql_query('INSERT INTO `eff_users` (`id_eff`,`overType`,`uid`,`name`,`data`,`timeUse`) VALUES ("31","23","'.$u->info['id'].'","Касание Хаоса","","'.time().'")');
						mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','Вы создали предмет &quot;Образец&quot;x".($this->info['izlomObr']-$this->info['izlomObrNow'])."','-1','10','0')");
						$i01 = 1;
						while($i01<=($this->info['izlomObr']-$this->info['izlomObrNow']))
						{				
							$u->addItem(1226,$u->info['id'],'|sudba='.$u->info['login']);
							$i01++;
						}
						unset($fin1);
					}
				}
			}else{
				$this->finishBattle(NULL,NULL,10);
			}
		}
		
	//завершение поединка
		public function finishBattle($t,$v,$nl)
		{
			global $magic,$u,$q;
			
			$frtu = false;
			
			$lock_file = 'lock/btl_finish_'.$this->info['id'].'.bk2'; 
			if ( !file_exists($lock_file) ) { 
				$fp_lock = fopen($lock_file, 'w');
				flock($fp_lock, LOCK_EX); 
			}else{
				$frtu = true;
			}
			
			if($frtu == false) {
			
				if($nl!=10)
				{
					$i = 0; $dnr = 0;
					if($this->info['team_win']==-1)
					{
						$this->info['team_win'] = 0;
						while($i<count($v))
						{
							if($v[$i]>=1 && $t[$v[$i]]>0)
							{
								$this->info['team_win'] = $v[$i];
							}
							$i++;
						}														
					}
				}
				mysql_query('LOCK TABLES users,stats,battle,battle_last,battle_end,chat WRITE');
				
				//данные о игроках в бою
				$t = mysql_query('SELECT `u`.`city`,`u`.`room`,`u`.`no_ip`,`u`.`pass`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`u`.`sex`,`u`.`money`,`u`.`bot_id` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'"');
				$i = 0; $bi = 0;
				while($pl = mysql_fetch_array($t))
				{
					//записываем данные
					if($pl['login2']=='')
					{
						$pl['login2'] = $pl['login'];
					}
					$this->users[$i] = $pl;
					$this->uids[$pl['id']] = $i;
					if($pl['bot']>0)
					{
						$this->bots[$bi] = $pl['id'];
						$this->iBots[$pl['id']] = $bi;
						$bi++;
					}
					//записываем статы
					$this->stats[$i] = $u->getStats($pl,0);
					$i++;
				}
			
			if($this->info['time_over']==0)
			{
				$tststrt = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `time_over` = "0" LIMIT 1'));
				if(isset($tststrt['id'])) {
					if( $this->info['inTurnir'] == 0 ) {
						mysql_query('UPDATE `battle` SET `time_over` = "'.time().'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					}
					//Заносим данные о завершении боя
					$i = 0; $vl = ''; $vtvl = ''; $relu = 0;
					while($i<count($this->users))
					{
						if( $this->user[$i]['clon'] == 0 && $this->user[$i]['bot'] == 0 ) {
							$relu++;
						}
						$vl .= '("'.$this->users[$i]['login'].'","'.$this->users[$i]['city'].'","'.$this->info['id'].'","'.$this->users[$i]['id'].'","'.time().'","'.$this->users[$i]['team'].'","'.$this->users[$i]['level'].'","'.$this->users[$i]['align'].'","'.$this->users[$i]['clan'].'","'.$this->users[$i]['exp'].'","'.$this->users[$i]['bot'].'","'.$this->users[$i]['money'].'"),';
						if($this->users[$i]['team'] == $this->info['team_win'] && $this->info['team_win'] > 0) {
							$vtvl .= '<b>'.$this->users[$i]['login'].'</b>, ';
						}
						$i++;
					}
					
					$this->info['players_c'] = $relu;
					mysql_query('UPDATE `battle` SET `players_c` = "'.$this->info['players_c'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					
					if($vtvl != '') {
						$vtvl = rtrim($vtvl,', ');
						$vtvl = str_replace('"','\\\\\"',$vtvl);
						$this->hodID++;
						$vLog = 'time1='.time();
						$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						$vtvl = 'Бой закончен, победа за '.$vtvl.'.';
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
					}else{
						$this->hodID++;
						$vLog = 'time1='.time();
						$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						$vtvl = 'Бой закончен, ничья.';
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
					}
					
					if( $this->info['type'] == 99 ) {
						//$this->hodID++;
						$vLog = 'time1='.time();
						$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						$vtvl = 'И победители стали калечить проигравших...';
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
						$i = 0; $vtvl = '';
						while($i<count($this->users)) {
							if( $this->users[$i]['team'] != $this->info['team_win'] ) {
								if( $this->users[$i]['sex'] == 1 ) {
									$vtvl = '<b>'.$this->users[$i]['login'].'</b> получила повреждение: <font color=red>тяжелую травму</font>.<br>'.$vtvl;
								}else{
									$vtvl = '<b>'.$this->users[$i]['login'].'</b> получил повреждение: <font color=red>тяжелую травму</font>.<br>'.$vtvl;
								}
							}
							$i++;
						}
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
					}
					
					if($vl!='')
					{
						$vl = rtrim($vl,',');
						mysql_query('INSERT INTO `battle_last` (`login`,`city`,`battle_id`,`uid`,`time`,`team`,`lvl`,`align`,`clan`,`exp`,`bot`,`money`) VALUES '.$vl.'');
					}
					
					mysql_query('INSERT INTO `battle_end` (`battle_id`,`city`,`time`,`team_win`) VALUES ("'.$this->info['id'].'","'.$this->info['city'].'","'.$this->info['time_start'].'","'.$this->info['team_win'].'")');
				}
								
				//Турнир БС
				if( $this->info['inTurnir'] > 0 ) {
					$bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `id` = "'.$this->info['inTurnir'].'" LIMIT 1'));
					$i = 0; $j = 0;
					while($i<count($this->users)) {
						if( $this->stats[$i]['hpNow'] < 1 && $this->users[$i]['clone'] == 0 && $this->stats[$i]['clone'] == 0 ) {
							//Удаляем из БС
							//echo '['.$this->users[$i]['login'].']';
							//Добавляем в лог БС
							if( $this->users[$i]['sex'] == 0 ) {
								$text .= '{u1} повержен и выбывает из турнира';
							}else{
								$text .= '{u1} повержена и выбывает из турнира';
							}
							//Выкидываем предметы с персонажа
							$spik = mysql_query('SELECT `id`,`item_id` FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `delete` ="0"');
							while( $plik = mysql_fetch_array($spik) ) {
								mysql_query('INSERT INTO `bs_items` (`x`,`y`,`bid`,`count`,`item_id`) VALUES (
									"'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'","'.$bs['id'].'","'.$bs['count'].'","'.$plik['item_id'].'"
								)');
							}
							unset($spik,$plik);
							//
							$usrreal = '';
							$usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `login` = "'.$this->users[$i]['login'].'" AND `inUser` = "'.$this->users[$i]['id'].'" LIMIT 1'));
							if( !isset($usr_real['id']) ) {
								$usr_real = $this->users[$i];
							}
							if( isset($usr_real['id'])) {
								$usrreal = '';
								if( $usr_real['align'] > 0 ) {
									$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >';
								}
								if( $usr_real['clan'] > 0 ) {
									$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >';
								}
								$usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
							}else{
								$mereal = '<i>Невидимка</i>[??]';
							}
							$text = str_replace('{u1}',$usrreal,$text);
							mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
								"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
								"'.round($bs['money']*0.85,2).'","'.$i.'"
							)');
							//
							//Удаление клона
							mysql_query('DELETE FROM `users` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							mysql_query('DELETE FROM `stats` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							//Обновление персонажа
							mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `login` = "'.$this->users[$i]['login'].'" OR `inUser` = "'.$this->users[$i]['id'].'" LIMIT 1');
							//Обновляем заявку
							mysql_query('UPDATE `bs_zv` SET `off` = "'.time().'" WHERE `inBot` = "'.$this->users[$i]['id'].'" AND `off` = "0" LIMIT 1');
							unset($text,$usrreal,$usr_real);
							if( $this->users[$i]['pass'] != 'bstowerbot' ) {
								$bs['users']--;
								$bs['users_finish']++;
							}else{
								$bs['arhiv']--;
							}
							$j++;
						}
						$i++;
					}
					if( $j > 0 ) {
						mysql_query('UPDATE `bs_turnirs` SET `arhiv` = "'.$bs['arhiv'].'",`users` = "'.$bs['users'].'",`users_finish` = "'.$bs['users_finish'].'" WHERE `id` = "'.$bs['id'].'" LIMIT 1');
					}
					unset($bs,$j);
				}
				
				//Награда за события
				if( $this->info['type'] == 500 && isset($tststrt['id']) ) {
					$i = 0;
					while($i < count($this->users)) {
						if( $this->users[$i]['no_ip'] == 'trupojor' ) {
							$mon = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_monsters` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 1'));
							if( isset($mon['id']) ) {
								if( $this->info['team_win'] == 0 ) {
									//Ничья
									mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats['hpAll'].'",`mpNow` = "'.$this->stats['mpAll'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>Внимание!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$this->users[$i]['login'].'</b> ['.$this->users[$i]['level'].']<a target=_blank href=info/'.$this->users[$i]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>',$mon['nich_text'])).' ","'.$this->users[$i]['city'].'","","6","1","'.time().'")');
								}elseif( $this->info['team_win'] == $this->stats[$i]['team'] ) {
									//Проиграли
									if( $mon['win_back'] == 1 ) {
										mysql_query('UPDATE `users` SET `room` = "303" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									}
									mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats['hpAll'].'",`mpNow` = "'.$this->stats['mpAll'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>Внимание!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$this->users[$i]['login'].'</b> ['.$this->users[$i]['level'].']<a target=_blank href=info/'.$this->users[$i]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>',$mon['lose_text'])).' ","'.$this->users[$i]['city'].'","","6","1","'.time().'")');
								}else{
									//Выиграли
									$j = 0; $usrwin = '';
									while($j < count($this->users)) {
										if( $this->users[$j]['no_ip'] != 'trupojor' && $this->users[$j]['bot'] == 0 ) {
											//Выдаем предметы
											//addGlobalItems($uid,$itm,$eff,$ico,$exp,$cr,$ecr)
											$this->addGlobalItems($this->users[$i]['id'],$this->users[$j]['id'],$mon['win_itm'],$mon['win_eff'],$mon['win_ico'],$mon['win_exp'],$mon['win_money1'],$mon['win_money2']);
											if( $this->stats[$j]['hpNow'] > 0 ) {
												$usrwin .= ', ';
												if( $this->users[$j]['align'] > 0 ) {
													$usrwin .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$this->users[$j]['align'].'.gif >';
												}
												if( $this->users[$j]['clan'] > 0 ) {
													$usrwin .= '<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$this->users[$j]['clan'].'.gif >';
												}
												$usrwin .= '<b>'.$this->users[$j]['login'].'</b> ['.$this->users[$j]['level'].']<a target=_blank href=info/'.$this->users[$j]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
											}
										}
										$j++;
									}
									if( $usrwin != '' ) {
										$usrwin = ltrim($usrwin,', ');
									}else{
										$usrwin = '<i>Команде героев</i>';
									}
									mysql_query('UPDATE `users` SET `room` = "303" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `res_x` = "'.(time() + 3600).'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>Внимание!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$this->users[$i]['login'].'</b> ['.$this->users[$i]['level'].']<a target=_blank href=info/'.$this->users[$i]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>', str_replace('{u}',$usrwin,$mon['win_text'])  )).' ","'.$this->users[$i]['city'].'","","6","1","'.time().'")');
									unset($usrwin);
								}
							}
						}
						$i++;
					}
				}
			}
			
			// выкидываем ботов из боя
			$i = 0; $botsi = 0;
			while($i<count($this->users))
			{
				if($this->users[$i]['bot']==2)
				{
					$this->users[$i]['battle'] = 0;
					mysql_query('UPDATE `users` SET `battle` = "0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `zv` = "0",`team` = "0",`exp` = `exp` + `battle_exp`,`battle_exp` = "0",`timeGo` = "'.time().'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 100');
				}elseif($this->users[$i]['bot']==1)
				{
					$botsi++;
					mysql_query('DELETE FROM `users` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `stats` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 100');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 100');
				}
				if($this->users[$i]['clone']>0 && $this->users[$i]['bot']>0 && isset($this->users[$this->uids[$this->users[$i]['clone']]]['id']) && $this->users[$this->uids[$this->users[$i]['clone']]]['team']!=$this->users[$i]['team'])
				{
					//Добавляем что клон побежден
					if($this->users[$this->uids[$this->users[$i]['clone']]]['team']==$this->info['team_win'])
					{
						$u->addAction(time(),'win_bot_clone','',$this->users[$i]['clone']);
					}elseif($this->info['team_win']==0){
						$u->addAction(time(),'nich_bot_clone','',$this->users[$i]['clone']);
					}else{
						$u->addAction(time(),'lose_bot_clone','',$this->users[$i]['clone']);
					}
				}elseif($this->users[$i]['bot']>0 && $this->users[$i]['bot_id']>0){
					//Добавляем что бота победили
					$j = 0;
					while($j<count($this->users))
					{
						if($this->users[$j]['bot']==0 && $this->users[$j]['team']!=$this->users[$i]['team'])
						{
							if($this->users[$j]['team']==$this->info['team_win'])
							{
								$u->addAction(time(),'win_bot_'.$this->users[$i]['bot_id'],'',$this->users[$j]['id']);
							}elseif($this->info['team_win']==0){
								$u->addAction(time(),'nich_bot_'.$this->users[$i]['bot_id'],'',$this->users[$j]['id']);
							}else{
								$u->addAction(time(),'lose_bot_'.$this->users[$i]['bot_id'],'',$this->users[$j]['id']);
							}
						}
						$j++;
					}
				}
				$i++;
			}
			$botss = array();
			if(true==true)
			{				
				if($nl!=10)
				{				
					//Из бота падают предметы
					if($this->info['dungeon']>0)
					{
						if($this->info['team_win']==$u->info['team'] && $this->info['dungeon'] == 102)
						{
							$j1 = mysql_fetch_array(mysql_query('SELECT * FROM `laba_obj` WHERE `type` = 2 AND `lib` = "'.$this->info['dn_id'].'" AND `x` = "'.$this->info['x'].'" AND `y` = "'.$this->info['y'].'" LIMIT 1'));
							if( isset($j1['id']) ) {
								mysql_query('DELETE FROM `laba_obj` WHERE `id` = "'.$j1['id'].'" LIMIT 1');
								//Выпадает шмотка
								mysql_query('INSERT INTO `laba_obj` (`use`,`lib`,`time`,`type`,`x`,`y`,`vars`) VALUES (
									"0","'.$j1['lib'].'","'.time().'","6","'.$j1['x'].'","'.$j1['y'].'","'.(0+$botsi).'"
								)');
							}
						}elseif($this->info['team_win']==$u->info['team'])
						{
							//выйграли люди, выкидываем предметы из мобов					
							$j1 = mysql_query('SELECT * FROM `dungeon_bots` WHERE `dn` = "'.$this->info['dn_id'].'" AND `for_dn` = "0" AND `x` = "'.$this->info['x'].'" AND `delete` = "0" AND `y`= "'.$this->info['y'].'" LIMIT 100');
							while($tbot = mysql_fetch_array($j1))
							{
								if(isset($tbot['id2']))
								{
									$tbot2 = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$tbot['id_bot'].'" LIMIT 1'));
									$itms = explode('|',$tbot2['p_items']);
									$tii = 0;
									while($tii<count($itms))
									{
										$itmz = explode('=',$itms[$tii]);
										if($itmz[0]>0)
										{
											//Добавляем этот предмет в зону Х и У
											if($itmz[1]*100000>=rand(1,10000000))
											{
												$tou = 0; //какому юзеру предназначено
												/* выделяем случайного юзера из команды */
												$itmnm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$itmz[0].'" LIMIT 1'));
												$itmnm = $itmnm['name'];
												
												$rtxt = 'У <b>'.$tbot2['login'].'</b> был предмет &quot;'.$itmnm.'&quot; и кто угодно может поднять его';
												mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$this->info['dn_id']."','".$this->users[0]['city']."','".$this->users[0]['room']."','','','".$rtxt."','".time()."','6','0','1','1')");
												
												$ins = mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`) VALUES (
												"'.$this->info['dn_id'].'",
												"'.$tou.'",
												"'.$itmz[0].'",
												"'.time().'",
												"'.$this->info['x'].'",
												"'.$this->info['y'].'")');
											}
										}
										$tii++;	
									}
								}
							}
							mysql_query('UPDATE `dungeon_bots` SET `delete` = "'.time().'" WHERE `dn` = "'.$this->info['dn_id'].'" AND `for_dn` = "0" AND `x` = "'.$this->info['x'].'" AND `y`= "'.$this->info['y'].'"');
							
						}else{
							//выкидываем всех игроков в клетку RESTART
							$dnr = 1;
							if( $this->info['dungeon'] != 102 ) {
								mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "0" WHERE `dn` = "'.$this->info['dn_id'].'" AND `for_dn` = "0" AND `x` = "'.$this->info['x'].'" AND `y`= "'.$this->info['y'].'"');
							}
						}
					}
				}
				
				$gm = array();
				$bm = array();
				
				//Квестовый раздел
					$i = 0;
					while($i < count($this->users))
					{
						if($this->users[$i]['bot']==0 && $this->users[$i]['id'] == $u->info['id'])
						{
							$q->bfinuser($this->users[$i],$this->info['id'],$this->info['team_win']);
						}
						$i++;
					}
				//Квестовый раздел				
				
				//завершаем поединок
				$i = $this->uids[$u->info['id']];
				
				//Выходные летом +100% опыта
				if(round(date('m')) >= 5 && round(date('m')) < 9) {
					if(round(date('w')) == 0 || round(date('w')) == 6) {
						$this->stats[$i]['exp'] += 100;
					}
				}
																
				$this->stats[$i]['exp'] += $this->aBexp;
				if($this->stats[$i]['silver']>0) {
					$this->stats[$i]['exp'] += 5*$this->stats[$i]['silver'];
					if($this->stats[$i]['bonusexp']>1) { // Для покупки опыта (получает максимум)
						$this->stats[$i]['exp'] += 1000*$this->stats[$i]['bonusexp'];
					}
					if($this->stats[$i]['speeden']>20) { // Для восстановления энергии (получает максимум)
						$this->stats[$i]['enNow'] += $this->stats[$i]['speeden'];
						
					//$upd2 = mysql_query('UPDATE `stats` SET `enNow` = "'.$this->users[$i]['enNow'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					}
				}
				
				$act01 = 0;
				$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']+($this->users[$i]['battle_exp']/100*(1+$this->info['addExp']+$this->stats[$i]['exp'])));
				
				if($this->users[$i]['dnow'] != 0 && $this->info['dungeon'] != 1) {
      				$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']*0.1);
				}
								
				if($this->info['team_win']==0)
				{
					//ничья
						if($this->users[$i]['level']<=1)
						{
							$this->users[$i]['battle_exp'] = floor($this->users[$i]['battle_exp']*0.33);
						}else{
							$this->users[$i]['battle_exp'] = 0;
						}
						$this->users[$i]['nich'] += 1;
				}elseif($this->users[$i]['team']==$this->info['team_win'])
				{
					//выйграл
						$gm[$i] = $this->info['money'];
						$this->users[$i]['win'] += 1;
						$act01 = 1;
				}else{
					//проиграл
						if($this->users[$i]['level']<=1)
						{
							$this->users[$i]['battle_exp'] = ceil($this->users[$i]['battle_exp']*0.23);
						}else{
							$this->users[$i]['battle_exp'] = 0;
						}
						$bm[$i] = $this->info['money'];
						$this->users[$i]['lose'] += 1;
						//Добавляем эффект ослабления
						if($this->users[$i]['level']>=4)
						{
							$noOsl = 0;
							$nn = 0;
							while($nn<count($this->stats[$i]['effects']))
							{
								if($this->stats[$i]['effects'][$nn]['id_eff']==5)
								{
									$noOsl = 1;
								}
								$nn++;
							}
							if($noOsl==0)
							{
								$magic->oslablenie($this->users[$i]['id']);
							}
						}
						$act01 = 2;
				}
				//заносим данные в БД
				//Поломка предметов
					
					if($act01==1)
					{
						//победа
						if( $this->users[$i]['dnow'] == 0 ) {
							$lom = (rand(0,5))/100;
						}
					}elseif($act01==2)
					{
						//поражение
						$lom = (rand(25,50))/100;
					}else{
						//ничья
						$lom = (rand(5,25))/100;
					}
					$nlom = array(0=>rand(0,18),1=>rand(0,18),2=>rand(0,18),3=>rand(0,18));
					mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW`+'.$lom.' WHERE `inOdet` < "18" AND `inOdet` > "0" AND `uid` = "'.$this->users[$i]['id'].'" AND `inOdet`!="0" AND `inOdet`!='.$nlom[0].' AND `inOdet`!='.$nlom[1].' AND `inOdet`!='.$nlom[2].' AND `inOdet`!='.$nlom[3].' LIMIT 18');
										
					$prc = '';						
					if($this->users[$i]['align']==2)
					{
						$this->users[$i]['battle_exp'] = floor($this->users[$i]['battle_exp']/2);
					}
					if($this->users[$i]['animal']>0)
					{
						$ulan = $u->testAction('`uid` = "'.$this->users[$i]['id'].'" AND `vars` = "animal_use'.$this->info['id'].'" LIMIT 1',1);
						if(isset($ulan['id']) && $this->users[$i]['team']==$this->info['team_win'])
						{
							$a004 = mysql_fetch_array(mysql_query('SELECT `max_exp` FROM `users_animal` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `id` = "'.$this->users[$i]['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
							//33% от опыта переходит боту, но не более максимума
							$aexp = (round($this->users[$i]['battle_exp']/100*33));
							if($aexp > $a004['max_exp'])
							{
								$aexp = $a004['max_exp'];
							}
							unset($ulan);
							$upd = mysql_query('UPDATE `users_animal` SET `exp` = `exp` + '.$aexp.' WHERE `id` = "'.$this->users[$i]['animal'].'" AND `level` < '.$this->users[$i]['level'].' LIMIT 1');
							if($upd) {
								$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']/100*67); 
								$this->info['addExp'] -= 33.333;
							}
						}
					}
					if($this->users[$i]['align']==2 || $this->users[$i]['haos'] > time()) {
						$this->stats[$i]['exp'] = -($this->info['addExp']+50);
					}
					if($this->info['addExp']+$this->stats[$i]['exp']!=0)
					{
						$prc = ' ('.(100+$this->info['addExp']+$this->stats[$i]['exp']).'%)';
					}
					if($this->info['money']>0)
					{
						if(isset($gm[$i]))
						{
							//выйграл деньги
							$prc .= ' Вы выйграли <b>'.$gm[$i].' кр.</b> за этот бой';
							$u->addDelo(4,$this->users[$i]['id'],'&quot;<font color="olive">System.battle</font>&quot;: Персонаж выйграл <b>'.$gm[$i].' кр.</b> (В бою №'.$this->info['id'].').',time(),$this->info['city'],'System.battle',0,0);
							$this->users[$i]['money'] += $gm[$i];
						}elseif(isset($bm[$i]))
						{
							//проиграл деньги
							$prc .= ' Вы заплатили <b>'.$bm[$i].' кр.</b> за этот бой';
							$u->addDelo(4,$this->users[$i]['id'],'&quot;<font color="olive">System.battle</font>&quot;: Персонаж <i>проиграл</i> <b>'.$gm[$i].' кр.</b> (В бою №'.$this->info['id'].').',time(),$this->info['city'],'System.battle',0,0);
							$this->users[$i]['money'] -= $bm[$i];
						}
					}
					
					/*
7ур - 10800
8ур - 36000
9ур - 56000
10ур - 86000
if($this->users[$i]['battle_exp'] > (1+$this->users[$i]['level']*$this->users[$i]['level'])*4755) {
						$this->users[$i]['battle_exp'] = (1+$this->users[$i]['level']*$this->users[$i]['level'])*4755;
					}
					*/
					
					$lime = array(8=>18000,9=>28000,10=>84000,11=>150000);
					if($this->users[$i]['level'] < 8) {
						$lime = 5400;
					}else{
						$lime = $lime[$this->users[$i]['level']];
					}
					if( $this->stats[$i]['silver'] > 0 ) {
						$lime += floor($lime/100*(10+$this->stats[$i]['silver']));
					}
					if($lime < $this->users[$i]['battle_exp']) {
						$this->users[$i]['battle_exp'] = $lime;
					}
					unset($lime);
					
					
					if(100+$this->info['addExp']+$this->stats[$i]['exp'] > 1000) {
						$prc = ' (Великая Битва)';
					}
					
					if($this->info['dungeon'] == 1 && $this->users[$i]['team']==$this->info['team_win']) {
						//канализация лимит
						$rep = mysql_fetch_array(mysql_query('SELECT `dl1`,`id` FROM `rep` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1'));
						if($rep['dl'.$this->info['dungeon']] > 0) {
							$this->users[$i]['battle_exp'] += ceil(500/$this->users[$i]['level']);
							if($rep['dl'.$this->info['dungeon']] > $this->users[$i]['battle_exp']) {
								$rep['dl'.$this->info['dungeon']] -= $this->users[$i]['battle_exp'];
							}else{
								$this->users[$i]['battle_exp'] = $rep['dl'.$this->info['dungeon']];
								$rep['dl'.$this->info['dungeon']] = 0;
							}
							mysql_query('UPDATE `rep` SET `dl'.$this->info['dungeon'].'` = "'.$rep['dl'.$this->info['dungeon']].'" WHERE `id` = "'.$rep['id'].'" LIMIT 1');
						}else{
							$this->users[$i]['battle_exp'] = 0;
						}
					}
					
					if($this->users[$i]['battle_exp'] < 1) {
						$this->users[$i]['battle_exp'] = 0;
					}
					
					if($this->users[$i]['battle_exp'] < 1) {
						$prc = '';
					}
					
					if($this->user[$i]['host_reg'] == 'real_bot_user') {
						$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']/3);
					}
					
					$this->users[$i]['battle_text'] = 'Бой закончен. Всего вами нанесено урона: <b>'.floor($this->users[$i]['battle_yron']).' HP</b>. Получено опыта: <b>'.(0+$this->users[$i]['battle_exp']).'</b>'.$prc.'.'; //stats
										
					/*Выпадение зубов в конце боя */
					if($this->info['dungeon'] == 0 && ($this->info['razdel'] > 0 || $this->info['clone'] == 1) && ($this->users[$i]['exp'] < 12499 || $this->users[$i]['exp'] > 12500)) {
						if($this->users[$i]['align'] != 2 && $this->users[$i]['level'] > 0 && $this->users[$i]['level'] < 8 && $this->users[$i]['battle_exp'] > 10*$this->users[$i]['level']) {
							$rzb = 0;
							$best_exp = array(
								0,
								0,
								20,
								40,
								60,
								125,
								200,
								350								
							);
							if($this->users[$i]['level'] < 8 && $best_exp[$this->users[$i]['level']] < $this->users[$i]['battle_exp']) {
								if($this->info['clone'] != 5) {
									if(rand(0,5) == 2) {
										$rzb = 0;
									}elseif(rand(0,3) == 1) {
										$rzb = rand(0,3);
									}elseif(rand(0,3) == 2) {
										$rzb = rand(0,3);
									}elseif(rand(0,3) == 3) {
										$rzb = rand(0,3);
									}elseif(rand(0,3) == 2) {
										$rzb = rand(0,3);
									}elseif(rand(0,3) == 1) {
										$rzb = rand(0,3);
									}elseif(rand(0,3) == 0) {
										$rzb = rand(0,3);
									}elseif(rand(0,3) == 2) {
										$rzb = rand(0,3);
									}elseif(rand(0,5) == 2) {
										$rzb = rand(19,23);
									}elseif(rand(0,5) == 2) {
										$rzb = rand(10,28);
									}elseif(rand(0,7) == 2) {
										$rzb = rand(29,33);
									}elseif(rand(0,11) == 2) {
										$rzb = rand(100,111);
									}else{
										$rzb = rand(0,3);
									}
								}else{
									$rzb = rand(0,3);
								}
							}
							if($rzb > 0) {
								mysql_query('UPDATE `users` SET `money4` = `money4` + "'.$rzb.'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
								$this->users[$i]['battle_text'] .= ' Вы получили <small>'.$u->zuby($rzb,1).'</small> за этот бой.';
							}
						}
					}
					
					//Добавляем воинственность
					if( $this->info['dungeon'] == 0 && $this->info['razdel'] == 5 && $this->users[$i]['exp'] >= 2500 /*($this->users[$i]['exp'] < 12499 || $this->users[$i]['exp'] > 12500)*/ ) {
						if( $this->users[$i]['battle_exp'] > 100 * $this->users[$i]['level'] ) {
							$rzbvo = 0;
							if( $this->info['players_c'] > 4 ) {
								$rzbvo = 2*$this->info['players_c'];
							}
							$this->users[$i]['battle_text'] .= ' Вы получили '.$rzbvo.' воинственности за этот бой.';
							mysql_query('UPDATE `rep` SET `rep3` = `rep3` + "'.$rzbvo.'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
						}
					}
					
					
					$this->users[$i]['last_b'] = $this->info['id']; //stats
					$this->users[$i]['last_a'] = $act01;
					$this->users[$i]['battle'] = -1; //users
					$this->users[$i]['battle_yron'] = 0; //stats
						
					$this->users[$i]['exp']  += $this->users[$i]['battle_exp']; //users
					
					/*if($this->stats[$i]['speeden']>2) { // Для восстановления энергии (получает максимум)
						$this->users[$i]['enNow']+= $this->stats[$i]['enNow']; //users
						$upd2 = mysql_query('UPDATE `stats` SET `enNow` = "'.$this->users[$i]['enNow'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					}*/
					//Добавляем клан опыт (Все кроме пещер)
					
					if($this->users[$i]['clan'] > 0) {
						$cpr = 1;
						if($this->info['typeBattle'] == 9) {
							$cpr = 25;
						}elseif($this->info['typeBattle'] == 50) {
							$cpr = 65;
						}
						mysql_query('UPDATE `clan` SET `exp` = `exp` + "'.round($this->users[$i]['battle_exp']/100*$cpr).'" WHERE `id` = "'.$this->users[$i]['clan'].'" LIMIT 1');
					}
					
					$this->users[$i]['battle_exp'] = 0; //stats
					
					if($this->users[$i]['team']==$this->info['team_win']) {
						mysql_query('UPDATE `rep` SET `n_capitalcity` = `n_capitalcity` + '.$this->users[$i]['bn_capitalcity'].' ,`n_demonscity` = `n_demonscity` + '.$this->users[$i]['bn_demonscity'].' ,`n_demonscity` = `n_demonscity` + '.$this->users[$i]['bn_suncity'].' WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					}
					
					//репутация

					$this->users[$i]['bn_demonscity'] = 0;
					$this->users[$i]['bn_capitalcity'] = 0;
					$this->users[$i]['bn_suncity'] = 0;
				//завершение эффектов с финишем
					$spe = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `file_finish` != "" AND `v1` = "priem" LIMIT 30');
					while($ple = mysql_fetch_array($spe)) {
						if(file_exists('../../_incl_data/class/priems/'.$ple['file_finish'].'.php'))	{
							require('../../_incl_data/class/priems/'.$ple['file_finish'].'.php');
						}
					}
				//обновляем данные
					mysql_query('DELETE FROM `eff_users` WHERE `v1` = "priem" AND `uid` = "'.$this->users[$i]['id'].'" LIMIT 30');
					if($dnr==1)
					{
						if( $this->users[$i]['room'] == 370 ) {
							$dies = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `dn` = "'.$this->users[$i]['dnow'].'" AND `vars` = "dielaba" LIMIT 1'));
							$dies = $dies[0];
							mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
								"'.$this->users[$i]['dnow'].'","'.$this->users[$i]['id'].'","'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'","'.time().'","dielaba",""
							)');
						}else{
							$dies = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `dn` = "'.$this->users[$i]['dnow'].'" AND `vars` = "die" LIMIT 1'));
							$dies = $dies[0];
							mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
								"'.$this->users[$i]['dnow'].'","'.$this->users[$i]['id'].'","'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'","'.time().'","die",""
							)');
						}
						if( $dies < 2 ) {
							//телепортируем в рестарт (координата 0х0)
							$this->users[$i]['x'] = $this->users[$i]['res_x'];
							$this->users[$i]['y'] = $this->users[$i]['res_y'];
							$this->users[$i]['s'] = $this->users[$i]['res_s'];
							if( $this->users[$i]['room'] == 370 ) {
								if( $this->users[$i]['sex'] == 0 ) {
									$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погиб и находится в начале лабиринта';
								}else{
									$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погибла и находится в начале лабиринта';
								}
							}else{
								if( $this->users[$i]['sex'] == 0 ) {
									$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погиб и находится в комнате &quot;...&quot;';
								}else{
									$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погибла и находится в комнате &quot;...&quot;';
								}
							}
						}elseif( $this->info['dungeon'] == 102 ) {
							$nld = '';
							$lab = mysql_fetch_array(mysql_query('SELECT `id`,`users` FROM `laba_now` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1'));
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
							mysql_query('UPDATE `stats` SET `dnow` = "0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							mysql_query('UPDATE `users` SET `room` = "369" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							//удаляем все предметы которые пропадают после выхода из пещеры
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$this->users[$i]['id'].'" AND `delete` < 1234567890 AND `inShop` = "0" AND (`dn_delete` = "1" OR `data` LIKE "%fromlaba=1%")');
							if( $this->users[$i]['sex'] == 0 ) {
								$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погиб без права на воскрешение и покидает подземелье'.$nld;
							}else{
								$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погибла без права на воскрешение и покидает подземелье'.$nld;
							}
						}else{
							$tinf = mysql_fetch_array(mysql_query('SELECT `uid` FROM `dungeon_now` WHERE `id` = "'.$this->info['dn_id'].'" LIMIT 1'));
							$nld = '';
							if( $tinf['uid'] == $this->users[$i]['id'] ) {
								$tinf = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dnow` = "'.$this->info['dn_id'].'" AND `hpNow` >= 1 LIMIT 1'));
								if( isset($tinf['id']) ) {
									$tinf = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$tinf['id'].'" LIMIT 1'));
									$nld .= ', новым лидером становится &quot;'.$tinf['login'].'&quot;';
									mysql_query('UPDATE `dungeon_now` SET `uid` = "'.$tinf['id'].'" WHERE `id` = "'.$this->info['dn_id'].'" LIMIT 1');
								}
							}
							mysql_query('UPDATE `stats` SET `dnow` = "0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							mysql_query('UPDATE `users` SET `room` = "188" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							//удаляем все предметы которые пропадают после выхода из пещеры
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$this->users[$i]['id'].'" AND `dn_delete` = "1" LIMIT 1000');
							if( $this->users[$i]['sex'] == 0 ) {
								$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погиб без права на воскрешение и покидает подземелье'.$nld;
							}else{
								$rtxt = '<b>'.$this->users[$i]['login'].'</b> трагически погибла без права на воскрешение и покидает подземелье'.$nld;
							}
						}
						if( $rtxt != '' ) {
							mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$this->info['dn_id']."','".$this->users[$i]['city']."','".$this->users[$i]['room']."','','','".$rtxt."','".time()."','6','0','1','1')");
						}
					}
					
					mysql_query('UPDATE `users` SET `login2` = "" WHERE `battle` = "'.$this->info['id'].'"');
					$upd  = mysql_query('UPDATE `users` SET `login2` = "", `money` = "'.$this->users[$i]['money'].'",`win` = "'.$this->users[$i]['win'].'",`lose` = "'.$this->users[$i]['lose'].'",`nich` = "'.$this->users[$i]['nich'].'",`battle` = "-1" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					
					$upd2 = mysql_query('UPDATE `stats` SET `bn_capitalcity` = 0,`bn_demonscity` = 0,`smena` = 3,`tactic7` = "-100",`x`="'.$this->users[$i]['x'].'",`y`="'.$this->users[$i]['y'].'",`priems_z`="0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0",`tactic1`="0",`tactic2`="0",`tactic3`="0",`tactic4`="0",`tactic5`="0",`tactic6`="0.00000000",`tactic7`="10",`exp` = "'.$this->users[$i]['exp'].'",`battle_exp` = "'.$this->users[$i]['battle_exp'].'",`battle_text` = "'.$this->users[$i]['battle_text'].'",`battle_yron` = "0",`enemy` = "0",`last_b`="'.$this->info['id'].'",`regHP` = "'.time().'",`regMP` = "'.time().'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
				if($this->info['turnir'] == 0) {
				//пишем в чат
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$i]['city']."','".$this->users[$i]['room']."','','".$this->users[$i]['login']."','".$this->users[$i]['battle_text']."','-1','6','0')");				
				}else{
					mysql_query('UPDATE `turnirs` SET `winner` = "'.$this->info['team_win'].'" WHERE `id` = "'.$this->info['turnir'].'" LIMIT 1');
				}
				//завершаем сам бой				
					$upd3  = mysql_query('UPDATE `battle` SET `time_over` = "'.time().'",`team_win` = "'.$this->info['team_win'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				
				//Если это БС (старая версия)
				/*$tinf = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_now` WHERE `id` = "'.$this->info['dn_id'].'" LIMIT 1'));
				if(isset($tinf['id']) && $tinf['bsid']>0)
				{
					$bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `city` = "'.$u->info['city'].'" AND `id` = "'.$tinf['bsid'].'" AND `time_start` = "'.$tinf['time_start'].'" LIMIT 1'));
					if(isset($bs['id']))
					{
						$u->bsfinish($bs,$this->users,$this->info);				
					}
				}*/
				mysql_query('UNLOCK TABLES');
			}
			unlink($lock_file);
			}
		}
		
	//Выдаем предметы
	//$this->addGlobalItems($this->user[$i]['id'],$this->user[$j]['id'],$mon['win_itm'],$mon['win_eff'],$mon['win_ico'],$mon['win_exp'],$mon['win_money'],$mon['win_money2']);
		public $ainm = array();
		public function addGlobalItems($bid,$uid,$itm,$eff,$ico,$exp,$cr,$ecr) {
			global $u;
			if( $exp > 0 ) {
				$this->users[$this->uids[$uid]]['battle_exp'] += round($exp*$this->users[$this->uids[$uid]]['battle_yron']/$this->stats[$this->uids[$bid]]['hpAll']);
				mysql_query('UPDATE `stats` SET `battle_exp` =  "'.mysql_real_escape_string($this->users[$this->uids[$uid]]['battle_exp']).'" WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1');
			}
			//
			if( $cr != '' && $cr > 0 ) {
				if( $this->stats[$this->uids[$uid]]['hpNow'] > 0 ) {
					mysql_query('UPDATE `users` SET `money` = (`money` + '.mysql_real_escape_string($cr).') WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>Вы получили кредиты:</b> ".mysql_real_escape_string($cr)." <b>кр.</b></font>','-1','6','0')");
				}
			}
			//
			if( $ecr != '' && $ecr > 0 ) {
				if( $this->stats[$this->uids[$uid]]['hpNow'] > 0 ) {
					if(mysql_query('UPDATE `bank` SET `money2` = `money2` + "'.mysql_real_escape_string($ecr).'" WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `block` = "0" ORDER BY `id` DESC LIMIT 1')) {
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>Вы получили Евро-кредиты:</b> ".mysql_real_escape_string($ecr)." <b>екр.</b></font>','-1','6','0')");
					}else{
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>Вы могли получили Евро-кредиты за этот бой, но у Вас нет банковского счета.</b> Его можно создать на Страшилкиной ул. в здании Банка за небольшое количество кредитов.</font>','-1','6','0')");
					}
				}
			}
			//
			if( $ico != '' ) {
				/*
				0(тип, 1 - значок, 2 - подвиг)@
				1(время в минутах)@
				2(название картинки)@
				3(название)@
				4(требует остаться в живых 0 или 1, либо игрок умер -1)@
				5(требует набить с ботам урона в % Например 0.001)@
				6(действия например: add_s1=5|add_hpAll=50)@
				7(Требует другой значок, название картинки)@
				8(плюсует значок 0 или 1)@
				9(удаляем прошлый значок 0 or 1)
				*/
				$i = 0;
				$txt = '';
				$ico = explode('#',$ico);
				while( $i < count($ico) ) {
					$ico_e = explode('@',$ico[$i]);
					if(isset($ico_e[3])) {
						//
						$add = 1;
						if($ico_e[4] == 1 && floor($this->stats[$this->uids[$uid]]['hpNow']) < 1) {
							$add = 0;
						}
						if( $add == 1 ) {
							$ins = false;
							if( $ico_e[8] == 0 ) {
								$ins = true;
								if( $ico_e[9] == 1 ) {
									mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `img` = "'.mysql_real_escape_string($ico_e[2]).'"');
								}
							}else{
								$old_ico = mysql_fetch_array(mysql_query('SELECT `id` FROM `users_ico` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND (`endTime` > "'.time().'" OR `endTime` = 0) AND `img` = "'.mysql_real_escape_string($ico_e[2]).'" LIMIT 1'));
								if( !isset($old_ico['id'])) {
									$ins = true;
								}else{
									if( $old_ico['id'] > 0 ) {
										$txt .= ', &quot;'.$ico_e[3].' (<small>Обновление</small>)&quot;';
										mysql_query('UPDATE `users_ico` SET `x` = `x` + 1,`endTime` = "'.mysql_real_escape_string(time()+$ico_e[1]*60).'" WHERE `id` = "'.$old_ico['id'].'" LIMIT 1');
									}else{
										$ins = true;
									}
								}
								unset($old_ico);
							}
							if($ins == true) {
								if( $ico_e[9] == 1 ) {
									mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `img` = "'.mysql_real_escape_string($ico_e[2]).'"');
								}
								mysql_query('INSERT INTO `users_ico` (`uid`,`time`,`text`,`img`,`endTime`,`type`,`bonus`) VALUES (
									"'.mysql_real_escape_string($uid).'",
									"'.time().'",
									"'.mysql_real_escape_string($ico_e[3]).'",
									"'.mysql_real_escape_string($ico_e[2]).'",
									"'.mysql_real_escape_string(time()+$ico_e[1]*60).'",
									"'.mysql_real_escape_string($ico_e[0]).'",
									"'.mysql_real_escape_string($ico_e[6]).'"
								)');
								$txt .= ', &quot;'.$ico_e[3].'&quot;';
							}
						}
						//
					}
					$i++;
				}
				if( $txt != '' ) {
					$txt = ltrim($txt,', ');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>Вы совершили подвиг:</b> ".mysql_real_escape_string($txt)."</font>','-1','6','0')");
				}
			}
			//
			if( $itm != '' ) {
				$i = 0;
				$txt = '';
				$itm = explode(',',$itm);
				while($i < count($itm)) {
					$itm_e = explode('@',$itm[$i]);
					if($itm_e[0] > 0) {
						$j = 0;
						while( $j < $itm_e[1] ) {
							$u->addItem($itm_e[0],$uid,'|'.$itm_e[2]);
							$j++;
						}
						if( !isset($this->ainm[$itm_e[0]]) ) {
							$this->ainm[$itm_e[0]] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($itm_e[0]).'" LIMIT 1'));
						}
						if( isset($this->ainm[$itm_e[0]]['id']) ) {
							//Добавляем текст о добавлении предмета
							$txt .= ', <b>'.$this->ainm[$itm_e[0]]['name'].'</b>';
							if( $itm_e[1] > 1 ) {
								$txt .= ' <b>(x'.$itm_e[1].')</b>';
							}
						}
					}
					$i++;
				}
				if( $txt != '' ) {
					$txt = ltrim($txt,', ');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>Вы получили предметы:</b> ".mysql_real_escape_string($txt)."</font>','-1','6','0')");
				}
			}
			//
		}
	
	//Проводим удар
		public function addNewAtack()
		{
			global $u;
			if(!isset($this->ga[$u->info['id']][$u->info['enemy']]))
			{
				if($this->stats[$this->uids[$u->info['id']]]['hpNow']>0)	
				{
					$us = $this->stats[$this->uids[$u->info['id']]];
					$i = 1; $no = 0;
					if($us['weapon1']!=1 && $us['weapon2']==1)
					{
						$uz['zona'] += 1;
					}
					while($i<=$uz['zona'])
					{
						if($this->uAtc['a'][$i]==0)
						{	
							$no = 1;
						}
						$i++;
					}
					
					if($this->uAtc['b']==0)
					{
						$no = 1;
					}

					
					if($no==0)
					{
						//наносим удар
						if($u->info['enemy']!=0)
						{
							if(!isset($this->ga[$u->info['enemy']][$u->info['id']]))
							{
								if($this->stats[$this->uids[$u->info['id']]]['hpNow']>=1 && $this->stats[$this->uids[$u->info['enemy']]]['hpNow']>=1)
								{
									//наносим новый удар
									$a = $this->uAtc['a'][1].''.$this->uAtc['a'][2].''.$this->uAtc['a'][3].''.$this->uAtc['a'][4].''.$this->uAtc['a'][5];
									$b = $this->uAtc['b'];	
									mysql_query('DELETE FROM `battle_act` WHERE `battle` = "'.$this->info['id'].'" AND ((`uid2` = "'.$u->info['id'].'" AND `uid1` = "'.$u->info['enemy'].'") OR (`uid1` = "'.$u->info['id'].'" AND `uid2` = "'.$u->info['enemy'].'")) LIMIT 2');						
									$d = mysql_query('INSERT INTO `battle_act` (`battle`,`time`,`uid1`,`uid2`,`a1`,`b1`) VALUES ("'.$this->info['id'].'","'.time().'","'.$u->info['id'].'","'.$u->info['enemy'].'","'.$a.'","'.$b.'")');
									if(!$d)
									{
										$this->e = 'Не удалось нанести удар по противнику...';
									}else{
										$this->ga[$u->info['id']][$u->info['enemy']] = mysql_insert_id();
									}
								}								
							}else{
								//отвечаем на удар противника
								if($this->stats[$this->uids[$u->info['id']]]['hpNow']>=1 && $this->stats[$this->uids[$u->info['enemy']]]['hpNow']>=1)
								{
									if(isset($this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['id']))
									{
										$this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['a2'] = $this->uAtc['a'][1].''.$this->uAtc['a'][2].''.$this->uAtc['a'][3].''.$this->uAtc['a'][4].''.$this->uAtc['a'][5];
										$this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['b2'] = $this->uAtc['b'];
										$this->startAtack($this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['id']);
									}
								}
							}
						}else{
							//ожидание хода противника (нет подходящего противника)
							
						}
					}else{
						$this->e = 'Выберите зоны удара и блока';
					}
				}else{
					$this->e = 'Для вас поединок закончен, ожидайте пока завершат другие...';
				}
			}else{
				//уже ударили противника, ожидание хода
				
			}	
		}
	
	//Запускаем магические предметы, если в них что-то встроено
		public function magicItems($uid1,$uid2)
		{
			global $u,$priem,$c,$code;
			if(isset($this->stats[$this->uids[$uid1]]))
			{
				$i = 0;
				while($i<count($this->stats[$this->uids[$uid1]]['items']))
				{
					$itm = $this->stats[$this->uids[$uid1]]['items'][$i];
					if(isset($itm['id']))
					{
						$e = $u->lookStats($itm['data']);
						if(isset($e['bm_a1']))
						{
							if(file_exists('../../_incl_data/class/priems/'.$e['bm_a1'].'.php'))
							{
								require('../../_incl_data/class/priems/'.$e['bm_a1'].'.php');
							}
						}
					}
					$i++;
				}
				unset($itm);
			}
		}
	//Поглощение урона
		public function testPog($uid,$yr)
		{
			$yr2 = $yr;
			if($yr > 0) {
			$testmana=false;
			global $u,$priem;
			$i = 0;
			/*
			$st['effects'][$h] = $e;	$h++;
			$st['pog'][$ctt]['id'] = $h;
			$st['pog'][$ctt]['y']  = $sts['add_pog'];
			*/
			/*while($i<count($this->stats[$this->uids[$uid]]['set_pog']))
			{
			
				$j = $this->stats[$this->uids[$uid]]['set_pog'][$i];
                
				$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog='.$j['y'],'add_pog=$',$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);

				$j['y'] -= $yr2; // осталось для поглощения			    
				
				if($j['y']>0 and $testmana == false)
				{   
					//урон полностью поглащен
					$yr2 = 0; 
				}elseif($testmana == true and $j['y']>0){
				    // поглощение магический барьер
				    if($min_mana<0){
						$j['y'] += round(($min_mana-$min_mana*2)/$mp_abs);
						$yr2 = round(($min_mana-$min_mana*2)/$mp_abs);
						$this->stats[$this->uids[$uid]]['mpNow'] = 0;
					}else{
						$yr2 -= round($pog); 
					}
				}else{					
					$dt2 = $u->lookStats($this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
					if(isset($dt2['endPog']) && $dt2['endPog'] == 1)
					{
						//удаляем прием
						$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['v2'].'" LIMIT 1'));
						$this->delPriem($this->stats[$this->uids[$uid]]['effects'][$j['id']-1],$this->users[$this->uids[$uid]],4,$uid);
						$this->stats[$this->uids[$uid]]['effects'][$j['id']-1] = 'delete';
					}
					unset($dt2);
					$yr2 = -($j['y']);
					$j['y'] = 0;
				}
								
				$this->stats[$this->uids[$uid]]['set_pog'][$i]['y'] = $j['y'];	
				$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog=$','add_pog='.$j['y'],$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
				$upd = mysql_query('UPDATE `eff_users` SET `data` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'].'" WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['id'].'" LIMIT 1');
				if($upd)
				{
					
				}
				$i++;
			}*/
			
			$ypg22 = 0;
			while($i<count($this->stats[$this->uids[$uid]]['set_pog2']))
			{
			
				$j = $this->stats[$this->uids[$uid]]['set_pog2'][$i];
                
				$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog2='.$j['y'],'add_pog2=$',$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
				
				$dt3 = $u->lookStats($this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
				
				if(isset($dt3['add_pog2mp'])) {
					$priem->minMana($uid,round(round($yr2/100*$dt3['add_pog2p'])*$dt3['add_pog2mp']));
				}
				
				$j['y'] -= $yr2; // осталось для поглощения
				
				if(isset($dt3['add_pog2p'])) {
					$yr2 = round($yr2/100*(100-$dt3['add_pog2p']));
					//echo '[Поглощаем: '.($dt3['add_pog2p']).'%]';
				}
											
				
				unset($dt3);
				
				if($j['y'] < 0 || ($this->stats[$this->uids[$uid]]['mpNow'] <= 0 && $dt3['add_pog2mp'] > 0)) {					
					$dt2 = $u->lookStats($this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
					if(isset($dt2['endPog']) && $dt2['endPog'] == 1)
					{
						//удаляем прием
						$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['v2'].'" LIMIT 1'));
						$this->delPriem($this->stats[$this->uids[$uid]]['effects'][$j['id']-1],$this->users[$this->uids[$uid]],4,$uid);
						$this->stats[$this->uids[$uid]]['effects'][$j['id']-1] = 'delete';
					}
					unset($dt2);
					$yr2 = -($j['y']);
					$j['y'] = 0;
				}
								
				$this->stats[$this->uids[$uid]]['set_pog'][$i]['y'] = $j['y'];	
				$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog2=$','add_pog2='.$j['y'],$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
				$upd = mysql_query('UPDATE `eff_users` SET `data` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'].'" WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['id'].'" LIMIT 1');
				if($upd)
				{
					
				}
				$i++;
			}
			
			}
			return $yr2;

		}
		public $rehodeff = array();
	//Наносим удар между игроками
		public $import_atack = array();
		public $contr = array();
		public function startAtack($id)
		{
			global $u,$log_text,$priem;
			
			if(isset($this->atacks[$id]))
			{
				//$this->stats[$this->uids[$a['uid1']]] = $u->getStats($a['uid1'],0);
				//$this->stats[$this->uids[$a['uid2']]] = $u->getStats($a['uid2'],0);
				
				$at = array(1=>array(),2=>array());
							
				$u1 = 1; $u2 = 2; $a = $this->atacks[$id];
				$hp1  = floor($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']);
				$hpA1 = $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpAll'];
				$hp2  = floor($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']);
				$hpA2 = $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpAll'];
				while($u1 <= 2) {
					if($this->stats[$this->uids[$a['uid'.$u1]]]['effects'][$this->stats[$this->uids[$a['uid'.$u1]]]['set_natoe']['id']-1] > 0) {
																										
						$nsq = $this->stats[$this->uids[$a['uid'.$u1]]]['effects'][$this->stats[$this->uids[$a['uid'.$u1]]]['set_natoe']['id']-1];
												
						if($a['out'.$u2] == 0) {											
							
							if($this->stats[$this->uids[$a['uid'.$u1]]]['set_natoe']['user_id'] > 0) {								
								/*echo $a['uid'.$u1].' ударил '.$a['uid'.$u2].', меняем на '.$this->stats[$this->uids[$a['uid'.$u1]]]['set_natoe']['user_id'];
								$this->import_atack[$id] = array(
									'uid0' => $a['uid'.$u1],
									'uid1' => $a['uid'.$u2],
									'uid2' => $this->stats[$this->uids[$a['uid'.$u1]]]['set_natoe']['user_id']
								);*/				
							}else{									
								if($this->stats[$this->uids[$a['uid'.$u1]]]['set_natoe']['b'] > 0) {									
									//удаляем прием
									$nsq['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$nsq['v2'].'" LIMIT 1'));
									$this->delPriem($nsq,$this->users[$this->uids[$a['uid'.$u1]]],1,$this->users[$this->uids[$a['uid'.$u2]]]);
									unset($this->stats[$this->uids[$a['uid'.$u1]]]['effects'][$this->stats[$this->uids[$a['uid'.$u1]]]['set_natoe']['id']-1]);
									unset($this->ga[$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->ga[$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']]);
									unset($this->ag[$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->ag[$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']]);
								}	
								$a['out'.$u1] = 100;
								
								//добавляем новый удар игрока
								$a40 = $this->atacks[$id]['a1'];
								$b40 = $this->atacks[$id]['b1'];	
																			
								$d = mysql_query('INSERT INTO `battle_act` (`battle`,`time`,`uid1`,`uid2`,`a1`,`b1`,`out2`) VALUES ("'.$this->info['id'].'","'.time().'","'.$a['uid'.$u1].'","'.$a['uid'.$u2].'","'.$a40.'","'.$b40.'","100")');
								
								$new_id = mysql_insert_id();
								if($new_id > 0)
								{
									$this->ga[$a['uid'.$u1]][$a['uid'.$u2]] = $new_id;
									$this->ag[$a['uid'.$u2]][$a['uid'.$u1]] = $new_id;
									$this->atacks[$new_id] = mysql_fetch_array(mysql_query('SELECT * FROM `battle_act` WHERE `id` = "'.$new_id.'" LIMIT 1'));
									$this->atacks[$new_id]['no_hr'] = 1;
								}							
															
								$a['uid'.$u1] = $a['uid'.$u2];
								$this->atacks[$id] = $a;
								
								if($new_id > 0) {
									$this->startAtack($new_id);
								}
							}
						}
						
						unset($nsq);
					}
					$u1++; $u2--;
				}
				
				//Запускаем магию предметов
				$this->magicItems($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
				$this->magicItems($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1']);
				
				//Расчет количества блоков и противников
				$this->testZonb($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
				
				if($this->atacks[$id]['out1']>0 && $this->atacks[$id]['out2']>0)
				{
					if($this->atacks[$id]['out1'] == 100) {
						$at[1] = -1;
					}else{
						$at[1] = 0;
					}
					if($this->atacks[$id]['out2'] == 100) {
						$at[2] = -1;
					}else{
						$at[2] = 0;
					}
				}elseif($this->atacks[$id]['out1']>0)
				{
					//игрок 1 пропустил ход
					if($this->atacks[$id]['out1'] == 100) {
						$at[1] = -1;
					}else{
						$at[1] = 0;
					}
					$at[2] = $this->takeAtack(2,1,$this->atacks[$id],$id);
				}elseif($this->atacks[$id]['out2']>0)
				{
					//игрок 2 пропустил ход
					$at[1] = $this->takeAtack(1,2,$this->atacks[$id],$id);
					if($this->atacks[$id]['out2'] == 100) {
						$at[2] = -1;
					}else{
						$at[2] = 0;
					}
				}else{
					//размен
					$at[1] = $this->takeAtack(1,2,$this->atacks[$id],$id);
					$at[2] = $this->takeAtack(2,1,$this->atacks[$id],$id);
				}							
				
				//КонтрУдар
				$at_add = array();
				$nopro = array();
				$kntr1 = 0;
				$kntr2 = 0;
				if(isset($this->contr[$id][$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']])) {
					if($this->contr[$id]['y'][$this->atacks[$id]['uid1']] > 0) {
						//echo '<br><b>'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['login'].'</b> нанес контрудар по <b>'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['login'].'</b> ('.$this->contr[$id][$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']].'/'.$this->stats[$this->uids[$a['uid2']]]['zona'].')';
						$jat = 0;
						while($jat < $this->contr[$id][$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']]) {
							if($kntr1 < $this->stats[$this->uids[$a['uid2']]]['zona']) {
								
								/* пересчет данных о персонажах */
								$this->stats[$this->uids[$a['uid1']]] = $u->getStats($a['uid1'],0);
								$this->stats[$this->uids[$a['uid2']]] = $u->getStats($a['uid2'],0);
								$at_add[1] = $this->takeAtack(1,2,$this->atacks[$id],$id,true);
								if(count($at_add[1]) > 0) {
									$jad = 0;
									while($jad < count($at_add[1])) {
										$at[1][] = $at_add[1][$jad];
										$nopro[1][count($at[1])] = 1;
										$nopro[2][count($at[2])] = 1;
										$jad++;
									}
									$kntr1++;
								}
							}
							$jat++;
						}
						
						
					}
					unset($this->contr[$id][$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->contr[$id]['y'][$this->atacks[$id]['uid1']]);
				}
				if(isset($this->contr[$id][$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']])) {
					if($this->contr[$id]['y'][$this->atacks[$id]['uid2']] > 0) {						
						//echo '<br><b>'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['login'].'</b> нанес контрудар по <b>'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['login'].'</b> ('.$this->contr[$id][$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']].'/'.$this->stats[$this->uids[$a['uid1']]]['zona'].')';
						$jat = 0;
						while($jat < $this->contr[$id][$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']]) {
							if($kntr2 < $this->stats[$this->uids[$a['uid1']]]['zona']) {
								/* пересчет данных о персонажах */
								$this->stats[$this->uids[$a['uid1']]] = $u->getStats($a['uid1'],0);
								$this->stats[$this->uids[$a['uid2']]] = $u->getStats($a['uid2'],0);
								$at_add[2] = $this->takeAtack(2,1,$this->atacks[$id],$id,true);
								if(count($at_add[2]) > 0) {
									$jad = 0;
									while($jad < count($at_add[2])) {
										$at[2][] = $at_add[2][$jad];
										$nopro[1][count($at[1])] = 1;
										$nopro[2][count($at[2])] = 1;
										$jad++;
									}
									$kntr2++;
								}
							}
							$jat++;
						}
					}
					unset($this->contr[$id][$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->contr[$id]['y'][$this->atacks[$id]['uid2']]);
				}
				
				//Перенос урона
				$this->prnt = false;
				unset($at_add,$jad);
				
				
				//производим удар и заносим в логи
					/*
						v - зона куда били (1-5)
						t - тип удара
						y - урон
					*/
					$this->hodID++;					
					
					//Добавляем тактики
					$tac1 = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0);
					$tac2 = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0); 
					
					//проверяем бои между игроками 1 и 2, если более 3 боев в час, то все, попали ежики
					$lbs = 0;
					$lbs1 = 0;
					$lbs2 = 0;
					//Если персонаж старше по уровню противника, то
					if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['level']>$this->users[$this->uids[$this->atacks[$id]['uid1']]]['level'])
					{
						$lbs2 = (1+$this->users[$this->uids[$this->atacks[$id]['uid2']]]['level']-$this->users[$this->uids[$this->atacks[$id]['uid1']]]['level']);
						$lbs1 = ($this->users[$this->uids[$this->atacks[$id]['uid1']]]['level']-$this->users[$this->uids[$this->atacks[$id]['uid2']]]['level']);
					}
					if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['level']<$this->users[$this->uids[$this->atacks[$id]['uid1']]]['level'])
					{
						$lbs1 = (1+$this->users[$this->uids[$this->atacks[$id]['uid1']]]['level']-$this->users[$this->uids[$this->atacks[$id]['uid2']]]['level']);
						$lbs2 = ($this->users[$this->uids[$this->atacks[$id]['uid2']]]['level']-$this->users[$this->uids[$this->atacks[$id]['uid1']]]['level']);
					}
									
					$doexp1 = mysql_fetch_array(mysql_query('SELECT SUM(`items_main`.`price1`) FROM `items_users`,`items_main` WHERE `items_users`.`inOdet` > 0 AND `items_main`.`inSlot` < 50 AND `items_users`.`uid` = "'.$this->atacks[$id]['uid1'].'" AND `items_users`.`delete` = 0 AND `items_main`.`id` = `items_users`.`item_id` ORDER BY `items_main`.`inSlot` ASC LIMIT 50'));
					if($doexp1[0]>0) {
						if($this->users[$this->uids[$this->atacks[$id]['uid1']]]['bot'] > 0) {
							$doexp1 = floor($doexp1[0]/5);
						}else{
							$doexp1 = floor($doexp1[0]);
						}
					}else{
						$doexp1 = 0;
					}
					
					$doexp2 = mysql_fetch_array(mysql_query('SELECT SUM(`items_main`.`price1`) FROM `items_users`,`items_main` WHERE `items_users`.`inOdet` > 0 AND `items_main`.`inSlot` < 50 AND `items_users`.`uid` = "'.$this->atacks[$id]['uid2'].'" AND `items_users`.`delete` = 0 AND `items_main`.`id` = `items_users`.`item_id` ORDER BY `items_main`.`inSlot` ASC LIMIT 50'));
					if($doexp2[0]>0) {
						if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['bot'] > 0) {
							$doexp2 = floor($doexp2[0]/5);
						}else{
							$doexp2 = floor($doexp2[0]);
						}
					}else{
						$doexp2 = 0;
					}
						
							
					//удары игрока 1
						$i = 1;
						$trawm_off=false;
						while($i<=count($at[1]))
						{
							$at[1][$i]['y'] = $this->testPog($this->atacks[$id]['uid2'],$at[1][$i]['y']);
							$y = $at[1][$i];
												
							if($y['y']<0){ $y['y'] = 0; }
							
							if($y['y'] > 0) {
								$this->users[$this->uids[$this->atacks[$id]['uid2']]]['last_hp'] = -$y['y'];
							}
							
							$addExp = 0+($y['y']/$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpAll']*100);
							if($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']+10-$y['y']<0)
							{
								$addExp = 0+(($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']+10)/$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpAll']*100);
							}
							if($addExp<0){ $addExp = 0; }				
							if($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['levels']!='undefined')
							{	
								$addExp = $addExp*(1+$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['levels']['expBtlMax']+$doexp2)/100;
							}else{
								//echo '[!]';
							}
							$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow'] -= $y['y'];

							/* расчет травмы 1 */	
							
							if(!isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['notravma']) || $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['notravma']==0) {
								if(($at[1][$i]['t']==3 or $at[1][$i]['t']==4) and $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']<=0 and $trawm_off==false and $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['lvl']>=2 and $this->info['type']!=99){
									//echo 'травма';
									$trawm_off=true;
									// Расчет типа травмы, тяжелая травма получаеться в поединках специальных, по этому её не считаем
									$sxsa = '';
									if($this->users[$this->uids[$this->atacks[$id]['uid2']]] == 1) {
										$sxsa = 'а';
									}
									$tr_type=0;
									if(rand(0,100)>90){
										$shans_type=rand(0,100); // тип травмы зависит от этого рандомного числа
										if($shans_type==0){
											$tr_type=0;
										}elseif($shans_type<50){
											$tr_type=1;
											$at[1][$i]['ttravm']=', в итоге, {u2} получил'.$sxsa.' легкое повреждение: <font color=red>синяк под левым глазом</font>. ';
										}elseif($shans_type>50 and $shans_type<80){
											$tr_type=2;
											$at[1][$i]['ttravm']=', в итоге, {u2} получил'.$sxsa.' среднее повреждение: <font color=red>синяк под левым глазом</font>. ';
										}else{
											$tr_type=3;
											$at[1][$i]['ttravm']=', в итоге, {u2} получил'.$sxsa.' тяжелое повреждение: <font color=red>синяк под левым глазом</font>. ';
										}
									}
										//расчет шанса травмы окончен   
										if($tr_type!=0){
											$this->addTravm($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['id'],$tr_type,$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['lvl']);
										}
									}/*elseif($this->info['type']==99 and $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']<=0 and $trawm_off==false){
									$trawm_off=true;
										$at[1][$i]['ttravm']='получил <font color=red><b>Тяжелую травму</b> (Кровь)</font>.';
										$this->addTravm($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['id'],3,$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['lvl']);
								}*/
							}
							
							/* расчет травмы 1 */	
								
							$this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_yron'] += $y['y'];
							if($lbs+$lbs1>1){ $addExp = floor($addExp/($lbs+$lbs1)); }
							$this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_exp'] += $addExp;
							//$this->takeExp($this->atacks[$id]['uid1'],$y['y'],$this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],false,true);
							
							$i++;
						}

					//удары игрока 2
						$i = 1;
						$trawm_off=false;
						while($i<=count($at[2]))
						{
							$at[2][$i]['y'] = $this->testPog($this->atacks[$id]['uid1'],$at[2][$i]['y']);
							$y = $at[2][$i];
							if($y['y']<0){ $y['y'] = 0; }
							
							if($y['y'] > 0) {
								$this->users[$this->uids[$this->atacks[$id]['uid1']]]['last_hp'] = -$y['y'];
							}							
							
							$addExp = 0+($y['y']/$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpAll']*100);
							if($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']+10-$y['y']<0)
							{
								$addExp = 0+(($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']+10)/$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpAll']*100);
							}
							if($addExp<0){ $addExp = 0; }					
							if($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['levels']!='undefined')
							{	
								$addExp = $addExp*(1+$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['levels']['expBtlMax']+$doexp1)/100;
							}else{
								//echo '[!]';
							}
							$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow'] -= $y['y'];
							
							/* расчет травмы 2 */	
						
							if(!isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['notravma']) || $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['notravma']==0) {
								if(($at[2][$i]['t']==3 or $at[2][$i]['t']==4) and $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']<=0 and $trawm_off==false and $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['lvl']>=2 and $this->info['type']!=99){
									$trawm_off=true;
									$sxsa = '';
									if($this->users[$this->uids[$this->atacks[$id]['uid2']]] == 1) {
										$sxsa = 'а';
									}
										// Расчет типа травмы, тяжелая травма получаеться в поединках специальных, по этому её не считаем
										$tr_type=0;
									if(rand(0,100)>90){
										$shans_type=rand(0,100); // тип травмы зависит от этого рандомного числа
										if($shans_type==0){$tr_type=0;}
										elseif($shans_type<50){
												 $tr_type=1;
												 $at[2][$i]['ttravm']=', в итоге, {u1} получил'.$sxsa.' легкое повреждение: <font color=red>синяк под левым глазом</font>. ';
										}elseif($shans_type>50 and $shans_type<80){
												 $tr_type=2;
												 $at[2][$i]['ttravm']=', в итоге, {u1} получил'.$sxsa.' среднее повреждение: <font color=red>синяк под левым глазом</font>. ';
										}else{
												 $tr_type=3;
												 $at[2][$i]['ttravm']=', в итоге, {u1} получил'.$sxsa.' тяжелое повреждение: <font color=red>синяк под левым глазом</font>. ';
										}
									}
									//расчет шанса травмы окончен
									
									if($tr_type!=0){
										$this->addTravm($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['id'],$tr_type,$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['lvl']);
									}
									}elseif($this->info['type']==99 and $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']<=0 and $trawm_off==false){
									$trawm_off=true;
										$at[2][$i]['ttravm']='получил <font color=red><b>Тяжелую травму</b> (Кровь)</font>.';
										$this->addTravm($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['id'],3,$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['lvl']);
								}
							}
							
							/* расчет травмы 2 */	
							
							$this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_yron'] += $y['y'];
							if($lbs+$lbs2>1){ $addExp = floor($addExp/($lbs+$lbs2)); }
							$this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_exp'] += $addExp;
							//$this->takeExp($this->atacks[$id]['uid2'],$y['y'],$this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],false,true);
							$i++;
						}
						
						if($this->atacks[$id]['time'] ==0){ $this->atacks[$id]['time'] = time(); }
						if($this->atacks[$id]['time2']==0){ $this->atacks[$id]['time2']= time(); }
						
						$s1 = $this->users[$this->uids[$this->atacks[$id]['uid1']]]['sex'];
						$s2 = $this->users[$this->uids[$this->atacks[$id]['uid2']]]['sex'];
						
						$vLog = 'at1='.$at[1][1]['v'].''.$at[1][2]['v'].''.$at[1][3]['v'].''.$at[1][4]['v'].''.$at[1][5]['v'].'||at2='.$at[2][1]['v'].''.$at[2][2]['v'].''.$at[2][3]['v'].''.$at[2][4]['v'].''.$at[2][5]['v'].'||bl1='.$this->atacks[$id]['b1'].'||bl2='.$this->atacks[$id]['b2'].'||zb1='.$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['zonb'].'||zb2='.$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['zonb'].'||time1='.$this->atacks[$id]['time'].'||time2='.$this->atacks[$id]['time2'].'||s2='.$s2.'||s1='.$s1.'||t2='.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['team'].'||t1='.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['team'].'||login1='.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['login2'].'||login2='.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['login2'].'';
						
						$this->get_comment();
						
						//0 - нет урона, 1 - колющий, 2 - рубящий, 3 - дробящий, 4 - режущий, 5 - огонь, 6 - воздух, 7 - вода, 8 - земля, 9 - свет, 10 - тьма, 11 - серая
						$tpyr1 = array(-1=>'оружие&nbsp;было&nbsp;не&nbsp;найдено',0=>'отсутствует',1=>'колющий',2=>'рубящий',3=>'дробящий',4=>'режущий',5=>'огненный',6=>'электрический',7=>'ледяной',8=>'земляной',9=>'Свет',10=>'Тьма',11=>'Серой&nbsp;магии');
						
						//заносим данные в лог боя игрока 1 по игроку 2
						
						$kntd = array( $kntr2 , $kntr1 );
						
						$i = 1;
						while($i<=count($at[1]))
						{
							$y = $at[1][$i]; $yki = ''; 
							$ttltp = 'Тип&nbsp;урона:&nbsp;'.$tpyr1[$y['x']].'&nbsp;';
							if($y['prb']==1)
							{
								$ttltp .= '(пробой&nbsp;брони)';
							}
							$kyda = $this->lg_zon[$y['v']][rand(0,(count($this->lg_zon[$y['v']])-1))];
							$chem = $this->lg_itm[$y['z']][rand(0,(count($this->lg_itm[$y['z']])-1))];
							
							if($y['y']<0){ $y['y'] = 0; }
							if($y['ki']==1){ $y['y'] = round($y['y']); /*$yki = ' <i>(контрудар)</i>';*/ }
							$hp2 -= $y['y'];
							if($hp2<0)
							{
								$hp2 = 0; 
								//игрок погиб
							}
							if($y['minf'] != '') {
								$ttltp .= "&nbsp;/&nbsp;Подвержен&nbsp;эффектам:&nbsp;".str_replace(' ','&nbsp;',ltrim($y['minf'],','));
							}
							$takt = array();
							$mas = array('time'=>'','battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');	
							
							if($y['t']==0)
							{
								//пропуск хода
								if($at[1] != -1 && $nopro[1][$i] == 0) {
									$mas['time'] = time();
									$mas['text'] = '{tm1} {u1} {1x15x'.$this->atacks[$id]['tpo1'].'}.';
								}
							}elseif($y['t']==1)
							{
								//обычный удар								
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp3id']) && $i==1)
								{
									if($y['w_bad'] != true) {
										$tac1[1]++;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp3id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp3id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac1[1] += 2;
										}
									}
								}elseif(!isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp14id']) && $i==1) {
									if($y['w_bad'] != true) {
										$tac1[1]++;
									}
								}
															
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp14id']) && $i==2)
								{
									if($y['w_bad'] != true) {
										$tac1[1]++;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp14id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp14id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac1[1] += 2;
										}
									}
								}								
								$mas['time'] = time();	
								if($y['y']<=0)
								{
									$y['y'] = '-';
								}
								$mas['text'] = '{tm1} {zn2_'.$y['v'].'} {u2} {2x1x'.rand(0,(count($log_text[$s2][1])-1)).'}{2x2x'.rand(0,(count($log_text[$s2][2])-1)).'}{1x3x'.rand(0,(count($log_text[$s1][3])-1)).'} {u1} {1x4x'.rand(0,(count($log_text[$s1][4])-1)).'}{1x5x'.rand(0,(count($log_text[$s1][5])-1)).'}{1x6x'.rand(0,(count($log_text[$s1][6])-1)).'} {1x7x'.$y['y2'].'} '.$chem.' '.$kyda.' на <b title='.$ttltp.'><font color=#006699>-'.$y['y'].'</font></b> '.$yki.' '.$this->hpSee($hp2,$hpA2);
								$y['y10'] = $y['y'];
								$y['y'] = 0;
							}elseif($y['t']==3)
							{
								//крит удар
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp3id']) && $i==1)
								{
									if($y['w_bad'] != true) {
										$tac1[2] += 2;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp3id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp3id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac1[2] += rand(0,1);
										}
									}
								}								
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp14id']) && $i==2)
								{
									if($y['w_bad'] != true) {
										$tac1[2]++;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp14id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['wp14id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac1[2] += rand(0,1);
										}
									}
								}	
								$mas['time'] = time();	
								if($y['y']<=0)
								{
									$y['y'] = '-';
								}
								if($y['ttravm'] == '') {
									$y['ttravm'] = ' на ';
								}
								$mas['text'] = '{tm1} {zn2_'.$y['v'].'} {u2} {2x1x'.rand(0,(count($log_text[$s2][1])-1)).'}{2x2x'.rand(0,(count($log_text[$s2][2])-1)).'}{1x3x'.rand(0,(count($log_text[$s1][3])-1)).'} {u1} {1x4x'.rand(0,(count($log_text[$s1][4])-1)).'}{1x5x'.rand(0,(count($log_text[$s1][5])-1)).'}{1x6x'.rand(0,(count($log_text[$s1][6])-1)).'} {1x7x'.$y['y2'].'} '.$chem.' '.$kyda.''.$y['ttravm'].' <b title='.$ttltp.'><font color=red>-'.$y['y'].'</font></b>'.$yki.' '.$this->hpSee($hp2,$hpA2);
								$y['y10'] = $y['y'];
								$y['y'] = 0;
							}elseif($y['t']==4)
							{
								//крит удар через блок
								$mas['time'] = time();	
								if($y['w_bad'] != true) {
									$tac1[2]++;	
								}
								if($y['y']<=0)
								{
									$y['y'] = '-';
								}	
								if($y['ttravm'] == '') {
									$y['ttravm'] = ' на ';
								}		
								$mas['text'] = '{tm1} {zn2_'.$y['v'].'} {u2} {2x1x'.rand(0,(count($log_text[$s2][1])-1)).'}{2x2x'.rand(0,(count($log_text[$s2][2])-1)).'}{1x3x'.rand(0,(count($log_text[$s1][3])-1)).'} {u1} {1x4x'.rand(0,(count($log_text[$s1][4])-1)).'}{1x5x'.rand(0,(count($log_text[$s1][5])-1)).'}{1x6x'.rand(0,(count($log_text[$s1][6])-1)).'} {1x7x'.$y['y2'].'} <u>пробив блок</u> '.$chem.' '.$kyda.''.$y['ttravm'].' <b title='.$ttltp.'><font color=red>-'.$y['y'].'</font></b>'.$yki.' '.$this->hpSee($hp2,$hpA2);
								$y['y10'] = $y['y'];
								$y['y'] = 0;
							}elseif($y['t']==5)
							{
								//противник заблокировал удар
								$tac2[4]++;
								$mas['time'] = time();
								$mas['text'] = '{tm1} {zn2_'.$y['v'].'} {u1} {1x8x'.rand(0,(count($log_text[$s1][8])-1)).'}{1x9x'.rand(0,(count($log_text[$s1][9])-1)).'} {u2} {2x10x'.rand(0,(count($log_text[$s2][10])-1)).'} {1x7x0} '.$chem.' '.$kyda.'.'.$yki.'';
							}elseif($y['t']==7)
							{
								//противник заблокировал удар щитом
								$tac2[4]++;
								$mas['time'] = time();
								$mas['text'] = '{tm1} {zn2_'.$y['v'].'} {u1} {1x8x'.rand(0,(count($log_text[$s1][8])-1)).'}{1x9x'.rand(0,(count($log_text[$s1][9])-1)).'} {u2}, воспользовавшись <b>своим щитом</b>, {2x10x'.rand(0,(count($log_text[$s2][10])-1)).'} {1x7x0} '.$chem.' '.$kyda.'.'.$yki.'';
							}elseif($y['t']==6)
							{
								//противник увернулся
								if( $kntd[0] > 0 ) {
									$kntd[0]--;
									$yki24 = ' и {1x5x'.rand(0,(count($log_text[$s1][5])-1)).'} <b>контрудар</b>';
								}
								$tac2[3]++;
								$mas['time'] = time(); 
								$mas['text'] = '{tm1} {zn2_'.$y['v'].'} {u1} {1x8x'.rand(0,(count($log_text[$s1][8])-1)).'}{1x9x'.rand(0,(count($log_text[$s1][9])-1)).'} {u2} {2x11x'.rand(0,(count($log_text[$s2][11])-1)).'} '.$chem.' '.$kyda.''.$yki24.'.'.$yki.'';
								unset($yki24);
							}elseif($y['t']==9)
							{
								//противник парировал
								$mas['time'] = time(); 
								$tac2[5]++;
								$mas['text'] = '{tm1} {zn2_'.$y['v'].'} {u1} {1x8x'.rand(0,(count($log_text[$s1][8])-1)).'}{1x9x'.rand(0,(count($log_text[$s1][9])-1)).'} {u2} <b>парировал</b> '.$chem.' '.$kyda.'.'.$yki.'';
							}else{
								$mas['time'] = time();
								$mas['text'] = '<i>{u1}. Неопознанный удар.</i>'.$yki.'';
							}	
							if($y['y10']>0 && $y['y10']!='-')
							{
								if($y['w_bad'] != true) {
									$tac1[6] += $y['y10']/($hpA2/100*10);
								}
							}
							if($y['ki']==1){ if($y['w_bad'] != true) { $tac1[3]++; } }
							$this->add_log($mas);
							//
							/*if($y['ttravm']!=''){
								$mas = array('time'=>'','battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');	
                            	$mas['time'] = time();
								$mas['text'] = '{tm1} {u2} '.$y['ttravm'];
								$this->add_log($mas);
							}*/
							
							$i++;
						}
						
						
						//заносим данные в лог боя удары игрока 2 по игроку 1
						$i = 1;
						while($i<=count($at[2]))
						{
							$y = $at[2][$i]; $yki = '';
							$ttltp = 'Тип&nbsp;урона:&nbsp;'.$tpyr1[$y['x']].'&nbsp;';
							if($y['prb']==1)
							{
								$ttltp .= '(пробой&nbsp;брони)';
							}
							$kyda = $this->lg_zon[$y['v']][rand(0,(count($this->lg_zon[$y['v']])-1))];
							$chem = $this->lg_itm[$y['z']][rand(0,(count($this->lg_itm[$y['z']])-1))];
							if($y['y']<0){ $y['y'] = 0; }
							if($y['ki']==1){ /*$yki = ' <i>(контрудар)</i>';*/ }
							$hp1 -= $y['y'];
							if($hp1<0)
							{
								$hp1 = 0; 
								//игрок погиб
							}
							if($y['minf'] != '') {
								$ttltp .= "&nbsp;/&nbsp;Подвержен&nbsp;эффектам:&nbsp;".str_replace(' ','&nbsp;',ltrim($y['minf'],','));
							}
							//добавляем в лог
							$mas = array('time'=>'','battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
							if($y['t']==0)
							{
								//пропуск хода
								if($at[2] != -1 && $nopro[2][$i] == 0) {
									$mas['time'] = time();
									//$mas['text'] = '{tm2} {u2} {2x15x0}.';
									$mas['text'] = '{tm2} {u2} {2x15x'.$this->atacks[$id]['tpo2'].'}.';
								}
							}elseif($y['t']==1)
							{
								//обычный удар								
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp3id']) && $i==1)
								{
									if($y['w_bad'] != true) {
										$tac2[1]++;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp3id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp3id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac2[1] +=2;
										}
									}
								}elseif(!isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp14id']) && $i==1) {
									if($y['w_bad'] != true) {
										$tac2[1]++;
									}
								}
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp14id']) && $i==2)
								{
									if($y['w_bad'] != true) {
										$tac2[1]++;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp14id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp14id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac2[1] += 2;
										}
									}

								}								
								$mas['time'] = time();
								if($y['y']<=0)
								{
									$y['y'] = '-';
								}	
								$mas['text'] = '{tm2} {zn1_'.$y['v'].'} {u1} {1x1x'.rand(0,(count($log_text[$s1][1])-1)).'}{1x2x'.rand(0,(count($log_text[$s1][2])-1)).'}{2x3x'.rand(0,(count($log_text[$s2][3])-1)).'} {u2} {2x4x'.rand(0,(count($log_text[$s2][4])-1)).'}{2x5x'.rand(0,(count($log_text[$s2][5])-1)).'}{2x6x'.rand(0,(count($log_text[$s2][6])-1)).'} {2x7x'.$y['y2'].'} '.$chem.' '.$kyda.' на <font color=#006699><b title='.$ttltp.'>-'.$y['y'].'</b></font>'.$yki.' '.$this->hpSee($hp1,$hpA1);
								$y['y10'] = $y['y'];
								$y['y'] = 0;
							}elseif($y['t']==3)
							{
								//крит удар
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp3id']) && $i==1)
								{
									if($y['w_bad'] != true) {
										$tac2[2] += 2;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp3id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp3id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac2[2] += rand(0,1);
										}
									}
								}								
								if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp14id']) && $i==2)
								{
									if($y['w_bad'] != true) {
										$tac2[2] += 2;
									}
									if(isset($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp14id']]) && $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['items'][$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['wp14id']]['2h']==1)
									{
										if($y['w_bad'] != true) {
											$tac2[2] += rand(0,1);
										}
									}
								}	
								$mas['time'] = time();
								if($y['y']<=0)
								{
									$y['y'] = '-';
								}	
								if($y['ttravm'] == '') {
									$y['ttravm'] = ' на ';
								}
								$mas['text'] = '{tm2} {zn1_'.$y['v'].'} {u1} {1x1x'.rand(0,(count($log_text[$s1][1])-1)).'}{1x2x'.rand(0,(count($log_text[$s1][2])-1)).'}{2x3x'.rand(0,(count($log_text[$s2][3])-1)).'} {u2} {2x4x'.rand(0,(count($log_text[$s2][4])-1)).'}{2x5x'.rand(0,(count($log_text[$s2][5])-1)).'}{2x6x'.rand(0,(count($log_text[$s2][6])-1)).'} {2x7x'.$y['y2'].'} '.$chem.' '.$kyda.''.$y['ttravm'].' <b title='.$ttltp.'><font color=red>-'.$y['y'].'</font></b>'.$yki.' '.$this->hpSee($hp1,$hpA1);
								$y['y10'] = $y['y'];
								$y['y'] = 0;
							}elseif($y['t']==4)
							{
								//крит удар через блок
								if($y['w_bad'] != true) {
									$tac2[2]++;
								}
								$mas['time'] = time();
								if($y['y']<=0)
								{
									$y['y'] = '-';
								}
								if($y['ttravm'] == '') {
									$y['ttravm'] = ' на ';
								}
								$mas['text'] = '{tm2} {zn1_'.$y['v'].'} {u1} {1x1x'.rand(0,(count($log_text[$s1][1])-1)).'}{1x2x'.rand(0,(count($log_text[$s1][2])-1)).'}{2x3x'.rand(0,(count($log_text[$s2][3])-1)).'} {u2} {2x4x'.rand(0,(count($log_text[$s2][4])-1)).'}{2x5x'.rand(0,(count($log_text[$s2][5])-1)).'}{2x6x'.rand(0,(count($log_text[$s2][6])-1)).'} {2x7x'.$y['y2'].'} <u>пробив блок</u> '.$chem.' '.$kyda.''.$y['ttravm'].' <b title='.$ttltp.'><font color=red>-'.$y['y'].'</font></b>'.$yki.' '.$this->hpSee($hp1,$hpA1);
								$y['y10'] = $y['y'];
								$y['y'] = 0;
							}elseif($y['t']==5)
							{
								//противник заблокировал удар
								$tac1[4]++;
								$mas['time'] = time();
								$mas['text'] = '{tm2} {zn1_'.$y['v'].'} {u2} {2x8x'.rand(0,(count($log_text[$s2][8])-1)).'}{2x9x'.rand(0,(count($log_text[$s2][9])-1)).'} {u1} {1x10x'.rand(0,(count($log_text[$s1][10])-1)).'} {1x7x0} '.$chem.' '.$kyda.'.'.$yki.'';
							}elseif($y['t']==7)
							{
								//противник заблокировал удар щитом
								$tac1[4]++;
								$mas['time'] = time();
								$mas['text'] = '{tm2} {zn1_'.$y['v'].'} {u2} {2x8x'.rand(0,(count($log_text[$s2][8])-1)).'}{2x9x'.rand(0,(count($log_text[$s2][9])-1)).'} {u1}, воспользовавшись <b>своим щитом</b>, {1x10x'.rand(0,(count($log_text[$s1][10])-1)).'} {1x7x0} '.$chem.' '.$kyda.'.'.$yki.'';
							}elseif($y['t']==6)
							{
								//противник увернулся от удара
								$mas['time'] = time(); 
								if( $kntd[1] > 0 ) {
									$kntd[1]--;
									$yki24 = ' и {1x5x'.rand(0,(count($log_text[$s2][5])-1)).'} <b>контрудар</b>';
								}
								$tac1[3]++;
								$mas['time'] = time();
								$mas['text'] = '{tm2} {zn1_'.$y['v'].'} {u2} {2x8x'.rand(0,(count($log_text[$s2][8])-1)).'}{2x9x'.rand(0,(count($log_text[$s2][9])-1)).'} {u1} {1x11x'.rand(0,(count($log_text[$s1][11])-1)).'} '.$chem.' '.$kyda.''.$yki24.'.'.$yki.'';
								unset($yki24);
							}elseif($y['t']==9)
							{
								//противник парировал удар
								$mas['time'] = time();
								$tac1[5]++;
								$mas['text'] = '{tm2} {zn1_'.$y['v'].'} {u2} {2x8x'.rand(0,(count($log_text[$s2][8])-1)).'}{2x9x'.rand(0,(count($log_text[$s2][9])-1)).'} {u1} <b>парировал</b> '.$chem.' '.$kyda.'.'.$yki.'';
							}else{
								$mas['time'] = time();
								$mas['text'] = '<i>{u2}. Неопознанный удар.</i>'.$yki.'';
							}		
							if($y['y10']>0 && $y['y10']!='-')
							{
								if($y['w_bad'] != true) {
									$tac2[6] += $y['y10']/($hpA1/100*10);
								}
							}
							if($y['ki']==1){ if($y['w_bad'] != true) { $tac2[3]++; } }
							$this->add_log($mas);
							//
							
							/*if($y['ttravm']!=''){
								$mas = array('time'=>'','battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');	
                            	$mas['time'] = time();
								$mas['text'] = '{tm1} {u1} '.$y['ttravm'];
								$this->add_log($mas);
							}*/
							
							$i++;
						}
						
						
						
						//добавляем тактики
						if($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['notactic'] != 1) {
							$l = 1;
							while($l<=7)
							{
								if($this->users[$this->uids[$this->atacks[$id]['uid1']]]['level']>1)
								{
									$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic'.$l] += $tac1[$l];
								}
								if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['level']>1)
								{
									$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic'.$l] += $tac2[$l];
								}
								$l++;
							}
						}
										
						//обновляем данные об ударе
							//обновляем задержки приемов
							$zd1 = explode('|',$this->users[$this->uids[$this->atacks[$id]['uid1']]]['priems_z']);
							$zd2 = explode('|',$this->users[$this->uids[$this->atacks[$id]['uid2']]]['priems_z']);
							$i5 = 0;

							while($i5<51)
							{
								if(isset($zd1[$i5]) && $zd1[$i5]>0)
								{
									$zd1[$i5] -= 1;
								}
								if(isset($zd2[$i5]) && $zd2[$i5]>0)
								{
									$zd2[$i5] -= 1;
								}
								$i5++;
							}
							
							$this->users[$this->uids[$this->atacks[$id]['uid1']]]['priems_z'] = implode('|',$zd1);
							$this->users[$this->uids[$this->atacks[$id]['uid2']]]['priems_z'] = implode('|',$zd2);	
							if($this->atacks[$id]['uid1']==$u->info['id'])
							{
								$u->info['priems_z'] = implode('|',$zd1);					
							}elseif($this->atacks[$id]['uid2']==$u->info['id'])
							{
								$u->info['priems_z'] = implode('|',$zd2);					
							}
							$ki = 1;
							while($ki<=6)
							{
								if($this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic'.$ki]>25)
								{
									$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic'.$ki] = 25;
								}
								if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic'.$ki]>25)
								{
									$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic'.$ki] = 25;
								}
								$ki++;	
							}
						if($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpAll']>0)
						{
							//$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] += round($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpAll']*0.01);
							$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] += $this->users[$this->uids[$this->atacks[$id]['uid1']]]['level'];
						}
						if($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpAll']>0)
						{
							$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] += $this->users[$this->uids[$this->atacks[$id]['uid2']]]['level'];
							//$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] += round($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpAll']*0.01);
						}
						
						if($this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'] == $this->users[$this->uids[$this->atacks[$id]['uid2']]]['id']) {
							$this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'] = -$this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'];
						}
						if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'] == $this->users[$this->uids[$this->atacks[$id]['uid1']]]['id']) {
							$this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'] = -$this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'];
						}						
						if($this->users[$this->uids[$this->atacks[$id]['uid1']]]['id'] == $u->info['id']) {
							$u->info['enemy'] = $this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'];
						}
						if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['id'] == $u->info['id']) {
							$u->info['enemy'] = $this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'];
						}
						
						//Размен на задержки предмета
						mysql_query('UPDATE `items_users` SET `btl_zd` = `btl_zd` - 1 WHERE (`uid` = "'.$this->atacks[$id]['uid1'].'" OR `uid` = "'.$this->atacks[$id]['uid2'].'") AND `btl_zd` > 0 AND `inOdet` > 0 LIMIT 100');
						
						mysql_query('UPDATE `users` SET `notrhod` = "-1" WHERE `id` = "'.$this->atacks[$id]['uid1'].'" OR `id` = "'.$this->atacks[$id]['uid2'].'" LIMIT 2');
						$up1 = mysql_query('UPDATE `stats` SET
						 `priems_z` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['priems_z'].'",
						 `tactic1` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic1'].'",
						 `tactic2` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic2'].'",
						 `tactic3` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic3'].'",
						 `tactic4` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic4'].'",
						 `tactic5` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic5'].'",
						 `tactic6` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic6'].'",
						 `tactic7` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic7'].'",						
						 `battle_yron` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_yron'].'",`last_hp` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['last_hp'].'",`battle_exp` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_exp'].'",`hpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow'].'",`mpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'].'",`enemy` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'].'" WHERE `id` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['id'].'" LIMIT 1');
						$up2 = mysql_query('UPDATE `stats` SET
						 `priems_z`= "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['priems_z'].'",
						 `tactic1` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic1'].'",
						 `tactic2` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic2'].'",
						 `tactic3` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic3'].'",
						 `tactic4` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic4'].'",
						 `tactic5` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic5'].'",
						 `tactic6` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic6'].'",
						 `tactic7` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic7'].'",
						 `battle_yron` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_yron'].'",`last_hp` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['last_hp'].'",`battle_exp` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_exp'].'",`hpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow'].'",`mpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'].'",`enemy` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'].'" WHERE `id` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['id'].'" LIMIT 1');
						
						$this->clear_cache( $this->atacks[$id]['uid1'] );
						$this->clear_cache( $this->atacks[$id]['uid2'] );
						
						$this->users[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'];
						$this->users[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'];
						
						//Эффекты которые слетают в случаи гибели игрока
						if($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow'] <= 0) {
							mysql_query('DELETE FROM `eff_users` WHERE `tr_life_user` > 0 AND `tr_life_user` = "'.$this->atacks[$id]['uid1'].'"');
						}
						if($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow'] <= 0) {
							mysql_query('DELETE FROM `eff_users` WHERE `tr_life_user` > 0 AND `tr_life_user` = "'.$this->atacks[$id]['uid2'].'"');
						}
						
						//Расчет кто кого убил
						if($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']>=1 && $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']<=0)
						{
							
							if($this->info['izlom'] > 0) {
								if( $this->users[$this->uids[$this->atacks[$id]['uid2']]]['team'] == 2 && $this->users[$this->uids[$this->atacks[$id]['uid2']]]['bot_id'] >  0 ) {
									$u->addAction(time(),'win_bot_'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['bot_id'],'',$this->atacks[$id]['uid1']);
								}
							}
							
							//игрок 1 убил игрока 2
							$azzz = $u->testAction('`uid` = "'.$this->atacks[$id]['uid2'].'" AND `vars` LIKE "%kill_user'.$this->info['id'].'&%"  LIMIT 1',2);
							if($azzz[0]<1)
							{
								$u->addAction(time(),'kill_user'.$this->info['id'].'&'.$id,$this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
							}
							$asxf = '';
							if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['sex']==1)
							{
								$asxf = 'ла';
							}
							$this->addFlog('{tm1} <b>'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['login'].'</b> '.$log_text[$this->users[$this->uids[$this->atacks[$id]['uid2']]]['sex']][14][rand(0,(count($log_text[$this->users[$this->uids[$this->atacks[$id]['uid2']]]['sex']][14])-1))].'',$this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
							unset($asxf);
							
							
									
								/* награда */
								if($this->users[$this->uids[$this->atacks[$id]['uid2']]]['bot_id']>0) {
									$tbot2 = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['bot_id'].'" LIMIT 1'));
									if($tbot2['award']!='') {
										$awr = explode(',',$tbot2['award']);
										$tii = 0; $awru = '';
										while($tii<count($awr)) {
											$awrd = explode('=',$awr[$tii]);
											if($awrd[1]>0) {
												$awru .= '`bn_'.$awrd[0].'` = `bn_'.$awrd[0].'` + '.$awrd[1].',';
											}
											$tii++;
										}
										$awru = trim($awru,',');
										if($awru!='') {
											mysql_query('UPDATE `stats` SET '.$awru.' WHERE `id` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['id'].'" LIMIT 1');
										}
									}
								}
							
							
						}
						if($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']>=1 && $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']<=0)
						{
							
							if($this->info['izlom'] > 0) {
								if( $this->users[$this->uids[$this->atacks[$id]['uid1']]]['team'] == 2 && $this->users[$this->uids[$this->atacks[$id]['uid1']]]['bot_id'] > 0) {
									$u->addAction(time(),'win_bot_'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['bot_id'],'',$this->atacks[$id]['uid2']);
								}
							}
							
							//игрок 2 убил игрока 1
							$azzz = $u->testAction('`uid` = "'.$this->atacks[$id]['uid1'].'" AND `vars` LIKE "%kill_user'.$this->info['id'].'&%" LIMIT 1',2);
							if($azzz[0]<1)
							{
								$u->addAction(time(),'kill_user'.$this->info['id'].'&'.$id,$this->atacks[$id]['uid2'],$this->atacks[$id]['uid1']);
							}
							$asxf = '';
							if($this->users[$this->uids[$this->atacks[$id]['uid1']]]['sex']==1)
							{
								$asxf = 'ла';
							}
							//$this->addFlog('{tm1} {u2} погиб'.$asxf.' от рук {u1}.',$this->atacks[$id]['uid2'],$this->atacks[$id]['uid1']);
							$this->addFlog('{tm1} <b>'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['login'].'</b> '.$log_text[$this->users[$this->uids[$this->atacks[$id]['uid1']]]['sex']][14][rand(0,(count($log_text[$this->users[$this->uids[$this->atacks[$id]['uid1']]]['sex']][14])-1))].'',$this->atacks[$id]['uid2'],$this->atacks[$id]['uid1']);
							unset($asxf);
							
							
								/* награда */ 
								if($this->users[$this->uids[$this->atacks[$id]['uid1']]]['bot_id']>0) {
									$tbot2 = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['bot_id'].'" LIMIT 1'));
									if($tbot2['award']!='') {
										$awr = explode(',',$tbot2['award']);
										$tii = 0; $awru = '';
										while($tii<count($awr)) {
											$awrd = explode('=',$awr[$tii]);
											if($awrd[1]>0) {
												$awru .= '`bn_'.$awrd[0].'` = `bn_'.$awrd[0].'` + '.$awrd[1].',';
											}
											$tii++;
										}
										$awru = trim($awru,',');
										if($awru!='') {
											mysql_query('UPDATE `stats` SET '.$awru.' WHERE `id` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['id'].'" LIMIT 1');
										}
									}
								}
							
							
						}
						
						if(!$up1)
						{
							
						}
						if(!$up2)
						{

						}
						unset($this->ga[$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->ga[$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']]);
						unset($this->ag[$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->ag[$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']]);
						mysql_query('DELETE FROM `battle_act` WHERE `id` = "'.$this->atacks[$id]['id'].'" LIMIT 1');
						/* ICQ создателя: 179-171 */
						//Возращаем зоны блока
						$this->restZonb($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
						
						//Добавляем ману, если она есть и не полная
						
						//Минусуем заряд приема \ эффекта
						$j = 1; $jn = 1;
						while($j<=2)
						{
							$eff = $this->stats[$this->uids[$this->atacks[$id]['uid'.$j]]]['effects'];
							$i = 0; 
							while($i<count($eff))
							{
								if(isset($eff[$i]))
								{
									if($eff[$i]['timeUse']==77 && $eff[$i]['hod']>-1)
									{
										$eff[$i]['hod']--;
										$eff[$i]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i]['v2'].'" LIMIT 1'));
										$re = $priem->hodUsePriem($eff[$i],$eff[$i]['priem']);
										if(isset($re['hod'])) {
											$eff[$i]['hod'] = $re['hod'];
										}
										if(isset($this->rehodeff[$eff[$i]['id']])) {
											$eff[$i]['hod'] = $this->rehodeff[$eff[$i]['id']];
										}
										if($eff[$i]['hod']>0)
										{
											$this->stats[$this->uids[$this->atacks[$id]['uid'.$j]]]['effects']['hod'] = $eff[$i]['hod'];

											mysql_query('UPDATE `eff_users` SET `hod` = "'.$eff[$i]['hod'].'" WHERE `id` = "'.$eff[$i]['id'].'" LIMIT 1');
										}else{
											//удаляем прием
											if($eff[$i]['v2']>0)
											{												
												if($j==1)
												{
													$jn = 2;
												}else{
													$jn = 1;	
												}
												$this->delPriem($eff[$i],$this->users[$this->uids[$this->atacks[$id]['uid'.$j]]],3,$this->atacks[$id]['uid'.$jn]);
											}
										}
									}elseif($eff[$i]['timeUse']==77 && $eff[$i]['hod']==-2)
									{
										$eff[$i]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i]['v2'].'" LIMIT 1'));
										$priem->hodUsePriem($eff[$i],$eff[$i]['priem']);
									}
								}
								$i++;
							}
							$j++;
						}
			}
		}
		
	//Отображение НР
		public function hpSee($now,$all,$type = 1) {
			$r = '['.$now.'/'.$all.']';
			if($all > 10000) {
				$type = 2;
			}
			if($type == 1) {
				
			}elseif($type == 2) {
				$p1 = floor($now/$all*100);
				$r = '['.$p1.'/100%]';
			}
			return $r;
		}
		
	//Быстрый лог
		public function addFlog($t,$u1,$u2)
		{
				$vLog = '';
				if(isset($this->info[$this->uids[$u1]]['id']))
				{
					$vLog .= 'time1='.time().'||s1='.$this->users[$this->uids[$u1]]['id']['sex'].'||t1='.$this->users[$this->uids[$u1]]['team'].'||login1='.$this->users[$this->uids[$u1]]['login'].'||';
				}
				if(isset($this->info[$this->uids[$u2]]['id']))
				{
					$vLog .= 'time2='.time().'||s2='.$this->users[$this->uids[$u2]]['sex'].'||t2='.$this->users[$this->uids[$u2]]['team'].'||login2='.$this->users[$this->uids[$u2]]['login'].'';
				}
				$vLog = rtrim($vLog,'||');
				$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				$mas1['text'] = $t;
				$this->add_log($mas1);
		}
				
	//Выводим лог боя
		public function lookLog()
		{
			global $c,$u,$log_text;
			$js = ''; $pll = 0;
			if($_POST['idlog']<1){ $_POST['idlog'] = 0; }
			$sp = mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'" AND `id` > '.mysql_real_escape_string($_POST['idlog']).' AND `id_hod` > '.($this->hodID-10).' LIMIT 150');
			$jin = 0; $forYou2 = 0;
			while($pl = mysql_fetch_array($sp))
			{
				$jin++;
				if($pl['type']==1 || $pl['type']==6)
				{
					$dt = explode('||',$pl['vars']);
					$i = 0; $d = array();
					while($i<count($dt))
					{
						$r = explode('=',$dt[$i]);
						if($r[0]!='')
						{
							$d[$r[0]] = $r[1];
						}
						$i++;
					}
					//обычный удар
					$rt = $pl['text'];
					
					$forYou = '';	 $forYou2 = 0;				
					if(($d['login1'] == $u->info['login2'] && $d['login1'] != '') || ($d['login1'] == $u->info['login'] && $d['login1'] != '')) {
						$forYou = 'forYou'; $forYou2 = 1;
					}elseif(($d['login2'] == $u->info['login2'] && $d['login2'] != '') || ($d['login2'] == $u->info['login'] && $d['login2'] != '')) {
						$forYou = 'forYou'; $forYou2 = 2;
					}
					
					//заменяем данные
					$rt = str_replace('{u1}','<span onClick=\"top.chat.addto(\''.$d['login1'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$d['login1'].'\',event,\'chat\'); return false;\" class=\"CSSteam'.$d['t1'].'\">'.$d['login1'].'</span>',$rt);
					$rt = str_replace('{u2}','<span onClick=\"top.chat.addto(\''.$d['login2'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$d['login2'].'\',event,\'chat\'); return false;\" class=\"CSSteam'.$d['t2'].'\">'.$d['login2'].'</span>',$rt);
					$rt = str_replace('{tm1}','<span class=\"date '.$forYou.'\">'.date('H:i',$d['time1']).'</span>',$rt);
					$rt = str_replace('{tm2}','<span class=\"date '.$forYou.'\">'.date('H:i',$d['time2']).'</span>',$rt);
					$rt = str_replace('{tm3}','<span class=\"date '.$forYou.'\">'.date('d.m.Y H:i',$d['time1']).'</span>',$rt);
					$rt = str_replace('{tm4}','<span class=\"date '.$forYou.'\">'.date('d.m.Y H:i',$d['time2']).'</span>',$rt);
					
					$k01 = 1;
					$zb1 = array(1=>0,2=>0,3=>0,4=>0,5=>0);
					$zb2 = array(1=>0,2=>0,3=>0,4=>0,5=>0);
					
					if($d['bl2']>0)
					{
						$b11 = 1;
						$b12 = $d['bl1'];
						while($b11<=$d['zb1'])
						{
							$zb1[$b12] = 1;
							if($b12>=5 || $b12<0)
							{
								$b12 = 0;
							}
							$b12++;
							$b11++;
						}
					}
					
					if($d['bl2']>0)
					{
						$b11 = 1;
						$b12 = $d['bl2'];
						while($b11<=$d['zb2'])
						{
							$zb2[$b12] = 1;
							if($b12>=5 || $b12<0)
							{
								$b12 = 0;
							}
							$b12++;
							$b11++;
						}
					}
					
					
					while($k01<=5)
					{
						$zns01 = ''; $zns02 = '';
						$j01 = 1;
						while($j01<=5)
						{
							$zab1 = '0'; $zab2 = '0';
							if($j01==$k01)
							{
								$zab1 = '1';
								$zab2 = '1';
							}
					
							$zab1 .= $zb1[$j01];
							$zab2 .= $zb2[$j01];
								
							$zns01 .= '<img src=\"http://img.xcombats.com/i/zones/'.$d['t1'].'/'.$d['t2'].''.$zab1.'.gif\">';
							$zns02 .= '<img src=\"http://img.xcombats.com/i/zones/'.$d['t2'].'/'.$d['t1'].''.$zab2.'.gif\">';
							$j01++;
						}
						$rt = str_replace('{zn1_'.$k01.'}',$zns01,$rt);
						$rt = str_replace('{zn2_'.$k01.'}',$zns02,$rt);
						$k01++;
					}

					$j = 1;
					while($j<=17)
					{
						//замена R - игрок 1
						$r = $log_text[$d['s1']][$j];
						$k = 0;
						while($k<=count($r))
						{
							if(isset($log_text[$d['s1']][$j][$k]))
							{
								$rt = str_replace('{1x'.$j.'x'.$k.'}',$log_text[$d['s1']][$j][$k],$rt);
							}
							$k++;
						}
						//замена R - игрок 2
						$r = $log_text[$d['s2']][$j];
						$k = 0;
						while($k<=count($r))
						{
							if(isset($log_text[$d['s2']][$j][$k]))
							{
								$rt = str_replace('{2x'.$j.'x'.$k.'}',$log_text[$d['s2']][$j][$k],$rt);
							}
							$k++;
						}						
						$j++;
					}
					
					//закончили заменять
					$pl['text'] = $rt;					
				}
				if($pll < $pl['id']) {
					$pll = $pl['id'];
				}
				$js .= 'add_log('.$pl['id'].','.$forYou2.',"'.$pl['text'].'",'.$pl['id_hod'].',0,0);';
			}
			$js .= 'id_log='.$pll.';';
			return $js;
		}
		
	//Добавляем в лог
		public function add_log($mass)
		{
			if($mass['time']!='' && $mass['text'] != '')
			{
				mysql_query('LOCK TABLES battle_logs WRITE');
				
				$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$mass['text'].'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
				
				mysql_query('UNLOCK TABLES');
			}
		}
		
	///Комментатор
	public function get_comment(){
		$boycom = array ('А танцуешь ты лучше.','А мы что, в прятки тут играем?','А вы разве пингвинов никогда не видели?','А, ведь когда-то, вы были красивыми… А теперь? Ну и рожи! Жуть!','А потом еще труп пинать будут.','А я вчера ночью за соседями подглядывал. Они точно так же кувыркались','А ведь вы живых людей дубасите...','А вот я вчера в зоопарке был...','А вы в стройбате не служили?','А вы видели, чтобы так на улице делали!?','А вы знали что ёжики размножаются в интернете?','А жить-то, как хочется:','А из-за чего вы собственно дерётесь?','А чего ржёте, вы ещё остальных не видели','А что произойдёт если ты испугаешся до полусмерти дважды?!','Больше так не делай. Ты же не садист?','Без комментариев...','Больно ведь!','Быстро ты за монитор спрятался!','Все хотят попасть в рай, но никто не хочет умирать!','Вчера с такой девчонкой познакомился.','Всего 5 минут знакомы, а дерутся, словно супруги с 20-ти летним стажем...','Все. Я так больше не могу.','В конце концов, кто-то победит?','Вы чего, с дерева упали?','Возятся как сонные мухи... давайте я вам лучше анекдот расскажу: ...','Вот видишь, как полезно чистить зубы на ночь?','Вот вы все руками махаете, а за вами уже очередь','Вот попадёте вы в плен и вас там будут долго бить. Но вы ничего не расскажете... и не потому, что вы такой стойкий, просто вы ничего не знаете','Вы бы лучше пошли потренировались!','Вы все еще разминаетесь? Позовите, когда кости в муку друг другу разминать будете.','Вы же бойцы! Имейте совесть!','Гаси недоумка!','Да, если бы я смог это остановить, то получил бы нобелевскую премию `За мир` ','Да куда они бьют?!','Давайте быстрее! За вами уже очередь образовалась.','Давайте обойдемся сегодня таймаутом. А? А то мне уже кошмары скоро будут сниться.','Дерутся как девчонки!','Дети, посмотрите налево... Ой!.. Нет, туда лучше не смотреть.','Если так будет продолжаться, то скоро мы заснем!','Если бы у меня было кресло-качалка, я бы в нём качался...','Если вы что-то сказать хотите, то лучше молчите :)','Жестокость не порок.','Жизнь вне нашего клуба - это пустая трата кислорода!!!','Жми! Дави! Кусай! Царапай!','За такие бои надо в хаос отправлять!','Знаете откуда в комиссионном магазине столько вещей? Это я после ваших гулянок собираю и сдаю туда. Иногда вместе с частями тела, застрявшими в них.','Здесь люди так близки друг к другу. Просто иначе ударить нельзя.','И пролитая кровь еще пульсирует...','Инвалидов развелось...','Какой бой!!!','Кто!? Кто здесь?!','Кто вас этому научил?','Кузнечик, блин...','Куплю импортный проигрыватель грампластинок.','Лошадью ходи!','Лучше враг, чем друг - враг.','Ладно, вы тут пока друг друга за волосы таскайте, а я пойду, пообедаю.','Мне ваш балет уже надоел!','Может, начнется-таки настоящий бой???','Мысли лезут в голову изнутри, а удары снаружи.','Ну и где ваши коронные удары? Где живописные падения я спрашиваю!','Ну, нельзя же так наотмашь лупить!','Надо раньше было думать, теперь смертельно поздно...','На такое зрелище билеты продавать можно. Народ ухохочется!','Нет! Не надо драки! А... ладно деритесь, все равно не умеете.','Нет, ну должен быть повод, должен же быть повод?','Нет, я отказываюсь это комментировать!','Не таких обламывали!','Ну выпили вы рюмку, ну две... ну литр, ну два... так зачем же после этого драку затевать?!','Ну и кто за этот погром платить будет?','Ну и оскал у вас. Из вашей улыбки кастеты делать можно.','Ну, что же ты..? Не печалься. Выше голову, так по ней удобней попасть.','Ничего... Блок тоже удар.','Обернись!!!.... Поздно...','Ого! Научите меня так не делать.','Осторожно! Сделаешь дырочку, уже не запломбируешь!','Оно вам надо???','Обычное дело...там что-то отклеилось.','Ой, и заболтался я с вами...','Он же не промахнётся если ты не отойдёшь!','По-моему, кому-то светит инвалидность.','Подкинь ему грабли, на которые он еще не наступал.','Прав был кот Леопольд, давайте жить дружно?','При ударе в живот нарушается кислотно-щелочной баланс.','Проверь, не торчит ли у тебя нож из живота.','Перестаньте мне орать!','Подкинь ему грабли, на которые он еще не наступал.','Прыгают тут как блохи... Все, я пошел за дихлофосом!','Разбудите меня когда эта порнография закончится...','Ребенок сильнее ударил бы!','Славно вмазал!','Славно они веселятся','Смотрю вот на вас, и слезы наворачиваются.','Сначала учатся ходить, а потом только в драку лезут.','Так они друг другу что-нибудь сломают.','Так ты ему все кости переломаешь!','У меня в подъезде точно так же соседа отмудохали','Убогих развелось...','Ух ты, какой прыткий!','Фашист!! Надо ж, так по больному месту врезать...','Хватит бить его об угол моей кабинки! Мне же потом ее чинить.','Хулиганы, прекратите немедленно!','Хочешь, подскажу, куда он ударит?','Хорошо, что у меня ловкости больше чем у вас всех, а то б вы и меня в инвалидную коляску посадили бы.','Хороший бой!','Хороший удар!','Хиляк-разрядник!','Что ты его за волосы схватил?! Отпусти немедленно!','Щас я вас настигну, вот тогда мы и похохочем','Это была какая-то неизвестная мне техника...','Это же противник, а не глина! Хватит мяться!','Это не бой, это издевательское избиение.','Это поубавит спеси','Это и был твой план `Б` ?','Это была какая-то неизвестная мне техника...','Я же предупреждал, - будет больно.','Я не страдаю безумием. Я наслаждаюсь им каждую минуту :)','Я красивый, я сильный, я умный, я добрый. А вот вы? Вы себя-то видели?!','Я тоже умею драться, но не буду...','(тревожно озираясь) я вам по секрету скажу... за вами наблюдают!','<вырезано цензурой> после боя я этих <вырезано цензурой> обоих в <вырезано цензурой> и <вырезано цензурой>','<вырезано цензурой> каратисты фиговы');
		$act_com = array();
		if(rand(1,6) == rand(1,6))
		{
			$txt = '{tm1} <i>Комментатор: '.$boycom[rand(0,count($boycom)-1)].'</i>';
									
			$vLog = 'time1='.time().'';									
			$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');									
			$mas1['text'] = $txt;
			$this->add_log($mas1);
			
		} else {
			return false;
		}
	}
	
	//Расчет типа удара от оружия
		public function weaponTx($item)
		{
			$tp = 0;
			if(isset($item['id']))
			{
				$itm = $this->lookStats($item['data']);
				//рубящий урон
				$t2 = round(0+$itm['tya2'],2);
				//колящий урон
				$t1 = round(0+$itm['tya1'],2);
				//дробящий урон
				$t3 = round(0+$itm['tya3'],2);
				//режущий урон
				$t4 = round(0+$itm['tya4'],2);
				//урон огня
				$t5 = round(0+$itm['tym1'],2);
				//урон воды
				$t7 = round(0+$itm['tym3'],2);
				//урон земли
				$t8 = round(0+$itm['tym4'],2);
				//урон воздуха
				$t6 = round(0+$itm['tym2'],2);
				//урон света
				$t9 = round(0+$itm['tym5'],2);
				//урон тьмы
				$t10 = round(0+$itm['tym6'],2);
				//урон серой магией
				$t11 = round(0+$itm['tym7'],2);
				
				$i = 0;
				if($t1+$t2+$t3+$t4+$t5+$t6+$t7+$t8+$t9+$t10+$t11 > 0)
				{
					$z = rand(0,100);
					$i = 1; $j = 1;
					$gmax = 0;
					$gmx  = 0;
					$inr = array(); //зоны которые участвуют в "конкурсе"
					while($i<=11)
					{
						if(${'t'.$i}>0)
						{
							if(${'t'.$i}>$gmx)
							{
								$gmx = ${'t'.$i};
								$gmax = $i;
							}elseif(${'t'.$i}==$gmx)
							{
								if(rand(0,1)==1)
								{
									$gmax = $i;
								}
							}
							$inr[$j] = $i;
							$j++; 
						}
						$i++;
					}
					
					//Мешаем элементы массива
					shuffle($inr);
					
					//выводим случайный тип урона
					$i = 1; $lst = 0;
					while($i<=$j)
					{
						$v = ${'t'.$inr[$i]};
						if($v>0)
						{
							$v += $lst;
							if($v>$z)
							{
								$tp = $inr[$i];
								$i = $j+1;
							}else{
								$lst = $v;
							}
						}
						$i++;
					}
					
					if($gmx>30)
					{
						if(rand(0,2)==1)
						{
							$tp = $gmax;
						}
					}
					
					if($tp==0)
					{
						$tp = $gmax;
					}
				}				
			}else{
				$tp = 12;
			}	
			return $tp;
		}
		
		
	//Расчет урона от оружия
		public function weaponAt($item,$st,$x)
		{
			$tp = 0;
			$tp20 = 0;
			if(isset($item['id']))
			{
				$itm = $this->lookStats($item['data']);				
				//начинаем расчет урона
				$min = $itm['sv_yron_min']+$itm['yron_min']+$st['minAtack'];
				$max = $itm['sv_yron_max']+$itm['yron_max']+$st['maxAtack'];
				if($x!=0)
				{
					/*
					Колющий - 60% Силы и 40% Ловкости. 
					Рубящий - 70% Силы 20% Ловкости и 20% Интуиции. 
					Дробящий - 100% Силы. 
					Режущий - 60% Силы и 40% Интуиции.
					*/
					//Тип урона: 0 - нет урона, 1 - колющий, 2 - рубящий, 3 - дробящий, 4 - режущий, 5 - огонь, 6 - воздух, 7 - вода, 8 - земля, 9 - свет, 10 - тьма, 11 - серая
					if($x==1)
					{
						//колющий
						$wst = $st['s1']*0.35+$st['s2']*0.35;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 1;
					}elseif($x==2)
					{
						//рубящий
						$wst = $st['s1']*0.45+$st['s2']*0.12+$st['s3']*0.13;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 2;
					}elseif($x==3)
					{
						//дробящий
						$wst = $st['s1']*0.65;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 3;
					}elseif($x==4)
					{
						//режущий
						$wst = $st['s1']*0.45+$st['s3']*0.25;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 4;
					}elseif($x>=5 && $x<=22)
					{
						//урон магии и магии стихий
						$wst = $st['s1']*0.01+$st['s2']*0.01+$st['s3']*0.01+$st['s5']*0.06;
						$min += 3+(ceil($wst*1.4)/2.25)+$st['minAtack'];
						$max += 5+(ceil(0.4+$min/0.9)/2.25)+$st['maxAtack'];
						$tp20 = 5;
					}else{
						//без профильного урона
						
					}
					
					$wst = ($st['s1']*0.02+$st['s2']*0.02+$st['s3']*0.05);
					$min1 = -2+ceil($wst*1.4)/1.25;
					$max2 = 4+ceil(0.4+$min1/0.9)/1.25;	
					
					$min =	round(($min+$min1));	
					$max =	round(($max+$max1));		
				}
				$tp = rand(($min+$max)/3.5,(($min+$max)/3.5 + (($min+$max)/3.5)/100*7));
			}
			return $tp;
		}
		
	//Расчет урона от оружия
		public function weaponAt22($item,$st,$x)
		{
			$tp = 0;
			$tp20 = 0;
			if(isset($item['id']))
			{
				$itm = $this->lookStats($item['data']);				
				//начинаем расчет урона
				$min = $itm['sv_yron_min']+$itm['yron_min']+$st['minAtack'];
				$max = $itm['sv_yron_max']+$itm['yron_max']+$st['maxAtack'];
			}
			return array($min,$max);
		}

		public function domino($itm) {
			$r = 0;
			//0 - inOdet , 1 - class , 2 - class-point , 3 - anti_class , 4 - antic_lass-point	 , 5 - level , 6 level_u
			//15 предметов
			$clss = array(
				1   => 100, //шлем
				2   => 80,  //наручи
				3   => 150, //оружие
				14  => 100, //щит
				5   => 200, //броня
				7   => 50,  //пояс
				17  => 50,  //ботинки
				10  => 80,  //кольцо
				11  => 80,  //кольцо
				12  => 80,  //кольцо
				9   => 100, //амулет
				8   => 100, //серьги
				4   => 50,  //рубаха
				16  => 80,  //поножи
				6   => 50   //плащ
			);
			$r += $clss[$itm[0]];
			if($itm[10] > 0) {
				//екр.предмет
				if($itm[10] < 500) {
					//не артефакт
					$r += $clss[$itm[0]]*4;
				}else{
					//артефакт
					$r += $clss[$itm[0]]*8;
				}
			}
			return $r;
		}
		
		public function adomino($itm) {
			$r = 0;
			//0 - inOdet , 1 - class , 2 - class-point , 3 - anti_class , 4 - antic_lass-point	 , 5 - level , 6 level_u
			//15 предметов
			$clss = array(
				1   => 80, //шлем
				2   => 60,  //наручи
				3   => 130, //оружие
				14  => 80, //щит
				5   => 180, //броня
				7   => 30,  //пояс
				17  => 30,  //ботинки
				10  => 50,  //кольцо
				11  => 50,  //кольцо
				12  => 50,  //кольцо
				9   => 80, //амулет
				8   => 80, //серьги
				4   => 30,  //рубаха
				16  => 50,  //поножи
				6   => 30   //плащ
			);
			$r += $clss[$itm[0]];
			return $r;
		}
		
		public function domino_lvl($r,$lvl,$lvl_itm) {
			if($lvl < $lvl_itm) {
				$r = $r*((50-$lvl+$lvl_itm)/100);
				//расчет урона, если есть добавочные бонусы на подобии екр.вещей \ артефактов, либо легендарных предметов
				$r = ceil($r);
			}
			return $r;
		}
		/*
		public $bal = array(
			//Расчет шанса победы X - Y
			// танк , уворот , крит , силовик , универсал , маг
			'Танк'   	=> array(0,50,90,00,90,50,50), // танк
			'Уворот' 	=> array(0,00,50,90,00,50,70), // уворот
			'Крит'   	=> array(0,90,00,50,90,30,50), // крит
			'Силовик'   => array(0,00,90,00,50,50,50), // силовик
			'Универсал' => array(0,50,30,90,00,50,70), // универсал
			'Маг' 		=> array(0,90,30,00,90,50,50)  // маг
		);
		*/
		
		/*
		public function domino_all($v1,$v2,$d1,$d2) {
			// Мощность класса 1 , Мощность класса 2 , Анти 1 , Анти 2
			//Расчет бонусов
			$mx = 0;
			$cs = array(NULL,'Танк','Уворот','Крит','Силовик','Универсал','Маг');
			$r = array(
				0 => 0,
				'Крит'=>array(),
				'Танк'=>array(),
				'Уворот'=>array(),
				'Универсал'=>array(),
				'Силовик'=>array(),
				'Маг'=>array()
			);
			$i = 0;
			while($i <= 7) {
				if(isset($v1[$i]) || isset($v2[$i])) {
					$r[$cs[$i]] = round(((1+($v1[$i]*1.3)-$v2[$i]+$d1[$i]+$d2[$i])/1300),2);
					if($v1[$i] > $mx) {
						$mx = $v1[$i];
						$r[0] = $cs[$i];
						$r[1] = $i;
					}
				}
				$i++;
			}
			return $r;
		}*/
 
		public function yronLvl($lvl1,$lvl2) {
			$r = array(
				1  => array(0,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				2  => array(0,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				3  => array(0,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				4  => array(0,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				5  => array(0,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200),
				6  => array(0,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200),
				7  => array(0,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200),
				8  => array(0,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200),
				9  => array(0,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200),
				10 => array(0,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200),
				11 => array(0,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200),
				12 => array(0,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600),
				13 => array(0,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000),
				14 => array(0,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400),
				15 => array(0,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800),
				16 => array(0,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200),
				17 => array(0,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600),
				18 => array(0,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000),
				19 => array(0,7400,7200,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400),
				20 => array(0,7800,7600,7400,7200,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800),
				21 => array(0,8200,8000,7800,7600,7400,7200,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200)
			);
			$r = floor($r[$lvl1][$lvl2]/2);
			return $r;
		}
		
	//Расчет защиты
		public function zago($v) {
			$r = (1-( pow(0.45, ($v/1000) ) ))*100;	
			return $r;
		}
	//Расчет защиты (магия)
		public function zmgo($v) {

			$r = 0;
			$r = (1-( pow(0.5, ($v/480) ) ))*100;		
			return $r;
		}
		
	/* Расчет урона */
		public function yrn($st1, $st2, $u1, $u2, $level, $level2, $type, $min_yron, $max_yron, $min_bron, $max_bron, $vladenie, $power_yron, $power_krit, $zashita, $ozashita, $proboi, $weapom_damage) {

			global $u;
			
			//Параметры для возврвата
			$r = array('min' => 0, 'max' => 0, 'type' => $type);
			$p = array(
				'Y'		=> 0,
				'B'		=> array(0 => 0, 1 => 0, 'rnd' => false),
				'L'		=> $level,
				'W'		=> array($min_yron, $max_yron, 'rnd' => false), //минимальный урон //максимальный урон добавочный
				'U'		=> $vladenie, //владение оружием
				'M'		=> $power_yron, //мощность урона
				'K'		=> $power_krit, //мощность крита
				'S'		=> 0,  //влияние статов на проф.урон
				'T'		=> 1   //Кф. оружия
				/*
					(S) - влияние наших статов на профильный урон
					Колющий: S = Сила * 0,6 + Ловкость * 0,4
					Рубящий: S = Сила * 0,7 + Ловкость * 0,2 + Интуиция * 0,2
					Дробящий: S = Сила * 1
					Режущий: S = Сила * 0,6 + Интуиция * 0,4
				*/
			);
			
			//Умножитель 1.33 для двуручки и 1.00 для одной руки
			if ($weapom_damage == 0) { $p['T'] = 1; }elseif($weapom_damage == 1) {$p['T'] = 1.33;}
			
			//Расчет типа урона			
				//колющий
						if($r['type'] == 1) {		$p['S'] = $st1['s1'] * 0.5 + $st1['s2'] * 0.3; 
													$p['U'] = $st1['a1']; //кинжалы
				//рубящий
					}elseif($r['type'] == 2) {		$p['S'] = $st1['s1'] * 0.5 + $st1['s2'] * 0.15 + $st1['s3'] * 0.15;
													$p['U'] = $st1['a2']; //топоры
				//дробящий
					}elseif($r['type'] == 3) {		$p['S'] = $st1['s1'] * 1.2;
													$p['U'] = $st1['a3']; //дубины
				//режущий
					}elseif($r['type'] == 4) {		$p['S'] = $st1['s1'] * 0.5 + $st1['s3'] * 0.4;
													$p['U'] = $st1['a4']; //мечи
				//Магиечески
					}elseif($r['type'] >= 5){		$p['S'] = $st1['s1'] * 0.3 + $st1['s2'] * 0.3;	
													$p['U'] = $st1['mg'.($r['type']-4)]; //магией					
					}else {
													$p['S'] = ($st1['s1']*0.5); $p['U']=0; // для кулака(нужно переписывать 
					}
					
					//$p['S'] = $p['S'];
			
			//Выставление параметров		
				$r['bron'] = array($min_bron, $max_bron); //Броня зоны куда бьем
				$r['bron']['rnd'] = rand($r['bron'][0],$r['bron'][1]);
				
				$r['za'] = $zashita; //Защита от урона
				$r['oza'] = $ozashita; //Особенность Защиты от урона
				
				
			//Остальные расчеты	
				/*if($p['S'] > 0) {
					$p['B'][0] = round((ceil($p['S']*1.4)/1.25)+2);
				}else{
					$p['B'][0] = round((ceil($st1['s1']*1.4)/1.25)+2);
				}
				$p['B'][1] = round(5+ceil(0.4+($p['B'][0]-0)/0.9)/1.25);			
				*/
				$p['B'][0] = 5;
				$p['B'][1] = 9;
				$p['B']['rnd'] = rand($p['B'][0],$p['B'][1]);
				
				$p['W']['rnd'] = rand($p['W'][0],$p['W'][1]);
				 
			//Обычный урон
				$r['min'] = (($p['B'][0]+$p['L']+$p['S']+$p['W'][0])*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				$r['max'] = (($p['B'][1]+$p['L']+$p['S']+$p['W'][1])*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				 

			//Критический урон
				$r['Kmin'] = ($p['B'][0]+$p['L']+$p['S']+$p['W'][0]*(1+0.07*$p['U']))*(1+$p['M']/100)*1.6*(1+$p['K']/100);
				$r['Kmax'] = ($p['B'][1]+$p['L']+$p['S']+$p['W'][1]*(1+0.07*$p['U']))*(1+$p['M']/100)*1.6*(1+$p['K']/100);
					
								
				$r['min'] = floor($r['min']);
				$r['max'] = floor($r['max']);
				$r['Kmin'] = floor($r['Kmin']);
				$r['Kmax'] = floor($r['Kmax']);
					
					
			//Минимальное значение урона
				$r['min_'] = floor($r['min']*0.1);
				$r['max_'] = floor($r['max']*0.1);
				$r['Kmin_'] = floor($r['Kmin']*0.1);
				$r['Kmax_'] = floor($r['Kmax']*0.1);
			
											
			//Расчет брони
				//для обычного
				if( $r['type'] < 5) {
					$r['min_abron'] = round($r['min']*0.15);
					$r['max_abron'] = round($r['max']*0.15);
					
					if($proboi != 0) {
						$r['bron']['rnd'] = floor($r['bron']['rnd']/100*(100-$st1['m9']));
					}
					
					$r['min'] -= $r['bron']['rnd'];
					$r['max'] -= $r['bron']['rnd'];
					if($r['min'] < $r['min_abron']) {
						$r['min'] = $r['min_abron'];
					}
					if($r['max'] < $r['max_abron']) {
						$r['max'] = $r['max_abron'];
					}
					
					
					//для крита
					$r['Kmin_abron'] = round($r['Kmin']/3);
					$r['Kmax_abron'] = round($r['Kmax']/3);
					$r['Kmin'] -= $r['bron']['rnd'];
					$r['Kmax'] -= $r['bron']['rnd'];
					if($r['Kmin'] < $r['Kmin_abron']) {
						$r['Kmin'] = $r['Kmin_abron'];
					}
					if($r['Kmax'] < $r['Kmax_abron']) {
						$r['Kmax'] = $r['Kmax_abron'];
					}
				}
				
			//Особенности защиты				
				$r['ozash_rnd'] = $r['oza'][$r['type']][1]; /*rand($r['oza'][$r['type']][0],$r['oza'][$r['type']][1]);*/
				
				if($r['ozash_rnd'] > 80) { $r['ozash_rnd'] = 80; }
				if($r['ozash_rnd'] < 0) { $r['ozash_rnd'] = 0; }
				

				$r['ozash_rnd'] = 100-$r['ozash_rnd'];				
				
				$r['min'] -= ($r['min']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				$r['max'] -= ($r['max']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				
				$r['Kmin'] -= ($r['Kmin']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				$r['Kmax'] -= ($r['Kmax']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				
			//Расчет защиты (не более 80%)
				 
				$r['min'] -= floor($r['min']/80*$this->zago($r['za']-45));
				$r['max'] -= floor($r['max']/80*$this->zago($r['za']-45));
				$r['Kmin'] -= floor($r['Kmin']/80*$this->zago($r['za']-45));
				$r['Kmax'] -= floor($r['Kmax']/80*$this->zago($r['za']-45));
			 	 
				if($r['min'] < $r['min_']) { $r['min'] = $r['min_']; }
				if($r['max'] < $r['max_']) { $r['max'] = $r['max_']; }
				if($r['Kmin'] < $r['Kmin_']) { $r['Kmin'] = $r['Kmin_']; }
				if($r['Kmax'] < $r['Kmax_']) { $r['Kmax'] = $r['Kmax_']; }
					
				if( $r['type'] >= 5 ) {
					$r['min'] = 1+floor($r['min']/2);
					$r['max'] = 1+floor($r['max']/2);
					$r['Kmin'] = 1+floor($r['Kmin']/2);
					$r['Kmax'] = 1+floor($r['Kmax']/2);
				}
						
				/*if($u->info['admin'] > 0) {
					echo " {<font style='color:blue'> - </font>}";
				}*/
					
			return $r;
		}
		
		public $pr_not_use = array(),$pr_reset = array(),$pr_yrn = false,$prnt = array();
	//Расчет урона и типа удара
		public function takeAtack($u1,$u2,$a,$iid = NULL,$recontr = false)
		{
			global $u;
					
			$uid1 = $a['uid'.$u1];
			$uid2 = $a['uid'.$u2];
	
	
			if(isset($this->users[$this->uids[$uid1]],$this->users[$this->uids[$uid2]]))
			{							
				$p1 = $this->users[$this->uids[$uid1]];
				$p2 = $this->users[$this->uids[$uid2]];
				$s1 = $this->stats[$this->uids[$uid1]];
				$s2 = $this->stats[$this->uids[$uid2]];
				
				
						//Добавляем модификаторы от предмета (свойства)
						//игрок 1
						$t = $this->items['add'];
						$ii = 0; $sv1 = array();
						while($ii<count($s1['items']))
						{
							$po = $u->lookStats($s1['items'][$ii]['data']);							
							$x = 0;
							while($x<count($t))
							{
								$n = $t[$x];
								if(isset($po['sv_'.$n]))
								{
									$sv1[$s1['items'][$ii]['inOdet']][$n] += $po['sv_'.$n];
									$s1[$n] += $po['sv_'.$n];
									$sv1['countd'][$s1['items'][$ii]['inOdet']][count($sv1['countd'])] = $n;
								}
								$x++;
							}
							
							if($s1['items'][$ii]['type'] == 22) {
								$s2['m4'] += 350;
							}
							
							$sv1['vls'][$s1['items'][$ii]['inOdet']] = array(
								$s1['items'][$ii]['id'],
								$s1['items'][$ii]['item_id'],
								$s1['items'][$ii]['name'],
								$s1['items'][$ii]['img'],
								$s1['items'][$ii]['inOdet'],
								$s1['items'][$ii]['type'],
								$s1['items'][$ii]['lvl_itm'],
								$s1['items'][$ii]['level'],
								$s1['items'][$ii]['lvl_exp'],
								$s1['items'][$ii]['lvl_aexp']
							);
							
							$ii++;
						}
						//игрок 2
						$ii = 0; $sv2 = array();
						while($ii<count($s2['items']))
						{
							$po = $u->lookStats($s2['items'][$ii]['data']);
							$x = 0;
							while($x<count($t))
							{
								$n = $t[$x];
								if(isset($po['sv_'.$n]))
								{
									$sv2[$s2['items'][$ii]['inOdet']][$n] += $po['sv_'.$n];
									//$s2[$n] += $po['sv_'.$n];
									$sv2['countd'][count($sv2['countd'])] = $n;
								}
								$x++;
							}
							
							if($s2['items'][$ii]['type'] == 22) {
								$s1['m4'] += 350;
							}
							
							$sv2['vls'][$s2['items'][$ii]['inOdet']] = array(
								$s2['items'][$ii]['id'],
								$s2['items'][$ii]['item_id'],
								$s2['items'][$ii]['name'],
								$s2['items'][$ii]['img'],
								$s2['items'][$ii]['inOdet'],
								$s2['items'][$ii]['type'],
								$s2['items'][$ii]['lvl_itm'],
								$s2['items'][$ii]['level'],
								$s2['items'][$ii]['lvl_exp'],
								$s2['items'][$ii]['lvl_aexp']
							);
							
							$ii++;
						}		 
				$i = 0;
			 
				
				/* делаем временный апгрейд */
				$re_st[1] = $this->stats[$this->uids[$uid1]];
				$re_st[2] = $this->stats[$this->uids[$uid2]];
				 
				
				if($s1['lvl']>0) {
					//$s1['za'] += floor($this->yronLvl($s1['lvl'],$s2['lvl']));
					$s1['za1'] += floor($this->yronLvl($s1['lvl'],$s2['lvl']));
					$s1['za2'] += floor($this->yronLvl($s1['lvl'],$s2['lvl']));
					$s1['za3'] += floor($this->yronLvl($s1['lvl'],$s2['lvl']));
					$s1['za4'] += floor($this->yronLvl($s1['lvl'],$s2['lvl']));
				}
				if($s2['lvl']>0) {
					//$s2['za'] += floor($this->yronLvl($s2['lvl'],$s1['lvl']));
					$s2['za1'] += floor($this->yronLvl($s2['lvl'],$s1['lvl']));
					$s2['za2'] += floor($this->yronLvl($s2['lvl'],$s1['lvl']));
					$s2['za3'] += floor($this->yronLvl($s2['lvl'],$s1['lvl']));
					$s2['za4'] += floor($this->yronLvl($s2['lvl'],$s1['lvl']));
				}
				
				$this->stats[$this->uids[$uid1]] = $s1;
				$this->stats[$this->uids[$uid2]] = $s2;
				
				/* расчет характеристик */
				
				
				$at = array(); //исходный вариант ударов: [номер удара][тип удара][урон]				
				$zb = array(1=>0,2=>0,3=>0,4=>0,5=>0); //какие зоны ударо заблокировал игрок которого бьют
				$i = 1;
				$j = $a['b'.$u2];
				
				//расчет урона игрока 1 по игроку 2
				$y1min = 1+(ceil($s1['s1']*1.4)/1.25)+$s1['minAtack'];
				$y1max = 3+(ceil(0.4+$y1min/0.9)/1.25)+$s1['maxAtack'];
								
				//урон кулаком
				$yrn = array();
				//Тип урона: 0 - нет урона, 1 - колющий, 2 - рубящий, 3 - дробящий, 4 - режущий, 5 - огонь, 6 - воздух, 7 - вода, 8 - земля, 9 - свет, 10 - тьма, 11 - серая
				
				$tx = array(); 
				$tz = array();
				$tc = array();
				$by = array();
				$yi = array();
				//$pd[игрок 1 или 2][action][i]
				//Массив с действиями приемов
				$pr = array();
				//Приемы которые не разгадываются
				$noraz = array(
					201 => 1,
					211 => 1
				);
				//получаем массив с приемами
				$j7 = 1; $pr7 = array(); $pd = array(); $jk7 = array();
				while($j7<=2)
				{
					$eff = $this->stats[$this->uids[${'uid'.$j7}]]['effects'];
					$i7 = 0;
					while($i7<count($eff))
					{
						$norzs = false;
						if( $j7 == 1 ) {
							if( $this->stats[$this->uids[$uid2]]['raztac'] == 1 ) {
								if(	!isset($noraz[$this->prnt[$eff[$i7]['id']]['eff']['v2']])) {
									$norzs = true;
								}							
							}
						}else{
							if( $this->stats[$this->uids[$uid1]]['raztac'] == 1 ) {
								if(	!isset($noraz[$this->prnt[$eff[$i7]['id']]['eff']['v2']])) {
									$norzs = true;
								}
							}
						}
						if(isset($this->prnt[$eff[$i7]['id']]['eff'])) {
							$eff[$i7] = $this->prnt[$eff[$i7]['id']]['eff'];
						}
						if(isset($eff[$i7]) && $norzs == false)
						{
							if(!isset($pr7[$eff[$i7]['v2']]))
							{
								$pr7[$eff[$i7]['v2']] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i7]['v2'].'" LIMIT 1'));									
							}
							if(isset($pr7[$eff[$i7]['v2']]['id']))
							{
								//$pd[игрок 1 или 2][action][i]
								if(!isset($jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]))
								{
									$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7] = 0;
								}
								$pd[$j7][$pr7[$eff[$i7]['v2']]['action']][$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]] = $eff[$i7]; //eff_users
								$pd[$j7][$pr7[$eff[$i7]['v2']]['action']][$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]]['priem'] = $pr7[$eff[$i7]['v2']]; //priems
								//$pd[$j7][$pr7[$eff[$i7]['v2']]['action']][$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]]['pdate'] = $u->lookStats($pr7[$eff[$i7]['v2']]['date2']); //stats of priems
								$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]++;								
							}
						}
						$i7++;
					}
					unset($eff);
					$j7++;
				}
				
/* ПРИОРИТЕТНЫЕ ПРИЕМЫ ======================================================= */
					$g1 = 0; $rstb = 0;
					while($g1<=1)
					{
						$k2 = 1;
						while($k2<=2)
						{							
							if(count($pd[$k2][1])>0)
							{								
								$k = 0;
								while($k<count($pd[$k2][1]))
								{									
									if($pd[$k2][1][$k]['priem']['prior'] == 1) {
										$d2 = $u->lookStats($pd[$k2][1][$k]['priem']['date2']);
									}
									if(isset($pd[$k2][1][$k]['priem']['id']) && $pd[$k2][1][$k]['priem']['delete'] == 0 && $pd[$k2][1][$k]['priem']['prior'] == 1)
									{					
														//echo '[!'.$pd[$k2][1][$k]['priem']['name'].'-'.$pd[$k2][1][$k]['uid'].']';
														//добавляем в лог что прием завершился										
														
															if(isset($d2['del_af_use'])) {
																$del_af[count($del_af)] = array($k2,$k);
															}
																																											
															if(((isset($d2['del_use']) && $nodel==0) || isset($d2['rzEnd'])) && !isset($d2['del'.$k2.'ef']))
															{	
																$d2['del_use']--;
																if($d2['del_use']==0)
																{
																	if($k2==1)
																	{
																		$k2jn = 2;
																	}else{
																		$k2jn = 1;
																	}
																	if($pd[$k2][1][$k]['priem']['delete'] == 0) {
																		$pd[$k2][1][$k]['priem']['delete'] = time();
																		$this->delPriem($pd[$k2][1][$k],${'p'.$k2},1,${'p'.$k2jn});
																	}
																}else{
																	$pd[$k2][1][$k]['priem']['date2'] = $u->impStats($d2);
																}
																if(isset($d2['btl_st_ref'])) {
																	$rstb++;
																}
															}
															
															if(isset($d2['die'.$k2])) {
																//Убиваем игрока
																if(${'s'.$k2}['hpNow'] > 0) {
																	${'s'.$k2}['hpNow'] = 0;
																	${'p'.$k2}['hpNow'] = 0;																	
																	$this->users[$this->uids[${'p'.$k2}['id']]]['hpNow'] = 0;																	
																	$this->stats[$this->uids[${'p'.$k2}['id']]]['hpNow'] = 0;
																	$re_st[$k2]['hpNow'] = 0;
																	$re_st[$k2]['tactic7'] = 0;
																	$this->stats[$this->uids[${'p'.$k2}['id']]]['tactic7'] = 0;
																	if(${'p'.$k2}['id'] == $u->info['id']) {
																		$u->stats['hpNow'] = 0;
																	}
																	mysql_query('UPDATE `stats` SET `hpNow` = "0",`tactic7` = "0" WHERE `id` = "'.${'s'.$k2}['id'].'" LIMIT 1');
																}
															}
															
															$this->prnt[$pd[$k2][1][$k]['id']] = array(
																'eff' => $pd[$k2][1][$k]
															);																													
															//$at[$i]['minf'] = str_replace(', '.$pd[$k2][1][$k]['priem']['name'],'',$at[$i]['minf']);
															//$at[$i]['minf'] .= ', '.$pd[$k2][1][$k]['priem']['name'];
									}
									$k++;
								}
							}
							$k2++;
						}					
						$g1++;
					}			
				
				if($this->re_pd['restart'] == true) {
					//получаем массив с приемами
					$j7 = 1; $pr7 = array(); $pd = array(); $jk7 = array();
					while($j7<=2)
					{
						$eff = $this->stats[$this->uids[${'uid'.$j7}]]['effects'];
						$i7 = 0;
						while($i7<count($eff))
						{
							if(isset($this->prnt[$eff[$i7]['id']]['eff'])) {
								$eff[$i7] = $this->prnt[$eff[$i7]['id']]['eff'];
							}
							if(isset($eff[$i7]))
							{
								if(!isset($pr7[$eff[$i7]['v2']]))
								{
									$pr7[$eff[$i7]['v2']] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i7]['v2'].'" LIMIT 1'));									
								}
								if(isset($pr7[$eff[$i7]['v2']]['id']))
								{
									//$pd[игрок 1 или 2][action][i]
									if(!isset($jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]))
									{
										$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7] = 0;
									}
									$pd[$j7][$pr7[$eff[$i7]['v2']]['action']][$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]] = $eff[$i7]; //eff_users
									$pd[$j7][$pr7[$eff[$i7]['v2']]['action']][$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]]['priem'] = $pr7[$eff[$i7]['v2']]; //priems
									//$pd[$j7][$pr7[$eff[$i7]['v2']]['action']][$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]]['pdate'] = $u->lookStats($pr7[$eff[$i7]['v2']]['date2']); //stats of priems
									$jk7[$pr7[$eff[$i7]['v2']]['action']][$j7]++;								
								}
							}
							$i7++;
						}
						unset($eff);
						$j7++;
					}
				}
				
							
								
					
				if($s1['min_zona']>0 && $s1['zona']<$s1['min_zona'])
				{
					$s1['zona'] = $s1['min_zona'];
				}
				

				if($s2['min_zonb']>0 && $s2['zonb']<$s2['min_zonb'])
				{
					$s2['zonb'] = $s2['min_zonb'];
				}
					
				//Для контр-удара
				$ik = $s1['zona'];
				$kii = array();
									
				$i = 1;
				$i2 = 0;
				$hst1 = $s1;
				$hst2 = $s2;
				while($i <= $ik)
				{
					$s1 = $hst1;
					$s2 = $hst2;
					
					$na = $a['a'.$u1][$i-1]; //зона удара		
										
					//Считаем урон с учетом СВОЙСТВ зоны удара
					if($na==1)
					{
						//проверяем шлем,ожерелье,серьги,венок						
						$sv2['ciadd'] = array(1,9,52,8,9,10,11,12);									
					}elseif($na==2 || $na==3)
					{
						//проверяем наручи,броня,рубаха,плащ
						$sv2['ciadd'] = array(2,5,4,6,8,9,10,11,12);
					}elseif($na==4)
					{
						//проверяем перчатки,пояс,кольца
						$sv2['ciadd'] = array(13,7,8,9,10,11,12);
					}elseif($na==5)
					{
						//проверяем поножи,ботинки
						$sv2['ciadd'] = array(16,17,8,9,10,11,12);
					}	
					
					/*
					$sv1['vls'][$s1['items'][$ii]['inOdet']][count($sv1['countd'])] = array(
					
					0 $s1['items'][$ii]['id'],
					1 $s1['items'][$ii]['item_id'],
					2 $s1['items'][$ii]['name'],
					3 $s1['items'][$ii]['img'],
					4 $s1['items'][$ii]['inOdet'],
					5 $s1['items'][$ii]['type'],
					6 $s1['items'][$ii]['lvl_itm'],
					7 $s1['items'][$ii]['level'],
					8 $s1['items'][$ii]['lvl_exp'],
					9 $s1['items'][$ii]['lvl_aexp']
					
					);
					*/
					
					$j2 = 0;
					while($j2 < count($sv2['ciadd'])) {
						$k3 = $sv2['ciadd'][$j2]; //айди предмета
						$j3 = 0;
						while($j3 < count($sv2['countd'][$k3])) {
							$k4 = $sv1['countd'][$j3];
							$s1[$k4] += $sv1[$j3][$k4];
							$j3++;
						}
						$j2++;
					}					
									/*
									$sv1[$s1['items'][$ii]['inOdet']][$n] += $po['sv_'.$n];
									//$s1[$n] += $po['sv_'.$n];
									$sv1['countd'][$s1['items'][$ii]['inOdet']][count($sv1['countd'])] = $n;
									*/
					
					$i2 = $i;
					
					if($i==1 && $s1['weapon1']!=1 && $s1['weapon2']==1)
					{
						$i2++;
					}
					
					if($i>2) {
						$i2 = 1;
						if($s1['weapon1']!=1 && $s1['weapon2']==1)
						{
							$i2 = 2;
						}
					}
										
					$tc[$i] = 12;					
					//урон кулаком
					$yrn[$i] = rand($y1min,$y1max);
					$yrt = array(0=>$y1min,1=>$y1max,3=>$yrn[$i],4=>0);
					$yrt[4] = 1+$yrt[1]/$yrt[3];
					//тип урона
					$tx[$i] = 0;
					//чем бьем
					$tz[$i] = 0;
					//Бонус умений	
					$by[$i] = 0;
					//если есть оружие в правой руке 
					if($i2==1 && $s1['weapon1']==1)
					{
						//считаем тип удара и урон от оружия
						$tx[$i]  = $this->weaponTx($s1['items'][$s1['wp3id']]);
						$tc[$i] = $tx[$i]-1;
						$yrn[$i] = $this->weaponAt($s1['items'][$s1['wp3id']],$s1,$tx[$i]);
						$yi[$i] = $this->weaponAt22($s1['items'][$s1['wp3id']],$s1,$tx[$i]);
						if($i > $s1['zona']) {
							//echo '['.$tx[$i].' , '.$tc[$i].' , '.$yrn[$i].']';
						}					
						if($s1['items'][$s1['wp3id']]['type']==21) //меч
						{
							$tz[$i] = 4;
							$by[$i] = $s1['aall']+$s1['a4'];						
						}elseif($s1['items'][$s1['wp3id']]['type']==22) //посох
						{
							$tz[$i] = 5;	
							$by[$i] = $s1['aall']+$s1['a5'];						
						}elseif($s1['items'][$s1['wp3id']]['type']==20 || $s1['items'][$s1['wp3id']]['type']==28) //дубина
						{
							$tz[$i] = 2;
							$by[$i] = $s1['aall']+$s1['a3'];							
						}elseif($s1['items'][$s1['wp3id']]['type']==19) //топор
						{
							$tz[$i] = 3;
							$by[$i] = $s1['aall']+$s1['a2'];							
						}elseif($s1['items'][$s1['wp3id']]['type']==18) //кинжал
						{
							$tz[$i] = 1;
							$by[$i] = $s1['aall']+$s1['a1'];							
						}elseif($s1['items'][$s1['wp3id']]['type']==26) //костыль
						{
							$tz[$i] = 22;							
						}
					}
					
					//если есть оружие в левой руке 
					if($i2==2 && $s1['weapon2']==1)
					{
						//считаем тип удара и урон от оружия
						$tx[$i]  = $this->weaponTx($s1['items'][$s1['wp14id']]);
						$tc[$i] = $tx[$i]-1;
						$yrn[$i] = $this->weaponAt($s1['items'][$s1['wp14id']],$s1,$tx[$i]);
						$yi[$i] = $this->weaponAt22($s1['items'][$s1['wp14id']],$s1,$tx[$i]);
						if($s1['items'][$s1['wp14id']]['type']==21) //меч
						{
							$tz[$i] = 4;
							$by[$i] = $s1['aall']+$s1['a4'];							
						}elseif($s1['items'][$s1['wp14id']]['type']==22) //посох
						{
							$tz[$i] = 5;	
							$by[$i] = $s1['aall']+$s1['a5'];						
						}elseif($s1['items'][$s1['wp14id']]['type']==20 || $s1['items'][$s1['wp14id']]['type']==28) //дубина
						{
							$tz[$i] = 2;		
							$by[$i] = $s1['aall']+$s1['a3'];					
						}elseif($s1['items'][$s1['wp14id']]['type']==19) //топор
						{
							$tz[$i] = 3;		
							$by[$i] = $s1['aall']+$s1['a2'];					
						}elseif($s1['items'][$s1['wp14id']]['type']==18) //кинжал
						{
							$tz[$i] = 1;
							$by[$i] = $s1['aall']+$s1['a1'];						
						}elseif($s1['items'][$s1['wp14id']]['type']==26) //костыль
						{
							$tz[$i] = 22;							
						}
					}
					
					
					if($tc[$i]<0)
					{
						$tc[$i] = 12;
					}
					
					//вы нанесли контрудар
					//if($this->mfs(2,array('amf'=>$s2['m5'],'mf'=>$s1['m4']+50,'absmf'=>$s1['m15']))==1 && ($s2['no_yv2']==0 || $s2['no_yv2']*100 < rand(1,10000)))
					//{
						if($recontr == false) {
							if($this->mfs(6,$s1['m6'])==1 && $this->contr[$iid][$p1['id']][$p2['id']] < $s2['zona'])
							{
								if(!isset($this->contr[$iid][$p1['id']][$p2['id']])) {
									$this->contr[$iid][$p1['id']][$p2['id']] = 0;
								}
								$this->contr[$iid][$p1['id']][$p2['id']]++;
								//echo '['.$ik.'<='.$s2['zona'].']';
								//контр-удар
								//$ik++;								
								//$a['a'.$u1][$ik-1] = rand(0,5);
								/*
								$tz[$ik] = $tz[$i];
								$tx[$ik] = $tx[$i];
								$tc[$ik] = $tc[$i];
								$yrn[$ik] = $yrn[$i];
								*/
								//$kii[$ik] = 1;
							}
						}
					//}
					
					$i++;
				}
					
				unset($sv1,$sv2);
											
				if($j>=1 && $j<=5)
				{
					$i = 1;
					while($i<=$s2['zonb'])
					{
						$zb[$j] = 1;
						if($j==5)
						{
							$j = 1;
						}else{
							$j++;
						}		
						$i++;
					}
				}					

				$del_af = array();
				
				$i = 1;
				if($recontr == true) {
					$ik = 2;
					$a['a'.$u1][$i-1] = rand(1,5);
				}
				while($i<=$ik)
				{
					$na = $a['a'.$u1][$i-1]; //зона удара
					if($na<1 || $na>5){ $na = 1; }
					$at[$i] = array('v'=>$na,'t'=>0,'y'=>0,'z'=>0,'x'=>0,'y2'=>0,'ki'=>0,'minf'=>'');
					
					if($recontr == true) {
						$at[$i]['ki'] = 1;
					}
					
					$at[$i]['z'] = $tz[$i]; //чем бьем
					$at[$i]['x'] = $tx[$i]; //1 - колющий, 2 - рубящий, 3 - дробящий, 4 - режущий, 5 - огонь, 6 - воздух, 7 - вода, 8 - земля, 9 - свет, 10 - тьма, 11 - серая 
					$at[$i]['y2'] = $tc[$i];
					$at[$i]['w_bad'] = false; //слабое оружие
					
					//Счтиаем мощности урона
						$w3 = array();
						$w14 = array();
						if($s1['wp3id']>0)
						{
							$w3 = $this->lookStats($s1['items'][$s1['wp3id']]['data']);
						}
						if($s1['wp14id']>0)
						{
							$w14 = $this->lookStats($s1['items'][$s1['wp14id']]['data']);
						}
						
						if($s1['wp14id'] > 0 && $s1['wp3id'] > 0 && $s1['items'][$s1['wp3id']]['type'] != 13 && $s1['items'][$s1['wp14id']]['type'] != 13) {
							if($w3['tr_lvl']-3 >= $w14['tr_lvl']) {
								//второе оружие слишком слабое
								if($i > 1) {
									$at[$i]['w_bad'] = true;
								}
							}
							if($w14['tr_lvl']-3 >= $w3['tr_lvl']) {
								//первое оружие слишком слабое
								if($i == 1) {
									$at[$i]['w_bad'] = true;
								}
							}
						}
										
						$w3p = 0;
						$w14p = 0;
						$wAp = 0;
						
						if($i==1)
						{
							if($at[$i]['x']<5)
							{
								$w3p = $w3['sv_pa'.$at[$i]['x']];
							}else{
								$w3p = $w3['sv_pm'.($at[$i]['x']-4)];
							}
						}elseif($i > 1)
						{
							if($at[$i]['x']<5)
							{
								$w14p = $w14['sv_pa'.$at[$i]['x']];
							}else{
								$w14p = $w14['sv_pm'.($at[$i]['x']-4)];
							}
						}						
						
						//мощность + подавление мощности противником
						
						if($at[$i]['x']==12)
						{
							//удар кулаком
							if($p1['align']==7)
							{
								$wAp += 15;
							}
							//$wAp += $s1['m10']*0.15;
							//$wAp -= $s2['antm10']*2.15;
						}elseif($at[$i]['x']<5)
						{
							$wAp += $s1['pa'.$at[$i]['x'].''];
							//$wAp += $s1['m10'];
							$wAp -= $s2['antpa'.$at[$i]['x'].''];
							//$wAp -= $s2['antm10'];
						}else{
							$wAp += $s1['pm'.($at[$i]['x']-4).''];
							//$wAp += $s1['m11a'];
							if($at[$i]['x']-4<5)
							{
							//	$wAp += $s1['m11'];
							}
							$wAp -= $s2['antpm'.($at[$i]['x']-4).''];
							//$wAp -= $s2['antm11a'];
							if($at[$i]['x']-4<5)
							{
								//$wAp -= $s2['antm11'];
							}
						}
						
						//$yrn[$i] += ceil((0.01+$yrn[$i]/100)*(0.01+0.25*($wAp+$w3p+$w14p)))-1;
						
						//Добавляем броню
						$brn = array(0,0);
						if($na==1)
						{
							$brn = array($s2['mib1'],$s2['mab1']);
						}elseif($na==2 || $na==3)
						{
							$brn = array($s2['mib2'],$s2['mab2']);
						}elseif($na == 4){
							$brn = array($s2['mib3'],$s2['mab3']);
						}else{
							$brn = array($s2['mib4'],$s2['mab4']);
						}
							
						//$wAp = 0;
						
						//Добавляем броню
						if($this->mfs(4,$s1['m9'])==0 && (!isset($s1['100proboi1']) || $s1['100proboi1'] < 1))
						{
							if(isset($s1['100proboi1']) && $s1['100proboi1'] > 0) {
								$s1['100proboi1']--;
							}
							$at[$i]['prb'] = 0;
						}else{
							if($at[$i]['x'] < 5) {
								$at[$i]['prb'] = 1;
							}else{
								$at[$i]['prb'] = 0;
							}
						}
																		
						$tu[$i] = $this->yrn($s1,$s2,$u1,$u2,$s1['lvl'],$s2['lvl'],$at[$i]['x'],$yi[$i][0],$yi[$i][1],$brn[0],$brn[1],$by[$i],($wAp+$w3p+$w14p),$s1['m3'],$s2['za'.$at[$i]['x']],$s2['ozash'],$at[$i]['prb'], $s1['items'][$s2['wp3id']]['2h']);
						$yrn[$i] = rand($tu[$i]['min'],$tu[$i]['max']);					
					
						if($at[$i]['x'] == 12) {
							//кулак
							
						}elseif($at[$i]['x']<5) {
							//проф
							
						}else{
							//магия
							
						}
						
												
                        if($yrn[$i] < 0){
							$yrn[$i] = 0;
						}
						
						//if($by[$i] > 0) {
						//	$yrn[$i] += ceil($yrn[$i]/100*$by[$i]*7);
						//}						
						
						//$yrMin = ceil($yrn[$i]/100*(19+1.11*($p1['level']-$p2['level'])));
						
						$yrMin = rand($yrMin+1,$yrMin+4);						
						if($yrMin < 0){ $yrMin = 0; }
													
									
						//расчет урона от (призрачки)
						//if(isset($s2['zaproc']) || isset($s2['za'.$at[$i]['x'].'proc']))
						//{
							// //$yrn[$i] = floor($yrn[$i]/100*(100-$s2['zaproc']-$s2['za'.$at[$i]['x'].'proc']));
						//}
						
						//if($yrn[$i]<$yrMin*0.95){ $yrn[$i] = round($yrMin*0.95); }
						
						//считаем защиту от урона
						/*if($at[$i]['x'] == 0) {
							//удар кулаком
							$yrn[$i] -= floor($yrn[$i]/107*($this->zago( floor(($s2['za1']+$s2['za2']+$s2['za3']+$s2['za4'])/4) )));
						}elseif($at[$i]['x']==12)
						{
							//удар кулаком
							//$yrn[$i] -= round($yrn[$i]/100*(($s2['za']+$s2['za'.$at[$i]['x']])/4));
							//$yrn[$i] -= round(  $yrn[$i]/100*(35*($s2['za']-$s1['pza']+$s2['za'.$at[$i]['x']]-$s2['pza'.$at[$i]['x']])/1200) );
							//$yrn[$i] -= floor($yrn[$i]/100*($this->zago($s2['za']+$s2['za'.$at[$i]['x']])));
							$yrn[$i] -= floor($yrn[$i]/107*($this->zago( floor(($s2['za1']+$s2['za2']+$s2['za3']+$s2['za4'])/4) )));
						}elseif($at[$i]['x']<5)
						{
							// оружие
							//$yrn[$i] -= round($yrn[$i]/100*(($s2['za']+$s2['za'.$at[$i]['x']])/12));
							//$yrn[$i] -= round(  $yrn[$i]/100*(35*($s2['za']-$s1['pza']+$s2['za'.$at[$i]['x']]-$s2['pza'.$at[$i]['x']])/1200) );
							$yrn[$i] -= floor($yrn[$i]/100*($this->zago($s2['za'.$at[$i]['x']])));
						}else{
							// магия
							//$yrn[$i] -= round($yrn[$i]/100*(($s2['za']+$s2['za'.$at[$i]['x']])/12));
							//$yrn[$i] -= round(  $yrn[$i]/100*(30*($s2['zm']-$s1['pzm']+$s2['zm'.$at[$i]['x']]-$s2['pzm'.$at[$i]['x']])/1200) );
							$yrn[$i] -= floor($yrn[$i]/100*($this->zago($s2['zm'.($at[$i]['x']-4)])));
						}*/
						
					$par = array();
					//уворот
					if($this->mfs(2,array('amf'=>$s1['m5'],'mf'=>$s2['m4'],'absmf'=>$s2['m15']))==1 && ($s1['no_yv2']==0 || ($s1['no_yv2']+$s2['no_yv1'])*100 < rand(1,10000))) {
						$par[0] = 1;
					}
					//крит
					if($this->mfs(1,array('amf'=>$s2['m2'],'mf'=>$s1['m1'],'absmf'=>$s1['m14']))==1 && $s2['nokrit']!=2 && $s1['nokrit']!=1) {
						$par[1] = 1;
					}
					//крит пробой блока
					if($this->mfs(1,array('amf'=>$s2['m2'],'mf'=>$s1['m1'],'absmf'=>$s1['m14']))==1 && $s2['nokrit']!=2 && $s1['nokrit']!=1 && $this->get_chanse(25) == true) {
						$par[4] = 1;
					}
					//парирование
					if($this->mfs(3,array(1=>$s2['m7'],2=>$s1['m7']))==1 && ($s1['no_pr2']==0 || ($s1['no_pr2']+$s2['no_pr1'])*100 < rand(1,10000))) {
						$par[2] = 1;
					}
					//блок щитом
					if($s2['items'][$s2['wp14id']]['type']==13)
					{
						if($this->mfs(5,$s2['m8'])==1 && ($s1['no_bl2']==0 || ($s1['no_bl2']+$s1['no_bl1'])*100 < rand(1,10000)))
						{
							$par[3] = 1;	
						}
					}
					
					//противник увернулся					
					if($par[0] == 1) {
						$at[$i]['t'] = 6;
					}else{
						
						//Обычный удар
						$at[$i]['t'] = 1;
						
						//нанесли крит
						if($par[1] == 1) {
							$at[$i]['t'] = 3;
						}
						
						//парирование
						if($par[2] == 1) {
							if($par[1] == 1) {
								//парируем через крит, наносим 50% крита
								//$at[$i]['t'] = 3;
							}else{
								//парируем
								$at[$i]['t'] = 9;
							}
						}
						
						//Попали в блок
						if($zb[$na]==1) {
							//в блок
							$at[$i]['t'] = 5;
							
							//пробив блок
							if($par[1] == 1 && $par[4] == 1) {
								$at[$i]['t'] = 4;
							}
						}
						
						//Блок щитом
						if($par[3] == 1) {
							$at[$i]['t'] = 7;
						}
					}
					/*					
					if($zb[$na]==1)
					{
						//удар был заблокирован
							$at[$i]['t'] = 5;
																				
							//вы пробили блок
							if($this->mfs(1,array('amf'=>$s2['m2'],'mf'=>$s1['m1'],'absmf'=>$s1['m14']))==1 && $s2['nokrit']!=2 && $s1['nokrit']!=1)
							{
								$at[$i]['t'] = 4;
							}	
							
							//противник увернулся
							if($this->mfs(2,array('amf'=>$s1['m5'],'mf'=>$s2['m4'],'absmf'=>$s2['m15']))==1 && ($s1['no_yv2']==0 || $s1['no_yv2']*100 < rand(1,10000)))
							{
								$at[$i]['t'] = 6;
							}					
					}else{
						//удар не заблокирован
							$at[$i]['t'] = 1;
							//противник заблокировал щитом
							
														
							//противник заблокировал удар щитом
							if($s2['items'][$s2['wp14id']]['type']==13)
							{
								if($this->mfs(5,$s2['m8'])==1 && ($s1['no_bl2']==0 || $s1['no_bl2']*100 < rand(1,10000)))
								{
									$at[$i]['t'] = 7;
								}
							}
							
							//противник увернулся
							if($this->mfs(2,array('amf'=>$s1['m5'],'mf'=>$s2['m4'],'absmf'=>$s2['m15']))==1 && ($s1['no_yv2']==0 || $s1['no_yv2']*100 < rand(1,10000)))
							{
								$at[$i]['t'] = 6;
							}else{													
								//противник парировал
								if($this->mfs(3,array(1=>$s2['m7'],2=>$s1['m7']))==1 && ($s1['no_pr2']==0 || $s1['no_pr2']*100 < rand(1,10000)))
								{
									$at[$i]['t'] = 9;
								}else{
									//вы нанесли крит
									if($this->mfs(1,array('amf'=>$s2['m2'],'mf'=>$s1['m1'],'absmf'=>$s1['m14']))==1 && $s2['nokrit']!=2 && $s1['nokrit']!=1)
									{
										$at[$i]['t'] = 3;
									}
								}
							}									
					}*/
					
					//Приемы с действием влияющим на урон / здоровье игроков (Игрок 1)
					$cas = 1; //"касание"
					if($yrn[$i]>0)
					{
						if($at[$i]['t'] == 1)
						{
							//обычный удар
							$cas = 1;
						}elseif($at[$i]['t'] == 6)
						{
							//противник увернулся от удара
							$cas = -1;
						}elseif($at[$i]['t'] == 3)
						{
							//вы нанесли крит-удар
							$cas = 2;
						}elseif($at[$i]['t'] == 4)
						{
							//вы нанесли крит-удар через блок
							$cas = 3;
						}elseif($at[$i]['t'] == 5)
						{
							//противник заблокировал удар
							$cas = -2;
						}else{
							//неизвестный удар
							$cas = 4;
						}
					}else{
						$cas = 0;
					}
					
							
/* ОСНОВНЫЕ ПРИЕМЫ ============================-============================== */
					$g1 = 0; $rstb = 0;
					while($g1<=1)
					{
						$k2 = 1;
						while($k2<=2)
						{							
							if(count($pd[$k2][1])>0)
							{								
								$k = 0;
								while($k<count($pd[$k2][1]))
								{									
									if(isset($this->pr_reset['data'][$pd[$k2][1][$k]['id']])) {
										$pd[$k2][1][$k]['data'] = $this->pr_reset['data'][$pd[$k2][1][$k]['id']];
										unset($this->pr_reset['data'][$pd[$k2][1][$k]['id']]);
									}
									$d2 = $u->lookStats($pd[$k2][1][$k]['priem']['date2']);
									if(isset($d2['d02'])) {
										$d02 = $u->lookStats($pd[$k2][1][$k]['data']);
									}
									if(isset($pd[$k2][1][$k]['priem']['id']) && $pd[$k2][1][$k]['priem']['delete'] == 0 && !isset($this->pr_not_use[$pd[$k2][1][$k]['id']]) && !isset($this->prnt[$pd[$k2][1][$k]['id']]))
									{					
										//echo '{'.$pd[$k2][1][$k]['priem']['name'].'-'.$pd[$k2][1][$k]['uid'].'}';
										if(!isset($d2['onlyCon']) || ($yrn[$i]>0 && $at[$i]['t'] != 7 && $at[$i]['t'] != 9) || isset($d2['rzEnd']) || ($d2['inblock'] == 1 && $at[$i]['t'] == 5 && isset($d2['onlyCon'])))
										{	
											if(!isset($d2['zash']) || $k2==2 || isset($d2['contr1']))
											{											
												if(!isset($d2['atck']) || $k2==1)
												{
													if(!isset($d2['zash']) || $g1==1)
													{
														//добавляем в лог что прием завершился
														if(isset($d2['rzEnd']) && $d2['rzEnd']==1)
														{
															$cas = 1;
														}
														if($d2['inblock'] == 1 && $at[$i]['t'] == 5) {
															$d2['noCos'] = 1;
														}
														if($cas<=0 && !isset($d2['noCos']) && !isset($d2['noCon']))
														{
															if($cas==-2 && isset($d2['ignor5']) && $d2['ignor5']==$k2)
															{
																
															}
														}elseif($cas != 2 && $cas != 3 && (isset($d2['atck_krit']) && !isset($d02['atck_krit_to_atck'])) && !isset($d2['noCon'])){
															//нужна крит атака для данного приема
														}elseif($cas > 0 || isset($d2['noCos']) || isset($d2['noCon']))
														{												
															$nodel = 0;
															if(isset($d2['yron_u'.$k2]) && $g1 == 0)
															{
																//влияет на урон игрока на котором прием
																$d2['yron_u'.$k2] = getdr($d2['yron_u'.$k2],array(0=>'lvl1',1=>'yr1'),array(0=>${'p'.$k2}['level'],1=>$yrn[$i]+$pr[$i]['y']));
																$pr[$i]['y'] += $d2['yron_u'.$k2];
															}	
															if(isset($d2['yv'.$k2]))
															{
																//влияет на уворот от удара
																$yrn[$i] = 0;
																$at[$i]['t'] = 6;
															}
															if(isset($d2['par'.$k2]))
															{
																//влияет на парирование удара
																$yrn[$i] = 0;
																$at[$i]['t'] = 9;
															}
															if(isset($d2['krit'.$k2]))
															{
																//влияет на крит удар
																$yrn[$i] = $yrn[$i];
																if($at[$i]['t']==1)
																{
																	$at[$i]['t'] = 3;
																}elseif($at[$i]['t']==5)
																{
																	$at[$i]['t'] = 4;
																}else{
																	$nodel = 1;
																}
															}
															
															if(isset($d2['contr2']))
															{
																//влияет на контрудар
																if($recontr == false) {
																	$this->contr[$iid]['y'][$s1['id']]++;
																	if($this->contr[$iid][$s1['id']][$s2['id']] < $s2['zona'])
																	{
																		if(!isset($this->contr[$iid][$s1['id']][$s2['id']])) {
																			$this->contr[$iid][$s1['id']][$s2['id']] = 0;
																		}
																		$this->contr[$iid][$s1['id']][$s2['id']]++;
																	}
																}
															}
															if(isset($d2['contr1']))
															{
																//влияет на контрудар
																if($recontr == false) {
																	$this->contr[$iid]['y'][$s2['id']]++;
																	if($this->contr[$iid][$s2['id']][$s1['id']] < $s1['zona'])
																	{
																		if(!isset($this->contr[$iid][$s2['id']][$s1['id']])) {
																			$this->contr[$iid][$s2['id']][$s1['id']] = 0;
																		}
																		$this->contr[$iid][$s2['id']][$s1['id']]++;
																	}
																}
															}
															
															/*if(isset($d2['contr'.$k2]))
															{
																//влияет на контрудар
																if($recontr == false) {
																	$this->contr[$iid]['y'][$s1['id']]++;
																	if($this->contr[$iid][$s1['id']][$s2['id']] < $s2['zona'])
																	{
																		if(!isset($this->contr[$iid][$s1['id']][$s2['id']])) {
																			$this->contr[$iid][$s1['id']][$s2['id']] = 0;
																		}
																		$this->contr[$iid][$s1['id']][$s2['id']]++;
																	}
																}
																																
																/*$ik++;					
																$ykf = rand(1,count($yrn[$i]));
																if($ykf < 0) {
																	$ykf = 1;
																}
																$a['a'.$u1][$ik-1] = rand(0,5);
																$tz[$ik] = $tz[$ykf];
																$tx[$ik] = $tx[$ykf];
																$tc[$ik] = $tc[$ykf];
																$yrn[$ik] = round($yrn[$ykf]*0.95);
																$kii[$ik] = 1;
																if(isset($d2['contr'.$k2.'ef']) && $pd[$k2][1][$k]['priem']['delete'] == 0) {
																	$pd[$k2][1][$k]['priem']['delete'] = time();
																	if($k2==1)
																	{
																		$k2jn = 2;
																	}else{
																		$k2jn = 1;
																	}																	
																	$this->delPriem($pd[$k2][1][$k],${'p'.$k2},1,${'p'.$k2jn},'Очиститься Кровью',$k2);
																}*/
															//}
															if(isset($d2['maxkrit'.$k2]))
															{
																//влияет на крит удар
																if($at[$i]['t']==3 || $at[$i]['t']==4)
																{
																	$yrn[$i] = $yrn[$i]+($yrn[$i]*0.25);
																}else{
																	$nodel = 1;
																}
															}
														
															if(isset($d2['del_af_use'])) {
																$del_af[count($del_af)] = array($k2,$k);
															}
															
															if(((isset($d2['del_use']) && $nodel==0) || isset($d2['rzEnd'])) && !isset($d2['del'.$k2.'ef']))
															{	
																$d2['del_use']--;
																if($d2['del_use']==0)
																{
																	$this->prnt[$pd[$k2][1][$k]['id']]++;
																	if($k2==1)
																	{
																		$k2jn = 2;
																	}else{
																		$k2jn = 1;
																	}
																	if($pd[$k2][1][$k]['priem']['delete'] == 0) {
																		$pd[$k2][1][$k]['priem']['delete'] = time();
																		$this->delPriem($pd[$k2][1][$k],${'p'.$k2},1,${'p'.$k2jn},'Очиститься Кровью',false,$yrn[$i],$at[$i]['t']);
																	}
																}else{
																	$pd[$k2][1][$k]['priem']['date2'] = $u->impStats($d2);
																}
																if(isset($d2['btl_st_ref'])) {
																	$rstb++;
																}
															}
															
															if(isset($d2['hfuse']) && $g1==1) {
																if($k2==1)
																{
																	$k2jn = 2;
																}else{
																	$k2jn = 1;
																}
																$this->hodUserPriem($pd[$k2][1][$k],${'p'.$k2},1,${'p'.$k2jn},'Очиститься Кровью',false,$yrn[$i],$at[$i]['t']);
															}
															
															if($this->pr_yrn != false) {
																$yrn[$i] = $this->pr_yrn[0];
																$this->pr_yrn = false;
															}
															
															if(isset($d2['die'.$k2])) {
																//Убиваем игрока
																if(${'s'.$k2}['hpNow'] > 0) {
																	${'s'.$k2}['hpNow'] = 0;
																	${'p'.$k2}['hpNow'] = 0;																	
																	$this->users[$this->uids[${'p'.$k2}['id']]]['hpNow'] = 0;																	
																	$this->stats[$this->uids[${'p'.$k2}['id']]]['hpNow'] = 0;
																	$re_st[$k2]['hpNow'] = 0;
																	$re_st[$k2]['tactic7'] = 0;
																	$this->stats[$this->uids[${'p'.$k2}['id']]]['tactic7'] = 0;
																	if(${'p'.$k2}['id'] == $u->info['id']) {
																		$u->stats['hpNow'] = 0;
																	}
																	mysql_query('UPDATE `stats` SET `hpNow` = "0",`tactic7` = "0" WHERE `id` = "'.${'s'.$k2}['id'].'" LIMIT 1');
																}
															}															
															$at[$i]['minf'] = str_replace(', '.$pd[$k2][1][$k]['priem']['name'],'',$at[$i]['minf']);
															$at[$i]['minf'] .= ', '.$pd[$k2][1][$k]['priem']['name'];												
														}
													}
												}
											}
										}
									}
									$k++;
								}
							}
							$k2++;
						}					
						$g1++;
					}
										
					//*[priems]*[Приемы защиты, либо усиления удара, типа 1 и 2]
					$yrn[$i] += $pr[$i]['y'];
					
					//расчет урона
					if($at[$i]['t'] == 1)
					{
						//обычный удар
						$at[$i]['y'] = round($yrn[$i]);
					}elseif($at[$i]['t'] == 6)
					{
						//противник увернулся от удара
						$at[$i]['y'] = 0;
						if($recontr == false) {
							$this->contr[$iid]['y'][$p2['id']]++;
						}
					}elseif($at[$i]['t'] == 9)
					{
						//противник парировал удар
						$at[$i]['y'] = 0;
					}elseif($at[$i]['t'] == 3)
					{
						//вы нанесли крит-удар
						//$at[$i]['y'] = round($yrn[$i]*1.95)+ceil($yrn[$i]/125*$s1['m3']);
						//$at[$i]['y'] = rand($tu[$i]['Kmin'],$tu[$i]['Kmax']);	
						$at[$i]['y'] = round($yrn[$i]*2*(1+$s1['m3']/200));
					}elseif($at[$i]['t'] == 4)
					{
						//вы нанесли крит-удар через блок
						//$at[$i]['y'] = round($yrn[$i]*0.45)+ceil($yrn[$i]/125*$s1['m3']);
						//$at[$i]['y'] = round(rand($tu[$i]['Kmin'],$tu[$i]['Kmax'])/2);
						$at[$i]['y'] = round($yrn[$i]*2*(1+$s1['m3']/100)/2);
					}else{
						//неизвестный удар
						$at[$i]['y'] = 0;
					}
					
					if($at[$i]['t'] != 6 && $at[$i]['t'] != 9 && $at[$i]['t'] != 5) {
						//расчет урона от (призрачки)
						if(isset($s2['zaproc']) || isset($s2['za'.$at[$i]['x'].'proc']) || isset($s2['zm'.($at[$i]['x']-4).'proc']))
						{
							if($at[$i]['x'] < 5) {
								//echo '[<b>'.$p2['login'].'</b> поглощено урона: '.($s2['zaproc']-$s2['za'.$at[$i]['x'].'proc']).'%]';
								$at[$i]['y'] = floor($at[$i]['y']/100*(100-$s2['zaproc']-$s2['za'.$at[$i]['x'].'proc']));
							}else{
								$at[$i]['y'] = floor($at[$i]['y']/100*(100-$s2['zmproc']-$s2['zm'.($at[$i]['x']-4).'proc']));
							}
						}
						if($at[$i]['y']<1 && $at[$i]['y']>-10000){ $at[$i]['y'] = 1; }
					}
					if($rstb>0) {
						$p1 = $this->users[$this->uids[$uid1]];
						$p2 = $this->users[$this->uids[$uid2]];
						$s1 = $this->stats[$this->uids[$uid1]];
						$s2 = $this->stats[$this->uids[$uid2]];
					}							
					$i++;
				}
				
				//Удаляем приемы в конце
				if(count($del_af) > 0) {
					$if = 0;
					while($if < count($del_af)) {
						//Удаляем приемы
						if(isset($pd[$del_af[$if][0]][1][$del_af[$if][1]]['priem']['id']) && $pd[$del_af[$if][0]][1][$del_af[$if][1]]['priem']['delete'] == 0) {
							$pd[$del_af[$if][0]][1][$del_af[$if][1]]['priem']['delete'] = time();
							if($del_af[$if][0]==1)
							{
								$del_af[$if][2] = 2;
							}else{
								$del_af[$if][2] = 1;
							}
							$this->delPriem($pd[$del_af[$if][0]][1][$del_af[$if][1]],${'p'.$del_af[$if][0]},1,${'p'.$del_af[$if][0]});
						}
						$if++;
					}
				}
				
				unset($rstb,$hst1,$hst2,$del_af);
						
				if($re_st[1]['hpNow'] != $this->stats[$this->uids[$uid1]]['hpNow']) {
					$re_st[1]['hpNow'] = $this->stats[$this->uids[$uid1]]['hpNow'];
				}
				
				if($re_st[2]['hpNow'] != $this->stats[$this->uids[$uid2]]['hpNow']) {
					$re_st[2]['hpNow'] = $this->stats[$this->uids[$uid2]]['hpNow'];
				}
						
				$this->stats[$this->uids[$uid1]] = $re_st[1];
				$this->stats[$this->uids[$uid2]] = $re_st[2];
				
				unset($re_st);					
							
				return $at;
			}
		}

	//Завершение действия приема
	// pl прием
	// u1 инфа юзера
	// t1 тип снятия 
	// 99 = очищение кровью
	// u2 
	//$this->delPriem($pd[$k2][1][$k],${'p'.$k2},1,${'p'.$k2jn});
		public $del_val = array(),$re_pd = array();
		public function delPriem($pl,$u1,$t = 1,$u2 = false,$rznm = 'Очиститься Кровью',$k2nm,$yrn,$yrnt)
		{
			global $u,$priem;
			if(isset($pl['priem']['id']) && !isset($this->del_val['eff'][$pl['priem']['id']]))
			{
				if($pl['x'] > 1) {
					$pl['name'] = $pl['name'].' x'.$pl['x'].'';
				}
				if($pl['timeUse']==77)
				{
					//завершаем прием
					mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
				$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'';
				if(isset($u2['id'])) {
					$vLog .= '||s2='.$u2['sex'].'||t2='.$u2['team'].'||login2='.$u2['login'].'';
				}
				$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				if($t==4)
				{
					$mas1['id_hod']++;
					$t = 2;
				}if($t==1)
				{
					$mas1['id_hod']++;
					if($pl['priem']['file']!='0')
					{						
						if(file_exists('../../_incl_data/class/priems/'.$pl['priem']['file'].'.php'))
						{
							require('priems/'.$pl['priem']['file'].'.php');
						}
					}elseif($pl['priem']['file3']!='0')
					{						
						if(file_exists('../../_incl_data/class/priems/'.$pl['priem']['file3'].'.php'))
						{
							require('priems/'.$pl['priem']['file3'].'.php');
						}
					}else{
						$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;.';
						$this->del_val['eff'][$pl['priem']['id']] = true;
					}
				}elseif($t==2)
				{
					$mas1['text'] = '{tm1} У персонажа {u1} закончилось действие магии &quot;<b>'.$pl['name'].'</b>&quot;.';
				}elseif($t==99){
				    $mas1['text'] = '{u1} Снял эфект &quot;<b>'.$pl['name'].'</b>&quot; с помощью <b>'.$rznm.'</b> .';
				}else{
					$mas1['text'] = '{tm1} Закончилось действие эффекта &quot;<b>'.$pl['name'].'</b>&quot; для {u1}.';
				}
				$this->add_log($mas1);
				$this->stats[$this->uids[$pl['uid']]] = $u->getStats($pl['uid'],0);
			}else{
				//не удалось удалить прием или эффект
			}
		}
		public function hodUserPriem($pl,$u1,$t = 1,$u2 = false,$rznm = 'Очиститься Кровью',$k2nm,$yrn,$yrnt)
		{
			global $u,$priem;
			if(isset($pl['priem']['id']) && !isset($this->del_val['eff'][$pl['priem']['id']]))
			{

				if($yrnt == 1)
				{
					//обычный удар
					$yrn = round($yrn);
				}elseif($yrnt == 6)
				{
					//противник увернулся от удара
					$yrn = 0;
				}elseif($yrnt == 9)
				{
					//противник парировал удар
					$yrn = 0;
				}elseif($yrnt == 3)
				{
					//вы нанесли крит-удар
					$yrn = round($yrn*1.95)+ceil($yrn/125*$this->stats[$this->uids[$u1['id']]]['m3']);
				}elseif($yrnt == 4)
				{
					//вы нанесли крит-удар через блок
					$yrn = round($yrn*0.45)+ceil($yrn/125*$this->stats[$this->uids[$u1['id']]]['m3']);
				}else{
					//неизвестный удар
					$yrn = 0;
				}
				
				if($pl['x'] > 1) {
					$pl['name'] = $pl['name'].' x'.$pl['x'].'';
				}
				$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'';
				if(isset($u2['id'])) {
					$vLog .= '||s2='.$u2['sex'].'||t2='.$u2['team'].'||login2='.$u2['login'].'';
				}
				$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				if($t==4)
				{
					$mas1['id_hod']++;
					$t = 2;
				}if($t==1)
				{
					$mas1['id_hod']++;
					if($pl['priem']['file']!='0')
					{						
						if(file_exists('../../_incl_data/class/priems/'.$pl['priem']['file'].'.php'))
						{
							require('priems/'.$pl['priem']['file'].'.php');
						}
					}else{
						$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;.';
						$this->del_val['eff'][$pl['priem']['id']] = true;
					}
				}
				$this->add_log($mas1);
				$this->stats[$this->uids[$pl['uid']]] = $u->getStats($pl['uid'],0);
			}else{
				//не удалось удалить прием или эффект
			}
		}
		
	//расчет защиты
		public function aPower($za,$za1,$yrn)
		{
			$z = 0;
			/*$z = ($za+$za1)*0.35;
			$z = round($yrn/$z*100);*/
			
			$z = (1-( pow(0.5, (($za+$za1)/250) ) ))*100; 			
			return $z;
		}
		
	//расчет брони
		public function bronGo($min,$max)
		{
			$v = 0;
			$v = ceil(($min+$max)/2,$max);
			return $v;
		}	
		
	//расчет брони (test)
		public function bronGoTest($min,$max)
		{
			$v = 0;
			//$v = ceil(($min+$max)/2,$max);
			$v = ceil(round($min,$max));
			return $v;
		}
	
	//Разбираем массив со статами
		public function lookStats($m)
		{
			$ist = array();
			$di = explode('|',$m);
			$i = 0; $de = false;
			while($i<count($di))
			{
				$de = explode('=',$di[$i]);
				$ist[$de[0]] = $de[1];
				$i++;
			}
			return $ist;
		}
		
	//Расчет зависимости уворота
		public function mfsgo1($a,$b) {
			/*$r = 0.0928;
			$a = 5 + ($a * 0.3);
			$b = 5 + ($b * 0.3);
			if($b < 1) {
				$b = 1;
			}
			if($a < $b) {
				$a = $b+1;
			}
			$r = ceil($r*($a-$b)) + 5;*/
			$r = $this->form_mf($a,$b);
			return $r;
		}
		
	//Расчет зависимости крита
		public function mfsgo2($a,$b) {
			/*$r = 0.0838;
			$a = 5 + ($a * 0.3);
			$b = 5 + ($b * 0.3);
			if($b < 1) {
				$b = 1;
			}
			if($a < $b) {
				$a = $b+1;
			}
			$r = ceil($r*($a-$b));*/
			$r = $this->form_mf($a,$b);
			return $r;
		}
		
	//Расчет мф. (новая)
		public function form_mf($u,$au) {
			$v = $u - $au;
			if($v < 0) {
				$v = 0; 
			} //testtest
			$r = (1-( pow(rand(89,90)/100, (($v)/100) ) ))*100;	
			$r = round($r);
			return $r;
		}
		
	//Расчет МФ		
		public function mfs($type, $mf)
		{
			$rval = 0;
			switch($type) {
				case 1:
					if($mf['amf'] < 1){ $mf['amf'] = 1; }
					if($mf['mf'] < 1){ $mf['mf'] = 1; }
					if($mf['amf'] > $mf['mf']) {
						$mf['amf'] = $mf['mf'];
					}
					$rval = min( (( 1 - ( ( $mf['amf']*0.8 + 500 ) / ( $mf['mf']*0.8 + 500 ) ) ) * 100), 80); //Крит. удар
					//$rval = min($this->mfsgo2(($mf['mf']),$mf['amf']),80);
					if($rval < 1) {
						$rval = 1;
					}
					if($rval > 80) {
						$rval = 80;
					}	
				break;
				case 2:
					if($mf['amf'] < 1){ $mf['amf'] = 1; }
					if($mf['mf'] < 1){ $mf['mf'] = 1; }
					if($mf['amf'] > $mf['mf']) {
						$mf['amf'] = $mf['mf'];
					}
					$rval = min( ( ( 1 - ( ( $mf['amf']*0.8 + 500 ) / ( $mf['mf']*0.8 + 500 ) ) ) * 100 ), 80 ); //Уворот
					//$rval = min($this->mfsgo1($mf['mf'],$mf['amf']),60);
					//$rval += 10;
					if($rval < 1) {
						$rval = 1;
					}
					if($rval > 60) {
						$rval = 60;
					}					
				break;
				case 3:
					if($mf[1] < 1){ $mf[1] = 1; }
					if($mf[2] < 1){ $mf[2] = 1; }
					
					$rval = $mf[1] - $mf[2]; //Парирование
					
					if( $rval < 0 ) {
						$rval = 0;
					}
					
					$rval = (1-( pow(0.75, ($rval/100) ) ))*100;	
					
				break;
				case 4:
					if($mf < 1){ $mf = 0; }
					$mf = (1-( pow(0.5, ($mf/200) ) ))*100;
					$rval = min( $mf , 40 ); //пробой брони
				break;
				case 5:
					if($mf < 1){ $mf = 0; }
					$rval = min( $mf, 40 ); //блок щитом
				break;
				case 6:
					if($mf < 1){ $mf = 0; }
					$mf = (1-( pow(0.8, ($mf/100) ) ))*100;
					$rval = min( $mf , 40 ); //Контрудар
					$rval = 0;
				break;
			}
			if($this->get_chanse($rval) == true) {
				$rval = 1;
			}else{
				$rval = 0;
			}
			return $rval;
		}		
	
		public function get_chanse($percent)
		{
			$a = 101+$percent;
			$b = 100-$percent;
			$i = 1;
			if(($a-$b)>0){
				while($i<=$a-$b){
					$conp[] = mt_rand(1,100);               
					$i++;   
				}
			}
			$t = count($conp);
			$prob = round($percent);
			if(@array_search($prob,$conp)!=false){
				$critical = true;
			}else{
				$critical = false;
			} 
			return $critical;
		}
	
	//Расчет шанса
		public function get_chanse_new($persent)
		{		
			$mm = 10;
			if(rand($mm,100*$mm)<=$persent*$mm)
			{
				return true;
			}else{
				return false;
			}
		}
	
	//Смена противника
		public function smena($uid,$auto = false)
		{
			global $u;
			if(($auto == false && $u->info['smena'] > 0) || $auto == true) {
				if($this->stats[$this->uids[$u->info['id']]]['hpNow']>=1)
				{
					if(isset($this->uids[$uid]) && $uid!=$u->info['id'] && $this->users[$this->uids[$uid]]['team']!=$this->users[$this->uids[$u->info['id']]]['team'])
					{
						if(!isset($this->ga[$u->info['id']][$uid]))
						{
							if(floor($this->stats[$this->uids[$uid]]['hpNow'])>=1)
							{
								//меняем противника
								if($auto == false) {
									$u->info['smena']--;
								}
								$upd = mysql_query('UPDATE `stats` SET `enemy` = "'.$uid.'",`smena` = "'.$u->info['smena'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								$u->info['enemy'] = $uid;
								$this->users[$this->uids[$uid]]['smena'] = $u->info['smena'];
								$this->users[$this->uids[$u->info['id']]]['enemy'] = $uid;
								return 1;
							}else{
								return 'Нельзя сменить, противник уже мертв';
							}
						}else{
							return 'Нельзя сменить на выбранную цель!';
						}
					}else{
						return 'Нельзя сменить на выбранную цель';
					}
				}else{
					return 'Для вас поединок закончен, ожидайте пока завершат другие...';
				}
			}else{
				return 'У вас закончились смены противника';
			}
		}
	
	//авто-смена противника
		public function autoSmena()
		{
			global $u;
			$ms = array();
			$ms_all = array();
			$i = 0; $j = 0;
			while($i<count($this->users))
			{
				if(isset($this->users[$i]) && $this->users[$i]['id']!=$u->info['id'] && $this->users[$i]['team']!=$u->info['team'] && $this->stats[$i]['hpNow']>=1 && -($u->info['enemy']) != $this->users[$i]['id'])
				{
					if(!isset($this->ga[$u->info['id']][$this->users[$i]['id']]))
					{
						$ms[$j] = $this->users[$i]['id'];
						$j++;
					}
					if( !isset($this->uids[(-($u->info['enemy']))]) ) {
						$ms_all[] = $this->users[$i]['id'];
					}
				}
				$i++;
			}

			$ms = $ms[rand(0,$j-1)];
			if($j>0)
			{
				$this->smena($ms,true);	
			}else{
				if( $u->info['enemy'] < 0 ) {
					$smnr5 = $this->smena(-($u->info['enemy']),true);
					if( $smnr5 != 1 ) {
						//$u->info['enemy'] = -($u->info['enemy']);
						//mysql_query('UPDATE `stats` SET `enemy` = "'.$u->info['enemy'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if( !isset($this->uids[(-($u->info['enemy']))]) ) {
							$u->info['enemy'] = $ms_all[rand(0,(count($ms_all)-1))];
							mysql_query('UPDATE `stats` SET `enemy` = "'.$u->info['enemy'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
						//echo '<font color=red><b>Fatal error: '.$smnr5.' #'.$j.'</b></font>';
					}
					unset($smnr5);
					//mysql_query('UPDATE `stats` SET `enemy` = "'.$u->info['enemy'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				}
			}
		}
			
	//Действия бота (атака)
		public function botAtack($uid,$pl,$tp)
		{
			//global $c,$u,$code;
			//Бот использует прием, если это возможно
			/*$ij = 0;
			$bp = explode('|',$this->users[$this->uids[$uid]]['priems']);
			$bpz = explode('|',$this->users[$this->uids[$uid]]['priems_z']);
			while($i<count($bp))
			{
				$plj = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.((int)$bp[$ij]).'" AND `activ` > "0" LIMIT 1'));
				if(isset($plj['id']))
				{
					$notrj = 0;

					

					if($bpz[$ij]<=0 && $bp[$ij]>0 && $notrj==0)
					{
						//можно использовать
						
					}
				}
				$ij++;
			}*/
			//$pl - удар 
			if($tp==1)
			{
				//бот сам делает удар
				//if(rand(0,1)==1)
				//{
					$a = rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5);
					$b = rand(1,5);
					mysql_query('LOCK TABLES battle_act WRITE');
					
					$d = mysql_query('INSERT INTO `battle_act` (`battle`,`time`,`uid1`,`uid2`,`a1`,`b1`) VALUES ("'.$this->info['id'].'","'.time().'","'.$pl.'","'.$uid.'","'.$a.'","'.$b.'")');
					
					mysql_query('UNLOCK TABLES');
				//}
			}elseif($tp==2)
			{
				//бот отвечает на удар
				$bot = $this->users[$this->uids[$pl['uid2']]];
				$na = array('id'=>0,'a'=>array(1=>0,2=>0,3=>0,4=>0,5=>0),'b'=>0);
				$a222 = rand(1,5).'_'.rand(1,5).'_'.rand(1,5).'_'.rand(1,5).'_'.rand(1,5);
				$a = explode('_',$a222);
				$i = 1;
				$na['id'] = time();
				while($i<=5)
				{
					if(isset($a[$i-1]))
					{
						$a[$i-1] = intval(round($a[$i-1]));
						if($a[$i-1]>=1 && $a[$i-1]<=5)
						{
							$na['a'][$i] = $a[$i-1];
						}else{
							$na['a'][$i] = 0;
						}
					}
					$i++;
				}				
				$na['b'] = rand(1,5);
				//Проводим удар
				$this->atacks[$pl['id']]['a2'] = $a222;
				$this->atacks[$pl['id']]['b2'] = $na['b'];
				$this->startAtack($pl['id']);
			}
		}
	
	//Проверяем удары, приемы, свитки, зверей
		public function testActions()
		{
			//проверяем удары
				$m = mysql_query('SELECT * FROM `battle_act` WHERE `battle` = "'.$this->info['id'].'" ORDER BY `id` DESC LIMIT 50');
				$i = 0;
				$botA = array();
				$botR = array();
				while($pl = mysql_fetch_array($m))
				{
					if($this->stats[$this->uids[$pl['uid1']]]['hpNow']<=0 || $this->stats[$this->uids[$pl['uid2']]]['hpNow']<=0)
					{
						mysql_query('DELETE FROM `battle_act` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					}elseif($pl['time']+$this->info['timeout']>time())
					{
						//удар не пропущен по тайму, просто заносим данные
						$this->atacks[$pl['id']] = $pl;
						$this->ga[$pl['uid1']][$pl['uid2']] = $pl['id'];
						$this->ag[$pl['uid2']][$pl['uid1']] = $pl['id'];
						if(isset($this->iBots[$pl['uid1']]))
						{
							//ударил бот и нет ответа
							$botA[$pl['uid1']] = $pl['id'];
						}elseif(isset($this->iBots[$pl['uid2']]))
						{
							//ударили бота и он не ответил
							$botR[$pl['uid2']] = $pl['id'];	
							$this->botAtack($pl['uid1'],$pl,2);
						}
					}else{
						//пропуск по тайму
						if($pl['a1']==0 && $pl['a2']==0)
						{
							//игрок 1 пропустил по тайму
							$pl['out1'] = time();	
							$pl['tout1'] = 1;	
							//игрок 2 пропустил по тайму
							$pl['out2'] = time();	
							$pl['tout2'] = 1;	
						}elseif($pl['a1']==0)
						{
							//игрок 1 пропустил по тайму
							$pl['out1'] = time();	
							$pl['tout1'] = 1;					
						}elseif($pl['a2']==0)
						{
							//игрок 2 пропустил по тайму
							$pl['out2'] = time();	
							$pl['tout2'] = 1;							
						}
						//наносим удар по пропуску
						$this->atacks[$pl['id']] = $pl;
						$this->startAtack($pl['id']);
					}
				}
			//тест удара
				if($this->uAtc['id']>0)
				{
					if($this->na==1)
					{
						$this->addNewAtack();
					}
				}
			//тест использования заклятий
				
			//тест использования приемов
				
			//тест, бот делает удары
				$i = 0;
				while($i<count($this->bots))
				{
					$bot = $this->bots[$i];
					if(isset($bot))
					{
						$j = 0;
						while($j<count($this->users))
						{
							if($this->users[$j]['hpNow'] >= 1) {
								if(isset($this->users[$j]) && $this->stats[$j]['hpNow']>=1 && $this->stats[$this->uids[$bot]]['hpNow']>=1 && !isset($this->ga[$bot][$this->users[$j]['id']]) && !isset($this->ag[$bot][$this->users[$j]['id']]) && $this->users[$j]['id']!=$bot && $this->users[$j]['team']!=$this->users[$this->uids[$bot]]['team'])
								{
									$this->botAtack($this->users[$j]['id'],$bot,1);
								}elseif(isset($this->users[$i]) && $this->users[$i]['bot']>0 && $this->stats[$i]['hpNow']>=1 && $this->stats[$this->uids[$bot]]['hpNow']>=1 && $this->users[$i]['id']!=$bot && $this->users[$i]['team']!=$this->users[$this->uids[$bot]]['team']){
									if($this->botAct($bot)==true)
									{
										if(!isset($this->ga[$bot][$this->users[$i]['id']]) && $this->users[$this->uids[$bot]]['timeGo']<time() && !isset($this->ag[$bot][$this->users[$i]['id']]))
										{
											$this->botAtack($this->users[$i]['id'],$bot,1);
											}elseif(isset($this->ag[$bot][$this->users[$i]['id']]))
										{
										}elseif(isset($this->ga[$bot][$this->users[$i]['id']]) && $this->users[$this->uids[$bot]]['timeGo']<time())
										{
											$this->botAtack($bot,$this->users[$i]['id'],1);
										}
									}
								}else{
									//Удары между ботами
									if(isset($this->ga[$bot][$this->users[$j]['id']]) && $this->users[$j]['bot']>0)
									{
										if($this->users[$j]['timeGo'] < time() && $this->users[$this->uids[$bot]]['timeGo']<time())
										{
											$this->startAtack($this->ga[$bot][$this->users[$j]['id']]);
											$this->users[$this->uids[$bot]]['timeGo'] = time()+7;
											mysql_query('UPDATE `stats` SET `timeGo` = "'.(time()+7).'" WHERE `id` = "'.$this->users[$j]['id'].'" LIMIT 1');
										}
									}elseif(isset($this->ag[$bot][$this->users[$j]['id']]) && $this->users[$j]['bot']>0){
										if($this->users[$this->uids[$bot]]['timeGo']<time() && $this->users[$j]['timeGo']<time())
										{
											$this->startAtack($this->ag[$bot][$this->users[$j]['id']]);
											$this->users[$this->uids[$bot]]['timeGo'] = time()+7;
											mysql_query('UPDATE `stats` SET `timeGo` = "'.(time()+7).'" WHERE `id` = "'.$this->users[$this->uids[$bot]]['id'].'" LIMIT 1');
										}
									}
								}
							}
							$j++;
						}
					}
					$i++;
				}
		}
		
	//Действия бота
		public function botAct($uid)
		{
			$r = false;
			if($this->users[$this->uids[$uid]]['bot']>0)
			{
				if($this->users[$this->uids[$uid]]['online'] < time()-3)
				{
					$r = true;
					$this->users[$this->uids[$uid]]['online'] = time();
					mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.((int)$uid).'" LIMIT 1');
				}else{
					if(rand(0,2)==1)
					{
						$r = true;
					}
				}
			}
			return $r;
		}
		
	//получаем данные о поединке
		public function battleInfo($id)
		{
			$b = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
			if(isset($b['id']))
			{
				$this->hodID = mysql_fetch_array(mysql_query('SELECT `id_hod` FROM `battle_logs` WHERE `battle` = "'.$b['id'].'" ORDER BY `id` DESC LIMIT 1'));
				if(isset($this->hodID['id_hod']))
				{
					$this->hodID = $this->hodID['id_hod'];
				}else{
					$this->hodID = 0;
				}	
				return $b;
			}else{
				return false;
			}
		}
	
	//наносим удар противнику
		public function addAtack()
		{
			global $js;
			if(isset($_POST['atack'],$_POST['block']))
			{
				$na = array('id'=>0,'a'=>array(1=>0,2=>0,3=>0,4=>0,5=>0),'b'=>0);
				$a = explode('_',$_POST['atack']);
				$i = 1;
				$na['id'] = time();
				while($i<=5)
				{
					if(isset($a[$i-1]))
					{
						$a[$i-1] = intval(round($a[$i-1]));
						if($a[$i-1]>=1 && $a[$i-1]<=5)
						{
							$na['a'][$i] = $a[$i-1];
						}else{
							$na['a'][$i] = 0;
						}
					}
					$i++;
				}
				
				$na['b'] = intval(round($_POST['block']));
				if($na['b']<1 || $na['b']>5)
				{
					$na['b'] = 0;
				}
				
				$this->uAtc = $na;
				$js .= 'testClearZone();';
			}else{
				$this->e = 'Выберите зоны удара и блока';
			}
		}
		
	//выделяем пользователей
		public function teamsTake()
		{
			global $u;
			$rs = ''; $ts = array(); $tsi = 0;
			if($this->info['id']>0)
			{
				//данные о игроках в бою
				$nxtlg = array();
				$t = mysql_query('SELECT `u`.`no_ip`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`u`.`sex`,`u`.`money`,`u`.`bot_id` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'" AND `st`.`hpNow` > 0');
				$i = 0; $bi = 0; $up = '';
				if($this->info['start2']==0) {
					$tststrt = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `start2` = "0" LIMIT 1'));
					if(isset($tststrt['id'])) {
						$upd = mysql_query('UPDATE `battle` SET `start2` = "'.time().'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					}else{
						$this->info['start2'] = time();
					}
				}
				while($pl = mysql_fetch_array($t))
				{
					//записываем данные
					if($pl['login2']=='')
					{
						$pl['login2'] = $pl['login'];
					}
					$this->users[$i] = $pl;
					$this->uids[$pl['id']] = $i;
					if($pl['bot']>0)
					{
						$this->bots[$bi] = $pl['id'];
						$this->iBots[$pl['id']] = $bi;
						$bi++;
					}
					//записываем статы
					$this->stats[$i] = $u->getStats($pl,0,0,false,$this->cached);
					//Заносим старт
					if($this->info['start2']==0)
					{
						if(!isset($ts[$this->users[$i]['team']]))
						{
							$tsi++;
							$ts[$this->users[$i]['team']] = $tsi;
						}
						
						if($this->users[$i]['level']<=7)
						{
							$this->users[$i]['tactic7'] = floor(10/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						}elseif($this->users[$i]['level']==8)
						{
							$this->users[$i]['tactic7'] = floor(20/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						}elseif($this->users[$i]['level']==9)
						{
							$this->users[$i]['tactic7'] = floor(30/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						}elseif($this->users[$i]['level']>=10)
						{
							$this->users[$i]['tactic7'] = floor((40+$this->stats[$i]['s7'])/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						}
						

						$this->users[$i]['tactic7'] += $this->stats[$i]['s7'];
						// Бафф Зверя animal_bonus
						if($this->users[$i]['animal'] > 0) {
							$a = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `id` = "'.$this->users[$i]['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
							if(isset($a['id'])) {
								if($a['eda']>=1) {
									$anl = mysql_fetch_array(mysql_query('SELECT `bonus` FROM `levels_animal` WHERE `type` = "'.$a['type'].'" AND `level` = "'.$a['level'].'" LIMIT 1'));
									$anl = $anl['bonus'];
									
									$tpa = array(1=>'cat',2=>'owl',3=>'wisp',4=>'demon',5=>'dog',6=>'pig',7=>'dragon');
									$tpa2 = array(1=>'Кота',2=>'Совы',3=>'Светляка',4=>'Чертяки',5=>'Пса',6=>'Свина',7=>'Дракона');
									$tpa3 = array(1=>'Кошачья Ловкость',2=>'Интуиция Совы',3=>'Сила Стихий',4=>'Демоническая Сила',5=>'Друг',6=>'Полная Броня',7=>'Инферно');
									
									mysql_query('INSERT INTO `eff_users` (`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`,`user_use`) VALUES ("-1","201","summon_pet_'.$tpa[$a['type']].'.gif",22,"'.$this->users[$i]['id'].'","'.$tpa3[$a['type']].' ['.$a['level'].']","'.$anl.'","0","77","priem","'.$this->users[$i]['id'].'")');
									
									$anl = $u->lookStats($anl);
									
									$vLog = 'time1='.time().'||s1='.$this->users[$i]['sex'].'||t1='.$this->users[$i]['team'].'||login1='.$this->users[$i]['login'].'';
									$vLog .= '||s2=1||t2='.$this->users[$i]['team'].'||login2='.$a['name'].' (Зверь '.$this->users[$i]['login'].')';
									
									$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>1,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
									
									$ba = '';
									$i6 = 0;
									while($i6<count($u->items['add'])) {
										if(isset($anl['add_'.$u->items['add'][$i6]])) {
											if($anl['add_'.$u->items['add'][$i6]] > 0) {
												$ba .= $u->is[$u->items['add'][$i6]].': +'.$anl['add_'.$u->items['add'][$i6]].', ';
											}
										}
										$i6++;
									}
									$ba = trim($ba,', ');
									if($ba == '') {
										$ba = 'Эффект отсутсвует';
									}
									
									$mas1['text'] = '{tm1} {u2} очнулся от медитации, и призвал заклятье &quot;<b>'.$tpa3[$a['type']].' ['.$a['level'].']</b>&quot; на {u1}. ('.$ba.')';
									$nxtlg[count($nxtlg)] = $mas1;
									mysql_query('UPDATE `users_animal` SET `eda` = `eda` - 1 WHERE `id` = "'.$a['id'].'" LIMIT 1');
									//$this->add_log($mas1);
									$this->get_comment();
								}else{
									$u->send('',$this->users[$i]['room'],$this->users[$i]['city'],'',$this->users[$i]['login'],'<b>'.$a['name'].'</b> нуждается в еде...',time(),6,0,0,0,1);
								}
							}
						}
												
						mysql_query('UPDATE `stats` SET `last_hp` = "0",`tactic1`="0",`tactic2`="0",`tactic3`="0",`tactic4`="0",`tactic5`="0",`tactic6`="0",`tactic7` = "'.($this->users[$i]['tactic7']).'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');

						$rs[$tsi] .= $u->microLogin($this->users[$i],2).', ';
					}
					$up .= '`uid` = "'.$pl['id'].'" OR';
					$i++;
				}
				
				/*
				if($i == 0) {
					$t = mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'" AND `st`.`hpNow` > 0');
					$i = 0; $bi = 0; $up = '';
					while($pl = mysql_fetch_array($t))
					{
						//записываем данные
						if($pl['login2']=='')
						{
							$pl['login2'] = $pl['login'];
						}
						$this->users[$i] = $pl;
						$this->uids[$pl['id']] = $i;
						if($pl['bot']>0)
						{
							$this->bots[$bi] = $pl['id'];
							$this->iBots[$pl['id']] = $bi;
							$bi++;
						}
						//записываем статы
						$this->stats[$i] = $u->getStats($pl,0);
						//Заносим старт
						if($this->info['start1']==0)
						{
							if(!isset($ts[$this->users[$i]['team']]))
							{
								$tsi++;
								$ts[$this->users[$i]['team']] = $tsi;
							}
							
							if($this->users[$i]['level']<=7)
							{
								$this->users[$i]['tactic7'] = floor(10/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}elseif($this->users[$i]['level']==8)
							{
								$this->users[$i]['tactic7'] = floor(20/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}elseif($this->users[$i]['level']==9)
							{
								$this->users[$i]['tactic7'] = floor(30/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}elseif($this->users[$i]['level']>=10)
							{
								$this->users[$i]['tactic7'] = floor(40/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}
							
							$this->users[$i]['tactic7'] += $this->stats[$i]['s7'];
							
							mysql_query('UPDATE `stats` SET `tactic1`="0",`tactic2`="0",`tactic3`="0",`tactic4`="0",`tactic5`="0",`tactic6`="0",`tactic7`="0",`tactic7` = "'.($this->users[$i]['tactic7']).'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							
							$rs[$tsi] .= $u->microLogin($this->users[$i],2).', ';
						}
						$up .= '`uid` = "'.$pl['id'].'" OR';
						$i++;
					}
				}
				*/
				
				$up = rtrim($up,' OR');
				//mysql_query('UPDATE `eff_users` SET `timeAce` = "0" WHERE ('.$up.') AND `delete` = "0"');
				//echo '<hr><hr><hr>';
				
				//Заносим в лог начало поединка
				
				if($this->info['start1']==0)
				{
					$tststrt = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `start1` = "0" LIMIT 1'));
					if(isset($tststrt['id'])) {
						$upd = mysql_query('UPDATE `battle` SET `start1` = "'.time().'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						if($upd)
						{
							$i = 0; $r = '';
							while($i<=$tsi)
							{
								if(isset($rs[$i]) && $rs[$i] != '') {
									$r .= rtrim($rs[$i],', ').' и ';
								}
								$i++;
							}
							$r = rtrim($r,' и ');
							$r = str_replace('"','\\\\\"',$r);
							$this->hodID++;
							$vLog = 'time1='.time().'||';
							$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
							$r = 'Часы показывали <span class=\\\\\"date\\\\\">'.date('d.m.Y H:i',$this->info['time_start']).'</span>, когда '.$r.' бросили вызов друг другу.';
							$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$r.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
							if(!$ins)
							{
								//echo $r;
							}
							$this->info['start1'] = time();
						}
					}
					
					//
					
					if(count($nxtlg) > 0) {
						$i = 0;
						while($i < count($nxtlg)) {
							$this->add_log($nxtlg[$i]);
							$i++;
						}
					}
					
					//
				}
			}
		}
	
	//Возращаем зоны блока по умолчанию
		public function restZonb($uid1,$uid2)
		{
			if($this->stnZbVs[$uid1]>0)
			{
				$this->stats[$this->uids[$uid1]]['zonb'] = $this->stnZbVs[$uid1];
			}
			if($this->stnZbVs[$uid2]>0)
			{
				$this->stats[$this->uids[$uid1]]['zonb'] = $this->stnZbVs[$uid2];
			}
		}
	
	//проверка блока (Визуальная)
		public function testZonbVis()
		{
			global $u;
			if($this->stnZbVs==0)
			{
				$zb = $this->stats[$this->uids[$u->info['id']]]['zonb'];
				$this->stnZbVs = $zb;
			}else{
				$zb = $this->stnZb;
			}
			$eu = $this->users[$this->uids[$u->info['id']]]['enemy'];
			if($zb>3){ $zb = 3; }
			if($eu!='' && $eu!=0)
			{
				if($this->stats[$this->uids[$eu]]['weapon1']==1 || $this->stats[$this->uids[$eu]]['weapon2']==1)
				{
					if($this->stats[$this->uids[$u->info['id']]]['weapon1']!=1 && $this->stats[$this->uids[$u->info['id']]]['weapon2']!=1)
					{
						$zb -= 1;		
					}				
				}
			}
			if($zb<1){ $zb = 1; }
			return $zb;
		}
		
	//проверка блока
		public function testZonb($uid,$uid2)
		{
			global $u;
			$zba = array(1=>0,2=>0);
			
			$zba[1] = $this->stats[$this->uids[$uid]]['zonb'];
			$zba[2] = $this->stats[$this->uids[$uid2]]['zonb'];
			
			if($this->stnZb[$uid]==0)
			{
				$zba[1] = $this->stats[$this->uids[$uid]]['zonb'];
				$this->stnZb[$uid] = $zba[1];
			}else{
				$zba[1] = $this->stnZb[$uid];
			}
			
			if($this->stnZb[$uid2]==0)
			{
				$zba[2] = $this->stats[$this->uids[$uid2]]['zonb'];
				$this->stnZb[$uid] = $zba[2];
			}else{
				$zba[2] = $this->stnZb[$uid2];
			}
			
			if($zba[1]>3){ $zba[1] = 3; }
			if($zba[2]>3){ $zba[2] = 3; }
			
			//Блоки игрока 1
			if($this->stats[$this->uids[$uid2]]['weapon1']==1 || $this->stats[$this->uids[$uid2]]['weapon2']==1)
			{
				if($this->stats[$this->uids[$uid]]['weapon1']!=1 && $this->stats[$this->uids[$uid]]['weapon2']!=1)
				{
					$zba[1] -= 1;		
				}				
			}
			
			//Блоки игрока 2
			if($this->stats[$this->uids[$uid]]['weapon1']==1 || $this->stats[$this->uids[$uid]]['weapon2']==1)
			{
				if($this->stats[$this->uids[$uid2]]['weapon1']!=1 && $this->stats[$this->uids[$uid2]]['weapon2']!=1)
				{
					$zba[2] -= 1;		
				}				
			}			
			
			if($zba[1]<1){ $zba[1] = 1; }
			if($zba[2]<1){ $zba[2] = 1; }
						
			$this->stats[$this->uids[$uid]]['zonb'] = $zba[1];
			$this->stats[$this->uids[$uid2]]['zonb'] = $zba[2];
			if($this->stats[$this->uids[$uid]]['min_zonb']>0 && $this->stats[$this->uids[$uid]]['zonb']<$this->stats[$this->uids[$uid]]['min_zonb'])
			{
				$this->stats[$this->uids[$uid]]['zonb'] = $this->stats[$this->uids[$uid]]['min_zonb'];
			}
			if($this->stats[$this->uids[$uid2]]['min_zonb']>0 && $this->stats[$this->uids[$uid2]]['zonb']<$this->stats[$this->uids[$uid2]]['min_zonb'])
			{
				$this->stats[$this->uids[$uid2]]['zonb'] = $this->stats[$this->uids[$uid2]]['min_zonb'];
			}	
		}
	
	//генерируем команды
		public function genTeams($you)
		{
			$ret = '';
			$teams = array( );
			//выделяем пользователей
			$i = 0;	 $j = 1; $tms =	array( );
			//if( $this->users[$this->uids[$you]]['team'] > 0 && $this->stats[$this->uids[$you]]['hpNow'] > 0 ) {
				$teams[$this->users[$this->uids[$you]]['team']] = '';
				$tms[0] = $this->users[$this->uids[$you]]['team'];
			//}
			while($i<count($this->uids))
			{
				if($this->stats[$i]['hpNow']>0)
				{
					if(!isset($teams[$this->users[$i]['team']]))
					{
						$tms[$j] = $this->users[$i]['team'];
						$j++;
					}	
					if($this->stats[$i]['hpNow']<0){ $this->stats[$i]['hpNow'] = 0; }
					$a1ms = '';
					if(isset($this->ga[$this->users[$i]['id']][$you]) && $this->ga[$this->users[$i]['id']][$you]!=false)
					{
						$a1mc = '';
						$ac = mysql_fetch_array(mysql_query('SELECT * FROM `battle_act` WHERE `id` = "'.$this->ga[$this->users[$i]['id']][$you].'" LIMIT 1'));
						if(isset($ac) && $ac['time']+$this->info['timeout']-15<time())
						{
							$a1mc = 'color:red;';
						}
						$a1ms = 'style=\"text-decoration: underline; '.$a1mc.'\"';
					}		
					if($this->users[$i]['login2']=='')
					{
						$this->users[$i]['login2'] = $this->users[$i]['login'];
					}
					$teams[$this->users[$i]['team']] .= ', <span '.$a1ms.' class=\"CSSteam'.$this->users[$i]['team'].'\" onClick=\"top.chat.addto(\''.$this->users[$i]['login2'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$this->users[$i]['login2'].'\',event,\'main\'); return false;\">'.$this->users[$i]['login2'].'</span><small> ['.floor($this->stats[$i]['hpNow']).'/'.$this->stats[$i]['hpAll'].']</small>';
				}
				$i++;				
			}
			
			//генерируем команды
			$i = 0;
			while($i<count($tms))
			{
				$teams[$tms[$i]] = ltrim($teams[$tms[$i]],', ');
				if( $teams[$tms[$i]] != '' ) {
					if($u->info['team'] == $tms[$i]) {
						$teams[$tms[$i]] = '<img src=\"http://img.xcombats.com/i/lock3.gif\" style=\"cursor:pointer\" width=\"20\" height=\"15\" onClick=\"top.chat.addto(\'team'.$tms[$i].'\',\'private\'); return false;\"> '.$teams[$tms[$i]];
					}else{
						$teams[$tms[$i]] = '<img src=\"http://img.xcombats.com/i/lock3.gif\" style=\"cursor:pointer\" width=\"20\" height=\"15\" onClick=\"top.chat.addto(\'team\',\'private\'); return false;\"> '.$teams[$tms[$i]];
					}
					$ret .= $teams[$tms[$i]];				
					if(count($tms)>$i+1)
					{
						$ret .= ' <span class=\"CSSvs\">&nbsp; против &nbsp;</span> ';
					}
				}
				$i++;
			}
			return 'genteam("'.$ret.'");';
		}
		
		
		public function addTravm($uid,$type,$lvl)
		{
		    global $u;
			$t=$type;
		    if($t==1){
			        $name='Легкая травма';
			        $stat=rand(1, 3); // пока без духовности
					$timeEnd=rand(5400,21600);// время травмы от 1.30 до 6 часов
					$data='add_s'.$stat.'=-'.$lvl;
					$img = 'eff_travma1.gif';
					$v1=1;
					//echo '<b><font color=red>'.$name.'</font></b>';
			}elseif($t==2){
			        $name='Средняя травма';
			        $stat=rand(1, 3); // пока без духовности
					$timeEnd=rand(21600,43200);// время травмы от 6 до 12 часов
					$data='add_s'.$stat.'=-'.($lvl*2);
					$v1=2;
					$img = 'eff_travma2.gif';
			}
			elseif($t==3){
			        $name='Тяжелая травма';
			        $stat=rand(1, 3); // пока без духовности
					$timeEnd=rand(43200,86400);// время травмы от 12 до 6 часов
					$data='add_s'.$stat.'=-'.($lvl*3);
					$v1=3;
					$img = 'eff_travma3.gif';
			}
			$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES ("0","'.time().'","-1","'.$name.'","'.$data.'","'.$uid.'", "4", "'.$img.'","'.$timeEnd.'", "'.$v1.'")');
			$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES ("0","'.time().'","-1","Иммунитет: Защита от травм","add_notravma=1","'.$uid.'", "263", "cure1.gif","21600", "")');
		}
}

$btl = new battleClass;
?>