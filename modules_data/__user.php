<?
if(!defined('GAME'))
{
	die();
}

function getdr($s,$v,$d)
{
	global $u;
	$i = 0;
	while($i<count($v))
	{
		if(isset($v[$i]))
		{
			$s = str_replace('{'.$v[$i].'}',$d[$i],$s);
		}
		$i++;
	}
	$s = eval("return (".$s.");");
	return $s;
}

class user
{
	private static $flag_one;
	public $aves,$rep,$tfer,$error2 = '', $room = array(), $bank = array(), $mod_nm = array(
						0=>array(0=>''),
						1=>array('1'=>'Свет','1.1'=>'Паладин Поднебесья','1.4'=>'Таможенный Паладин','1.5'=>'Паладин Солнечной Улыбки','1.6'=>'Инквизитор','1.7'=>'Паладин Огненной Зари','1.75'=>'Паладин-Хранитель','1.9'=>'Паладин Неба','1.91'=>'Старший Паладин Неба','1.92'=>'Ветеран Ордена','1.99'=>'Верховный Паладин'),
						3=>array('3'=>'Тьма','3.01'=>'Тарман-Служитель','3.05'=>'Тарман-Надсмотрщик','3.06'=>'Каратель','3.07'=>'Тарман-Убийца','3.075'=>'Тарман-Хранитель','3.09'=>'Тарман-Палач','3.091'=>'Тарман-Владыка','3.092'=>'Ветеран Армады','3.99'=>'Тарман Патриарх')
						);
	public $btl_txt = '',$rgd = array(0=>0,1=>0),$error = '',$city_unid = array(1=>'capitalcity',2=>'angelscity',3=>'abandonedplain'),$city_id = array('capitalcity'=>1,'angelscity'=>2,'abandonedplain'=>3),$city_name = array('abandonedplain'=>'Abandoned Plain','capitalcity'=>'Capital city','angelscity'=>'Angels city'),$city_name2 = array('abandonedplain'=>'Abandonedplain','capitalcity'=>'Capitalcity','angelscity'=>'Angelscity'),$stats,$info,$map,$mapUsers,$is = array('hpAll'=>'Уровень жизни (HP)','mpAll'=>'Уровень маны','sex'=>'Пол','lvl'=>'Уровень','s1'=>'Сила','s2'=>'Ловкость','s3'=>'Интуиция','s4'=>'Выносливость','s5'=>'Интелект','s6'=>'Мудрость','s7'=>'Духовность','s8'=>'Воля','s9'=>'Свобода духа','s10'=>'Божественность','m1'=>'Мф. критического удара (%)','m2'=>'Мф. против критического удара (%)','m3'=>'Мф. мощности критического удара (%)','m4'=>'Мф. увертывания (%)','m5'=>'Мф. против увертывания (%)','m6'=>'Мф. контрудара (%)','m7'=>'Мф. парирования (%)','m8'=>'Мф. блока щитом (%)','m9'=>'Мф. пробоя брони (%)','m14'=>'Мф. абс. критического удара (%)','m15'=>'Мф. абс. увертывания (%)','m16'=>'Мф. абс. парирования (%)','m17'=>'Мф. абс. контрудара (%)','m18'=>'Мф. абс. блока щитом (%)','m19'=>'Мф. абс. магический промах (%)','m20'=>'Мф. удача (%)','a1'=>'Мастерство владения ножами, кинжалами','a2'=>'Мастерство владения топорами, секирами','a3'=>'Мастерство владения дубинами, молотами','a4'=>'Мастерство владения мечами','a5'=>'Мастерство владения магическими посохами','a6'=>'Мастерство владения луками','a7'=>'Мастерство владения арбалетами','aall'=>'Мастерство владения оружием','mall'=>'Мастерство владения магией стихий','m2all'=>'Мастерство владения магией','mg1'=>'Мастерство владения магией огня','mg2'=>'Мастерство владения магией воздуха','mg3'=>'Мастерство владения магией воды','mg4'=>'Мастерство владения магией земли','mg5'=>'Мастерство владения магией Света','mg6'=>'Мастерство владения магией Тьмы','mg7'=>'Мастерство владения серой магией','tj'=>'Тяжелая броня','lh'=>'Легкая броня','minAtack'=>'Минимальный урон','maxAtack'=>'Максимальный урон','m10'=>'Мф. мощности урона','m11'=>'Мф. мощности магии стихий','m11a'=>'Мф. мощности магии','pa1'=>'Мф. мощности колющего урона','pa2'=>'Мф. мощности рубящего урона','pa3'=>'Мф. мощности дробящий урона','pa4'=>'Мф. мощности режущий урона','pm1'=>'Мф. мощности магии огня','pm2'=>'Мф. мощности магии воздуха','pm3'=>'Мф. мощности магии воды','pm4'=>'Мф. мощности магии земли','pm5'=>'Мф. мощности магии Света','pm6'=>'Мф. мощности магии Тьмы','pm7'=>'Мф. мощности серой магии','za'=>'Защита от урона','zm'=>'Защита от магии стихий','zma'=>'Защита от магии','za1'=>'Защита от колющего урона','za2'=>'Защита от рубящего урона','za3'=>'Защита от дробящий урона','za4'=>'Защита от режущий урона','zm1'=>'Защита от магии огня','zm2'=>'Защита от магии воздуха','zm3'=>'Защита от магии воды','zm4'=>'Защита от магии земли','zm5'=>'Защита от магии Света','zm6'=>'Защита от магии Тьмы','zm7'=>'Защита от серой магии','pza'=>'Понижение защиты от урона','pzm'=>'Понижение защиты от магии','pza1'=>'Понижение защиты от колющего урона','silver'=>'Премиум','notravma'=>'Защита от травм','yron_min'=>'Минимальный урон','yron_max'=>'Максимальный урон','pza2'=>'Понижение защиты от рубящего урона','pza3'=>'Понижение защиты от дробящего урона','pza4'=>'Понижение защиты от режущего урона','pzm1'=>'Понижение защиты от магии огня','pzm2'=>'Понижение защиты от магии воздуха','pzm3'=>'Понижение защиты от магии воды','pzm4'=>'Понижение защиты от магии земли','pzm5'=>'Понижение защиты от магии Света','pzm6'=>'Понижение защиты от магии Тьмы','pzm7'=>'Понижение защиты от серой магии','speedhp'=>'Регенерация здоровья (НР)','speedmp'=>'Регенерация маны (МР)','tya1'=>'Колющие атаки','tya2'=>'Рубящие атаки','tya3'=>'Дробящие атаки','tya4'=>'Режущие атаки','tym1'=>'Огненные атаки','tym2'=>'Электрические атаки','tym3'=>'Ледяные атаки','tym4'=>'Земляные атаки','tym5'=>'Атаки Света','tym6'=>'Атаки Тьмы','tym7'=>'Серые атаки','min_use_mp'=>'Уменьшает расход маны','pog'=>'Поглощение урона','maxves'=>'Увеличивает рюкзак','bonusexp'=>'Увеличивает получаемый опыт');
	public $items = array(
					'tr'  => array('lvl','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','a1','a2','a3','a4','a5','a6','a7','mg1','mg2','mg3','mg4','mg5','mg6','mg7','mall','m2all','aall'),
					'add' => array('no_yv1','no_bl1','no_pr1','no_yv2','no_bl2','no_pr2','silver','pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','yron_min','yron_max','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack'),
					'sv' => array('pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','min_use_mp','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack','bonusexp')
					);
	
	public static function start()
	{
		if (!isset(self::$flag_one))
		{
			$c = __CLASS__;
			self::$flag_one = new $c();
		}
		return self::$flag_one;
	}
	
	public function lookKeys($m,$i)
	{
		$e = explode('|',$m);
		$r = array();
		while($i<count($e))
		{
			$j = explode('=',$e[$i]);
			$r[$i] = $j[0];
			$i++;	
		}
		return $r;
	}
	
