<?php
if(!defined('GAME'))
{
	die();
}

class dungeon
{
	public $bs,$info,$see,$error,$gs = 1,$map = array(
				0 => array() //карта
			)	,$id_dng,$cord = array('x' => 0),$sg = array(1 => array(1=>1,2=>2,3=>3,4=>4),2 => array(1=>2,2=>3,3=>4,4=>1),3 => array(1=>3,2=>4,3=>1,4=>2),4 => array(1=>4,2=>1,3=>2,4=>3));
	public function start()
	{
		global $u,$c,$code,$id;
		$this->info = array(
			'id2'	=> $id,
			'id'	=> 0,
			'name'	=> 'Редактирование Пещеры',
			'room'	=> 0,
			'time_start'	=> time(),
			'time_start'	=> 0,
			'uid'	=> 0,
			'type'	=> 0,
			'bsid'	=> 0
		);
		
		$this->id_dng = $this->info['id2'];
		$this->gs = $u->info['psevdo_s'];	
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
				if($this->bs['users']-$this->bs['users_finish'] < 2)
				{
					$u->bsfinish($this->bs,false,NULL);
				}
			}
		}
		
		/* генерируем вид персонажа (только карта)
			$this->gs = 1; //смотрим прямо
						2; //смотрим лево
						3; //смотрим вниз
						4; //смотрим право
						( ( ( `y` >= '.$u->info['psevdo_y'].' && `y` <= '.($u->info['psevdo_y']+4).' ) && ( `x` >= '.($u->info['psevdo_x']-1).' && `x` <= '.($u->info['psevdo_x']+1).' ) ) || ( (`x` = '.($u->info['psevdo_x']+2).' || `x` = '.($u->info['psevdo_x']-2).') && ( `y` = '.($u->info['psevdo_y']+3).' || `y` = '.($u->info['psevdo_y']+4).' ) ) )
		*/
		
		$whr = array(
					1 => ' ((`x` <= '.($u->info['psevdo_x']+2).' && `x` >= '.($u->info['psevdo_x']-2).') && (`y` >= '.$u->info['psevdo_y'].' && `y` <= '.($u->info['psevdo_y']+4).')) ', //прямо 
					3 => ' ((`x` <= '.($u->info['psevdo_x']+2).' && `x` >= '.($u->info['psevdo_x']-2).') && (`y` <= '.$u->info['psevdo_y'].' && `y` >= '.($u->info['psevdo_y']-4).')) ', //вниз
					2 => ' ((`x` <= '.$u->info['psevdo_x'].' && `x` >= '.($u->info['psevdo_x']-4).') && (`y` <= '.($u->info['psevdo_y']+2).' && `y` >= '.($u->info['psevdo_y']-2).')) ', //лево				
					4 => ' ((`x` >= '.$u->info['psevdo_x'].' && `x` <= '.($u->info['psevdo_x']+4).') && (`y` <= '.($u->info['psevdo_y']+2).' && `y` >= '.($u->info['psevdo_y']-2).')) ' //право
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
	
	public function usersDng()
	{
		global $u,$c;
		$r = '';
		/* отображение ботов (бесполезно в данном редакторе) */
	}
		
	public function atack($id)
	{
		global $u,$c,$code;
		/* нападение, какое нападение может быть в редакторе? :) На самого себя, со стулом? :) */
	}
	
	public function takeinv($id)
	{
		global $u,$c,$code;
		/* Это редактор, а не майнкрафт, здесь ничего собирать не нужно :) */
	}
	
	public function takeit($id)
	{
		global $u,$c,$code,$magic;
		/* В редакторе ничего не падает ;) */
	}
	
	public function addItem($i)
	{
		//добавляем предмет в пещеру (возможно выпал из бота или из сундука)
		/* А что еще? Пульт к управлению Землей?! */
	}
	
	public function itemsMap()
	{
		global $u,$c,$code;
		/* Да ничего здесь не падает! Говорю же! */
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
		/*
		$whr = array(
			1 => ' ((`u`.`x` <= '.($u->info['psevdo_x']+2).' && `u`.`x` >= '.($u->info['psevdo_x']-2).') && (`u`.`y` >= '.($u->info['psevdo_y']+1).' && `u`.`y` <= '.($u->info['psevdo_y']+4).')) ', //прямо 
			3 => ' ((`u`.`x` <= '.($u->info['psevdo_x']+2).' && `u`.`x` >= '.($u->info['psevdo_x']-2).') && (`u`.`y` <= '.($u->info['psevdo_y']-1).' && `u`.`y` >= '.($u->info['psevdo_y']-4).')) ', //вниз
			2 => ' ((`u`.`x` <= '.($u->info['psevdo_x']-1).' && `u`.`x` >= '.($u->info['psevdo_x']-4).') && (`u`.`y` <= '.($u->info['psevdo_y']+2).' && `u`.`y` >= '.($u->info['psevdo_y']-2).')) ', //лево				
			4 => ' ((`u`.`x` >= '.($u->info['psevdo_x']+1).' && `u`.`x` <= '.($u->info['psevdo_x']+4).') && (`u`.`y` <= '.($u->info['psevdo_y']+2).' && `u`.`y` >= '.($u->info['psevdo_y']-2).')) ' //право
		);
		*/
		$whr = array(
			1 => ' (((`u`.`x` <= '.($u->info['psevdo_x']+2).' && `u`.`x` >= '.($u->info['psevdo_x']-2).') && (`u`.`y` >= '.($u->info['psevdo_y']+1).' && `u`.`y` <= '.($u->info['psevdo_y']+4).')) OR (`u`.`y` = '.$u->info['psevdo_y'].' && `u`.`x` = '.$u->info['psevdo_x'].')) ', //прямо 
			3 => ' (((`u`.`x` <= '.($u->info['psevdo_x']+2).' && `u`.`x` >= '.($u->info['psevdo_x']-2).') && (`u`.`y` <= '.($u->info['psevdo_y']-1).' && `u`.`y` >= '.($u->info['psevdo_y']-4).')) OR (`u`.`y` = '.$u->info['psevdo_y'].' && `u`.`x` = '.$u->info['psevdo_x'].')) ', //вниз
			2 => ' (((`u`.`x` <= '.($u->info['psevdo_x']-1).' && `u`.`x` >= '.($u->info['psevdo_x']-4).') && (`u`.`y` <= '.($u->info['psevdo_y']+2).' && `u`.`y` >= '.($u->info['psevdo_y']-2).'))OR (`u`.`y` = '.$u->info['psevdo_y'].' && `u`.`x` = '.$u->info['psevdo_x'].')) ', //лево				
			4 => ' (((`u`.`x` >= '.($u->info['psevdo_x']+1).' && `u`.`x` <= '.($u->info['psevdo_x']+4).') && (`u`.`y` <= '.($u->info['psevdo_y']+2).' && `u`.`y` >= '.($u->info['psevdo_y']-2).')) OR (`u`.`y` = '.$u->info['psevdo_y'].' && `u`.`x` = '.$u->info['psevdo_x'].')) ' //право
		); 
		$sp = mysql_query('SELECT `u`.* FROM `dungeon_obj` AS `u` WHERE `u`.`dn` = "0" AND `u`.`for_dn` = "'.$this->id_dng.'" AND ((`u`.`s` = "0" OR `u`.`s` = "'.$this->gs.'") OR `u`.`s2` = "'.$this->gs.'") AND '.$whr[$this->gs].' LIMIT 76');
		# die('SELECT `u`.* FROM `dungeon_obj` AS `u` WHERE `u`.`dn` = "0" AND `u`.`for_dn` = "'.$this->id_dng.'" AND ((`u`.`s` = "0" OR `u`.`s` = "'.$this->gs.'") OR `u`.`s2` = "'.$this->gs.'") AND '.$whr[$this->gs].' LIMIT 76');
		$i = 0; $pos = array();
		while($pl = mysql_fetch_array($sp)) {
			
			if($pl['fix_x_y'] == 0 ||
			  ($pl['fix_x_y'] == 1 && $pl['x'] == $u->info['psevdo_x']) ||
			  ($pl['fix_x_y'] == 2 && $pl['y'] == $u->info['psevdo_y']) ||
			  ($pl['fix_x_y'] == 3 && $pl['x'] == $u->info['psevdo_x'] && $pl['y'] == $u->info['psevdo_y'])) {
				if(
					(
						$pl['os1']== 0 && $pl['os2']==0 && $pl['os3']==0 && $pl['os4']==0
					) ||
					(
						$this->cord[$pl['y'].'_'.$pl['x']] == $pl['os1'] || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os2']
						||
						$this->cord[$pl['y'].'_'.$pl['x']] == $pl['os3'] || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os4']
					)
				) { 
					$i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
					$r .= ','.($i-1).':{\'x\':'.$pl['x'].',\'y\':'.$pl['y'].',0:'.$pl['id'].',1:\''.$pl['name'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:\'action\',4:\''.$pl['img'].'\',5:'.$pl['type'].',6:0,7:'.$pl['w'].',8:'.$pl['h'].',9:'.$pl['left'].',10:'.$pl['top'].',11:'.$pl['date'].',12:'.$pl['type'].',13:'.$pl['type2'].',14:'.$pl['s'].',15:'.$pl['s2'].',16:'.$pl['os1'].',17:'.$pl['os2'].',18:'.$pl['os3'].',19:'.$pl['os4'].',20:'.$pl['fix_x_y'].'}';
				} elseif( $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os1']-1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os2']-1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os3']-1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os4']-1 ) {
					$dt2 = explode(',',ltrim(rtrim($pl['date'],'\}'),'\{'));
					$da = array();
					$is = 0;
					while($is < count($dt2)) {
						$dt2[$is] = explode(':',$dt2[$is]);
						$da[$dt2[$is][0]] = $dt2[$is][1];
						$is++;
					}
					#if(isset($da['rl2']))$da['rl2'] = -round((int)$da['rl2'] * 0.70); // Слева
					if(isset($da['rl2']))$da['rl2'] = round((int)$da['rl2'] -230); // Слева
					if(isset($da['rl3']))$da['rl3'] = round((int)$da['rl3'] +160);
					if(isset($da['rl4']))$da['rl4'] = round((int)$da['rl4'] -120);
					$pl['date'] = str_replace('"', '', json_encode($da));
					$i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
					$r .= ','.($i-1).':{\'x\':'.$pl['x'].',\'y\':'.$pl['y'].',0:'.$pl['id'].',1:\''.$pl['name'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:\'action\',4:\''.$pl['img'].'\',5:'.$pl['type'].',6:0,7:'.$pl['w'].',8:'.$pl['h'].',9:'.$pl['left'].',10:'.$pl['top'].',11:'.$pl['date'].',12:'.$pl['type'].',13:'.$pl['type2'].',14:'.$pl['s'].',15:'.$pl['s2'].',16:'.$pl['os1'].',17:'.$pl['os2'].',18:'.$pl['os3'].',19:'.$pl['os4'].',20:'.$pl['fix_x_y'].'}';
				} else if( $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os1']+1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os2']+1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os3']+1 || $this->cord[$pl['y'].'_'.$pl['x']] == $pl['os4']+1 ) {
					
					$dt2 = explode(',',ltrim(rtrim($pl['date'],'\}'),'\{'));
					$da = array();
					$is = 0;
					while($is < count($dt2)) {
						$dt2[$is] = explode(':',$dt2[$is]);
						$da[$dt2[$is][0]] = $dt2[$is][1];
						$is++;
					} 
					#if(isset($da['rl2']))$da['rl2'] = 355-round((int)$da['rl2'] * 0.30); // Справа
					if(isset($da['rl2']))$da['rl2'] = round((int)$da['rl2'] +230); // Справа
					if(isset($da['rl3']))$da['rl3'] = round((int)$da['rl3'] -160);
					if(isset($da['rl4']))$da['rl4'] = round((int)$da['rl4'] +120);
					$pl['date'] = str_replace('"', '', json_encode($da));
					$i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
					$r .= ','.($i-1).':{\'x\':'.$pl['x'].',\'y\':'.$pl['y'].',0:'.$pl['id'].',1:\''.$pl['name'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:\'action\',4:\''.$pl['img'].'\',5:'.$pl['type'].',6:0,7:'.$pl['w'].',8:'.$pl['h'].',9:'.$pl['left'].',10:'.$pl['top'].',11:'.$pl['date'].',12:'.$pl['type'].',13:'.$pl['type2'].',14:'.$pl['s'].',15:'.$pl['s2'].',16:'.$pl['os1'].',17:'.$pl['os2'].',18:'.$pl['os3'].',19:'.$pl['os4'].',20:'.$pl['fix_x_y'].'}';
				}
			}
		}
		$r = 'count:'.$i.$r;
		return $r;
	}
	
	public function genUsers(){
		global $u,$c,$code;
		////i:{id,login,mapPoint,sex,obraz,type,users_p},
		//'count':1,0:{0:1015,1:'Test1',2:5,3:0,4:'1',5:'user',6:1},
		$r = '';
		$whr = array(
			1 => ' ((`u`.`x` <= '.($u->info['psevdo_x']+2).' && `u`.`x` >= '.($u->info['psevdo_x']-2).') && (`u`.`y` >= '.$u->info['psevdo_y'].' && `u`.`y` <= '.($u->info['psevdo_y']+4).')) ', //прямо 
			3 => ' ((`u`.`x` <= '.($u->info['psevdo_x']+2).' && `u`.`x` >= '.($u->info['psevdo_x']-2).') && (`u`.`y` <= '.$u->info['psevdo_y'].' && `u`.`y` >= '.($u->info['psevdo_y']-4).')) ', //вниз
			2 => ' ((`u`.`x` <= '.$u->info['psevdo_x'].' && `u`.`x` >= '.($u->info['psevdo_x']-4).') && (`u`.`y` <= '.($u->info['psevdo_y']+2).' && `u`.`y` >= '.($u->info['psevdo_y']-2).')) ', //лево				
			4 => ' ((`u`.`x` >= '.$u->info['psevdo_x'].' && `u`.`x` <= '.($u->info['psevdo_x']+4).') && (`u`.`y` <= '.($u->info['psevdo_y']+2).' && `u`.`y` >= '.($u->info['psevdo_y']-2).')) ' //право
		);
		$i = 0;
		//отображаем ботов
		$sp = mysql_query('SELECT `u`.*,`st`.* FROM `dungeon_bots` AS `u` LEFT JOIN `test_bot` AS `st` ON (`u`.`id_bot` = `st`.`id`) WHERE '.$whr[$this->gs].' AND `dn` = "0" AND `for_dn` = "'.$this->id_dng.'" AND `u`.`delete` = "0" LIMIT 50');
		while($pl = mysql_fetch_array($sp)){
			$i++; if(!isset($pos[$this->cord[$pl['y'].'_'.$pl['x']]])){ $pos[$this->cord[$pl['y'].'_'.$pl['x']]] = 0; } $pos[$this->cord[$pl['y'].'_'.$pl['x']]]++;
			$dlg = 0;
			if($pl['dialog']>0){
				$dlg = $pl['dialog'];	
			}
			$r .= ','.($i-1).':{0:'.$pl['id2'].',1:\''.$pl['login'].'\',2:'.(0+$this->cord[$pl['y'].'_'.$pl['x']]).',3:'.$pl['sex'].',4:\''.str_replace('.gif','',$pl['obraz']).'\',5:\'bot\',6:'.$pos[$this->cord[$pl['y'].'_'.$pl['x']]].',7:'.$dlg.'}';
		}
		$r = 'count:'.$i.$r;
		//$wd = $this->cord['2_0'];
		return $r;
	}
	
	public function botAtack($bot,$uid,$bs) {
		global $u,$c,$code;
		$user = mysql_fetch_array(mysql_query('SELECT `id`,`battle` FROM `users` WHERE `id` = "'.$uid['id'].'" LIMIT 1'));
		if($user['battle']>0){
			$btli = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$user['battle'].'" AND `team_win` = "-1" LIMIT 1'));
		}
		if(!isset($btli['id'])){
			//Создаем поединок
				$btl_id = 0;
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
					
					if($btl_id>0){
						//Добавляем ботов
						$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "0" AND `dn` = "'.$this->info['id'].'" AND `x` = "'.$bot['x'].'" AND `y` = "'.$bot['y'].'" LIMIT 50');
						$j = 0; $logins_bot = array();
						while($pl = mysql_fetch_array($sp)){
							mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "'.$btl_id.'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							$jui = 1;
							while($jui<=$pl['colvo']){
								$k = $u->addNewbot($pl['id_bot'],NULL,NULL,$logins_bot);
								$logins_bot = $k['logins_bot'];
								if($k!=false) {
									$upd = mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
									if($upd){
										$upd = mysql_query('UPDATE `stats` SET `x`="'.$bot['x'].'",`y`="'.$bot['y'].'",`team` = "1" WHERE `id` = "'.$k['id'].'" LIMIT 1');
										if($upd){
											$j++;
										}
									}
								}
								$jui++;
							}
						}						
						unset($logins_bot);
						if($j>0)
						{
							mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$user['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$user['id'].'" LIMIT 1');
						}
					}
		}else{
			//Вмешиваемся в поединок
			
		}
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
		$thp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['psevdo_x'].'" AND `y` = "'.$u->info['psevdo_y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
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
		
		$tbot = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `x` = "'.($u->info['psevdo_x']+(int)$tgo[0]).'" AND `y` = "'.($u->info['psevdo_y']+(int)$tgo[1]).'" AND `dn` = "'.$this->info['id'].'" AND `for_dn` = "0" AND `delete` = "0" LIMIT 1'));
		if(isset($tbot['id2']) && $u->info['admin']==0)
		{
			$go = 0;	
		}
		
		$tmap = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['psevdo_x'].'" AND `y` = "'.$u->info['psevdo_y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
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
				$u->info['psevdo_y'] += 1;
			}elseif($go==2)
			{
				$u->info['psevdo_x'] -= 1;
			}elseif($go==3)
			{
				$u->info['psevdo_y'] -= 1;
			}elseif($go==4)
			{
				$u->info['psevdo_x'] += 1;
			}
			$u->info['timeGo'] = time()+$tmap['timeGO'];			
			$u->info['timeGoL'] = time();
			$upd = mysql_query('UPDATE `stats` SET `x` = "'.$u->info['psevdo_x'].'",`y` = "'.$u->info['psevdo_y'].'",`timeGo` = "'.$u->info['timeGo'].'",`timeGoL` = "'.$u->info['timeGoL'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
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
		$thp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['psevdo_x'].'" AND `y` = "'.$u->info['psevdo_y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
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
		
		$tbot = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `x` = "'.($u->info['psevdo_x']+(int)$tgo[0]).'" AND `y` = "'.($u->info['psevdo_y']+(int)$tgo[1]).'" AND `dn` = "'.$this->info['id'].'" AND `for_dn` = "0" AND `delete` = "0" LIMIT 1'));
		if(isset($tbot['id2']) && $u->info['admin']==0)
		{
			$go = 0;	
		}
		
		$tmap = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `x` = "'.$u->info['psevdo_x'].'" AND `y` = "'.$u->info['psevdo_y'].'" AND `id_dng` = "'.$this->info['id2'].'" LIMIT 1'));
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
		if($this->testSt(2,4)>0 || $this->testSt(3,2)>0){ $pd[28] = 1; }
		if($this->testSt(1,4)>0 || $this->testSt(2,2)>0){ $pd[27] = 1; }
		if($this->testSt(2,1)>0 || $this->testSt(5,3)>0){ $pd[26] = 1; }
		if($this->testSt(3,1)>0 || $this->testSt(6,3)>0){ $pd[25] = 1; }
		if($this->testSt(1,1)>0 || $this->testSt(4,3)>0){ $pd[24] = 1; }
		
		/* LEVEL 2 */
		if($this->testSt(5,4)>0 || $this->testSt(6,2)>0){ $pd[23] = 1; }
		if($this->testSt(4,4)>0 || $this->testSt(5,2)>0){ $pd[22] = 1; }
		if($this->testSt(5,1)>0 || $this->testSt(8,3)>0){ $pd[21] = 1; }
		if($this->testSt(6,1)>0 || $this->testSt(7,3)>0){ $pd[20] = 1; }
		if($this->testSt(4,1)>0 || $this->testSt(9,3)>0){ $pd[19] = 1; }
		
		/* LEVEL 3 */
		if($this->testSt(8,4)>0 || $this->testSt(7,2)>0){ $pd[18] = 1; }
		if($this->testSt(9,4)>0 || $this->testSt(8,2)>0){ $pd[17] = 1; }
		if($this->testSt(8,1)>0 || $this->testSt(12,3)>0){ $pd[16] = 1; }
		if($this->testSt(7,1)>0 || $this->testSt(13,3)>0){ $pd[15] = 1; }
		if($this->testSt(9,1)>0 || $this->testSt(11,3)>0){ $pd[14] = 1; }
		
		/* LEVEL 4 */
		if($this->testSt(12,4)>0 || $this->testSt(13,2)>0){ $pd[13] = 1; }
		if($this->testSt(12,2)>0 || $this->testSt(11,4)>0){ $pd[12] = 1; }
		if($this->testSt(13,1)>0 || $this->testSt(17,3)>0){ $pd[11] = 1; } //8
		if($this->testSt(11,1)>0 || $this->testSt(16,3)>0){ $pd[10] = 1; } //7
		if($this->testSt(12,1)>0 || $this->testSt(15,3)>0){ $pd[9] = 1; }
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
		$this->cord[($u->info['psevdo_y']+$y).'_'.($u->info['psevdo_x']+$x)] = $this->cord['x'];
		return $this->map[0][($u->info['psevdo_y']+$y).'_'.($u->info['psevdo_x']+$x)];
	}
	
	public function genMatix()
	{
		$r = array();
		if($this->gs == 1)
		{
			//1; //смотрим прямо
			$r[1]  = $this->getMatrix(0,-1);
			$r[2]  = $this->getMatrix(0,0);
			$r[3]  = $this->getMatrix(0,1);
			$r[4]  = $this->getMatrix(1,-1);
			$r[5]  = $this->getMatrix(1,0);
			$r[6]  = $this->getMatrix(1,1);
			$r[7]  = $this->getMatrix(2,1);
			$r[8]  = $this->getMatrix(2,0);
			$r[9]  = $this->getMatrix(2,-1);
			$r[10] = $this->getMatrix(3,-2);
			$r[11] = $this->getMatrix(3,-1);
			$r[12] = $this->getMatrix(3,0);
			$r[13] = $this->getMatrix(3,1);
			$r[14] = $this->getMatrix(3,2);
			$r[15] = $this->getMatrix(4,0);
			$r[16] = $this->getMatrix(4,-1);
			$r[17] = $this->getMatrix(4,1);
			$r[18] = $this->getMatrix(4,2);
			$r[19] = $this->getMatrix(4,-2);
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