	public function bsfinish($id,$bu,$di) {
		if($bu == true) {
			/* в этом бою проверяем юзеров */
			$i = 0;
			while($i < count($bu[$i])) {
				if($bu[$i]['lose'] > 0 || $bu[$i]['nich']) {
					mysql_query('UPDATE `users` SET `lose` = "'.$bu[$i]['lose'].'", `nick` = "'.$bu[$i]['nich'].'" WHERE `id` = "'.$bu[$i]['id'].'" LIMIT 1');
					mysql_query('UPDATE `bs_turnirs` SET `users_finish` = `users_finish` + 1 WHERE `id` = "'.$id['id'].'" LIMIT 1');
					/* удаляем юзера */
					if($bu['inBot'] == 0) {
						$pls1 = mysql_fetch_array(mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$id['id'].'" AND `finish` = "0" AND `time` = "'.$id['time_start'].'" AND `inBot` = "'.$bu[$i]['id'].'" LIMIT 1'));
						if(isset($pls1['id'])) {
							mysql_query('DELETE FROM `users` WHERE `id` = "'.$bu[$i]['id'].'" LIMIT 1');
							mysql_query('DELETE FROM `stats` WHERE `id` = "'.$bu[$i]['id'].'" LIMIT 1');
							
							//выкидываем предметы на землю
							$spi = mysql_query('SELECT `id`,`item_id` FROM `items_users` WHERE `uid` = "'.$bu[$i]['id'].'" LIMIT 500');
							$ins = '';
							while($pli = mysql_fetch_array($spi))
							{
								$ins .= '("'.$btl['dn_id'].'","'.$pli['item_id'].'","'.time().'","'.$btl['x'].'","'.$btl['y'].'"),'; 
							}
							$ins = rtrim($ins,',');
							mysql_query('INSERT INTO `dungeon_items` (`dn`,`item_id`,`time`,`x`,`y`) VALUES '.$ins.'');
							
							mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pls1['inBot'].'" LIMIT 1');	
							mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pls1['inBot'].'" LIMIT 1');	
							//upd
							mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `id` = "'.$pls1['id'].'" LIMIT 1');
							mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$pls1['uid'].'" LIMIT 1');	
						}
					}
					$id['users_finish']++;
				}
				$i++;
			}
		}
		if($id['users']-$id['users_finish'] < 2) {
			$win = array();
			$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$id['id'].'" AND `finish` = "0" AND `time` = "'.$id['time_start'].'" ORDER BY `money` DESC LIMIT 100');	
			while($pl = mysql_fetch_array($sp))
			{
				$ur = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser`,`lose`,`nich`,`win` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				$ub = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser`,`lose`,`nich`,`win` FROM `users` WHERE `id` = "'.$ur['inUser'].'" LIMIT 1'));
				if(isset($ur['id']) && isset($ub['id']))
				{
					if($ub['lose'] > 0 || $ub['nich'] > 0) {
						//выкидываем из БС
						
					}else{
						$win = $ub;
						$winr = $ur;
					}
				}
			}
			
			/* завершаем БС */
			if(isset($win['id']) && $win['lose'] == 0 && $win['nich'] == 0 && $win['id'] > 0) {
				//есть победитель
				$bsep = 0;
				if($winr['level'] < 6) {
					$bsep = 2500;
				}elseif($winr['level'] < 7) {
					$bsep = 5000;
				}elseif($winr['level'] < 8) {
					$bsep = 15000;
				}elseif($winr['level'] < 9) {
					$bsep = 25000;
				}else{
					$bsep = 50000;
				}
				/* Выдаем приз */
				$mn = (round($id['money']/100*85));
				mysql_query('UPDATE `users` SET `money` = `money` + "'.$mn.'" WHERE `id` = "'.$winr['id'].'" LIMIT 1');
				mysql_query('UPDATE `stats` SET `exp` = `exp` + "'.$bsep.'" WHERE `id` = "'.$winr['id'].'" LIMIT 1');
				/* чат */
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$winr['login']."','Поздравляем! Вы победили в турнире &quot;Башня Смерти&quot;! Получено опыта: ".$bsep.", деньги: ".$mn." кр.','-1','6','0')");
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','','<font color=red>Внимание!</font> Завершился турнир &quot;Башня Смерти&quot;, победитель турнира: <b>".$winr['login']."</b>! Поздравляем!','-1','5','0')");
				$this->addDelo(1,$uid,'&quot;<font color=#C65F00>WinTournament.'.$this->info['city'].'</font>&quot; (Башня Смерти): Получено &quot;<b>'.$mn.'</b> кр.&quot;',time(),$this->info['city'],'WinTournament.'.$this->info['city'].'',0,0);
			}else{
				//нет победителя				
				//Выдаем травму
				
				/* чат */
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','','<font color=red>Внимание!</font> Завершился турнир &quot;Башня Смерти&quot;, победитель турнира: отсутствует.','-1','5','0')");
			}
			
			$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$id['id'].'" AND `time` = "'.$id['time_start'].'" ORDER BY `money` DESC LIMIT 100');	
			while($pl = mysql_fetch_array($sp))
			{
				$ur = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser`,`lose`,`nich`,`win` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				$ub = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser`,`lose`,`nich`,`win` FROM `users` WHERE `id` = "'.$ur['inUser'].'" LIMIT 1'));
				if(isset($ub['id']))
				{
					//del
					mysql_query('DELETE FROM `users` WHERE `id` = "'.$ub['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `stats` WHERE `id` = "'.$ub['id'].'" LIMIT 1');	
					mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$ub['id'].'" LIMIT 500');	
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$ub['id'].'" LIMIT 500');	
					//upd
					mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$pl['uid'].'" LIMIT 1');					
				}
			}			
		}
	}
	
	//вес предметов у юзера
	public function ves($u)
	{
		$r = array('now'=>0,'max'=>0);
		if($u==NULL)
		{
			//текущий персонаж
			$r['now'] = mysql_fetch_array(mysql_query('SELECT SUM(`im`.`massa`) FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `iu`.`item_id` = `im`.`id` WHERE `iu`.`uid` = "'.$this->info['id'].'" AND (`iu`.`delete` = "0" OR (`iu`.`delete` = "1000" AND `iu`.`inGroup` > 0)) AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0"'));
			$r['now'] = $r['now'][0];
			$r['max'] = 40+($this->stats['os7']*10)+$this->stats['s4']+$this->stats['maxves']+$this->stats['s1']*4;
			$r['items'] = mysql_fetch_array(mysql_query('SELECT COUNT(`im`.`id`) FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `iu`.`item_id` = `im`.`id` WHERE `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0"'));
			$r['items'] = $r['items'][0];
		}else{
			
		}
		return $r;
	}
	
	//Переплавка вещей \ рун
	public function plavka($id,$type)
	{
		$e = '';
		$rn = 0; $rnn = array();
		$pl = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1;'));
		$d = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$pl['item_id'].'" LIMIT 1'));
		$po = $this->lookStats($d['data']);
		$rlvl = 4;
		if($pl['level'] == 0) {
			$pl['level'] = 0+$po['tr_lvl'];
		}

		if($pl['level']==7 || $pl['level']==8){
			$rlvl = 7;
		}elseif($pl['level']==9 || $pl['level']==10){
			$rlvl = 9;
		}elseif($pl['level']==11){
			$rlvl = 9;
		}
		$rs = mysql_query('SELECT * FROM `items_main` WHERE `type` = 31 AND `level` = "'.$rlvl.'"');
		while($rl = mysql_fetch_array($rs))
		{
			$nm = explode(' ',$rl['name']);
			if($nm[0] && $nm[1]) {
				$rnn[count($rnn)] = $rl['id'];
			}
		}
		$rn = $rnn[rand(0,count($rnn)-1)];
		if($po['tr_lvl']<4 && $pl['item_id']!=1035)
		{
			$e = 'Предмет должен быть 4-го и выше уровня';
		}elseif($pl['type']!=1 && $pl['type']!=3 && $pl['type']!=5 && $pl['type']!=6 && $pl['type']!=8 && $pl['type']!=9 && $pl['type']!=10 && $pl['type']!=11 && $pl['type']!=12 && $pl['type']!=14 && $pl['type']!=15 && $pl['item_id']!=1035)
		{
			$e = 'Хрен вам, а не руны! Куда кидаешь предмет? Не подходит он! Приходи как будет что-то стоющее!';
		}elseif($po['tr_lvl']>=7 && $this->rep['rep1']<100)
		{
			$e = 'Для растворения предметов 7-го и старше уровня требуется знак Храма Знаний первого круга';
		}elseif($po['tr_lvl']>=9 && $this->rep['rep1']<1000)
		{
			$e = 'Для растворения предметов 9-го и старше уровня требуется знак Храма Знаний второго круга';
		}elseif($po['tr_lvl']>=11 && $this->rep['rep1']<10000)
		{
			$e = 'Для растворения предметов 11-го и старше уровня требуется знак Храма Знаний третьего круга';
		}elseif($rn>0)
		{
			if(isset($pl['id'],$d['id']))
			{
				$rnn = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `type` = "31" AND `id` = "'.$rn.'" LIMIT 1'));
				if(isset($rnn['id']))
				{
					$pl['rep'] = 1;
					if($this->rep['rep1']>99)
					{
						$pl['rep'] = 1;
					}elseif($this->rep['rep1']>999)
					{
						$pl['rep'] = 1;
					}elseif($this->rep['rep1']>9999)
					{
						$pl['rep'] = 1;
					}
					if($pl['item_id']==1035)
					{
						$pl['rep']++;
					}
					if(rand(0,10000) > 7000+round($this->rep['rep1']/6)) {
						$e = 'Предмет "'.$pl['name'].'" был растворен неудачно...';
						mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					}elseif(mysql_query('UPDATE `rep` SET `rep1` = `rep1` + "'.$pl['rep'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1'))
					{
						$e = 'Удачно растворен предмет "'.$pl['name'].'". Получена руна "'.$rnn['name'].'".';
						$this->addItem($rnn['id'],$this->info['id']);
						mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						$this->rep['rep1'] += $pl['rep'];
						mysql_query('UPDATE `rep` SET `rep1` = "'.$this->rep['rep1'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');						
					}else{
						$e = 'Не удалось расплавить предмет, котел переполнен ...';
					}
				}else{
					$e = 'Не удалось расплавить предмет ...';
				}
			}else{
				$e = 'Предмет не найден, либо не подходит '.$pl['item_id'].'...';
			}
		}else{
			$e = 'Не удалось переплавить, рецепты рун были потеряны ...';
		}
		return $e;
	}
	
	//Выводим вещи котоыре нужно отремонтировать
	public function info_remont()
	{
		$r = '';
		$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid` = "'.((int)$this->info['id']).'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` > "0" AND `iu`.`inOdet` < "18" LIMIT 18');
		while($pl = mysql_fetch_array($sp))
		{
			if($pl['iznosNOW']>ceil($pl['iznosMAX']*0.80))
			{
				$r .= '<b>'.$pl['name'].'</b> [<font color="brown">'.floor($pl['iznosNOW']).'/'.ceil($pl['iznosMAX']).'</font>] требуется ремонт<br>';
			}
		}
		$r = '<div align="left"><small>'.$r.'</small></div>';
		return $r;
	}
	
	//Расчет урона от оружия
	public function weaponAtc($item,$st,$x)
	{
		$tp = 0;
		if(isset($item['id']))
		{
			$itm = $this->lookStats($item['data']);				
			//начинаем расчет урона
			$min = $itm['sv_yron_min']+$st['minAtack']+$itm['yron_min'];
			$max = $itm['sv_yron_max']+$st['maxAtack']+$itm['yron_max'];
			if($x!=0)
			{
				//Тип урона: 0 - нет урона, 1 - колющий, 2 - рубящий, 3 - дробящий, 4 - режущий, 5 - огонь, 6 - воздух, 7 - вода, 8 - земля, 9 - свет, 10 - тьма, 11 - серая
				if($x==1)
				{
					//колющий
					$min = ceil($min+(($st['s1']*0.6+$st['s2']*0.4)*0.9));
					$max = ceil($max+(($st['s1']*0.6+$st['s2']*0.4)*0.9));
				}elseif($x==2)
				{
					//рубящий
					$min = ceil($min+($st['s1']*0.6+$st['s2']*0.2+$st['s3']*0.2));
					$max = ceil($max+($st['s1']*0.6+$st['s2']*0.2+$st['s3']*0.2));
				}elseif($x==3)
				{
					//дробящий
					$min = ceil($min+$st['s1']*1.2);
					$max = ceil($max+$st['s1']*1.2);
				}elseif($x==4)
				{
					//режущий
					$min = ceil($min+($st['s1']*0.6+$st['s3']*0.4)*0.9);
					$max = ceil($max+($st['s1']*0.6+$st['s3']*0.4)*0.9);
				}elseif($x>=5 && $x<=22)
				{
					//урон магии и магии стихий
						
				}else{
					//без профильного урона
					
				}
				
				//7% за каждую умелку
				$bn = 0;
				if($item['type'] == 21) {
					// меч
					$bn = $st['a4'];
				}elseif($item['type'] == 20) {
					// дубина
					$bn = $st['a3'];
				}elseif($item['type'] == 19) {
					// топор
					$bn = $st['a2'];
				}elseif($item['type'] == 18) {
					// нож
					$bn = $st['a1'];
				}elseif($item['type'] == 22) {
					// посох
					$bn = $st['a5'];					
				}
				$bn = $bn*7;
				
				$min += ceil($min/100*$bn);
				$max += ceil($max/100*$bn);
			}
			$tp = array(0=>$min,1=>$max);
		}
		return $tp;
	}
	
	public function inform($v)
	{
		//$this->stats['items'][13] , $this->stats['items'][14]
		$r = '';
		if($v=='yron')
		{
			$y = array();
			/* первое оружие или кулак */
			$w1 = 0;
			$w2 = 0;
			$i = 0;
			while($i<count($this->stats['items']))
			{
				if($this->stats['items'][$i]['inOdet']==3 && $w1==0 && $this->stats['items'][$i]['type'] >= 18 && $this->stats['items'][$i]['type'] <= 28)
				{
					$w1 = $this->stats['items'][$i];
				}elseif($this->stats['items'][$i]['inOdet']==14 && $w2==0 && $this->stats['items'][$i]['type'] >= 18 && $this->stats['items'][$i]['type'] <= 28)
				{
					$w2 = $this->stats['items'][$i];
				}
				$i++;
			}
			if(isset($w1['id']) && $w1['inOdet']!=14)
			{
				$tp = 0;
				$t = 0;
				$i = 1;
				$d = $this->lookStats($w1['data']);
				while($i<=4)
				{
					if($t<$d['tya'.$i])
					{
						$t = $d['tya'.$i];
						$tp = $i;
					}
					$i++;
				}
				$y = $this->weaponAtc($w1,$this->stats,$tp);
				$r .= '<span title="'.$w1['name'].'">'.$y[0].'-'.$y[1].'</span>';
			}else{
				//урон кулаком
				$y[0] = ceil($this->stats['s1']*1.4)+$this->stats['minAtack']+$this->stats['yron_min'];
				$y[1] = ceil(0.4+$y[0]/0.9)+$this->stats['maxAtack']+$this->stats['yron_max'];
				$r .= $y[0].'-'.$y[1];	
			}
			/* второе оружие */
			if(isset($w2['id']))
			{
				$tp = 0;
				$t = 0;
				$i = 1;
				$d = $this->lookStats($w2['data']);
				while($i<=4)
				{
					if($t<$d['tya'.$i])
					{
						$t = $d['tya'.$i];
						$tp = $i;
					}
					$i++;
				}
				$y = $this->weaponAtc($w2,$this->stats,$tp);
				$r .= ' / <span title="'.$w2['name'].'">'.$y[0].'-'.$y[1].'</span>';	
			}
		}else{
			//модификаторы
				$y = array();
				/* первое оружие или кулак */
				$w1 = 0;
				$w2 = 0;
				$i = 0;
				$ry = 0;
				while($i<count($this->stats['items']))
				{
					if($this->stats['items'][$i]['inOdet']==3 && $w1==0 && $this->stats['items'][$i]['type'] >= 18 && $this->stats['items'][$i]['type'] <= 28)
					{
						$w1 = $this->stats['items'][$i];
					}elseif($this->stats['items'][$i]['inOdet']==14 && $w2==0 && $this->stats['items'][$i]['type'] >= 18 && $this->stats['items'][$i]['type'] <= 28)
					{
						$w2 = $this->stats['items'][$i];
					}
					$i++;
				}
				if(isset($w1['id']) && $w1['inOdet']!=14)
				{
					$tp = 0;
					$t = 0;
					$i = 1;
					$d = $this->lookStats($w1['data']);
					$y = $this->stats[$v]+$d['sv_'.$v];
					$ry = $y;
					$r .= '<span title="'.$w1['name'].'">'.$y.'</span>';
				}else{
					//кулаком
					$r .= $this->stats[$v];	
					$ry = $this->stats[$v];
				}
				/* второе оружие */
				if(isset($w2['id']))
				{
					$tp = 0;
					$t = 0;
					$i = 1;
					$d = $this->lookStats($w2['data']);
					$y = $this->stats[$v]+$d['sv_'.$v];
					if($y!=$ry)
					{
						$r .= ' / <span title="'.$w2['name'].'">'.$y.'</span>';	
					}else{
						$r = str_replace('title="'.$w1['name'].'"','',$r);
					}
				}
			//модификаторы
		}
		return $r;
	}
	
	public function timeOut($ttm)
	{
	    $out = '';
		$time_still = $ttm;
		$tmp = floor($time_still/2592000);
		$id=0;
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." мес. ";}
			$time_still = $time_still-$tmp*2592000;
		}
		/*
		$tmp = floor($time_still/604800);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." нед. ";}
			$time_still = $time_still-$tmp*604800;
		}
		*/
		$tmp = floor($time_still/86400);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." дн. ";}
			$time_still = $time_still-$tmp*86400;
		}
		$tmp = floor($time_still/3600);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ч. ";}
			$time_still = $time_still-$tmp*3600;
		}
		$tmp = floor($time_still/60);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." мин. ";}
		}
		if($out=='')
		{
			if($time_still<0)
			{
				$time_still = 0;
			}
			$out = $time_still.' сек.';
		}
		return $out;
	}
	
	public function rep_zv($id,$e)
	{
		if($id==1)
		{
			//Храм знаний
			if($e>9999)
			{
				$r = 'Посвященный третьего круга, '.$e.' / ??';
			}elseif($e>999)
			{
				$r = 'Посвященный второго круга'.$e.' / 9999';
			}elseif($e>99)
			{
				$r = 'Посвященный первого круга '.$e.' / 999';
			}else{
				$r = $e.' / 99';
			}
		}elseif($id==2)
		{
			//Capital city
			if($e>24998)
			{
				$r = 'Рыцарь второго круга, '.$e.' / ??';
			}elseif($e>9999)
			{
				$r = 'Рыцарь первого круга, '.$e.' / 24999';
			}else{
				$r = $e.' / 10000';
			}
		}elseif($id==4)
		{
			//Demons city
			if($e>24998)
			{
				$r = 'Рыцарь второго круга, '.$e.' / ??';
			}elseif($e>9999)
			{
				$r = 'Рыцарь первого круга, '.$e.' / 24999';
			}else{
				$r = $e.' / 10000';
			}
		}
		
		return $r;
	}
	
	public function addItem($id,$uid,$md = NULL,$dn = NULL)
	{
		$rt = -1;
		$i = mysql_fetch_array(mysql_query('SELECT `im`.* FROM `items_main` AS `im` WHERE `im`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(isset($i['id']))
		{
			$d = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));		
			//новая дата
			$data = $d['data'];	
			if($md!=NULL)
			{
				$data .= $md;
			}	
			if($i['ts']>0)
			{
				$ui = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1'));
				$data .= '|sudba='.$ui['login'];
			}
	
			if($dn!=NULL)
			{
				//предмет с настройками из подземелья
				if($dn['del']>0)
				{
					$i['dn_delete'] = 1;
				}
			}
			$ins = mysql_query('INSERT INTO `items_users` (`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`,`dn_delete`) VALUES (
											"'.$i['overTypei'].'",
											"'.$i['id'].'",
											"'.$uid.'",
											"'.$data.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geni'].'",
											"'.$i['magic_inci'].'",
											"'.$this->info['city'].'",
											"'.time().'",
											"'.time().'",
											"'.$i['dn_delete'].'")');
			if($ins)
			{
				$rt = mysql_insert_id();
				//Записываем в личное дело что предмет получен
				$ld = $this->addDelo(1,$uid,'&quot;<font color=#C65F00>AddItems.'.$this->info['city'].'</font>&quot;: Получен предмет &quot;<b>'.$i['name'].'</b>&quot; (x1) [#'.$i['iid'].'].',time(),$this->info['city'],'AddItems.'.$this->info['city'].'',0,0);
			}else{
				$rt = 0;	
			}			
		}
		return $rt;
	}
	
	public function getNum($v)
	{
		$plid = $v;
		$pi = iconv_strlen($plid);
		if($pi<5)
		{
			$i = 0;
			while($i<=5-$pi)
			{
				$plid = '0'.$plid;
				$i++;
			}
		}
		return $plid;
	}
	
	public function microLogin($id,$t)
	{
		global $c;
		if($t==1)
		{
			$inf = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($id).'" OR `u`.`login` = "'.mysql_real_escape_string((int)$id).'" LIMIT 1'));
		}else{
			$inf = $id;
			$id = $inf['id'];
		}
		$r = '';
		if(isset($inf['id']))
		{
			if($inf['align']>0)
			{
				$r .= '<img width="12" height="15" src="http://img.xcombats.com/i/align/align'.$inf['align'].'.gif" />';
			}
			if($inf['clan']>0)
			{
				$cln = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.$inf['clan'].'" LIMIT 1'));
				if(isset($cln['id']))
				{
					$r .= '<img width="24" height="15" src="http://img.xcombats.com/i/clan/'.$cln['name_mini'].'.gif" />';
				}
			}
			$r .= ' <b>'.$inf['login'].'</b> ['.$inf['level'].']<a target="_blank" href="http://'.$c[$inf['city']].'/info/'.$inf['id'].'"><img title="Инф. о '.$inf['login'].'" src="http://img.xcombats.com/i/inf_'.$inf['cityreg'].'.gif" /></a>';	
		}else{
			$r = '<b><i>Неизвестно</i></b> [??]<a target="_blank" href="http://'.$c['capitalcity'].'.'.$c['url'].'/info/0"><img title="Инф. о &lt;i&&gt;Неизвестно&lt;/i&gt;" src="http://img.xcombats.com/i/inf_.gif" /></a>';	
		}
		return $r;
	}
	
	public function testHome()
	{
		/*----Быстрый(Особенность)----*/
		$timeforwait = 3600;
		if($st['os3']>0) {
		$timeforwait = 3600-(($st['os6']*5)*60);
		}
		/*----Быстрый(Особенность)----*/
		$hgo = $this->testAction('`uid` = "'.$this->info['id'].'" AND `time` >= '.(time()-$timeforwait).' AND `vars` = "go_homeworld" LIMIT 1',1);
		if($this->info['level']==0 || $this->info['active']!='')
		{
			$hgo['id'] = true;
		}
		return $hgo;
	}
	
	public function telegram($uid,$text,$type = 1,$from = NULL)
	{
		if(!(int)$uid)
		{
			$uid = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `login` = "'.mysql_real_escape_string($uid).'" LIMIT 1'));
			$uid = $uid['id'];
		}
		$r = 0;
		if($uid>0)
		{
			if($from == NULL)
			{
				$from = $this->info['login'];
			}
			$ins = mysql_query('INSERT INTO `telegram` (`uid`,`from`,`time`,`fromType`,`text`) VALUES ("'.mysql_real_escape_string($uid).'","'.mysql_real_escape_string($from).'","'.time().'","'.mysql_real_escape_string($type).'","'.mysql_real_escape_string(htmlspecialchars($text,NULL,'cp1251')).'")');
			if($ins)
			{
				$r = 1;
			}else{
				$r = -2;	
			}
		}else{
			$r = -1;	
		}
		return $r;
	}
	
	private function __construct()
	{
		global $c,$code,$magic;
		$this->info = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.*,`r`.`noatack` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) LEFT JOIN `room` AS `r` ON (`u`.`room` = `r`.`id`) WHERE `u`.`login`="'.mysql_real_escape_string($_COOKIE['login']).'" AND `u`.`pass`="'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
		if(isset($this->info['id']) && $this->info['inUser'])
		{
			$this->info = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($this->info['inUser']).'" LIMIT 1'));
		}
		if(!isset($this->info['id']))
		{
			$this->info = mysql_fetch_array(mysql_query('SELECT `u`.* FROM `users` AS `u` WHERE `u`.`login`="'.mysql_real_escape_string($_COOKIE['login']).'" AND `u`.`pass`="'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
			if($this->info['dateEnter']!=$_SERVER['HTTP_USER_AGENT'])
			{
				unset($this->info);
			}
			$this->btl_txt = $this->info['battle_text'];
			if(!isset($this->info['id']))
			{
				unset($this->info);
				setcookie('login','',time()-60*60*24,'',$c['host']);
				setcookie('pass','',time()-60*60*24,'',$c['host']);
			}else{
				echo 'stats is lost.';
			}
		}
				
		if(isset($this->info['id']))
		{					
			$sb = mysql_fetch_array(mysql_query('SELECT SUM(`money2`) FROM `bank` WHERE `uid` = "'.$this->info['id'].'" LIMIT 100'));
			$sb = $sb[0];
			
			if(round($sb-3) > $this->info['catch']-$this->info['frg']) {
				if($this->info['frg'] == -1) {
					$sm = $this->testAction('`uid` = "'.$this->info['id'].'" AND `vars` = "frg" LIMIT 1',1);
				}
				if(!isset($sm['id']) && $this->info['frg']==-1) {
					mysql_query('UPDATE `users` SET `catch` = "'.round($sb).'",`frg` = "0" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					$this->addAction(time(),'frg','[ '.$this->info['login'].' ] '.date('d.m.Y H:i:s').' [true] , balance: '.$sb.' / '.$this->info['catch'].' / '.$this->info['frg'].' ');
				}else{
					mysql_query('UPDATE `users` SET `catch` = "'.round($sb+$this->info['frg']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					$this->addAction(time(),'frgfalse','[ '.$this->info['login'].' ] '.date('d.m.Y H:i:s').' [false] , balance: '.$sb.' / '.$this->info['catch'].' / '.$this->info['frg'].' ');
				}
			}
			
			if($this->info['login2']!='' && $this->info['battle']==0 && $this->info['zv']==0) {
				mysql_query('UPDATE `users` SET `login2` = "" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				$this->info['login2'] = '';	
			}
			
			$this->rep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$this->info['id'].'" LIMIT 1'));
			if(!isset($this->rep['id']))
			{
				mysql_query('INSERT INTO `rep` (`id`) VALUES ('.$this->info['id'].')');
			}
			
			
			//Бесплатный Аккаунт
			$this->stats = $this->getStats($this->info,0);
			if($this->stats['silver'] < 1) {
				mysql_query("INSERT INTO `eff_users` (`id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `sleeptime`, `no_Ace`) VALUES (276, ".$this->info['id'].", 'Silver account', 'add_silver=1', 30, ".time().", 0, 0, 1)");
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','<font color=red>Поздравляем!</font> <b>".$this->info['login']."</b>, Вы стали счастливым обладателем Silver Account! Это наше спасибо за то что Вы здесь!','-1','5','0')");
			}
			//Боты которые пещемещаются по карте
			$sp = mysql_query('SELECT `u`.`id`,`u`.`bot_room`,`s`.`atack`,`u`.`type_pers`,`s`.`timeGo`,`s`.`timeGoL`,`u`.`login`,`u`.`sex`,`u`.`align`,`u`.`clan`,`u`.`room`,`u`.`level`,`u`.`battle`,`s`.`hpNow`,`s`.`mpNow`,`s`.`team`,`u`.`city` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`type_pers` > 0 AND `s`.`timeGo` < '.time().' AND `s`.`timeGoL` < '.time().' LIMIT 1');
			while($pl = mysql_fetch_array($sp)) {
				if($pl['type_pers']>0 && $pl['battle'] == 0) {
					//Бот перемещается
					if($pl['timeGo']<time()) {
						$rm = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `id` = "'.$pl['room'].'" LIMIT 1'));
						$rmgo = explode(',',$rm['roomGo']);
						$rmgo = $rmgo[rand(0,count($rmgo)-1)];
						$rmgo = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `id` = "'.$rmgo.'" AND `botgo` > 0 AND `close` = 0 AND `destroy` = 0 LIMIT 1'));
						if(isset($rmgo['id'])) {
							$pl['room'] = $rmgo['id'];
							mysql_query('UPDATE `users` SET `room` = "'.$rmgo['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `timeGo` = "'.(time()+rand(60,240)).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}
					}
				}
				if($pl['type_pers']>1 && $pl['battle'] == 0 && $pl['timeGoL']<time()) {
					//Бот атакует
					$ru = array();
					$rs = array();
					$spu = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`battle`,`s`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`room` = "'.$pl['room'].'" AND `u`.`city` = "'.$pl['city'].'" AND `u`.`type_pers` = 0 AND `s`.`bot` = 0 AND `u`.`id` != "'.$pl['id'].'" AND `u`.`level` > 6 AND `u`.`online` > "'.(time()-60).'" AND `u`.`banned` = "0" LIMIT 100');
					while($plu = mysql_fetch_array($spu)) {
						if($plu['battle'] == 0) {
							$ru[count($ru)] = $plu['id'];
							$rs[$plu['id']] = $plu;
						}
					}
					$ru = $ru[rand(0,count($ru)-1)];
					if($ru > 0 && rand(0,10000) < 2500) {
						//нападаем на перса
						if($pl['timeGoL'] < time()) {
							//Нападаем
							$atc = $magic->atackUser($pl['id'],$ru,$rs[$ru]['team'],$rs[$ru]['battle'],$pl['bbexp']);
							if($atc == 1) {
								$rs[$ru] = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`battle`,`s`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`room` = "'.$pl['room'].'" AND `u`.`city` = "'.$pl['city'].'" AND `u`.`type_pers` = 0 AND `s`.`bot` = 0 AND `u`.`id` != "'.$pl['id'].'" AND `u`.`level` > 6 AND `u`.`online` > "'.(time()-60).'" AND `u`.`banned` = "0" AND `u`.`id` = "'.$rs[$ru]['id'].'" LIMIT 1'));
								$pl['battle'] = $rs[$ru]['battle'];
								if($rs[$ru]['team'] == 1) {
									$pl['team'] = 2;
								}else{
									$pl['team'] = 1;
								}
								mysql_query('UPDATE `users` SET `battle` = "'.$pl['battle'].'",`team` = "'.$pl['team'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red>Внимание!</font> <b>".$pl['login']."</b> совершил нападение на <b>".$rs[$ru]['login']."</b>...','".time()."','6','0')");
							}else{
								mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red>Внимание!</font> <b>".$pl['login']."</b> совершил не удачное нападение на <b>".$rs[$ru]['login']."</b>...','".time()."','6','0')");
							}
						}else{
							//Предупреждаем
							mysql_query('UPDATE `stats` SET `timeGoL` = "'.(time()+rand(10,30)).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}
					}
				}
				
				if($pl['type_pers']>2) {
					if($pl['battle'] > 0) {
						//бот в поединке
						$btlu = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `time_over` = 0 AND `id` = "'.$pl['battle'].'" LIMIT 1'));
						if(!isset($btlu['id'])) {
							//Поединок завершен
							mysql_query('UPDATE `users` SET `battle` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}else{
							//Поединок продолжается
							if($pl['atack'] < time()) {
								mysql_query('UPDATE `stats` SET `atack` = "'.(time()+123456789).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							}
						}
					}else{
						//бот не в поединке
						if($pl['atack'] > time()) {
							mysql_query('UPDATE `stats` SET `atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}
						
						//ели закончилось НР
						if($pl['hpNow'] < 1) {
							if($pl['bot_room'] > 0) {
								//Портируем в "место отдыха"
								mysql_query('UPDATE `users` SET `room` = "'.$pl['bot_room'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								mysql_query('UPDATE `stats` SET `hpNow` = "1",`mpNow` = "1",`team` = "0",`timeGoL` = "'.(time()+rand(60,240)).'",`atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red><b>Внимание!</b></font> <b>".$pl['login']."</b> был повержен в ".$this->city_name[$pl['city']]."...','".time()."','6','0')");
							}else{
								//Просто хиляем
								mysql_query('UPDATE `stats` SET `hpNow` = "1000000000",`mpNow` = "1000000000",`team` = "0",`timeGoL` = "'.(time()+rand(60,240)).'",`atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							}
						}elseif($pl['bot_room'] > 0 && $pl['room'] == $pl['bot_room']) {
							$btst = $this->getStats($pl['id']);
							if($btst['hpAll'] <= $btst['hpNow'] && $btst['mpAll'] <= $btst['mpNow']) {
								mysql_query('UPDATE `stats` SET `hpNow` = "'.$btst['hpAll'].'",`mpNow` = "'.$btst['mpAll'].'",`team` = "0",`timeGoL` = "'.(time()+rand(60,240)).'",`atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								$nrm = mysql_fetch_array(mysql_query('SELECT `id` FROM `room` WHERE `name` = "Центральная площадь" AND `city` = "'.$pl['city'].'" LIMIT 1'));
								mysql_query('UPDATE `users` SET `room` = "'.(0+$nrm['id']).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red>Внимание!</font> <b>".$pl['login']."</b> вернулся в локацию &quot;Центральная площадь&quot; в ".$this->city_name[$pl['city']]."...','".time()."','6','0')");
								unset($nrm);
							}else{
								//хиляемся
								
							}
							unset($btst);
						}
					}
				}
				unset($pl,$sp,$plu,$spu,$atc,$ru,$rs);
			}
			/*
			$upd = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `login` = "Трупожор" AND `online` < '.(time()-604800).' LIMIT 1'));
			if(isset($upd['id']))
			{
				mysql_query('UPDATE `users` SET `online` = "'.(time()+600).'" WHERE `id` = "'.$upd['id'].'" LIMIT 1');
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','','<font color=red>Внимание!</font> <b>Трупожор</b> выбрался на улицы Capital City! Будьте осторожны!','-1','5','0')");
				unset($upd);
			}
			*/
			
			if(isset($_GET['homeworld']))
			{
				if($this->info['zv']==0 && $this->info['battle']==0 && $this->info['dnow']==0)
				{
					$hgo = $this->testHome();
					if(!isset($hgo['id']))
					{
						$this->addAction(time(),'go_homeworld','');
						$rmt = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "Центральная площадь" AND `city` = "'.$this->info['city'].'" LIMIT 1'));
						if(isset($rmt['id']))
						{
							$this->info['room'] = $rmt['id'];
							mysql_query('UPDATE `users` SET `room` = "'.$this->info['room'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');	
						}else{
							$this->error = 'В этом городе нельзя пользоваться кнопкой возрата';
						}
						unset($rmt);
					}
					unset($hgo);
				}
			}
			
			//Заносим текст
			if(isset($_GET['itmid']) && isset($_GET['addtext'])) {
				$itm = mysql_fetch_array(mysql_query('SELECT `i`.*,`m`.`max_text` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `i`.`item_id` = `m`.`id` WHERE `i`.`id` = "'.mysql_real_escape_string($_GET['itmid']).'" LIMIT 1'));
				if(isset($itm['id'])) {
					if($itm['max_text'] > 0 && $itm['max_text']-$itm['use_text'] > 0) {
						$txt = $_GET['addtext'];
						$txt = str_replace(' ','',$txt);
						$txt = str_replace('	','',$txt);
						if($txt != '') {						
							$txt = substr($_GET['addtext'],0,$itm['max_text']-$itm['use_text']);
							$sx = iconv_strlen($txt);
							$itm['use_text'] += $sx;
							if($itm['use_text'] > $itm['max_text']) {
								$itm['use_text'] = $itm['max_text'];
							}
							mysql_query('UPDATE `items_users` SET `use_text` = "'.$itm['use_text'].'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
							mysql_query('INSERT INTO `items_text` (`item_id`,`time`,`login`,`text`,`city`,`x`) VALUES ("'.$itm['id'].'","'.time().'","'.$this->info['login'].'","'.mysql_real_escape_string(htmlspecialchars($txt,NULL,'cp1251')).'","'.$this->info['city'].'","'.$sx.'")');
							$this->error = 'Запись успешно произведена';
						}else{
							$this->error = 'Нужно что-то написать...';
						}
					}else{
						$this->error = 'Предмет для записи не подходит';
					}
				}else{
					$this->error = 'Предмет для записи не найден';
				}
			}
			
			//Кидаем передачу
			if(isset($_POST['trnLogin'],$_GET['transfer']) && $this->info['battle']==0)
			{
				if($this->info['level']<4 && $this->info['admin']==0)
				{
					$this->error = 'Передавать предметы могут персонажи старше 4-го уровня';
				}elseif($this->info['align']==2 && $this->info['admin']==0)
				{
					$this->error = 'Хаосники не могут передавать предметы другим персонажам';
				}else{
					$t = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['trnLogin']).'" AND `city` = "'.$this->info['city'].'" LIMIT 1'));
					if(isset($t['id']))
					{
						if($t['battle']>0)
						{
							$this->error = 'Персонаж находится в бою';
						}elseif($t['level']<4 && $this->info['admin']==0)
						{
							$this->error = 'Вы не можете передавать предметы персонажам ниже 4-го уровня';
						}elseif($t['align']==2 && $this->info['admin']==0)
						{
							$this->error = 'Вы не можете передавать предметы хаосникам';
						}elseif($t['room']!=$this->info['room'])
						{
							$this->error = 'Вы должны находится в одной комнате с персонажем';	
						}else{
							//создаем передачу
							$tt = mysql_fetch_array(mysql_query('SELECT * FROM `transfers` WHERE (`uid1` = "'.$this->info['id'].'" OR `uid2` = "'.$this->info['id'].'") AND (`cancel1` = "0" OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'") OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'")) AND (`cancel2` = "0" OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'") OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'")) ORDER BY `id` DESC LIMIT 1'));
							if(isset($tt['id']))
							{
								$this->error = 'Вы уже находитесь в передаче';
							}else{
								$tt = mysql_fetch_array(mysql_query('SELECT * FROM `transfers` WHERE (`uid1` = "'.$t['id'].'" OR `uid2` = "'.$t['id'].'") AND (`cancel1` = "0" OR (`finish1` > 0 AND `uid1` = "'.$t['id'].'") OR (`finish2` > 0 AND `uid2` = "'.$t['id'].'")) AND (`cancel2` = "0" OR (`finish2` > 0 AND `uid2` = "'.$t['id'].'") OR (`finish1` > 0 AND `uid1` = "'.$t['id'].'")) ORDER BY `id` DESC LIMIT 1'));
								if(isset($tt['id']))
								{
									$this->error = 'Персонаж уже проводит сделку';
								}else{
									$ins = mysql_query('INSERT INTO `transfers` (`uid1`,`uid2`,`city`,`room`,`time`,`text`,`start1`) VALUES ("'.$this->info['id'].'","'.$t['id'].'","'.$this->info['city'].'","'.$this->info['room'].'","'.time().'","'.mysql_real_escape_string(htmlspecialchars($_POST['textarea'],NULL,'cp1251')).'","'.time().'")');
									if($ins)
									{
										$this->addAction(time(),'trasfer_'.$this->info['city'].'_'.$this->info['room'].'_'.$t['id'].'',$this->info['login']);
									}
								}
							}
						}
					}else{
						$this->error = 'Персонаж не найден в этом городе';	
					}
				}
			}
			
			//Выделяем передачи
			$this->tfer = mysql_fetch_array(mysql_query('SELECT * FROM `transfers` WHERE (`uid1` = "'.$this->info['id'].'" OR `uid2` = "'.$this->info['id'].'") AND (`cancel1` = "0" OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'") OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'")) AND (`cancel2` = "0" OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'") OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'")) ORDER BY `id` DESC LIMIT 1'));
			if(isset($this->tfer['id']))
			{
				if($this->tfer['cancel1']==0 && $this->tfer['cancel2']==0)
				{
					if($this->tfer['uid2']==$this->info['id'] && $this->tfer['start2']==0 && isset($_GET['transfer']))
					{
						$this->tfer['start2'] = time();
						mysql_query('UPDATE `transfers` SET `start2` = "'.$this->tfer['start2'].'" WHERE `id` = "'.$this->tfer['id'].'" LIMIT 1');	
					}
					if($this->tfer['uid2']==$this->info['id'] && $this->tfer['start2']==0)
					{
						$this->tfer = false;
					}elseif($this->tfer['time']<time()-1800)
					{
						//если передача дольше 30 минут, то отмена
						$upd = mysql_query('UPDATE `transfers` SET `cancel1` = "'.time().'",`cancel2` = "'.time().'" WHERE `id` = "'.$this->tfer['id'].'" LIMIT 1');
						if($upd)
						{
							unset($this->tfer,$upd);
						}
					}elseif($this->info['room']!=$this->tfer['room'] || $this->info['city']!=$this->tfer['city'] || $this->info['battle']>0)
					{
						$upd = mysql_query('UPDATE `transfers` SET `cancel1` = "'.time().'",`cancel2` = "'.time().'" WHERE `id` = "'.$this->tfer['id'].'" LIMIT 1');
						if($upd)
						{
							mysql_query('UPDATE `items_users` SET `inTransfer` = "0" WHERE (`uid` = "'.$this->tfer['uid1'].'" OR `uid` = "'.$this->tfer['uid2'].'") AND `inTransfer` > 0');
							unset($this->tfer,$upd);
						}
					}elseif(isset($_GET['exit_transfer']))				
					{
						$upd = 1;
						if($this->tfer['uid2']==$this->info['id'])
						{
							$upd = 2;
						}
						$upd = mysql_query('UPDATE `transfers` SET `cancel'.$upd.'` = "'.time().'" WHERE `id` = "'.$this->tfer['id'].'" LIMIT 1');
						if($upd)
						{
							mysql_query('UPDATE `items_users` SET `inTransfer` = "0" WHERE (`uid` = "'.$this->tfer['uid1'].'" OR `uid` = "'.$this->tfer['uid2'].'") AND `inTransfer` > 0');
							//Добавляем сообщение в чат
							if($this->tfer['start2']>0)
							{
								
							}
							unset($this->tfer,$upd);
						}
					}else{
						if($this->tfer['uid1']==$u->info['id'])
						{
							//Передаем предметы другому игроку
							
						}elseif($this->tfer['uid2']==$u->info['id'])
						{
							//Принимаем передачу от другого игрока
							
						}
					}
				}
			}
			
			/*
			автофлудераст
			*/
			//Внимание Акция с 04.12.2012 по 07.12.2012 включительно! Стоимость еврокредитов снижена на 50%, при покупке более 2000 екр именной артефакт в подарок!
			$i = $this->testAction('`time` >= "'.(time()-1800).'" AND `vars` = "fluder_all" LIMIT 1',1);
			if(!isset($i['id'])) {
				$this->addAction(time(),'fluder_all','');
				//Сообщение в чат
				$r = '<font color=red><b>Внимание!</b></font> Акция с <b>04.12.2012</b> по <b>07.12.2012</b> включительно! Стоимость еврокредитов снижена на 50%, при покупке более 2000 екр <b>именной</b> <img src=http://img.xcombats.com/i/artefact.gif > артефакт в подарок!';				
				//Отправляем сообщение в чат
				mysql_query('UPDATE `chat` SET `delete` = "1" WHERE `text` LIKE "%<font color=red><b>Внимание!</b></font> Акция с <b>04.12.2012</b> по <b>07.12.2012</b>%" AND `delete` = "0" LIMIT 1');
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->info['city']."','','','','".$r."','".time()."','6','0')");
			}
			
			/* различные мероприятия 
			$i = $this->testAction('`time` >= "'.(time()-3600).'" AND `vars` = "turnirs_capitalcity" LIMIT 1',1);
			if(!isset($i['id']))
			{				
				$this->addAction(time(),'turnirs_capitalcity','');				
				$exp = 400;
				$money = 0;
				//2-21
				mysql_query('INSERT INTO `zayvki` (`exp`,`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
				"'.$exp.'","0","0","'.time().'","capitalcity","Комментатор","0","'.(10*60).'","180","2","2","21","21","99","99","0","1","5","<font color=red>Турнир &quot;<b>Троица</b>&quot;, Призовой фонд: '.$money.'.00 кр., Опыт: '.(100+$exp).'%</font>","0","","0","0")');
				//2-3
				mysql_query('INSERT INTO `zayvki` (`exp`,`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
				"'.$exp.'","0","0","'.time().'","capitalcity","Комментатор","0","'.(10*60).'","180","2","2","3","3","99","99","0","1","5","<font color=red>Турнир &quot;<b>Троица</b>&quot;, Призовой фонд: '.$money.'.00 кр., Опыт: '.(100+$exp).'%</font>","0","","0","0")');
				//4-5
				mysql_query('INSERT INTO `zayvki` (`exp`,`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
				"'.$exp.'","0","0","'.time().'","capitalcity","Комментатор","0","'.(10*60).'","180","4","4","5","5","99","99","0","1","5","<font color=red>Турнир &quot;<b>Троица</b>&quot;, Призовой фонд: '.$money.'.00 кр., Опыт: '.(100+$exp).'%</font>","0","","0","0")');
				//6-7
				mysql_query('INSERT INTO `zayvki` (`exp`,`bot1`,`bot2`,`time`,`city`,`creator`,`type`,`time_start`,`timeout`,`min_lvl_1`,`min_lvl_2`,`max_lvl_1`,`max_lvl_2`,`tm1max`,`tm2max`,`travmaChance`,`invise`,`razdel`,`comment`,`money`,`withUser`,`tm1`,`tm2`) VALUES (
				"'.$exp.'","0","0","'.time().'","capitalcity","Комментатор","0","'.(10*60).'","180","6","6","7","7","99","99","0","1","5","<font color=red>Турнир &quot;<b>Троица</b>&quot;, Призовой фонд: '.$money.'.00 кр., Опыт: '.(100+$exp).'%</font>","0","","0","0")');
				//Сообщение в чат
				$r = '<font color=red><b>Внимание!</b></font> <b><u>Мероприятие в Capital City:</u></b> Хаотичный турнир &quot;<b>Троицы</b>&quot; начнется <u>'.date('d.m.Y H:i:s',time()+10*60).'</u>, через 10 минут. Принять участие в турнире смогут персонажи 2-7 уровней. Турнир проходит в разделе &quot;хаотичные поединки&quot;, опыт в турнире: +'.$exp.'%. Турнир проводится раз в час! Спешите принять участие!';				
				//Отправляем сообщение в чат
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->info['city']."','','','','".$r."','".time()."','6','0')");
			}
			*/
			
			//Статистика персонажа на сегодня
			$stat = $this->testAction('`uid` = "'.$this->info['id'].'" AND `time` >= '.strtotime('now 00:00:00').' AND `vars` = "statistic_today" LIMIT 1',1);
			if(!isset($stat['id']))
			{
				$this->addAction(time(),'statistic_today','e='.$this->info['exp'].'|w='.$this->info['win'].'|l='.$this->info['lose'].'|n='.$this->info['nich']);
			}
			
			//Одеваем боевой комплект
			if(isset($_GET['usec1']) && $this->info['battle']==0)
			{
				$cmp = mysql_fetch_array(mysql_query('SELECT * FROM `save_com` WHERE `uid` = "'.$this->info['id'].'" AND `delete` = "0" AND `id` = "'.mysql_real_escape_string($_GET['usec1']).'" LIMIT 1'));
				if(isset($cmp['id']))
				{
					//снимаем все вещи
					mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `uid` = "'.$this->info['id'].'" AND `delete` = "0" AND `inOdet` != "0" AND `inShop` = "0" LIMIT 100');
					//одеваем вещи, если они не удалены
					$cm = $this->lookStats($cmp['val']);
					$i = 1;
					while($i<=50)
					{
						if(isset($cm[$i]))
						{
							mysql_query('UPDATE `items_users` SET `inOdet` = "'.$i.'" WHERE `id` = "'.((int)$cm[$i]).'" AND `uid` = "'.$this->info['id'].'" AND `delete` = "0" AND `inShop` = "0" LIMIT 63');
						}
						$i++;
					}
				}
				unset($cmp,$cm);
			}
			
			$this->room = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `id` = "'.$this->info['room'].'" LIMIT 1'));
			
			if(isset($_POST['bankpsw']))
			{
				$this->bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$this->info['id'].'" AND `block` = "0" AND `id` = "'.mysql_real_escape_string((int)$_POST['bank']).'" AND `pass` = "'.mysql_real_escape_string($_POST['bankpsw']).'"  LIMIT 1'));
				if(isset($this->bank))
				{
					mysql_query('UPDATE `bank` SET `useNow` = "'.(time()+24*60*60).'" WHERE `id` = "'.$this->bank['id'].'" LIMIT 1');
				}else{
					$this->bank['error'] = 'Неверный пароль от счета';
				}
			}elseif(!isset($_GET['bank_exit']))
			{
				$this->bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$this->info['id'].'" AND `block` = "0" AND `useNow` > '.time().' ORDER BY `useNow` DESC  LIMIT 1'));
			}
			
			if(isset($_GET['bank_exit']))
			{
				mysql_query('UPDATE `bank` SET `useNow` = "0" WHERE `uid` = "'.$this->info['id'].'" AND `useNow`!="0" LIMIT 1');
			}
			
			if(isset($_GET['obr_sel']) || isset($_GET['obraz']))
			{
				$sm = $this->testAction('`uid` = "'.$this->info['id'].'" AND `time` > '.(time()-86400).' AND `vars` = "sel_obraz" LIMIT 1',1);
				if(!isset($sm['id']))
				{
					if(isset($_GET['obr_sel']))
					{
						$o = mysql_fetch_array(mysql_query('SELECT * FROM `obraz` WHERE `id` = "'.((int)$_GET['obr_sel']).'" AND `sex` = "'.$this->info['sex'].'" AND (`login` = "" OR `login` = "'.$this->info['login'].'") LIMIT 1'));
						if(isset($o['id']))
						{
							mysql_query('UPDATE `users` SET `obraz` = "'.$o['img'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
							$this->addAction(time(),'sel_obraz','id='.$o['id'].'');
							$this->info['obraz'] = $o['img'];
						}
					}
				}else{
					$this->error = 'Выбирать образ можно не чаще одного раза в сутки, следующая смена '.date('d.m.Y H:i',$sm['time']+86400).'';
					unset($_GET['obr_sel']);
					$_GET['inv'] = 1;
				}
			}
			
			if($this->info['zv'] > 0)
			{
				$zv = mysql_fetch_array(mysql_query('SELECT `id` FROM `zayvki` WHERE `id` = "'.$this->info['zv'].'" AND `btl_id` = "0" AND `cancel` = "0" LIMIT 1'));
				if(!isset($zv['id']))
				{
					$this->info['zv'] = 0;
					mysql_query('UPDATE `stats` SET `zv` = "0" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');	
				}
			}
			
			if($this->info['wipe']!=0)
			{
				$this->wipe();
			}else{
				//проверяем статы
				
			}
			$this->allActionsStart();
		}
	}
	
	public function allActionsStart()
	{
		global $magic;
		if(isset($_GET['use_snowball']))
		{
			$this->stats = $this->getStats($this->info,0);
			//Начинаем лепить снежок на ЦП
			if(isset($this->stats['items'][$this->stats['wp13id']]['id']) && $this->stats['items'][$this->stats['wp13id']]['item_id']==998)
			{
				//варежки одеты, все ок
				if($this->room['name']!='Центральная площадь')
				{
					$this->error2 = 'Собирать снег можно только на Центральной площади';
				}else{
					$smt = $this->testAction('`uid` = "'.$this->info['id'].'" AND `time`>='.(time()-600).' AND `vars` = "create_snowball_cp" LIMIT 1',1);
					if(isset($smt['id']))
					{
						$this->error2 = 'Нельзя липить несколько снежков одновременно ;)';
					}else{
						$smt = $this->testAction('`uid` = "'.$this->info['id'].'" AND `time`>='.strtotime('now 00:00:00').' AND `vars` = "create_snowball_cp" LIMIT 25',2);
						$smt = $smt[0];
						if($smt<24)
						{
							$this->addAction(time(),'create_snowball_cp',$this->info['city']);
							$magic->add_eff($this->info['id'],24);
							$this->error2 = 'Начинаем лепить снежок, осталось '.(24-$smt).' раз на сегодня ...';
						}else{
							$this->error2 = 'Вы уже слепили 24 снежка за сегодня ...';
						}
					}
				}
			}
		}
	}
	
	public function round2($v)
	{
		$v = explode('.',$v);
		$v = doubleval($v[0].'.'.$v[1][0].''.$v[1][1]);
		$f = explode('.',$v);
		if(!isset($f[1]))
		{
			$v = $v.'.00';
		}
		return $v;
	}
	
	public function onlineBonus()
	{
			
				$ts = mysql_fetch_array(mysql_query('SELECT `time_all`,`time_today` FROM `online` WHERE `uid` = "'.$this->info['id'].'" LIMIT 1'));
				$tf = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`vars`,`vals` FROM `actions` WHERE `uid` = "'.$this->info['id'].'" AND `vars` = "online_bonus_time" LIMIT 1'));
				$h = floor(($ts['time_all']-$tf['vals'])/3600);
				if($h>0)
				{
					$r = '';
					if(!isset($tf['id']))
					{
						$this->addAction(time(),'online_bonus_time',$ts['time_all']);
					}elseif($tf['vals'] < $ts['time_all'])
					{
						mysql_query('UPDATE `actions` SET `vals` = "'.$ts['time_all'].'" WHERE `id` = "'.$tf['id'].'" LIMIT 1');
					}
					
					//Выдаем $h шт. предметов награды за онлайн
					if($h > 0) {
						//$this->addItem(2130,$this->info['id'],'noodet=1|noremont=1');
						$this->info['money'] += 5;
						mysql_query('UPDATE `users` SET `money` = "'.$this->info['money'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					}
					$r .= '<b><font color=red>Внимание!</font></b> За проведенный <b>1 час</b> в игре Вы получаете: <b>5</b> кр. Спасибо что Вы с нами!';
					
					//Отправляем сообщение в чат
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','".$r."','-1','5','0')");
				}
			
	}
	
	public function addAction($time,$vars,$vls,$uid = NULL)
	{
		if($uid==NULL)
		{
			$uid = $this->info['id'];
		}
		$ins = mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`,`vals`) VALUES ("'.$uid.'","'.$time.'","'.$this->info['city'].'","'.$this->info['room'].'","'.mysql_real_escape_string($vars).'","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'","'.mysql_real_escape_string($vls).'")');
		if($ins)
		{
			return true;
		}else{
			return false;
		}	 
	}
	
	public function testAction($filter,$tp)
	{
		if($tp==1)
		{
			$ins = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE '.$filter.''));
		}elseif($tp==2)
		{
			$ins = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `actions` WHERE '.$filter.''));
		}
		return $ins;
	}
	
	public function takePersInfo($whr)
	{
		$inf = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE '.$whr.' LIMIT 1'));
		return $inf;
	}
	
	public function addNewbot($id,$botDate,$clon,$logins_bot,$luser)
	{
		global $c,$code;
		if($clon!=NULL)
		{
			$r = false;
			$clon = $this->takePersInfo('`u`.`id` = "'.((int)$clon).'"');
			if(isset($clon['id']))
			{
				//копируем пользователя
				$ins1 = mysql_query('INSERT INTO `users` (
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
				`bot_id`
				) VALUES (
				"'.$clon['login'].' (Клон)",
				"'.$clon['level'].'",
				"'.md5('bot_pass_'.$clon['login'].'_').'",
				"'.$this->info['city'].'",
				"'.$clon['city_reg'].'",
				"'.$clon['name'].'",
				"'.$clon['sex'].'",
				"'.$clon['deviz'].'",
				"'.$clon['hobby'].'",
				"'.$clon['time_reg'].'",
				"'.$clon['obraz'].'",
				"'.mysql_real_escape_string($id).'"
				)');
				if($ins1)
				{
					if($luser == true) {
						//Хуже уворот, крит и защита
						$statss = $this->lookStats($clon['stats']);
						$statss['m1'] = ceil($statss['m1']/5);
						$statss['m2'] = ceil($statss['m2']/5);
						$statss['m3'] = ceil($statss['m3']/2.5);
						$statss['m4'] = ceil($statss['m4']/5);
						$statss['m5'] = ceil($statss['m5']/5);
						$statss['za'] = $statss['za']-175;
						$clon['stats'] = $this->impStats($statss);
						unset($statss);
					}
					$uid = mysql_insert_id();
					//копируем статы
					$ins2 = mysql_query('INSERT INTO `stats` (`clone`,`id`,`stats`,`hpNow`,`upLevel`,`bot`,`priems`) VALUES ("'.$clon['id'].'","'.$uid.'","'.$clon['stats'].'","1000000","'.$clon['upLevel'].'","1","'.$clon['priems'].'")');
					if($ins2)
					{							
						//копируем предметы
						$sp = mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$clon['id'].'" AND `inOdet` > 0 AND `delete` = "0" LIMIT 50');
						while($pl = mysql_fetch_array($sp))
						{
							mysql_query('INSERT INTO `items_users` (`uid`,`item_id`,`data`,`inOdet`,`iznosMAX`,`kolvo`) VALUES ("'.$uid.'","'.$pl['item_id'].'","'.$pl['data'].'","'.$pl['inOdet'].'","'.$pl['iznosMAX'].'","'.$pl['kolvo'].'")');
						}
						//копируем эффекты
						$sp = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$clon['id'].'" AND `delete` = "0" LIMIT 50');
						while($pl = mysql_fetch_array($sp))
						{
							mysql_query('INSERT INTO `eff_users` (`uid`,`id_eff`,`data`,`name`,`overType`,`timeUse`,`x`) VALUES ("'.$uid.'","'.$pl['id_eff'].'","'.$pl['data'].'","'.$pl['name'].'","'.$pl['overType'].'","'.$pl['timeUse'].'","'.$pl['x'].'")');
						}
						$r = $uid;
					}
				}
			}
			return $r;
		}else{
			if($botDate==NULL)
			{
				$bot = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$id.'" LIMIT 1'));
			}else{
				$bot = $botDate;
			}
			if(isset($bot['id']))
			{
				if(isset($logins_bot[$bot['login']]))
				{				
					$logins_bot[$bot['login']]++;
					$bot['login'] = $bot['login'].' ('.$logins_bot[$bot['login']].')';											
				}else{
					$logins_bot[$bot['login']] = 1;
				}
				$ret = true;
				if($bot['time_reg']==100)
				{
					$bot['time_reg'] = time();
				}
				if($bot['city_reg']=='{thiscity}')
				{
					$bot['city_reg'] = $this->info['city'];
				}
				$ins1 = mysql_query('INSERT INTO `users` (
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
				`bot_id`
				) VALUES (
				"'.$bot['login'].'",
				"'.$bot['level'].'",
				"'.md5('bot_pass_'.$bot['login'].'_').'",
				"'.$this->info['city'].'",
				"'.$bot['city_reg'].'",
				"'.$bot['name'].'",
				"'.$bot['sex'].'",
				"'.$bot['deviz'].'",
				"'.$bot['hobby'].'",
				"'.$bot['time_reg'].'",
				"'.$bot['obraz'].'",
				"'.mysql_real_escape_string($id).'"
				)');
				if($ins1)
				{
					$uid = mysql_insert_id();
					$ins2 = mysql_query('INSERT INTO `stats` (`id`,`stats`,`hpNow`,`upLevel`,`bot`) VALUES ("'.$uid.'","'.$bot['stats'].'","1000000","'.$bot['upLevel'].'","1")');
					if($ins2)
					{
						$bot['id'] = $uid;
						$bot['logins_bot'] = $logins_bot;
						$ret = $bot;
						
						//Выдаем предметы
						//$this->addItem($item_id,$uid);
						$iu = explode(',',$bot['itemsUse']);
						$i = 0;
						while($i<count($iu)) {
							if($iu[$i]>0) {
								$idiu = $this->addItem($iu[$i],$bot['id']);
								$islot = mysql_fetch_array(mysql_query('SELECT `id`,`inslot` FROM `items_main` WHERE `id` = "'.$iu[$i].'" LIMIT 1'));
								if(isset($islot['id'])) {
									$islot = $islot['inslot'];
								}else{
									$islot = 2000;
								}
								mysql_query('UPDATE `items_users` SET `inOdet` = "'.$islot.'" WHERE `id` = "'.$idiu.'" LIMIT 1');
							}
							$i++;
						}
						
					}else{
						$ret = false;
					}
				}else{
					$ret = false;
				}
				return $ret;
			}else{
				return false;
			}
		}
	}
	/*
         * $iid Уникальный id прдемета и одновремено флаг что
         * покупка из комка.
         */
	
        public function buyItem($sid,$itm,$x,$mdata = NULL)
	{
		global $c,$code,$sid;
		$x = round((int)$x);
		if($x<1){ $x = 1; }
		if($x>99){ $x = 99; }
		$i = mysql_fetch_array(mysql_query('SELECT `im`.*,`ish`.* FROM `items_shop` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`sid` = "'.mysql_real_escape_string($sid).'" AND `ish`.`kolvo` > 0 AND `ish`.`item_id` = "'.mysql_real_escape_string($itm).'" LIMIT 1'));
		$r = '';
		if(isset($i['id']))
		{
			if($sid==2 || $sid==777)
			{
				if($i['kolvo']<$x)
				{
					$x = $i['kolvo'];
				}
				if($i['price_2']<=0)
				{
					$i['price_2'] = $i['price2'];
				}
				$price = $i['price_2']*$x;
				if($i['price_2']*$x>$this->bank['money2'])
				{
					$r = 'У вас недостаточно денег на счете (не хватает '.($price-$this->bank['money2']).' екр.)';
				}else{
					$d = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
					$this->bank['money2'] -= $price;
					$upd = mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string(round($this->bank['money2'],2)).'" WHERE `id` = "'.$this->bank['id'].'" LIMIT 1');
					if($upd)
					{					
						$this->info['frg'] += $price;
						mysql_query('UPDATE `users` SET `frg` = "'.floor($this->info['frg']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						//новая дата
						$data = '';
						$data .= $d['data'];
						if($mdata!=NULL)
						{
							$data .= '|'.$mdata;
						}
						$ix = 1; $gix = 0;
						while($ix<=$x)
						{
							$ins = mysql_query('INSERT INTO `items_users` (`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
											"'.$i['overType'].'",
											"'.$i['item_id'].'",
											"'.$this->info['id'].'",
											"'.$data.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geniration'].'",
											"'.$i['magic_inc'].'",
											"'.$this->info['city'].'",
											"'.time().'",
											"'.time().'")');
							if($ins)
							{
								$gix++;
							}
							$ix++;
						}
						if($ins)
						{
							//Записываем в личное дело что предмет получен
							$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' екр.<br>Предмет успешно добавлен в инвентарь.';
							mysql_query('UPDATE `items_shop` SET `kolvo` = "'.($i['kolvo']-$x).'" WHERE `iid` = "'.$i['iid'].'" LIMIT 1');
							$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>EkrShop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i['name'].'</b>&quot; (x'.$x.',add items '.$gix.') [#'.$i['iid'].'] за <b>'.$price.'</b> екр.',time(),$this->info['city'],'EkrShop.'.$this->info['city'].'',(int)$price,0);
						}else{
							//Записываем в личное дело что предмет не получен
							$r = 'Вам не удалось приобрести &quot;'.$i['name'].'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' екр.<br>Приносим свои извинения за неудобства.';
							$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>EkrShop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i['iid'].'. К возрату: <b>'.$price.'</b> екр.',time(),$this->info['city'],'EkrShop.'.$this->info['city'].'',0,0);
							if(!$ld)
							{
								echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
							}
						}
					}else{
						$r = 'Вам не удалось приобрести предмет...';
					}
				}
			}else{
				if($i['kolvo']<$x)
				{
					$x = $i['kolvo'];
				}
				if($x<1)
				{
					$x = 1;	
				}
				if($i['price_1']<=0 && $i['tr_items']=='')
				{
					$i['price_1'] = $i['price1'];
				}
				$price = $i['price_1']*$x;
				$trnt = ''; $detrn = array();
				$trn = 1;
				if($i['tr_items']!='')
				{
					$tims2 = explode(',',$i['tr_items']);
					$j = 0;
					while($j<count($tims2))
					{
						$tims = explode('=',$tims2[$j]);
						if($tims[0]>0 && $tims[1]>0)
						{
							$tis = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$tims[0].'" LIMIT 1'));
							if(isset($tis['id']))
							{
								$num_rows = 0;
								$s1p = mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "'.((int)$tims[0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.((int)$tims[1]*$x).'');
								while($p1l = mysql_fetch_array($s1p))
								{
									$num_rows++;
								}
								if($num_rows < (int)$tims[1]*$x)
								{
									$trn = 0;	
								}else{
									$detrn[count($detrn)] = array(0 => $tims[0], 1 => ((int)$tims[1]*$x)); //id_item
								}
								$trnt .= '['.$tis['name'].' (x'.$x.')]x'.$tims[1].', ';
							}
						}
						$j++;	
					}
				}
				
				if($trn==0)
				{
					$trnt = rtrim($trnt,', ');
					$r = 'У вас недостаточно требуемых предметов (не хватает '.$trnt.')';
				}elseif($i['price_1']*$x>$this->info['money'])
				{
					$r = 'У вас недостаточно денег (не хватает '.($price-$this->info['money']).' кр.)';
				}else{
					$d = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
					$this->info['money'] -= $price;
					$upd = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string(round($this->info['money'],2)).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					if($upd)
					{						
						//новая дата
						$data = '';
						$data .= $d['data'];
						if($mdata!=NULL)
						{
							$data .= '|'.$mdata;
						}
						$ix = 1; $gix = 0;
						while($ix<=$x)
						{
							$ins = mysql_query('INSERT INTO `items_users` (`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
											"'.$i['overType'].'",
											"'.$i['item_id'].'",
											"'.$this->info['id'].'",
											"'.$data.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geniration'].'",
											"'.$i['magic_inc'].'",
											"'.$this->info['city'].'",
											"'.time().'",
											"'.time().'")');
							if($ins)
							{
								$gix++;
							}
							$ix++;
						}
						if($ins)
						{
							//Записываем в личное дело что предмет получен
							if($trnt!='' && $i['tr_items']!='')
							{
								$trnt = ', '.$trnt;	
							}
							$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' кр. '.$trnt.'<br>Предмет успешно добавлен в инвентарь.';
							$j = 0;
							while($j<count($detrn))
							{
								$ost = ((int)$detrn[$j][1]);
								$s4 = mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "'.((int)$detrn[$j][0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" ORDER BY `inGroup` DESC LIMIT '.((int)$detrn[$j][1]).'');
								while($itm = mysql_fetch_array($s4))
								{
									//удаляем предмет
										mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
								}							
								$j++;
							}
							mysql_query('UPDATE `items_shop` SET `kolvo` = "'.($i['kolvo']-$x).'" WHERE `iid` = "'.$i['iid'].'" LIMIT 1');
							$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i['name'].'</b>&quot; (x'.$x.') [#'.$i['iid'].'] за <b>'.$price.'</b> кр.',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
						}else{
							//Записываем в личное дело что предмет не получен
							$r = 'Вам не удалось приобрести &quot;'.$i['name'].'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' кр.<br>Приносим свои извинения за неудобства.';
							$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i['iid'].'. К возрату: <b>'.$price.'</b> кр.',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
							if(!$ld)
							{
								echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
							}
						}
					}else{
						$r = 'Вам не удалось приобрести предмет...';
					}
				}
			}
		}else{
			$r = 'Предмет не найден на прилавке';
		}		
		return '<div align="left">'.$r.'</div>';
	}
        
	
	public function addDelo($tp,$uid,$txt,$tm,$ct,$frm,$mo,$mi,$vvv = false)
	{
		$inc = mysql_query("INSERT INTO `users_delo` (`dop`,`moneyOut`,`moneyIn`,`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".mysql_real_escape_string($vvv)."','".mysql_real_escape_string($mo)."','".mysql_real_escape_string($mi)."','".mysql_real_escape_string($uid)."','".$_SERVER['HTTP_X_REAL_IP']."','".$ct."','".$tm."','".mysql_real_escape_string($txt)."','".mysql_real_escape_string($frm)."',".$tp.")");	
		if($inc)
		{
			return true;
		}else{
			return false;
		}	
	}
	
	public function newAct($test)
	{
		$r = true;
		if($test!=$this->info['nextAct'] && $this->info['nextAct']!='0')
		{
			$r = false;
		}else{
			$na = md5(time().'_nextAct_'.rand(0,100));
			$upd = mysql_query('UPDATE `stats` SET `nextAct` = "'.$na.'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			if(!$upd)
			{
				$r = false;
			}else{
				$this->info['nextAct'] = $na;
			}
		}
		return $r;
	}

        public function buyItemCommison($sid,$item,$iid = NULL){
            global $sid;
            $sid  = mysql_real_escape_string($sid);
            $itme = mysql_real_escape_string($item);
            $iid  = mysql_real_escape_string($iid);
			$i2 = mysql_fetch_array(mysql_query('SELECT
                                                    `uid`,
                                                    `id`,
                                                    `uid`,
                                                    `1price`,
                                                    `data`,
                                                    `inShop`,
													`item_id`
                                                FROM 
                                                    `items_users` 
                                                WHERE 
                                                    `id` = '.$iid.' AND 
                                                    `inShop` = 30 
                                                LIMIT 1'));
			$i1 = mysql_fetch_array(mysql_query('SELECT
                                                    `name`,`price1`
                                                FROM 
                                                    `items_main` 
                                                WHERE 
                                                    `id` = '.$i2['item_id'].'
                                                LIMIT 1'));
            $price = $i2['1price'];
            if(isset($i2['id']) AND isset($iid) AND $sid==1 AND $i2['inShop']==30){                
                if($price>$this->info['money'])
                    $r = 'У вас недостаточно денег (не хватает '.($price-$this->info['money']).' кр.)';
                    else{   
                        $UpdMoney = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string(round($this->info['money']-$price,2)).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
                            
                        if($UpdMoney){                                
                                $this->info['money'] -= $price;
                                $UpMoney2 = mysql_query('UPDATE 
                                                            `users` 
                                                        SET 
                                                            `money` = `money` + '.$price.' 
                                                        WHERE 
                                                            `id` = '.$i2['uid'].'
                                                        LIMIT 1');
                                
                                $UpItems = mysql_query('UPDATE 
                                                            `items_users` 
                                                        SET 
                                                            `uid` = '.$this->info['id'].',
															`1price` = "'.$i1['price1'].'", 
															`lastUPD` = "'.time().'",
                                                            `inShop` = 0
                                                        WHERE 
                                                            `id` = '.$iid.' and 
                                                            `inShop` = 30 
                                                        LIMIT 1'); 

                                //Вставляем функцию передачи кредитов владельцу предмета
                                    if($UpItems){
                                        //Записываем в личное дело что предмет получен
                                        $r = 'Вы приобрели предмет &quot;'.$i1['name'].'&quot; за '.$price.' кр.<br>Предмет успешно добавлен в инвентарь.';                                        
                                        $ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>Shop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i1['name'].'</b>&quot; в коммисионом магазине [#'.$i2['iid'].'] за <b>'.$price.'</b> кр.',time(),$this->info['city'],'Shop.'.$this->info['city'].'',0,0);
                                        }else{
                                            //Записываем в личное дело что предмет не получен
                                            $r = 'Вам не удалось приобрести &quot;'.$i1['name'].'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' екр.<br>Приносим свои извинения за неудобства.';
                                            $ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>EkrShop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i1['iid'].'. К возрату: <b>'.$price.'</b> кр.',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
                                                if(!$ld)
                                                    echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
                                        }
                                }else
                                    $r = 'Вам не удалось приобрести предмет...';
                    }
            }else
                $r = 'Предмет не найден на прилавке';
            return '<div align="left">'.$r.'</div>';
        }
        
	public function commisonRent($action,$iid,$price=NULL){
            if($action=="Сдать в магазин" && isset($iid) && $price >0 ){                                
                $ChImtem = mysql_fetch_array(mysql_query('SELECT `data` FROM `items_users` WHERE `id` = '.$iid.' LIMIT 1')); 
                $ChSudba = $this->lookStats($ChImtem['data']);
                    if(isset($ChSudba['sudba']) || $ChSudba['sudba'] != 0 || $ChSudba['sudba']==1){
                        continue;
                        }else
                            mysql_query('UPDATE `items_users` set `inShop` = 30, `1price` = '.$price.' where `uid` = "'.$this->info['id'].'" AND `id` = "'.$iid.'" AND `inOdet` = "0" AND `delete` = "0" ');                            
                }elseif($action=="Забрать" && isset($iid)){
                     $i = mysql_fetch_array(mysql_query('SELECT `im`.`price1`,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.$iid.'" AND `iu`.`inShop` = "30" AND `iu`.`delete` = "0" LIMIT 1'));                    
                     $UpItems = mysql_query('UPDATE `items_users` SET `inShop` = 0, `1price` = "'.$i['price1'].'" WHERE `id` = "'.$iid.'" and `inShop` = "30" LIMIT 1');                    
                }
        }
        
        
	public function commisionShop($sid,$preview = "full"){                                   
            global $c,$code,$sid;
            
                switch ((INT)$_GET['otdel']){
                    case 1:$typeOtdel = 18; break;
                    case 2:$typeOtdel = 19; break;
                    case 3:$typeOtdel = 20; break;
                    case 4:$typeOtdel = 21; break;
                    case 5:$typeOtdel = 22; break;
                    case 6:$typeOtdel = 15; break;
                    case 7:$typeOtdel = 12; break;
                    case 8:$typeOtdel = 4; break;
                    case 9:$typeOtdel = 5; break;
                    case 10:$typeOtdel = 6; break;
                    case 11:$typeOtdel = 1; break;
                    case 12:$typeOtdel = 3; break;
                    case 13:$typeOtdel = 8; break;
                    case 14:$typeOtdel = 14; break;
                    case 15:$typeOtdel = 13; break;
                    case 16:$typeOtdel = 9; break;
                    case 17:$typeOtdel = 10; break;
                    case 18:$typeOtdel = 11; break;
                    case 19:$typeOtdel = 29; break;
                    case 20:$typeOtdel = 30; break;
                    default :$typeOtdel = 18;
                }
                
                if($preview == "full"){
                    $cl = mysql_query('SELECT `items_users`.*,`items_main`.* FROM `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`) WHERE `items_users`.`delete`="0" AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` = "'.mysql_real_escape_string($typeOtdel).'" and `items_users`.`item_id` = "'.(INT)$_GET['itemid'].'" ORDER BY `items_users`.`lastUPD` DESC');                                        
                }
                    else
                        $cl = mysql_query('SELECT `items_users`.*,`items_main`.* FROM `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`) WHERE `items_users`.`delete`="0" AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` = "'.mysql_real_escape_string($typeOtdel).'" ORDER BY `items_main`.`id` DESC');

		$cr = 'c8c8c8';
		$i = 0;
                $steckCikl = 1;
                
		while($pl = mysql_fetch_array($cl))
		{					
                    
                    // количетсво одинаковых предметов в комке
                    if ( $preview == "preview" )
                        $steck = mysql_fetch_array(mysql_query('SELECT COUNT(`item_id`),min(`iznosNOW`),max(`iznosMAX`),min(`1price`),max(`1price`) FROM `items_users` WHERE `item_id` = '.$pl['item_id'].' and inShop =30'));

			if($cr=='d4d4d4')
                            $cr = 'c8c8c8';
                            else
				$cr = 'd4d4d4';			
			
                        if( $preview == "preview" && ($steck[0]>1 && $steck[0]>$steckCikl )){
                            ++$steckCikl;
                            continue;
                        }
                            else{
                                $steckCikl = 1;
				$d = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$pl['id'].'" LIMIT 1'));
                if($steck[0]>1 && $preview == "preview")
                $po = $this->lookStats($d['data']);
                else
                $po = $this->lookStats($pl['data']);
							
				if(($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27)
				{
					//Зоны блока +
					if($pl['inOdet'] != 14) {
						$po['zonb']++;
					}
				}					                               
                                
				$is2 = '';
				$is1 = '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'"><br>';

                                if ($preview == "full")
                                    $is1 .= '<a href="?otdel='.((int)$_GET['otdel']).'&toRent=3&itemid='.(INT)$_GET['itemid'].'&buy='.$pl[0].'&sd4='.$this->info['nextAct'].'&rnd='.$code.' " >купить</a> ';                                    
                                    elseif($preview=="preview")
                                        $is1 .= '<a href="?otdel='.((int)$_GET['otdel']).'&toRent=3&itemid='.$pl['item_id'].' " >Просмотреть</a> ';
                                
				
				//название
				$is2 .= '<a href="items_info.php?id='.$pl['item_id'].'&rnd='.$code.'" target="_blank">'.$pl['name'].'</a> &nbsp; &nbsp;';
				if($pl['massa']>0 && $preview == "full")				
					$is2 .= '(Масса: '.round($pl['massa'],2).')';
				
				
				//цена
				$is2 .= '<br><b>Цена: ';                                
                                if($steck[0]>1 && $preview == "preview")
                                    $is2 .= $steck[3].'-'.$steck[4].' кр.</b> ';
                                    else
                                        $is2 .= $pl['1price'].' кр.</b> ';
                                
                                
                                //долговечность
                                
                                    if($pl['iznosMAX']>0){
                                        $izcol = '';
                                        if(floor($pl['iznosNOW'])>=(floor($pl['iznosMAX'])-ceil($pl['iznosMAX'])/100*20)){					
                                            $izcol = 'brown';
                                        }
                                    }
                                    
                                if ($preview == "preview")
                                    $is2 .= '<br>Долговечность: <font color="'.$izcol.'">'.floor($steck[1]).'/'.ceil($steck[2]).'</font>';
                                    else
                                        $is2 .= '<br>Долговечность: <font color="'.$izcol.'">'.floor($pl['iznosNOW']).'/'.ceil($pl['iznosMAX']).'</font>';
				//$is2 = rtrim($is2,'<br>');
				
				//Срок годности предмета
				if($po['srok'] > 0)
				{
					$pl['srok'] = $po['srok'];
				}
				if($pl['srok'] > 0)
				{
					$is2 .= '<br>Срок годности: '.$this->timeOut($pl['srok']);
				}
								
				//Продолжительность действия магии:
				if((int)$pl['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.((int)$pl['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						$is2 .= '<br>Продолжительность действия: '.$this->timeOut($efi['actionTime']);
					}
				}
				
                                if ($preview == "full"){
                                
				//<b>Требуется минимальное:</b>
				$tr = ''; $t = $this->items['tr'];
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['tr_'.$n]))
					{
						if($po['tr_'.$n] > $this->stats[$n])
						{
							$tr .= '<font color="red">'; $notr++;
						}
						$tr .= '<br>• ';
						$tr .= $this->is[$n].': '.$po['tr_'.$n];
						if($po['tr_'.$n] > $this->stats[$n])
						{
							$tr .= '</font>';
						}
					}
					$x++;
				}
				if($tr!='')
				{
					
					$is2 .= '<br><b>Требуется минимальное:</b>'.$tr;
				}
				//<b>Действует на:</b>
				$tr = ''; $t = $this->items['add'];
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['add_'.$n],$this->is[$n]))
					{
						$z = '+';
						if($po['add_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['add_'.$n];
					}
					$x++;
				}
				//действует на (броня)
				$i = 1; $bn = array(1=>'головы',2=>'корпуса',3=>'пояса',4=>'ног');
				while($i<=4)
				{
					if(isset($po['add_mab'.$i]))
					{
						if($po['add_mab'.$i]==$po['add_mib'.$i] && $pl['geniration']==1)
						{
							$z = '+';
							if($po['add_mab'.$i]<0)
							{
								$z = '';
							}
							$tr .= '<br>• Броня '.$bn[$i].': '.$z.''.$po['add_mab'.$i];
						}else{
							$tr .= '<br>• Броня '.$bn[$i].': '.$po['add_mib'.$i].'-'.$po['add_mab'.$i];
						}
					}
					$i++;
				}
				
				if($tr!='')
				{
					$is2 .= '<br><b>Действует на:</b>'.$tr;
				}
				//<b>Свойства предмета:</b>
				$tr = ''; $t = $this->items['sv'];
				if(isset($po['sv_yron_min'],$po['sv_yron_max']))
				{
					$tr .= '<br>• Урон: '.$po['sv_yron_min'].' - '.$po['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['sv_'.$n]))
					{
						$z = '+';
						if($po['sv_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['sv_'.$n];
					}
					$x++;
				}
				if($pl['2too']==1)
				{
					$tr .= '<br>• Второе оружие';
				}
				if($pl['2h']==1)
				{
					$tr .= '<br>• Двуручное оружие';
				}
				if(isset($po['zonb']))
				{
					$tr .= '<br>• Зоны блокирования: ';
					if($po['zonb']>0)
					{
						$x = 1;
						while($x<=$po['zonb'])
						{
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '—';
					}
				}
				if($tr!='')
				{
					$is2 .= '<br><b>Свойства предмета:</b>'.$tr;
				}
				
				//Особенности
				$tr = '';
				$x = 1;
				while($x<=4)
				{
					if($po['tya'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($po['tya'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($po['tya'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($po['tya'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($po['tya'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($po['tya'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($po['tya'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tya'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if($po['tym'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($po['tym'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($po['tym'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($po['tym'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($po['tym'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($po['tym'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($po['tym'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tym'.$x].': '.$tyc;
					}
					$x++;
				}
				if($tr!='')
				{
					$is2 .= '<br><b>Особенности:</b>'.$tr;
				}
				
				
				if($notr==0)
				{
					$d[0] = 1;
					if($pl['magic_inc']!='')
					{
						$d[2] = 1;
					}
				}
				
				if(floor($pl['iznosNOW'])>=ceil($pl['iznosMAX']))
				{
					$d[0] = 0;
					$d[2] = 0;
				}
				if(isset($po['complect']))
				{
					$is2 .= '<br><i>Дополнительная информация:</i>';
				}
				if(isset($po['complect']))
				{
					//не отображается
					$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT * FROM `complects` WHERE `com` = "'.$po['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($plc = mysql_fetch_array($spc))
					{
						$com1['name'] = $plc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$plc['x'].'</font>: ';
						//действие комплекта
						$i1c = 0; $i2c = 0;
						$i1e = $this->lookStats($plc['data']);
						while($i1c<count($this->items['add']))
						{
							if(isset($i1e[$this->items['add'][$i1c]]))
							{
								$i3c = $i1e[$this->items['add'][$i1c]];
								if($i3c>0)
								{
									$i3c = '+'.$i3c;
								}
								if($i2c>0)
								{
									$com1['text'] .= '&nbsp; &nbsp; '.$this->is[$this->items['add'][$i1c]].': '.$i3c;
								}else{
									$com1['text'] .= $this->is[$this->items['add'][$i1c]].': '.$i3c;
								}								
								$com1['text'] .= '<br>';
								$i2c++;
							}
							$i1c++;
						}
						unset($i1c,$i2c,$i3c);
						$com1['x']++;
					}
					$is2 .= '<br>&bull; Часть комплекта: <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				$is2 .= '<small style="font-size:10px;">';
				
				if($pl['info']!='')
				{
					$is2 .= '<div><b>Описание:</b></div><div>'.$pl['info'].'</div>';
				}
				
				if($po['info']!='')
				{
					$is2 .= '<div>'.$po['info'].'</div>';                                        
				}
				
				if($pl['max_text']-$pl['use_text'] > 0) {
					$is2 .= '<div>Количество символов: '.($pl['max_text']-$pl['use_text']).'</div>';
				}
				
				if($pl['maidin']!='')
				{
					$is2 .= '<div>Сделано в '.$this->city_name[$pl['maidin']].'</div>';
				}
				
				if(isset($po['noremont']))
				{
					$is2 .= '<div style="color:brown;">Предмет не подлежит ремонту</div>';
				}
				
				if(isset($po['frompisher']) && $po['frompisher']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
				}
				
				if($pl['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
				}				
				
				//$is2 .= '<div>Сделано в '.$this->city_name[$this->info['city']].'</div>';
				
				$is2 .= '</small>';
                                }
                                if ($preview == "preview")
                                    $kolvoprint = "<small style=\"float:right; color:grey;\" align=\"right\">Количество: <b>$steck[0]</b> шт.</small>";
                                echo '<tr style="background-color:#'.$cr.';"><td width="200" style="padding:7px;" valign="middle" align="center">'.$is1.'</td><td style="padding:7px;" valign="top">'.$kolvoprint.$is2.'</td></tr>';
			//}
			$i++;
		}
                }
		if($i==0)
                    echo '<tr style="background-color:#'.$cr.';"><td style="padding:7px;" align="center" valign="top">Прилавок магазина пуст</td></tr>';
		
            
        }

	public function shopItems($sid,$plu = '')
	{
		global $c,$code,$sid;
		$cl = mysql_query('SELECT `im`.*,`ish`.* FROM `items_shop` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`sid` = "'.mysql_real_escape_string($sid).'" AND `ish`.`r` = "'.mysql_real_escape_string($_GET['otdel']).'" ORDER BY `ish`.`level`,`ish`.`price_1` ASC');
		$cr = 'c8c8c8';
		$i = 0;
		while($pl = mysql_fetch_array($cl))
		{
			
			/*if($pl['price_1']==0 && $pl['price1']>0)
			{
				mysql_query('UPDATE `items_shop` SET `price_1` = "'.$pl['price1'].'" WHERE `iid` = "'.$pl['iid'].'" LIMIT 1');
			}
			if($pl['price_2']==0 && $pl['price2']>0)
			{
				mysql_query('UPDATE `items_shop` SET `price_2` = "'.$pl['price2'].'" WHERE `iid` = "'.$pl['iid'].'" LIMIT 1');
			}*/			
			
			if($cr=='d4d4d4')
			{
				$cr = 'c8c8c8';
			}else{
				$cr = 'd4d4d4';
			}
			if($pl['kolvo']>0)
			{
				if($pl['price_1']<0.01 && $pl['tr_items']=='')
				{
					$pl['price_1'] = $pl['price1'];
				}
				if($pl['price_2']<0.01 && $pl['tr_items']=='')
				{
					$pl['price_2'] = $pl['price2'];
				}
								
				if($pl['price_1']<0){ $pl['price_1'] = 0; }
				if($pl['price_2']<0){ $pl['price_2'] = 0; }
				$d = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$pl['id'].'" LIMIT 1'));
				$po = $this->lookStats($d['data']);
				
				/*
				if($pl['level']==0 && $po['tr_lvl']>0)
				{
					mysql_query('UPDATE `items_shop` SET `level` = "'.$po['tr_lvl'].'" WHERE `iid` = "'.$pl['iid'].'" LIMIT 1');
					mysql_query('UPDATE `items_main` SET `level` = "'.$po['tr_lvl'].'" WHERE `iid` = "'.$pl['id'].'" LIMIT 1');
				}*/
				
				if(($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27)
				{
					//Зоны блока +
					$po['zonb']++;
				}	
				
				$is2 = '';
				$is1 = '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'"><br>';
				if($this->info['money']>$pl['price'])
				{
					$is1 .= '<span id="shopPlus'.$pl['id'].'"></span><a href="javascript:void('.$pl['id'].');" onClick="top.buyShopNow('.$pl['id'].',\'?'.$plu.'otdel='.((int)$_GET['otdel']).'&buy='.$pl['id'].'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\');">купить</a> <a href="javascript:void(0);" onClick="top.payPlus('.$pl['id'].');"><img style="width:11px; height:11px;" src="http://img.xcombats.com/i/up.gif" title="Купить несколько предметов"></a>';
				}
				
				if($this->info['admin'] > 0) {
					$is1 .= '<br><br><small>Выберите тип предмета: <br>'.
					'  <a href="?otdel='.$_GET['otdel'].'&add_class=0&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Отсутствует</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_class=1&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Танк</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_class=2&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Уворот</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_class=3&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Крит</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_class=4&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Силовик</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_class=5&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Универсал</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_class=6&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Маг</a></small>';
				}
				
				if($this->info['admin'] > 0) {
					$is1 .= '<br><small>Выберите тип доминирования: <br>'.
					'  <a href="?otdel='.$_GET['otdel'].'&add_aclass=0&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">Отсутствует</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_aclass=1&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">над Танком</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_aclass=2&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">над Уворотом</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_aclass=3&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">над Критом</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_aclass=4&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">над Силовиком</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_aclass=5&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">над Универсалом</a>'.
					', <a href="?otdel='.$_GET['otdel'].'&add_aclass=6&itm_id='.$pl['id'].'#sit_'.$pl['id'].'">над Магом</a></small>';
					//уровень доминирования
					
				}
				
				if($this->info['admin']>0) {
					if(isset($_GET['add_class']) && isset($_GET['itm_id']) && $_GET['itm_id'] == $pl['id']) {
						mysql_query('UPDATE `items_main` SET `class` = "'.mysql_real_escape_string($_GET['add_class']).'" WHERE `id` = "'.$pl['item_id'].'" LIMIT 1');
						$pl['class'] = $_GET['add_class'];
					}
					if(isset($_GET['add_aclass']) && isset($_GET['itm_id']) && $_GET['itm_id'] == $pl['id']) {
						mysql_query('UPDATE `items_main` SET `anti_class` = "'.mysql_real_escape_string($_GET['add_aclass']).'" WHERE `id` = "'.$pl['item_id'].'" LIMIT 1');
						$pl['anti_class'] = $_GET['add_aclass'];
					}
					
					if($pl['class'] > 0) {
						$clnm = array('Отсутствует','Танк','Уворот','Крит','Силовик','Универсал','Маг');
						$is1 .= '<br><br><small>Предмет для '.$clnm[$pl['class']].'а</small>';
						unset($clnm);
					}
					
					if($pl['anti_class'] > 0) {
						$clnm = array('Отсутствует','Танк','Уворот','Крит','Силовик','Универсал','Маг');
						$is1 .= '<br><small>Доминирует над '.$clnm[$pl['anti_class']].'ом</small>';
						unset($clnm);
					}
				}
				
				//название
				$is2 .= '<a name="sit_'.$pl['id'].'" href="items_info.php?id='.$pl['item_id'].'&rnd='.$code.'" target="_blank">'.$pl['name'].'</a> &nbsp; &nbsp;';
				if($pl['massa']>0)
				{
					$is2 .= '(Масса: '.round($pl['massa'],2).')';
				}
				
				if(isset($po['art']))
				{
					$is2 .= ' <img title="Артефакт" src="http://img.xcombats.com/i/artefact.gif">';
				}
				
				if(isset($po['sudba']))
				{
					if($po['sudba']=='0')
					{
						$is2 .= ' <img title="Этот предмет будет связан общей судьбой с первым, кто наденет его. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif">';
					}else{
						$is2 .= ' <img title="Этот предмет связан общей судьбой с '.$po['sudba'].'. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/desteny.gif">';
					}
				}
				
				//цена
				$is2 .= '<br><b>Цена: ';
				if($sid==2 || $sid==777)
				{
					if($pl['price_2']>$this->bank['money2'])
					{
						$is2 .= '<font color="red">'.$pl['price_2'].'</font>';
					}else{
						$is2 .= $pl['price_2'];
					}
					$is2 .= ' екр.</b> ';
				}else{
					if($pl['price_1']>$this->info['money'])
					{
						$is2 .= '<font color="red">'.$pl['price_1'].'</font>';
					}else{
						$is2 .= $pl['price_1'];
					}
					$is2 .= ' кр.</b> ';
				}
				
				if($pl['tr_items']!='')
				{
					$ttmm = '';
					$trn = 1;
					$tims2 = explode(',',$pl['tr_items']);
					$j = 0;
					while($j<count($tims2))
					{
						$tims = explode('=',$tims2[$j]);
						if($tims[0]>0 && $tims[1]>0)
						{
							$tis = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$tims[0].'" LIMIT 1'));
							if(isset($tis['id']))
							{
								$num_rows = 0;
								$s1p = mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "'.((int)$tims[0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0"');
								while($p1l = mysql_fetch_array($s1p))
								{
									$num_rows++;	
								}
								if($num_rows < (int)$tims[1])
								{
									$trn = 0;	
								}
								$ttmm .= '[<b>'.$tis['name'].'</b>] x'.$tims[1].', ';	
							}
						}
						$j++;	
					}
					
					if($ttmm!='')
					{
						$ttmm = '<br>Требует предмет: '.rtrim($ttmm,', ').' ';	
						if($trn==0)
						{
							$ttmm = '<font color="red">'.$ttmm.'</font>';	
						}
					}
				}
				
				$is2 .= $ttmm.' <br>';
				
				
				//долговечность
				if($pl['iznosMAXi']>0)
				{
					$is2 .= 'Долговечность: 0/'.$pl['iznosMAXi'].'<br>';
				}				
				
				$is2 = rtrim($is2,'<br>');
				
				//Срок годности предмета
				if($po['srok'] > 0)
				{
					$pl['srok'] = $po['srok'];
				}
				if($pl['srok'] > 0)
				{
					$is2 .= '<br>Срок годности: '.$this->timeOut($pl['srok']);
				}
				
				//Продолжительность действия магии:
				if((int)$pl['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.((int)$pl['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						$is2 .= '<br>Продолжительность действия: '.$this->timeOut($efi['actionTime']);
					}
				}
				
				//<b>Требуется минимальное:</b>
				$tr = ''; $t = $this->items['tr'];
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['tr_'.$n]))
					{
						if($po['tr_'.$n] > $this->stats[$n])
						{
							$tr .= '<font color="red">'; $notr++;
						}
						$tr .= '<br>• ';
						$tr .= $this->is[$n].': '.$po['tr_'.$n];
						if($po['tr_'.$n] > $this->stats[$n])
						{
							$tr .= '</font>';
						}
					}
					$x++;
				}
				if($tr!='')
				{
					
					$is2 .= '<br><b>Требуется минимальное:</b>'.$tr;
				}
				//<b>Действует на:</b>
				$tr = ''; $t = $this->items['add'];
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['add_'.$n],$this->is[$n]))
					{
						$z = '+';
						if($po['add_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['add_'.$n];
					}
					$x++;
				}
				//действует на (броня)
				$i = 1; $bn = array(1=>'головы',2=>'корпуса',3=>'пояса',4=>'ног');
				while($i<=4)
				{
					if(isset($po['add_mab'.$i]))
					{
						if($po['add_mab'.$i]==$po['add_mib'.$i] && $pl['geniration']==1)
						{
							$z = '+';
							if($po['add_mab'.$i]<0)
							{
								$z = '';
							}
							$tr .= '<br>• Броня '.$bn[$i].': '.$z.''.$po['add_mab'.$i];
						}else{
							$tr .= '<br>• Броня '.$bn[$i].': '.$po['add_mib'.$i].'-'.$po['add_mab'.$i];
						}
					}
					$i++;
				}
				
				if($tr!='')
				{
					$is2 .= '<br><b>Действует на:</b>'.$tr;
				}
				//<b>Свойства предмета:</b>
				$tr = ''; $t = $this->items['sv'];
				if(isset($po['sv_yron_min'],$po['sv_yron_max']))
				{
					$tr .= '<br>• Урон: '.$po['sv_yron_min'].' - '.$po['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['sv_'.$n]))
					{
						$z = '+';
						if($po['sv_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['sv_'.$n];
					}
					$x++;
				}
				if($pl['2too']==1)
				{
					$tr .= '<br>• Второе оружие';
				}
				if($pl['2h']==1)
				{
					$tr .= '<br>• Двуручное оружие';
				}
				if(isset($po['zonb']))
				{
					$tr .= '<br>• Зоны блокирования: ';
					if($po['zonb']>0)
					{
						$x = 1;
						while($x<=$po['zonb'])
						{
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '—';
					}
				}
				if($tr!='')
				{
					$is2 .= '<br><b>Свойства предмета:</b>'.$tr;
				}
				
				//Особенности
				$tr = '';
				$x = 1;
				while($x<=4)
				{
					if($po['tya'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($po['tya'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($po['tya'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($po['tya'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($po['tya'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($po['tya'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($po['tya'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tya'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if($po['tym'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($po['tym'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($po['tym'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($po['tym'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($po['tym'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($po['tym'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($po['tym'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tym'.$x].': '.$tyc;
					}
					$x++;
				}
				if($tr!='')
				{
					$is2 .= '<br><b>Особенности:</b>'.$tr;
				}
				
				
				if($notr==0)
				{
					$d[0] = 1;
					if($pl['magic_inc']!='')
					{
						$d[2] = 1;
					}
				}
				
				if(floor($pl['iznosNOW'])>=ceil($pl['iznosMAX']))
				{
					$d[0] = 0;
					$d[2] = 0;
				}
				if(isset($po['complect']))
				{
					$is2 .= '<br><i>Дополнительная информация:</i>';
				}
				if(isset($po['complect']))
				{
					//не отображается
					$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT * FROM `complects` WHERE `com` = "'.$po['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($plc = mysql_fetch_array($spc))
					{
						$com1['name'] = $plc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$plc['x'].'</font>: ';
						//действие комплекта
						$i1c = 0; $i2c = 0;
						$i1e = $this->lookStats($plc['data']);
						while($i1c<count($this->items['add']))
						{
							if(isset($i1e[$this->items['add'][$i1c]]))
							{
								$i3c = $i1e[$this->items['add'][$i1c]];
								if($i3c>0)
								{
									$i3c = '+'.$i3c;
								}
								if($i2c>0)
								{
									$com1['text'] .= '&nbsp; &nbsp; '.$this->is[$this->items['add'][$i1c]].': '.$i3c;
								}else{
									$com1['text'] .= $this->is[$this->items['add'][$i1c]].': '.$i3c;
								}								
								$com1['text'] .= '<br>';
								$i2c++;
							}
							$i1c++;
						}
						unset($i1c,$i2c,$i3c);
						$com1['x']++;
					}
					$is2 .= '<br>&bull; Часть комплекта: <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				if($pl['max_text']-$pl['use_text'] > 0) {
					$is2 .= '<div>Количество символов: '.($pl['max_text']-$pl['use_text']).'</div>';
				}
				
				$is2 .= '<small style="font-size:10px;">';
				
				if($pl['info']!='')
				{
					$is2 .= '<div><b>Описание:</b></div><div>'.$pl['info'].'</div>';
				}
				
				if($po['info']!='')
				{
					$is2 .= '<div>'.$po['info'].'</div>';
				}
								
				if($pl['maidin']!='')
				{
					$is2 .= '<div>Сделано в '.$this->city_name[$pl['maidin']].'</div>';
				}
				
				if(isset($po['noremont']))
				{
					$is2 .= '<div style="color:brown;">Предмет не подлежит ремонту</div>';
				}
				
				if(isset($po['frompisher']) && $po['frompisher']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
				}
				
				if($pl['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
				}	
				
				$is2 .= '<div>Сделано в '.$this->city_name[$this->info['city']].'</div>';
				
				$is2 .= '</small>';
				
				echo '<tr style="background-color:#'.$cr.';"><td width="200" style="padding:7px;" valign="middle" align="center">'.$is1.'</td><td style="padding:7px;" valign="top"><small style="float:right; color:grey;" align="right">Количество: <b>'.$pl['kolvo'].'</b> шт.</small>'.$is2.'</td></tr>';
			}
			$i++;
		}
		if($i==0)
		{
			echo '<tr style="background-color:#'.$cr.';"><td style="padding:7px;" align="center" valign="top">Прилавок магазина пуст</td></tr>';
		}
	}
	
	public function price($vl)
	{
		if($vl==round($vl))
		{
			$vl = $vl.'.00';
		}
		$vl = explode('.',$vl);
		$vl = $vl[0].'.<small>'.$vl[1].'</small>';
		return $vl;
	}
	
	public function testBagStats()
	{
		$st = $this->lookStats($this->info['stats']);
		$n1 = $this->info['ability'];
		$i = 1;
		while($i<=10)
		{
			$n1 += $st['s'.$i];
			$i++;
		}
		$n2 = $this->info['skills'];
		$i = 1;
		while($i<=7)
		{
			$n2 += $st['a'.$i]+$st['mg'.$i];
			$i++;
		}
		
		$n01 = 12;
		$n02 = 1; $ll = 0;
		$lvl = mysql_query('SELECT * FROM `levels` WHERE `upLevel` < "'.$this->info['upLevel'].'"');
		while($pl = mysql_fetch_array($lvl))
		{
			$n01 += $pl['ability'];
			$n02 += $pl['skills'];
			if($ll<$pl['nextLevel'])
			{
				$n01 += 1; //вынос
				if($this->info['level']>=9)
				{
					$n01 += 1;
				}
				if($this->info['level']>=10)
				{
					$n01 += 2;
				}
				if($this->info['level']>=11)
				{
					$n01 += 4;
				}
				$ll++;
			}			
		}
		
		if($n1-$n01!=3 || $n2!=$n02)
		{
			if($this->info['bagStats']!=$bg)
			{
				$bg = '['.$n1.'|'.$n01.'|'.$n2.'|'.$n02.']';
				mysql_query('UPDATE `stats` SET `bagStats` = "'.$bg.'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				$this->info['bagStats'] = $bg;
			}
		}else{
			if($this->info['bagStats']!='0')
			{
				mysql_query('UPDATE `stats` SET `bagStats` = "0" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				$this->info['bagStats'] = 0;
			}
		}
	}
	
	public function wipe()
	{
		if($this->info['wipe']>0 && $this->info['battle']==0)
		{
			//wipe = 1 , значит обнуляем статы и умения
			if($this->info['wipe']<4)
			{
				$st = $this->lookStats($this->info['stats']);
				$n1 = $this->info['ability']; //способности
				$n2 = $this->info['skills']; //владение магией и оружием
				$n3 = $this->info['sskills']; //особенности
				$n4 = $this->info['nskills']; //???
				if($this->info['wipe']==1 || $this->info['wipe']==2)
				{
					$i = 1;
					while($i<=10)
					{					
						if($i<=4)
						{
							$n1 += $st['s'.$i]-3;
							$st['s'.$i] = 3;
						}else{
							$n1 += $st['s'.$i];
							$st['s'.$i] = 0;
						}
						$i++;
					}
					$n1 -= $this->info['level'];
					$st['s4'] = 3+$this->info['level'];
					if($this->info['level']>=9)
					{
						$st['s4'] += 1;
						$n1 -= 1;
					}
					if($this->info['level']>=10)
					{
						$st['s4'] += 2;
						$n1 -= 2;
					}
					if($this->info['level']>=11)
					{
						$st['s4'] += 4;
						$n1 -= 4;
					}
				}
				
				if($this->info['wipe']==1 || $this->info['wipe']==3)
				{
					$i = 1;
					while($i<=7)
					{
						$n2 += $st['a'.$i];
						$n2 += $st['mg'.$i];
						$st['a'.$i]  = 0;
						$st['mg'.$i] = 0;
						$i++;
					}
				}
				if($this->info['wipe']==1 || $this->info['wipe']==3.5)#---Сброс особенностей
				{
					$i = 1;
					while($i<=11)
					{
					$n3 += $st['os'.$i];
					$st['os'.$i] = 0;
					$i++;
					}
				}
				//сохраняем данные
				$st = $this->impStats($st);
				$upd = mysql_query('UPDATE `stats` SET `wipe`="0",`stats`="'.$st.'",`ability`="'.$n1.'",`skills`="'.$n2.'",`sskills`="'.$n3.'",`nskills`="'.$n4.'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');		
				if($upd)
				{
					$this->info['stats'] = $st;
					$this->info['ability'] = $n1;
					$this->info['skills'] = $n2;
					$this->info['sskills'] = $n3;
					$this->info['nskills'] = $n4;
				}					
			}
		}
	}
	
	public function __clone()
	{
		trigger_error('Дублирование не допускается.', E_USER_ERROR);
	}
	
	//Сверяем требования предмета для его использования
	public function trItem($po)
	{
		$tr = ''; $t = $this->items['tr'];
		$x = 0; $notr = 0;
		while($x<count($t))
		{
			$n = $t[$x];
			if(isset($po['tr_'.$n]))
			{
				if($po['tr_'.$n] > $this->stats[$n])
				{
					$notr++;
				}
			}
			$x++;
		}
		return $notr;
	}
	
	public function freeStatsItem($id,$s,$uid)
	{
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.((int)$id).'" AND `iu`.`uid` = "'.$uid.'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0" LIMIT 1'));
		if(isset($itm['id']))
		{
			$po = $this->lookStats($itm['data']);
			if(isset($po['free_stats']) && $po['free_stats']>0)
			{
				$s = (int)$s;
				if($s>=1 || $s<=3 || $s==5)
				{
					$po['free_stats'] = (int)$po['free_stats'];
					$po['free_stats'] -= 1;
					$po['add_s'.$s] += 1;
				}
			}
			$po = $this->impStats($po);
			mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
		}
	}
	
	public function obj_addItem($id)
	{
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1'));
		if(isset($itm['id']))
		{
			$upd = mysql_query('UPDATE `items_users` SET `inShop` = "1" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			if($upd)
			{
				$col = $this->itemsX($itm['id']);
				if($col>0)
				{
					//Переносим группу предметов
					mysql_query('UPDATE `items_users` SET `inShop` = "1" WHERE `inGroup` = "'.$itm['id'].'"');
				}
				if($col>1)
				{
					$col = ' (x'.$col.')';
				}else{
					$col = '';	
				}
				$this->error = 'Предмет &quot;'.$itm['name'].''.$col.'&quot; перенесен из инвентаря';
			}
		}else{
			$this->error = 'Предмет не найден в рюкзаке';	
		}
	}
	
	public function obj_takeItem($id)
	{
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="1" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1'));
		if(isset($itm['id']))
		{
			$upd = mysql_query('UPDATE `items_users` SET `inShop` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			if($upd)
			{
				$col = $this->itemsX($itm['id']);
				if($col>0)
				{
					//Переносим группу предметов
					mysql_query('UPDATE `items_users` SET `inShop` = "0" WHERE `inGroup` = "'.$itm['id'].'" LIMIT 1');
				}
				if($col>1)
				{
					$col = ' (x'.$col.')';
				}else{
					$col = '';	
				}
				$this->error = 'Предмет &quot;'.$itm['name'].''.$col.'&quot; перенесен в инвентаря';
			}
		}else{
			$this->error = 'Предмет не найден в сундуке';	
		}
	}
	
	public function itemsSmSave($id,$s,$uid)
	{
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.((int)$id).'" AND `iu`.`uid` = "'.$uid.'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0" LIMIT 1'));
		if(isset($itm['id']))
		{
			$s = (int)$s;
			$po = $this->lookStats($itm['data']);
			if(!isset($po['sudba']) || $po['sudba']!='0')
			{
				if(isset($po['sm_skill']) && $s>100)
				{
					$mx2 = 1; $mx1 = 0; $mx3 = $this->lookStats($this->info['stats']);
					while($mx2<=7)
					{
						$mx1 += ($mx3['a'.$mx2]+$mx3['mg'.$mx2])-($po['add_a'.$mx2]+$po['add_mg'.$mx2]);
						$mx2++;
					}
					$mx1 += $this->info['skills'];
					if($mx1>0)
					{
						//умения
						$s = $s - 100;
						if($s>5 && $s<=12)
						{
							//магия
							$po['add_mg'.($s-5)]++;						
						}elseif($s>0)
						{
							//оружие
							$po['add_a'.$s]++;
						}
					}
				}elseif(isset($po['sm_abil']) && $s<11 && $s>0)
				{
					$mx2 = 1; $mx1 = 0; $mx3 = $this->lookStats($this->info['stats']);
					while($mx2<=7)
					{
						$mx1 += $mx3['s'.$mx2]-$po['add_s'.$mx2];
						$mx2++;
					}
					$mx1 += $this->info['ability'];
					if($mx1>0)
					{
						$po['add_s'.$s]++;
					}
				}
				
				$po = $this->impStats($po);
				mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				unset($mx1,$mx2,$mx3,$po);
			}
		}
	}
	
	public function runeItem($id,$name,$ruid) {
		
		if($id == NULL) {
			$it_type = 0;
			if(isset($_GET['item_rune'])) {
				$name = $_GET['item_rune'];
			}
			$sp = mysql_query('SELECT `i`.*,`m`.`type`,`m`.`name` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON (`i`.`item_id` = `m`.`id`) WHERE `i`.`inShop` = "0" AND `i`.`delete` = "0" AND `i`.`inOdet` = "0" AND `m`.`name` = "'.mysql_real_escape_string($name).'" AND `i`.`uid` = "'.mysql_real_escape_string($this->info['id']).'"');
			while($pl = mysql_fetch_array($sp)) {
				if(!isset($id['id'])) {
					if($pl['type']!=4 && $pl['type']!=2 && $pl['type']!=7 && $pl['type']!=13) {
						$dt = $this->lookStats($pl['data']);
						$id = array();
						$id = $pl;	
						$id_type = $pl['type'];
					}
				}
			}
		}
		if($id['id'] > 0) {			
			if($ruid < 1 && isset($_GET['use_rune'])) {
				$ruid = $_GET['use_rune'];
			}
			$rune = mysql_fetch_array(mysql_query('SELECT `i`.*,`m`.`name`,`m`.`type`,`m`.`level` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `i`.`item_id` = `m`.`id` WHERE `i`.`id` = "'.mysql_real_escape_string($ruid).'" AND `i`.`uid` = "'.$this->info['id'].'" AND `i`.`delete` = "0" AND `i`.`inShop` = "0" LIMIT 1'));
			if($rune['type']==31 && $id['type'] >= 1 && $id['type'] <=15) {
				//Встраиваем руну
				$data = $this->lookStats($id['data']);
				if(!isset($data['rune']) || $data['rune'] == 0) {
					$data['rune'] = $rune['id'];
					$data['rune_id'] = $rune['item_id'];
					$data['rune_name'] = $rune['name'];
					$data['rune_lvl'] = $rune['level'];
					//Добавляем характеристики руны
					$add = $this->lookStats($rune['data']);
					$i = 0;
					while($i<count($this->items['add'])) {
						if(isset($add['add_'.$this->items['add'][$i]])) {
							$data['add_'.$this->items['add'][$i]] += $add['add_'.$this->items['add'][$i]];
						}
						$i++;
					}
					$data = $this->impStats($data);
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
					$this->error = 'Встраивание руны прошло успешно';
				} else {
					$this->error = 'В предмет уже встроена руна...';
				}
			} else {
				$rune = mysql_fetch_array(mysql_query('SELECT `i`.*,`m`.`name`,`m`.`level`,`m`.`type` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `i`.`item_id` = `m`.`id` WHERE `i`.`id` = "'.mysql_real_escape_string($ruid).'" AND `i`.`uid` = "'.$this->info['id'].'" AND `i`.`delete` = "0" AND `i`.`inShop` = "0" LIMIT 1'));
				if(!isset($rune['id'])) {
					$this->error = 'Усиление которое вы использовали не найдено';
				}elseif($rune['type']==62) {
					
					$idt = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT` 1'));
					//$id['type'] = $idt['type'];
					//Встраиваем руну
					$data = $this->lookStats($id['data']);
					$add = $this->lookStats($rune['data']);
									
					if(!isset($data['spell']) || $data['spell'] == 0) {
						//Новая чарка
						$i = 0;
						$utp = explode(',',$add['onItemType']);
						while($i<count($utp)) {
							if($utp[$i]==$id['type']) {						
								
								$j = 0;
								while($j<count($this->items['add'])) {
									if(isset($add['add'.$utp[$i].'_'.$this->items['add'][$j]])) {									
										$rnda[count($rnda)] = $this->items['add'][$j];
									}
									$j++;
								}
								
								$rnda = $rnda[rand(0,count($rnda)-1)];
								$data['add_'.$rnda] += $add['add'.$utp[$i].'_'.$rnda];
								
								$data['spell'] = $rune['id'];
								if(!isset($data['sudba'])) {
									$data['sudba'] = '0';
								}
								$data['spell_id'] = $rune['item_id'];
								$data['spell_name'] = $rune['name'];
								$data['spell_lvl'] = $rune['level'];
								
								$data = $this->impStats($data);														
								$this->error = 'Увеличина характеристика предмета &quot;'.$id['name'].'&quot;, '.$this->is[$rnda].': +'.$add['add'.$utp[$i].'_'.$rnda];
								
								mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
								mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
								
								$i = 100499;
							}
							$i++;
						}
						if($i<100500) {
							$this->error = 'Данный предмет не подходит для зачарования...';
						}					
					}else{
						//Старая чарка
						$this->error = 'Предмет уже был зачарован ранее...';
					}
					
				}elseif($rune['type']==46) {
					$idt = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT` 1'));
					$id['type'] = $idt['type'];
					if($id_type < 18 || $id_type > 24) {
						$this->error = 'Затачивать можно только оружие...';
					} else {
						//Встраиваем руну
						$data = $this->lookStats($id['data']);
						$add = $this->lookStats($rune['data']);
						if($add['uptype'] != $id_type || $add['uptype'] == 0){
							$this->error = 'Заточка не подходит к данному предмету...';
						} elseif(!isset($data['upatack']) || $data['upatack'] == 0) {
							$data['upatack'] = $rune['id'];
							$data['upatack_id'] = $rune['item_id'];
							$data['upatack_name'] = $rune['name'];
							$data['upatack_lvl'] = $add['upatack'];
							//Добавляем характеристики руны						
							$i = 0;
							while($i<count($this->items['add'])){
								if(isset($add['add_'.$this->items['add'][$i]])){
									$data['add_'.$this->items['add'][$i]] += $add['add_'.$this->items['add'][$i]];
								}
								$i++;
							}
							$data['sv_yron_min'] += $add['upatack'];
							$data['sv_yron_max'] += $add['upatack'];
							$data = $this->impStats($data);
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							$this->error = 'Заточка &quot;'.$id['name'].'&quot; прошла успешно';
						} else {
							$this->error = 'Предмет уже заточен...';
						}
					}
				}else{
					$this->error = 'Усиление которое вы использовали не найдено...';
				}
			}
		}else{
			$this->error = 'Подходящего предмета не нашлось...';
		}
	}
	
	public function genInv($type,$sort)
	{
		global $c,$code;
		$i = 0; $k = 1; $rt = array(0=>0,1=>0,2=>'');$j=0;
		$clr = array(0=>'c8c8c8',1=>'d4d4d4');
		$cl = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE '.$sort.';');
                //echo "SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE '.$sort";
		if($type == 15) {
			$anm = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `id` = "'.$this->info['animal'].'" LIMIT 1'));
		}
		while($pl = mysql_fetch_array($cl))
		{
                    if($type==30){
                        $ChSudba = $this->lookStats($pl['data']);                    
                            if(isset($ChSudba['sudba']) || $ChSudba['sudba'] != 0 || $ChSudba['nosale']==1)
                                continue;
                    }
			if($pl['1price']>0)
			{
				$pl['price1'] = $pl['1price'];
			}
			$is1 = '';
			$is2 = '';
			$d = array();
			//тест
			$d[0] = 0;
			$d[1] = 1;
			$d[2] = 0;
			$po = $this->lookStats($pl['data']);
			
			$see1 = 1;
			if($type == 15) {
				//кормушка зверя
				if($anm['type'] == 3 && substr_count($pl['img'],'wisp') == 0) {
					//светляк
					$see1 = 0;
				}elseif($anm['type'] == 2 && substr_count($pl['img'],'owl') == 0) {
					//сова
					$see1 = 0;
				}elseif($anm['type'] == 1 && substr_count($pl['img'],'cat') == 0) {
					//кот
					$see1 = 0;
				}elseif($anm['type'] == 4 && substr_count($pl['img'],'chrt') == 0) {
					//чертяка
					$see1 = 0;
				}elseif($anm['type'] == 5 && substr_count($pl['img'],'dog') == 0) {
					//собака
					$see1 = 0;
				}elseif($anm['type'] == 6 && substr_count($pl['img'],'pig') == 0) {
					//свинья
					$see1 = 0;
				}elseif($anm['type'] == 7 && substr_count($pl['img'],'dragon') == 0) {
					//дракон
					$see1 = 0;
				}
			}
			if(isset($po['nosale']) && $type==2)
			{
				$see1 = 0;	
			}
			if(isset($po['frompisher']) && $type==2) {
				$see1 = 0;	
			}
			if(isset($po['noremont']) && $type==4)
			{
				$see1 = 0;	
			}
			if($type==5 && $pl['gift']!='')
			{
				$see1 = 0;	
			}
			if(($type==9 || $type==10) && $pl['gift']=='')
			{
				$see1 = 0;	
			}
			if($type==5 && isset($po['sudba']) && $po['sudba']!='0')
			{
				$see1 = 0;	
			}
			if($type==5 && $pl['inTransfer']>0)
			{
				$see1 = 0;
			}
			if($type==6 && !isset($po['fshop']))
			{
				$see1 = 0;	
			}
			if($type==11)
			{
				if($pl['gift']!='' || $pl['inTransfer']>0)
				{
					$see1 = 0;
				}
				if($pl['type']!=1 && $pl['type']!=3 && $pl['type']!=9 && $pl['type']!=10 && $pl['type']!=11 && $pl['type']!=5 && $pl['type']!=6 && $pl['type']!=8 && $pl['type']!=12 && $pl['type']!=14 && $pl['type']!=15 && $pl['item_id']!=1035)
				{
					$see1 = 0; 
				}
				if($po['tr_lvl']<4 && $pl['item_id']!=1035)
				{
					$see1 = 0;
				}
			}
			if($po['musor']>0 && $pl['iznosNOW']>=$pl['iznosMAX'])
			{
				$see1 = 0;
			}
			if($see1==1)
			{
				if($k==1)
				{
					$k = 0;
				}else{
					$k = 1;
				}
				if(($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27)
				{
					//Зоны блока +
					$po['zonb']++;
				}
				//правая часть
				$mx = '';
				if(isset($po['upatack_lvl'])) {
					$mx .= ' +'.$po['upatack_lvl'];
				}
				$col = $this->itemsX($pl['id']);
				if($col>1)
				{
					$mx .= ' (x'.$col.')';
				}
				$is2  = '<a oncontextmenu="top.addTo(\''.$pl['id'].'\',\'item\'); return false;" href="http://lib.'.$c['host'].'/items_info.php?id='.$pl['item_id'].'&rnd='.$code.'" target="_blank">'.$pl['name'].''.$mx.'</a>';
				$is2 .= '&nbsp;&nbsp;';
				if($pl['massa']>0)
				{
					$is2 .= ' (Масса: '.($pl['massa']*$col).')';
				}
				if($pl['gift']!='')
				{
					$ttl = '';
					if($pl['gift']==1)
					{
						$ttl = 'Вы не можете передать этот предмет кому-либо';
					}else{
						$ttl = 'Этот предмет вам подарил '.$pl['gift'].'. Вы не сможете передать этот предмет кому-либо еще';
					}
					$is2 .= ' <img title="'.$ttl.'" src="http://img.xcombats.com/i/podarok.gif">';
				}
				
				if(isset($po['art']))
				{
					$is2 .= ' <img title="Артефакт" src="http://img.xcombats.com/i/artefact.gif">';
				}
				
				if(isset($po['sudba']))
				{
					if($po['sudba']=='0')
					{
						$is2 .= ' <img title="Этот предмет будет связан общей судьбой с первым, кто наденет его. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif">';
					}else{
						$is2 .= ' <img title="Этот предмет связан общей судьбой с '.$po['sudba'].'. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/desteny.gif">';
					}
				}
				
				//цена
				if($pl['price1']>0)
				{
					$pex = explode('.',$pl['price1']);
					if($pex[1]=='00')
					{
						$pl['price1'] = $pex[0];
					}
					$is2 .= '<br><b>Цена: '.($pl['price1']*$col).' кр.</b>';
				}
				
				//долговечность
				if($pl['iznosMAX']>0)
				{
					$izcol = '';
					if(floor($pl['iznosNOW'])>=(floor($pl['iznosMAX'])-ceil($pl['iznosMAX'])/100*20))
					{
						$izcol = 'brown';
					}
					$is2 .= '<br>Долговечность: <font color="'.$izcol.'">'.floor($pl['iznosNOW']).'/'.ceil($pl['iznosMAX']).'</font>';
				}
				
				if($po['srok'] > 0)
				{
					$pl['srok'] = $po['srok'];
				}
				//Срок годности предмета
				if($pl['srok'] > 0)
				{
					$is2 .= '<br>Срок годности: '.$this->timeOut($pl['srok']).' (до '.date('d.m.Y H:i',$pl['time_create']+$pl['srok']).')';
				}
				
				//Продолжительность действия магии:
				if((int)$pl['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.((int)$pl['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						$is2 .= '<br>Продолжительность действия: '.$this->timeOut($efi['actionTime']);
					}
				}
				
				$notr = 0;
				if(isset($po['sudba']) && $po['sudba']!='0' && $po['sudba']!=$this->info['login'])
				{
					$notr++;
				}
				//<b>Требуется минимальное:</b>
				$tr = ''; $t = $this->items['tr'];
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['tr_'.$n]))
					{
						if($po['tr_'.$n] > $this->stats[$n])
						{
							$tr .= '<font color="red">'; $notr++;
						}
						$tr .= '<br>• ';
						$tr .= $this->is[$n].': '.$po['tr_'.$n];
						if($po['tr_'.$n] > $this->stats[$n])
						{
							$tr .= '</font>';
						}
					}
					$x++;
				}
				if($tr!='')
				{
					
					$is2 .= '<br><b>Требуется минимальное:</b>'.$tr;
				}
				//<b>Действует на:</b>
				$tr = ''; $t = $this->items['add'];
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['add_'.$n],$this->is[$n]))
					{
						$z = '+';
						if($po['add_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['add_'.$n];
					}
					$x++;
				}
				//действует на (броня)
				$i = 1; $bn = array(1=>'головы',2=>'корпуса',3=>'пояса',4=>'ног');
				while($i<=4)
				{
					if(isset($po['add_mab'.$i]))
					{
						if($po['add_mab'.$i]==$po['add_mib'.$i] && $pl['geniration']==1)
						{
							$z = '+';
							if($po['add_mab'.$i]<0)
							{
								$z = '';
							}
							$tr .= '<br>• Броня '.$bn[$i].': '.$z.''.$po['add_mab'.$i];
						}else{
							$tr .= '<br>• Броня '.$bn[$i].': '.$po['add_mib'.$i].'-'.$po['add_mab'.$i];
						}
					}
					$i++;
				}
				
				if($tr!='')
				{
					$is2 .= '<br><b>Действует на:</b>'.$tr;
				}
				//<b>Свойства предмета:</b>
				$tr = ''; $t = $this->items['sv'];
				if(isset($po['sv_yron_min'],$po['sv_yron_max']))
				{
					$tr .= '<br>• Урон: '.$po['sv_yron_min'].' - '.$po['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['sv_'.$n]))
					{
						$z = '+';
						if($po['sv_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['sv_'.$n];
					}
					$x++;
				}
				if($pl['2too']==1)
				{
					$tr .= '<br>• Второе оружие';
				}
				if($pl['2h']==1)
				{
					$tr .= '<br>• Двуручное оружие';
				}
				if(isset($po['zonb']))
				{
					$tr .= '<br>• Зоны блокирования: ';
					if($po['zonb']>0)
					{
						$x = 1;
						while($x<=$po['zonb'])
						{
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '—';
					}
				}
				if($tr!='')
				{
					$is2 .= '<br><b>Свойства предмета:</b>'.$tr;
				}
				
				//Особенности
				$tr = '';
				$x = 1;
				while($x<=4)
				{
					if($po['tya'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($po['tya'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($po['tya'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($po['tya'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($po['tya'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($po['tya'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($po['tya'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tya'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if($po['tym'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($po['tym'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($po['tym'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($po['tym'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($po['tym'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($po['tym'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($po['tym'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tym'.$x].': '.$tyc;
					}
					$x++;
				}
				if($tr!='')
				{
					$is2 .= '<br><b>Особенности:</b>'.$tr;
				}
				
				
				if($notr==0)
				{
					$d[0] = 1;
					if($pl['magic_inci']!='' || $pl['magic_inc']!='')
					{
						$d[2] = 1;
					}
				}
				
				if(floor($pl['iznosNOW'])>=ceil($pl['iznosMAX']))
				{
					$d[0] = 0;
					$d[2] = 0;
				}
						
				//Апгрейды вещей
				$tr = '';
				if(isset($po['rune']) && $po['rune']>0) {
					$rnc = explode(' ',$po['rune_name']);
					if($rnc[0] == 'Игнис') {
						$rnc = '#9b5d40';
					}elseif($rnc[0] == 'Аква') {
						$rnc = '#3a2b64';
					}elseif($rnc[0] == 'Аура') {
						$rnc = '#20a3b0';
					}elseif($rnc[0] == 'Тера') {
						$rnc = '#4c7718';
					}else{
						$rnc = '#4c4c4c';
					}
					
					$tr .= '<div>&bull; Встроенная руна: <small><font color='.$rnc.'><u><b>'.$po['rune_name'].' ['.$po['rune_lvl'].']</b></u></font></small></div>';
					unset($rnc);
				}
				if(isset($po['spell']) && $po['spell']>0) {
					$rnc = explode(' ',$po['spell_name']);
					if($rnc[2] == '[0]') {
						$rnc = '#282828';
					}elseif($rnc[2] == '[1]') {
						$rnc = '#624542';
					}elseif($rnc[2] == '[2]') {
						$rnc = '#77090b';
					}elseif($rnc[2] == '[3]') {
						$rnc = '#d99800';
					}else{
						$rnc = '#282828';
					}
					$po['spell_name'] = str_replace('Зачаровать ','',$po['spell_name']);
					$tr .= '<div>&bull; Встроенно зачарование: <small><font color='.$rnc.'><u><b>'.$po['spell_name'].'</b></u></font></small></div>';
					unset($rnc);
				}
				
				if($tr!='') {
					$is2 .= '<br><b>Улучшения предмета:</b>';
					$is2 .= $tr;
				}
						
				if(isset($po['free_stats']) && $po['free_stats']>0)
				{
					$is2 .= '<br><b>Свободные распределения:</b>';
					$is2 .= '<div style="margin-left:20px;"><small>Сила: '.$po['add_s1'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=1"><img src="http://img.xcombats.com/i/plus.gif"></a><br>Ловкость: '.$po['add_s2'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=2"><img src="http://img.xcombats.com/i/plus.gif"></a><br>Интуиция: '.$po['add_s3'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=3"><img src="http://img.xcombats.com/i/plus.gif"></a><br>Интелект: '.$po['add_s5'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=5"><img src="http://img.xcombats.com/i/plus.gif"></a></small></div>';
					$is2 .= 'Осталось распределение: '.$po['free_stats'].'';
				}
				
				if(isset($po['sm_abil']))
				{
					//Возможно сохранять и распределять скилы
					$mx2 = 1; $mx1 = 0; $mx3 = $this->lookStats($this->info['stats']);
					while($mx2<=7)
					{
						$mx1 += $mx3['s'.$mx2]-$po['add_s'.$mx2];
						$mx2++;
					}
					$mx1 += $this->info['ability'];
					if($mx1>0)
					{
						$is2 .= '<br><b>Распределение характеристик:</b>';
						if(isset($po['sudba']) && $po['sudba']=='0')
						{
							$mx1 = 0;
							$is2 .= '<div style="margin-left:20px;"><small>&bull; Распределение характеристик будет доступно после первого одевания</small></div>';
						}else{
							$is2 .= '<div style="margin-left:20px;"><small>
							Сила: '.(0+$po['add_s1']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=1"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Ловкость: '.(0+$po['add_s2']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=2"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Интуиция: '.(0+$po['add_s3']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=3"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Выносливость: '.(0+$po['add_s4']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=4"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Интелект: '.(0+$po['add_s5']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=5"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мудрость: '.(0+$po['add_s6']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=6"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							</small></div>';					
							$is2 .= 'Осталось распределение: '.$mx1;
						}
					}
					unset($mx1,$mx2,$mx3);
				}
				
				if(isset($po['sm_skill']))
				{
					//Возможно сохранять и распределять скилы
					$mx2 = 1; $mx1 = 0; $mx3 = $this->lookStats($this->info['stats']);
					while($mx2<=7)
					{
						$mx1 += ($mx3['a'.$mx2]+$mx3['mg'.$mx2])-($po['add_a'.$mx2]+$po['add_mg'.$mx2]);
						$mx2++;
					}
					$mx1 += $this->info['skills'];
					if($mx1>0)
					{
						$is2 .= '<br><b>Распределение владений оружием и магией:</b>';
						if(isset($po['sudba']) && $po['sudba']=='0')
						{
							$mx1 = 0;
							$is2 .= '<div style="margin-left:20px;"><small>&bull; Распределение владений будет доступно после первого одевания</small></div>';
						}else{
							$is2 .= '<div style="margin-left:20px;"><small>
							Мастерство владения мечами: '.(0+$po['add_a1']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=1"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения дубинами, булавами: '.(0+$po['add_a2']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=2"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения ножами, кастетами: '.(0+$po['add_a3']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=3"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения топорами, секирами: '.(0+$po['add_a4']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=4"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения магическими посохами: '.(0+$po['add_a5']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=5"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения стихией Огня: '.(0+$po['add_mg1']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=6"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения стихией Воздуха: '.(0+$po['add_mg2']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=7"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения стихией Воды: '.(0+$po['add_mg3']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=8"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения стихией Земли: '.(0+$po['add_mg4']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=9"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения магией Света: '.(0+$po['add_mg5']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=10"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения магией Тьмы: '.(0+$po['add_mg6']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=11"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мастерство владения серой магией: '.(0+$po['add_mg7']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsms=12"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							</small></div>';					
							$is2 .= 'Осталось распределение: '.$mx1;
						}
					}
					unset($mx1,$mx2,$mx3);
				}
				
				if(isset($po['complect']))
				{
					$is2 .= '<br><i>Дополнительная информация:</i>';
				}
				if(isset($po['complect']))
				{
					//не отображается
					$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT * FROM `complects` WHERE `com` = "'.$po['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($plc = mysql_fetch_array($spc))
					{
						$com1['name'] = $plc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$plc['x'].'</font>: ';
						//действие комплекта
						$i1c = 0; $i2c = 0;
						$i1e = $this->lookStats($plc['data']);
						while($i1c<count($this->items['add']))
						{
							if(isset($i1e[$this->items['add'][$i1c]]))
							{
								$i3c = $i1e[$this->items['add'][$i1c]];
								if($i3c>0)
								{
									$i3c = '+'.$i3c;
								}
								if($i2c>0)
								{
									$com1['text'] .= '&nbsp; &nbsp; '.$this->is[$this->items['add'][$i1c]].': '.$i3c;
								}else{
									$com1['text'] .= $this->is[$this->items['add'][$i1c]].': '.$i3c;
								}								
								$com1['text'] .= '<br>';
								$i2c++;
							}
							$i1c++;
						}
						unset($i1c,$i2c,$i3c);
						$com1['x']++;
					}
					$is2 .= '<br>&bull; Часть комплекта: <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				if($pl['max_text'] > 0) {
					//Инвентарь
					$sm_sp = mysql_query('SELECT * FROM `items_text` WHERE `item_id` = "'.$pl['id'].'" ORDER BY `id` ASC  LIMIT 500');
					$sma = 0; $smt = ''; $ixi = 0;
					while($sm_pl = mysql_fetch_array($sm_sp)) {
						if($sm_pl['type']==0) {
							$smt .= '<font class="date">'.date('d.m.Y H:i',$sm_pl['time']).'</font> <b>'.$sm_pl['login'].'</b>. '.$sm_pl['text'].'<br>';
						}else{
							$smt .= $sm_pl['text'].'<br>';
						}
						if($ixi == 2) {
							$smt .= '<div style="display:none" id="close_text_itm'.$pl['id'].'">';
						}
						$ixi++;
						$sma += $sm_pl['x'];
					}
					$smt .= '</div>';
					if($pl['max_text']-$pl['use_text'] > 0) {
						$is2 .= '<div>Количество символов: '.($pl['max_text']-$pl['use_text']).'</div>';
					}
					if($sma > 0) {
						$is2 .= '<div>На предмете записан текст:<br>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td><div style="background-color:#e8e8e8;padding:5px;"><CODE>'.$smt.'</CODE></div></td>
									<td width="20" align="center" valign="top"><img style="cursor:pointer" onClick="seetext('.$pl['id'].');" src="http://img.xcombats.com/expand.gif" height="35" width="10"></td>
								  </tr>
								</table>
							</div>';
					}
					unset($sm_sp,$sma,$sm_pl);
				}
				
				$is2 .= '<small style="font-size:10px;">';
				
				if($pl['info']!='')
				{
					$is2 .= '<div><b>Описание:</b></div><div>'.$pl['info'].'</div>';
				}
				
				if($po['info']!='')
				{
					$is2 .= '<div>'.$po['info'].'</div>';
				}
								
				if($pl['maidin']!='')
				{
					$is2 .= '<div>Сделано в '.$this->city_name[$pl['maidin']].'</div>';
				}
				
				if(isset($po['noremont']))
				{
					$is2 .= '<div style="color:brown;">Предмет не подлежит ремонту</div>';
				}
				
				if(isset($po['frompisher']))
				{
					$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
				}
				
				if($pl['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
				}	
				
				$is2 .= '</small>';
				
				//левая часть
				$is1 .= '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'" style="margin-bottom:5px;"><br>';
				if($type==15) {
					//кормушка зверя
					$is1 .= '<a href="main.php?pet=1&obj_corm='.$pl['id'].'&rnd='.$code.'">Кормить</a>';
				}elseif($type==11)
				{
					$pl['rep'] = 1;
					if($this->rep['rep1']>99)
					{
						$pl['rep'] = 1;
					}elseif($this->rep['rep1']>999)
					{
						$pl['rep'] = 1;
					}elseif($this->rep['rep1']>9999)
					{
						$pl['rep'] = 1;
					}
					if($pl['item_id']==1035)
					{
						$pl['rep']++;
					}
					$is1 .= '<a href="javascript:void(0);" onclick="takeItRun(\''.$pl['img'].'\','.$pl['id'].','.$pl['rep'].');">Выбрать</a>';
				}elseif($type==10)
				{
					//Общага (отображение предметов в общаге (под стеклом))
					$is1 .= '<a href="main.php?obj_take='.$pl['id'].'&room='.((int)$_GET['room']).'&rnd='.$code.'">В рюкзак</a>';
				}elseif($type==9)
				{
					//Общага (отображение предметов в инвентаре (под стеклом))
					$is1 .= '<a href="main.php?obj_add='.$pl['id'].'&room='.((int)$_GET['room']).'&rnd='.$code.'">Под стекло</a>';
				}elseif($type==8)
				{
					//Общага (отображение предметов в инвентаре)
					$is1 .= '<a href="main.php?obj_add='.$pl['id'].'&room='.((int)$_GET['room']).'&rnd='.$code.'">В сундук</a>';
				}elseif($type==7)
				{
					//Общага (отображение предметов в общаге)
					$is1 .= '<a href="main.php?obj_take='.$pl['id'].'&room='.((int)$_GET['room']).'&rnd='.$code.'">В рюкзак</a>';
				}elseif($type==6)
				{
					//Цветочный магазин
					$is1 .= '<a href="main.php?otdel=2&add_item_f='.$pl['id'].'&rnd='.$code.'">Добавить</a>';
				}elseif($type==5)
				{
					//передача
					$is1 .= '<a onClick="saleitem('.$pl['id'].',1); return false;" href="#">подарить</a><br><a onClick="saleitem('.$pl['id'].',2); return false;" href="#">передать</a><br><small style="font-size:10px">(налог: 1 кр.)</small>';
				}elseif($type==12)
				{
					//передача почта
					$skcd = round($col*($pl['price1']*0.06-0.01*$u->stats['os1']),2);
					if($skcd < 0.06) {
						$skcd = 0.06;
					}
					$is1 .= '<a href="main.php?otdel='.$_GET['otdel'].'&setlogin='.$_REQUEST['setlogin'].'&setobject='.$pl['id'].'&room=2&tmp='.$code.'" onclick="return confirm(\'Передать предмет '.$pl['name'].'?\')">передать&nbsp;за&nbsp;'.(1+$skcd).'&nbsp;кр.</A>';
				}elseif($type==13)
				{
					//Забираем шмот
					if($pl['1price']>0){
					$mess = "Отказаться от предмета? Предмет будет уничтожен!";
					$pl['name']= 'Деньги '.$pl['1price'].' кр.';
					}else{
					$mess = "Отказаться от предмета? Предмет будет возвращен отправителю";
					}
					$date1 = $pl['delete']-time();
					$is1 .='<BR><NOBR><A href="?room=4&to_box='.$pl['id'].'&tmp='.$code.'">Забрать</A></NOBR>
							<BR><NOBR><A onclick="return confirm(\''.$mess.'\')" href="?room=4&del_box='.$pl['id'].'&tmp='.$code.'">Отказаться</A></NOBR><SMALL><BR><BR>('.date("j дн. H ч.",$date1).' )</small></TD>';
				}elseif($type==4)
				{					
					//ремонт
					$r1 = round($pl['price1']*0.06/100,2);
					$r2 = round($pl['price1']*0.06/10,2);
					$r3 = round($pl['price1']*$pl['iznosNOW']*0.06/100,2);
					if($r1<0.01){ $r1 = 0.01; }
					if($r2<0.01){ $r2 = 0.01; }
					if($r3<0.01){ $r3 = 0.01; }
					$is1 .= '<small style="font-size:10px;"><a href="?remon='.$pl['id'].'&t=1&rnd='.$code.'">Ремонт 1 ед. за '.$r1.' кр.</a><br>';
					if($pl['iznosNOW']>=10){$is1 .= '<a href="?remon='.$pl['id'].'&t=2&rnd='.$code.'">Ремонт 10 ед. за '.$r2.' кр.</a><br>';}
					$is1 .= '<a href="?remon='.$pl['id'].'&t=3&rnd='.$code.'">Полный ремонт за '.$r3.' кр.</a></small>';

				}elseif($type==3)
				{
					$is1 .= '<input type="button" onClick="document.getElementById(\'itemgift\').value='.$pl['id'].';document.F1.submit();" value="Подарить" />';
				}elseif($type==2)
				{
					global $shopProcent;
					$shpCena = $pl['price1'];
					if($pl['kolvo']>0)
					{
						$shpCena = $shpCena*$pl['kolvo'];
					}
					$plmx = 0;
					if($pl['iznosMAXi']!=$pl['iznosMAX'] && $pl['iznosMAX']!=0)
					{
						$plmx = $pl['iznosMAX'];
					}else{
						$plmx = $pl['iznosMAXi'];
					}
					if($pl['iznosNOW']>0)
					{
						$prc1 = $pl['iznosNOW']/$plmx*100;
					}else{
						$prc1 = 0;
					}
					$shpCena = $shpCena/100*(100-$prc1);
					if($pl['iznosMAX']>0 && $pl['iznosMAXi']>0 && $pl['iznosMAXi']>$pl['iznosMAX'])
					{
						$shpCena = $shpCena/100*($pl['iznosMAX']/$pl['iznosMAXi']*100);
					}
					$shpCena = $this->round2($shpCena/100*(100-$shopProcent));
					if($shpCena<0)
					{
						$shpCena = 0;
					}
					$is1 .= '<a href="#" onClick="if(confirm(\'Продать предмет &quot;'.$pl['name'].'&quot; за '.$shpCena.' кр.?\')){ location = \'main.php?sale&sd4='.$this->info['nextAct'].'&item='.$pl['id'].'&rnd='.$code.'\'; }">Продать за '.$shpCena.' кр.</a>';
				}elseif($type==30){
                                    
                                   $is1.= '<form method="POST">
                                            <input type="hidden" value="'.$pl['id'].'" name="iid">
                                            <input type="text" value="" name="summTR">
                                            <input type="submit" value="Сдать в магазин" name="PresTR">
                                            </form>';
                                }elseif($type==31){
                                    $is1.= '<form method="POST">
                                            <input type="hidden" value="'.$pl['id'].'" name="iid">                                            
                                            <input type="submit" value="Забрать" name="PresTR">
                                            </form>';
                                }else{
					if($d[2]==1) //можно использовать
					{
						if($pl['item_id']==74)
						{
							$is1 .= '<a onclick="top.addNewSmile('.$pl['id'].',0); return false;" href="#" title="Использовать">исп-ть</a>';
						}else{
							$useUrl = '';
							if($pl['magic_inc']=='')
							{
								$pl['magic_inc'] = $pl['magic_inci'];
							}
							if($pl['magic_inc'] && $pl['type']==30)
							{
								//используем эликсир
								$pldate = '<table width=\\\'100%\\\' border=\\\'0\\\' cellspacing=\\\'0\\\' cellpadding=\\\'0\\\'><tr><td width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
								$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\');';
							}elseif($pl['magic_inc'] && $pl['type']==29)
							{
								//используем заклятие
								//на персонажа
								if(isset($po['useOnLogin']))
								{
									$useUrl = 'top.useMagic(\''.$pl['name'].'\','.(0+$pl['id']).',\''.$pl['img'].'\',1,\'main.php?inv=1&otdel='.((int)$_GET['otdel']).'&use_pid='.$pl['id'].'&rnd='.$code.'\');';
								}else{
									//просто использование (на селя, либо без указания предмета\логина)
									$pldate = '<table width=\\\'100%\\\' border=\\\'0\\\' cellspacing=\\\'0\\\' cellpadding=\\\'0\\\'><tr><td width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
									$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\');';
								}
								
									
								//на предмет
									
							}
							$is1 .= '<a href="#" onClick="'.$useUrl.'" title="Использовать">исп-ть</a>';
						}
					}
					
					if($pl['max_text'] > 0 && $pl['max_text']-$pl['use_text'] > 0) {
						$is1 .= '<a onclick="top.addNewText('.$pl['id'].','.($pl['max_text']-$pl['use_text']).','.$pl['inRazdel'].'); return false;" href="#" title="Записать текст на предмете">Записать</a><br>';
					}
					if($pl['type']==31 || $pl['type']==46 || $pl['type']==62)
					{
						if($d[2]==1)
						{
							$is1 .= '<br>';
						}
						$is1 .= '<a href="javascript:void(0);" onClick="top.useRune('.$pl['id'].',\''.$pl['name'].'\',\''.$pl['img'].'\',\'main.php?inv=1&otdel='.((int)$_GET['otdel']).'&use_rune='.$pl['id'].'&rnd='.$code.'\');return false;" title="Использовать">Исп-ть</a><br>';
					}
					
					if($d[0]==1 && $pl['type']!=30 && $pl['type']!=31 && (($pl['type']!=38 && $pl['type']!=39 && $pl['type']!=37) || $pl['gift']!='')) //можно одеть
					{
						if(!isset($po['noodet']))
						{
							if($d[2]==1)
							{
								$is1 .= '<br>';
							}
							$is1 .= '<a href="main.php?otdel='.$pl['inRazdel'].'&inv=1&oid='.$pl['id'].'&rnd='.$code.'" title="Надеть">надеть</a>';
						}
						if(isset($po['open']))
						{
							if($d[2]==1)
							{
								$is1 .= '<br>';
							}
							$is1 .= '<a href="main.php?otdel='.$pl['inRazdel'].'&inv=1&open=1&oid='.$pl['id'].'&rnd='.$code.'" title="Открыть">Открыть</a>';	
						}
					}
					if($pl['group']>0)
					{
						$is1 .= '<br>';
						if($this->itemsX($pl['id'])<$pl['group_max'])
						{
							$is1 .= '<a href="main.php?inv=1&otdel='.((int)$_GET['otdel']).'&stack='.$pl['id'].'&rnd='.$code.'" title="Собрать"><img src="http://img.xcombats.com/i/stack.gif" /></a>';
						}
						if($this->itemsX($pl['id'])>1)
						{
							$is1 .= ' <a href="main.php?inv=1&otdel='.((int)$_GET['otdel']).'&unstack='.$pl['id'].'&rnd='.$code.'" title="Разделить"><img src="http://img.xcombats.com/i/unstack.gif" /></a>';
						}
					}
					if($d[1]==1) //можно выкинуть
					{
						$is1 .= ' <a onClick="top.drop('.$pl['id'].',\''.$pl['img'].'\',\''.$pl['name'].'\',1,\'<table border=\\\'0\\\' cellspacing=\\\'0\\\' cellpadding=\\\'5\\\'><tr><td><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></td><td align=\\\'left\\\'>Предмет <b>'.$pl['name'].'</b> будет утерян, вы уверены ?</td></tr></table>\',\''.intval($_GET['otdel']).'\'); return false;" href="javascript:void(0);" title="Выкинуть предмет"><img src="http://img.xcombats.com/i/clear.gif"></a>';
						//$is1 .= ' <img onclick="if (confirm(\'Предмет &quot;'.$pl['name'].'&quot; будет утерян, вы уверены?\')) window.location=\'main.php?inv=1&delete='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\'" title="Выкинуть предмет" src="http://img.xcombats.com/i/clear.gif" style="cursor:pointer;">';
					}
				}	
				//собираем все в одно (:
				$rt[2] .= '<tr><td align="center" bgcolor="#'.$clr[$k].'"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="20%" align="center" style="border-right:#A5A5A5 1px solid; padding:5px;">'.$is1.'</td><td valign="top" align="left" style="padding-left:3px; padding-bottom:3px; padding-top:7px;"><div align="left">'.$is2.'</div></td></tr></table></td></tr>'; 
				$rt[1] += $pl['massa'];
				$i++;
			}
			$j++;
		}
		$rt[0] = $i;
		$rt['collich']=$j;
		return $rt;
	}
	
	public function itemsX($id,$uid = NULL)
	{
		if($uid==NULL)
		{
			$uid = $this->info['id'];
		}
		$r = mysql_num_rows(mysql_query('SELECT `iu`.`id` FROM `items_users` AS `iu` WHERE `iu`.`uid` = "'.((int)$uid).'" AND `iu`.`delete` = "1000" AND `iu`.`inGroup` = "'.((int)$id).'" LIMIT 1000'));
		$r++;
		return $r;
	}
	
	public function stack($id)
	{
		global $c,$code;
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.mysql_real_escape_string((int)$id).'" AND `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" LIMIT 1'));
		if(isset($itm['id']) && $itm['iznosNOW']==0 && $itm['inGroup']==0)
		{
			//группируем похожие свободные предметы с этим
			//сначало те которые по х1
			$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`kolvo` = "1" AND `iu`.`item_id` = "'.$itm['item_id'].'" AND `iu`.`id`!="'.$itm['id'].'" AND `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" ORDER BY `iu`.`inGroup` DESC LIMIT '.$itm['group_max'].'');
			$i = 0; $j = 0;
			while($pl = mysql_fetch_array($sp))
			{
				if($pl['data']==$itm['data'] && $itm['iznosMAX']==$pl['iznosMAX'] && $pl['iznosNOW']==0 && ($pl['timeOver']==0 || $pl['timeOver']>time()) && $this->itemsX($pl['id'])==1 && $pl['gift']==$itm['gift'])
				{
					//группируем эти предметы. delete = time(), inGroup = $itm['id']
					$upd = mysql_query('UPDATE `items_users` SET `delete` = "1000",`inGroup` = "'.$itm['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					if($upd)
					{
						$j++;
					}
					$i++;
				}
			}
			if($j>0)
			{
				mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			}else{
				
				//группируем похожие свободные предметы с этим, те которые уже сгруппированы, тоесть обьединяем группы
				$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`kolvo` = "1" AND `iu`.`item_id` = "'.$itm['item_id'].'" AND `iu`.`id`!="'.$itm['id'].'" AND `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" ORDER BY `iu`.`inGroup` DESC LIMIT '.$itm['group_max'].'');
				$i = 0; $j = 0;
				while($pl = mysql_fetch_array($sp))
				{
					if($pl['data']==$itm['data'] && $itm['iznosMAX']==$pl['iznosMAX'] && $pl['iznosNOW']==0 && ($pl['timeOver']==0 || $pl['timeOver']>time()) && $pl['gift']==$itm['gift'])
					{
						if($this->itemsX($pl['id']) > 0) {
							$upd = mysql_query('UPDATE `items_users` SET `delete` = "1000",`inGroup` = "'.$itm['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							if($upd)
							{
								$j++;
							}
							$upd = mysql_query('UPDATE `items_users` SET `delete` = "1000",`inGroup` = "'.$itm['id'].'" WHERE `inGroup` = "'.$pl['id'].'" LIMIT '.$this->itemsX($pl['id']));
							if($upd)
							{
								$j++;
							}
						}
					}
				}
				
				
				
			}
		}
	}
	
	public function unstack($id,$x = NULL)
	{
		$id = (int)$id;
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.mysql_real_escape_string((int)$id).'" AND `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inGroup` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" LIMIT 1'));
		if(isset($itm['id']) && $itm['inGroup']==0)
		{
			if($x==NULL)
			{
				$x = $this->itemsX($itm['id']); //кол-во распада
			}
			$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "1000" AND `iu`.`inGroup` = "'.$itm['id'].'" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" LIMIT '.$x.'');
			$i = 0; $j = 0;
			while($pl = mysql_fetch_array($sp))
			{
				$upd = mysql_query('UPDATE `items_users` SET `delete` = "0",`inGroup` = "0",`lastUPD` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				if($upd)
				{
					$j++;	
				}
				$i++;
			}
			if($j>0)
			{
				mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');			
			}
		}
	}
	
	public function lookStats($m)
	{
		$ist = array();
		$di = explode('|',$m);
		$i = 0; $de = false;
		while($i<count($di))
		{
			$de = explode('=',$di[$i]);
			if(isset($de[0]))
			{
				$ist[$de[0]] = $de[1];
			}
			$i++;
		}
		return $ist;
	}
	
	public function testItems($uid,$sn,$dt)
	{
		global $c,$code;
		$st = false; $rt = false;
		if($uid!=$this->info['id'])
		{
			$u = mysql_fetch_array(mysql_query('SELECT `u`.`align`,`u`.`clan`,`u`.`animal`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`sex`,`u`.`obraz`,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
			if($sn==0)
			{
				$sn = $this->getStats($uid,$i1);
			}
		}else{
			$u  = $this->info;
			if(isset($this->stats['hpAll']))
			{
				$sn = $this->stats;
			}elseif($sn!=0)
			{
				
			}else{
				$sn = $this->getStats($uid,$i1);
			}
		}
		if(isset($u['id']))
		{
			$snIt = 0;
			//Проверяем одетые вещи и вещи с сроком годности
			$cl = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`iu`.`inOdet`!="0" OR `iu`.`data` LIKE "%srok%" OR `iu`.`iznosNOW` > 0 OR `im`.`srok` > 0 OR (`iu`.`timeOver`<'.time().' AND `iu`.`timeOver`!="0")) AND `iu`.`uid`="'.$u['id'].'" AND (`iu`.`delete`="0" OR `iu`.`delete`="1000")');
			while($itm = mysql_fetch_array($cl))
			{
				$po = array();
				$po = $this->lookStats($itm['data']);
				$po['lvl'] = $u['level'];
				//проверяем требования				
				$t = $this->items['tr'];
				$x = 0;
				$notr = 0;
				$j = 1;
				while($j<=4)
				{
					if(!isset($po['tr_s'.$j]) && $itm['type']!=26)
					{
						$po['tr_s'.$j] = 0;
					}
					$j++;
				}
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($po['tr_'.$n]))
					{
						if($po['tr_'.$n] > $this->stats[$n])
						{
							$notr++;
						}
					}
					$x++;
				}
				if($po['srok'] > 0)
				{
					$itm['srok'] = $po['srok'];
				}
				if($itm['iznosNOW']>=ceil($itm['iznosMAX']))
				{
					$notr++;	
				}
				if($notr>0 && $itm['inOdet']!=0)
				{
					//снимаем предмет
					$this->snatItem($itm['id'],$u['id']);
					$snIt++;
				}
				//проверяем срок годности
				if($itm['iznosNOW']>=ceil($itm['iznosMAX']))
				{
					//предмет сломался
					if(isset($po['musor']))
					{
						if($po['musor']>0)
						{
							$this->recr($itm['id'],$itm['type'],$u['id'],(int)$po['musor']);
						}
					}
				}elseif($itm['time_create']+$itm['srok'] <= time() && $itm['srok']>0)
				{
					if($itm['inOdet']!=0)
					{
						$this->snatItem($itm['id'],$u['id']);
						$snIt++;
					}
					//удаляем предмет
					//
					$this->isport($itm['id'],$itm['timeOver'],$itm['overType'],$u['id'],(int)$po['musor2'],$itm['type'],$itm['name']);
				}elseif($itm['time_create']+$itm['srok'] <= time() && $itm['srok']>0)
				{
					echo 'test';
				}
			}		
			
			if($snIt>0)
			{
				$this->testItems($uid,$sn,1);
			}elseif($dt==0)
			{
				return -2;
			}
		}else{
			return 0;
		}
	}
	
	public function recr($id,$tp,$uid,$id2)
	{
		if($id!=0)
		{
			if($uid!=0)
			{
				$uid2 = 'AND `uid`="'.$uid.'"';
			}else{
				$uid2 = '';
			}
			$upd = mysql_query('UPDATE `items_users` SET `delete`="'.time().'" WHERE `id` = "'.$id.'" '.$uid2.' LIMIT 1');
			if($upd)
			{
				$this->addDelo(2,$uid,'&quot;<font color="maroon">System.inventory</font>&quot;: Предмет [itm:'.$it.'] был <b>сломан</b>.',time(),$this->info['city'],'System.inventory',0,0);
				if($id2>1)
				{
					//Добавляем пустую бутылку
					$this->addItem($id2,$uid,'noodet=1|noremont=1');
				}
			}
		}
	}
		
	public function isport($it,$t,$tp,$uid,$id2,$type,$name)
	{
		if($it!=0)
		{
			if($uid!=0)
			{
				$uid2 = 'AND `uid`="'.$uid.'"';
			}else{
				$uid2 = '';
			}
			$upd = mysql_query('UPDATE `items_users` SET `delete`="'.time().'",`timeOver`="1" WHERE `id` = "'.$it.'" '.$uid2.' LIMIT 1');
			if($upd)
			{
				$this->addDelo(2,$uid,'&quot;<font color="maroon">System.inventory</font>&quot;: Предмет <b>'.$name.'</b> [itm:'.$it.']['.$id2.']['.$tp.']['.$type.'] был <b>испорчен</b>.',time(),$this->info['city'],'System.inventory',0,0);
				if($tp!=0)
				{
					//Добавляем испорченый предмет в инвентарь, в зависимости от типа
					if($id2>0)
					{
						$this->addItem($id2,$uid,'noodet=1');
					}else{
						if( $type == 30 ) {
							//испорченный эликсир
							$this->addItem(4036,$uid,'|renameadd='.$name.'|noodet=1');
						}
					}
				}				
			}
		}
	}
	
	public function btlMagicList() {
		global $c;
		$i = 1; $sv = array();
		while($i<=10) {
			$sv[$i] = '<img style="filter: alpha(opacity=25); -moz-opacity: 0.25; -khtml-opacity: 0.25; opacity: 0.25;" title="Пустой слот заклятия" src="http://img.xcombats.com/i/items/w/w101.gif" />';
			$i++;
		}

			$i = 0;
			while($i < count($this->stats['items'])) {
				if($this->stats['items'][$i]['inslot'] == 40 || $this->stats['items'][$i]['inslot'] == 50) {
					if($this->stats['items'][$i]['useInBattle']==0 || $this->stats['items'][$i]['magic_inci']=='' || $this->stats['items'][$i]['magic_inci']=='0') {
						$vl = 'title="'.$this->stats['items'][$i]['name'].'" style="filter: alpha(opacity=25); -moz-opacity: 0.25; -khtml-opacity: 0.25; opacity: 0.25;"';
					}else{
						$po = $this->lookStats($this->stats['items'][$i]['data']);
						if($po['useOnLogin']==1) {
							$useUrl = 'top.useMagicBattle(\''.$this->stats['items'][$i]['name'].'\','.$this->stats['items'][$i]['id'].',\''.$this->stats['items'][$i]['img'].'\',1,1);';
						}else{
							$useUrl = 'top.useMagicBattle(\''.$this->stats['items'][$i]['name'].'\','.$this->stats['items'][$i]['id'].',\''.$this->stats['items'][$i]['img'].'\',1,2);';
						}
						$vl = 'style="cursor:pointer" onclick="'.$useUrl.'"';
					}
					$sv[$this->stats['items'][$i]['inOdet']-39] = '<img '.$vl.' src="http://img.xcombats.com/i/items/'.$this->stats['items'][$i]['img'].'" />';
				}
				$i++;
			}

		$r = '<table border="0" cellspacing="0" cellpadding="0">'.
                 '<tr>'.
                    '<td>'.$sv[1].'</td>'.
                    '<td>'.$sv[2].'</td>'.
                    '<td>'.$sv[3].'</td>'.
                    '<td>'.$sv[4].'</td>'.
                    '<td>'.$sv[5].'</td>'.
                    '<td>'.$sv[6].'</td>'.
                    '<td>'.$sv[7].'</td>'.
                    '<td>'.$sv[8].'</td>'.
                    '<td>'.$sv[9].'</td>'.
                    '<td>'.$sv[10].'</td>'.
					'<td>'.$sv[11].'</td>'.
					'<td>'.$sv[12].'</td>'.
                    '</tr>'.
                '</table>';
			return str_replace('"','\"',$r);
	}
	
	public function getInfoPers($uid,$i1,$sn = 0,$ivv = 0)
	{
		global $c,$code;
		$st = false; $rt = false;
		if($uid!=$this->info['id'])
		{
			$u = mysql_fetch_array(mysql_query('SELECT `u`.`banned`,`u`.`align`,`u`.`clan`,`u`.`animal`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`sex`,`u`.`obraz`,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
			if($sn==0)
			{
				$sn = $this->getStats($uid,$i1);
			}
		}else{
			$u  = $this->info;
			if(isset($this->stats['hpAll']))
			{
				$sn = $this->stats;
			}elseif($sn!=0)
			{
				
			}else{
				$sn = $this->getStats($uid,$i1);
			}
		}

		if(isset($u['id'],$u['stats']))
		{
			$rt = array(0=>'',1=>array());
			$st = array();
			$st['id'] = $u['id'];
			$st['login'] = $u['login'];
			$st['lvl'] = $u['level'];

			//Характеристики от предметов и их изображение
			$witm    = array();
			$witm[1] = '<img style="display:block;" title="Пустой слот шлем" src="http://img.xcombats.com/i/items/w/w9.gif">';
			$witm[2] = '<img style="display:block;" title="Пустой слот наручи" src="http://img.xcombats.com/i/items/w/w13.gif">';
			$witm[3] = '<img style="display:block;" title="Пустой слот оружие" src="http://img.xcombats.com/i/items/w/w3.gif">';
			$witm[4] = '<img style="display:block;" title="Пустой слот броня" src="http://img.xcombats.com/i/items/w/w4.gif">';
			$witm[7] = '<img style="display:block;" title="Пустой слот пояс" src="http://img.xcombats.com/i/items/w/w5.gif">';
			$witm[8] = '<img style="display:block;" title="Пустой слот серьги" src="http://img.xcombats.com/i/items/w/w1.gif">';
			$witm[9] = '<img style="display:block;" title="Пустой слот ожерелье" src="http://img.xcombats.com/i/items/w/w2.gif">';
			$witm[10] = '<img style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[11] = '<img style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[12] = '<img style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[13] = '<img style="display:block;" title="Пустой слот перчатки" src="http://img.xcombats.com/i/items/w/w11.gif">';
			$witm[14] = '<img style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w10.gif">';
			$witm[16] = '<img style="display:block;" title="Пустой слот поножи" src="http://img.xcombats.com/i/items/w/w19.gif">';
			$witm[17] = '<img style="display:block;" title="Пустой слот обувь" src="http://img.xcombats.com/i/items/w/w12.gif">';
			//40-52 слот под магию		
			$witm[53] = '<img style="display:block;" title="Пустой слот правый карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[54] = '<img style="display:block;" title="Пустой слот левый карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[55] = '<img style="display:block;" title="Пустой слот центральный карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[56] = '<img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$witm[57] = '<img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$witm[58] = '<img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$cl = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`inOdet`!="0" AND `iu`.`uid`="'.$u['id'].'" AND `iu`.`delete`="0"');
			$wj = array(1=>false,2=>false,4=>false,5=>false,6=>false);
			$b1 = '<br>';
			while($pl = mysql_fetch_array($cl))
			{
				$td = $this->lookStats($pl['data']);
				
				if(isset($td['upatack_lvl']))
				{
					$pl['name'] = $pl['name'].' +'.$td['upatack_lvl'];
				}
				
				if(isset($td['mod_lvl']))
				{
					$pl['name'] = $pl['name'].' '.$mx.' ['.$td['mod_lvl'].']';
				}
				
				if($pl['inOdet']==1 || $pl['inOdet']==52 || ($pl['inOdet']>=4 && $pl['inOdet']<=6))
				{
					$wj[$pl['inOdet']] = $pl;
				}
												
				$lvar = '';
				if($td['add_hpAll']>0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$lvar .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if($td['sv_yron_max']>0)
				{
					$lvar .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
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
				if($pl['iznosMAX']>0)
				{
					$lvar .= '<br>Долговечность: '.floor($pl['iznosNOW']).'/'.ceil($pl['iznosMAX']);
				}
				
				$witm[$pl['inOdet']]  = '<img style="display:block;" src="http://img.xcombats.com/i/items/'.$pl['img'].'" onMouseOver="top.hi(this,\'<b>'.$pl['name'].'</b>'.$lvar.'\',event,3,1,1,1,\'\')" onMouseOut="top.hic();" onMouseDown="top.hic();">';
				
				if($i1==1)
				{
					$witm[$pl['inOdet']] = '<a href="http://lib.'.$c['host'].'/items_info.php?id='.$pl['item_id'].'&rnd='.$code.'" target="_blank">'.$witm[$pl['inOdet']].'</a>';
				}else{
					if($pl['inOdet']>=40 && $pl['inOdet']<=52 && !isset($_GET['inv']))
					{
						$useUrl = '';
						if($pl['magic_inc']=='')
						{
							$pl['magic_inc'] = $pl['magic_inci'];
						}
						if($pl['magic_inc'] && $pl['type']==30)
						{
							//используем эликсир
							$pldate = '<table width=\\\'100%\\\' border=\\\'0\\\' cellspacing=\\\'0\\\' cellpadding=\\\'0\\\'><tr><td width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
							$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\');';
						}elseif($pl['magic_inc'] && $pl['type']==29)
						{
							//используем заклятие
							//на персонажа
							if(isset($td['useOnLogin']))
							{
								$useUrl = 'top.useMagic(\''.$pl['name'].'\','.(0+$pl['id']).',\''.$pl['img'].'\',1,\'main.php?inv=1&otdel='.((int)$_GET['otdel']).'&use_pid='.$pl['id'].'&rnd='.$code.'\');';
							}else{
								//просто использование (на селя, либо без указания предмета\логина)
								$pldate = '<table width=\\\'100%\\\' border=\\\'0\\\' cellspacing=\\\'0\\\' cellpadding=\\\'0\\\'><tr><td width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
								$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\');';
							}								
						}
						$witm[$pl['inOdet']] = '<a href="javascript:void(0);" onClick="'.$useUrl.'">'.$witm[$pl['inOdet']].'</a>';
					}elseif($pl['item_id']==998 && !isset($_GET['inv']))
					{
						//варежки
						$witm[$pl['inOdet']] = '<a href="main.php?use_snowball='.$code.'">'.$witm[$pl['inOdet']].'</a>';
					}else{
						$witm[$pl['inOdet']] = '<a href="main.php?otdel='.$pl['inRazdel'].'&inv=1&sid='.$pl['id'].'&rnd='.$code.'">'.$witm[$pl['inOdet']].'</a>';
					}
				}		
			}
			//Шлем,Венок
			$wj1i = '';
			$br  = '<div align=\\\'center\\\' style=\\\'margin:4px;\\\'><img src=\\\'http://img.xcombats.com/1x1.gif\\\' height=\\\'1\\\' width=\\\'111\\\' style=\\\'background-color:black;\\\'></div>';
			if($wj[1]!=false)
			{
				$wj1i .= '<b>'.$wj[1]['name'].'</b>';	
				$td = array();							
				$td = $this->lookStats($wj[1]['data']);
				if($td['add_hpAll']>0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj1i .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if($td['sv_yron_max']>0)
				{
					$wj1i .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if($td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj1i .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$wj1i .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if($td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj1i .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$wj1i .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if($td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj1i .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$wj1i .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if($td['add_mab4']>0)
				{
					if($td['add_mib4']==$td['add_mab4'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab4']<0){ $m1l = ''; }
						$wj1i .= '<br>Броня ног: '.$m1l.''.(0+$td['add_mab4']).'';
					}else{
						$wj1i .= '<br>Броня ног: '.(0+$td['add_mib4']).'-'.(0+$td['add_mab4']).'';
					}
				}
				
				if($wj[1]['iznosMAX']>0)
				{
					$wj1i .= '<br>Долговечность: '.floor($wj[1]['iznosNOW']).'/'.ceil($wj[1]['iznosMAX']).'';
				}
			}
			if($wj[52]!=false)
			{
				$wj1i .= '<b>'.$wj[52]['name'].'</b>';
				$wj[1]['img'] = $wj[52]['img'];
				$wj[1]['id']  = $wj[52]['id'];
				$wj[1]['inRazdel'] = $wj[52]['inRazdel'];
			}
			//Рубаха,Броня,Плащ
			$wj4i = '';
			if($wj[6]!=false)
			{
				$td = array();	
				$wj4i .= '<b>'.$wj[6]['name'].'</b>';
				$td = $this->lookStats($wj[6]['data']);
				if($td['add_hpAll']>0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj4i .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if($td['sv_yron_max']>0)
				{
					$wj4i .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if($td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[6]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$wj4i .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if($td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[6]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$wj4i .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if($td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[6]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$wj4i .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if($td['add_mab4']>0)
				{
					if($td['add_mib4']==$td['add_mab4'] && $wj[6]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab4']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня ног: '.$m1l.''.(0+$td['add_mab4']).'';
					}else{
						$wj4i .= '<br>Броня ног: '.(0+$td['add_mib4']).'-'.(0+$td['add_mab4']).'';
					}
				}
				
				if($wj[6]['iznosMAX']>0)
				{
					$wj4i .= '<br>Долговечность: '.floor($wj[6]['iznosNOW']).'/'.ceil($wj[6]['iznosMAX']).'';
				}
				if($wj[5]!=false || $wj[4]!=false)
				{
					$wj4i .= $br;
				}
			}
			if($wj[5]!=false)
			{
				$td = array();	
				$wj4i .= '<b>'.$wj[5]['name'].'</b>';
				$td = $this->lookStats($wj[5]['data']);	
				if($td['add_hpAll']>0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj4i .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if($td['sv_yron_max']>0)
				{
					$wj4i .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if($td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[5]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$wj4i .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if($td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[5]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$wj4i .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if($td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[5]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$wj4i .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if($td['add_mab4']>0)
				{
					if($td['add_mib4']==$td['add_mab4'] && $wj[5]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab4']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня ног: '.$m1l.''.(0+$td['add_mab4']).'';
					}else{
						$wj4i .= '<br>Броня ног: '.(0+$td['add_mib4']).'-'.(0+$td['add_mab4']).'';
					}
				}
				
				if($wj[5]['iznosMAX']>0)
				{
					$wj4i .= '<br>Долговечность: '.floor($wj[5]['iznosNOW']).'/'.ceil($wj[5]['iznosMAX']).'';
				}
				if($wj[4]!=false)
				{
					$wj4i .= $br;
				}
			}
			if($wj[4]!=false)
			{
				$td = array();	
				$wj4i .= '<b>'.$wj[4]['name'].'</b>';
				$td = $this->lookStats($wj[4]['data']);	
				if($td['add_hpAll']>0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj4i .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if($td['sv_yron_max']>0)
				{
					$wj4i .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if($td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[4]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$wj4i .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if($td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[4]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$wj4i .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if($td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[4]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$wj4i .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if($td['add_mab4']>0)
				{
					if($td['add_mib4']==$td['add_mab4'] && $wj[4]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab4']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня ног: '.$m1l.''.(0+$td['add_mab4']).'';
					}else{
						$wj4i .= '<br>Броня ног: '.(0+$td['add_mib4']).'-'.(0+$td['add_mab4']).'';
					}
				}
				
				if($wj[4]['iznosMAX']>0)
				{
					$wj4i .= '<br>Долговечность: '.floor($wj[4]['iznosNOW']).'/'.ceil($wj[4]['iznosMAX']).'';
				}
			}
			if($wj[6]!=false)
			{
				$wj[4]['img'] = $wj[6]['img'];
				$wj[4]['id']  = $wj[6]['id'];
				$wj[4]['inRazdel'] = $wj[6]['inRazdel'];
			}elseif($wj[5]!=false)
			{
				$wj[4]['img'] = $wj[5]['img'];
				$wj[4]['id']  = $wj[5]['id'];
				$wj[4]['inRazdel'] = $wj[5]['inRazdel'];
			}elseif($wj[4]!=false)
			{
				
			}
			if($wj[1]!=false || $wj[2]!=false)
			{
				$witm[1] = '<img style="display:block;" src="http://img.xcombats.com/i/items/'.$wj[1]['img'].'" onMouseOver="top.hi(this,\''.$wj1i.'\',event,3,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">';
				if($i1==1)
				{
					$witm[1] = '<a href="http://lib.'.$c['host'].'/items_info.php?id='.$wj[1]['id'].'&rnd='.$code.'" target="_blank">'.$witm[1].'</a>';
				}else{
					$witm[1] = '<a href="main.php?otdel='.$wj[1]['inRazdel'].'&inv=1&sid='.$wj[1]['id'].'&rnd='.$code.'">'.$witm[1].'</a>';
				}	
			}
			if($wj[4]!=false || $wj[5]!=false || $wj[6]!=false)
			{
				$witm[4] = '<img style="display:block;" src="http://img.xcombats.com/i/items/'.$wj[4]['img'].'" onMouseOver="top.hi(this,\''.$wj4i.'\',event,3,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">';
				if($i1==1)
				{
					$witm[4] = '<a href="http://lib.'.$c['host'].'/items_info.php?id='.$wj[4]['id'].'&rnd='.$code.'" target="_blank">'.$witm[4].'</a>';
				}else{
					$witm[4] = '<a href="main.php?otdel='.$wj[4]['inRazdel'].'&inv=1&sid='.$wj[4]['id'].'&rnd='.$code.'">'.$witm[4].'</a>';
				}	
			}
			
			/*------------ ГЕНЕРИРУЕМ ИНФ. О ПЕРСОНАЖЕ ---------------*/
			$msl = '<img src="http://img.xcombats.com/i/slot_bottom.gif">';
			$jf = '';
			$oi = '';
			if($i1!=1)
			{
				$jf = 'main';
				$oi = 'onMouseOver="top.hi(this,\''.$u['login'].' (Перейти в &quot;Инвентарь&quot;)\',event,3,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"';
				$msl = '<table width="120" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="40" height="20">'.$witm[53].'</td>
						  <td width="40" height="20">'.$witm[55].'</td>
						  <td width="40" height="20">'.$witm[54].'</td>
						</tr>
						<tr>
						  <td width="40" height="20">'.$witm[56].'</td>
						  <td width="40" height="20">'.$witm[57].'</td>
						  <td width="40" height="20">'.$witm[58].'</td>
						</tr>
					  </table>';
			}
			$anml = ''; $hpmp = '';
			if($u['animal']>0)
			{
				$an = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `id` = "'.$u['animal'].'" LIMIT 1'));
				if(isset($an['id']))
				{
					$anml = '<div style="position:absolute; width:40px; height:73px; z-index:3; top:147px; left:80px;"><a href="#"><img height="73" width="40" src="http://img.xcombats.com/i/obraz/'.$an['sex'].'/'.$an['obraz'].'.gif" title="'.$an['name'].' ['.$an['level'].'] (Посмотреть образ)"></a></div>';
				}
			}
			$eff = '';
			//-------- генерируем эффекты
			$efs = mysql_query('SELECT `eu`.*,`em`.* FROM `eff_users` AS `eu` LEFT JOIN `eff_main` AS `em` ON (`eu`.`id_eff` = `em`.`id2`) WHERE `eu`.`uid`="'.mysql_real_escape_string($u['id']).'" AND `delete`="0"');
			while($e = mysql_fetch_array($efs))
			{
				$esee = 1;
				if($e['see']==0 && ($i1==1 || $e['uid']!=$this->info['id']))
				{
					$esee = 0;
				}
				if($e['see']==2 && ($u['battle']!=$this->info['battle'] || $this->info['battle']==0))
				{
					$esee = 0;
				}
				if($e['see']==3 && $i1==0)
				{
					$esee = 0;
				}
				
				if(($e['timeUse']+$e['timeAce']+$e['actionTime']>=time() || $e['timeUse']==77) && $esee == 1)
				{
					$ei = '<b><u>'.$e['name'].'</u></b>';
					if($e['type1']>0 && $e['type1']<7)
					{
						$ei .= ' (Эликсир)';
					}elseif(($e['type1']>6 && $e['type1']<11) || $e['type1']==16)
					{
						$ei .= ' (Заклятие)';
					}elseif($e['type1']==14)
					{
						$ei .= ' (Прием)';
					}elseif($e['type1']==15)
					{
						$ei .= ' (Изучение)';
					}elseif($e['type1']==17)
					{
						$ei .= ' (Проклятие)';
					}elseif($e['type1']==18 || $e['type1']==19)
					{
						$ei .= ' (Травма)';
					}elseif($e['type1']==20)
					{
						$ei .= ' (Пристрастие)';
					}elseif($e['type1']==22)
					{
						$ei .= ' (Ожидание)';
					}else{
						$ei .= ' (Эффект)';
					}
					$ei .= '<br>';
					
					if($e['type1']!=13 && $e['timeUse']!=77)
					{
						$out = '';
						$time_still = ($e['timeUse']+$e['timeAce']+$e['actionTime'])-time();
						$tmp = floor($time_still/2592000);
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
						}
						$ei .= 'Осталось: '.$out.'<br>';
					}
					
					//Действие эффекта
					$tr = ''; $t = $this->items['add'];
					$x = 0; $ed = $this->lookStats($e['data']);	
					while($x<count($t))
					{
						$n = $t[$x];
						if(isset($ed['add_'.$n],$this->is[$n]))
						{
							$z = '';
							if($ed['add_'.$n]>0)
							{
								$z = '+';
							}
							$tr .= '<br>'.$this->is[$n].': '.$z.''.$ed['add_'.$n];
						}
						$x++;
					}
					if($tr!='')
					{
						$ei .= $tr;
					}
					if($e['info']!='')
					{
						$ei .= '<br><i>Информация:</i><br>'.$e['info'];
					}
					if($e['img2']!='' && $e['img']=='icon_none.gif')
					{
						$e['img'] = $e['img2'];
					}
					if($e['type1']==18 || $e['type1']==19)
					{
					$e['img'] = $e['img2'];
					}
					$eff .= '<img width="38" style="margin:1px;display:block;float:left;" src="http://img.xcombats.com/i/eff/'.$e['img'].'"onMouseOver="top.hi(this,\''.$ei.'\',event,3,1,1,1,\'\');" onMouseOut="top.hic(event);" onMouseDown="top.hic(event);" >';
				}elseif($e['timeUse']+$e['timeAce']+$e['actionTime']<time() && $e['timeUse']!=77)
				{
					//удаляем эффект
					$this->endEffect($e['id'],$u);
				}
			}
			//здоровье
			$hptop = 0;
			$lh = array(0=>'hp_none',1=>1);
			$lh[1] = floor((0+$sn['hpNow'])/(0+$sn['hpAll'])*120);
			if($lh[1]>0){  $lh[0] = 'hp_1'; }
			if($lh[1]>32){ $lh[0] = 'hp_2'; }
			if($lh[1]>65){ $lh[0] = 'hp_3'; }
			if($sn['mpAll']>0)
			{
				//мана
				$lm = array(0=>'hp_none',1=>1);
				$lm[1] = floor($sn['mpNow']/$sn['mpAll']*120);
				if($lm[1]>0){ $lm[0] = 'hp_mp'; }
				$hpmp .='<div id="vmp'.$u['id'].'" title="Уровень маны" align="left" class="seemp" style="position:absolute; top:10px; width:120px; height:10px; z-index:12;"> '.floor($sn['mpNow']).'/'.(0+$sn['mpAll']).'</div>
						 <div title="Уровень маны" class="hpborder" style="position:absolute; top:10px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
						 <div class="'.$lm[0].' senohp" style="height:9px; position:absolute; top:10px; width:'.$lm[1].'px; z-index:11;" id="lmp'.$u['id'].'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
						 <div title="Уровень маны" class="hp_none" style="position:absolute; top:10px; width:120px; height:10px; z-index:10;"></div>';
			}else{
				$hptop = 5;
			}
			$hpmp = '<div id="vhp'.$u['id'].'" title="Уровень жизни" align="left" class="seehp" style="position:absolute; top:'.$hptop.'px; width:120px; height:10px; z-index:12;"> '.floor($sn['hpNow']).'/'.(0+$sn['hpAll']).'</div>
					 <div title="Уровень жизни" class="hpborder" style="position:absolute; top:'.$hptop.'px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
					 <div class="'.$lh[0].' senohp" style="height:9px; width:'.$lh[1].'px; position:absolute; top:'.$hptop.'px; z-index:11;" id="lhp'.$u['id'].'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
					 <div title="Уровень жизни" class="hp_none" style="position:absolute; top:'.$hptop.'px; width:120px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div>'.$hpmp;
			//Собираем НР и МР
			$hpmp = '<div style="position:relative;">'.$hpmp.'</div>';
			$lgn = '<b>'.$u['login'].'</b> ['.$u['level'].']<a href="info/'.$u['id'].'" target="_blank"><img title="Инф. о '.$u['login'].'" src="http://img.xcombats.com/i/inf_capitalcity.gif"></a>';
			if($u['clan']!=0)
			{
				$pc = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id`="'.$u['clan'].'" LIMIT 1'));
				$pc['img'] = $pc['name_mini'].'.gif';
				$lgn = '<img title="'.$pc['name'].'" src="http://img.xcombats.com/i/clan/'.$pc['name_mini'].'.gif">'.$lgn;
			}
			if($u['align']>0)
			{
				$lgn = '<img title="'.$this->mod_nm[floor(intval($u['align']))][$u['align']].'" src="http://img.xcombats.com/i/align/align'.$u['align'].'.gif">'.$lgn;
			}
			$pb = '';
			if($u['banned']>0)
			{
				$pb = '<div style="margin:2px;"><font color="red"><b>Персонаж заблокирован</b></font></div>';
			}
			
			$swm = 0; //свитки магии
			$l = 40;
			while($l<=52)
			{
				if(isset($witm[$l]))
				{
					$swm++;
				}else{
					$witm[$l] = '<img title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif">';
				}
				$l++;
			}
			
			$ssm = 0; //слоты сумки
			$l = 59;
			while($l<=62)
			{
				if(isset($witm[$l]))
				{
					$ssm++;
				}else{
					$witm[$l] = '<img width="60" height="60" title="Пустой слот сумка" src="http://img.xcombats.com/i/items/w/w83.gif">';
				}
				$l++;
			}
			
			$witmg = '';
			if($swm>0 && $i1==0)
			{
				$witmg .= '<table width="240" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40" height="25">'.$witm[40].'</td>
    <td width="40">'.$witm[41].'</td>
    <td width="40">'.$witm[42].'</td>
    <td width="40">'.$witm[43].'</td>
    <td width="40">'.$witm[44].'</td>
    <td width="40">'.$witm[50].'</td>
  </tr>
  <tr>
    <td height="25">'.$witm[45].'</td>
    <td>'.$witm[46].'</td>
    <td>'.$witm[47].'</td>
    <td>'.$witm[48].'</td>
    <td>'.$witm[49].'</td>
    <td>'.$witm[51].'</td>
  </tr>
</table>';
			}
			if($ssm>0 && $i1==0)
			{
				$witmg .= '<table width="240" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60" height="60">'.$witm[59].'</td>
    <td width="60">'.$witm[60].'</td>
    <td width="60">'.$witm[61].'</td>
    <td width="60">'.$witm[62].'</td>
  </tr>
</table>';
			}
			
			$rt[0] .= '<div style="width:246px; padding:2px;" align="center">'.$lgn.'</div>
			<div style="width:240px; padding:2px; border-bottom:1px solid #666666; border-right:1px solid #666666; border-left:1px solid #FFFFFF; border-top:1px solid #FFFFFF;">
			<div align="center"><!-- blocked -->'.$pb.'</div>
			<table width="240" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="60" valign="top">
				<table width="60" height="280" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="60">'.$witm[1].'</td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[2].'</td>
				  </tr>
				  <tr>
					<td height="60">'.$witm[3].'</td>
				  </tr>
				  <tr>
					<td height="80">'.$witm[4].'</td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[7].'</td>
				  </tr>
				</table>
				</td>
				<td height="280" valign="top">
				<table width="120" height="280" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="20" valign="top">
					<!-- HP and MP -->
					'.$hpmp.'
					<!-- -->
					</td>
				  </tr>
				  <tr>
					<td valign="top">
					<div style="position:relative;height:220px;">
						<!-- образ -->
							<div style="position:absolute; width:120px; height:220px; z-index:1;"><a href="#obraz_pers"><img id="infobr" width="120" height="220" src="http://img.xcombats.com/i/obraz/'.$u['sex'].'/'.$u['obraz'].'" '.$oi.'></a></div>
							<div style="position:absolute; width:120px; height:220px; z-index:3;" align="left">'.$eff.'</div>'.$anml.'
					</div>
					</td>
				  </tr>
				  <tr>
					<td height="40"><div align="center">'.$msl.'</div></td>
				  </tr>
				</table>
				</td>
				<td width="60" valign="top">
				<table width="60" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="20">'.$witm[8].'</td>
				  </tr>
				  <tr>
					<td height="20">'.$witm[9].'</td>
				  </tr>
				  <tr>
					<td height="20"><table width="60" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="20" height="20">'.$witm[10].'</td>
						  <td width="20">'.$witm[11].'</td>
						  <td width="20">'.$witm[12].'</td>
						</tr>
					</table></td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[13].'</td>
				  </tr>
				  <tr>
					<td height="60">'.$witm[14].'</td>
				  </tr>
				  <tr>
					<td height="80">'.$witm[16].'</td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[17].'</td>
				  </tr>
				</table>
				</td>
			  </tr>
			</table>'.$witmg.'
			</div>';
			if($i1==0 && $u['battle']==0)
			{
				$rt[0] .= '<script>top.lafstReg['.$u['id'].'] = 0; top.startHpRegen("main",'.$u['id'].','.(0+$sn['hpNow']).','.(0+$sn['hpAll']).','.(0+$sn['mpNow']).','.(0+$sn['mpAll']).','.(time()-$u['regHP']).','.(time()-$u['regMP']).','.(0+$this->rgd[0]).','.(0+$this->rgd[1]).',1);</script>';
			}
			if($ivv==0 && $i1==0)
			{
				$rt[0] .= $this->info_remont();
			}
		}
		return $rt;
	}
	
	public function endEffect($id,$u)
	{
		$e = mysql_fetch_array(mysql_query('SELECT `eu`.*,`em`.* FROM `eff_users` AS `eu` LEFT JOIN `eff_main` AS `em` ON (`eu`.`id_eff` = `em`.`id2`) WHERE `eu`.`id`="'.mysql_real_escape_string($id).'" AND `delete`="0"'));
		if(isset($e['id']))
		{
			$upd = mysql_query('UPDATE `eff_users` SET `delete`="'.time().'" WHERE `id` = "'.$e['id'].'" LIMIT 1');
			if($upd)
			{
				$po = $this->lookStats($e['data']);
				if(isset($po['finish_file']))
				{
					if(file_exists('_incl_data/class/magic/'.$po['finish_file'].'.php'))
					{
						require('_incl_data/class/magic/'.$po['finish_file'].'.php');
					}else{
						$this->error2 = '!File not exists &quot;cgi-bin/magic.pl?use_'.$po['finish_file'].'&quot;.';
					}
				}
				if(isset($u['id']) && ($e['type1']<11 || ($e['type1']>16 && $e['type1']<23)) && $e['noch']==0)
				{
					$text = 'Закончилось действие эффекта &quot;<b>'.$e['name'].'</b>&quot;';
					mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('".$u['city']."','".$u['room']."','','".$u['login']."','".$text."','-1','10','0')");
					if($u['battle']>0)
					{
						$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
						if(isset($lastHOD['id']))
						{
							$id_hod = $lastHOD['id_hod'];
							if($lastHOD['type']!=6)
							{
								$id_hod++;
							}
							mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u['battle'].'","'.($id_hod).'","{tm1} '.$text.' у персонажа {u1}.","login1='.$u['login'].'||t1='.$u['team'].'||time1='.time().'","","","","","6")');
						}
					}
				}
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	public function snatItem($id,$uid)
	{
		if($uid!=0)
		{
			$au = 'AND `iu`.`uid`="'.mysql_real_escape_string($uid).'"';
		}else{
			$au = '';
		}
		$itm = mysql_fetch_array($cl = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id`="'.mysql_real_escape_string($id).'" AND `iu`.`inOdet`!="0" '.$au.' AND `iu`.`delete`="0" LIMIT 1 FOR UPDATE'));
		if(isset($itm['id']))
		{
			$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`inOdet`="0" WHERE `id`="'.$itm['id'].'" LIMIT 1');
			if($upd)
			{
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	public function snatItemAll($uid)
	{
		$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`inOdet`="0" WHERE `uid`="'.$uid.'" AND `inOdet`!="0" AND `delete`="0" LIMIT 100');
		if($upd)
		{
			return 1;
		}else{
			return 0;
		}
	}
	
	public function impStats($m)
	{
		$i = 0;
		$k = array_keys($m);
		$d = '';
		while($i<=count($k))
		{
			if($k[$i]!='')
			{
				$d .= $k[$i].'='.$m[$k[$i]].'|';
			}
			$i++;
		}
		$d = rtrim($d,'|');
		return $d;
	}
	
	public function odetItem($id,$uid)
	{
		if($uid!=0)
		{
			$au = 'AND `iu`.`uid`="'.mysql_real_escape_string($uid).'"';
		}else{
			$au = '';
		}
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id`="'.mysql_real_escape_string($id).'" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" '.$au.' AND `iu`.`delete`="0" LIMIT 1'));
		if(isset($itm['id']))
		{
			
			if($itm['group'] == 1) {
				//Группа предметов
				if($this->itemsX($itm['id'])>1) {
					//вытаскиваем предмет из группы
					$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`inOdet`="0" AND `iu`.`inShop`="0" '.$au.' AND `iu`.`delete`="1000" AND `iu`.`inGroup` = "'.$itm['id'].'" LIMIT 1'));
					if(!isset($itm['id'])) {
						$this->error = 'Группа предметов ошибочна...';
						$notr++;
					}else{
						$itm['delete'] = 0;
					}
				}
			}
			
			
			$tr = $this->lookStats($itm['data']);
			$notr = $this->trItem($tr);
			$msb = '';
			if(isset($tr['art']) && $this->stats['art']>=2) {
				$this->error = 'Вы не можете надеть более двух артефактов';
				$notr++;
			}
			if(isset($tr['sudba']))
			{
				if($tr['sudba']!='0' && $tr['sudba']!=$this->info['login'])
				{
					$notr++;
				}elseif($tr['sudba']=='0'){
					$tr['sudba'] = $this->info['login'];
					$itm['data'] = $this->impStats($tr);
					$msb = ',`data`="'.$itm['data'].'"';
				}
			}
			if($notr>0)
			{
				//Не хватает характеристик или не совпадают условия
				if(isset($tr['open']) && isset($_GET['open']))
				{
					$this->error = 'Вы не можете открыть данный предмет';
				}else{
					$this->error = 'Вы не можете надеть данный предмет';
				}
				return 0;
			}elseif(isset($tr['open']) && isset($_GET['open']))
			{
				//открываем предмет
				$io = '';
				$i = 0;
				$itms = explode(',',$tr['items_in']);
				while($i<count($itms))
				{
					if(isset($itms[$i]))
					{
						$x = 0;
						$itms[$i] = explode('*',$itms[$i]);
						$x += (int)$itms[$i][1];
						$itms[$i] = $itms[$i][0];
						$s = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id`="'.((int)$itms[$i]).'" LIMIT 1'));
						if(isset($s['id']))
						{
							$j = 1;
							while($j<=$x)
							{
								$pid = $this->addItem($s['id'],$this->info['id']);
								if($pid>0)
								{
									mysql_query('UPDATE `items_users` SET `gift` = "'.$itm['gift'].'" WHERE `id` = "'.$pid.'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
								}
								$j++;
							}
							$io .= ''.$s['name'].' (x'.$x.'), ';	
						}
					}
					$i++;
				}
				if($itm['inGroup'] > 0) {
					mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
				$this->deleteItem($itm['id'],$this->info['id']);
				$this->error = 'Вы успешно открыли &quot;'.$itm['name'].'&quot;, внутри было найдено:<br>'.$io.'...';
			}else{
				$inSlot = $itm['inslot'];
				$s = mysql_query('SELECT `iu`.`id`,`iu`.`inOdet` FROM `items_users` AS `iu` WHERE `iu`.`inOdet`!="0" AND `iu`.`uid`="'.$uid.'" AND `iu`.`delete`="0"');
				$d = array();
				while($p = mysql_fetch_array($s))
				{
					$d[$p['inOdet']] = $p['id'];
				}
				
				//Если в слот оружия и можно одеть в левую руку
				if($itm['2too']==1 && $inSlot==3 && isset($d[3]))
				{
					$inSlot = 14;
				}
				
				if($inSlot==3 || $inSlot==14)
				{
					//Проверяем есть-ли двуручное оружие
				    if($this->stats['items'][$this->stats['wp3id']]['2h']==1 || $this->stats['items'][$this->stats['wp14id']]['2h']==1 || $itm['2h']==1)
					{
						$this->snatItem($this->stats['items'][$this->stats['wp3id']]['id'],$uid);
						$this->snatItem($this->stats['items'][$this->stats['wp14id']]['id'],$uid);					
					}
				}
				
				if(isset($d[$inSlot]))
				{
					if($inSlot==10)
					{
						if(!isset($d[12]))
						{
							$inSlot = 12;
						}elseif(!isset($d[11]))
						{
							$inSlot = 11;	
						}
					}elseif($inSlot==40)
					{
						$i = 40;
						while($i<=50)
						{
							if(!isset($d[$i]))
							{
								$inSlot = $i;
								$i = 51;
							}elseif($i==50)
							{
								$inSlot = 50;	
							}
							$i++;
						}
					}elseif($inSlot==53)
					{
						if(!isset($d[53]))
						{
							$inSlot = 53;
						}elseif(!isset($d[54]))
						{
							$inSlot = 54;	
						}
					}elseif($inSlot==56)
					{
						if(!isset($d[56]))
						{
							$inSlot = 56;
						}elseif(!isset($d[57]))
						{
							$inSlot = 57;	
						}elseif(!isset($d[58]))
						{
							$inSlot = 58;	
						}else{
							$inSlot = 58;
						}
					}elseif($inSlot==59)
					{
						if(!isset($d[59]))
						{
							$inSlot = 59;
						}elseif(!isset($d[60]))
						{
							$inSlot = 60;	
						}elseif(!isset($d[61]))
						{
							$inSlot = 61;	
						}elseif(!isset($d[62]))
						{
							$inSlot = 62;	
						}
					}
				}
				if(isset($d[$inSlot]))
				{
					$this->snatItem($d[$inSlot],$uid);
				}
				$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`inOdet`="'.$inSlot.'"'.$msb.' WHERE `id`="'.$itm['id'].'" LIMIT 1');
				if($itm['inGroup'] > 0) {
					mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
				if($upd)
				{
					//Если предмет привязывается после одевания
					//if($itm[''])
					//{
					//	
					//}
					return 1; 
				}else{
					$this->error = '(!) Ошибка обновления данных';
					return 0; 
				}
			}
		}else{
			$this->error = 'Предмет не найден в вашем рюкзаке';
			return 0;
		}
	}	
	
	public function deleteItem($id,$uid)
	{
		if($uid!=0)
		{
			$au = 'AND `iu`.`uid`="'.mysql_real_escape_string($uid).'"';
		}else{
			$au = '';
		}
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id`="'.mysql_real_escape_string($id).'" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" '.$au.' AND `iu`.`delete`="0" LIMIT 1'));
		if(isset($itm['id']))
		{
			$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'" WHERE `id`="'.$itm['id'].'" LIMIT 1');
			if($upd)
			{
				$this->error = 'Предмет "'.$itm['name'].'" выброшен';
				$this->addDelo(1,$uid,'&quot;<font color="maroon">System.inventory</font>&quot;: Предмет &quot;<b>'.$itm['name'].'</b>&quot; [itm:'.$itm['id'].'] был <b>выброшен</b>.',time(),$this->info['city'],'System.inventory',0,0);
				return 1;
			}else{
				return 0;
			}
		}else{
			$this->error = 'Предмет не найден в вашем рюкзаке';
		}
	}
	
	public function return_btn()
	{
		return false;
	}
	
	public function getStats($uid,$i1,$res = 0)
	{
		global $c;
		if(count($uid)>1)
		{
			$u = $uid;
		}elseif($uid!=$this->info['id'] || $res==1)
		{
			$u = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`level`,`u`.`login`,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
		}else{
			$u = $this->info;
		}
		
		if(isset($u['id'],$u['stats']))
		{
			$st  = array();
			$s_vi = array();
			$s_v = array();
			$lvl = mysql_fetch_array(mysql_query('SELECT * FROM `levels` WHERE `upLevel` = "'.$u['upLevel'].'" LIMIT 1'));
			if(isset($lvl['upLevel']))
			{
				$st['levels'] = $lvl;
			}else{
				$st['levels'] = 'undefined';
			}
			$st2 = array();
			$st['id'] = $u['id'];
			$st['login'] = $u['login'];
			$st['lvl'] = $u['level'];
			$st['hpNow'] = $u['hpNow'];
			$st['hpAll'] = 0;
			$st['mpNow'] = $u['mpNow'];
			$st['mpAll'] = 0;
			$st['zona'] = 1;
			$st['zonb'] = 2;
			$st['items'] = array();
			$st['effects'] = array();
			$st['reting'] = 0;
			$sts = explode('|',$u['stats']);
			$i = 0; $ste = '';
			//Родные характеристики
			while($i<count($sts))
			{
				$ste = explode('=',$sts[$i]);
				if(isset($ste[1]))
				{
					$st[$ste[0]]  += intval($ste[1]);
					$st2[$ste[0]] += intval($ste[1]);
				}
				$i++;
			}			
			//Характеристики от предметов
			$cl = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`inOdet`!="0" AND `iu`.`uid`="'.$u['id'].'" AND `iu`.`delete`="0"');
			$ia = $this->items['add'];
			$h = 0;
			$hnd1 = 0;
			$hnd2 = 0;
			$sht1 = 0;
			$reitm = array();
			$coms = array(); // комплекты
			$dom = array();
			while($pl = mysql_fetch_array($cl))
			{
				/* Доминирование */
				$dom[count($dom)] = array($pl['inOdet'],$pl['class'],$pl['class_point'],$pl['anti_class'],$pl['anti_class_point'],$pl['level'],$u['level'],$pl['price2']);
				
				$st['reting'] += $pl['price1']+$pl['price2']*20;
				$st['wp'.$pl['inOdet'].'id'] = $h; 
				$st['items'][$h] = $pl;	$h++;
				if($pl['inOdet']==3 && (($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27))
				{
					$hnd1 = 1;
				}
				if($pl['inOdet']==14 && (($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27))
				{
					$hnd2 = 1;
				}elseif($pl['inOdet']==14 && $pl['type']==13)
				{
					$sht1 = 1;
				}
				$sts = explode('|',$pl['data']);
				$i = 0; $ste = ''; $sti = array();
				while($i<count($sts))
				{
					$ste = explode('=',$sts[$i]);
					if(isset($ste[1]))
					{
						$sti[$ste[0]] += intval($ste[1]);
					}
					$i++;
				}
				
				$st['art'] += $sti['art'];
				
				if(isset($sti['complect']))
				{
					$coms[count($coms)]['id'] = $sti['complect'];
					if(!isset($coms['com'][$sti['complect']]))
					{
						$coms['com'][$sti['complect']] = 0;	
						$coms['new'][count($coms['new'])] = $sti['complect'];
					}
					$coms['com'][$sti['complect']]++;
				}
				
				if($sti['zonb']!=0)
				{
					$st['zonb'] += $sti['zonb'];
				}
				
				if($sti['zona']!=0)
				{
					$st['zona'] += $sti['zona'];
				}
				
				//Добавляем статы от данного предмета
				if(!isset($sti['restart_stats']))
				{
					$i = 0;
					while($i<count($ia))
					{
						if(isset($ia[$i]))
						{
							$st[$ia[$i]] += intval($sti['add_'.$ia[$i]]);
						}
						$i++;
					}
				}else{
					$reitm[count($reitm)] = $sti;
				}
				$i = 0;
				while($i<count($ia))
				{
					if(isset($ia[$i]))
					{
						$s_v[$ia[$i]] += intval($sti['sv_'.$ia[$i]]);
					}
					$i++;
				}			
			}
			
			//Сохраненные хар-ки и умения
			if(count($reitm)>0)
			{
				$i39 = array(0=>0,1=>0,2=>0);
				$i = 0;
				while($i<count($reitm))
				{
					if(isset($reitm[$i]['sm_skill']) && $i39[0]==0)
					{
						//умения
						$i9 = 1; $i39[0] = 1;
						while($i9<=7)
						{
							$st['a'.$i9] = $st['a'.$i9]-$st2['a'.$i9]+$reitm[$i]['add_a'.$i9];
							$st['mg'.$i9] = $st['mg'.$i9]-$st2['mg'.$i9]+$reitm[$i]['add_mg'.$i9];
							$i9++;
						}												
					}elseif(isset($reitm[$i]['sm_abil']) && $i39[1]==0)
					{
						//статы
						$i9 = 1; $i39[1] = 1;
						while($i9<=12)
						{
							$st['s'.$i9] = $st['s'.$i9]-$st2['s'.$i9]+$reitm[$i]['add_s'.$i9];
							$i9++;
						}	
					}elseif(isset($reitm[$i]['sm_skill2']) && $i39[2]==0)
					{
						//навыки
						
						$i39[2] = 1;
					}
					$i++;
				}
			}
			
			//Характеристики от эффектов
			$h = 0;
			$efs = mysql_query('SELECT `eu`.*,`em`.* FROM `eff_users` AS `eu` LEFT JOIN `eff_main` AS `em` ON (`eu`.`id_eff` = `em`.`id2`) WHERE `eu`.`uid`="'.mysql_real_escape_string($u['id']).'" AND `eu`.`delete`="0" AND `eu`.`v1`!="priem" LIMIT 50');
			while($e = mysql_fetch_array($efs))
			{
				if($e['timeUse']+$e['timeAce']+$e['actionTime']>time() || $e['timeUse']==77)
				{					
					$st['effects'][$h] = $e;	$h++;
					$sts = $this->lookStats($e['data']);
					$i = 0;
					while($i<count($ia))
					{
						if(isset($ia[$i]))
						{
							$sti[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
							$st[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
						}
						$i++;
					}	
					$i = 0;
					while($i<count($ia))
					{
						if(isset($ia[$i]))
						{
							$s_vi[$ia[$i]] += intval($sts['sv_'.$ia[$i]]);
							$s_v[$ia[$i]] += intval($sts['sv_'.$ia[$i]]);
						}
						$i++;
					}	
				}elseif($e['timeUse']!=77){
					//удаляем эффект					
					$this->endEffect($e['id'],$u);
					$st['act'] = 1;
				}
			}
			
			//Характеристики от приемов
			$efs = mysql_query('SELECT `eu`.* FROM `eff_users` AS `eu` WHERE `eu`.`uid`="'.mysql_real_escape_string($u['id']).'" AND `eu`.`delete`="0" AND `eu`.`v1` = "priem" LIMIT 30');
			$st['set_pog'] = array();
			while($e = mysql_fetch_array($efs))
			{
				$e['type1'] = 14;
				$e['img'] = $e['img2'];
				if($e['timeUse']+$e['timeAce']+$e['actionTime']>time() || $e['timeUse']==77)
				{					
					$st['effects'][$h] = $e;	$h++;
					$sts = $this->lookStats($e['data']);
					if(isset($sts['add_pog']))
					{
						$ctt = count($st['set_pog']);
						$st['set_pog'][$ctt]['id'] = $h;
						$st['set_pog'][$ctt]['y']  = $sts['add_pog'];
						unset($ctt);
					}
					$i = 0;
					while($i<count($ia))
					{
						if(isset($ia[$i]))
						{
							$sti[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
							$st[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
						}
						$i++;
					}	
					$i = 0;
					while($i<count($ia))
					{
						if(isset($ia[$i]))
						{
							$s_vi[$ia[$i]] += intval($sts['sv_'.$ia[$i]]);
							$s_v[$ia[$i]] += intval($sts['sv_'.$ia[$i]]);
						}
						$i++;
					}	
				}elseif($e['timeUse']!=77){
					//удаляем эффект					
					$this->endEffect($e['id'],$u);
					$st['act'] = 1;
				}
			}
			
				
			//Характеристики от статов
			$st['hpAll'] += $st['s4']*6;
			$st['mpAll'] += $st['s6']*10;
			$st['m1']    += $st['s3']*5;
			$st['m2']    += $st['s3']*5;
			$st['m4']    += $st['s2']*5;
			$st['m5']    += $st['s2']*5;
			$st['za']    += $st['s4']*1.5;
			$st['zm']    += $st['s4']*1.5;
			//Мощности
			$st['m10'] += 0;
			$st['m11'] += 0;
			$st['m11a'] += 0;
			$st['m7'] += 0;
			$st['m8'] += 0;
		
			//Бонусы статов
			//сила
			if($st['s1']>24 && $st['s1']<50){ $st['m10']  += 5;  }
			if($st['s1']>49 && $st['s1']<75){ $st['m10']  += 10; }
			if($st['s1']>74 && $st['s1']<100){ $st['m10']  += 17; }
			if($st['s1']>99 && $st['s1']<125){ $st['m10']  += 25; }
			if($st['s1']>124 && $st['s1']<150){ $st['m10'] += 25; $st['minAtack'] += 10; $st['maxAtack'] += 10; }
			if($st['s1']>149){ $st['m10'] += 25; $st['minAtack'] += 20; $st['maxAtack'] += 20; }
			//ловкость
			if($st['s2']>24 && $st['s2']<50){ $st['m7']  += 5;  }
			if($st['s2']>49 && $st['s2']<75){ $st['m7']  += 5; $st['m4']  += 35; $st['m2']  += 15; }
			if($st['s2']>74 && $st['s2']<100){ $st['m7']  += 15; $st['m4']  += 35; $st['m2']  += 15; }
			if($st['s2']>99 && $st['s2']<125){ $st['m7']  += 15; $st['m4']  += 105; $st['m2']  += 40; }
			if($st['s2']>124 && $st['s2']<150){ $st['m7']  += 15; $st['m4']  += 105; $st['m2']  += 40; $st['m15'] += 5; }
			if($st['s2']>149){ $st['m7']  += 20; $st['m4']  += 155; $st['m2']  += 50; $st['m15'] += 7; }
			//интуиция
			if($st['s3']>24 && $st['s3']<50){ $st['m3'] += 10; }
			if($st['s3']>49 && $st['s3']<75){ $st['m3'] += 10; $st['m1'] += 35; $st['m5'] += 15; }
			if($st['s3']>74 && $st['s3']<100){ $st['m3'] += 25; $st['m1'] += 35; $st['m5'] += 15; }
			if($st['s3']>99 && $st['s3']<125){ $st['m3'] += 25; $st['m1'] += 105; $st['m5'] += 45; }
			if($st['s3']>124 && $st['s3']<150){ $st['m3'] += 25; $st['m1'] += 105; $st['m5'] += 45; $st['m14'] += 5; }
			if($st['s3']>149){ $st['m3'] += 30; $st['m1'] += 125; $st['m5'] += 55; $st['m14'] += 7; }
			//выносливость
			if($st['s4']>0){ $st['hpAll']   += 30;  }
			if($st['s4']>24 && $st['s4']<50){ $st['hpAll']  += 50;  }
			if($st['s4']>49 && $st['s4']<75){ $st['hpAll']  += 100; }
			if($st['s4']>74 && $st['s4']<100){ $st['hpAll']  += 175; }
			if($st['s4']>99 && $st['s4']<125){ $st['hpAll']  += 250; }
			if($st['s4']>124 && $st['s4']<150){ $st['hpAll'] += 250; $st['za'] += 25; }
			if($st['s4']>149){ $st['hpAll'] += 500; $st['za'] += 25; $st['zm'] += 25; }
			//интелект
			if($st['s5']>24 && $st['s5']<50){ $st['m11'] += 5; }
			if($st['s5']>49 && $st['s5']<75){ $st['m11'] += 10;  }
			if($st['s5']>74 && $st['s5']<100){ $st['m11'] += 17; }
			if($st['s5']>99 && $st['s5']<125){ $st['m11'] += 25; }
			if($st['s5']>124 && $st['s5']<150){ $st['m11'] += 35;  }
			if($st['s5']>149){ $st['m11'] += 50; }
			//мудрость
			if($st['s6']>24 && $st['s6']<50){ $st['mpAll'] += 50; }
			if($st['s6']>49 && $st['s6']<75){ $st['mpAll'] += 100; }
			if($st['s6']>74 && $st['s6']<100){ $st['mpAll'] += 175; $st['speedmp'] += 375; }
			if($st['s6']>99 && $st['s6']<125){ $st['mpAll'] += 250; $st['speedmp'] += 500; }
			if($st['s6']>124 && $st['s6']<150){ $st['mpAll'] += 250; $st['speedmp'] += 500; $st['pzm'] += 3; }
			if($st['s6']>149){ $st['mpAll'] += 300; $st['speedmp'] += 750; $st['pzm'] += 5; }
			
			/* Владения */
			$i = 1;						
			while($i<=7)
			{				
				$st['pm'.$i] += $st['s5']*0.5 + $st['m11a'];				
				$st['a'.$i] += $st['aall'];
				$st['mg'.$i] += $st['m2all'];
				$st['zm'.$i] += $st['zm'];
				if($i<=4)
				{					
					$st['mib'.$i] += 0;
					$st['mab'.$i] += 0;
					$st['mg'.$i] += $st['mall'];
					$st['pm'.$i] += $st['m11'];
					$st['pa'.$i] += $st['m10'];
					$st['za'.$i] += $st['za'];
				}				
				$i++;
			}
			
			//если второе оружие одето
			if($hnd2==1 && $hnd1==1)
			{
				$st['zona']++;
			}
			
			if($sht1==1)
			{
				$st['zonb']++;
			}
			
			//Бонусы комплектов
			$i = 0;
			while($i<=count($coms['new']))
			{
				if(isset($coms['new'][$i]))
				{
					//$coms[$i]['id'] - id комплекта, $j - кол-во предметов данного комплекта
					$j = $coms['com'][$coms['new'][$i]];
					$com = mysql_fetch_array(mysql_query('SELECT * FROM `complects` WHERE `com` = "'.((int)$coms['new'][$i]).'" AND `x` <= '.((int)$j).' ORDER BY  `x` DESC  LIMIT 1'));					
					if(isset($com['id']))
					{						
						//добавляем действия комплекта
						$ij = 0;
						$sti = $this->lookStats($com['data']);
						while($ij<count($ia))
						{
							if(isset($ia[$ij]) && isset($sti[$ia[$ij]]))
							{
								$st[$ia[$ij]] += $sti[$ia[$ij]];
							}
							$ij++;
						}	
					}
				}
				$i++;
			}
			
			//конец бонусов
			if($st['hpNow']<0)	
			{
				$st['hpNow'] = 0;
			}elseif($st['hpNow']>$st['hpAll'])
			{
				$st['hpNow'] = $st['hpAll'];
			}
			if($st['mpNow']<0)	
			{
				$st['mpNow'] = 0;
			}elseif($st['mpNow']>$st['mpAll'])
			{
				$st['mpNow'] = $st['mpAll'];
			}
			
			//зоны блока и удара
			if($st['zona']<1){ $st['zona'] = 1; }
			if($st['zona']>5){ $st['zona'] = 5; }
			if($st['zonb']<1){ $st['zonb'] = 1; }
			if($st['zonb']>3){ $st['zonb'] = 3; }
			
			$st['weapon1'] = $hnd1;
			$st['weapon2'] = $hnd2;
			$st['sheld1']  = $sht1;
			$st['sv_']     = $s_v;
			$st['sv_i']    = $s_vi;
			$st['dom']     = $dom;		
			
			$rt = array();
			if($i1==1)
			{
				$rt[0] = $st;
				$rt[1] = $st2; //родные статы
			}else{
				$rt = $st;
			}
		}
		return $rt;
	}	
	
	public function send($color,$room,$city,$from,$to,$text,$time,$type,$toChat,$spam,$sound,$new = 1)
	{
		mysql_query("INSERT INTO `chat` (`new`,`sound`,`color`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`spam`) VALUES ('".$new."','".$sound."','".$color."','".$city."','".$room."','".$from."','".$to."','".$text."','".$time."','".$type."','".$toChat."','".$spam."')");
		$msg_id = mysql_insert_id();
		return $msg_id;
	}
	
	//получаем уровень
	public function testLevel()
	{
		global $c;
		$rt = 0;
		if(isset($this->stats['levels']['upLevel']))
		{
			$telvl = array(	98 => 120000000,97 => 52000000,96 => 50000000,95 => 48000000,94 => 46000000,93 => 45000000,92 => 44000000,91 => 42000000,90 => 40000000,89 => 38000000,88 => 36000000,87 => 35000000,86 => 34000000,85 => 32000000,84 => 30000000,83 => 20000000,82 => 19500000,81 => 19000000,80 => 18000000,79 => 17500000,78 => 17000000,77 => 16000000,76 => 15500000,75 => 14000000,74 => 13000000,73 => 10000000,72 => 9900000,71 => 9750000,70 => 9500000,69 => 9250000,68 => 9000000,67 => 8500000,66 => 7500000,65 => 6500000,64 => 6000000,63 => 3000000,62 => 2800000,61 => 2600000,60 => 2500000,59 => 2400000,58 => 2300000,57 => 2175000,56 => 2000000,55 => 1750000,54 => 1500000,53 => 300000,52 => 280000,51 => 260000,50 => 250000,49 => 225000,48 => 200000,47 => 175000,46 => 150000,45 => 75000,44 => 60000,43 => 30000,42 => 27000,41 => 23000,40 => 21000,39 => 19000,38 => 17000,37 => 15500,36 => 14000,35 => 12500,34 => 12000,33 => 11000,32 => 10000,31 => 9000,30 => 8000,29 => 7000,28 => 6000,27 => 5000,26 => 4600,25 => 4200,24 => 3800,23 => 3350,22 => 2900,21 => 2500,20 => 2200,19 => 2050,18 => 1850,17 => 1650,16 => 1450,15 => 1300,14 => 1100,13 => 950,12 => 830,11 => 670,10 => 530,9 => 410,8 => 350,7 => 280,6 => 215,5 => 160,4 => 110,3 => 75,2 => 45,1 => 20);
			echo '['.$telvl[$this->info['upLevel']].']';
			$lvl  = mysql_fetch_array(mysql_query('SELECT * FROM `levels` WHERE `upLevel`="'.$this->info['upLevel'].'" LIMIT 1'));			
			$lvln = mysql_fetch_array(mysql_query('SELECT * FROM `levels` WHERE `upLevel`="'.($lvl['upLevel']+1).'" LIMIT 1'));
			//Кристал вечности
			$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "1204" AND `delete` = "0" AND `uid` = "'.$this->info['id'].'" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
			if($this->info['exp']>12499 && $this->info['level']<=5)
			{
				if(!isset($itm['id']))
				{
					$this->info['exp'] = 12499;
					mysql_query('UPDATE `stats` SET `exp` = "12499" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				}else{
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
					$text = 'Предмет &quot;<b>Кристалл Вечности [6]</b>&quot; был успешно использован.';
					echo 'chat.sendMsg(["new","'.time().'","6","","'.$this->info['login'].'","'.$text.'","Black","1","1","0"]);';
					//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1',".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','".$text."','".time()."','6','0')");
				}

			}
			//****************
			$i = 0;
			while($i!=1)
			{
				if($this->info['exp']>=$lvl['exp'] && isset($lvln['upLevel']))
				{
					//берем апп или уровень, $lvln
					if($lvl['nextLevel']>$this->info['level'])
					{
						//повышаем выносливость 
						$a4 = 1;
						if($lvl['nextLevel']==9)
						{
							$a4 = 2;
						}elseif($lvl['nextLevel']==10)
						{
							$a4 = 3;
						}elseif($lvl['nextLevel']==11)
						{
							$a4 = 5;
						}
						
						$this->stats['s4'] += $a4;
						$sex1 = '';
						if($this->info['sex']==1)
						{
							$sex1 = 'ла';
						}
						$this->send('',$this->info['room'],$this->info['city'],'','','<b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$lvl['nextLevel'].'!',time(),6,0,0,0,1);
						
						//Рефералы
						if(round($this->info['host_reg']) > 0) {
							$mtest = mysql_fetch_array(mysql_query('SELECT * FROM `mults` WHERE (`uid` = "'.$this->info['id'].'" AND `uid2` = "'.((int)$this->info['host_reg']).'") OR (`uid2` = "'.$this->info['id'].'" AND `uid` = "'.((int)$this->info['host_reg']).'") LIMIT 1'));
							$rlog = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.((int)$this->info['host_reg']).'" LIMIT 1'));
							$rlogs = mysql_fetch_array(mysql_query('SELECT `id`,`ref_data` FROM `stats` WHERE `id` = "'.((int)$this->info['host_reg']).'" LIMIT 1'));
							
							if(!isset($mtest['id']) && isset($rlog['id'])) {
								$rfs['data'] = explode('|',$rlogs['ref_data']);
								$bnk = $rfs['data'][0];
								if($bnk < 1) {
									$bnk = mysql_fetch_array(mysql_query('SELECT `id` FROM `bank` WHERE `uid` = "'.((int)$rlog['id']).'" AND `block` = "0" LIMIT 1'));
									$bnk = $bnk['id'];
								}
								$ekr = '0.00';
								$bn = mysql_fetch_array(mysql_query('SELECT * FROM `referal_bous` WHERE `type` = 1 AND `level` = "'.$lvl['nextLevel'].'" LIMIT 1'));
								if(isset($bn['id'])) {
									$ekr = $bn['add_bank'];
									$up = mysql_query('UPDATE `bank` SET `money2` = `money2` + '.$ekr.' WHERE `id` = "'.mysql_real_escape_string($bnk).'" LIMIT 1');
									if($up) {
										$this->send('',$this->info['room'],$this->info['city'],'',$rlog['login'],'Ваш реферал <b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$lvl['nextLevel'].'! На Ваш банковский счет №'.$bnk.' зачисленно '.$ekr.' Ekr.',-1,6,0,0,0,1);
										$this->info['catch'] += $ekr;
										mysql_query('UPDATE `users` SET `catch` = "'.floor($this->info['catch']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
									}else{
										$this->send('',$this->info['room'],$this->info['city'],'',$rlog['login'],'Ваш реферал <b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$lvl['nextLevel'].'! (Ошибка зачисления, обратитесь с Администрации проекта) На Ваш банковский счет №'.$bnk.' зачисленно '.$ekr.' Ekr.',-1,6,0,0,0,1);
									}
								}
							}elseif(isset($rlog['id'])){
								$this->send('',$this->info['room'],$this->info['city'],'',$rlog['login'],'Ваш реферал <b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$lvl['nextLevel'].'! <small><font color=red>(Из-за возможных пересечений по IP - Вы не получаете за это награду)</font></small>',-1,6,0,0,0,1);
							}
						}
						
						$tst = $this->lookStats($this->info['stats']);
						$tst['s4'] += $a4;
						$this->info['stats'] = $this->impStats($tst);
					}
					
					$this->info['level'] = $lvl['nextLevel'];
					$this->stats['levels'] = $lvln;
					$this->info['ability'] += $lvl['ability'];
					$this->info['skills'] += $lvl['skills'];
					$this->info['sskills'] += $lvl['sskills'];
					$this->info['nskills'] += $lvl['nskills'];
					$this->info['money'] = $lvl['money']+$this->info['money'];
					$lvl  = $lvln;
					$lvln = mysql_fetch_array(mysql_query('SELECT * FROM `levels` WHERE `upLevel`="'.($lvl['upLevel']+1).'" LIMIT 1'));
					$this->info['upLevel'] += 1;
					$rt++;				
				}else{
					$i = 1;
				}
			}
			if($rt>0)
			{
				$upd = mysql_query('UPDATE `users` SET `level` = "'.$this->info['level'].'",`money` = "'.$this->info['money'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				if($upd)
				{
					mysql_query('UPDATE `stats` SET `ability` = "'.$this->info['ability'].'",`skills` = "'.$this->info['skills'].'",`nskills` = "'.$this->info['nskills'].'",`sskills` = "'.$this->info['sskills'].'",`stats` = "'.$this->info['stats'].'",`upLevel` = "'.$this->info['upLevel'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					$this->info['money'] = $this->r2($this->info['money']);
				}
				return 1;
			}
			//****************
		}
		if($this->info['animal']>0)
		{
			//уровень зверя
			$a = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$this->info['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
			if(isset($a['id']))
			{
				$ea = array(
					0=>0,
					1=>110,
					2=>420,
					3=>1300,
					4=>2500,
					5=>5000,
					6=>12500,
					7=>30000,
					8=>300000,
					9=>3000000
				);
				$mx = array(
					0=>50,
					1=>75,
					2=>100,
					3=>125,
					4=>150,
					5=>175,
					6=>300,
					7=>600,
					8=>1200,
					9=>2500
				);
				$iz = 0;
				while($iz!=-1)
				{
					if($ea[$a['level']+1]<=$a['exp'])
					{
						//поднимаем уровень
						$a['level']++;
						$a['max_exp'] = $mx[$a['level']];				
					}else{
						if($iz>0)
						{
							$a['stats'] = mysql_fetch_array(mysql_query('SELECT * FROM `levels_animal` WHERE `type` = "'.$a['type'].'" AND `level` = "'.$a['level'].'" LIMIT 1'));
							$a['stats'] = $a['stats']['stats'];
							mysql_query('UPDATE `users_animal` SET `stats` = "'.$a['stats'].'",`level`="'.$a['level'].'",`max_exp`="'.$a['max_exp'].'" WHERE `id` = "'.$a['id'].'" LIMIT 1');
						}
						$iz = -2;
					}
					$iz++;
				}
			}
		}
	}
	
	public function r2($v)
	{
		$v = number_format($v, 2, '.', ' ');
		return $v;
	}
		
	public function regen($uid,$st,$i1)
	{
		if($uid!=$this->info['id'])
		{
			$u = mysql_fetch_array(mysql_query('SELECT `u`.`align`,`u`.`clan`,`u`.`battle`,`u`.`animal`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`sex`,`u`.`obraz`,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
			if(!isset($st['hpAll']))
			{
				$st = $this->getStats($uid,$i1);
			}
		}else{
			$u  = $this->info;
			if(isset($this->stats['hpAll']))
			{
				$st = $this->stats;
			}elseif($st!=0)
			{
				
			}else{
				$st = $this->getStats($uid,$i1);
			}
		}
		$btl = 0;
		if($u['battle']>0)
		{
			//$btl = mysql_fetch_array(mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `id` = "'.$this->info['battle'].'" AND `team_win` = "-1" LIMIT 1'));
		}
		if($u['battle']==0 || (isset($btl['id']) && $btl['time_start']>$this->info['timereg']))
		{
			$sth = $u['minHP']; //Стандартное время восстановления в минутах HP
			$stm = $u['minMP']; //Стандартное время восстановления в минутах MP
			$sh = 0; //Скорость регенерации НР в 1 сек.
			$sm = 0; //Скорость регенерации MР в 1 сек.
			/*---Двужильный(Особенность)---*/
			if($st['os9']>0){
				if($st['os9']==5) {
					$st['os9']=6;
				}
				$sth = floor($u['minHP']-($u['minHP']/100)*($st['os9']*5));
			}
			/*---Двужильный(Особенность)---*/
			/*---Здравомыслящий(Особенность)---*/
			if($st['os10']>0){
				$stm = floor($u['minMP']-($u['minMP']/100)*($st['os10']*5));
			}
			/*---Здравомыслящий(Особенность)---*/
			if($u['battle']>0)
			{
				$this->info['timereg'] = $btl['time_start'];
			}
			
			//Тестеры первой волны
			//тесты боев
			//$st['speedhp'] += 150;
			//$st['speedmp'] += 150;
			
			//hp
			$sh = ($st['hpAll']/(60*$sth));
			$sh += ($sh/100)*(1+$st['speedhp']+$st['levels']['hpRegen']);
			$st['hpNow'] += $sh*(time()-$u['regHP']);
			if($st['hpNow']<0)	
			{
				$st['hpNow'] = 0;
			}elseif($st['hpNow']>$st['hpAll'])
			{
				$st['hpNow'] = $st['hpAll'];
			}
			//mp
			$sm = ($st['mpAll']/(60*$stm));
			$sm += ($sm/100)*(1+$st['speedmp']+$st['levels']['mpRegen']);
			$st['mpNow'] += $sm*(time()-$u['regMP']);
			if($st['mpNow']<0)	
			{
				$st['mpNow'] = 0;
			}elseif($st['mpNow']>$st['mpAll'])
			{
				$st['mpNow'] = $st['mpAll'];
			}
			//Заносим новые данные в базу
			$upd = mysql_query('UPDATE `stats` SET `regHP`="'.time().'",`regMP`="'.time().'",`hpNow`="'.$st['hpNow'].'",`mpNow`="'.$st['mpNow'].'" WHERE `id` = "'.$u['id'].'" LIMIT 1');
			if(!$upd)
			{
				return array(0=>0,1=>0);
			}else{
				if($this->info['id']==$u['id'])
				{
					$this->stats['regHP'] = time();
					$this->stats['regMP'] = time();
					$this->stats['hpNow'] = $st['hpNow'];
					$this->stats['mpNow'] = $st['mpNow'];
				}
				return array(0=>$sh,1=>$sm,'hpNow'=>$st['hpNow'],'mpNow'=>$st['mpNow']);
			}
		}
	}
}

$u = user::start();
?>