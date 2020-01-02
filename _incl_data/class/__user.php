<?php
if(!defined('GAME')) { die(); }

//system('pkill www-data');

function getdr($s, $v, $d) {
  global $u;
  $i = 0;
  while($i < count($v))	{
	if(isset($v[$i])) {
	  $s = str_replace('{'.$v[$i].'}',$d[$i],$s);
	}
	$i++;
  }
  $s = eval("return (".$s.");");
  return floor($s);
}

class user {
	private static $flag_one;
	public $pokol = 2; //Акктуальное поколение предметов
	public $ekrcast = array(
		310 => true, //Снадобье Забытых Мастеров
		
		33	=> true, //Звездная Энергия
		34	=> true, //Звездная Тяжесть
		35	=> true, //Звездная Сияние
		
		42	=> true, //Неуязвимость Оружию	
		43	=> true, //Неуязвимость Стихиям
		
		296	=> true
		
	);
	
	public function updateAlign($align,$uid) {
		
	}
	
	public function setOnline($online,$uid,$afk,$gj = false)
	{
		if( $gj == true ) {
			/*$add = 0;			
			if(time()-$online >= 60)
			{
				if(time()-$online < 60) {
					$add += time()-$online;
				}else{
					$add += 1;
				}
			}			
			if( $add > 5 * 60 ) {
				$add = 5 * 60;
			}			
			$afk = 0;
			
			if( $uid == 12345 ) {
				$on = mysql_fetch_array(mysql_query('SELECT * FROM `online` WHERE `uid` = "'.$uid.'" LIMIT 1'));
				echo '['.$add.'|'.(time()-$on['lastUp']).']';
			}*/
			
			//if($add > 0) {
				$on = mysql_fetch_array(mysql_query('SELECT * FROM `online` WHERE `uid` = "'.$uid.'" LIMIT 1'));
				if(isset($on['id'])) {
					$add = (time()-$on['lastUp']);
					if( $add > 180 ) {
						$add = 180;
					}
					$mt = 0;
					$lst = time();
					if( $add > 0 ) {
						if(date('d',$on['lastUp'])!=date('d',$lst)) {
							$on['time_today'] = $add;
						}else{
							$on['time_today'] += $add;
						}
						$add = $on['time_all']+$add;
						$afkNow = 0;
						$afkAll = 0;
						if($afk==1) {
							$mt = time();
						}
						mysql_query('UPDATE `online` SET `mainTime` = "'.$mt.'",`time_today` = "'.$on['time_today'].'",`lastUp` = "'.$lst.'",`time_all` = "'.$add.'" WHERE `id` = "'.$on['id'].'" LIMIT 1');
					}
				}
			//}
		}
	}
	
	public $aves = array('now'=>0,'max'=>0),$rep,$tfer,$error2 = '', $room = array(), $bank = array(), $align_nm = array(
		1	=>	'Свет',
		2	=>	'Хаос',
		3	=>	'Тьма',
		7	=>	'Нейтралитет'
	), $mod_nm = array(
						0=>array(0=>''),
						1=>array('1'=>'Свет','1.1'=>'Паладин Поднебесья','1.4'=>'Таможенный Паладин','1.5'=>'Паладин Солнечной Улыбки','1.6'=>'Инквизитор','1.7'=>'Паладин Огненной Зари','1.75'=>'Паладин-Хранитель','1.9'=>'Паладин Неба','1.91'=>'Старший Паладин Неба','1.92'=>'Ветеран Ордена','1.99'=>'Верховный Паладин'),
						3=>array('3'=>'Тьма','3.01'=>'Тарман-Служитель','3.05'=>'Тарман-Надсмотрщик','3.06'=>'Каратель','3.07'=>'Тарман-Убийца','3.075'=>'Тарман-Хранитель','3.09'=>'Тарман-Палач','3.091'=>'Тарман-Владыка','3.092'=>'Ветеран Армады','3.99'=>'Тарман Патриарх')
						);
	public $btl_txt = '',$rgd = array(0=>0,1=>0),$error = '',
	
	$city_unid = array(0,'capitalcity','angelscity','abandonedplain','newcapitalcity','demonscity','fallenearth','emeraldscity','dreamscity','suncity'),
	$city_id = array('capitalcity'=>1,'angelscity'=>2,'abandonedplain'=>3,'newcapitalcity'=>4,'demonscity'=>5,'fallenearth'=>6,'emeraldscity'=>6,'suncity'=>7),
	$city_name = array('emeraldscity' => 'Emeralds city','abandonedplain'=>'Abandoned Plain','capitalcity'=>'Capital city','angelscity'=>'Angels city','newcapitalcity'=>'New Capital city','demonscity'=>'Demons city','fallenearth'=>'Fallen Earth','dreamscity'=>'Dreams City','suncity'=>'Sun City'),
	$city_name2 = array('emeraldscity' => 'Emeraldscity', 'abandonedplain'=>'Abandonedplain','capitalcity'=>'Capitalcity','angelscity'=>'Angelscity','newcapitalcity'=>'Newcapitalcity','demonscity'=>'Demonscity','fallenearth'=>'FallenEarth','dreamscity'=>'Dreams City','suncity'=>'Sun City'),
	
	$stats,$info,$map,$mapUsers,$is = array(
	'acestar' => 'Следующий каст будет критическим', 'spasenie' => 'Спасение после смерти', 'exp' => 'Получаемый опыт (%)', 'align_bs' => 'Служитель закона',
	'nopryh' => 'Прямое поподание', 'puti'=>'Запрет перемещения','align'=>'Склонность','hpAll'=>'Уровень жизни (HP)','mpAll'=>'Уровень маны','enAll'=>'Уровень энергии','sex'=>'Пол','lvl'=>'Уровень','s1'=>'Сила','s2'=>'Ловкость','s3'=>'Интуиция','s4'=>'Выносливость','s5'=>'Интеллект','s6'=>'Мудрость','s7'=>'Духовность','s8'=>'Воля','s9'=>'Свобода духа','s10'=>'Божественность','s11'=>'Энергия','m1'=>'Мф. критического удара (%)','m2'=>'Мф. против критического удара (%)','m3'=>'Мф. мощности крит. удара (%)','m4'=>'Мф. увертывания (%)','m5'=>'Мф. против увертывания (%)','m6'=>'Мф. контрудара (%)','m7'=>'Мф. парирования (%)','m8'=>'Мф. блока щитом (%)','m9'=>'Мф. удара сквозь броню (%)','m14'=>'Мф. абс. критического удара (%)','m15'=>'Мф. абс. увертывания (%)','m16'=>'Мф. абс. парирования (%)','m17'=>'Мф. абс. контрудара (%)','m18'=>'Мф. абс. блока щитом (%)','m19'=>'Мф. абс. магический промах (%)','m20'=>'Мф. удача (%)','a1'=>'Мастерство владения ножами, кинжалами','a2'=>'Мастерство владения топорами, секирами','a3'=>'Мастерство владения дубинами, молотами','a4'=>'Мастерство владения мечами','a5'=>'Мастерство владения магическими посохами','a6'=>'Мастерство владения луками','a7'=>'Мастерство владения арбалетами','aall'=>'Мастерство владения оружием','mall'=>'Мастерство владения магией стихий','m2all'=>'Мастерство владения магией','mg1'=>'Мастерство владения магией огня','mg2'=>'Мастерство владения магией воздуха','mg3'=>'Мастерство владения магией воды','mg4'=>'Мастерство владения магией земли','mg5'=>'Мастерство владения магией Света','mg6'=>'Мастерство владения магией Тьмы','mg7'=>'Мастерство владения серой магией','tj'=>'Тяжелая броня','lh'=>'Легкая броня','minAtack'=>'Минимальный урон','maxAtack'=>'Максимальный урон','m10'=>'Мф. мощности урона','m11'=>'Мф. мощности магии стихий','m11a'=>'Мф. мощности магии','pa1'=>'Мф. мощности колющего урона','pa2'=>'Мф. мощности рубящего урона','pa3'=>'Мф. мощности дробящий урона','pa4'=>'Мф. мощности режущий урона','pm1'=>'Мф. мощности магии огня','pm2'=>'Мф. мощности магии воздуха','pm3'=>'Мф. мощности магии воды','pm4'=>'Мф. мощности магии земли','pm5'=>'Мф. мощности магии Света','pm6'=>'Мф. мощности магии Тьмы','pm7'=>'Мф. мощности серой магии','za'=>'Защита от урона','zm'=>'Защита от магии стихий','zma'=>'Защита от магии','za1'=>'Защита от колющего урона','za2'=>'Защита от рубящего урона','za3'=>'Защита от дробящего урона','za4'=>'Защита от режущего урона','zm1'=>'Защита от магии огня','zm2'=>'Защита от магии воздуха','zm3'=>'Защита от магии воды','zm4'=>'Защита от магии земли','zm5'=>'Защита от магии Света','zm6'=>'Защита от магии Тьмы','zm7'=>'Защита от серой магии','magic_cast'=>'Дополнительный каст за ход','pza'=>'Понижение защиты от урона','pzm'=>'Понижение защиты от магии','pza1'=>'Понижение защиты от колющего урона','min_heal_proc'=>'Эффект лечения (%)','notravma'=>'Защита от травм','yron_min'=>'Минимальный урон','yron_max'=>'Максимальный урон','zaproc'=>'Защита от урона (%)','zmproc'=>'Защита от магии стихий (%)','zm2proc'=>'Защита от магии Воздуха (%)','pza2'=>'Понижение защиты от рубящего урона','pza3'=>'Понижение защиты от дробящего урона','pza4'=>'Понижение защиты от режущего урона','pzm1'=>'Понижение защиты от магии огня','pzm2'=>'Понижение защиты от магии воздуха','pzm3'=>'Понижение защиты от магии воды','pzm4'=>'Понижение защиты от магии земли','pzm5'=>'Понижение защиты от магии Света','pzm6'=>'Понижение защиты от магии Тьмы','pzm7'=>'Понижение защиты от серой магии','speedhp'=>'Регенерация здоровья (%)','speedmp'=>'Регенерация маны (%)','tya1'=>'Колющие атаки','tya2'=>'Рубящие атаки','tya3'=>'Дробящие атаки','tya4'=>'Режущие атаки','tym1'=>'Огненные атаки','mg2static_points'=>'Уровень заряда (Воздух)','tym2'=>'Электрические атаки','tym3'=>'Ледяные атаки','tym4'=>'Земляные атаки','hpProc'=>'Уровень жизни (%)','mpProc'=>'Уровень маны (%)','tym5'=>'Атаки Света','tym6'=>'Атаки Тьмы','tym7'=>'Серые атаки','min_use_mp'=>'Уменьшает расход маны','pog'=>'Поглощение урона','pog2'=>'Поглощение урона','pog2p'=>'Процент поглощение урона','pog2mp'=>'Цена поглощение урона','maxves'=>'Увеличивает рюкзак','bonusexp'=>'Увеличивает получаемый опыт','speeden'=>'Регенерация энергии (%)',
	'antm3' => 'Мф. против мощности крита',
'yza' => 'Уязвимость физическому урона (%)','yzm' => 'Уязвимость магии стихий (%)','yzma' => 'Уязвимость магии (%)'
,'yza1' => 'Уязвимость колющему урона (%)','yza2' => 'Уязвимость рубящему урона (%)','yza3' => 'Уязвимость дробящему урона (%)','yza4' => 'Уязвимость режущему урона (%)'
,'yzm1' => 'Уязвимость магии огня (%)','yzm2' => 'Уязвимость магии воздуха (%)','yzm3' => 'Уязвимость магии воды (%)','yzm4' => 'Уязвимость магии земли (%)','yzm5' => 'Уязвимость магии (%)','yzm6' => 'Уязвимость магии (%)','yzm7' => 'Уязвимость магии (%)','rep'=> 'Репутация Рыцаря'
,'repair_discount' => 'Скидка на ремонт вещей','hpVinos' => 'Бонус жизни','bronze' => 'Bronze Premium Account','silvers' => 'Silver Premium Account','gold' => 'Gold Premium Account','speed_dungeon' => 'Скорость передвижения по подземельям'
);
	public $items = array(
					'tr'  => array('sex','align','lvl','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','a1','a2','a3','a4','a5','a6','a7','mg1','mg2','mg3','mg4','mg5','mg6','mg7','mall','m2all','aall','rep', 'align_bs'),
					'add' => array(
					'antm3','acestar','spasenie','exp','enemy_am1','hod_minmana','yhod','noshock_voda','bronze','silvers','gold','repair_discount',
					'yza','yzm','yzma','yza1','yza2','yza3','yza4','yzm1','yzm2','yzm3','yzm4','yzm5','yzm6','yzm7',
					'notuse_last_pr','yrn_mg_first','antishock','nopryh','speed_dungeon','naemnik','mg2static_points','yrnhealmpprocmg3','nousepriem','notactic','seeAllEff','100proboi1','pog2','pog2p','magic_cast','min_heal_proc','no_yv1','no_krit1','no_krit2','no_contr1','no_contr2','no_bl1','no_pr1','no_yv2','no_bl2','no_pr2','silver','pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','yron_min','yron_max','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','shopSaleEkr','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','hpVinos','mpVinos','mpAll','enAll','hpProc','mpProc','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','m11a','zona','zonb','maxves','minAtack','maxAtack','bonusexp','speeden'),
					'sv' => array('pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','shopSaleEkr','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','enAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','min_use_mp','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack','speeden')
					);
					
					/*
						yrnhealmpprocmg3 - восставнавливает ману от урона по игроку, в % (магия воды)
					*/

	public function sys_add($uid,$time,$type,$text,$data,$time_see) {
		/*if(mysql_query('INSERT INTO `a_system` (`uid`,`time`,`type`,`text`,`data`,`time_see`) VALUES ("'.$uid.'","'.$time.'","'.$type.'","'.$text.'","'.$data.'","'.$time_see.'")')){
			
		}else{
			echo '.';
		}
		*/
	}
	
	public function testAlign( $an , $uid ) {
		$r = 1;
		if( floor($an) > 0 ) {
			$a = mysql_fetch_array(mysql_query('SELECT * FROM `users_align` WHERE `uid` = "'.$uid.'" AND (`delete` = "0" OR `delete` > "'.time().'") LIMIT 1'));
			if(isset($a['id'])) {
				if( floor($a['align']) > 0 ) {
					if( floor($a['align']) != $an ) {
						$r = 0;
					}
				}
			}
		}
		return $r;
	}
	
	public function insertAlign( $an , $uid ) {
		if( $an > 0 ) {
			mysql_query('UPDATE `users_align` SET `delete` = "'.(time()+1).'" WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `align` = "'.mysql_real_escape_string(floor($an)).'"');
			mysql_query('INSERT INTO `users_align` (`uid`,`time`,`delete`,`align`) VALUES (
				"'.mysql_real_escape_string($uid).'","'.time().'","0","'.mysql_real_escape_string(floor($an)).'"
			) ');
		}
	}
	
	public function deleteAlign( $an , $uid ) {
		if( $an > 0 ) {
			mysql_query('UPDATE `users_align` SET `delete` = "'.(time()+86400*60).'" WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `align` = "'.mysql_real_escape_string(floor($an)).'"');
		}
	}
	
	public function shopSaleM( $val, $itm ) {
		global $c;
		/*$proc = array(
			0,0,0,0,0,0,0,0,
			5,30,30,30,30,30,30,30,30,30,30,30,30,30			
		);
		$po = $this->lookStats($itm['data']);
		$proc = $proc[$po['tr_lvl']];*/
		$proc = 0;	
		//
		$proc = array(
			0,	//0
			0,	//1
			0,	//2
			0,	//3
			0,	//4
			10,	//5
			20,	//6
			30,	//7
			40, //8
			50, //9
			50, //10
			50,50,50,50,50,50,50,50,50,50,50			
		);
		$po = $this->lookStats($itm['data']);
		$proc = $proc[$po['tr_lvl']];
		//$proc = 0;
		//
		if( $itm['type'] >= 28 ) {
			$proc = 50;
		}
		//
		if( $this->stats['shopSaleEkr'] != 0 ) {
			$proc -= $this->stats['shopSaleEkr']*2;
		}
		if( $c['shop_all'] > 0 ) {
			$proc = 100-$c['shop_all'];
		}
		$val = round(($val/100*(100-$proc)),2);
		return $val;
	}
	
	//Удаление определенного типа предметов
 public function deleteItemID($id, $uid, $coldel) {
   $sp = mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($id).'" AND `uid` = "'.mysql_real_escape_string($uid).'" AND (`delete` = 0 OR `delete` = 1000) ORDER BY `inGroup` DESC LIMIT '.$coldel);
   $delitm = array();
   while($pl = mysql_fetch_array($sp)) {
	 if(count($delitm) < $coldel) {
	   $delitm[] = $pl['id'];
	 }
   }
   $i = 0;
   while($i < count($delitm)) {
	 mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$delitm[$i].'" LIMIT 1');
	 $i++;
   }		
 }
 
 public function count_items($id, $uid, $coldel) {
   $sp = mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($id).'" AND `uid` = "'.mysql_real_escape_string($uid).'" AND (`delete` = 0 OR `delete` = 1000) ORDER BY `inGroup` DESC LIMIT '.$coldel);
   $delitm = array();
   while($pl = mysql_fetch_array($sp)) {
     if(count($delitm) < $coldel) {
       $delitm[] = $pl['id'];
     }
   }
   $i = 0;
   while($i < count($delitm)) {
     $i++;
   }
   return $i;
 }
	
	public function repobmen($id,$type)
	{
		//echo 'обмен';
		$pl = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1;'));
		$d = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$pl['item_id'].'" LIMIT 1'));
		$po = $this->lookStats($pl['data']);
		//echo $po['dungeon'].' '.$po['tr_lvl'];
		if((!isset($po['frompisher']) or $po['tr_lvl']<4) and $pl['type']!=31){
			$e = 'Не удалось обменять предмет на репутацию.';
		}else{
			$e = 'Обмен предмета "'.$pl['name'].'" на репутацию Сapitalcity прошел удачно.';
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			$this->rep['repcapitalcity'] += 1;
			mysql_query('UPDATE `rep` SET `repcapitalcity` = "'.$this->rep['repcapitalcity'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');		
		}
		return $e;
	}	
	
	public function add_buf($id,$tp,$uid = NULL,$mod = NULL) {
		/*if($this->info['admin'] > 0) {
			if($tp == 1) {
				
				$cache = ob_get_contents();
				ob_end_clean ();
								
				//Добавляем новый
				if($uid != '') {
					$uid_f = floor($uid/1000);
				}else{
					$uid_f = '_m';
				}
								
				//Проверяем существование первой директории
				if(!is_dir($id.'~a/'.$uid_f.'/') ) {
					//создаем
					mkdir($id.'~a/'.$uid_f.'/', 0700);
				}
				
				//Проверяем существование второй директории
				if(!is_dir($id.'~a/'.$uid_f.'/'.$uid.'/') ) {
					//создаем
					mkdir($id.'~a/'.$uid_f.'/'.$uid.'/', 0700);
				}
				
				$id = $id.'~a/'.$uid_f.'/'.$uid.'/'.$mod.'_'.md5($mod).'.cach';
				
				//Удаляем предыдущий экземпляр
				if(file_exists($id)) {
					$this->del_buf($id);
				}
				
				//Создаем файл
				$fp = @fopen ($id, "w");
				@fwrite($fp, $cache);
				@fclose($fp);
				
			}elseif($tp == 0) {
				ob_start();
			}
		}*/
	}
	
	public function del_buf($id,$uid = NULL,$mod = NULL) {
		/*if($uid != NULL && $mod != NULL) {
			//генерируем ссылку
			if((int)$uid > 0 && $uid != '') {
				$uid_f = floor($uid/1000);
			}else{
				$uid_f = '_m_'.$uid;
			}
			$id = $id.'~a/'.$uid_f.'/'.$uid.'/'.$mod.'_'.md5($mod).'.cach';
		}
		if(file_exists($id)) {			
			unlink($id);
		}*/
	}
	
	public function see_buf($id,$uid = NULL,$mod = NULL) {
		/*if(file_exists($id)) {
			if($uid != NULL && $mod != NULL) {
				//генерируем ссылку
				if($uid != '') {
					$uid_f = floor($uid/1000);
				}else{
					$uid_f = '_m';
				}
				$id = $id.'~a/'.$uid_f.'/'.$uid.'/'.$mod.'_'.md5($mod).'.cach';
			}
			return file_get_contents($id);
		}else{
			return false;
		}*/
	}
	
	public function sys_see($type) {
			/*
			$r = ''; $lid = $this->info['sys'];
			$sp = mysql_query('SELECT `id`,`uid`,`time`,`type`,`text`,`data`,`time_see` FROM `a_system` WHERE `uid` = "'.$this->info['id'].'" AND `id` > "'.$this->info['sys'].'" ORDER BY `time` DESC LIMIT 20');
			while($pl = mysql_fetch_array($sp)) {
				$r .= 'top.tow('.$pl['id'].',"'.$pl['text'].'",'.$pl['time_see'].','.$pl['data'].');';
				if($pl['id'] > $lid) {
					$lid = $pl['id'];
				}
			}
			if($lid > $this->info['sys']) {
				mysql_query('UPDATE `users` SET `sys` = "'.$lid.'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			}
			return $r;
			*/
	}
	
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
	
	public function bsfinish($id, $bu, $di) {
		if($bu == true) {
			/* в этом бою проверяем юзеров */
			$i = 0;
			while($i < count($bu[$i])) {
			  if($bu[$i]['lose'] > 0 || $bu[$i]['nich']) {
					mysql_query('UPDATE `users` SET `lose` = "'.$bu[$i]['lose'].'", `nick` = "'.$bu[$i]['nich'].'" WHERE `id` = "'.$bu[$i]['id'].'" LIMIT 1');
					mysql_query('UPDATE `bs_turnirs` SET `users_finish` = `users_finish` + 1 WHERE `id` = "'.$id['id'].'" LIMIT 1');
					/* удаляем юзера */
					if($bu['inBot'] == 0) {
						$pls1 = mysql_fetch_array(mysql_query('SELECT `id`, `bsid`, `money`, `finish`, `time`, `inBot`, `uid` FROM `bs_zv` WHERE `bsid` = "'.$id['id'].'" AND `finish` = 0 AND `time` = "'.$id['time_start'].'" AND `inBot` = "'.$bu[$i]['id'].'" LIMIT 1'));
						if(isset($pls1['id'])) {
							mysql_query('DELETE FROM `users` WHERE `id` = "'.$bu[$i]['id'].'" LIMIT 1');
							mysql_query('DELETE FROM `stats` WHERE `id` = "'.$bu[$i]['id'].'" LIMIT 1');
							
							//выкидываем предметы на землю
							$spi = mysql_query('SELECT `id`,`item_id` FROM `items_users` WHERE `uid` = "'.$bu[$i]['id'].'" LIMIT 500');
							$ins = '';
							while($pli = mysql_fetch_array($spi))
							{
								$ins .= '("'.$di['dn_id'].'","'.$pli['item_id'].'","'.time().'","'.$di['x'].'","'.$di['y'].'"),'; 
							}
							
							$ins = rtrim($ins,',');
							mysql_query('INSERT INTO `dungeon_items` (`dn`,`item_id`,`time`,`x`,`y`) VALUES '.$ins.'');
							mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pls1['inBot'].'" LIMIT 1');	
							mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pls1['inBot'].'" LIMIT 1');	
							mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `id` = "'.$pls1['id'].'" LIMIT 1');
							mysql_query('UPDATE `users` SET `inUser` = 0 WHERE `id` = "'.$pls1['uid'].'" LIMIT 1');	
						}
					}
					$id['users_finish']++;
				}
				$i++;
			}
		}
		if($id['users']-$id['users_finish'] < 2) {
			$win = array();
			$sp = mysql_query('SELECT `id`,`bsid`,`money`,`finish`,`time`,`inBot`,`uid` FROM `bs_zv` WHERE `bsid` = "'.$id['id'].'" AND `finish` = "0" AND `time` = "'.$id['time_start'].'" ORDER BY `money` DESC LIMIT 100');	
			while($pl = mysql_fetch_array($sp))
			{
				$ur = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser`,`twink`,`lose`,`nich`,`win` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				$ub = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser`,`twink`,`lose`,`nich`,`win` FROM `users` WHERE `id` = "'.$ur['inUser'].'" LIMIT 1'));
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
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$winr['login']."','Поздравляем! Вы победили в турнире &quot;Башня Смерти&quot;! Получено опыта: ".$bsep.", деньги: <img src=/coin1.png height=11 >&nbsp;".$mn." кр.','-1','6','0')");
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','','<font color=red>Внимание!</font> Завершился турнир &quot;Башня Смерти&quot;, победитель турнира: <b>".$winr['login']."</b>! Поздравляем!','-1','5','0')");
				$this->addDelo(1,$uid,'&quot;<font color=#C65F00>WinTournament.'.$this->info['city'].'</font>&quot; (Башня Смерти): Получено &quot;<b>'.$mn.'</b> кр.&quot;',time(),$this->info['city'],'WinTournament.'.$this->info['city'].'',0,0);
			}else{
				//нет победителя				
				//Выдаем травму
				
				/* чат */
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','','<font color=red>Внимание!</font> Завершился турнир &quot;Башня Смерти&quot;, победитель турнира: отсутствует.','-1','5','0')");
			}
			
			$sp = mysql_query('SELECT `id`,`bsid`,`money`,`finish`,`time`,`inBot`,`uid` FROM `bs_zv` WHERE `bsid` = "'.$id['id'].'" AND `time` = "'.$id['time_start'].'" ORDER BY `money` DESC LIMIT 100');	
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
			$r['now'] = 0+$r['now'][0];
			@$r['max'] = 40+($this->stats['os7']*10)+$this->stats['s4']+$this->stats['maxves']+$this->stats['s1']*4;
			$r['items'] = mysql_fetch_array(mysql_query('SELECT COUNT(`im`.`id`) FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `iu`.`item_id` = `im`.`id` WHERE `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0"'));
			$r['items'] = $r['items'][0];
		}else{
			
		}
		return $r;
	}
	
	//Переплавка вещей \ рун
	public function plavka($id, $type)
	{
		$e = '';
		$rn = 0; $rnn = array();
		$pl = mysql_fetch_array(mysql_query('SELECT		
`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1;'));
		$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$pl['item_id'].'" LIMIT 1'));
		$po = $this->lookStats($d['data']);
		$rlvl = 4;
		if($pl['level'] == 0) {
			$pl['level'] = 0+$po['tr_lvl'];
		}

		if($pl['level']==7 || $pl['level']==8){
			$rlvl = 7;
		}elseif($pl['level']==9){
			$rlvl = 9;
		}elseif($pl['level']>=10){
			$rlvl = 9;
		}
		if( rand(0,100) < 4 && rand(0,100) > 96 ) {
			$rlvl = 1;
		}
		$rs = mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `type` = 31 AND `level` = "'.$rlvl.'"');
		while($rl = mysql_fetch_array($rs))
		{
			$nm = explode(' ',$rl['name']);
			if(($nm[0] && $nm[1] || $rlvl >= 10)) {
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
		}elseif($po['tr_lvl']>=10 && $this->rep['rep1']<10000)
		{
			$e = 'Для растворения предметов 10-го и старше уровня требуется знак Храма Знаний третьего круга';
		}elseif($rn>0)
		{
			if(isset($pl['id'],$d['id']))
			{
				$rnn = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `type` = "31" AND `id` = "'.$rn.'" LIMIT 1'));
				if(isset($rnn['id'])){
					$pl['rep'] = 0;
					if( $po['tr_lvl'] >= 4 && $po['tr_lvl'] <= 6 && $this->rep['rep1'] < 100 ) {
						$pl['rep'] = 1; //4-6
					}elseif($this->rep['rep1']>99){
						if( $po['tr_lvl'] >= 7 && $po['tr_lvl'] <= 8 ) { //7-8
							$pl['rep'] = 1;
						}
						if($pl['item_id']==1035){
							$pl['rep'] = 1;
						} 
					}elseif($this->rep['rep1']>999){
						if( $po['tr_lvl'] >= 9 && $po['tr_lvl'] <= 10 ) { //9-10
							$pl['rep'] = 1;
						}
						if($pl['item_id']==1035){
							$pl['rep'] = 1;
						} 
					}elseif($this->rep['rep1']>9999){
						if( $po['tr_lvl'] >= 11 && $po['tr_lvl'] <= 12 ) { //11-11
							$pl['rep'] = 1;
						}
						if($pl['item_id']==1035){
							$pl['rep'] = 1;
						} 
					}else{
						if($pl['item_id']==1035){
							$pl['rep'] = 2;
						} 
					}
					if(rand(0,5200) > 3000+round($this->rep['rep1']/4) && $pl['item_id'] != 1035) {
						$e = 'Предмет "'.$pl['name'].'" был растворен неудачно...';
						$ld = $this->addDelo(1, $this->info['id'],'&quot;<font color=#C65F00>AddItems.'.$this->info['city'].'</font>&quot;: Предмет [<b>'.$pl['name'].'</b>] был расплавлен неудачно.',time(),$this->info['city'],'AddItems.'.$this->info['city'].'',0,0); 
						mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					}elseif(mysql_query('UPDATE `rep` SET `rep1` = `rep1` + "'.$pl['rep'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1')){
						
						mysql_query('UPDATE `dailybonus` SET `rune` = `rune` + 1 WHERE `uid` = "'.$this->info['id'].'" LIMIT 1');
						
						$e = 'Удачно растворен предмет "'.$pl['name'].'". '.( $pl['item_id'] != 1035 ? 'Получена руна "'.$rnn['name'].'".' : '').'';
						if($pl['item_id'] != 1035) {
							$irunew = $this->addItem($rnn['id'],$this->info['id'], null, null, null, null, $pl['name']);
							if( $rlvl == 1 ) {
								$irunew = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `id` = "'.$irunew.'" LIMIT 1'));
								if(isset($irunew['id'])) {
									$irunew['data'] .= '|sudba='.$u->info['login'].'';
								}
								mysql_query('UPDATE `items_users` SET `data` = "'.$irunew['data'].'" WHERE `id` = "'.$irunew['id'].'" LIMIT 1');
								unset($irunew);
							}
						}
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
		$sp = mysql_query('SELECT 
		`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
		 FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid` = "'.((int)$this->info['id']).'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` > "0" AND `iu`.`inOdet` < "18" LIMIT 18');
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
			if(!isset($st['minAtack'])) {		$st['minAtack'] = 0;		}
			if(!isset($st['maxAtack'])) {		$st['maxAtack'] = 0;		}
			if(!isset($st['yron_min'])) {		$st['yron_min'] = 0;		}
			if(!isset($st['yron_max'])) {		$st['yron_max'] = 0;		}
			if(!isset($itm['yron_min'])) {		$itm['yron_min'] = 0;		}
			if(!isset($itm['yron_max'])) {		$itm['yron_max'] = 0;		}
			if(!isset($itm['sv_yron_min'])) {	$itm['sv_yron_min'] = 0;	}
			if(!isset($itm['sv_yron_max'])) {	$itm['sv_yron_max'] = 0;	}
			
			$min = $itm['sv_yron_min']+$st['minAtack']+$itm['yron_min']+$st['yron_min'];
			$max = $itm['sv_yron_max']+$st['maxAtack']+$itm['yron_max']+$st['yron_max'];
			
			if($x!=0)
			{
				//Тип урона: 0 - нет урона, 1 - колющий, 2 - рубящий, 3 - дробящий, 4 - режущий, 5 - огонь, 6 - воздух, 7 - вода, 8 - земля, 9 - свет, 10 - тьма, 11 - серая
				if($x==1)
				{
					//колющий
					$sss = ceil((($st['s1']*0.6+$st['s2']*0.4)));
				}elseif($x==2)
				{
					//рубящий
					$sss = ceil(($st['s1']*0.7+$st['s2']*0.2+$st['s3']*0.2));
				}elseif($x==3)
				{
					//дробящий
					$sss = ceil($st['s1']*1.1);
				}elseif($x==4)
				{
					//режущий
					$sss = ceil(($st['s1']*0.6+$st['s3']*0.4));
				}elseif($x>=5 && $x<=22)
				{
					//урон магии и магии стихий
					$sss = ceil($st['s1']*0.33);
				}else{
					//без профильного урона
					$sss = ceil($st['s1']*0.33);
				}
				
				
			//Обычный урон
				$p['B'][0] = 5;
				$p['B'][1] = 9;
				$p['B']['rnd'] = rand($p['B'][0],$p['B'][1]);
				//Добавочный минимальный урон
				$p['W'][0] = $min;
				$p['W'][1] = $max;				
				$p['W']['rnd'] = rand($p['W'][0],$p['W'][1]);
				// Коэф. оружия
				$p['T'] = 1;
				//Владения
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
				//Мощнность
				if( $x < 5 ) {
					$p['M'] = $st['pa'.$x];
				}else{
					$p['M'] = $st['pm'.($x-4)];
				}
				$min  = (($p['B'][0]+$st['level']+$sss+$p['W'][0]+1)*$p['T']*(1+0.07*$bn))*(1+$p['M']/100);
				$max  = (($p['B'][1]+$st['level']+$sss+$p['W'][1]+1)*$p['T']*(1+0.07*$bn))*(1+$p['M']/100);	
				//
				$kmin  = ceil( ( 2 * ( 1 + $st['m3']/100 ) ) * $min);
				$kmax  = ceil( ( 2 * ( 1 + $st['m3']/100 ) ) * $max);	
				/*
				$r['Kmin'] = ceil( ( 2 * ( 1 + $p['K']/100 ) ) * $r['min']);
				$r['Kmax'] = ceil( ( 2 * ( 1 + $p['K']/100 ) ) * $r['max']);
				*/
				//
				$min = $min*0.8;
				$max = $max*0.8;		
			}
			$tp = array(0=>ceil($min),1=>ceil($max),2=>ceil($kmin),3=>ceil($kmax));
		}
		return $tp;
	}
	
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
			$r = floor($r[$lvl1][$lvl2]/100);
			$r = 0;
			return $r;
		}
		
	//Расчет защиты
		public function zago($v) {
			if($v > 1000) {
				$v = 1000;
			}
			$r = (1-( pow(0.5, ($v/250) ) ))*100;		
			return $r;
		}
	//Расчет защиты (магия)
		public function zmgo($v) {
			if($v > 1000) {
				$v = 1000;
			}
			$r = (1-( pow(0.5, ($v/250) ) ))*100;		
			return $r;
		}
	
	public function inform($v)

	{
		//$this->stats['items'][13] , $this->stats['items'][14]
		$r = '';
		if($v=='yrontest' || $v=='yrontest-krit')
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
					if(isset($d['tya'.$i]) && $t<$d['tya'.$i])
					{
						$t = $d['tya'.$i];
						$tp = $i;
					}
					$i++;
				}
				$y = $this->weaponAtc($w1,$this->stats,$tp);
				if($v=='yrontest-krit') {
					$y[0] = $y[2];
					$y[1] = $y[3];
				}
				if( $y[0] < 1 ) {
					$y[0] = 1;
				}
				if( $y[1] < 1 ) {
					$y[1] = 1;
				}
				$r .= '<span title="'.$w1['name'].'">'.$y[0].'-'.$y[1].'</span>';
			}else{
				//урон кулаком
				$y[0] = ceil($this->stats['s1']*1.4)+$this->stats['minAtack']+$this->stats['yron_min'];
				$y[1] = ceil(0.4+$y[0]/0.9)+$this->stats['maxAtack']+$this->stats['yron_max'];
				if( $y[0] < 1 ) {
					$y[0] = 1;
				}
				if( $y[1] < 1 ) {
					$y[1] = 1;
				}
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
				if($v=='yrontest-krit') {
					$y[0] = $y[2];
					$y[1] = $y[3];
				}
				if( $y[0] < 1 ) {
					$y[0] = 1;
				}
				if( $y[1] < 1 ) {
					$y[1] = 1;
				}
				$r .= ' / <span title="'.$w2['name'].'">'.$y[0].'-'.$y[1].'</span>';	
			}		
			/* --- */
		}elseif($v=='yron')
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
					if(isset($d['tya'.$i]) && $t<$d['tya'.$i])
					{
						$t = $d['tya'.$i];
						$tp = $i;
					}
					$i++;
				}
				$y = $this->weaponAtc($w1,$this->stats,$tp);
				if( $y[0] < 1 ) {
					$y[0] = 1;
				}
				if( $y[1] < 1 ) {
					$y[1] = 1;
				}
				$r .= '<span title="'.$w1['name'].'">'.$y[0].'-'.$y[1].'</span>';
			}else{
				//урон кулаком
				$y[0] = ceil($this->stats['s1']*1.4)+$this->stats['minAtack']+$this->stats['yron_min'];
				$y[1] = ceil(0.4+$y[0]/0.9)+$this->stats['maxAtack']+$this->stats['yron_max'];
				if( $y[0] < 1 ) {
					$y[0] = 1;
				}
				if( $y[1] < 1 ) {
					$y[1] = 1;
				}
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
				if( $y[0] < 1 ) {
					$y[0] = 1;
				}
				if( $y[1] < 1 ) {
					$y[1] = 1;
				}
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
					$y = 0;
					if(isset($d['sv_'.$v])) {
						$y += $d['sv_'.$v];
					}
					if(isset($this->stats[$v])) {
						$y += $this->stats[$v];
					}
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
					$y = @$this->stats[$v]+@$d['sv_'.$v];
					//if($y!=$ry)
					//{
						$r .= ' / <span title="'.$w2['name'].'">'.$y.'</span>';	
					//}else{
						//$r = str_replace('title="'.$w1['name'].'"','',$r);
					//}
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
			if($e>24999)
			{
				$r = 'Рыцарь второго круга, '.$e.' / ??';
			}elseif($e>9999)
			{
				$r = 'Рыцарь первого круга, '.$e.' / 24999';
			}else{
				$r = $e.' / 10000';
			}
		}elseif($id==3)
		{
			//Angels city
			if($e>24999)
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
			if($e>24999)
			{
				$r = 'Рыцарь второго круга, '.$e.' / ??';
			}elseif($e>9999)
			{
				$r = 'Рыцарь первого круга, '.$e.' / 24999';
			}else{
				$r = $e.' / 10000';
			}
		}elseif($id==5)
		{
			//Алтарь Крови
			if($e>99)
			{
				$r = 'Посвященный первого круга '.$e.' / 999';
			}else{
				$r = $e.' / 99';
			}
		}elseif($id==6)
		{
			//Sand
			if($e>24999)
			{
				$r = 'Рыцарь второго круга, '.$e.' / ??';
			}elseif($e>9999)
			{
				$r = 'Рыцарь первого круга, '.$e.' / 24999';
			}else{
				$r = $e.' / 10000';
			}
		}elseif($id==7)
		{
			//Sun
			if($e>24999)
			{
				$r = 'Рыцарь второго круга, '.$e.' / ??';
			}elseif($e>9999)
			{
				$r = 'Рыцарь первого круга, '.$e.' / 24999';
			}else{
				$r = $e.' / 10000';
			}
		}elseif($id==8)
		{
			//Moon
			if($e>24999)
			{
				$r = 'Рыцарь второго круга, '.$e.' / ??';
			}elseif($e>9999)
			{
				$r = 'Рыцарь первого круга, '.$e.' / 24999';
			}else{
				$r = $e.' / 10000';
			}
	  }elseif($id==9)
		{
			//Demons city
			if($e>9999)
			{
				$r = 'Посвященный третьего круга,  ['.$e.']';
			}elseif($e>4999)
			{
				$r = 'Посвященный второго круга,'  .$e.' / 9999';
			}elseif($e>999)
			{
				$r = 'Посвященный первого круга,  '.$e.' / 4999';
			}else{
				$r = $e.' / 999';
			}
		}
		
		return $r;
	}
	
	public function addItem($id, $uid, $md = null, $dn = null, $mxiznos = null, $nosudba = null, $plavka = null) {
		$rt = -1;
		$i = mysql_fetch_array(mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp` FROM `items_main` AS `im` WHERE `im`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(isset($i['id']))
		{
			$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));		
			//новая дата
			$data = $d['data'];	
			if($i['ts']>0)
			{
				if( $nosudba == NULL ) {
					$ui = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1'));
					$data .= '|sudba='.$ui['login'];
				}
			}
			if($md!=NULL)
			{
				  $data .= $md; 
				  $data = $this->lookStats($data); // Если в функции имеются две одинаковых константы SROK?
				  $data = $this->impStats($data);
			}

	
			if($dn!=NULL)
			{
				//предмет с настройками из подземелья
				if($dn['dn_delete']>0)
				{
					$i['dn_delete'] = 1;
				}
			}
			if($mxiznos > 0) {
				$i['iznosMAXi'] = $mxiznos;
			}
			if($this->info['dnow'] > 0){
				$room = $this->room['city'];
			}else {
				$room = $this->info['city'];
			}
			$ins = mysql_query('INSERT INTO `items_users` (`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`,`dn_delete`) VALUES (
											"'.$i['overTypei'].'",
											"'.$i['id'].'",
											"'.$uid.'",
											"'.$data.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geni'].'",
											"'.$i['magic_inci'].'",
											"'.$room.'",
											"'.time().'",
											"'.time().'",
											"'.$i['dn_delete'].'")');
			if($ins)
			{
				$rt = mysql_insert_id();
				mysql_query('UPDATE `items_users` SET `dn_delete` = "1" WHERE `id` = "'.$rt.'" AND `data` LIKE "%dn_delete=%" LIMIT 1');
				if( $uid == $this->info['id'] ) {
					$this->stack( $rt );
				}
				$ads = '';
				if($plavka != null) {
				  $ads = 'Расплавлен предмет : ['.$plavka.']';
				}
				//Записываем в личное дело что предмет получен
				$ld = $this->addDelo(1,$uid,'&quot;<font color=#C65F00>AddItems.'.$this->info['city'].'</font>&quot;: Получен предмет &quot;<b>'.$i['name'].'</b>&quot; (x1) [#'.$i['iid'].']. '.$ads.'',time(),$this->info['city'],'AddItems.'.$this->info['city'].'',0,0);
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
	
	public function microLogin2($bus) {
		$bus['login_BIG']  = '<b>';
		if( $bus['align'] > 0 ) {
			$bus['login_BIG'] .= '<img src=http://img.xcombats.com/i/align/align'.$bus['align'].'.gif width=12 height=15 >';
		}
		if( $bus['clan'] > 0 ) {
			$bus['login_BIG'] .= '<img src=http://img.xcombats.com/i/clan/'.$bus['clan'].'.gif width=24 height=15 >';
		}
		$bus['login_BIG'] .= ''.$bus['login'].'</b>['.$bus['level'].']<a target=_blank href=http://xcombats.com/info/'.$bus['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
		return $bus['login_BIG'];
	}
	
	public function  microLogin($id,$t,$nnz = 1)
	{
		global $c;
		if($t==1)
		{
			$inf = mysql_fetch_array(mysql_query('SELECT 
			`u`.`id`,
			`u`.`align`,
			`u`.`login`,
			`u`.`clan`,
			`u`.`level`,
			`u`.`city`,
			`u`.`online`,
			`u`.`sex`,
			`u`.`cityreg`,
			`u`.`palpro`,
			`u`.`invis`,
			`st`.`hpNow` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($id).'" OR `u`.`login` = "'.mysql_real_escape_string((int)$id).'" LIMIT 1'));
		}else{
			$inf = $id;
			$id = $inf['id'];
		}
		$r = '';
		if(isset($inf['id']) && ( ($inf['invis'] < time() && $inf['invis'] != 1) || ($this->info['id'] == $inf['id'] && $nnz == 1) ))
		{
			if($inf['align']>0)
			{
				$r .= '<img width="12" height="15" src="http://img.xcombats.com/i/align/align'.$inf['align'].'.gif" />';
			}
			if($inf['clan']>0)
			{
				$cln = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`name_mini`,`align`,`type_m`,`money1`,`exp` FROM `clan` WHERE `id` = "'.$inf['clan'].'" LIMIT 1'));
				if(isset($cln['id']))
				{
					$r .= '<img width="24" height="15" src="http://img.xcombats.com/i/clan/'.$cln['name_mini'].'.gif" />';
				}
			}
			if($inf['cityreg'] == '') {
				$inf['cityreg'] = 'capitalcity';
			}
			$r .= ' <b>'.$inf['login'].'</b> ['.$inf['level'].']<a target="_blank" href="http://xcombats.com/info/'.$inf['id'].'"><img src="http://img.xcombats.com/i/inf_'.$inf['cityreg'].'.gif" /></a>';	
		}else{
			$r = '<b><i>Невидимка</i></b> [??]<a target="_blank" href="http://xcombats.com/info/0"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" /></a>';	
		}
		return $r;
	}
	
	public function testHome()
	{
		/*----Быстрый(Особенность)----*/
		$timeforwait = 3600;
		if(isset($st['os3']) && $st['os3']>0) {
			$timeforwait = 3600-(($st['os6']*5)*60);
		}
		/*----Быстрый(Особенность)----*/
		$hgo = $this->testAction('`uid` = "'.$this->info['id'].'" AND `time` >= '.(time()-$timeforwait).' AND `vars` = "go_homeworld" LIMIT 1',1);
		if($this->info['level']==0 || $this->info['active']!='' || $this->info['inTurnir'] > 0 || $this->info['inTurnirnew'] > 0 || $this->info['zv'] > 0 || $this->info['dnow'] > 0) {
			$hgo['id'] = true;
		}
		if(isset($this->info['noreal']) || $this->info['dnow'] > 0) {
			$hgo['id'] = true;
		}
		if(!isset($hgo['id'])) {
			$ku = mysql_fetch_array(mysql_query('SELECT `id` FROM `katok_zv` WHERE `uid` = "'.$this->info['id'].'" LIMIT 1'));
			if(isset($ku['id'])) {
				$hgo['id'] = true;
			}
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
	
	public function functionThisData() {
		//Обновление данных сегодня
		
	}
	
	private function __construct()
	{
		global $c,$code,$magic;
		
		$this->info = mysql_fetch_array(mysql_query('SELECT 
		`u`.`nextBonus`,`u`.`mat`,`u`.`skype`,`u`.`skype_hide`,`u`.`stopexp`,`u`.`twink`,`u`.`swin`,`u`.`slose`,`u`.`send`,`u`.`activ`,`u`.`b1`,`u`.`nadmin`,`u`.`fnq`,`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`real`,`u`.`pass`,`u`.`pass2`,`u`.`repass`,`u`.`notrhod`,`u`.`emailconfirmation`,`u`.`securetime`,`u`.`sys`,`u`.`palpro`,`u`.`online`,`u`.`ip`,`u`.`ipreg`,`u`.`joinIP`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`banned`,`u`.`auth`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`mod_zvanie`,`u`.`clan`,`u`.`nextMsg`,`u`.`molch1`,`u`.`molch2`,`u`.`molch3`,`u`.`level`,`u`.`money`,`u`.`money4`,`u`.`money3`,`u`.`money3`,`u`.`battle`,`u`.`cityreg`,`u`.`invBlock`,`u`.`allLock`,`u`.`invBlockCode`,`u`.`zag`,`u`.`a1`,`u`.`q1`,`u`.`mail`,`u`.`name`,`u`.`bithday`,`u`.`sex`,`u`.`city_real`,`u`.`icq`,`u`.`icq_hide`,`u`.`homepage`,`u`.`deviz`,`u`.`hobby`,`u`.`chatColor`,`u`.`timereg`,`u`.`add_smiles`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`cityreg2`,`u`.`host`,`u`.`info_delete`,`u`.`dateEnter`,`u`.`afk`,`u`.`dnd`,`u`.`timeMain`,`u`.`clan_prava`,`u`.`addpr`,`u`.`marry`,`u`.`city2`,`u`.`invis`,`u`.`bot_id`,`u`.`haos`,`u`.`host_reg`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`jail`,`u`.`animal`,`u`.`vip`,`u`.`catch`,`u`.`frg`,`u`.`no_ip`,`u`.`type_pers`,`u`.`bot_room`,
		`st`.`id`,`st`.`lider`,`st`.`btl_cof`,`st`.`last_hp`,`st`.`last_pr`,`st`.`smena`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`enNow`,`st`.`transfers`,`st`.`regHP`,`st`.`regMP`,`st`.`showmenu`,`st`.`prmenu`,`st`.`ability`,`st`.`skills`,`st`.`sskills`,`st`.`nskills`,`st`.`exp`,`st`.`exp_eco`,`st`.`minHP`,`st`.`minMP`,`st`.`zv`,`st`.`dn`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`last_a`,`st`.`last_b`,`st`.`battle_text`,`st`.`upLevel`,`st`.`wipe`,`st`.`bagStats`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`nextAct`,`st`.`active`,`st`.`bot`,`st`.`lastAlign`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`s`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`ref_data`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`bn_capitalcity`,`st`.`bn_demonscity`,
		`r`.`noatack` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) LEFT JOIN `room` AS `r` ON (`u`.`room` = `r`.`id`) WHERE `u`.`login`="'.mysql_real_escape_string($_COOKIE['login']).'" AND `u`.`pass`="'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
		
		if( $c['securetime'] > 0 ) {
			if(!defined('IP')) {
				$dip = '';
				if (!empty($_SERVER['HTTP_CLIENT_IP']))
					$dip = $_SERVER['HTTP_CLIENT_IP'];
				else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
					$dip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				$dip = $_SERVER['REMOTE_ADDR'];
				define('IP',$dip);
			}
			
			if( isset($this->info['id']) && ($this->info['ip'] != IP  || $this->info['banned'] > 0) ) {
				if( $_SERVER['REQUEST_URI'] != '/' ) {
					unset($this->info, $_COOKIE['login'], $_COOKIE['pass']);
					die('<script>top.location.href="http://xcombats.com/";</script>');
				}
			}
		}
		
		if(isset($this->info['id'])) {
			//
			$this->setOnline($this->info['online'],$this->info['id'],0,true);
			//
			$this->info['1_level'] = $this->info['level'];
			if( $this->info['exp'] > 300000 && $this->info['twink'] > 0 ) {
				$this->info['exp'] = 300000;
				mysql_query('UPDATE `users` SET `exp` = '.$this->info['exp'].' WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			}
		}
				
		if(isset($this->info['id']) && $this->info['inUser'])
		{
			$md = array($this->info['molch1'],$this->info['molch2'],$this->info['molch3'],$this->info['admin'],$this->info['nadmin'],$this->info['banned'],$this->info['activ'],$this->info['align'],$this->info['id'],$this->info['level']);
			$this->info = mysql_fetch_array(mysql_query('SELECT 
			`u`.`nextBonus`,`u`.`mat`,`u`.`skype`,`u`.`skype_hide`,`u`.`stopexp`,`u`.`twink`,`u`.`swin`,`u`.`slose`,`u`.`activ`,`u`.`nadmin`,`u`.`b1`,`u`.`fnq`,`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`real`,`u`.`pass`,`u`.`pass2`,`u`.`repass`,`u`.`notrhod`,`u`.`emailconfirmation`,`u`.`securetime`,`u`.`sys`,`u`.`palpro`,`u`.`online`,`u`.`ip`,`u`.`ipreg`,`u`.`joinIP`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`banned`,`u`.`auth`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`mod_zvanie`,`u`.`clan`,`u`.`nextMsg`,`u`.`molch1`,`u`.`molch2`,`u`.`molch3`,`u`.`level`,`u`.`money`,`u`.`money4`,`u`.`money3`,`u`.`battle`,`u`.`cityreg`,`u`.`invBlock`,`u`.`allLock`,`u`.`invBlockCode`,`u`.`zag`,`u`.`a1`,`u`.`q1`,`u`.`mail`,`u`.`name`,`u`.`bithday`,`u`.`sex`,`u`.`city_real`,`u`.`icq`,`u`.`icq_hide`,`u`.`homepage`,`u`.`deviz`,`u`.`hobby`,`u`.`chatColor`,`u`.`timereg`,`u`.`add_smiles`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`cityreg2`,`u`.`host`,`u`.`info_delete`,`u`.`dateEnter`,`u`.`afk`,`u`.`dnd`,`u`.`timeMain`,`u`.`clan_prava`,`u`.`addpr`,`u`.`marry`,`u`.`city2`,`u`.`invis`,`u`.`bot_id`,`u`.`haos`,`u`.`host_reg`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`jail`,`u`.`animal`,`u`.`vip`,`u`.`catch`,`u`.`frg`,`u`.`no_ip`,`u`.`type_pers`,`u`.`bot_room`,
			`st`.`id`,`st`.`lider`,`st`.`btl_cof`,`st`.`last_hp`,`st`.`last_pr`,`st`.`smena`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`enNow`,`st`.`transfers`,`st`.`regHP`,`st`.`regMP`,`st`.`showmenu`,`st`.`prmenu`,`st`.`ability`,`st`.`skills`,`st`.`sskills`,`st`.`nskills`,`st`.`exp`,`st`.`exp_eco`,`st`.`minHP`,`st`.`minMP`,`st`.`zv`,`st`.`dn`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`last_a`,`st`.`last_b`,`st`.`battle_text`,`st`.`upLevel`,`st`.`wipe`,`st`.`bagStats`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`nextAct`,`st`.`active`,`st`.`bot`,`st`.`lastAlign`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`s`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`ref_data`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`bn_capitalcity`,`st`.`bn_demonscity`
			 FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($this->info['inUser']).'" LIMIT 1'));
			if($this->info['molch1'] < $md[0]) {
				$this->info['molch1'] = $md[0];
			}
			if($this->info['molch2'] < $md[1]) {
				$this->info['molch2'] = $md[1];
			}
			if($this->info['molch3'] < $md[2]) {
				$this->info['molch3'] = $md[2];
			}
			if($this->info['admin'] < $md[3]) {
				$this->info['admin'] = $md[3];
			}
			if($this->info['nadmin'] < $md[4]) {
				$this->info['nadmin'] = $md[4];
			}
			if($this->info['banned'] < $md[5]) {
				$this->info['banned'] = $md[5];
			}
			$this->info['activ'] = $md[6];
			$this->info['noreal'] = 1;
			$this->info['align_real'] = $md[7];
			$this->info['__id'] = $md[8];
			$this->info['__level'] = $md[9];
			$this->info['1_level'] = $this->info['__level'];
		}
		
		if($this->info['battle'] != $_COOKIE['btl']) {
			setcookie('btl',$this->info['battle'],time()+86400);
		}
				
		//Бан спамера
		/*if($this->info['banned'] == 7007001) {
			$spm_url = mysql_fetch_array(mysql_query('SELECT * FROM `_spamer` WHERE `host` = "'.$this->info['host'].'" LIMIT 1'));
			if(isset($spm_url['id'])) {
				die('<meta http-equiv="refresh" content="0; URL=/banned.php?id='.$spm_url['id'].'">');
			}else{
				die('<meta http-equiv="refresh" content="0; URL=/banned.php?kill=1">');
			}
		}*/
		
		if(!isset($this->info['id']))
		{
			$this->info = mysql_fetch_array(mysql_query('SELECT 
			`u`.`nadmin`,`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`real`,`u`.`pass`,`u`.`pass2`,`u`.`repass`,`u`.`notrhod`,`u`.`emailconfirmation`,`u`.`securetime`,`u`.`sys`,`u`.`palpro`,`u`.`online`,`u`.`ip`,`u`.`ipreg`,`u`.`joinIP`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`banned`,`u`.`auth`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`mod_zvanie`,`u`.`clan`,`u`.`nextMsg`,`u`.`molch1`,`u`.`molch2`,`u`.`molch3`,`u`.`level`,`u`.`money`,`u`.`money4`,`u`.`money3`,`u`.`battle`,`u`.`cityreg`,`u`.`invBlock`,`u`.`allLock`,`u`.`invBlockCode`,`u`.`zag`,`u`.`a1`,`u`.`q1`,`u`.`mail`,`u`.`name`,`u`.`bithday`,`u`.`sex`,`u`.`city_real`,`u`.`icq`,`u`.`icq_hide`,`u`.`homepage`,`u`.`deviz`,`u`.`hobby`,`u`.`chatColor`,`u`.`timereg`,`u`.`add_smiles`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`cityreg2`,`u`.`host`,`u`.`info_delete`,`u`.`dateEnter`,`u`.`afk`,`u`.`dnd`,`u`.`timeMain`,`u`.`clan_prava`,`u`.`addpr`,`u`.`marry`,`u`.`city2`,`u`.`invis`,`u`.`bot_id`,`u`.`haos`,`u`.`host_reg`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`jail`,`u`.`animal`,`u`.`vip`,`u`.`catch`,`u`.`frg`,`u`.`no_ip`,`u`.`type_pers`,`u`.`bot_room`
			FROM `users` AS `u` WHERE `u`.`login`="'.mysql_real_escape_string($_COOKIE['login']).'" AND `u`.`pass`="'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
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
			
		if(isset($this->info['id'])) {
			if($this->info['invis'] == 1 || $this->info['invis'] > time()) {
				$this->info['cast_login'] = '<i>Невидимка</i>';
			}else{
				$this->info['cast_login'] = $this->info['login'];
			}
		}
			
		if(isset($this->info['id']) && $this->info['battle'] == 0)
		{										
			$sb = mysql_fetch_array(mysql_query('SELECT SUM(`money2`) FROM `bank` WHERE `uid` = "'.$this->info['id'].'" LIMIT 100'));
			$sb = $sb[0];
			
			if($sb-1 > $this->info['catch']-$this->info['frg']) {
				if($this->info['frg'] == -1) {
					$sm = $this->testAction('`uid` = "'.$this->info['id'].'" AND `vars` = "frg" LIMIT 1',1);
				}
				if(!isset($sm['id']) && $this->info['frg']==-1) {
					mysql_query('UPDATE `users` SET `catch` = "'.round($sb).'",`frg` = "0" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					$this->addAction(time(),'frg','[ '.$this->info['login'].' ] '.date('d.m.Y H:i:s').' [true] , balance: '.$sb.' / '.$this->info['catch'].' / '.$this->info['frg'].' ');
				}else{
					mysql_query('UPDATE `users` SET `catch` = "'.round($sb+$this->info['frg']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					if($sb-10 > $this->info['catch']-$this->info['frg']) {
						$this->addAction(time(),'frgfalse','[ '.$this->info['login'].' ] '.date('d.m.Y H:i:s').' [false] , ['.($sb-($this->info['catch']-$this->info['frg'])).'] , balance: '.$sb.' | '.$this->info['catch'].' | '.$this->info['frg'].' ');
					}
				}
			}
			
			if($this->info['login2']!='' && $this->info['battle']==0 && $this->info['zv']==0) {
				mysql_query('UPDATE `users` SET `login2` = "" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				$this->info['login2'] = '';	
			}
			
			$this->rep = mysql_fetch_array(mysql_query('SELECT 
			`repitems`,`n_items`,`nu_items`,
			`dl1`,`id`,`rep1`,`rep2`,`repcapitalcity`,`repdemonscity`,`repangelscity`,
			`repdevilscity`,`repmooncity`,`repsuncity`,`repsandcity`,`repemeraldscity`,`repdreamscity`,
			`n_capitalcity`,`n_demonscity`,`n_suncity`,`nu_demonscity`,`nu_angelscity`,
			`nu_capitalcity`,`nu_suncity`,`nu_devilscity`,`add_stats`,`add_money`,`add_skills`,`add_skills2`,
			`rep3`,`rep3_buy`,`repdragonscity`,`n_dragonscity`,`nu_dragonscity`,
			(`repcapitalcity`+`repdemonscity`+`repangelscity`+`repsuncity`+`repitems`) as allrep, 
			(`nu_capitalcity`+`nu_demonscity`+`nu_angelscity`+`nu_suncity`+`nu_items`) as allnurep
			FROM `rep` WHERE `id` = "'.$this->info['id'].'" LIMIT 1'));
			 
			
			if(!isset($this->rep['id'])){
				mysql_query('INSERT INTO `rep` (`id`) VALUES ('.$this->info['id'].')');
			}
			if($this->info['login2'] != '' && $this->info['zv'] == 0 && $this->info['battle'] == 0) {
				$this->info['login2'] = '';
				mysql_query('UPDATE `users` SET `login2` = "" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			}
			/* различные мероприятия  */
			/*$i = $this->testAction('`time` >= "'.(time()-7129).'" AND `vars` = "reclama1" LIMIT 1',1);
			if(!isset($i['id']))
			{				
				$this->addAction(time(),'reclama1','');				
				//Сообщение в чат
				$t6 = array('','');
				if(date('N') == 1) {
					//понедельник
					$t6[0] = 'в среду';
				}elseif(date('N') == 2) {
					//вторник
					$t6[0] = 'завтра';
				}elseif(date('N') == 3) {
					//среда
					if(date('H')<20) {
						$t6[0] = 'сегодня';
					}else{
						$t6[0] = 'в пятницу';
					}
				}elseif(date('N') == 4) {
					//четверг
					$t6[0] = 'завтра';
				}elseif(date('N') == 5) {
					//пятница
					if(date('H')<20) {
						$t6[0] = 'сегодня';
					}else{
						$t6[0] = 'в воскресенье';
					}
				}elseif(date('N') == 6) {
					//суббота
					$t6[0] = 'завтра';
				}elseif(date('N') == 7) {
					//воскресенье
					if(date('H')<20) {
						$t6[0] = 'сегодня';
					}else{
						$t6[0] = 'в среду';
					}
				}
				$r = '<font color=red><b>Мероприятие!</b></font> Турнир по покеру Холдем состоится '.$t6[0].' (в 20:00 по Московскому времени). Более подробная информация у персонажа <img src=http://img.xcombats.com/i/clan/PokerStars.com.gif width=24 height=15><a href=http://xcombats.com/info/1457199 target=_blank>Alexandr</a> или на сайте событий <a href=http://events.xcombats.com/?page_id=3&st=323 target=_blank>Events.xcombats.com</a>';				
				//Отправляем сообщение в чат
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','','','','".$r."','".time()."','6','0')");
			}
			*/
			/*
			//Боты которые пещемещаются по карте
			mysql_query('LOCK TABLES users,stats,chat,room,test_bot,battle WRITE');
			$dasc = array('ASC','DESC');
			$sp = mysql_query('SELECT `u`.`id`,`u`.`bot_room`,`s`.`atack`,`u`.`type_pers`,`s`.`bbexp`,`s`.`timeGo`,`s`.`timeGoL`,`u`.`login`,`u`.`sex`,`u`.`align`,`u`.`clan`,`u`.`room`,`u`.`level`,`u`.`battle`,`s`.`hpNow`,`s`.`mpNow`,`s`.`team`,`u`.`city` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`type_pers` > 0 AND `s`.`timeGo` < '.time().' AND `s`.`timeGoL` < '.time().' ORDER BY `s`.`timeGoL` '.$dasc[rand(0,1)].' LIMIT 11');
			while($pl = mysql_fetch_array($sp)) {
				if($pl['type_pers']>0 && $pl['battle'] == 0) {
					//Бот перемещается
					if($pl['timeGo']<time()) {
						$rm = mysql_fetch_array(mysql_query('SELECT `id`,`lider`,`name`,`city`,`code`,`timeGO`,`file`,`level`,`align`,`clan`,`items`,`effects`,`destroy`,`close`,`roomGo`,`sex`,`FR`,`noatack`,`botgo`,`block_all`,`zvsee` FROM `room` WHERE `id` = "'.$pl['room'].'" LIMIT 1'));
						$rmgo = explode(',',$rm['roomGo']);
						$rmgo = $rmgo[rand(0,count($rmgo)-1)];
						$rmgo = mysql_fetch_array(mysql_query('SELECT `id`,`lider`,`name`,`city`,`code`,`timeGO`,`file`,`level`,`align`,`clan`,`items`,`effects`,`destroy`,`close`,`roomGo`,`sex`,`FR`,`noatack`,`botgo`,`block_all`,`zvsee` FROM `room` WHERE `id` = "'.$rmgo.'" AND `botgo` > 0 AND `close` = 0 AND `destroy` = 0 LIMIT 1'));
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
					$spu = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`battle`,`s`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`room` = "'.$pl['room'].'" AND `u`.`city` = "'.$pl['city'].'" AND `u`.`type_pers` = 0 AND `s`.`bot` = 0 AND `u`.`id` != "'.$pl['id'].'" AND `u`.`level` > 6 AND `u`.`online` > "'.(time()-10).'" AND `u`.`banned` = "0" LIMIT 5');
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
								//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red>Внимание!</font> <b>".$pl['login']."</b> совершил нападение на <b>".$rs[$ru]['login']."</b>...','".time()."','6','0')");
								$sx = '';
								if($pl['sex'] == 1) {
									$sx = 'а';
								}
								$rtxt = '[img[items/pal_button8.gif]] &quot;<small><font color=grey>!</font></small>'.$pl['login'].'&quot; использовал'.$sx.' магию нападения на персонажа &quot;'.$rs[$ru]['login'].'&quot;.';
								mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$pl['city']."','','','','".$rtxt."','".time()."','7','0','1','1')");	
							}else{
								//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red>Внимание!</font> <b>".$pl['login']."</b> совершил не удачное нападение на <b>".$rs[$ru]['login']."</b>...','".time()."','6','0')");
							}
						}else{
							//Предупреждаем
							mysql_query('UPDATE `stats` SET `timeGoL` = "'.(time()+rand(30,520)).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
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
							//mysql_query('UPDATE `stats` SET `atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}else{
							//Поединок продолжается
							if($pl['atack'] < time()) {
								//mysql_query('UPDATE `stats` SET `atack` = "'.(time()+123456789).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							}
						}
					}else{
						//бот не в поединке
						if($pl['atack'] > time()) {
							//mysql_query('UPDATE `stats` SET `atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}
						
						//ели закончилось НР
						if($pl['hpNow'] < 1) {
							if($pl['bot_room'] > 0) {
								//Портируем в "место отдыха"
								mysql_query('UPDATE `users` SET `room` = "'.$pl['bot_room'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								mysql_query('UPDATE `stats` SET `hpNow` = "1",`mpNow` = "1",`team` = "0",`timeGoL` = "'.(time()+rand(60,240)).'",`atack` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red><b>Внимание!</b></font> <b>".$pl['login']."</b> был повержен в ".$this->city_name[$pl['city']]."...','".time()."','6','0')");
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
								//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','0','','','<font color=red>Внимание!</font> <b>".$pl['login']."</b> вернулся в локацию &quot;Центральная площадь&quot; в ".$this->city_name[$pl['city']]."...','".time()."','6','0')");
								unset($nrm);
							}else{
								//хиляемся
								
							}
							unset($btst);
						}
					}
				}				
			}
			mysql_query('UNLOCK TABLES');
			unset($pl,$sp,$plu,$spu,$atc,$ru,$rs);
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
					if(!isset($hgo['id']) && $this->info['room'] != 274 && $this->info['align']!=2 && $this->info['inTurnir'] == 0)
					{
						$this->addAction(time(),'go_homeworld','');
						$rmt = mysql_fetch_array(mysql_query('SELECT `id`,`lider`,`name`,`city`,`code`,`timeGO`,`file`,`level`,`align`,`clan`,`items`,`effects`,`destroy`,`close`,`roomGo`,`sex`,`FR`,`noatack`,`botgo`,`block_all`,`zvsee` FROM `room` WHERE `name` = "Центральная площадь" AND `city` = "'.$this->info['city'].'" LIMIT 1'));
						if(isset($rmt['id']))
						{
							//Удаляем все ставки в БС
							if( $this->room['file'] == 'bsenter' ) {
								//Удаляем все ставки в БС
								$sp_bs = mysql_query('SELECT `id`,`bsid`,`money` FROM `bs_zv` WHERE `uid` = "'.$this->info['id'].'" AND `inBot` = "0" AND `finish` = "0"');
								while( $pl_bs = mysql_fetch_array($sp_bs) ) {
									mysql_query('UPDATE `bs_turnirs` SET `users` = `users` - 1 WHERE `id` = "'.$pl_bs['bsid'].'" LIMIT 1');
								}
								unset($sp_bs,$pl_bs);
								mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `uid` = "'.$this->info['id'].'" AND `inBot` = "0" AND `finish` = "0"');
							}
							$this->info['room'] = $rmt['id'];
							mysql_query('UPDATE `users` SET `room` = "'.$this->info['room'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');	

						}else{
							$this->error = 'В этом городе нельзя пользоваться кнопкой возрата';
						}
						unset($rmt);
					}else{
						$this->error = 'Вам запрещено пользоваться кнопкой возрата';
					}
					unset($hgo);
				}
			}
			
			//Заносим текст
			if(isset($_GET['itmid']) && isset($_GET['addtext'])) {
				$itm = mysql_fetch_array(mysql_query('SELECT 
				`i`.`id`,`i`.`item_id`,`i`.`1price`,`i`.`2price`,`i`.`uid`,`i`.`use_text`,`i`.`data`,`i`.`inOdet`,`i`.`inShop`,`i`.`delete`,`i`.`iznosNOW`,`i`.`iznosMAX`,`i`.`gift`,`i`.`gtxt1`,`i`.`gtxt2`,`i`.`kolvo`,`i`.`geniration`,`i`.`magic_inc`,`i`.`maidin`,`i`.`lastUPD`,`i`.`timeOver`,`i`.`overType`,`i`.`secret_id`,`i`.`time_create`,`i`.`inGroup`,`i`.`dn_delete`,`i`.`inTransfer`,`i`.`post_delivery`,`i`.`lbtl_`,`i`.`bexp`,`i`.`so`,`i`.`blvl`
				,`m`.`max_text` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `i`.`item_id` = `m`.`id` WHERE `i`.`id` = "'.mysql_real_escape_string($_GET['itmid']).'" LIMIT 1'));
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
			if(isset($_POST['trnLogin'],$_GET['transfer']) && $this->info['battle']==0) {
				if($this->info['level']<4 && $this->info['admin']==0) {
					$this->error = 'Передавать предметы могут персонажи старше 4-го уровня';
				} elseif($this->info['align']==2 && $this->info['admin']==0) {
					$this->error = 'Хаосники не могут передавать предметы другим персонажам';
				} elseif($this->info['login'] == $_POST['trnLogin']) {
					$this->error = 'Себе передавать нельзя...';

				} else {
					$t = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`login2`,`pass`,`pass2`,`emailconfirmation`,`securetime`,`sys`,`online`,`ip`,`ipreg`,`joinIP`,`admin`,`city`,`room`,`banned`,`auth`,`align`,`mod_zvanie`,`clan`,`nextMsg`,`molch1`,`molch2`,`molch3`,`level`,`money`,`battle`,`cityreg`,`invBlock`,`invBlockCode`,`zag`,`a1`,`q1`,`mail`,`name`,`bithday`,`sex`,`city_real`,`icq`,`icq_hide`,`homepage`,`deviz`,`hobby`,`chatColor`,`timereg`,`add_smiles`,`obraz`,`win`,`lose`,`nich`,`cityreg2`,`host`,`info_delete`,`dateEnter`,`afk`,`dnd`,`timeMain`,`clan_prava`,`addpr`,`marry`,`city2`,`invis`,`bot_id`,`haos`,`host_reg`,`inUser`,`jail`,`animal`,`vip`,`catch`,`frg`,`no_ip`,`type_pers`,`bot_room` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['trnLogin']).'" AND `city` = "'.$this->info['city'].'" LIMIT 1'));
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
							$tt = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`uid1`,`uid2`,`city`,`room`,`good1`,`good2`,`cancel1`,`cancel2`,`money1`,`money2`,`start1`,`start2`,`text`,`r0`,`r1`,`r2`,`finish1`,`finish2` FROM `transfers` WHERE (`uid1` = "'.$this->info['id'].'" OR `uid2` = "'.$this->info['id'].'") AND (`cancel1` = "0" OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'") OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'")) AND (`cancel2` = "0" OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'") OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'")) ORDER BY `id` DESC LIMIT 1'));
							if(isset($tt['id']))
							{
								$this->error = 'Вы уже находитесь в передаче';
							}else{
								$tt = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`uid1`,`uid2`,`city`,`room`,`good1`,`good2`,`cancel1`,`cancel2`,`money1`,`money2`,`start1`,`start2`,`text`,`r0`,`r1`,`r2`,`finish1`,`finish2` FROM `transfers` WHERE (`uid1` = "'.$t['id'].'" OR `uid2` = "'.$t['id'].'") AND (`cancel1` = "0" OR (`finish1` > 0 AND `uid1` = "'.$t['id'].'") OR (`finish2` > 0 AND `uid2` = "'.$t['id'].'")) AND (`cancel2` = "0" OR (`finish2` > 0 AND `uid2` = "'.$t['id'].'") OR (`finish1` > 0 AND `uid1` = "'.$t['id'].'")) ORDER BY `id` DESC LIMIT 1'));
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
			$this->tfer = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`uid1`,`uid2`,`city`,`room`,`good1`,`good2`,`cancel1`,`cancel2`,`money1`,`money2`,`start1`,`start2`,`text`,`r0`,`r1`,`r2`,`finish1`,`finish2` FROM `transfers` WHERE (`uid1` = "'.$this->info['id'].'" OR `uid2` = "'.$this->info['id'].'") AND (`cancel1` = "0" OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'") OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'")) AND (`cancel2` = "0" OR (`finish2` > 0 AND `uid2` = "'.$this->info['id'].'") OR (`finish1` > 0 AND `uid1` = "'.$this->info['id'].'")) ORDER BY `id` DESC LIMIT 1'));
			if($this->tfer['uid1'] == $this->tfer['uid2']) {
				$this->tfer = false;
			}
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
						if($this->tfer['uid1']==$this->info['id'])
						{
							//Передаем предметы другому игроку
							
						}elseif($this->tfer['uid2']==$this->info['id'])
						{
							//Принимаем передачу от другого игрока
							
						}
					}
				}
			}
			
			/*
			автофлудераст
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
				$cmp = mysql_fetch_array(mysql_query('SELECT `id`,`uid`,`type`,`val`,`name`,`time`,`delete` FROM `save_com` WHERE `uid` = "'.$this->info['id'].'" AND `delete` = "0" AND `id` = "'.mysql_real_escape_string($_GET['usec1']).'" LIMIT 1'));
				if(isset($cmp['id']))
				{
					//снимаем все вещи
					mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `uid` = "'.$this->info['id'].'"');
					//одеваем вещи, если они не удалены
					$cm = $this->lookStats($cmp['val']);
					$i = 1;
					while($i<=250)
					{
						if(isset($cm[$i]))
						{
							mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `uid` = "'.$this->info['id'].'" AND `inOdet` = "'.$i.'"');
							mysql_query('UPDATE `items_users` SET `inOdet` = "'.$i.'" WHERE `id` = "'.((int)$cm[$i]).'" AND `uid` = "'.$this->info['id'].'" AND `delete` = "0" AND `inShop` = "0"');
						}
						$i++;
					}
				}
				
				
				
				
				
				unset($cmp,$cm);
			}
			
			$this->room = mysql_fetch_array(mysql_query('SELECT `id`,`extdlg`,`lider`,`name`,`city`,`code`,`timeGO`,`file`,`level`,`align`,`clan`,`items`,`effects`,`destroy`,`close`,`roomGo`,`sex`,`FR`,`noatack`,`botgo`,`block_all`,`zvsee`,`roomAjax` FROM `room` WHERE `id` = "'.$this->info['room'].'" LIMIT 1'));
			
			/*if(isset($_POST['bankpsw']))
			{
				$this->bank = mysql_fetch_array(mysql_query('SELECT `id`,`uid`,`block`,`create`,`pass`,`money1`,`money2`,`useNow`,`notmail` FROM `bank` WHERE `uid` = "'.$this->info['id'].'" LIMIT 1'));
				if(isset($this->bank)) {
					mysql_query('UPDATE `bank` SET `useNow` = "'.(time()+24*60*60).'" WHERE `id` = "'.$this->bank['id'].'" LIMIT 1');
				}else{
					$this->bank['error'] = 'Неверный пароль от счета';
				}
			}elseif(!isset($_GET['bank_exit']))
			{*/
				$this->bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$this->info['id'].'"  LIMIT 1'));
				if(!isset($this->bank['id'])) {
					$ins = mysql_query('INSERT INTO `bank` (`uid`,`create`,`pass`) VALUES ("'.$this->info['id'].'","'.time().'","0000")');
					$this->bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$this->info['id'].'"  LIMIT 1'));
				}
			//}
			
			/*if(isset($_GET['bank_exit']))
			{
				//mysql_query('UPDATE `bank` SET `useNow` = "0" WHERE `uid` = "'.$this->info['id'].'" AND `useNow`!="0" LIMIT 1');
			}
			*/
			
			if(!isset($_GET['obt_sel']) && $this->info['battle'] == 0 && $this->info['obraz'] != '0.gif') {
				//Проверяем текущий образ
					$this->stats = $this->getStats($this->info['id']);
					$tr = true;
					$o = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`tr`,`img`,`login`,`level`,`admin`,`align`,`clan`,`itm` FROM `obraz` WHERE `img` = "'.mysql_real_escape_string($this->info['obraz']).'" AND `sex` = "'.$this->info['sex'].'" AND (`login` = "" OR `login` = "'.$this->info['login'].'") LIMIT 1'));
					$t = $this->items['tr'];
					$x = 0;
					$po = $this->lookStats($o['tr']);
					if( $o['itm'] > 0 ) {
						$o['itm'] = explode(',',$o['itm']);
						$j = 0;
						while( $j < count($o['itm']) ) {
							$itm_id = $o['itm'][$j];
							if( $itm_id > 0 ) {
								$itm_id = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$itm_id.'" LIMIT 1'));
								$itm_id_true = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = "'.$itm_id['id'].'" AND
								`delete` = 0 AND `inOdet` > 0 AND `inShop` = 0 AND `uid` = "'.$this->info['id'].'"
								LIMIT 1'));
								if(!isset($itm_id_true['id'])) {
									$tr = false;
								}
							}
							$j++;
						}
					}
					while($x<count($t))	{
						$n = $t[$x];
						if(isset($po['tr_'.$n])) {
							if($po['tr_'.$n] > $this->stats[$n]) {
								$tr = false;
							}
						}
						$x++;
					}
					if( $this->info['clan'] != $o['clan'] && $o['clan'] != 0 ) {
						$tr = false;
					}
					if(!isset($o['id']) || $tr == false) {
						if( $this->info['obraz'] == $o['img'] ) {
							$this->info['obraz'] = '0.gif';
							mysql_query('UPDATE `users` SET `obraz` = "'.$this->info['obraz'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						}
					}

			}
						
			if(isset($_GET['obr_sel']) || isset($_GET['obraz']))
			{
				//$sm = $this->testAction('`uid` = "'.$this->info['id'].'" AND `time` > '.(time()-86400).' AND `vars` = "sel_obraz" LIMIT 1',1);
				//if(!isset($sm['id']))
				//{
					if(isset($_GET['obr_sel']))
					{
						$this->stats = $this->getStats($this->info['id']);
						$tr = true;
						$o = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`tr`,`img`,`login`,`level`,`admin`,`align`,`clan`,`itm` FROM `obraz` WHERE `id` = "'.((int)$_GET['obr_sel']).'" AND `sex` = "'.$this->info['sex'].'" AND (`login` = "" OR `login` = "'.$this->info['login'].'") LIMIT 1'));
						$t = $this->items['tr'];
						$x = 0;
						$po = $this->lookStats($o['tr']);
						if( $o['itm'] > 0 ) {
							$o['itm'] = explode(',',$o['itm']);
							$j = 0;
							$tritm = '';
							while( $j < count($o['itm']) ) {
								$itm_id = $o['itm'][$j];
								if( $itm_id > 0 ) {
									$itm_id = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$itm_id.'" LIMIT 1'));
									$itm_id_true = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = "'.$itm_id['id'].'" AND
									`delete` = 0 AND `inOdet` > 0 AND `inShop` = 0 AND `uid` = "'.$this->info['id'].'"
									LIMIT 1'));
									if(!isset($itm_id_true['id'])) {
										$tr = false;
									}
									if( $j > 0 ) {
										$tritm .= ', ';
									}
									$tritm .= '&quot;'.$itm_id['name'].'&quot;';
								}
								$j++;
							}
							if( $tritm != '' && $tr == false ) {
								$this->error = 'Необходимы предметы: '.$tritm.'';
							}
						}
						while($x<count($t))	{
							$n = $t[$x];
							if(isset($po['tr_'.$n])) {
								if($po['tr_'.$n] > $this->stats[$n]) {
									$tr = false;
									$this->error = 'Недостаточно характеристик или параметров персонажа';
								}
							}
							$x++;
						}
						if(isset($o['id']) && $tr == true) {
							if( $this->info['obraz'] != $o['img'] ) {
								mysql_query('UPDATE `users` SET `obraz` = "'.$o['img'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
								//$this->addAction(time(),'sel_obraz','id='.$o['id'].'');
								$this->info['obraz'] = $o['img'];
							}
						}else{
							if( $this->info['obraz'] == $o['img'] ) {
								$this->info['obraz'] = '0.gif';
								mysql_query('UPDATE `users` SET `obraz` = "'.$this->info['obraz'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
							}
						}
					}
				//}else{
				//	$this->error = 'Выбирать образ можно не чаще одного раза в сутки, следующая смена '.date('d.m.Y H:i',$sm['time']+86400).'';
				//	unset($_GET['obr_sel']);
				//	$_GET['inv'] = 1;
				//}
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

		}elseif(isset($this->info['id'])) {
			$this->room = mysql_fetch_array(mysql_query('SELECT `id`,`extdlg`,`lider`,`name`,`city`,`code`,`timeGO`,`file`,`level`,`align`,`clan`,`items`,`effects`,`destroy`,`close`,`roomGo`,`sex`,`FR`,`noatack`,`botgo`,`block_all`,`zvsee`,`roomAjax` FROM `room` WHERE `id` = "'.$this->info['room'].'" LIMIT 1'));
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
						$this->error2 = 'Нельзя лепить несколько снежков одновременно ;)';
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
	
	public function zuby($v,$t = 0) {
		$r = '';
		if( $v < 0 ) {
			$v = 0;
		}
		if($t == 0) {
			$names[] = ' <img height=7 title=Гнилой&nbsp;Зуб src=http://img.xcombats.com/zub_low1.gif />';
			$names[] = ' <img height=7 title=Нормальный&nbsp;Зуб src=http://img.xcombats.com/zub_low2.gif />';
			$names[] = ' <img height=7 title=Белый&nbsp;Зуб src=http://img.xcombats.com/zub_low3.gif />';
			$names[] = ' <img height=7 title=Золотой&nbsp;Зуб src=http://img.xcombats.com/zub_low4.gif />';
		}else{
			$names[] = ' <img style=vertical-align:baseline height=7 title=Гнилой&nbsp;Зуб src=http://img.xcombats.com/zub_low1.gif />';
			$names[] = ' <img style=vertical-align:baseline height=7 title=Нормальный&nbsp;Зуб src=http://img.xcombats.com/zub_low2.gif />';
			$names[] = ' <img style=vertical-align:baseline height=7 title=Белый&nbsp;Зуб src=http://img.xcombats.com/zub_low3.gif />';
			$names[] = ' <img style=vertical-align:baseline height=7 title=Золотой&nbsp;Зуб src=http://img.xcombats.com/zub_low4.gif />';
		}
		$int = $v;
		do{
		 $mod = $int%10;
		 $int = floor($int/10);//или быстрее $int = ($int-$mod)/10;
		 $r = array_shift($names)."".$mod."".$r;
		 
		}while($int);
	
		$ost = explode('.',$v);
		$ost = $ost[1];
		if($ost == '' || $ost == 0) {
			$ost = '00';
		}
		
		$r .= '.'.$ost;
	
		return $r;
	}
	
	public function onlineBonus()
	{
		/*if( $this->info['inTurnir'] == 0 ){
			$ts = mysql_fetch_array(mysql_query('SELECT `time_all`,`time_today` FROM `online` WHERE `uid` = "'.$this->info['id'].'" LIMIT 1'));
			$tf = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`vars`,`vals` FROM `actions` WHERE `uid` = "'.$this->info['id'].'" AND `vars` = "online_bonus_time" LIMIT 1'));
			$m = floor(($ts['time_all']-$tf['vals'])/60);
			$h = floor($m/60);
				
			if($h > 0 ) {
				$ekr_add = round($h*0.45,2);
				$bnks = mysql_fetch_array(mysql_query('SELECT `id`,`money2` FROM `bank` WHERE `uid` = "'.$this->info['id'].'" AND `block` = "0" ORDER BY `id` DESC LIMIT 1'));
				if( isset($bnks['id']) ) {
					$r .= ' Вы получили '.$ekr_add.' екр. (<small>Банк №'.$bnks['id'].'</small>) за '.$h.' ч. в онлайне!';
					$this->info['catch'] += $ekr_add;
					$bnks['money2'] += $ekr_add;
					mysql_query('UPDATE `users` SET `catch` = "'.$this->info['catch'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `bank` SET `money2` = "'.$bnks['money2'].'" WHERE `id` = "'.$bnks['id'].'" LIMIT 1');
					if(!isset($tf['id'])) {
						$this->addAction(time(),'online_bonus_time',$ts['time_all']);
					}elseif($tf['vals'] < $ts['time_all']) {
						mysql_query('UPDATE `actions` SET `vals` = "'.$ts['time_all'].'" WHERE `id` = "'.$tf['id'].'" LIMIT 1');
					}
					mysql_query("INSERT INTO `chat` (`typeTime`,`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('2','1','".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','".$r."','".time()."','6','0')");
				}
			}
		}
				/*$ts = mysql_fetch_array(mysql_query('SELECT `time_all`,`time_today` FROM `online` WHERE `uid` = "'.$this->info['id'].'" LIMIT 1'));
				$tf = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`vars`,`vals` FROM `actions` WHERE `uid` = "'.$this->info['id'].'" AND `vars` = "online_bonus_time" LIMIT 1'));
				$m = floor(($ts['time_all']-$tf['vals'])/60);
				$h = floor($m/60);
			
				if($m > 0)
				{
					$this->stats = $this->getStats($this->info,0);
					$r = '';
					if(!isset($tf['id']))
					{
						$this->addAction(time(),'online_bonus_time',$ts['time_all']);
					}elseif($tf['vals'] < $ts['time_all'])
					{
						mysql_query('UPDATE `actions` SET `vals` = "'.$ts['time_all'].'" WHERE `id` = "'.$tf['id'].'" LIMIT 1');
					}
					
					//Выдаем $m шт. предметов награды за онлайн
					if($m > 0) {
						$hrg = 1;
						//$this->addItem(2130,$this->info['id'],'noodet=1|noremont=1');
						$this->stats['enNow'] = $this->info['enNow'];
						$enreg = round($m*(@$this->stats['enAll']/(60*$hrg)),7);
						if($this->info['admin'] > 0) {
							//
						}
						
						$this->stats['enNow'] += $enreg;
						if($this->stats['enNow'] > $this->stats['enAll']) {
							$this->stats['enNow'] = $this->stats['enAll'];
							$enreg = 0;
						}
						$this->info['enNow'] = $this->stats['enNow'];
						mysql_query('UPDATE `stats` SET `enNow` = "'.$this->stats['enNow'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					}
					
					/*if($this->info['id'] == 285838 || $this->info['admin'] > 0) {
					$r .= '<b><font color=red>Внимание!</font></b> восстановлено энергии: <b>'.$enreg.'</b> ед. ['.$this->stats['enNow'].'/'.$this->stats['enAll'].']';
					
					//Отправляем сообщение в чат
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','".$r."','-1','5','0')");
					}*/
				//}
			return NULL;
	}
	
	   public function addAction($time,$vars,$vls,$uid = NULL)
	   {
		 if($uid==NULL)
		 {
		  $uid = $this->info['id'];
		 }
		 //mysql_query('LOCK TABLES actions WRITE');
		 $ins = mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`,`vals`) VALUES ("'.$uid.'","'.$time.'","'.$this->info['city'].'","'.$this->info['room'].'","'.mysql_real_escape_string($vars).'","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'","'.mysql_real_escape_string($vls).'")');
		 
		 //mysql_query('UNLOCK TABLES');
		 if($ins)
		 {
		  return true;
		 }else{
		  return false;
		 }	 
	   }
	
	   public function testAction($filter,$tp)
	   {
		 //mysql_query('LOCK TABLES actions WRITE');
		 if($tp==1)
		 {
		  $ins = mysql_fetch_array(mysql_query('SELECT `id`,`uid`,`time`,`city`,`room`,`vars`,`ip`,`vals`,`val` FROM `actions` WHERE '.$filter.''));
		 }elseif($tp==2){
		  $ins = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `actions` WHERE '.$filter.''));
		 }
		 return $ins;
	   }
	
	public function takePersInfo($whr)
	{
		$inf = mysql_fetch_array(mysql_query('SELECT 
		`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`real`,`u`.`pass`,`u`.`pass2`,`u`.`repass`,`u`.`notrhod`,`u`.`emailconfirmation`,`u`.`securetime`,`u`.`sys`,`u`.`palpro`,`u`.`online`,`u`.`ip`,`u`.`ipreg`,`u`.`joinIP`,`u`.`admin`,`u`.`city`,`u`.`room`,`u`.`banned`,`u`.`auth`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`mod_zvanie`,`u`.`clan`,`u`.`nextMsg`,`u`.`molch1`,`u`.`molch2`,`u`.`molch3`,`u`.`level`,`u`.`money`,`u`.`money4`,`u`.`money3`,`u`.`battle`,`u`.`cityreg`,`u`.`invBlock`,`u`.`allLock`,`u`.`invBlockCode`,`u`.`zag`,`u`.`a1`,`u`.`q1`,`u`.`mail`,`u`.`name`,`u`.`bithday`,`u`.`sex`,`u`.`city_real`,`u`.`icq`,`u`.`icq_hide`,`u`.`homepage`,`u`.`deviz`,`u`.`hobby`,`u`.`chatColor`,`u`.`timereg`,`u`.`add_smiles`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`cityreg2`,`u`.`host`,`u`.`info_delete`,`u`.`dateEnter`,`u`.`afk`,`u`.`dnd`,`u`.`timeMain`,`u`.`clan_prava`,`u`.`addpr`,`u`.`marry`,`u`.`city2`,`u`.`invis`,`u`.`bot_id`,`u`.`haos`,`u`.`host_reg`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`jail`,`u`.`animal`,`u`.`vip`,`u`.`catch`,`u`.`frg`,`u`.`no_ip`,`u`.`type_pers`,`u`.`bot_room`,
		`st`.`id`,`st`.`lider`,`st`.`btl_cof`,`st`.`last_hp`,`st`.`last_pr`,`st`.`smena`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`enNow`,`st`.`transfers`,`st`.`regHP`,`st`.`regMP`,`st`.`showmenu`,`st`.`prmenu`,`st`.`ability`,`st`.`skills`,`st`.`sskills`,`st`.`nskills`,`st`.`exp`,`st`.`exp_eco`,`st`.`minHP`,`st`.`minMP`,`st`.`zv`,`st`.`dn`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`last_a`,`st`.`last_b`,`st`.`battle_text`,`st`.`upLevel`,`st`.`wipe`,`st`.`bagStats`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`nextAct`,`st`.`active`,`st`.`bot`,`st`.`lastAlign`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`s`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`ref_data`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`bn_capitalcity`,`st`.`bn_demonscity`
		FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE '.$whr.' LIMIT 1'));
		return $inf;
	}
	
	public function addNewbot($id,$botDate,$clon,$logins_bot,$luser,$round)
	{
		global $c,$code;
		if($clon!=NULL)
		{
			$r = false;
			if( is_array($clon) ) { 
				//уже взяли инфу
			}else{
				$clon = $this->takePersInfo('`u`.`id` = "'.((int)$clon).'"');
			}
			if(isset($clon['id']))
			{
				if( !isset($clon['loclon']) ) {
					$clon['login'] .= ' (Клон)';
				}
				//копируем пользователя
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
				"'.$clon['align'].'",
				"'.$clon['login'].'",
				"'.$clon['level'].'",
				"'.md5('bot_pass_'.$clon['login'].'_'.time()).'",
				"'.$this->info['city'].'",
				"'.$clon['city_reg'].'",
				"'.$clon['login'].'",
				"'.$clon['sex'].'",
				"",
				"",
				"'.$clon['time_reg'].'",
				"'.$clon['obraz'].'",
				"'.mysql_real_escape_string($id).'",
				"'.$clon['inTurnir'].'"
				)');
				if($ins1)
				{
					if( $round > 0 ) {
						//Улучшаем мф. и статы и НР 1 раунд = +3%
						$statss = $this->lookStats($clon['stats']);
						//
						$statss['s1'] = ceil($statss['s1']*(1 + 0.03*($round)));
						$statss['s2'] = ceil($statss['s2']*(1 + 0.03*($round)));
						$statss['s3'] = ceil($statss['s3']*(1 + 0.03*($round)));
						$statss['s4'] = ceil($statss['s4']*(1 + 0.03*($round)));
						//
						$statss['hpAll'] = ceil($statss['hpAll']*(1 + 0.05*($round)));
						$statss['mpAll'] = ceil($statss['mpAll']*(1 + 0.05*($round)));
						//
						$statss['m1'] = ceil($statss['m1']*(1 + 0.03*($round)));
						$statss['m2'] = ceil($statss['m2']*(1 + 0.03*($round)));
						$statss['m3'] = ceil($statss['m3']*(1 + 0.03*($round)));
						$statss['m4'] = ceil($statss['m4']*(1 + 0.03*($round)));
						$statss['m5'] = ceil($statss['m5']*(1 + 0.03*($round)));
						$statss['za'] = ceil($statss['za']*(1 + 0.03*($round)));
						$statss['zm'] = ceil($statss['zm']*(1 + 0.03*($round)));
						$clon['stats'] = $this->impStats($statss);
						unset($statss);
					}
					if($luser == true && $clon['level'] < 8) {
						//Хуже уворот, крит и защита
						$statss = $this->lookStats($clon['stats']);
						$statss['m1'] -= ceil($statss['m1']/5);
						$statss['m2'] -= ceil($statss['m2']/5);
						$statss['m3'] -= ceil($statss['m3']/2.5);
						$statss['m4'] -= ceil($statss['m4']/5);
						$statss['m5'] -= ceil($statss['m5']/5);
						$statss['za'] -= 175;
						$clon['stats'] = $this->impStats($statss);
						unset($statss);
					}
					$uid = mysql_insert_id();
					//копируем статы
					$ins2 = mysql_query('INSERT INTO `stats` (`clone`,`id`,`stats`,`hpNow`,`upLevel`,`bot`,`priems`) VALUES ("'.$clon['id'].'","'.$uid.'","'.$clon['stats'].'","1000000","'.$clon['upLevel'].'","1","'.$clon['priems'].'")');
					if($ins2)
					{							
						//копируем предметы
						$sp = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `uid` = "'.$clon['id'].'" AND `inOdet` > 0 AND `delete` = "0" LIMIT 50');
						while($pl = mysql_fetch_array($sp))
						{
							$pl['data'] = str_replace('toclan','to_clan_',$pl['data']);
							mysql_query('INSERT INTO `items_users` (`uid`,`item_id`,`data`,`inOdet`,`iznosMAX`,`kolvo`) VALUES ("'.$uid.'","'.$pl['item_id'].'","'.$pl['data'].'","'.$pl['inOdet'].'","'.$pl['iznosMAX'].'","'.$pl['kolvo'].'")');
						}
						//копируем эффекты
						$sp = mysql_query('SELECT `id`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`timeAce`,`user_use`,`delete`,`v1`,`v2`,`img2`,`x`,`hod`,`bj`,`sleeptime`,`no_Ace`,`tr_life_user` FROM `eff_users` WHERE `uid` = "'.$clon['id'].'" AND `delete` = "0" AND `deactiveTime` < "'.time().'" AND `v1` != "priem" LIMIT 50');
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
			if($botDate==NULL){
				$bot = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`stats`,`obraz`,`level`,`sex`,`name`,`deviz`,`hobby`,`type`,`itemsUse`,`priemUse`,`align`,`clan`,`align_zvanie`,`bonus`,`clan_zvanie`,`time_reg`,`city_reg`,`upLevel`,`active`,`expB`,`p_items`,`agressor`,`priems`,`priems_z`,`award` FROM `test_bot` WHERE `id` = "'.$id.'" LIMIT 1'));
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
				`bot_id`
				) VALUES (
				"'.$bot['align'].'",
				"'.$bot['login'].'",
				"'.$bot['level'].'",
				"'.md5('bot_pass_'.$bot['login'].'_'.time()).'",
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
				if($ins1){
					$uid = mysql_insert_id();
					if( $round > 0 ) {
						//Улучшаем мф. и статы и НР 1 раунд = +3%
						$statss = $this->lookStats($bot['stats']);
						//
						$statss['s1'] = ceil($statss['s1']*(1 + 0.03*($round)));
						$statss['s2'] = ceil($statss['s2']*(1 + 0.03*($round)));
						$statss['s3'] = ceil($statss['s3']*(1 + 0.03*($round)));
						$statss['s4'] = ceil($statss['s4']*(1 + 0.03*($round)));
						//
						$statss['hpAll'] = ceil($statss['hpAll']*(1 + 0.05*($round)));
						$statss['mpAll'] = ceil($statss['mpAll']*(1 + 0.05*($round)));
						//
						$statss['m1'] = ceil($statss['m1']*(1 + 0.03*($round)));
						$statss['m2'] = ceil($statss['m2']*(1 + 0.03*($round)));
						$statss['m3'] = ceil($statss['m3']*(1 + 0.03*($round)));
						$statss['m4'] = ceil($statss['m4']*(1 + 0.03*($round)));
						$statss['m5'] = ceil($statss['m5']*(1 + 0.03*($round)));
						$statss['za'] = ceil($statss['za']*(1 + 0.03*($round)));
						$statss['zm'] = ceil($statss['zm']*(1 + 0.03*($round)));
						$bot['stats'] = $this->impStats($statss);
						unset($statss);
					}
					$ins2 = mysql_query('INSERT INTO `stats` (`id`,`stats`,`hpNow`,`upLevel`,`bot`) VALUES ("'.$uid.'","'.$bot['stats'].'","1000000","'.$bot['upLevel'].'","1")');
					if($ins2){
						$bot['id'] = $uid;
						$bot['logins_bot'] = $logins_bot;
						$ret = $bot;
						
						//Выдаем предметы
						//$this->addItem($item_id,$uid);
						$iu = explode(',',$bot['itemsUse']);
						$i = 0;
						$w3b = 0;
						while($i<count($iu)) {
							if($iu[$i]>0) {
								$idiu = $this->addItem($iu[$i],$bot['id']);
								$islot = mysql_fetch_array(mysql_query('SELECT `id`,`inslot` FROM `items_main` WHERE `id` = "'.$iu[$i].'" LIMIT 1'));
								if(isset($islot['id'])) {
									if( $islot['inslot'] == 3 ) {
										if( $w3b == 1 ) {
											$islot = 14;
										}else{
											$islot = 3;
											$w3b = 1;
										}
									}else{
										$islot = $islot['inslot'];
									}
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
	public function buyItem($sid,$itm,$x,$mdata = NULL,$vip = false) {
	global $c,$code,$sid;
	// sid 700 - 730 зарезервированный диапазон для кузниц в пещере (АП вешей до 10лвл)
	$x = round((int)$x);
	if($x<1){ $x = 1; }
	if($x>99){ $x = 99; }
	$i = mysql_fetch_array(mysql_query('SELECT `im`.*,`ish`.* FROM `items_shop` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`sid` = "'.mysql_real_escape_string($sid).'" AND `ish`.`kolvo` > 0 AND `ish`.`item_id` = "'.mysql_real_escape_string($itm).'" LIMIT 1'));
	
	$r = '';
	$vip = false;
	if( $vip == true ) {
		$test = $this->testVipItems($i['type']);
	}
	if( $vip == true && $test == false ) {
		$r = 'Данная покупка ограничена VIP аккаунтом, приобретите более дорогой аккаунт';
	}elseif($this->info['allLock'] > time()) {
		$r = 'Вам запрещено совершать покупки до '.date('d.m.y H:i',$this->info['allLock']).'';
	}elseif(isset($i['id'])){
		if($i['price_4'] <= 0) {
			$i['price_4'] = $i['price3'];
		}
		if($sid == 12) {
			if($i['kolvo']<$x){
				$x = $i['kolvo'];
			}
			if($i['price_1']<=0){
				$i['price_1'] = $i['price1'];
			}
			
			$price = $i['price_1']*$x;
			$mxby = 0;
			if($i['max_buy'] > 0) {
				$mxby = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE ( `delete` = "0" OR `delete` = "1000" ) AND `item_id` = "'.mysql_real_escape_string($itm).'" AND `uid` = "'.$this->info['id'].'" LIMIT '.$i['max_buy']));
				if($mxby[0] >= $i['max_buy']) {
					$mxby = -1;
				}
			}

			if($i['max_buy'] < $x && $i['max_buy'] > 0) {
				$r = 'Для покупки доступно '.$i['max_buy'].' шт.';
			}elseif($mxby == -1) {
				$r = 'Максимальное количество покупок для данного предмета исчерпана';
			}elseif($i['price_1']*$x > ($this->rep['repitems']-$this->rep['nu_items'])){
				$r = 'У вас недостаточно репутации сдачи ресурсов (не хватает '.($price - ($this->rep['repitems']-$this->rep['nu_items'])).' репутации.)';
			}else{
				$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
				$this->rep['nu_items'] += $price;
				$upd = mysql_query('UPDATE `rep` SET `nu_items` = "'.mysql_real_escape_string(round($this->rep['nu_items'],2)).'" WHERE `id` = "'.$this->rep['id'].'" LIMIT 1');
				if($upd){
					//новая дата
					$data = '';
					$data .= $d['data'];
					$i['time_create'] = time();
					if($mdata!=NULL){
						$data .= '|'.$mdata;
					}
					$ix = 1; $gix = 0;
					while($ix<=$x){
						if($i['type'] != 71) {
							if( $i['iznos'] > 0 ) {
								$i['iznosMAXi'] = $i['iznos'];
							}
							$ins = mysql_query('INSERT INTO `items_users` (`1price`,`2price`,`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
											"'.($i['price_1']/2).'",
											"'.$i['price_2'].'",
											"'.$i['overType'].'",
											"'.$i['item_id'].'",
											"'.$this->info['id'].'",
											"'.$data.'|fromshop='.$sid.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geniration'].'",
											"'.$i['magic_inc'].'",
											"'.$this->info['city'].'",
											"'.time().'",
											"'.$i['time_create'].'")');
						}else{
							mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `sex` = "'.$i['sex'].'" AND `img` = "'.str_replace('.png','.gif',$i['img']).'" LIMIT 1');
							$ins = mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("'.$i['sex'].'","'.str_replace('.png','.gif',$i['img']).'","'.$i['level'].'","'.$this->info['id'].'","'.time().'")');
						}
						if($ins){
							$gix++;
						}
						$ix++;
					}
					if($ins){
						//Записываем в личное дело что предмет получен
						$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' репутации сдачи ресурсов.<br>Предмет успешно добавлен в инвентарь.';
						mysql_query('UPDATE `items_shop` SET `kolvo` = "'.($i['kolvo']-$x).'" WHERE `iid` = "'.$i['iid'].'" LIMIT 1');
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>EkrShop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i['name'].'</b>&quot; (x'.$x.',add items '.$gix.') [#'.$i['iid'].'] за <b>'.$price.'</b> репутации сдачи ресурсов.',time(),$this->info['city'],'EkrShop.'.$this->info['city'].'',(int)$price,0);
					}
				}else{
					$r = 'Вам не удалось приобрести предмет...';
				}
			}
		}elseif($i['price_4'] > 0) {
			if($i['kolvo']<$x){
				$x = $i['kolvo'];
			}
			if($x<1){
				$x = 1;
			}
			$price = $i['price_4']*$x;
			$trnt = ''; $detrn = array();
			$trn = 1;
			if($i['tr_items']!=''){
				$tims2 = explode(',',$i['tr_items']);
				$j = 0;
				while($j<count($tims2)){
					$tims = explode('=',$tims2[$j]);
					if($tims[0]>0 && $tims[1]>0){
						$tis = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`price4`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$tims[0].'" LIMIT 1'));
						if(isset($tis['id'])){
							$num_rows = 0;
							$s1p = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`4price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$tims[0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.((int)$tims[1]*$x).'');
							while($p1l = mysql_fetch_array($s1p)){
								$num_rows++;
							}
							if($num_rows < (int)$tims[1]*$x){
								$trn = 0;
							}else{
								$detrn[count($detrn)] = array(0 => $tims[0], 1 => ((int)$tims[1]*$x)); //id_item
							}
							$trnt .= '['.$tis['name'].' (x'.$x.')]x'.$tims[1].', ';
						}
					}
					$j++;
				}
				$trnt = rtrim($trnt,', ');
			}
			
			if( $c['noitembuy'] == true ) {
				$trn = 1;
			}

			if( isset($i['tr_reputation']) && $i['tr_reputation']!=''){ // Требуемая репутация для покупки
				$need_rep = 0;
				$tr_rep = $this->lookStats($i['tr_reputation']);
				foreach($tr_rep as $row){
					if( $this->rep[$row[0]] >= $row[1] ){
						$need_rep++;
					}
				}
			} else $need_rep = 0;

			$mxby = 0;
			if($i['max_buy'] > 0) {
				$mxby = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE ( `delete` = "0" OR `delete` = "1000" ) AND `item_id` = "'.mysql_real_escape_string($itm).'" AND `uid` = "'.$this->info['id'].'" LIMIT '.$i['max_buy']));
				if($mxby[0] >= $i['max_buy']) {
					$mxby = -1;
				}
			}
			
			if($i['max_buy'] < $x && $i['max_buy'] > 0) {
				$r = 'Для покупки доступно '.$i['max_buy'].' шт.';
			}elseif( $need_rep > 0 ) {
				$r = 'Вы не заслужили нашего доверия, мы не продадим вам этот товар.';
			}elseif($mxby == -1) {
				$r = 'Максимальное количество покупок для данного предмета исчерпана';
			}elseif($trn==0 && $this->info['admin'] == 0){
				$r = 'У вас недостаточно требуемых предметов (не хватает '.$trnt.')';
			}elseif($i['price_4']*$x > ($this->rep['rep3']-$this->rep['rep3_buy'])){
				$r = 'У вас недостаточно репутации (не хватает '.($price-($this->rep['rep3']-$this->rep['rep3_buy'])).' репутации.)';
			}else{
				$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
				$this->rep['rep3_buy'] += $price;
				$upd = mysql_query('UPDATE `rep` SET `rep3_buy` = "'.mysql_real_escape_string($this->rep['rep3_buy']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				if($upd){
					//новая дата
					$data = '';
					$data .= $d['data'];
					if($mdata!=NULL){
						$data .= '|'.$mdata;
					}
					$ix = 1; $gix = 0;
					while($ix<=$x){
						if($i['type']!=71) {
							if( $i['iznos'] > 0 ) {
								$i['iznosMAXi'] = $i['iznos'];
							}
							$ins = mysql_query('INSERT INTO `items_users` (`1price`,`2price`,`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
								"1",
								"1",
								"'.$i['overType'].'",
								"'.$i['item_id'].'",
								"'.$this->info['id'].'",
								"'.str_replace('sudba=0','',$data).'|sudba='.$this->info['login'].'|icos=WL|fromshop='.$sid.'",
								"'.$i['iznosMAXi'].'",
								"'.$i['geniration'].'",
								"'.$i['magic_inc'].'",
								"'.$this->info['city'].'",
								"'.time().'",
								"'.time().'")');
						}else{
							mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `sex` = "'.$i['sex'].'" AND `img` = "'.str_replace('.png','.gif',$i['img']).'" LIMIT 1');
							$ins = mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("'.$i['sex'].'","'.str_replace('.png','.gif',$i['img']).'","'.$i['level'].'","'.$this->info['id'].'","'.time().'")');
						}
						if($ins){
							$gix++;
						}
						$ix++;
					}
					if($ins){
						//Записываем в личное дело что предмет получен
						if($trnt!='' && $i['tr_items']!=''){
							$trnt = ', '.$trnt;
						}
						$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' репутации. '.$trnt.'<br>Предмет успешно добавлен в инвентарь.';

						$j = 0;
						while($j<count($detrn)){
							$ost = ((int)$detrn[$j][1]);
							$s4 = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$detrn[$j][0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" ORDER BY `inGroup` DESC LIMIT '.((int)$detrn[$j][1]).'');
							while($itm = mysql_fetch_array($s4)){
								//удаляем предмет
									mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
							}
							$j++;
						}

						//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','','<font color=red><b>Артефактный магазин!</b></font> Персонаж <b>".$this->info['login']."</b> приобрел предмет &quot;<font color=salmon><b>".$i['name']."</b></font>&quot; , Поздравляем с удачной покупкой!','-1','5','0')");
						mysql_query('UPDATE `items_shop` SET `kolvo` = "'.($i['kolvo']-$x).'" WHERE `iid` = "'.$i['iid'].'" LIMIT 1');
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i['name'].'</b>&quot; (x'.$x.') [#'.$i['iid'].'] за <b>'.$price.'</b> репутации.',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
					}else{
						//Записываем в личное дело что предмет не получен
						$r = 'Вам не удалось приобрести &quot;'.$i['name'].'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' репутации. <br>Приносим свои извинения за неудобства.';
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i['iid'].'. К возрату: <b>'.$price.'</b> репутации. ',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
						if(!$ld){
							echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
						}
					}
				}else{
					$r = 'Вам не удалось приобрести предмет...';
				}
			}
		}elseif($i['price_3'] > 0) {
			if($i['kolvo']<$x){
				$x = $i['kolvo'];
			}
			if($x<1){
				$x = 1;
			}
			$price = $i['price_3']*$x;
			$trnt = ''; $detrn = array();
			$trn = 1;
			if($i['tr_items']!=''){
				$tims2 = explode(',',$i['tr_items']);
				$j = 0;
				while($j<count($tims2)){
					$tims = explode('=',$tims2[$j]);
					if($tims[0]>0 && $tims[1]>0){
						$tis = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`price4`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$tims[0].'" LIMIT 1'));
						if(isset($tis['id'])){
							$num_rows = 0;
							$s1p = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`4price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$tims[0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.((int)$tims[1]*$x).'');
							while($p1l = mysql_fetch_array($s1p)){
								$num_rows++;
							}
							if($num_rows < (int)$tims[1]*$x){
								$trn = 0;
							}else{
								$detrn[count($detrn)] = array(0 => $tims[0], 1 => ((int)$tims[1]*$x)); //id_item
							}
							$trnt .= '['.$tis['name'].' (x'.$x.')]x'.$tims[1].', ';
						}
					}
					$j++;
				}
				$trnt = rtrim($trnt,', ');
			}
			if( $c['noitembuy'] == true ) {
				$trn = 1;
			}

			if( isset($i['tr_reputation']) && $i['tr_reputation']!=''){ // Требуемая репутация для покупки
				$need_rep = 0;
				$tr_rep = $this->lookStats($i['tr_reputation']);
				foreach($tr_rep as $row){
					if( $this->rep[$row[0]] >= $row[1] ){
						$need_rep++;
					}
				}
			} else $need_rep = 0;

			$mxby = 0;
			if($i['max_buy'] > 0) {
				$mxby = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE ( `delete` = "0" OR `delete` = "1000" ) AND `item_id` = "'.mysql_real_escape_string($itm).'" AND `uid` = "'.$this->info['id'].'" LIMIT '.$i['max_buy']));
				if($mxby[0] >= $i['max_buy']) {
					$mxby = -1;
				}
			}

			if($i['max_buy'] < $x && $i['max_buy'] > 0) {
				$r = 'Для покупки доступно '.$i['max_buy'].' шт.';
			}elseif( $need_rep > 0 ) {
				$r = 'Вы не заслужили нашего доверия, мы не продадим вам этот товар.';
			}elseif($mxby == -1) {
				$r = 'Максимальное количество покупок для данного предмета исчерпана';
			}elseif($trn==0 && $this->info['admin'] == 0){
				$r = 'У вас недостаточно требуемых предметов (не хватает '.$trnt.')';
			}elseif($i['price_3']*$x > $this->info['money3']){
				$r = 'У вас недостаточно валюты (не хватает '.($price-$this->info['money3']).' $)';
			}else{
				$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
				$this->info['money3'] -= $price;
				$upd = mysql_query('UPDATE `users` SET `money3` = "'.mysql_real_escape_string($this->info['money3']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				if($upd){
					//новая дата
					$data = '';
					$data .= $d['data'];
					if($mdata!=NULL){
						$data .= '|'.$mdata;
					}
					$ix = 1; $gix = 0;
					while($ix<=$x){
						if($i['type']!=71) {
							if( $i['iznos'] > 0 ) {
								$i['iznosMAXi'] = $i['iznos'];
							}
							$ins = mysql_query('INSERT INTO `items_users` (`1price`,`2price`,`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
								"1",
								"1",
								"'.$i['overType'].'",
								"'.$i['item_id'].'",
								"'.$this->info['id'].'",
								"'.str_replace('sudba=0','',$data).'|sudba='.$this->info['login'].'|frombax=1|fromshop='.$sid.'",
								"'.$i['iznosMAXi'].'",
								"'.$i['geniration'].'",
								"'.$i['magic_inc'].'",
								"'.$this->info['city'].'",
								"'.time().'",
								"'.time().'")');
						}else{
							mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `sex` = "'.$i['sex'].'" AND `img` = "'.str_replace('.png','.gif',$i['img']).'" LIMIT 1');
							$ins = mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("'.$i['sex'].'","'.str_replace('.png','.gif',$i['img']).'","'.$i['level'].'","'.$this->info['id'].'","'.time().'")');
						}
						if($ins){
							$gix++;
						}
						$ix++;
					}
					if($ins){
						//Записываем в личное дело что предмет получен
						if($trnt!='' && $i['tr_items']!=''){
							$trnt = ', '.$trnt;
						}
						$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' $. '.$trnt.'<br>Предмет успешно добавлен в инвентарь.';

						$j = 0;
						while($j<count($detrn)){
							$ost = ((int)$detrn[$j][1]);
							$s4 = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$detrn[$j][0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" ORDER BY `inGroup` DESC LIMIT '.((int)$detrn[$j][1]).'');
							while($itm = mysql_fetch_array($s4)){
								//удаляем предмет
									mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
							}
							$j++;
						}

						//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','','<font color=red><b>Артефактный магазин!</b></font> Персонаж <b>".$this->info['login']."</b> приобрел предмет &quot;<font color=salmon><b>".$i['name']."</b></font>&quot; , Поздравляем с удачной покупкой!','-1','5','0')");
						mysql_query('UPDATE `items_shop` SET `kolvo` = "'.($i['kolvo']-$x).'" WHERE `iid` = "'.$i['iid'].'" LIMIT 1');
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i['name'].'</b>&quot; (x'.$x.') [#'.$i['iid'].'] за <b>'.$price.'</b> $',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
					}else{
						//Записываем в личное дело что предмет не получен
						$r = 'Вам не удалось приобрести &quot;'.$i['name'].'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' $ <br>Приносим свои извинения за неудобства.';
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i['iid'].'. К возрату: <b>'.$price.'</b> $ ',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
						if(!$ld){
							echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
						}
					}
				}else{
					$r = 'Вам не удалось приобрести предмет...';
				}
			}
		}elseif($sid==2 || $sid==777) {
			if($i['kolvo']<$x){
				$x = $i['kolvo'];
			}
			if($i['price_2']<=0){
				$i['price_2'] = $i['price2'];
			}
			if($i['price_1']<=0){
				$i['price_1'] = $i['price1'];
			}
			if( $vip == true ) {
				$i['price_2'] = round($i['price_2']/20,2);
			}
			
			//Скидка в государственный магазин 5%
			if( $this->stats['silver'] >= 1 ) {
				if( $sid == 2 ) {
					$i['price_1'] = round($i['price_1']/100*95 , 2);
				}
			}
			
			//Скидка в березку магазин 5%
			if( $this->stats['silver'] >= 5 ) {
				if( $sid == 2 ) {
					$i['price_2'] = round($i['price_2']/100*95 , 2);
				}
			}
			
			$price = $i['price_2']*$x;
			$mxby = 0;
			if($i['max_buy'] > 0) {
				$mxby = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE ( `delete` = "0" OR `delete` = "1000" ) AND `item_id` = "'.mysql_real_escape_string($itm).'" AND `uid` = "'.$this->info['id'].'" LIMIT '.$i['max_buy']));
				if($mxby[0] >= $i['max_buy']) {
					$mxby = -1;
				}
			}

			if($i['max_buy'] < $x && $i['max_buy'] > 0) {
				$r = 'Для покупки доступно '.$i['max_buy'].' шт.';
			}elseif($mxby == -1) {
				$r = 'Максимальное количество покупок для данного предмета исчерпана';
			}elseif($i['price_2']*$x>$this->bank['money2']){
				$r = 'У вас недостаточно денег на счете (не хватает '.($price-$this->bank['money2']).' екр.)';
			}else{
				$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
				$this->bank['money2'] -= $price;
				$upd = mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string(round($this->bank['money2'],2)).'" WHERE `id` = "'.$this->bank['id'].'" LIMIT 1');
				if($upd){
					$this->info['frg'] += $price;
					mysql_query('UPDATE `users` SET `frg` = "'.floor($this->info['frg']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					//новая дата
					$data = '';
					$data .= $d['data'];
					$i['time_create'] = time();
					if( $vip == true ) {
						$i['time_create'] = $this->stats['slvtm'];
						$mdata .= '|vip_sale=1|sudba='.$this->info['login'].'';
						$data = str_replace('sudba=0','',$data);
						$data = str_replace('sudba=1','',$data);
					}
					if($mdata!=NULL){
						$data .= '|'.$mdata;
					}
					$ix = 1; $gix = 0;
					while($ix<=$x){
						if($i['type'] != 71) {
							if( $i['iznos'] > 0 ) {
								$i['iznosMAXi'] = $i['iznos'];
							}
							$ins = mysql_query('INSERT INTO `items_users` (`1price`,`2price`,`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
											"'.$i['price_1'].'",
											"'.$i['price_2'].'",
											"'.$i['overType'].'",
											"'.$i['item_id'].'",
											"'.$this->info['id'].'",
											"'.$data.'|fromshop='.$sid.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geniration'].'",
											"'.$i['magic_inc'].'",
											"'.$this->info['city'].'",
											"'.time().'",
											"'.$i['time_create'].'")');
						}else{
							mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `sex` = "'.$i['sex'].'" AND `img` = "'.str_replace('.png','.gif',$i['img']).'" LIMIT 1');
							$ins = mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("'.$i['sex'].'","'.str_replace('.png','.gif',$i['img']).'","'.$i['level'].'","'.$this->info['id'].'","'.time().'")');
						}
						if($ins){
							$gix++;
						}
						$ix++;
					}
					if($ins){
						//Записываем в личное дело что предмет получен
						$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' екр.<br>Предмет успешно добавлен в инвентарь.';
						mysql_query('UPDATE `items_shop` SET `kolvo` = "'.($i['kolvo']-$x).'" WHERE `iid` = "'.$i['iid'].'" LIMIT 1');
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>EkrShop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i['name'].'</b>&quot; (x'.$x.',add items '.$gix.') [#'.$i['iid'].'] за <b>'.$price.'</b> екр.',time(),$this->info['city'],'EkrShop.'.$this->info['city'].'',(int)$price,0);
					}else{
						//Записываем в личное дело что предмет не получен
						$r = 'Вам не удалось приобрести &quot;'.$i['name'].'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' екр.<br>Приносим свои извинения за неудобства.';
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>EkrShop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i['iid'].'. К возрату: <b>'.$price.'</b> екр.',time(),$this->info['city'],'EkrShop.'.$this->info['city'].'',0,0);
						if(!$ld){
							echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
						}
					}
				}else{
					$r = 'Вам не удалось приобрести предмет...';
				}
			}
		} elseif( ($sid >= 700 && $sid <=730 ) /*OR ( $sid >= 800 && $sid <=805 )*/ ) { // nalpva2.php Покупаем предмет, и перемещаем в него Чарку, Руну и прочее.
			if( $i['kolvo'] < $x ) $x = $i['kolvo'];
			if( $x < 1 ) $x = 1;
			if( $i['price_1'] <= 0 && $i['tr_items']=='' ) $i['price_1'] = $i['price1'];
			if( $i['price_2'] <= 0 && $i['tr_items']=='' ) $i['price_2'] = $i['price2'];

			$price = $i['price_1']*$x;
			$trnt = ''; $detrn = array();
			$trn = 1;
			if($i['tr_items']!='') {
				$tims2 = explode(',',$i['tr_items']);
				$j = 0;
				while($j<count($tims2)) {
					$tims = explode('=',$tims2[$j]);
					if($tims[0]>0 && $tims[1]>0){
						$tis = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$tims[0].'" LIMIT 1'));
						if(isset($tis['id'])) {
							$num_rows = 0;
							$s1p = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$tims[0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.((int)$tims[1]*$x).'');
							while($p1l = mysql_fetch_array($s1p)){
								$num_rows++;
							}
							if($num_rows < (int)$tims[1]*$x){
								$trn = 0;
							}else{
								$detrn[count($detrn)] = array(0 => $tims[0], 1 => ((int)$tims[1]*$x)); //id_item
							}
							$trnt .= '['.$tis['name'].' (x'.$x.')]x'.$tims[1].', ';
						}
					}
					$j++;
				}
				$trnt = rtrim($trnt,', ');
			}
			if( $c['noitembuy'] == true ) {
				$trn = 1;
			}

			if( isset($i['tr_reputation']) && $i['tr_reputation'] != '' ) { // Требуемая репутация для покупки
				$need_rep = 0;
				$tr_rep = $this->lookStats($i['tr_reputation']);
				foreach($tr_rep as $row){
					if( $this->rep[$row[0]] >= $row[1] ){
						$need_rep++;
					}
				}
			} else $need_rep = 0;

			$mxby = 0;
			if($i['max_buy'] > 0) {
				$mxby = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE ( `delete` = "0" OR `delete` = "1000" ) AND `item_id` = "'.mysql_real_escape_string($itm).'" AND `uid` = "'.$this->info['id'].'" LIMIT '.$i['max_buy']));
				if($mxby[0] >= $i['max_buy']) {
					$mxby = -1;
				}
			}

			if($i['max_buy'] < $x && $i['max_buy'] > 0) {
				$r = 'Для покупки доступно '.$i['max_buy'].' шт.';
			}elseif( $need_rep > 0 ) {
				$r = 'Вы не заслужили нашего доверия, мы не продадим вам этот товар.';
			}elseif($i['price_1']*$x>$this->info['money4'] && $zuby == 1){
				$r = 'У вас недостаточно денег.';
			}elseif($mxby == -1) {
				$r = 'Максимальное количество покупок для данного предмета исчерпана<br>Возможно купить: '.$i['max_buy'].' шт.';
			}elseif($trn==0 && $this->info['admin'] == 0){
				$r = 'У вас недостаточно требуемых предметов (не хватает '.$trnt.')';
			}elseif($i['price_1']*$x>$this->info['money'] && $zuby == 0){
				$r = 'У вас недостаточно денег (не хватает '.($price-$this->info['money']).' кр.)';
			}else{
				$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
				if($zuby == 0) {
					$this->info['money'] -= $price;
				}
				$upd = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string(round($this->info['money'],2)).'",`money4` = "'.mysql_real_escape_string(round($this->info['money4'],2)).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				if($upd){
					//новая дата
					$data = '';
					$data .= $d['data'];
					if($mdata!=NULL){
						$data .= '|'.$mdata;
					}
					$j=0;
					$c_itm = 0;
					$c_itm_data_a = array();
					while( $j < count($detrn) ) {
						$itm= mysql_fetch_array(mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$detrn[$j][0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0") AND `inShop` = "0" ORDER BY `inGroup` DESC LIMIT 1'));

						if ( isset($itm['data']) && $itm['data'] != '' && $c_itm == 0){
							$po = $this->lookStats($itm['data']);
							//if(isset($po['complect'])) {
							//	if(isset($po['complect'])) $c_itm_data_a['complect'] = $po['complect'];
							//}
							if(isset($po['sudba'])) {
								if(isset($po['sudba'])) $c_itm_data_a['sudba'] = $po['sudba'];
							}
							if(isset($po['gravi']) && $po['gravi']!='') {
								if(isset($po['gravi'])) $c_itm_data_a['gravi'] = $po['gravi'];
								if(isset($po['gravic'])) $c_itm_data_a['gravic'] = $po['gravic'];
								$c_itm++;
							}
							if(isset($po['imposed_id']) && $po['imposed_id']>0) {
								if(isset($po['imposed'])) $c_itm_data_a['imposed'] = $po['imposed'];
								if(isset($po['imposed_id'])) $c_itm_data_a['imposed_id'] = $po['imposed_id'];
								if(isset($po['imposed_name'])) $c_itm_data_a['imposed_name'] = $po['imposed_name'];
								if(isset($po['imposed_level'])) $c_itm_data_a['imposed_level'] = $po['imposed_level'];
								if(isset($po['bm_a1'])) $c_itm_data_a['bm_a1'] = $po['bm_a1'];
								$c_itm++;
							}
							if(isset($po['spell_id']) && $po['spell_id']>0) {
								if(isset($po['spell'])) $c_itm_data_a['spell'] = $po['spell'];
								if(isset($po['spell_id'])) $c_itm_data_a['spell_id'] = $po['spell_id'];
								if(isset($po['spell_name'])) $c_itm_data_a['spell_name'] = $po['spell_name'];
								if(isset($po['spell_lvl'])) $c_itm_data_a['spell_lvl'] = $po['spell_lvl'];
								if(isset($po['spell_st_name'])) $c_itm_data_a['spell_st_name'] = $po['spell_st_name'];
								if(isset($po['spell_st_val'])) $c_itm_data_a['spell_st_val'] = $po['spell_st_val'];
								$c_itm++;
							}
							if(isset($po['rune_id']) && $po['rune_id']>0) {
								if(isset($po['rune'])) $c_itm_data_a['rune'] = $po['rune'];
								if(isset($po['rune_id'])) $c_itm_data_a['rune_id'] = $po['rune_id'];
								if(isset($po['rune_name'])) $c_itm_data_a['rune_name'] = $po['rune_name'];
								if(isset($po['rune_lvl'])) $c_itm_data_a['rune_lvl'] = $po['rune_lvl'];
								$c_itm++;
							}
							if(isset($po['upatack_id']) && $po['upatack_id']>0) {
								if(isset($po['upatack'])) $c_itm_data_a['upatack'] = $po['upatack'];
								if(isset($po['upatack_id'])) $c_itm_data_a['upatack_id'] = $po['upatack_id'];
								if(isset($po['upatack_name'])) $c_itm_data_a['upatack_name'] = $po['upatack_name'];
								if(isset($po['upatack_lvl'])) $c_itm_data_a['upatack_lvl'] = $po['upatack_lvl'];
								$c_itm++;
							}
						}
						$j++;
					}
					if($c_itm>0){
						$data = $this->lookStats($data);
						// Чарка
						if( isset($c_itm_data_a['spell_st_name']) && isset($c_itm_data_a['spell_st_val']) && $c_itm_data_a['spell_st_name'] != '' && $c_itm_data_a['spell_st_val'] != '' ){
							$data['add_'.$c_itm_data_a['spell_st_name']] = (int)( isset($data['add_'.$c_itm_data_a['spell_st_name']]) ? (int)$data['add_'.$c_itm_data_a['spell_st_name']] : 0 ) + (int)$c_itm_data_a['spell_st_val'];
						}
						// Руна
						if(isset($c_itm_data_a['rune']) && $c_itm_data_a['rune'] > 0) {

							$ritm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$c_itm_data_a['rune_id'].'" LIMIT 1'));
							$j = 0;
							$data_r = $this->lookStats($ritm['data']);
							while( $j < count($this->items['add']) ) {
								if( isset($data_r['add_'.$this->items['add'][$j]]) ) {
									$data['add_'.$this->items['add'][$j]] += $data_r['add_'.$this->items['add'][$j]];
								}
								$j++;
							}

						}

						$data = $this->impStats($data);
						$c_itm_data = $this->impStats($c_itm_data_a);
						$data .= '|'.$c_itm_data;
					}

					$i['gift'] = '';

					$ix = 1; $gix = 0;
					while($ix<=$x){
						if($i['type'] != 71) {
							if( $i['iznos'] > 0 ) {
								$i['iznosMAXi'] = $i['iznos'];
							}
							//
							$i['price_2'] = 0;
							//
							$ins = mysql_query('INSERT INTO `items_users` (`1price`,`2price`,`gift`,`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
											"'.$i['price_1'].'",
											"'.$i['price_2'].'",
											"'.$i['gift'].'",
											"'.$i['overType'].'",
											"'.$i['item_id'].'",
											"'.$this->info['id'].'",
											"'.$data.'|fromshop='.$sid.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geniration'].'",
											"'.$i['magic_inc'].'",
											"'.$this->info['city'].'",
											"'.time().'",
											"'.time().'")');
						}else{
							mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `sex` = "'.$i['sex'].'" AND `img` = "'.str_replace('.png','.gif',$i['img']).'" LIMIT 1');
							$ins = mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("'.$i['sex'].'","'.str_replace('.png','.gif',$i['img']).'","'.$i['level'].'","'.$this->info['id'].'","'.time().'")');
						}
						if($ins){
							$gix++;
						}
						$ix++;
					}
					if($ins){
						//Записываем в личное дело что предмет получен
						if($trnt!='' && $i['tr_items']!=''){
							$trnt = ', '.$trnt;
						}

						if($zuby == 0) {
							$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' кр. '.$trnt.'<br>Предмет успешно добавлен в инвентарь.';
						}else{
							$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$this->zuby($price).'. '.$trnt.'<br>Предмет успешно добавлен в инвентарь.';
						}

						$j = 0;
						while($j<count($detrn)){
							$ost = ((int)$detrn[$j][1]);
							$s4 = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$detrn[$j][0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" ORDER BY `inGroup` DESC LIMIT '.((int)$detrn[$j][1]).'');
							while($itm = mysql_fetch_array($s4)){

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
						if(!$ld){
							echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
						}
					}
				}else{
					$r = 'Вам не удалось приобрести предмет...';
				}
			}
		} else { // Стандартная покупка предмета
			if($i['kolvo']<$x){
				$x = $i['kolvo'];
			}
			if($x<1){
				$x = 1;
			}
			if($i['price_1']<=0 && $i['tr_items']==''){
				$i['price_1'] = $i['price1'];
			}
			if($i['price_2']<=0 && $i['tr_items']==''){
				$i['price_2'] = $i['price2'];
			}
			
			//Скидка в государственный магазин 5%
			if( $this->stats['silver'] >= 1 ) {
				if( $sid == 1 ) {
					$i['price_1'] = round($i['price_1']/100*95 , 2);
				}
			}
			
			//Скидка в березку магазин 5%
			if( $this->stats['silver'] >= 5 ) {
				if( $sid == 2 ) {
					$i['price_2'] = round($i['price_2']/100*95 , 2);
				}
			}
			
			$price = $i['price_1']*$x;
						
			$trnt = ''; $detrn = array();
			$trn = 1;
			if($i['tr_items']!='') {
				$tims2 = explode(',',$i['tr_items']);
				$j = 0;
				while($j<count($tims2)){
					$tims = explode('=',$tims2[$j]);
					if($tims[0]>0 && $tims[1]>0){
						$tis = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$tims[0].'" LIMIT 1'));
						if(isset($tis['id'])){
							$num_rows = 0;
							$s1p = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$tims[0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.((int)$tims[1]*$x).'');
							while($p1l = mysql_fetch_array($s1p)){
								$num_rows++;
							}
							if($num_rows < (int)$tims[1]*$x){
								$trn = 0;
							}else{
								$detrn[count($detrn)] = array(0 => $tims[0], 1 => ((int)$tims[1]*$x)); //id_item
							}
							$trnt .= '['.$tis['name'].' (x'.$x.')]x'.$tims[1].', ';
						}
					}
					$j++;
				}
				$trnt = rtrim($trnt,', ');
			}
			if( $c['noitembuy'] == true ) {
				$trn = 1;
			}

			if( isset($i['tr_reputation']) && $i['tr_reputation']!=''){ // Требуемая репутация для покупки
				$need_rep = 0;
				$tr_rep = $this->lookStats($i['tr_reputation']);
				foreach($tr_rep as $key=>$val){
					if( (int)$this->rep[$key] > (int)$val ){
						$need_rep++;
					}
				}
			} else unset($need_rep);

			$mxby = 0;
			if($i['max_buy'] > 0) {
				$mxby = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE ( `delete` = "0" OR `delete` = "1000" ) AND `item_id` = "'.mysql_real_escape_string($itm).'" AND `uid` = "'.$this->info['id'].'" LIMIT '.$i['max_buy']));
				if($mxby[0] >= $i['max_buy']) {
					$mxby = -1;
				}
			}

			$zuby = 0;
			if(isset($_GET['zuby']) && $i['level'] < 8 && $c['zuby'] == true) {
				$zuby = 1;
			}

			$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));
			$po = $this->lookStats($d['data']);
			
			if( $zuby == 1 && $po['tr_lvl'] > 7 ) {
				$r = 'Данный предмет нельзя приобрести за зубы.';
			}elseif(!isset($this->sid_zuby[$sid]) && $zuby == 1) {
				$r = 'Данный предмет нельзя приобрести за зубы.';
			}elseif($zuby == 1 && $i['nozuby'] == 1) {
				$r = 'Данный предмет нельзя приобрести за зубы.';
			}elseif($i['max_buy'] < $x && $i['max_buy'] > 0) {
				$r = 'Для покупки доступно '.$i['max_buy'].' шт.';
			}elseif( isset($need_rep) && $need_rep == 0 ) {
				$r = 'Вы не заслужили нашего доверия, мы не продадим вам этот товар.';
			}elseif($zuby == 1 && $this->info['money4'] < $i['price_1']) {
				$r = 'У вас недостаточно зубов.';
			}elseif($i['price_1']*$x>$this->info['money4'] && $zuby == 1){
				$r = 'У вас недостаточно денег.';
			}elseif($mxby == -1) {
				$r = 'Максимальное количество покупок для данного предмета исчерпана<br>Возможно купить: '.$i['max_buy'].' шт.';
			}elseif($trn==0 && $this->info['admin'] == 0){
				$r = 'У вас недостаточно требуемых предметов (не хватает '.$trnt.')';
			}elseif($i['price_1']*$x > $this->info['money'] && $zuby == 0){
				$r = 'У вас недостаточно денег (не хватает '.($price-$this->info['money']).' кр.)';
			}else{
				if($zuby == 0) {
					$this->info['money'] -= $price;
				}else{
					$this->info['money4'] -= $price;
				}
				$upd = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string(round($this->info['money'],2)).'",`money4` = "'.mysql_real_escape_string(round($this->info['money4'],2)).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				if($upd){
					//новая дата
					$data = '';
					$data .= $d['data'];
					$data = str_replace('sudba=1','sudba='.$this->info['login'],$data);

					if($mdata!=NULL){
						$data .= '|'.$mdata;
					}

					$i['gift'] = '';
					if($zuby == 1) {
						$i['gift'] = '1';
						$data .= '|nosale=1|zazuby='.round($price/$x,2).'';
					}
					
					if( $sid == 404 ) {
						$data .= '|nosale=1|notransfer=1|fromshop=404|sudba=1';
					}
					
					$ix = 1; $gix = 0;
					while($ix<=$x){
						if($i['type'] != 71) {
							if( $i['iznos'] > 0 ) {
								$i['iznosMAXi'] = $i['iznos'];
							}
							//
							$i['price_2'] = 0;
							//
							$ins = mysql_query('INSERT INTO `items_users` (`1price`,`2price`,`gift`,`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`) VALUES (
											"'.$i['price_1'].'",
											"'.$i['price_2'].'",
											"'.$i['gift'].'",
											"'.$i['overType'].'",
											"'.$i['item_id'].'",
											"'.$this->info['id'].'",
											"'.$data.'|fromshop='.$sid.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geniration'].'",
											"'.$i['magic_inc'].'",
											"'.$this->info['city'].'",
											"'.time().'",
											"'.time().'")');
						}else{
							mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `sex` = "'.$i['sex'].'" AND `img` = "'.str_replace('.png','.gif',$i['img']).'" LIMIT 1');
							$ins = mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("'.$i['sex'].'","'.str_replace('.png','.gif',$i['img']).'","'.$i['level'].'","'.$this->info['id'].'","'.time().'")');
						}
						if($ins){
							$gix++;
						}
						$ix++;
					}
					if($ins){
						//Записываем в личное дело что предмет получен
						if($trnt!='' && $i['tr_items']!=''){
							$trnt = ', '.$trnt;
						}

						if($zuby == 0) {
							$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$price.' кр. '.$trnt.'<br>Предмет успешно добавлен в инвентарь.';
						}else{
							$r = 'Вы приобрели предмет &quot;'.$i['name'].'&quot; (x'.$x.' / '.$gix.') за '.$this->zuby($price).'. '.$trnt.'<br>Предмет успешно добавлен в инвентарь.';
						}

						$zx = 0; $rs = 0;
						while($zx < $x) {

							if( ( $sid == 1 || $sid == 5 ) && $i['level'] >= 4 && round($price/$x) > 25 ) {
								if($this->stats['shopSale'] == 0 && $c['nosanich'] == false) {
									//Выдаем странички Саныча в гос магазине и канаве
									$prmn = 0; //дополнительный бонус
									$prmn = (1-( pow(0.5, ($price/$x/200) ) ))*100;
									$prmn = round(20/100*$prmn);
									if(rand(0,100) < 20+$prmn && rand(0,100) > 80-$prmn) {
										//3143 - 3192
										if(rand(0,100) < 20+$prmn && rand(0,100) > 80-$prmn) {
											//обложка
											$gitm = rand(3193,3195);
										}else{
											$gitm = rand(3143,3192);
										}
										if($gitm == 3193) {
											$gitm = 3194;
										}
										if($sid == 5) {
											//привязываем судьбой
										}
										$this->addItem($gitm,$this->info['id']);
										$rs++;
									}
								}
							}
							$zx++;
						}

						if($rs > 1) {
							if(rand(0,1) == 1) {
								$r .= ' Предмет был завернут в какую-то бумажку. (x'.$rs.')';
							}elseif(rand(0,1)){
								$r .= ' Предмет был завернут в странный свиток. (x'.$rs.')';
							}else{
								$r .= ' К предмету была прикреплена какая-та бумажка. (x'.$rs.')';
							}
						}elseif($rs > 0) {
							if(rand(0,1) == 1) {
								$r .= ' Предмет был завернут в какую-то бумажку.';
							}elseif(rand(0,1)){
								$r .= ' Предмет был завернут в странный свиток.';
							}else{
								$r .= ' К предмету была прикреплена какая-та бумажка.';
							}
						}


						$j = 0;
						while($j<count($detrn)){
							$ost = ((int)$detrn[$j][1]);
							$s4 = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$detrn[$j][0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" ORDER BY `inGroup` DESC LIMIT '.((int)$detrn[$j][1]).'');
							while($itm = mysql_fetch_array($s4)){
								//удаляем предмет
									mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
							}
							$j++;
						}





						mysql_query('UPDATE `items_shop` SET `kolvo` = "'.($i['kolvo']-$x).'" WHERE `iid` = "'.$i['iid'].'" LIMIT 1');
						$zuby_inf = '';
						if($zuby == 1) {
							$zuby_inf = ' <font color=red><small>(За зубы)</small></font>';
						}
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i['name'].'</b>&quot; (x'.$x.') [#'.$i['iid'].'] за <b>'.$price.'</b> кр.'.$zuby_inf.'',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
					}else{
						//Записываем в личное дело что предмет не получен
						$r = 'Вам не удалось приобрести &quot;'.$i['name'].'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' кр.<br>Приносим свои извинения за неудобства.';
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=green>Shop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i['iid'].'. К возрату: <b>'.$price.'</b> кр.',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
						if(!$ld){
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
	  
	public function takeBonus() {
		global $c;
		/*$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$this->info['id'].'" AND `time` > '.time().' LIMIT 1'));
		if(!isset($bns['id'])) {
			$rndi = array(
				//хилки
				2544,
				2543,
				2542,
				2481,
				//
				2435,
				4285,
				4286,
				4371,
				3106,
				3105,
				1163,
				1164,
				//
				4263,
				4248,
				4253,
				4258,
				//
				886,
				885,
				884,
				883,
				3109,
				//
				4373,
				4374,
				4375,
				4376,
				4377,
				4378,
				4379,
				4380,
				4381,
				4382,
				4383,
				4384,
				4385,
				//
				4272,
				//манки :D
				//4024
				//
			);
			$i1 = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`pricerep` FROM `items_main` WHERE `id` = "'.$rndi[rand(0,count($rndi)-1)].'" LIMIT 1'));
			$i2 = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`pricerep` FROM `items_main` WHERE `id` = "'.$rndi[rand(0,count($rndi)-1)].'" LIMIT 1'));
			$i3 = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`pricerep` FROM `items_main` WHERE `id` = "'.$rndi[rand(0,count($rndi)-1)].'" LIMIT 1'));
			//4393 - 1 екр.
			$this->addItem(4393,$this->info['id'],'|nosale=1|sudba='.$this->info['login'].'');
			$txt = '<b>Чек на 1екр.</b>, ';
			if( isset($i1['id']) ) {
				$txt .= '<b>'.$i1['name'].'</b>, ';
				if($i1['pricerep'] == 0) {
					$this->addItem($i1['id'],$this->info['id'],'|nosale=1|sudba='.$this->info['login'].'',NULL,1);
				}else{
					$this->addItem($i1['id'],$this->info['id'],'|sudba='.$this->info['login'].'',NULL,1);
				}
			}
			if( isset($i2['id']) ) {
				$txt .= '<b>'.$i2['name'].'</b>, ';
				if($i2['pricerep'] == 0) {
					$this->addItem($i2['id'],$this->info['id'],'|nosale=1|sudba='.$this->info['login'].'',NULL,1);
				}else{
					$this->addItem($i2['id'],$this->info['id'],'|sudba='.$this->info['login'].'',NULL,1);
				}
			}
			if( isset($i3['id']) ) {
				$txt .= '<b>'.$i3['name'].'</b>, ';
				if($i3['pricerep'] == 0) {
					$this->addItem($i3['id'],$this->info['id'],'|nosale=1|sudba='.$this->info['login'].'',NULL,1);
				}else{
					$this->addItem($i3['id'],$this->info['id'],'|sudba='.$this->info['login'].'',NULL,1);
				}
			}
			$txt = rtrim($txt,', ');
			$this->error = 'Вы успешно открыли сундук';
			
			$text1 = '[img[items/event_sunduk.gif]] Открыв сундук Вы обнаружили в нем: '.$txt.'!';
			mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','".$text1."','-1','6','0',1)");
			mysql_query('INSERT INTO `aaa_bonus` (`uid`,`time`) VALUES (
				"'.$this->info['id'].'","'.(time()+2*3600).'"
			)');
		}*/
			//Каптча
			if(!isset($_SESSION['code3'])) {
				session_start();
			}
			if(!isset($_GET['cptch1']) || $_GET['cptch1'] == 0 || $_GET['cptch1'] == '' || $_GET['cptch1'] != $_SESSION['code3']) {
				$this->error = 'Неверно указан код на картинки';
			}elseif(!isset($_GET['getb1w'])) {
				/*$this->info['money'] += 25;
				mysql_query('UPDATE `users` SET `money` = "'.$this->info['money'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				mysql_query('INSERT INTO `aaa_bonus` (`uid`,`time`) VALUES (
					"'.$this->info['id'].'","'.(time()+1*3600).'"
				)');
				$this->error = 'Вам зачислено 25 кр.';*/
			}else{
				if( $_GET['getb1w'] == 3 ) {
					//$bnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$this->info['id'].'" AND `block` = "0" ORDER BY `useNow` DESC LIMIT 1'));
					//if( isset($bnk['id']) ) {
					//if($this->info['level'] >= 8 ) {
						$money1 = 0;
						$money2 = 0;
						$nagatxt = '';
						if( $this->info['align'] == 0 || $this->info['align'] == 2 ) {
							$money1 = round($c['bonline'][0][$this->info['level']]*$c['bonusonline_kof'],2);
							$this->info['money'] += $money1;
							mysql_query('UPDATE `users` SET `money` = "'.$this->info['money'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
							$this->error = 'На счет персонажа зачислено '.$money1.' кр.';
							mysql_query('INSERT INTO `aaa_bonus` (`uid`,`time`,`money1`,`money2`,`type`) VALUES (
								"'.$this->info['id'].'","'.(time()+1*3600).'","'.$money1.'","'.$money2.'","0"
							)');
							$nagatxt = '<img src=/coin1.png >&nbsp;<b>'.$money1.' кр.</b>';
						}elseif( $this->info['align'] > 0 ) {
							if( $this->bank['id'] > 0 ) {
								$money2 = round($c['bonline'][1][$this->info['level']]*$c['bonusonline_kof'],2);
								$this->bank['money2'] += $money2;
								mysql_query('UPDATE `bank` SET `money2` = "'.$this->bank['money2'].'" , `shara` = `shara` + "'.$this->bank['money2'].'" WHERE `id` = "'.$this->bank['id'].'" LIMIT 1');
								$this->error = 'На банковский счет персонажа зачислено '.$money2.' екр.';
								mysql_query('INSERT INTO `aaa_bonus` (`uid`,`time`,`money1`,`money2`,`type`) VALUES (
									"'.$this->info['id'].'","'.(time()+1*3600).'","'.$money1.'","'.$money2.'","0"
								)');
								$nagatxt = '<img src=/coin2.png >&nbsp;<b><font color=green>'.$money2.' екр.</font></b>';
							}else{
								$this->error = 'Авторизируйтесь в банке чтобы получать награду в екр.';
							}
						}
						if( $nagatxt != '' ) {
							$txtb1 = 'Вы получили награду за онлайн '.$nagatxt.' (Следующий раз через час)';
							mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','".$txtb1."','".time()."','6','0')");
						}
						//$this->error = 'Получение кр. временно недоступно.';
					/*}elseif($this->info['level'] == 7 ) {
						mysql_query('UPDATE `users` SET `money4` = `money4` + "'.round($this->info['level']*0.75,2).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						$this->error = 'На счет персонажа зачислено '.$this->zuby(round($this->info['level']*0.75,2)).'';
						mysql_query('INSERT INTO `aaa_bonus` (`uid`,`time`) VALUES (
							"'.$this->info['id'].'","'.(time()+1*3600).'"
						)');
						//$this->error = 'Получение кр. временно недоступно.';
					}else{
						$expad = round(50+($this->stats['levels']['exp']-$this->info['exp'])/2);
						if( $expad > 100 ) {
							$expad = 100;
						}
						mysql_query('UPDATE `stats` SET `exp` = `exp` + "'.$expad.'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						$this->error = 'Опыт персонажа повышен на '.$expad.' ед. опыта';
						mysql_query('INSERT INTO `aaa_bonus` (`uid`,`time`) VALUES (
							"'.$this->info['id'].'","'.(time()+1*3600).'"
						)');
						$this->info['exp'] += $expad;
						$this->stats['exp'] += $expad;
					}*/
					//}else{
						//Нет банка!
					//	$this->error = 'Нельзя получить екр. т.к. у Вас нет банковского счета';
					//}
				}else{
					/*$this->rep['rep3'] += 3*$this->info['level'];
					mysql_query('UPDATE `rep` SET `rep3` = "'.$this->rep['rep3'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					mysql_query('INSERT INTO `aaa_bonus` (`uid`,`time`) VALUES (
						"'.$this->info['id'].'","'.(time()+1*3600).'"
					)');
					$this->error = 'Вам зачислено '.(3*$this->info['level']).' Воин.';*/
				}
			}
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
	
	public function newAct($test){
		$r = true;
		if($test!=$this->info['nextAct'] && $this->info['nextAct']!='0'){
			$r = false;
		}else{
			$na = md5(time().'_nextAct_'.rand(0,100));
			$upd = mysql_query('UPDATE `stats` SET `nextAct` = "'.$na.'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			if(!$upd){
				$r = false;
			}else{
				$this->info['nextAct'] = $na;
			}
		}
		return $r;
	}

	public function buyItemCommison($sid, $item, $iid = NULL) {
		global $sid;
		$sid = mysql_real_escape_string($sid);
		$itme = mysql_real_escape_string($item);
		$iid = mysql_real_escape_string($iid);
		$i2 = mysql_fetch_array(mysql_query('SELECT `iu`.`uid`, `iu`.`id`, `iu`.`uid`, `iu`.`1price`, `iu`.inGroup, `iu`.`data`, `iu`.`inShop`, `iu`.`item_id`, `u`.`login` as login FROM `items_users` as `iu` LEFT JOIN `users` as `u` ON `u`.id=`iu`.uid WHERE `iu`.`id` = '.$iid.' AND `iu`.`inShop` = 30 LIMIT 1'));
		$i1 = mysql_fetch_array(mysql_query('SELECT `name`,`price1` FROM `items_main` WHERE `id` = '.$i2['item_id'].' LIMIT 1'));
		$price = $i2['1price'];
		if(isset($i2['id']) && isset($iid) && $sid==1 && $i2['inShop']==30){
			if($price>$this->info['money'])
				$r = 'У вас недостаточно денег (не хватает '.($price-$this->info['money']).' кр.)';
			else{
				$UpdMoney = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string(round($this->info['money']-$price,2)).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				if($UpdMoney) {
					$this->info['money'] -= $price;
					$UpMoney2 = mysql_query('UPDATE `users` SET `money` = `money` + '.$price.' WHERE `id` = '.$i2['uid'].' LIMIT 1');
					#$UpItems = mysql_query('UPDATE `items_users` SET `uid` = '.$this->info['id'].', `1price` = "'.$i1['price1'].'", `lastUPD` = "'.time().'", `inShop` = 0 WHERE `id` = '.$iid.' and `inShop` = 30 LIMIT 1');
					
					$col = $this->itemsX($iid);
					if($col > 1) {
						$UpItems = mysql_query('UPDATE `items_users` SET `uid` = '.$this->info['id'].', `inGroup` = `inGroup`+1000, `1price` = "'.$i1['price1'].'", `lastUPD` = "'.time().'", `inShop` = 0 WHERE `inShop` = 30 AND `inOdet` = "0"AND `item_id`="'.$i2['item_id'].'" AND `uid`="'.$i2['uid'].'" AND `inGroup` = "'.$i2['inGroup'].'" LIMIT '.$col.'');
					} else {
						$UpItems = mysql_query('UPDATE `items_users` SET `uid` = '.$this->info['id'].', `1price` = "'.$i1['price1'].'", `lastUPD` = "'.time().'", `inShop` = 0 WHERE  `id` = "'.$iid.'" AND `inOdet` = "0" AND `delete` = "0" AND `uid`="'.$i2['uid'].'" LIMIT 1');
					}
					//Вставляем функцию передачи кредитов владельцу предмета
					if($UpItems){
						//Записываем в личное дело что предмет получен
						$r = 'Вы приобрели предмет &quot;'.$i1['name'].( $col>1 ? ' (x'.$col.')' : '').'&quot; за '.$price.' кр.<br>Предмет успешно добавлен в инвентарь.';
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>ComissShop.'.$this->info['city'].'</font>&quot;: Приобрел предмет &quot;<b>'.$i1['name'].( $col>1 ? ' (x'.$col.')' : '').'</b>&quot; [item:'.$iid.'] в коммисионном магазине за <b>'.$price.'</b> кр. [Продавец: '.( $i2['login']!='' ? '<a href="http://xcombats.com/info/'.$i2['uid'].'" target="_blank">'.$i2['login'].'</a>' : '('.$i2['uid'].')').']',time(),$this->info['city'],'Shop.'.$this->info['city'].'',$price,0);
						$u2s = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$i2['uid'].'" LIMIT 1'));
						$ld = $this->addDelo(1,$u2s['id'],'&quot;<font color=#C65F00>ComissShop.'.$this->info['city'].'</font>&quot;: Продал предмет &quot;<b>'.$i1['name'].( $col>1 ? ' (x'.$col.')' : '').'</b>&quot; [item:'.$iid.'] через коммисионный магазине за <b>'.$price.'</b> кр. [Покупатель: <a href="http://xcombats.com/info/'.$this->info['id'].'" target="_blank">'.$this->info['login'].'</a>]',time(),$this->info['city'],'Shop.'.$this->info['city'].'',0,$price);
					} else {
						//Записываем в личное дело что предмет не получен
						$r = 'Вам не удалось приобрести &quot;'.$i1['name'].( $col>1 ? ' (x'.$col.')' : '').'&quot;. Администрация магазина в &quot;'.$this->city_name[$this->info['city']].'&quot; должна Вам '.$price.' екр.<br>Приносим свои извинения за неудобства.';
						$ld = $this->addDelo(1,$this->info['id'],'&quot;<font color=#C65F00>EkrShop.'.$this->info['city'].'</font>&quot;: не удалось приобрести предмет #'.$i1['iid'].'. К возрату: <b>'.$price.'</b> кр.',time(),$this->info['city'],'Shop.'.$this->info['city'].'',(int)$price,0);
						if(!$ld) echo '<div>Ошибка, невозможно добавить запись в /db/usersDelo/!</div>';
					}
				} else {
					$r = 'Вам не удалось приобрести предмет...';
				}
			}
		}else
			$r = 'Предмет не найден на прилавке';
		return '<div align="left">'.$r.'</div>';
	}
	 	 
	public function commisonRent($action,$iid,$price=NULL) {
		if($action=="Сдать в магазин" && isset($iid) && $price >0 ) {
			$ChImtem = mysql_fetch_array(mysql_query('SELECT `id`, `item_id`, `data`, `inGroup`, `uid` FROM `items_users` WHERE `id` = '.$iid.' LIMIT 1')); 
			$ChSudba = $this->lookStats($ChImtem['data']);
			if(isset($ChSudba['sudba']) || $ChSudba['sudba'] != 0 || $ChSudba['sudba']==1 || isset($ChSudba['toclan'])) {
				continue;
			} else {
				if( isset($ChImtem['inGroup']) AND $ChImtem['inGroup'] > 0 ) {
					$col = $this->itemsX($ChImtem['id']);
					if($col > 1){
						mysql_query('UPDATE `items_users` SET `inShop` = 30, `1price` = '.$price.' WHERE `item_id`="'.$ChImtem['item_id'].'" AND `uid`="'.$ChImtem['uid'].'" AND `inGroup` = "'.$ChImtem['inGroup'].'" LIMIT '.$col.'');
					} else {
						mysql_query('UPDATE `items_users` SET `inShop` = 30, `1price` = '.$price.' WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$iid.'" AND `inOdet` = "0" AND `delete` = "0" ');
					}
				} else {
					mysql_query('UPDATE `items_users` set `inShop` = 30, `1price` = '.$price.' where `uid` = "'.$this->info['id'].'" AND `id` = "'.$iid.'" AND `inOdet` = "0" AND `delete` = "0" ');
				}
			}
		}elseif($action=="Забрать" && isset($iid)) {
			$i = mysql_fetch_array(mysql_query('SELECT `im`.`price1`,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.$iid.'" AND `iu`.`inShop` = "30" AND `iu`.`delete` = "0" LIMIT 1'));
			if( isset($i['inGroup']) AND $i['inGroup'] > 0 ){
				$col = $this->itemsX($i['id']);
				if($col > 1){
					mysql_query('UPDATE `items_users` SET `inShop` = 0, `1price` = '.$i['price1'].' WHERE `item_id`="'.$i['item_id'].'" AND `inShop` = "30" AND `uid`="'.$i['uid'].'" AND `inGroup` = "'.$i['inGroup'].'" LIMIT '.$col.'');
				} else {
					mysql_query('UPDATE `items_users` SET `inShop` = 0, `1price` = '.$i['price1'].' WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$iid.'" AND `inOdet` = "0" AND `delete` = "0" ');
				}
			} else {
				mysql_query('UPDATE `items_users` SET `inShop` = 0, `1price` = "'.$i['price1'].'" WHERE `id` = "'.$iid.'" and `inShop` = "30" LIMIT 1');
			}
		}
	}

	public function bronFx($br) {
		$r = '';
		if( $br[0] > 0 ) {
			$r .= $br[0]-1;
		}else{
			$r .= 0;
		}
		$r .= 'd';
		if( $br[0] > 0 ) {
			$r .= $br[1]-$br[0];
		}else{
			$r .= 0;
		}
		return $r;
	}
	 	 
	/*public function commisionShop($sid,$preview = "full"){	 	 	 	 	 	 	 	    
		global $c,$code,$sid;
	 	 	 
		switch ((int)$_GET['otdel']) {
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
					case 1050:$typeOtdel = 1050; break;
	 	 	 	 	 default :$typeOtdel = 18;
		}
		
		
		//
		
		if( $typeOtdel == 1050 ) {
			//Просмотр прочего
			if($preview == "full") {
				
			}else{
				$sp = mysql_query('SELECT `a`.*,`b`.* FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`inShop` = "30" AND
					`b`.`type` != "18" AND
					`b`.`type` != "19" AND
					`b`.`type` != "20" AND
					`b`.`type` != "21" AND
					`b`.`type` != "22" AND
					`b`.`type` != "15" AND
					`b`.`type` != "12" AND
					`b`.`type` != "4" AND
					`b`.`type` != "5" AND
					`b`.`type` != "6" AND
					`b`.`type` != "1" AND
					`b`.`type` != "3" AND
					`b`.`type` != "8" AND
					`b`.`type` != "14" AND
					`b`.`type` != "13" AND
					`b`.`type` != "9" AND
					`b`.`type` != "10" AND
					`b`.`type` != "11" AND
					`b`.`type` != "29" AND
					`b`.`type` != "30"
					
					GROUP BY `b`.`name`
					
					');
			}
		}else{
			//Вывод общего списка предметов
			if($preview == "full") {
					
			}else{
				$sp = mysql_query('SELECT `a`.*,`b`.* FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`inShop` = "30" AND `b`.`type` = "'.mysql_real_escape_string($typeOtdel).'" GROUP BY `b`.`name` ORDER BY `b`.`name` DESC');
			}
			//
		}
		$cr = 'c8c8c8';
		$i = 0;
	 	 $steckCikl = 1;
		while($pl = mysql_fetch_array($sp)) {
			//
			$is1 = '';
			$is2 = '';
			//
			if($preview == "full") {
				
			}else{		
				//Отоюражение в разделах
				//$pvr1 = mysql_fetch_array(mysql_query('SELECT `1price` FROM `items_users` WHERE `inShop` = 30 AND `item_id` = "'.$pl['item_id'].'" ORDER BY `1price` ASC LIMIT 1'));
				//$pvr2 = mysql_fetch_array(mysql_query('SELECT `1price` FROM `items_users` WHERE `inShop` = 30 AND `item_id` = "'.$pl['item_id'].'" ORDER BY `1price` DESC LIMIT 1'));
				//
				$is2 .= '<a target="_blank" href="http://xcombats.com/item/'.$pl['item_id'].'">'.$pl['name'].'</a> &nbsp; (Масса: '.$pl['massa'].')';
				//$is2 .= '<br><b>Цена: '.$pvr1[0].'-'.$pvr2[0].' кр.</b> <small>(количество: 0)</small>';
				//
				//$pvr1 = mysql_fetch_array(mysql_query('SELECT `iznosNOW`,`iznosMAX` FROM `items_users` WHERE `inShop` = 30 AND `item_id` = "'.$pl['item_id'].'" ORDER BY `iznosNOW` ASC LIMIT 1'));
				//$pvr2 = mysql_fetch_array(mysql_query('SELECT `iznosNOW`,`iznosMAX` FROM `items_users` WHERE `inShop` = 30 AND `item_id` = "'.$pl['item_id'].'" ORDER BY `iznosNOW` DESC LIMIT 1'));
				//
				//$is2 .= '<br>Долговечность: '.floor($pvr1[0]).'-'.floor($pvr1[1]).'/'.floor($pvr2[0]).'-'.floor($pvr2[1]).'';
				//
				$is1 .= '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'"><br>';	
				$is1 .= '<a href="?toRent=3&otdel='.round($_GET['otdel']).'&itemid='.$pl['item_id'].'">подробнее</a><br>';			
			}
			echo '<tr style="background-color:#'.$cr.';"><td width="100" style="padding:7px;" valign="middle" align="center">'.$is1.'</td><td style="padding:7px;" valign="top">'.$is2.'</td></tr>';
			if( $cr == 'c8c8c8' ) {
				$cr = 'd4d4d4';
			}else{
				$cr = 'c8c8c8';
			}
			$i++;
		}
		//
		if( $i == 0 ) {
			echo '<tr style="background-color:#'.$cr.';"><td style="padding:7px;" align="center" valign="top">Прилавок магазина пуст</td></tr>';
		}
		//
	 	 //*   * * * * * * * * * *  
	 }*/
	
	public function commisionShop($sid,$preview = "full"){
		global $c,$code,$sid;
		switch ((int)$_GET['otdel']){
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
			case 1050:$typeOtdel = 1050; break;
			default :$typeOtdel = 18;
	 	}
	 	if($typeOtdel != 1050) {
			if($preview == "full"){
				$cl = mysql_query('SELECT `items_users`.`id`,`items_users`.`item_id`,`items_users`.`1price`,`items_users`.`2price`,`items_users`.`uid`,`items_users`.`use_text`,`items_users`.`data`,`items_users`.`inOdet`,`items_users`.`inShop`,`items_users`.`delete`,`items_users`.`iznosNOW`,`items_users`.`iznosMAX`,`items_users`.`gift`,`items_users`.`gtxt1`,`items_users`.`gtxt2`,`items_users`.`kolvo`,`items_users`.`geniration`,`items_users`.`magic_inc`,`items_users`.`maidin`,`items_users`.`lastUPD`,`items_users`.`timeOver`,`items_users`.`overType`,`items_users`.`secret_id`,`items_users`.`time_create`,`items_users`.`inGroup`,`items_users`.`dn_delete`,`items_users`.`inTransfer`,`items_users`.`post_delivery`,`items_users`.`lbtl_`,`items_users`.`bexp`,`items_users`.`so`,`items_users`.`blvl`,`items_main`.`id`,`items_main`.`name`,`items_main`.`img`,`items_main`.`type`,`items_main`.`inslot`,`items_main`.`2h`,`items_main`.`2too`,`items_main`.`iznosMAXi`,`items_main`.`inRazdel`,`items_main`.`price1`,`items_main`.`price2`,`items_main`.`magic_chance`,`items_main`.`info`,`items_main`.`massa`,`items_main`.`level`,`items_main`.`magic_inci`,`items_main`.`overTypei`,`items_main`.`group`,`items_main`.`group_max`,`items_main`.`geni`,`items_main`.`ts`,`items_main`.`srok`,`items_main`.`class`,`items_main`.`class_point`,`items_main`.`anti_class`,`items_main`.`anti_class_point`,`items_main`.`max_text`,`items_main`.`useInBattle`,`items_main`.`lbtl`,`items_main`.`lvl_itm`,`items_main`.`lvl_exp`,`items_main`.`lvl_aexp`, count(`items_users`.`id`) as inGroupCount
FROM `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`)
WHERE (( `items_users`.time_create + `items_main`.srok) > unix_timestamp()  OR `items_main`.srok = "0") AND `items_users`.`delete`="0" AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` = "'.mysql_real_escape_string($typeOtdel).'" and `items_users`.`item_id` = "'.(INT)$_GET['itemid'].'" GROUP BY `items_users`.`inGroup`, `items_users`.`uid`,`items_users`.`1price` HAVING `items_users`.inGroup > 0
UNION ALL SELECT `items_users`.`id`,`items_users`.`item_id`,`items_users`.`1price`,`items_users`.`2price`,`items_users`.`uid`,`items_users`.`use_text`,`items_users`.`data`,`items_users`.`inOdet`,`items_users`.`inShop`,`items_users`.`delete`,`items_users`.`iznosNOW`,`items_users`.`iznosMAX`,`items_users`.`gift`,`items_users`.`gtxt1`,`items_users`.`gtxt2`,`items_users`.`kolvo`,`items_users`.`geniration`,`items_users`.`magic_inc`,`items_users`.`maidin`,`items_users`.`lastUPD`,`items_users`.`timeOver`,`items_users`.`overType`,`items_users`.`secret_id`,`items_users`.`time_create`,`items_users`.`inGroup`,`items_users`.`dn_delete`,`items_users`.`inTransfer`,`items_users`.`post_delivery`,`items_users`.`lbtl_`,`items_users`.`bexp`,`items_users`.`so`,`items_users`.`blvl`,`items_main`.`id`,`items_main`.`name`,`items_main`.`img`,`items_main`.`type`,`items_main`.`inslot`,`items_main`.`2h`,`items_main`.`2too`,`items_main`.`iznosMAXi`,`items_main`.`inRazdel`,`items_main`.`price1`,`items_main`.`price2`,`items_main`.`magic_chance`,`items_main`.`info`,`items_main`.`massa`,`items_main`.`level`,`items_main`.`magic_inci`,`items_main`.`overTypei`,`items_main`.`group`,`items_main`.`group_max`,`items_main`.`geni`,`items_main`.`ts`,`items_main`.`srok`,`items_main`.`class`,`items_main`.`class_point`,`items_main`.`anti_class`,`items_main`.`anti_class_point`,`items_main`.`max_text`,`items_main`.`useInBattle`,`items_main`.`lbtl`,`items_main`.`lvl_itm`,`items_main`.`lvl_exp`,`items_main`.`lvl_aexp`, count(`items_users`.`id`) as inGroupCount
FROM `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`)
WHERE (( `items_users`.time_create + `items_main`.srok) > unix_timestamp()  OR `items_main`.srok = "0") AND `items_users`.`delete`="0" AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` = "'.mysql_real_escape_string($typeOtdel).'" and `items_users`.`item_id` = "'.(INT)$_GET['itemid'].'" GROUP BY `items_users`.`uid`, `items_users`.`1price` HAVING `items_users`.inGroup = 0 
ORDER BY `1price`ASC , inGroupCount DESC');	 	 	 	 	 	 	 	 	 
	 	 	} else {
				$cl = mysql_query('SELECT `items_users`.`id`,`items_users`.`item_id`,`items_users`.`1price`,`items_users`.`2price`,`items_users`.`uid`,`items_users`.`use_text`,`items_users`.`data`,`items_users`.`inOdet`,`items_users`.`inShop`,`items_users`.`delete`,`items_users`.`iznosNOW`,`items_users`.`iznosMAX`,`items_users`.`gift`,`items_users`.`gtxt1`,`items_users`.`gtxt2`,`items_users`.`kolvo`,`items_users`.`geniration`,`items_users`.`magic_inc`,`items_users`.`maidin`,`items_users`.`lastUPD`,`items_users`.`timeOver`,`items_users`.`overType`,`items_users`.`secret_id`,`items_users`.`time_create`,`items_users`.`inGroup`,`items_users`.`dn_delete`,`items_users`.`inTransfer`,`items_users`.`post_delivery`,`items_users`.`lbtl_`,`items_users`.`bexp`,`items_users`.`so`,`items_users`.`blvl`,`items_main`.`id`,`items_main`.`name`,`items_main`.`img`,`items_main`.`type`,`items_main`.`inslot`,`items_main`.`2h`,`items_main`.`2too`,`items_main`.`iznosMAXi`,`items_main`.`inRazdel`,`items_main`.`price1`,`items_main`.`price2`,`items_main`.`magic_chance`,`items_main`.`info`,`items_main`.`massa`,`items_main`.`level`,`items_main`.`magic_inci`,`items_main`.`overTypei`,`items_main`.`group`,`items_main`.`group_max`,`items_main`.`geni`,`items_main`.`ts`,`items_main`.`srok`,`items_main`.`class`,`items_main`.`class_point`,`items_main`.`anti_class`,`items_main`.`anti_class_point`,`items_main`.`max_text`,`items_main`.`useInBattle`,`items_main`.`lbtl`,`items_main`.`lvl_itm`,`items_main`.`lvl_exp`,`items_main`.`lvl_aexp` FROM `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`) WHERE (( `items_users`.time_create + `items_main`.srok) > unix_timestamp()  OR `items_main`.srok = "0") AND `items_users`.`delete`="0" AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` = "'.mysql_real_escape_string($typeOtdel).'" GROUP BY `items_users`.`item_id` ORDER BY `items_main`.`id` DESC');
			}
		} else {
			if($preview == "full"){
	 	 	 	$cl = mysql_query('SELECT 
`items_users`.`id` as id, `items_users`.`id` AS `idu`,`items_users`.`item_id` as item_id,`items_users`.`1price`,`items_users`.`2price`,`items_users`.`uid`,`items_users`.`use_text`,
`items_users`.`data`,`items_users`.`inOdet`,`items_users`.`inShop`,`items_users`.`delete`,`items_users`.`iznosNOW`,`items_users`.`iznosMAX`,
`items_users`.`gift`,`items_users`.`gtxt1`,`items_users`.`gtxt2`,`items_users`.`kolvo`,`items_users`.`geniration`,`items_users`.`magic_inc`,`items_users`.`maidin`,
`items_users`.`lastUPD`,`items_users`.`timeOver`,`items_users`.`overType`,`items_users`.`secret_id`,`items_users`.`time_create`,`items_users`.`inGroup`,`items_users`.`dn_delete`,
`items_users`.`inTransfer`,`items_users`.`post_delivery`,`items_users`.`lbtl_`,`items_users`.`bexp`,`items_users`.`so`,`items_users`.`blvl`,`items_main`.`id` as item_id,`items_main`.`name`,
`items_main`.`img`,`items_main`.`type`,`items_main`.`inslot`,`items_main`.`2h`,`items_main`.`2too`,`items_main`.`iznosMAXi`,`items_main`.`inRazdel`,`items_main`.`price1`,
`items_main`.`price2`,`items_main`.`magic_chance`,`items_main`.`info`,`items_main`.`massa`,`items_main`.`level`,`items_main`.`magic_inci`,`items_main`.`overTypei`,
`items_main`.`group`,`items_main`.`group_max`,`items_main`.`geni`,`items_main`.`ts`,`items_main`.`srok`,`items_main`.`class`,`items_main`.`class_point`,`items_main`.`anti_class`,
`items_main`.`anti_class_point`,`items_main`.`max_text`,`items_main`.`useInBattle`,`items_main`.`lbtl`,`items_main`.`lvl_itm`,`items_main`.`lvl_exp`,
`items_main`.`lvl_aexp` , count(`items_users`.`id`) as inGroupCount
FROM `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`) 
WHERE (( `items_users`.time_create + `items_main`.srok) > unix_timestamp()  OR `items_main`.srok = "0") AND `items_users`.`delete`="0"  AND `items_users`.`item_id` = "'.(INT)$_GET['itemid'].'"
AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` != "18" AND `items_main`.`type` != "19" AND `items_main`.`type` != "20" AND `items_main`.`type` != "21" AND `items_main`.`type` != "22" AND `items_main`.`type` != "15" AND `items_main`.`type` != "12" AND `items_main`.`type` != "4" AND `items_main`.`type` != "5" AND `items_main`.`type` != "6" AND `items_main`.`type` != "1" AND `items_main`.`type` != "3" AND `items_main`.`type` != "8" AND `items_main`.`type` != "14" AND `items_main`.`type` != "13" AND `items_main`.`type` != "9" AND `items_main`.`type` != "10" AND `items_main`.`type` != "11" AND `items_main`.`type` != "29" AND `items_main`.`type` != "30" 
GROUP BY `items_users`.`inGroup`, `items_users`.`uid`,`items_users`.`1price` HAVING `items_users`.inGroup > 0
UNION ALL 
SELECT `items_users`.`id` as id, `items_users`.`id` AS `idu`,`items_users`.`item_id` as item_id,`items_users`.`1price`,`items_users`.`2price`,`items_users`.`uid`,`items_users`.`use_text`,
`items_users`.`data`,`items_users`.`inOdet`,`items_users`.`inShop`,`items_users`.`delete`,`items_users`.`iznosNOW`,`items_users`.`iznosMAX`,
`items_users`.`gift`,`items_users`.`gtxt1`,`items_users`.`gtxt2`,`items_users`.`kolvo`,`items_users`.`geniration`,`items_users`.`magic_inc`,`items_users`.`maidin`,
`items_users`.`lastUPD`,`items_users`.`timeOver`,`items_users`.`overType`,`items_users`.`secret_id`,`items_users`.`time_create`,`items_users`.`inGroup`,`items_users`.`dn_delete`,
`items_users`.`inTransfer`,`items_users`.`post_delivery`,`items_users`.`lbtl_`,`items_users`.`bexp`,`items_users`.`so`,`items_users`.`blvl`,`items_main`.`id` as item_id,`items_main`.`name`,
`items_main`.`img`,`items_main`.`type`,`items_main`.`inslot`,`items_main`.`2h`,`items_main`.`2too`,`items_main`.`iznosMAXi`,`items_main`.`inRazdel`,`items_main`.`price1`,
`items_main`.`price2`,`items_main`.`magic_chance`,`items_main`.`info`,`items_main`.`massa`,`items_main`.`level`,`items_main`.`magic_inci`,`items_main`.`overTypei`,
`items_main`.`group`,`items_main`.`group_max`,`items_main`.`geni`,`items_main`.`ts`,`items_main`.`srok`,`items_main`.`class`,`items_main`.`class_point`,`items_main`.`anti_class`,
`items_main`.`anti_class_point`,`items_main`.`max_text`,`items_main`.`useInBattle`,`items_main`.`lbtl`,`items_main`.`lvl_itm`,`items_main`.`lvl_exp`,
`items_main`.`lvl_aexp`, count(`items_users`.`id`) as inGroupCount
FROM  `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`) 
WHERE (( `items_users`.time_create + `items_main`.srok) > unix_timestamp()  OR `items_main`.srok = "0") AND `items_users`.`delete`="0"  AND `items_users`.`item_id` = "'.(INT)$_GET['itemid'].'"
AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` != "18" AND `items_main`.`type` != "19" AND `items_main`.`type` != "20" AND `items_main`.`type` != "21" AND `items_main`.`type` != "22" AND `items_main`.`type` != "15" AND `items_main`.`type` != "12" AND `items_main`.`type` != "4" AND `items_main`.`type` != "5" AND `items_main`.`type` != "6" AND `items_main`.`type` != "1" AND `items_main`.`type` != "3" AND `items_main`.`type` != "8" AND `items_main`.`type` != "14" AND `items_main`.`type` != "13" AND `items_main`.`type` != "9" AND `items_main`.`type` != "10" AND `items_main`.`type` != "11" AND `items_main`.`type` != "29" AND `items_main`.`type` != "30" 
GROUP BY `items_users`.`uid`,`items_users`.`1price` HAVING `items_users`.inGroup = 0
ORDER BY `1price`ASC , inGroupCount DESC');	 	 	 	 	 	 	 	 	 
	 	 	} else {
				$cl = mysql_query('SELECT `items_users`.`id`, `items_users`.`id` AS `idu`,`items_users`.`item_id`,`items_users`.`1price`,`items_users`.`2price`,`items_users`.`uid`,`items_users`.`use_text`,`items_users`.`data`,`items_users`.`inOdet`,`items_users`.`inShop`,`items_users`.`delete`,`items_users`.`iznosNOW`,`items_users`.`iznosMAX`,`items_users`.`gift`,`items_users`.`gtxt1`,`items_users`.`gtxt2`,`items_users`.`kolvo`,`items_users`.`geniration`,`items_users`.`magic_inc`,`items_users`.`maidin`,`items_users`.`lastUPD`,`items_users`.`timeOver`,`items_users`.`overType`,`items_users`.`secret_id`,`items_users`.`time_create`,`items_users`.`inGroup`,`items_users`.`dn_delete`,`items_users`.`inTransfer`,`items_users`.`post_delivery`,`items_users`.`lbtl_`,`items_users`.`bexp`,`items_users`.`so`,`items_users`.`blvl`,`items_main`.`id`,`items_main`.`name`,`items_main`.`img`,`items_main`.`type`,`items_main`.`inslot`,`items_main`.`2h`,`items_main`.`2too`,`items_main`.`iznosMAXi`,`items_main`.`inRazdel`,`items_main`.`price1`,`items_main`.`price2`,`items_main`.`magic_chance`,`items_main`.`info`,`items_main`.`massa`,`items_main`.`level`,`items_main`.`magic_inci`,`items_main`.`overTypei`,`items_main`.`group`,`items_main`.`group_max`,`items_main`.`geni`,`items_main`.`ts`,`items_main`.`srok`,`items_main`.`class`,`items_main`.`class_point`,`items_main`.`anti_class`,`items_main`.`anti_class_point`,`items_main`.`max_text`,`items_main`.`useInBattle`,`items_main`.`lbtl`,`items_main`.`lvl_itm`,`items_main`.`lvl_exp`,`items_main`.`lvl_aexp` FROM `items_users` LEFT JOIN `items_main` ON (`items_main`.`id` = `items_users`.`item_id`) WHERE (( `items_users`.time_create + `items_main`.srok) > unix_timestamp()  OR `items_main`.srok = "0") AND `items_users`.`delete`="0" AND `items_users`.`inOdet`="0" AND `items_users`.`inShop`="30" and `items_main`.`type` != "18" AND `items_main`.`type` != "19" AND `items_main`.`type` != "20" AND `items_main`.`type` != "21" AND `items_main`.`type` != "22" AND `items_main`.`type` != "15" AND `items_main`.`type` != "12" AND `items_main`.`type` != "4" AND `items_main`.`type` != "5" AND `items_main`.`type` != "6" AND `items_main`.`type` != "1" AND `items_main`.`type` != "3" AND `items_main`.`type` != "8" AND `items_main`.`type` != "14" AND `items_main`.`type` != "13" AND `items_main`.`type` != "9" AND `items_main`.`type` != "10" AND `items_main`.`type` != "11" AND `items_main`.`type` != "29" AND `items_main`.`type` != "30" GROUP BY `items_users`.`item_id` ORDER BY `items_main`.`id` DESC');
			}
		}
		$cr = 'c8c8c8';
		$i = 0;
	 	$steckCikl = 1;
		while($pl = mysql_fetch_array($cl)){
			// количетсво одинаковых предметов в комке
	 	 	if ( $preview == "preview" ) {
		 	 	//$steck = mysql_fetch_array(mysql_query('SELECT COUNT(`item_id`) FROM `items_users` WHERE `item_id` = "'.$pl['item_id'].'" AND `inShop` = 30 LIMIT 1'));
				$steck = array('--' , '--' , '--' , '--');
			}
			if($cr=='d4d4d4'){ $cr = 'c8c8c8'; } else { $cr = 'd4d4d4'; }
			if( $preview == "preview" && ($steck[0]>1 && $steck[0]>$steckCikl )){
				++$steckCikl;
				continue;
			} else {
				$steckCikl = 1;
				$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$pl['item_id'].'" LIMIT 1'));
				if($steck[0]>1 && $preview == "preview") {
					$po = $this->lookStats($d['data']);
				} else {
					$po = $this->lookStats($pl['data']);
				}
				$pozonb = 0;
				if(($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27) {
					//Зоны блока +
					if(!isset($po['zonb'])) { $po['zonb'] = 0; }
					$pozonb = $po['zonb']+1;
				}
				$is2 = '';
				$is1 = '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'"><br>';
				if ($preview == "full"){
					$is1 .= '<a href="?otdel='.((int)$_GET['otdel']).'&toRent=3&itemid='.(INT)$_GET['itemid'].'&buy='.$pl[0].'&sd4='.$this->info['nextAct'].'&rnd='.$code.' " >купить</a> ';	 	 	 	 	 	 	 	 
				} elseif($preview=="preview") {
					$is1 .= '<a href="?otdel='.((int)$_GET['otdel']).'&toRent=3&itemid='.$pl['item_id'].' " >Просмотреть</a> ';
				}
				//название
				
				$col = $this->itemsX($pl[0]); 
				if($col>1 && $pl['inGroup']!=0 && $pl['inGroupCount']>1) { 
					$pl['kolvo'] = $col;
					$pl['name'] .= ' (x'.$col.')';
				}
				$is2 .= '<a href="http://xcombats.com/item/'.$pl['item_id'].'" target="_blank">'.$pl['name'].'</a> &nbsp; &nbsp;';
				if($pl['massa']>0 && $preview == "full") {
					$is2 .= '(Масса: '.round($pl['massa'],2).')';
					if($pl['gift']!=''){
						$ttl = '';
						if($pl['gift']==1){
							$ttl = 'Вы не можете передать этот предмет кому-либо';
						}else{
							$ttl = 'Этот предмет подарил '.$pl['gift'].'. Вы не сможете передать этот предмет кому-либо еще';
						}
						$is2 .= ' <img title="'.$ttl.'" src="http://img.xcombats.com/i/podarok.gif">';
					}
					if(isset($po['art'])){
						$is2 .= ' <img title="Артефакт" src="http://img.xcombats.com/i/artefact.gif">';
					}
					if(isset($po['sudba'])){
						if($po['sudba']=='0'){
							$is2 .= ' <img title="Этот предмет будет связан общей судьбой с первым, кто наденет его. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif">';
						}elseif($po['sudba']=='1'){
							$is2 .= ' <img title="Этот предмет будет связан общей судьбой с первым, кто возьмет предмет. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif">';
						}else{
							$is2 .= ' <img title="Этот предмет связан общей судьбой с '.$po['sudba'].'. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/desteny.gif">';
						}
					}
				}
				//цена
				$is2 .= '<br><b>Цена: <img src=/coin1.png height=11 >&nbsp;';	 	 	 	 	 	 	 
				if($steck[0]>1 && $preview == "preview") {
						$is2 .= $steck[3].'-'.$steck[4].' кр.</b> ';
				} else {
					$is2 .= $pl['1price'].' кр.</b> ';
				}
				if($pl['pricerep']>0){
					$is2 .= ' <small><b>('.round($pl['pricerep'],2).' Воинственности)</b></small>';
				}

				//долговечность
				if($pl['iznosMAX']>0){
					$izcol = '';
					if(floor($pl['iznosNOW'])>=(floor($pl['iznosMAX'])-ceil($pl['iznosMAX'])/100*20)){
						$izcol = 'brown';
					}
				} 
				if ($preview == "preview") {
					$is2 .= '<br>Долговечность: <font color="'.$izcol.'">'.floor($steck[1]).'/'.ceil($steck[2]).'</font>';
				} else {
					if($pl['iznosMAXi'] == 999999999) {
						$is2 .= '<br>Долговечность: <font color="brown">неразрушимо</font>';
					} else {
						$is2 .= '<br>Долговечность: <font color="'.$izcol.'">'.floor($pl['iznosNOW']).'/'.ceil($pl['iznosMAX']).'</font>';
					}
				}
				//Срок годности предмета
				
				if($po['srok'] > 0){
					$pl['srok'] = $po['srok'];
				}
				if( $pl['srok'] > 0 AND $preview!="preview" ) {
					if( $pl['time_create']+$pl['srok'] < time() ){
						$is2 .= '<br>Срок годности: '.$this->timeOut($pl['srok']).' (испорчен)';
					} else {
						$is2 .= '<br>Срок годности: '.$this->timeOut($pl['srok']).' (до '.date('d.m.Y H:i',$pl['time_create']+$pl['srok']).')';
					}
				}elseif( $pl['srok'] > 0 ){
					$is2 .= '<br>Срок годности: '.$this->timeOut($pl['srok']);
				}
				if($pl['magic_chance'] > 0) {
					$is2 .= '<br>Вероятность срабатывания: '.min(array($pl['magic_chance'],100)).'%';
				}

				//Продолжительность действия магии:
				if((int)$pl['magic_inci'] > 0){
					$efi = mysql_fetch_array(mysql_query('SELECT `id2`,`mname`,`type1`,`img`,`mdata`,`actionTime`,`type2`,`type3`,`onlyOne`,`oneType`,`noAce`,`see`,`info`,`overch`,`bp`,`noch` FROM `eff_main` WHERE `id2` = "'.((int)$pl['magic_inci']).'" LIMIT 1'));
					if( isset($efi['id2']) && $efi['actionTime'] > 0 ){
						$is2 .= '<br>Продолжительность действия: '.$this->timeOut($efi['actionTime']);
					}
				}
				if ($preview == "full"){
					if( !isset($po['notr']) ) {
						//<b>Требуется минимальное:</b>
						$tr = '';
						$t = $this->items['tr'];
						$x = 0;
						while($x<count($t)){
							$n = $t[$x];
							if(isset($po['tr_'.$n]) && $po['tr_'.$n] != 0){
								if($po['tr_'.$n] > $this->stats[$n]){
									if( $n == 'rep' ) {
										$temp = explode('::',$po['tr_'.$n]);
										if( $this->rep['rep'.$temp[1]] < $temp[0] ) { $tr .= '<font color="red">'; $notr++; }
										unset($temp);
									} elseif( $n != 'align' || floor($this->info['align']) != $po['tr_'.$n] ) {
										$tr .= '<font color="red">'; $notr++;
									}
								}
								$tr .= '<br>• ';
								if( $n == 'rep' ) {
									$temp = explode('::',$po['tr_'.$n]);
									$tr .= $this->is[$n].' '.ucfirst(str_replace('city',' city',$temp[1])).': '.$temp[0];
									unset($temp);
								}elseif( $n != 'align' ) {
									if( $n == 'sex' ) {
										if( $po['tr_'.$n] == 1 ) {
											$tr .= $this->is[$n].': Женский';
										}else{
											$tr .= $this->is[$n].': Мужской';
										}
									}else{
										$tr .= $this->is[$n].': '.$po['tr_'.$n];
									}
								}else{
									$tr .= $this->is[$n].': '.$this->align_nm[$po['tr_'.$n]];
								}
								if($po['tr_'.$n] > $this->stats[$n]){
									if( $n != 'align' || floor($this->info['align']) != $po['tr_'.$n] ) {
										$tr .= '</font>';
									}
								}
							}
							$x++;
						}
	
						if($tr!=''){
							$is2 .= '<br><b>Требуется минимальное:</b>'.$tr;
						}
					}
					//<b>Действует на:</b>
					$tr = ''; $t = $this->items['add'];
					$x = 0;
					while($x<count($t)){
						$n = $t[$x];
						if(isset($po['add_'.$n],$this->is[$n])){
							$z = '+';
							if($po['add_'.$n]<0){
								$z = '';
							}
							$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['add_'.$n];
						}
						$x++;
					}
					//действует на (броня)
					$i = 1; $bn = array(1=>'головы',2=>'корпуса',3=>'пояса',4=>'ног');
					while($i<=4){
						if(isset($po['add_mab'.$i])){
							if($po['add_mab'.$i]==$po['add_mib'.$i] && $pl['geniration']==1){
								$z = '+';
								if($po['add_mab'.$i]<0){
									$z = '';
								}
								$tr .= '<br>• Броня '.$bn[$i].': '.$z.''.$po['add_mab'.$i];
							}else{
								$tr .= '<br>• Броня '.$bn[$i].': '.$po['add_mib'.$i].'-'.$po['add_mab'.$i].' ('.$this->bronFx(array($po['add_mib'.$i],$po['add_mab'.$i])).')';
							}
						}
						$i++;
					}
					if($tr!=''){
						$is2 .= '<br><b>Действует на:</b>'.$tr;
					}
					//<b>Свойства предмета:</b>
					$tr = ''; $t = $this->items['sv'];
					if(isset($po['sv_yron_min'],$po['sv_yron_max'])){
						$tr .= '<br>• Урон: '.$po['sv_yron_min'].' - '.$po['sv_yron_max'];
					}
					$x = 0;
					while($x<count($t)){
						$n = $t[$x];
						if(isset($po['sv_'.$n])){
							$z = '+';
							if($po['sv_'.$n]<0){
								$z = '';
							}
							$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['sv_'.$n];
						}
						$x++;
					}
					if($pl['2too']==1){
						$tr .= '<br>• Второе оружие';
					}
					if($pl['2h']==1){
						$tr .= '<br>• Двуручное оружие';
					}
					if(isset($po['zonb'])){
						$tr .= '<br>• Зоны блокирования: ';
						if($pozonb>0){
							$x = 1;
							while($x<=$pozonb){
								$tr .= '+';
								$x++;
							}
						}else{
							$tr .= '—';
						}
					}

					if($tr!=''){
						$is2 .= '<br><b>Свойства предмета:</b>'.$tr;
					}

					//Особенности
					$tr = '';
					$x = 1;
					while($x<=4){
						if($po['tya'.$x]>0){
							$tyc = 'Ничтожно редки';
							if($po['tya'.$x]>6){
								$tyc = 'Редки';
							}
							if($po['tya'.$x]>14){
								$tyc = 'Малы';
							}
							if($po['tya'.$x]>34){
								$tyc = 'Временами';
							}
							if($po['tya'.$x]>79){
								$tyc = 'Регулярны';
							}
							if($po['tya'.$x]>89){
								$tyc = 'Часты';
							}
							if($po['tya'.$x]>=100){
								$tyc = 'Всегда';
							}
							$tr .= '<br>• '.$this->is['tya'.$x].': '.$tyc.' ('.$po['tya'.$x].'%)';
						}
						$x++;
					}
					$x = 1;
					while($x<=7){
						if($po['tym'.$x]>0){
							$tyc = 'Ничтожно редки';
							if($po['tym'.$x]>6){
								$tyc = 'Редки';
							}
							if($po['tym'.$x]>14){
								$tyc = 'Малы';
							}
							if($po['tym'.$x]>34){
								$tyc = 'Временами';
							}
							if($po['tym'.$x]>79){
								$tyc = 'Регулярны';
							}
							if($po['tym'.$x]>89){
								$tyc = 'Часты';
							}
							if($po['tym'.$x]>=100){
								$tyc = 'Всегда';
							}
							$tr .= '<br>• '.$this->is['tym'.$x].': '.$tyc.' ('.$po['tym'.$x].'%)';
						}
						$x++;
					}
					
					if($tr!=''){
						$is2 .= '<br><b>Особенности:</b>'.$tr;
					}
					
				$tr = '';
					
				if(isset($po['imposed']) && $po['imposed']>0) {
					if($po['imposed_lvl'] == 0) {
						$rnc = 'maroon';
					}elseif($po['imposed_lvl'] == 1) {
						$rnc = '#624542';
					}elseif($po['imposed_lvl'] == 2) {
						$rnc = '#77090b';
					}elseif($po['imposed_lvl'] == 3) {
						$rnc = '#d99800';
					}else{
						$rnc = '#282828';
					}
					$po['imposed_name'] = str_replace('Чары ','',$po['imposed_name']); 
					$tr .= '<br>&bull; <font color='.$rnc.'>Наложены заклятия:</font> '.$po['imposed_name'].' '; 
					unset($rnc);
				} 
				if($tr!='') {
					$is2 .= '<br><b>Улучшения предмета:</b>';
					$is2 .= $tr;
				}
					
					if($notr==0){
						$d[0] = 1;
						if($pl['magic_inc']!=''){
							$d[2] = 1;
						}
					}
					if(isset($po['free_stats']) && $po['free_stats']>0){
						$is2 .= '<br><b>Распределение статов:</b>';
						$is2 .= '&bull; Возможных распределений: '.$po['free_stats'].'';
					}
					if(floor($pl['iznosNOW'])>=ceil($pl['iznosMAX'])){
						$d[0] = 0;
						$d[2] = 0;
					}
					if(isset($po['complect']) || isset($po['complect2'])){
						$is2 .= '<br><i>Дополнительная информация:</i>';
					}
					if(isset($po['complect'])){
						//не отображается
						$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
						$spc = mysql_query('SELECT `id`,`com`,`name`,`x`,`data` FROM `complects` WHERE `com` = "'.$po['complect'].'" ORDER BY `x` ASC LIMIT 20');
						while($plc = mysql_fetch_array($spc)){
							$com1['name'] = $plc['name'];
							$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$plc['x'].'</font>: ';
							//действие комплекта
							$i1c = 0; $i2c = 0;
							$i1e = $this->lookStats($plc['data']);
							while($i1c<count($this->items['add'])){
								if(isset($i1e[$this->items['add'][$i1c]])){
									$i3c = $i1e[$this->items['add'][$i1c]];
									if($i3c>0){
										$i3c = '+'.$i3c;
									}
									if($i2c>0){
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
					if(isset($po['complect2'])){
						//не отображается
						$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
						$spc = mysql_query('SELECT `id`,`com`,`name`,`x`,`data` FROM `complects` WHERE `com` = "'.$po['complect2'].'" ORDER BY `x` ASC LIMIT 20');
						while($plc = mysql_fetch_array($spc)){
							$com1['name'] = $plc['name'];
							$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$plc['x'].'</font>: ';
							//действие комплекта
							$i1c = 0; $i2c = 0;
							$i1e = $this->lookStats($plc['data']);
							while($i1c<count($this->items['add'])){
								if(isset($i1e[$this->items['add'][$i1c]])){
									$i3c = $i1e[$this->items['add'][$i1c]];
									if($i3c>0){
										$i3c = '+'.$i3c;
									}
									if($i2c>0){
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
						$is2 .= '<br>&bull; Часть комплекта (подгонка): <b>'.$com1['name'].'</b><br><small>';
						$is2 .= $com1['text'];
						$is2 .= '</small>';
					}

					$is2 .= '<small style="">';
					if(isset($po['gravi'])) {
						$is2 .= '<div><img title="'.$this->city_name[$po['gravic']].'" style="vertical-align:bottom" width=34 height=19 src=http://img.xcombats.com/i/city_ico2/'.$po['gravic'].'.gif> На поверхности выгравирована надпись:</div><div align=left style=color:#575757>&nbsp; &nbsp;'.$po['gravi'].'</div>';
					}
					if($pl['info']!=''){
						$is2 .= '<div><b>Описание:</b></div><div>'.$pl['info'].'</div>';
					}
					if($po['info']!=''){
						$is2 .= '<div>'.$po['info'].'</div>';	 	 	 	 	 	 	 	 	 
					}
					if($pl['max_text']-$pl['use_text'] > 0) {
						$is2 .= '<div>Количество символов: '.($pl['max_text']-$pl['use_text']).'</div>';
					}
					if($pl['maidin']!=''){
						$is2 .= '<div>Сделано в '.$this->city_name[$pl['maidin']].'</div>';
					}
					if(isset($po['noremont'])){
						$is2 .= '<div style="color:brown;">Предмет не подлежит ремонту</div>';
					}
					if(isset($po['nosale'])){
						$is2 .= '<div style="color:brown;">Предмет нельзя продать</div>';
					}
					if(isset($po['nomodif'])){
						$is2 .= '<div style="color:brown;">Предмет нельзя улучшать</div>';
					}
					if(isset($po['nodelete'])){
						$is2 .= '<div style="color:brown;">Предмет нельзя выбросить</div>';
					}
					if(isset($po['frompisher']) && $po['frompisher']>0){
						$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
					}
					if(isset($po['sleep_moroz']) && $po['sleep_moroz'] > 0 ) {
						$is2 .= '<div style="color:brown;">Предмет не портится во время сна</div>';
					}
					if(isset($po['fromlaba']) && $po['fromlaba']>0){
						$is2 .= '<div style="color:brown;">Предмет из лабиринта</div>';
					}
					if(isset($po['vip_sale']) && $po['vip_sale']>0) {
						$is2 .= '<div style="color:brown;">Предмет куплен за 10% от стоимости</div>';
					}
					if($pl['dn_delete']>0){
						$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
					}
					if( $this->pokol > $pl['geni'] ) {
						$is2 .= '<div style="color:brown">Предмет устарел</div>';
					}
					if(isset($po['zazuby']) && $po['zazuby']>0){
						$is2 .= '<div style="color:brown;">Предмет куплен за зубы</div>';
					}
					//$is2 .= '<div>Сделано в '.$this->city_name[$this->info['city']].'</div>';
					$is2 .= '</small>';
				}
				if ($preview == "preview"){
					$kolvoprint = "<small style=\"float:right; color:grey;\" align=\"right\">Количество: <b>$steck[0]</b> шт.</small>";
				}
				echo '<tr style="background-color:#'.$cr.';"><td width="100" style="padding:7px;" valign="middle" align="center">'.$is1.'</td><td style="padding:7px;" valign="top">'.$kolvoprint.$is2.'</td></tr>';
				$i++;
			}
	 	}
		if($i==0) echo '<tr style="background-color:#'.$cr.';"><td style="padding:7px;" align="center" valign="top">Прилавок магазина пуст</td></tr>';
	}

	public $sid_zuby = array(
	//Кэпитал
		1 => 1, //гос
		9 => 1, //таверна
		8 => 1 //зоо
	);

	public function shopItems($sid,$plu = '')
	{
		global $c,$code,$sid;
		
		$sid_zuby = 0;
		
		if(isset($this->sid_zuby[$sid])) {
			$sid_zuby = 1;
		}
		
		if( $this->info['admin'] > 0 ) {
			$ishp = mysql_fetch_array(mysql_query('SELECT * FROM `items_shop` WHERE `sid` = "'.mysql_real_escape_string($sid).'" AND `r` = "'.mysql_real_escape_string($_GET['otdel']).'" AND `item_id` = "'.mysql_real_escape_string($_GET['itmid']).'" AND `kolvo` > 0 LIMIT 1'));
			if( isset($_GET['itmup']) ) {
				//mysql_query('UPDATE `items_shop` SET `pos` = "'.($ishp['pos']+1).'" WHERE `sid` = "'.mysql_real_escape_string($sid).'" AND `r` = "'.mysql_real_escape_string($_GET['otdel']).'" AND `pos` = "'.($ishp['pos']-1).'" LIMIT 1');
				mysql_query('UPDATE `items_shop` SET `pos` = "'.($ishp['pos']-1).'" WHERE `sid` = "'.mysql_real_escape_string($sid).'" AND `r` = "'.mysql_real_escape_string($_GET['otdel']).'" AND `item_id` = "'.mysql_real_escape_string($_GET['itmid']).'"  AND `kolvo` > 0 LIMIT 1');
			}elseif( isset($_GET['itmdown']) ) {
				//mysql_query('UPDATE `items_shop` SET `pos` = "'.($ishp['pos']-1).'" WHERE `sid` = "'.mysql_real_escape_string($sid).'" AND `r` = "'.mysql_real_escape_string($_GET['otdel']).'" AND `pos` = "'.($ishp['pos']+1).'" LIMIT 1');
				mysql_query('UPDATE `items_shop` SET `pos` = "'.($ishp['pos']+1).'" WHERE `sid` = "'.mysql_real_escape_string($sid).'" AND `r` = "'.mysql_real_escape_string($_GET['otdel']).'" AND `item_id` = "'.mysql_real_escape_string($_GET['itmid']).'"  AND `kolvo` > 0 LIMIT 1');
			}elseif( isset($_GET['itmid']) ) {
				//mysql_query('UPDATE `items_shop` SET `pos` = "'.mysql_real_escape_string($_GET['itmpos']).'" WHERE `sid` = "'.mysql_real_escape_string($sid).'" AND `r` = "'.mysql_real_escape_string($_GET['otdel']).'" AND `item_id` = "'.mysql_real_escape_string($_GET['itmid']).'" LIMIT 1');
			}
		}
		
		
		
		/*
		$cl = mysql_query('SELECT
		`ish`.`price_4`,`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`ish`.`iid`,`ish`.`item_id`,`ish`.`data2`,`ish`.`iznos`,`ish`.`pos`,`ish`.`cantBuy`,`ish`.`kolvo`,`ish`.`geniration`,`ish`.`magic_inc`,`ish`.`timeOver`,`ish`.`overType`,`ish`.`secret_id`,`ish`.`sid`,`ish`.`r`,`ish`.`price_1`,`ish`.`price_2`,`ish`.`price_3`,`ish`.`level`,`ish`.`tr_items`,`ish`.`max_buy`,`ish`.`real`,`ish`.`nozuby`
		FROM `items_shop` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`sid` = "'.mysql_real_escape_string($sid).'" AND `ish`.`r` = "'.mysql_real_escape_string($_GET['otdel']).'" ORDER BY `ish`.`level`,`ish`.`price_1` ASC');
		*/
		
		
		
		$cl = mysql_query('SELECT
		`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`ish`.`iid`,`ish`.`item_id`,`ish`.`data2`,`ish`.`iznos`,`ish`.`pos`,`ish`.`cantBuy`,`ish`.`kolvo`,`ish`.`geniration`,`ish`.`magic_inc`,`ish`.`timeOver`,`ish`.`overType`,`ish`.`secret_id`,`ish`.`sid`,`ish`.`r`,`ish`.`price_1`,`ish`.`price_2`,`ish`.`price_3`,`ish`.`level`,`ish`.`tr_items`,`ish`.`max_buy`,`ish`.`real`,`ish`.`nozuby`
		FROM `items_shop` AS `ish` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `ish`.`item_id`) WHERE `ish`.`sid` = "'.mysql_real_escape_string($sid).'" AND `ish`.`r` = "'.mysql_real_escape_string($_GET['otdel']).'" ORDER BY `ish`.`pos` ASC');
		
		
		$cr = 'c8c8c8';
		$i = 0;
		$jid = 0;
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
			
			if($pl['kolvo']>0)
			{
				$jid++;				
				//if( $pl['pos'] != $jid ) {
				//	$pl['pos'] = $jid;
				//	mysql_query('UPDATE `items_shop` SET `pos` = "'.($jid).'" WHERE `sid` = "'.mysql_real_escape_string($sid).'" AND `r` = "'.mysql_real_escape_string($_GET['otdel']).'" AND `item_id` = "'.$pl['id'].'" AND `kolvo` > 0 LIMIT 1');
				//}
				if($cr=='d4d4d4')
				{
					$cr = 'c8c8c8';
				}else{
					$cr = 'd4d4d4';
				}
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
				$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$pl['id'].'" LIMIT 1'));
				$po = $this->lookStats($d['data']);
				
				if( $sid == 609 ) {
					$po['icos'] = 'WL';
				}
				
				/*
				if($pl['level']==0 && $po['tr_lvl']>0)
				{
					mysql_query('UPDATE `items_shop` SET `level` = "'.$po['tr_lvl'].'" WHERE `iid` = "'.$pl['iid'].'" LIMIT 1');
					mysql_query('UPDATE `items_main` SET `level` = "'.$po['tr_lvl'].'" WHERE `iid` = "'.$pl['id'].'" LIMIT 1');
				}*/
				$pozonb = 0;
				if(($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27)
				{
					//Зоны блока +
					if(!isset($po['zonb'])) { $po['zonb'] = 0; }
					$pozonb = $po['zonb']+1;
				}	
				
				$is2 = '';
				if($pl['type'] == 71) {
					$is1 = '<img width="80" src="http://img.xcombats.com/i/items/'.$pl['img'].'"><br>';
				}else{
					$is1 = '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'"><br>';
				}
				if( $sid == 609 ) {
					$is1 .= '<span id="shopPlus'.$pl['id'].'"></span><a href="javascript:void('.$pl['id'].');" onClick="top.buyShopNow('.$pl['id'].',\'?'.$plu.'otdel='.((int)$_GET['otdel']).'&buy='.$pl['id'].'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\',\''.$pl['name'].'\',\'??\',\' ??.\');">купить</a>';
				}else{
					//if($this->info['money']>$pl['price'])
					//{
					if($sid==12) {
						$is1 .= '<span id="shopPlus'.$pl['id'].'"></span><a href="javascript:void('.$pl['id'].');" onClick="top.buyShopNow('.$pl['id'].',\'?'.$plu.'otdel='.((int)$_GET['otdel']).'&buy='.$pl['id'].'&repshop&sd4='.$this->info['nextAct'].'&rnd='.$code.'\',\''.$pl['name'].'\',\''.$pl['price_1'].'\',\' Репутации сдачи ресурсов\');">купить</a> <a href="javascript:void(0);" onClick="top.payPlus('.$pl['id'].');"><img style="width:11px; height:11px;" src="http://img.xcombats.com/i/up.gif" title="Купить несколько предметов"></a>';
					}elseif($sid==2 || $sid==777) {
						$is1 .= '<span id="shopPlus'.$pl['id'].'"></span><a href="javascript:void('.$pl['id'].');" onClick="top.buyShopNow('.$pl['id'].',\'?'.$plu.'otdel='.((int)$_GET['otdel']).'&buy='.$pl['id'].'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\',\''.$pl['name'].'\',\''.$pl['price_2'].'\',\' екр.\');">купить</a> <a href="javascript:void(0);" onClick="top.payPlus('.$pl['id'].');"><img style="width:11px; height:11px;" src="http://img.xcombats.com/i/up.gif" title="Купить несколько предметов"></a>';
					}else{
						$is1 .= '<span id="shopPlus'.$pl['id'].'"></span><a href="javascript:void('.$pl['id'].');" onClick="top.buyShopNow('.$pl['id'].',\'?'.$plu.'otdel='.((int)$_GET['otdel']).'&buy='.$pl['id'].'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\',\''.$pl['name'].'\',\''.$pl['price_1'].'\',\' кр.\');">купить</a> <a href="javascript:void(0);" onClick="top.payPlus('.$pl['id'].');"><img style="width:11px; height:11px;" src="http://img.xcombats.com/i/up.gif" title="Купить несколько предметов"></a>';
					}
					//}
					if( $sid == 777 && $this->stats['silver'] > 1 ) {
						//$is1 .= '<br><a onClick="if(confirm(\'Совершить VIP-покупку?\')){ location.href = \'main.php?'.$plu.'otdel='.((int)$_GET['otdel']).'&buy_vip='.$pl['id'].'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\'; }" href="javascript:void('.$pl['id'].');">VIP покупка ('.round($pl['price_2']/20,2).' екр.)</a> ';
					}
					if($pl['nozuby'] == 0) {
						if($this->info['level'] > 0 && $this->info['level'] < 8) {
							if($pl['level'] < 8 && $sid_zuby == 1 && $pl['nozuby'] == 0 && $c['zuby'] == true) {
								$is1 .= '<br><a href="javascript:void('.$pl['id'].');" onClick="top.buyShopNow('.$pl['id'].',\'?'.$plu.'otdel='.((int)$_GET['otdel']).'&zuby=1&buy='.$pl['id'].'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\',\''.$pl['name'].'\',\''.$pl['price_1'].'\',\' (Зубы)\');">купить за зубы</a>';
								/*if( $this->info['admin'] > 0 ) {
									if( isset($_GET['nozbpl']) && $pl['iid'] == $_GET['nozbpl'] ) {
										mysql_query('UPDATE `items_shop` SET `nozuby` = 1 WHERE `iid` = "'.$pl['iid'].'" LIMIT 1');
									}
									$is1 .= '<br><br><small>(<a name="itmzub'.$pl['iid'].'" href="main.php?otdel='.((int)$_GET['otdel']).'&nozbpl='.$pl['iid'].'#itmzub'.$pl['iid'].'">Запретить продажу за зубы</a>)</small>';
								}*/
							}
						}
					}/*elseif( $this->info['admin'] > 0 ) {
						if( isset($_GET['nozbpl']) && $pl['iid'] == $_GET['nozbpl'] ) {
							mysql_query('UPDATE `items_shop` SET `nozuby` = 0 WHERE `iid` = "'.$pl['iid'].'" LIMIT 1');
						}
						$is1 .= '<br><br><small>(<a name="itmzub'.$pl['iid'].'" href="main.php?otdel='.((int)$_GET['otdel']).'&nozbpl='.$pl['iid'].'#itmzub'.$pl['iid'].'">Разрешить продажу за зубы</a>)</small>';
					}*/
				}
				/*
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
				*/
				//название
				
				if(isset($po['tr_align']) && !isset($po['tr_align_bs'])) {
					$pl['name'] .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$po['tr_align'].'.gif >';
				} elseif(isset($po['tr_align_bs'])) {
					if($po['tr_align_bs'] == '1') {
					  $pl['name'] .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align1.75.gif >';
					} elseif($po['tr_align_bs'] == '3') {
					  $pl['name'] .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align3.01.gif >';
					}
				}
				
				if( isset($po['tr_rep']) && isset($po['tr_dungeon']) ) {
					//$pl['name'] .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$po['tr_align'].'.gif >';
				}
				if( isset($po['renameadd']) && $po['renameadd'] != '' ) {
					$pl['name'] .= ' (Предмет: '.$po['renameadd'].')';
				}
				if( isset($po['icos']) ) {
					$pl['name'] = '<span class="icos_'.$po['icos'].'">'.$pl['name'].' <span><small>&nbsp;'.$po['icos'].'&nbsp;</small></span></span>';
				}
				$is2 .= '<a name="sit_'.$pl['id'].'" href="http://xcombats.com/item/'.$pl['item_id'].'" target="_blank">'.$pl['name'].'</a> &nbsp; &nbsp;';
				
				
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
					}elseif($po['sudba']=='1'){
						$is2 .= ' <img title="Этот предмет будет связан общей судьбой с первым, кто возьмет предмет. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif">';
					}else{
						$is2 .= ' <img title="Этот предмет связан общей судьбой с '.$po['sudba'].'. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/desteny.gif">';
					}
				}
				
				//цена
				if( $this->info['admin'] > 0 ) {
					$is2 .= '<div style="float:right"> <a href="?otdel='.round($_GET['otdel']).'&itmid='.$pl['id'].'&itmup=1&rnd='.microtime().'#itmdown'.$pl['id'].'">&uarr;</a> &nbsp; '.$pl['pos'].' &nbsp; <a name="itmdown'.$pl['id'].'" id="itmdown'.$pl['id'].'" href="?rand='.microtime().'&otdel='.round($_GET['otdel']).'&itmid='.$pl['id'].'&itmdown=1#itmdown'.$pl['id'].'">&darr;</a></div>';
				}
				$is2 .= '<br><b>Цена: ';
				if( $this->stats['silver'] >= 1 && $sid == 1 ) {
					$is2 .= '<strike>';
				}elseif( $this->stats['silver'] >= 5 && ($sid == 2 || $sid == 777) ) {
					$is2 .= '<strike>';
				}
				if( $sid == 12 ) {
					if($pl['price_1'] > (($this->rep['repitems']-$this->rep['nu_items'])))
					{
						$is2 .= '<font color="red">'.round($pl['price_1']).'</font>';
					}else{
						$is2 .= ''.round($pl['price_1']);
					}
					$is2 .= '</b> <b>Репутации сдачи ресурсов</b>';
				}elseif( $sid == 609 ) {
					if($pl['price_4'] > ($this->rep['rep3']-$this->rep['rep3_buy']))
					{
						$is2 .= '<font color="red">'.round($pl['price_4']).'</font>';
					}else{
						$is2 .= round($pl['price_4']);
					}
					$is2 .= '</b> <b>*</b> Воинственности ';
				}elseif($pl['price_3'] > 0) {
					if($pl['price_3']>$this->info['money3'])
					{
						$is2 .= '<font color="red">'.$pl['price_3'].'</font>';
					}else{
						$is2 .= $pl['price_3'];
					}
					$is2 .= ' $ </b> ';
				}elseif($sid==2 || $sid==777)
				{
					$is .= '<span style="color:#f93737">';
					if($pl['price_2']>$this->bank['money2'])
					{
						$is2 .= '<img src=/coin2.png height=11 >&nbsp;<font color="red">'.$pl['price_2'].'</font>';
					}else{
						$is2 .= '<img src=/coin2.png height=11 >&nbsp;'.$pl['price_2'];
					}
					$is2 .= ' екр.</b></span> ';
				}else{
					if($pl['price_1']>$this->info['money'])
					{
						$is2 .= '<img src=/coin1.png height=11 >&nbsp;<font color="red">'.$pl['price_1'].'</font>';
					}else{
						$is2 .= '<img src=/coin1.png height=11 >&nbsp;'.$pl['price_1'];
					}
					$is2 .= ' кр.</b> ';
				}
				if( $this->stats['silver'] >= 1 && $sid == 1 ) {
					$is2 .= '</strike> &nbsp; <b>';
					if($pl['price_1']>$this->info['money'])
					{
						$is2 .= '<img src=/coin1.png height=11 >&nbsp;<font color="red">'.round($pl['price_1']*0.95,2).'</font>';
					}else{
						$is2 .= '<img src=/coin1.png height=11 >&nbsp;'.round($pl['price_1']*0.95,2);
					}
					$is2 .= ' кр.</b> <sup><small style=color:blue; >Скидка -5%</small></sup>';
				}elseif( $this->stats['silver'] >= 5 && ($sid == 2 || $sid == 777) ) {
					$is2 .= '</strike> &nbsp; <b>';
					if($pl['price_2']>$this->bank['money2'])
					{
						$is2 .= '<img src=/coin2.png height=11 >&nbsp;<font color="red">'.round($pl['price_2']*0.95,2).'</font>';
					}else{
						$is2 .= '<img src=/coin2.png height=11 >&nbsp;'.round($pl['price_2']*0.95,2);
					}
					$is2 .= ' екр.</b> <sup><small style=color:blue; >Скидка -5%</small></sup>';
				}
				
				if($pl['pricerep']>0)
				{
					$is2 .= ' <small><b>('.round($pl['pricerep'],2).' Воинственности)</b></small>';
				}
				
				if($pl['kolvo'] < 100000) {
					$is2 .= ' &nbsp; &nbsp; <small>(количество: <b>'.$pl['kolvo'].'</b>)</small>';
				}
				
				if($pl['nozuby'] == 0 && $sid != 609 && $c['zuby'] == true) {
					if($this->info['level'] > 0 && $this->info['level'] < 8) {
						if($pl['level'] < 8 && $sid_zuby == 1) {
							if( $this->stats['silver'] > 0 && $sid == 1 ) {
								$is2 .= ' (<small>'.ltrim($this->zuby(round($pl['price_1']*0.95,2)),' ').'</small>)';
							}else{
								$is2 .= ' (<small>'.ltrim($this->zuby($pl['price_1']),' ').'</small>)';
							}
						}
					}
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
							$tis = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$tims[0].'" LIMIT 1'));
							if(isset($tis['id']))
							{
								$num_rows = 0;
								$s1p = mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "'.((int)$tims[0]).'" AND `uid` = "'.$this->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0"');
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
					if( $c['noitembuy'] == true ) {
						$trn = 1;
						$ttmm = '';
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
				unset($ttmm);
				
				
				//долговечность
				if( $pl['iznos'] > 0 ) {
					$pl['iznosMAXi'] = $pl['iznos'];
				}
				if($pl['iznosMAXi']>0)
				{
	 	 	 	 	 if($pl['iznosMAXi'] == 999999999) {
						$is2 .= 'Долговечность: <font color=brown>неразрушимо</font > <br>';
					}else{
						$is2 .= 'Долговечность: 0/'.$pl['iznosMAXi'].'<br>';
					}
				}
				
				if( $po['battleUseZd'] > 0 ) {
					$is2 .= 'Задержка использования: '.$this->timeOut($po['battleUseZd']).'<br>';
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
				if($pl['magic_chance'] > 0) {
					$is2 .= '<br>Вероятность срабатывания: '.min(array($pl['magic_chance'],100)).'%';
				}
				
				//Продолжительность действия магии:
				if((int)$pl['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT `id2`,`mname`,`type1`,`img`,`mdata`,`actionTime`,`type2`,`type3`,`onlyOne`,`oneType`,`noAce`,`see`,`info`,`overch`,`bp`,`noch` FROM `eff_main` WHERE `id2` = "'.((int)$pl['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						$is2 .= '<br>Продолжительность действия: '.$this->timeOut($efi['actionTime']);
					}
				}
				
				//<b>Требуется минимальное:</b>
				if( !isset($po['notr']) ) {
					$tr = ''; $t = $this->items['tr'];
					$x = 0;
					while($x<count($t))
					{
						$n = $t[$x];
						if(isset($po['tr_'.$n]) && $po['tr_'.$n] != 0)
						{
							if($po['tr_'.$n] > $this->stats[$n])
							{
								if( $n == 'rep' ) {
									$temp = explode('::',$po['tr_'.$n]); 
									if( $this->rep['rep'.$temp[1]] < $temp[0] ) { $tr .= '<font color="red">'; $notr++; } 
									unset($temp);
								} elseif( $n != 'align' || floor($this->info['align']) != $po['tr_'.$n] ) {
									$tr .= '<font color="red">'; $notr++;
								}
							}
							$tr .= '<br>• ';
							if( $n == 'rep' ) {
								$temp = explode('::',$po['tr_'.$n]);
								$tr .= $this->is[$n].' '.ucfirst(str_replace('city',' city',$temp[1])).': '.$temp[0];
								unset($temp);
							}elseif( $n != 'align' ) {
								if( $n == 'sex' ) {
									if( $po['tr_'.$n] == 1 ) {
										$tr .= $this->is[$n].': Женский';
									}else{
										$tr .= $this->is[$n].': Мужской';
									}
								}else{
									$tr .= $this->is[$n].': '.$po['tr_'.$n];
								}
							}else{
								$tr .= $this->is[$n].': '.$this->align_nm[$po['tr_'.$n]];
							}
							if($po['tr_'.$n] > $this->stats[$n])
							{
								if( $n != 'align' || floor($this->info['align']) != $po['tr_'.$n] ) {
									$tr .= '</font>';
								}
							}
						}
						$x++;
					}
					if($tr!='')
					{
						
						$is2 .= '<br><b>Требуется минимальное:</b>'.$tr;
					}
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
					if($pozonb>0)
					{
						$x = 1;
						while($x<=$pozonb)
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
						$tr .= '<br>• '.$this->is['tya'.$x].': '.$tyc.' ('.$po['tya'.$x].'%)';
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if(@$po['tym'.$x]>0)
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
						$tr .= '<br>• '.$this->is['tym'.$x].': '.$tyc.' ('.$po['tym'.$x].'%)';
					}
					$x++;
				}
				if($tr!='')
				{
					$is2 .= '<br><b>Особенности:</b>'.$tr;
				}
				
				$tr = '';
				
				if(isset($po['imposed']) && $po['imposed']>0) {
					if($po['imposed_lvl'] == 0) {
						$rnc = 'maroon';
					}elseif($po['imposed_lvl'] == 1) {
						$rnc = '#624542';
					}elseif($po['imposed_lvl'] == 2) {
						$rnc = '#77090b';
					}elseif($po['imposed_lvl'] == 3) {
						$rnc = '#d99800';
					}else{
						$rnc = '#282828';
					}
					$po['imposed_name'] = str_replace('Чары ','',$po['imposed_name']); 
					$tr .= '<br>&bull; <font color='.$rnc.'>Наложены заклятия:</font> '.$po['imposed_name'].' '; 
					unset($rnc);
				} 
				if($tr!='') {
					$is2 .= '<br><b>Улучшения предмета:</b>';
					$is2 .= $tr;
				}
				
				
				if($notr==0)
				{
					$d[0] = 1;
					if($pl['magic_inc']!='')
					{
						$d[2] = 1;
					}
				}
				
				if(isset($po['free_stats']) && $po['free_stats']>0)
				{
					$is2 .= '<br><b>Распределение статов:</b><br>';
					$is2 .= '&bull; Возможных распределений: +'.$po['free_stats'].' характеристик';
				}
				
				//Встроенная магия
				if($pl['magic_inci']!='' || $pl['magic_inc']!='') {
					if($pl['magic_inc'] == '') {
						$pl['magic_inc'] = $pl['magic_inci'];
					}
					$mgi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$pl['magic_inc'].'" AND `type1` = "12345" LIMIT 1'));
					if(isset($mgi['id2'])) {
						$is2 .= '<div> Встроено заклятие <img height=18 title="'.$mgi['mname'].'" src="http://img.xcombats.com/i/eff/'.$mgi['img'].'"> '.$mgi['minfo'].'</div>';
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
					$spc = mysql_query('SELECT `id`,`com`,`name`,`x`,`data` FROM `complects` WHERE `com` = "'.$po['complect'].'" ORDER BY  `x` ASC LIMIT 20');
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
				
				$is2 .= '<small style="">';
				
				if(isset($po['gravi'])) {
					$is2 .= '<div><img title="'.$this->city_name[$po['gravic']].'" style="vertical-align:bottom" width=34 height=19 src=http://img.xcombats.com/i/city_ico2/'.$po['gravic'].'.gif> На поверхности выгравирована надпись:</div><div align=left style=color:#575757>&nbsp; &nbsp;'.$po['gravi'].'</div>';
				}
				
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
				
				if(isset($po['nosale']))
				{
					$is2 .= '<div style="color:brown;">Предмет нельзя продать</div>';
				}
				
				if(isset($po['nomodif']))
				{
					$is2 .= '<div style="color:brown;">Предмет нельзя улучшать</div>';
				}
				
				if(isset($po['nodelete']))
				{
					$is2 .= '<div style="color:brown;">Предмет нельзя выбросить</div>';
				}
				
				if(isset($po['frompisher']) && $po['frompisher']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
				}
				
				if(isset($po['sleep_moroz']) && $po['sleep_moroz'] > 0 ) {
					$is2 .= '<div style="color:brown;">Предмет не портится во время сна</div>';
				}
				
				if(isset($po['fromlaba']) && $po['fromlaba']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет из лабиринта</div>';
				}
				
				if(isset($po['vip_sale']) && $po['vip_sale']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет куплен за 10% от стоимости</div>';
				}
				
				if($pl['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
				}
				
				if( $this->pokol > $pl['geni'] ) {
					$is2 .= '<div style="color:brown">Предмет устарел</div>';
				}	
				
				if(isset($po['zazuby']) && $po['zazuby']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет куплен за зубы</div>';
				}
				
				$is2 .= '<div>Сделано в '.$this->city_name[$this->info['city']].'</div>';
				
				$is2 .= '</small>';
				
				$crd = '';
				/*
				if($this->info['admin'] > 0) {
					$crd = '<small><a href="javascript:window.open(\'http://xcombats.com/item_edit_data.php?edit_item_data='.$pl['id'].'\',\'winEdi1\',\'width=850,height=400,top=400,left=500,resizable=no,scrollbars=yes,status=no\');" target="_blank">Редактировать предмет</a> &nbsp; <a href="http://xcombats.com/main.php?timeWorld='.microtime().'&otdel='.round((int)$_GET['otdel']).'#itmShop'.$pl['id'].'" name="itmShop'.$pl['id'].'">обновить</a></small><br>';
				}
				*/
				echo '<tr style="background-color:#'.$cr.';"><td width="100" style="padding:7px;" valign="middle" align="center">'.$is1.'</td><td style="padding:7px;" valign="top">'.$is2.'</td></tr>';
				$i++;
			}
			
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
		$lvl = mysql_query('SELECT `upLevel`,`nextLevel`,`exp`,`money`,`money_bonus1`,`money_bonus2`,`ability`,`skills`,`nskills`,`sskills`,`expBtlMax`,`hpRegen`,`mpRegen`,`money2` FROM `levels` WHERE `upLevel` < "'.$this->info['upLevel'].'"');
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
					while($i<=11)
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
		$notr = 0;
		if(!isset($po['notr'])) {
			$x = 0;
			while($x<count($t))
			{
				$n = $t[$x];
				if(isset($po['tr_'.$n]))
				{
					if( $n == 'sex' ) {
						if( $this->info['sex'] != $po['tr_'.$n] ) {
							$notr++;
						}
					}elseif($po['tr_'.$n] > $this->stats[$n] && $n != 'align')
					{
						$notr++;
					}
				}
				$x++;
			}
		}
		return $notr;
	}
	
	public function freeStatsMod($id,$s,$uid)
	{
		$itm = mysql_fetch_array(mysql_query('SELECT
		`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
		FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.((int)$id).'" AND `iu`.`uid` = "'.$uid.'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0" LIMIT 1'));
		if(isset($itm['id']))
		{
			$po = $this->lookStats($itm['data']);
/*
		mf_stats
		mf_mod
		mf_mib
*/
			//статы
			if($_GET['mf'] == 's1' || $_GET['mf'] == 's2' || $_GET['mf'] == 's3' || $_GET['mf'] == 's5') {
				if(isset($po['mf_stats']) && $po['mf_stats']>0 && isset($po['add_'.$_GET['mf']])) {
					$po['mf_stats'] = (int)$po['mf_stats'];
					$po['mf_stats'] -= 1;
					$po['add_'.$_GET['mf']] += 1;		
					
					$po = $this->impStats($po);				
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
			}elseif($_GET['mf'] == 'm1' || $_GET['mf'] == 'm2' || $_GET['mf'] == 'm4' || $_GET['mf'] == 'm5') {
				if(isset($po['mf_mod']) && $po['mf_mod']>0 && isset($po['add_'.$_GET['mf']])) {
					$po['mf_mod'] = (int)$po['mf_mod'];
					$po['mf_mod'] -= 1;
					$po['add_'.$_GET['mf']] += 1;		
					
					$po = $this->impStats($po);				
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
			}elseif($_GET['mf'] == 'mib1' || $_GET['mf'] == 'mib2' || $_GET['mf'] == 'mib3' || $_GET['mf'] == 'mib4') {
				$s = $_GET['mf'];
				$s = str_replace('mib','',$s);
				$s = (int)$s;
				if(isset($po['mf_mib']) && $po['mf_mib']>0 && (isset($po['add_mib'.$s]) || isset($po['add_mab'.$s]))) {
					$po['mf_mib'] = (int)$po['mf_mib'];
					$po['mf_mib'] -= 1;
					if(isset($po['add_mab'.$s])) {
						$po['add_mab'.$s] += 1;	
					}
					if(isset($po['add_mib'.$s])) {
						$po['add_mib'.$s] += 1;	
					}
					
					$po = $this->impStats($po);				
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
			}
			
		}
	}
	
	public function freeStatsItem($id,$s,$uid)
	{
		$itm = mysql_fetch_array(mysql_query('SELECT
		`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
		FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.((int)$id).'" AND `iu`.`uid` = "'.$uid.'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0" LIMIT 1'));
		if(isset($itm['id']) && ( $s == 1 || $s == 2 || $s == 3 || $s == 5 ) )
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
	
	public function freeStats2Item($id,$s,$uid,$tp)
	{

			$itm = mysql_fetch_array(mysql_query('SELECT
			`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
			`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
			FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.((int)$id).'" AND `iu`.`uid` = "'.$uid.'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0" LIMIT 1'));
			if(isset($itm['id']))
			{
				$po = $this->lookStats($itm['data']);
				if($itm['so'] > 0)
				{
					$s = (int)$s;
					if($tp == 1) {
						//статы
						if($s == 1 || $s == 2 || $s == 3 || $s == 5) {
							if(10+25*$po['add_s'.$s] <= $itm['so'] && $itm['so'] > 0) {
								$itm['so'] -= 10+25*$po['add_s'.$s];
								$po['add_s'.$s]++;
								$this->error = 'Характеристика улучшена...';
							}else{
								$this->error = 'Не хватает очков развития...';
							}
						}
					}elseif($tp == 2) {
						//мф
						if($s > 0 && $s < 13) {
							$s2 = 0;							
							if($s == 9 || $s == 12) {
								if($s == 9) {
									$s = 'm10';
								}elseif($s == 12) {
									$s = 'zm';
								}
								$s2 = 4+4*$po['add_'.$s];
							}else{
								if($s == 1) {
									$s = 'm1';
								}elseif($s == 2) {
									$s = 'm2';
								}elseif($s == 3) {
									$s = 'm4';
								}elseif($s == 4) {
									$s = 'm5';
								}elseif($s == 5) {
									$s = 'mab1';
								}elseif($s == 6) {
									$s = 'mab2';
								}elseif($s == 7) {
									$s = 'mab3';
								}elseif($s == 8) {
									$s = 'mab4';
								}elseif($s == 10) {
									$s = 'za';
								}elseif($s == 11) {
									$s = 'm11a';
								}
								$s2 = 5+5*$po['add_'.$s];
							}
							
							if(4+4*$po['add_'.$s] <= $itm['so'] && $itm['so'] > 0) {
								$itm['so'] -= $s2;
								if($s=='mab1') {
									$po['add_mib1']++;
								}elseif($s=='mab2') {
									$po['add_mib2']++;
								}elseif($s=='mab3') {
									$po['add_mib3']++;
								}elseif($s=='mab4') {
									$po['add_mib4']++;
								}
								$po['add_'.$s]++;
							}else{
								$this->error = 'Не хватает очков развития...';
							}
						}
					}
				}
				$po = $this->impStats($po);
				mysql_query('UPDATE `items_users` SET `data` = "'.$po.'",`so` = "'.$itm['so'].'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			}
	}

	public function obj_addItem($id){
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl` FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1'));
		if( $this->info['transfers'] < 1 ) {
			$this->error = 'На сегодня лимит передач исчерпан.';
		}elseif(isset($itm['id'])){
			if( isset($itm['inGroup']) AND $itm['inGroup'] > 0 ) {
				$col = $this->itemsX($itm['id']);
				if($col > 1){
					$upd = mysql_query('UPDATE `items_users` SET `inShop` = 1 WHERE `item_id`="'.$itm['item_id'].'" AND `uid`="'.$itm['uid'].'" AND `inGroup` = "'.$itm['inGroup'].'" LIMIT '.$col.'');
				} else {
					$upd = mysql_query('UPDATE `items_users` SET `inShop` = 1 WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$itm['id'].'" AND `inOdet` = "0" AND `delete` = "0" ');
				}
			} else {
				$upd = mysql_query('UPDATE `items_users` SET `inShop` = 1 WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$id.'" AND `inOdet` = "0" AND `delete` = "0" ');
			} 
			if($upd) { /*
				if($col>1) { $col = ' (x'.$col.')'; }else{ $col = ''; }
				$this->info['transfers']--;
				mysql_query('UPDATE `stats` SET `transfers` = "'.$this->info['transfers'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				$this->error = 'Предмет &quot;'.$itm['name'].''.$col.'&quot; перенесен из инвентаря';
				*/
			}
		} else {
			$this->error = 'Предмет не найден в рюкзаке';	
		}
	}
	
	public function obj_takeItem($id){
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="1" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1'));
		if( $this->info['transfers'] < 1 ) {
			$this->error = 'На сегодня лимит передач исчерпан.';
		}elseif(isset($itm['id'])){
			if( isset($itm['inGroup']) && $itm['inGroup'] > 0 ) {
				$col = $this->itemsX($itm['id']);
				if($col > 1){
					$upd = mysql_query('UPDATE `items_users` SET `inShop` = 0 WHERE `uid` = "'.$this->info['id'].'" AND ( `id` = "'.$itm['id'].'" OR `inGroup` = "'.$itm['inGroup'].'") AND `inOdet` = "0" AND `delete` = "0" ');
				} else {
					$upd = mysql_query('UPDATE `items_users` SET `inShop` = 0 WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$itm['id'].'" AND `inOdet` = "0" AND `delete` = "0" ');
				}
			} else {
				$upd = mysql_query('UPDATE `items_users` SET `inShop` = 0 WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$id.'" AND `inOdet` = "0" AND `delete` = "0" ');
			}
			if($upd){ /*
				if($col>1) { $col = ' (x'.$col.')'; }else{ $col = ''; }
				$this->error = 'Предмет &quot;'.$itm['name'].''.$col.'&quot; перенесен в инвентаря';
				$this->info['transfers']--;
				mysql_query('UPDATE `stats` SET `transfers` = "'.$this->info['transfers'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
				*/
			}
		}else{
			$this->error = 'Предмет не найден в сундуке';	
		}
	}
	
	public function itemsSmSave($id,$s,$uid){
		$itm = mysql_fetch_array(mysql_query('SELECT
		`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
		FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.((int)$id).'" AND `iu`.`uid` = "'.$uid.'" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0" LIMIT 1'));
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
			$vi = 0;
			$vid = '';
			if( isset($_GET['item_rune_id']) ) {
				$sp = mysql_query('SELECT
				`i`.`id`,`i`.`item_id`,`i`.`1price`,`i`.`2price`,`i`.`uid`,`i`.`use_text`,`i`.`data`,`i`.`inOdet`,`i`.`inShop`,`i`.`delete`,`i`.`iznosNOW`,`i`.`iznosMAX`,`i`.`gift`,`i`.`gtxt1`,`i`.`gtxt2`,`i`.`kolvo`,`i`.`geniration`,`i`.`magic_inc`,`i`.`maidin`,`i`.`lastUPD`,`i`.`timeOver`,`i`.`overType`,`i`.`secret_id`,`i`.`time_create`,`i`.`inGroup`,`i`.`dn_delete`,`i`.`inTransfer`,`i`.`post_delivery`,`i`.`lbtl_`,`i`.`bexp`,`i`.`so`,`i`.`blvl`
				,`m`.`type`,`m`.`2h`,`m`.`inslot`,`m`.`name`,`m`.`img` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON (`i`.`item_id` = `m`.`id`) WHERE `i`.`inShop` = "0" AND `i`.`delete` = "0" AND `i`.`inOdet` = "0" AND `i`.`id` = "'.mysql_real_escape_string($_GET['item_rune_id']).'" AND `i`.`uid` = "'.mysql_real_escape_string($this->info['id']).'"');
				$vi = -1;
			}else{
				$sp = mysql_query('SELECT
				`i`.`id`,`i`.`item_id`,`i`.`1price`,`i`.`2price`,`i`.`uid`,`i`.`use_text`,`i`.`data`,`i`.`inOdet`,`i`.`inShop`,`i`.`delete`,`i`.`iznosNOW`,`i`.`iznosMAX`,`i`.`gift`,`i`.`gtxt1`,`i`.`gtxt2`,`i`.`kolvo`,`i`.`geniration`,`i`.`magic_inc`,`i`.`maidin`,`i`.`lastUPD`,`i`.`timeOver`,`i`.`overType`,`i`.`secret_id`,`i`.`time_create`,`i`.`inGroup`,`i`.`dn_delete`,`i`.`inTransfer`,`i`.`post_delivery`,`i`.`lbtl_`,`i`.`bexp`,`i`.`so`,`i`.`blvl`
				,`m`.`type`,`m`.`2h`,`m`.`inslot`,`m`.`name`,`m`.`img` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON (`i`.`item_id` = `m`.`id`) WHERE `i`.`inShop` = "0" AND `i`.`delete` = "0" AND `i`.`inOdet` = "0" AND `m`.`name` LIKE "%'.mysql_real_escape_string(str_replace('"','&quot;',$name)).'%" AND `i`.`uid` = "'.mysql_real_escape_string($this->info['id']).'"');
			}
			while($pl = mysql_fetch_array($sp)) {
				$vibor .= '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'"><hr>';
				if(!isset($id['id'])) {
					if($pl['type']!=4 && $pl['type']!=2 && $pl['type']!=7 ) {
						$dt = $this->lookStats($pl['data']);
						$id = array();
						$id = $pl;	
						$id_type = $pl['type'];
					}
				}
				if( $vi != -1 ) {
					$vi++;
					$vid .= '`iu`.`id` = "'.$pl[0].'" OR';
				}
			}
		}
		
		if( $vi > 0 ) {
			$itm_inv = $this->genInv(80,' '.rtrim($vid,'OR').' ');
			if($ruid < 1 && isset($_GET['use_rune'])) {
				$ruid = $_GET['use_rune'];
			}
			$rune = mysql_fetch_array(mysql_query('SELECT 
			`i`.`id`,`i`.`item_id`,`i`.`1price`,`i`.`2price`,`i`.`uid`,`i`.`use_text`,`i`.`data`,`i`.`inOdet`,`i`.`inShop`,`i`.`delete`,`i`.`iznosNOW`,`i`.`iznosMAX`,`i`.`gift`,`i`.`gtxt1`,`i`.`gtxt2`,`i`.`kolvo`,`i`.`geniration`,`i`.`magic_inc`,`i`.`maidin`,`i`.`lastUPD`,`i`.`timeOver`,`i`.`overType`,`i`.`secret_id`,`i`.`time_create`,`i`.`inGroup`,`i`.`dn_delete`,`i`.`inTransfer`,`i`.`post_delivery`,`i`.`lbtl_`,`i`.`bexp`,`i`.`so`,`i`.`blvl`
			,`m`.`name`,`m`.`type`,`m`.`level` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `i`.`item_id` = `m`.`id` WHERE `i`.`id` = "'.mysql_real_escape_string($ruid).'" AND `i`.`uid` = "'.$this->info['id'].'" AND `i`.`delete` = "0" AND `i`.`inShop` = "0" LIMIT 1'));
			echo '<button style="float:right" class="btnnew" type="button" onclick="top.frames[\'main\'].location=\'main.php?inv=1&otdel='.floor($_GET['otdel']).'\'">Вернуться</button><b>Выберите предмет для использования &quot;'.$rune['name'].'&quot;:</b><br><br><table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">'.$itm_inv[2].'</table>';
			die();
		}

		if($id['id'] > 0) {	
			if($ruid < 1 && isset($_GET['use_rune'])) {
				$ruid = $_GET['use_rune'];
			}
			$rune = mysql_fetch_array(mysql_query('SELECT 
			`i`.`id`,`i`.`item_id`,`i`.`1price`,`i`.`2price`,`i`.`uid`,`i`.`use_text`,`i`.`data`,`i`.`inOdet`,`i`.`inShop`,`i`.`delete`,`i`.`iznosNOW`,`i`.`iznosMAX`,`i`.`gift`,`i`.`gtxt1`,`i`.`gtxt2`,`i`.`kolvo`,`i`.`geniration`,`i`.`magic_inc`,`i`.`maidin`,`i`.`lastUPD`,`i`.`timeOver`,`i`.`overType`,`i`.`secret_id`,`i`.`time_create`,`i`.`inGroup`,`i`.`dn_delete`,`i`.`inTransfer`,`i`.`post_delivery`,`i`.`lbtl_`,`i`.`bexp`,`i`.`so`,`i`.`blvl`
			,`m`.`name`,`m`.`type`,`m`.`level` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `i`.`item_id` = `m`.`id` WHERE `i`.`id` = "'.mysql_real_escape_string($ruid).'" AND `i`.`uid` = "'.$this->info['id'].'" AND `i`.`delete` = "0" AND `i`.`inShop` = "0" LIMIT 1'));
			if($rune['level'] > $this->info['level'] && $rune['type']==31) {
				$this->error = 'У вас слишком маленький уровень чтобы использовать эту руну';
			}elseif($rune['type']==31 && $id['type'] >= 1 && $id['type'] <=15) {
				//Встраиваем руну
				if( $id['type'] == 5 ) {
					$id['type'] = 6;
				}
				$data = $this->lookStats($id['data']);
				$type_rune = array(
					'хи' => 9, //Серьги
					'хэ' => 10, //Ожерелье
					'ви' => 11, //Кольцо
					'во' => 12, //Перчатки
					'кэ' => 14, //Поножи
					'ки' => 15, //Обувь
					'ми' => 1, //Шлем
					'си' => 3, //Наручи
					'мо' => 6, //Броня
					'со' => 8  //Пояс
				);
				if( $rune['type'] == 5 ) {
					$type_rune['мо'] = 5;
				}
				//
				//$idt = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT` 1'));
				//if( $idt['type'] == 5 ){ $idt['type'] = 6; }
				//$id['type'] = $idt['type'];
				//
				$type_rune = $type_rune[substr($rune['name'], -2, 2)];
				if( $type_rune != $id['type'] && isset($type_rune2[substr($rune['name'], -2, 2)])) {
					$type_rune2 = array(
						'хи' => 'серьги', //Серьги
						'хэ' => 'ожерелья', //Ожерелье
						'ви' => 'кольцо', //Кольцо
						'во' => 'перчатки', //Перчатки
						'кэ' => 'поножи', //Поножи
						'ки' => 'обувь', //Обувь
						'ми' => 'шлем', //Шлем
						'си' => 'наручи', //Наручи
						'мо' => 'броню', //Броня
						'со' => 'пояс'  //Пояс
					);
					$this->error = 'Встроить данную руну возможно только в '.$type_rune2[substr($rune['name'], -2, 2)].' (Тип предмета: '.$id['type'].'. Требуемый тип: '.$type_rune.')';
				}elseif(isset($data['nomodif'])) {
					$this->error = 'Улучшить данный предмет невозможно';
				}elseif(isset($data['art']) && $data['tr_lvl'] < 10) {
					$this->error = 'Встраивание усилений в артефакты ниже 10-го уровня недоступно';
				}else{
					if(isset($data['rune']) && $data['rune'] > 0) {
						$ritm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$data['rune_id'].'" LIMIT 1'));
						$j = 0;
						$data_r = $this->lookStats($ritm['data']);
						while($j < count($this->items['add'])) {
							if(isset($data_r['add_'.$this->items['add'][$j]])) {
								$data['add_'.$this->items['add'][$j]] -= $data_r['add_'.$this->items['add'][$j]];
							}
							$j++;
						}
					}
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
					if( $rune['level'] > $data['tr_lvl'] ) {
						$data['tr_lvl'] = $rune['level'];
					}
					$data = $this->impStats($data);
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
					
					
					
					
					
					$this->error = 'Встраивание руны прошло успешно';
				}
			}else{
				$rune = mysql_fetch_array(mysql_query('SELECT 
				`i`.`id`,`i`.`item_id`,`i`.`1price`,`i`.`2price`,`i`.`uid`,`i`.`use_text`,`i`.`data`,`i`.`inOdet`,`i`.`inShop`,`i`.`delete`,`i`.`iznosNOW`,`i`.`iznosMAX`,`i`.`gift`,`i`.`gtxt1`,`i`.`gtxt2`,`i`.`kolvo`,`i`.`geniration`,`i`.`magic_inc`,`i`.`maidin`,`i`.`lastUPD`,`i`.`timeOver`,`i`.`overType`,`i`.`secret_id`,`i`.`time_create`,`i`.`inGroup`,`i`.`dn_delete`,`i`.`inTransfer`,`i`.`post_delivery`,`i`.`lbtl_`,`i`.`bexp`,`i`.`so`,`i`.`blvl`
				,`m`.`name`,`m`.`level`,`m`.`type` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `i`.`item_id` = `m`.`id` WHERE `i`.`id` = "'.mysql_real_escape_string($ruid).'" AND `i`.`uid` = "'.$this->info['id'].'" AND `i`.`delete` = "0" AND `i`.`inShop` = "0" LIMIT 1'));
				if(!isset($rune['id'])) {
					$this->error = 'Усиление которое вы использовали не найдено';
				}elseif($rune['type']==62) {
					
					$idt = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT` 1'));
					if( $idt['type'] == 5 ){ $idt['type'] = 6; }
					//$id['type'] = $idt['type'];
					//Встраиваем руну 
					$data = $this->lookStats($id['data']);
					$add = $this->lookStats($rune['data']);				
					if(isset($data['nomodif'])) {
						$this->error = 'Улучшить данный предмет невозможно';
					}elseif(isset($data['art']) && $data['tr_lvl'] < 10) {
						$this->error = 'Встраивание усилений в артефакты ниже 10-го уровня недоступно';
					}elseif(isset($add['onimposed'])) {
						//Встраиваем магию
						//Новая чарка
						$i = 0; $j = 0;
						$utp = explode(',',$add['onItemType']);
						while($i<count($utp)) {
							if($utp[$i] == $id['type']){
								$j++;
							}
							$i++;
						}
						if( $j > 0 ) {
							
							unset($data['imposed'],$data['imposed_name'],$data['bm_a1']);
							
							$imposed = array(
								'imposed' => 1,
								'imposed_name' => $add['onSpellName'],
								'bm_a1' => $add['onSpellFile']
							);
							
							if(!isset($add['onSpellFile'])) {
								unset($imposed['bm_a1']);
								//
								if(isset($add['onSpell_mpAll'])) {
									$imposed['addspell_mpAll'] = $add['onSpell_mpAll'];
									$imposed['add_mpAll'] = $data['add_mpAll'] + $add['onSpell_mpAll'] - $data['addspell_mpAll'];
								}
								//
							}
							
							$imposed['sudba'] = 1;
														
							$data = array_merge($data, $imposed); 
							$data = $this->impStats($data);
			
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							$this->error = 'Наложены чары "'.$add['onSpellName'].'" на предмет &quot;'.$id['name'].'&quot;';
						}else{
							$this->error = 'Предмет &quot;'.$id['name'].'&quot; не подходит для усиления.,.';
						}
					}else{
						if(isset($data['spell']) && $data['spell'] > 0) {
							//Отнимаем эффект от прошлых чарок
							$litm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$id['item_id'].'" LIMIT 1'));
							
							$data_l = $this->lookStats($litm['data']);
							$pvr = array(
								'i' => 0,
								'spell' => array(),
								'rune'	=> array(),
								'atack'	=> array(),
								'podgon'=> array()
							);
							
							if( isset($data['podgon']) ) {
								if($data['tr_lvl'] > $id['level']) {
									$id['level'] = $data['tr_lvl'];
								}
								$pvr['podgon']['hpAll'] = 6*$id['level']+6;
							}
							if( isset($data['addspell_hpAll']) ) {
								$pvr['podgon']['hpAll'] += $data['addspell_hpAll'];
							}
							if( isset($data['rune_id']) && $data['rune_id'] > 0 ) {
								$ritm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$data['rune_id'].'" LIMIT 1'));
								$j = 0;
								$data_r = $this->lookStats($ritm['data']);
								while($j < count($this->items['add'])) {
									if(isset($data_r['add_'.$this->items['add'][$j]])) {
										$pvr['rune'][$this->items['add'][$j]] = $data_r['add_'.$this->items['add'][$j]];
									}
									$j++;
								}
							}
							
							if(isset($data['spell_st_val_hp'])) {
								$data['add_hpAll'] -= $data['spell_st_val_hp'];
							}
							
							//$i = 0;
							//while( $i < count($data)) {
							if( !isset($data['spell_st_name']) ) {
								$delk = count($this->items['add']);
								$this->items['add'][] = 'mib1';
								$this->items['add'][] = 'mib2';
								$this->items['add'][] = 'mib3';
								$this->items['add'][] = 'mib4';								
								$this->items['add'][] = 'mab1';
								$this->items['add'][] = 'mab2';
								$this->items['add'][] = 'mab3';
								$this->items['add'][] = 'mab4';
								$j = 0;
								while($j < count($this->items['add'])) {
									if(isset($data['add_'.$this->items['add'][$j]])) {
										if( $data_l['add_'.$this->items['add'][$j]] != $data['add_'.$this->items['add'][$j]] - $pvr['rune'][$this->items['add'][$j]] - $pvr['podgon'][$this->items['add'][$j]] ) {									
											//echo ''.$this->items['add'][$j].' -> '.$data_l['add_'.$this->items['add'][$j]].' / '.$data['add_'.$this->items['add'][$j]].'<br>';
											if(!isset($data_l['add_'.$this->items['add'][$j]])) {
												unset($data['add_'.$this->items['add'][$j]]);
											}else{
												$data['add_'.$this->items['add'][$j]] = $data_l['add_'.$this->items['add'][$j]];
											}
										}
									}
									$j++;
								}
								$i = 0;
								while( $i < 8 ) {
									unset($this->items['add'][$delk+$i]);
									$i++;
								}
								//$i++;
							//}
							}							
						}
						//Новая чарка
						$i = 0;
						$utp = explode(',',$add['onItemType']);
						while($i<count($utp)) {
							if($utp[$i] == $id['type']){
								// Определили нужный тип оружия.
								$itm_twohand = mysql_fetch_array(mysql_query('SELECT `2h` FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT 1'));
								if(isset($itm_twohand['2h']) && $itm_twohand['2h']==1) $tw = '2'; else $tw = '';
								$j = 0;
								while($j<count($this->items['add'])) {
									if(isset($add[$tw.'add'.$utp[$i].'_'.$this->items['add'][$j]])) {									
										$rnda[count($rnda)] = $this->items['add'][$j];
									}
									$j++;
								}

								if( isset($add['imposed']) && $add['imposed'] != '' ) {
									$imposed = array(
										'imposed'=>'1', // Активно
										'imposed_id'=>$rune['id'], // ID чарки свитка
										'imposed_name'=>$rune['name'], // Наименование чар, которое отображается на предмете
										'imposed_level'=>$rune['level'], // Цвет подсветки заклинания при отображении. По умолчанию: 0;
										'bm_a1'=>$add['imposed'], // Имя файла заклинания.
										'sudba'=>$this->info['login'] // Цвет подсветки заклинания при отображении. По умолчанию: 0;
									);
									if( isset($add['imposed_name']) ) $imposed['imposed_name']=$add['imposed_name'];
									if( isset($add['imposed_level']) ) $imposed['imposed_level']=$add['imposed_level'];
									$rnda[0] = 1;
								}
								if( count($rnda) >= 0 ) {
									$rnda = $rnda[rand(0,count($rnda)-1)];
									if( $rnda == 'mib1' || $rnda == 'mib2' || $rnda == 'mib3' || $rnda == 'mib4' ) {
										$rnda = str_replace('mib','mab',$rnda);
									}
									
									if( !isset($this->is[$rnda]) && isset($imposed) ){
										$data = array_merge($data, $imposed); 
										$data = $this->impStats($data);
		
										mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
										mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
										$this->error = 'Наложены чары "'.$imposed['imposed_name'].'" на предмет &quot;'.$id['name'].'&quot;';
										
									} elseif( !isset($this->is[$rnda])
										&& $rnda != 'mib1' && $rnda != 'mib2' && $rnda != 'mib3' && $rnda != 'mib4'
										&& $rnda != 'mab1' && $rnda != 'mab2' && $rnda != 'mab3' && $rnda != 'mab4'
									) {
										$this->error = 'Попробуйте зачарить предмет снова. ( '.$rnda.' )';
									} else {
										$data['add_'.$rnda] += $add[$tw.'add'.$utp[$i].'_'.$rnda];
										if( $rnda == 'mab1' || $rnda == 'mab2' || $rnda == 'mab3' || $rnda == 'mab4' ) {
											$data['add_'.str_replace('mab','mib',$rnda)] += $add[$tw.'add'.$utp[$i].'_'.$rnda];
										}
										/*$jkh = 1;
										while( $jkh <= 4 ) {
											if($rnda == 'mib'.$jkh) {
												$data['add_mab'.$jkh] += $add['add'.$utp[$i].'_'.$rnda];
											}elseif($rnda == 'mab'.$jkh) {
												$data['add_mib'.$jkh] += $add['add'.$utp[$i].'_'.$rnda];
											}
											$jkh++;
										}*/
											
										$data['spell'] = $rune['id'];
										if( !isset($data['sudba']) ) {
											$data['sudba'] = '0';
										}
											
										if( isset($data['spell_st_name']) ) {
											$data['add_' . $data['spell_st_name']] -= $data['spell_st_val'];
											if( $data['spell_st_name'] == 'mab1' || $data['spell_st_name'] == 'mab2' || $data['spell_st_name'] == 'mab3' || $data['spell_st_name'] == 'mab4' ) {
												$data['add_'.str_replace('mab','mib',$data['spell_st_name'])] -= $data['spell_st_val'];
											}
											/*$jkh = 1;
											while( $jkh <= 4 ) {
												if( $data['spell_st_name'] == 'mib'.$jkh ) { 
													$data['add_' . str_replace('mib','mab',$data['spell_st_name'])] -= $data['spell_st_val'];
												}elseif( $data['spell_st_name'] == 'mab'.$jkh ) { 
													$data['add_' . str_replace('mab','mib',$data['spell_st_name'])] -= $data['spell_st_val'];
												}
												$jkh++;
											}*/
											if( $data['add_' . $data['spell_st_name']] == 0 ) {
												unset($data['add_' . $data['spell_st_name']]);
											}
										}
											
										$data['spell_id'] = $rune['item_id'];
										$data['spell_name'] = $rune['name'];
										$data['spell_lvl'] = $rune['level'];
										$data['spell_st_name'] = $rnda;
										$data['spell_st_val'] = $add[$tw.'add'.$utp[$i].'_'.$rnda];	
										if(isset($add['addspell_hpAll'])) {
											$data['spell_st_val_hp'] = $add['addspell_hpAll'];
											$data['add_hpAll'] += $add['addspell_hpAll'];
										}else{
											unset($data['spell_st_val_hp']);
										}
										$data = $this->impStats($data);	
										
										$this->is['mab1'] = 'Броня головы';
										$this->is['mab2'] = 'Броня корпуса';
										$this->is['mab3'] = 'Броня пояса';
										$this->is['mab4'] = 'Броня ног';
										$this->error = 'Увеличина характеристика предмета &quot;'.$id['name'].'&quot;, '.$this->is[$rnda].': +'.$add[$tw.'add'.$utp[$i].'_'.$rnda];
										
										//$this->error = '<br/><br/><br/>Увеличина характеристика предмета &quot;'.$id['name'].'&quot;, '.$this->is[$rnda].': +'.$add['add'.$utp[$i].'_'.$rnda]."<br/><br/>$:".$idt['2h']."<";
		
										unset($this->is['mab1'],$this->is['mab2'],$this->is['mab3'],$this->is['mab4']);
		
										mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
										mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
									}
								}else{
									$this->error = 'Что-то не так, невозможно зачаровать данным свитком';
								}
								$i = 100499;
							}
							$i++;
						}
						if($i < 100500) {
							$this->error = 'Данный предмет не подходит для зачарования...';
						}					
					}
					
				} elseif($rune['type']==47) {
					$add = $this->lookStats($id['data']);
					$data = $this->lookStats($rune['data']);
					if($add['art'] ==1) {
				      if($id['iznosNOW'] > 0) {
						$id['iznosNOW'] -= $data['repairLevel'];
						if($id['iznosNOW'] < 0) { $id['iznosNOW'] = 0; }
						mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$id['iznosNOW'].'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'"  LIMIT 1');
						mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
						$this->error = 'Предмет '.$id['name'].' успешно отремонтирован.';
					  } else {
						$this->error = 'Предмет не нуждаеться в ремонте...';
					  }
					} else {
					  $this->error = 'Свиток ремонтирует только артефакты...';
					}
				} elseif($rune['type']==46) {
					$idt = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT` 1'));
					if( $idt['type'] == 5 ){ $idt['type'] = 6; }
					$id['type'] = $idt['type'];
					if($id_type < 18 || $id_type > 24) {
						$add = $this->lookStats($rune['data']);
						if( isset($add['uptimeitem']) ) {
							$data = $this->lookStats($id['data']);
							if( $data['srok'] > 0 || $id['srok'] > 0 ) {
								if( !isset($data['srok']) ) {
									$data['srok'] = $id['srok'];
								}
								if( $data['srok'] + $id['time_create'] - time() > 86400 + 30 ) {
									$this->error = 'Нельзя использовать на предметы с сроком годности 30 и более дней.';
								}elseif( $id['inslot'] > 0 && $id['inslot'] < 20 ) {
									$this->error = 'Срок годности предмета &quot;'.$id['name'].'&quot; продлен на '.$this->timeOut($add['uptimeitem']).'.';
									
									if( isset($data['sleep_moroz']) ) {
										unset($data['sleep_moroz']);
									}
									
									$data = $this->impStats($data);	
									
									mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
									mysql_query('UPDATE `items_users` SET `data` = "'.$data.'",`time_create` = `time_create` + '.floor($add['uptimeitem']).' WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');

								}else{
									$this->error = 'Предмет данного типа нельзя продлить...';
								}
							}else{
								$this->error = 'Это работает только на предметы с сроком годности...';
							}
						}else{
							$this->error = 'Затачивать можно только оружие...';
						}
					}else{
						//Заточка
						$data = $this->lookStats($id['data']);
						$add = $this->lookStats($rune['data']);
						if(isset($data['nomodif'])) {
							$this->error = 'Улучшить данный предмет невозможно';
						}elseif($add['uptype'] != $id_type || $add['uptype'] == 0) {
							$this->error = 'Заточка не подходит к данному предмету...';
						}else{
							
							if(isset($data['upatack_id']) && $data['upatack_id'] > 0) {
								$ritm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$data['upatack_id'].'" LIMIT 1'));
								$data_r = $this->lookStats($ritm['data']);
								if( $id_type == 22 ) {
									$data['add_m11'] -= $data_r['upatack']*2;
								}elseif( $id['2h'] == 1 ) {
									$data['sv_yron_min'] -= $data_r['upatack']*2;
									$data['sv_yron_max'] -= $data_r['upatack']*2;
								}else{
									$data['sv_yron_min'] -= $data_r['upatack'];
									$data['sv_yron_max'] -= $data_r['upatack'];
								}
							}
							$data['upatack'] = $rune['id'];
							$data['upatack_id'] = $rune['item_id'];
							$data['upatack_name'] = $rune['name'];
							$data['upatack_lvl'] = $add['upatack'];
							$data['upatack_lvl'] = $add['upatack'];
							if(!isset($data['base_price1'])) {
								$data['base_price1'] = $id['1price'];
								$data['base_price2'] = $id['2price'];
							}

							//Добавляем характеристики руны						
							$i = 0;
							while($i<count($this->items['add'])) {
								if(isset($add['add_'.$this->items['add'][$i]])) {
									$data['add_'.$this->items['add'][$i]] += $add['add_'.$this->items['add'][$i]];
								}
								$i++;
							}
							
							if( $id_type == 22 ) {
								$data['add_m11'] += $add['upatack']*2;
								//print_r($data);
								//die('Посохи временно не точатся.');
							}elseif( $id['2h'] == 1 ) {
								$data['sv_yron_min'] += $add['upatack']*2;
								$data['sv_yron_max'] += $add['upatack']*2;
							}else{
								$data['sv_yron_min'] += $add['upatack'];
								$data['sv_yron_max'] += $add['upatack'];
							}
							
							if( !isset($add['nosale']) && !isset($add['frompisher']) ) {
								$id['1price'] = $data['base_price1']+$rune['1price'];	
								$id['2price'] = $data['base_price2']+$rune['2price'];
							}
							
							$data = $this->impStats($data);
													
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `items_users` SET `1price` = "'.$id['1price'].'",`2price` = "'.$id['2price'].'",`data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							$this->error = 'Заточка &quot;'.$id['name'].'&quot; прошла успешно';
						}
					}
				}elseif($rune['type']==68) {
					$idt = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT` 1'));
					if( $idt['type'] == 5 ){ $idt['type'] = 6; }
					$id['type'] = $idt['type'];

						//Встраиваем руну
						$data = $this->lookStats($id['data']);
						$add = $this->lookStats($rune['data']);
						if(isset($data['nomodif'])) {
							$this->error = 'Улучшить данный предмет невозможно';
						}elseif(!isset($data['close']) && $data['close'] != 0) {
							$this->error = 'Не подходит к данному предмету, он не закрыт...';
						}else{
							$data['unopen'] = $rune['id'];
							$data['unopen_id'] = $rune['item_id'];
							$data['unopen_name'] = $rune['name'];
							
							unset($data['close']);
							
							$data['open'] = 1;
							
							$data = $this->impStats($data);								
							
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$rune['id'].'"  AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `items_users` SET `data` = "'.$data.'" WHERE `id` = "'.$id['id'].'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
							$this->error = 'Открытие &quot;'.$id['name'].'&quot; прошло успешно';
						}
						
				}elseif($rune['type']==37) {
					$idt = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$id['item_id'].'" LIMIT 1'));
					if($idt['type'] != 37) {
						//
						$data = $this->lookStats($id['data']);
						$add = $this->lookStats($rune['data']);
						//
						if($id['gift'] == '' || $id['gift'] == '0') {
							if(isset($data['notransfer'])) {
								$this->error = 'Предмет нельзя передавать...';
							}elseif(isset($data['sudba']) && $data['sudba'] != 0) {
								$this->error = 'Предмет связан общей судьбой...';
							}else{
								if(isset($add['item_inbox']) && $add['item_inbox'] > 0) {
									$this->error = 'В упаковке уже что-то есть, осталось сделать подарок!';
								}elseif($id['inTransfer'] > 0 || $id['inShop'] > 0 || $id['inGroup'] > 0) {
									$this->error = 'Нельзя передать этот предмет, он в передаче, на прилавке или в группе...';
								}else{
									//Закидываем предмет в упаковку
									$add['item_inbox'] = $id['id'];		
									$add['open'] = 1;
									$add['nosale'] = 1;									
									$add = $this->impStats($add);
									//
									mysql_query('UPDATE `items_users` SET `inGroup` = 0,`data` = "'.$add.'" WHERE `id` = "'.$rune['id'].'" LIMIT 1');
									mysql_query('UPDATE `items_users` SET `uid` = -1 WHERE `id` = "'.$id['id'].'" LIMIT 1');
									//
									$this->error = 'Предмет &quot;'.$idt['name'].'&quot; упакован в коробку.';
									//
								}
							}
						}else{
							$this->error = 'Даренное не дарят...';
						}
						//
					}else{
						$this->error = 'Вы не сможете упаковать упаковку...';
					}						
				}else{
					$this->error = 'Усиление которое вы использовали не найдено...';
				}
			}
		}else{
			$this->error = 'Подходящего предмета не нашлось...';
		}
	}
	
	public function floordec($zahl,$decimals=2){	 
		 return floor($zahl*pow(10,$decimals))/pow(10,$decimals);
	}
	
	public function testBattle( $id ) {
		$r = true;
		if( $id == 0 ) {
			$r = false;
		}else{
			$btla = mysql_fetch_array(mysql_query('SELECT `id`,`team_win` FROM `battle` WHERE `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
			if( isset($btla['id']) ) {
				if( $btla['team_win'] > -1 ) {
					$r = false;
				}
			}
		}
		return $r;
	}
	
	public function testVipItems($slot_new) {
		$sp = mysql_query('SELECT `i`.`id`,`m`.`type`,`i`.`2price`,`m`.`price2` FROM `items_users` AS `i` LEFT JOIN `items_main` AS `m` ON `m`.`id` = `i`.`item_id` WHERE `i`.`uid` = "'.$this->info['id'].'" AND `i`.`delete` = "0" AND `i`.`data` LIKE "%vip_sale%" LIMIT 20');
		$itm = array();
		$slot = array();
		$j = 0;
		if( $slot_new > 0 ) {
			$itm[$j] = array('new');
			$slot[$slot_new][] = $j;
			$j++;
		}
		$r = true;
		while( $pl = mysql_fetch_array($sp)) {
			$itm[$j] = $pl;
			$slot[$pl['type']][] = $j;
			$j++;
		}
		$l = count($slot[18])+count($slot[19])+count($slot[20])+count($slot[21])+count($slot[22])+count($slot[23])+count($slot[24])+count($slot[25])+count($slot[26])+count($slot[27])+count($slot[28]);
		$v = count($slot[9])+count($slot[10])+count($slot[11]);
		$a = count($itm)-$l-$v;
		if( $this->stats['silver'] == 2 ) {
			// 3 Артефакта (1оружие и все кроме ювелирки)
			if( $j > 3 || $l > 1 || $v > 0 ) {
				$r = false;
			}
		}elseif( $this->stats['silver'] == 3 ) {
			// 6 Артефактов (1 оружие, 1 ювелирка и 4 вещи на выбор) 
			if( $j > 6 || $l > 1 || $v > 1 ) {
				$r = false;
			}
		}elseif( $this->stats['silver'] == 4 ) {
			// 9 Артефактов (2 оружия, 2 ювелирки и 5 вещей на выбор) 
			if( $j > 9 || $l > 2 || $v > 2 ) {
				$r = false;
			}
		}elseif( $this->stats['silver'] == 5 ) {
			// сколько угодно			
		}	
		return $r;
	}
	
	public function berezCena() {
		global $c;
		$r = 0;
		if( $this->stats['silver'] > 0 ) {
			$r = 50+(($this->stats['silver']-1)*5);
			$r = $r/100;
		}
		$r = $c['shop_type2'];
		//$r = 1; //скупка 100%
		$r = round(($r/100),2);
		return $r;	
	}
	
	public function genInv($type, $sort){
		global $c,$code;
		
		$i = 0; // счетчик, просто обнуняем.
		$j = 0;  // Всего предметов while ++
		$k = 1; // 0 или 1
		$rt = array( 0=>0, 1=>0, 2=>'' ); // Количество? Непонятно
		$clr = array( 0=>'c8c8c8', 1=>'d4d4d4' ); // Цвет фона для предметов 
			$sort = explode('ORDER BY', $sort);
			if( isset($sort[0],$sort[1])){
				$where= $sort[0];
				if($sort[1] !='' )$sort = $sort[1].''; else $sort =''; 
			} else {
				$where = $sort[0]; $sort = ' `lastUPD`  DESC';
			}
			$cl = mysql_query('SELECT count(`iu`.item_id) as inGroupCount, `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,`iu`.`so`,`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl` FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE  '.$where.' GROUP BY `im`.id,`iu`.item_id, `iu`.inGroup HAVING `iu`.inGroup > 0  UNION ALL SELECT count(`iu`.item_id) as inGroupCount, `im`.`id`,`im`.`name`,`im`.`img`, `im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`, `im`.`iznosMAXi`,`im`.`inRazdel`, `im`.`price1`,`im`.`price2`, `im`.`pricerep`,`im`.`magic_chance`, `im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`, `im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`, `im`.`ts`,`im`.`srok`,`im`.`class`, `im`.`class_point`,`im`.`anti_class`, `im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`, `im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,`iu`.`so`,`iu`.`id`,`iu`.`item_id`, `iu`.`1price`,`iu`.`2price`,`iu`.`uid`, `iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`, `iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`, `iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`, `iu`.`magic_inc`, `iu`.`maidin`,`iu`.`lastUPD`, `iu`.`timeOver`, `iu`.`overType`, `iu`.`secret_id`, `iu`.`time_create`, `iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`, `iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl` FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE  `iu`.inGroup=0 AND '.$where.' GROUP BY `iu`.id, `iu`.item_id ORDER BY '.$sort.' 
');
		 
		if($type == 15) {
			$anm = mysql_fetch_array(mysql_query('SELECT `id`,`type`,`name`,`uid`,`delete`,`inBattle`,`eda`,`exp`,`obraz`,`stats`,`level`,`sex`,`levelUp`,`pet_in_cage`,`max_exp`,`priems`,`bonus` FROM `users_animal` WHERE `id` = "'.$this->info['animal'].'" AND `pet_in_cage` = 0 AND `delete` = "0" LIMIT 1'));
		} 
		while($pl = mysql_fetch_array($cl)) {
	 	 	 if($type==30){
	 	 	 	 $ChSudba = $this->lookStats($pl['data']);	 	 	 	 	 
	 	 	 	 if(isset($ChSudba['sudba']) || $ChSudba['sudba'] != 0 || $ChSudba['nosale']==1)
	 	 	 	    continue;
	 	 	 }
			if($pl['1price']>0) {
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
			if($type == 15) { //кормушка зверя
				if($anm['type'] == 3 && substr_count($pl['img'],'wisp') == 0) {
					$see1 = 0; //светляк
				}elseif($anm['type'] == 2 && substr_count($pl['img'],'owl') == 0) {
					$see1 = 0; //сова
				}elseif($anm['type'] == 1 && substr_count($pl['img'],'cat') == 0) {
					$see1 = 0; //кот
				}elseif($anm['type'] == 4 && substr_count($pl['img'],'chrt') == 0) {
					$see1 = 0; //чертяка
				}elseif($anm['type'] == 5 && substr_count($pl['img'],'dog') == 0) {
					$see1 = 0; //собака
				}elseif($anm['type'] == 6 && substr_count($pl['img'],'pig') == 0) {
					$see1 = 0; //свинья
				}elseif($anm['type'] == 7 && substr_count($pl['img'],'dragon') == 0) {
					$see1 = 0; //дракон
				}
			}elseif(isset($po['nosale']) && $type==2){
				$see1 = 0;	
			}elseif(isset($po['frompisher']) && $type==2) {
				$see1 = 0;	
			}elseif(isset($po['noremont']) && $type==4){
				$see1 = 0;	
			}elseif($type==5 && $pl['gift']!=''){
				$see1 = 0;	
			}elseif(($type==9 || $type==10) && $pl['gift']==''){
				$see1 = 0;	
			}elseif($type==5 && isset($po['sudba']) && $po['sudba']!='0'){
				$see1 = 0;	
			}elseif($type==5 && $pl['inTransfer']>0){
				$see1 = 0;
			}elseif($type==6 && !isset($po['fshop'])){
				$see1 = 0;	
			} elseif($type == 67 && isset($po['art'])) {
				$see1 = 0;
			}elseif($type == 67 && isset($po['sudba'])) {
				$see1 = 0;
			} elseif($type == 67 && $pl['gift']!='') {
				$see1 = 0;
			}elseif($type==11) { // Храм Знаний (плавка предметов)
				if($pl['inTransfer']>0 || ($po['sudba'] != '' && $po['sudba'] != 0 && $po['sudba'] != 1 && $po['sudba'] != $this->info['id']) ){
					$see1 = 0;
				}
				if($pl['type']!=1 && $pl['type']!=3 && $pl['type']!=9 && $pl['type']!=10 && $pl['type']!=11 && $pl['type']!=5 && $pl['type']!=6 && $pl['type']!=8 && $pl['type']!=12 && $pl['type']!=14 && $pl['type']!=15 && $pl['item_id']!=1035){
					$see1 = 0; 
				}
				if($po['tr_lvl']<4 && $pl['item_id']!=1035){
					$see1 = 0;
				}
			}elseif($type==14) { // Храм Знаний (плавка рун)
				if($pl['inTransfer'] > 0){
					$see1 = 0;
				}
				if($pl['type'] != 31){
					$see1 = 0;
				}
			}elseif($type==12){
				if($pl['inslot']!=3 && $pl['inslot']!=14 && $pl['type']!=31){
					$see1 = 0;
				}
				if(!isset($po['frompisher']) && $pl['type']!=31){
					$see1 = 0;
				}
			}elseif($type==56 && ($pl['inslot'] != 5 || isset($po['podgon']))){
				$see1 = 0;	
			}elseif(isset($po['musor']) && $po['musor']>0 && $pl['iznosNOW']>=$pl['iznosMAX']){
				$see1 = 0;
			}elseif($type==57) {
				//Подгонка
				if($pl['type'] < 18 || $pl['type'] > 28 || $pl['type'] == 25) {
					$see1 = 0;
				}
			}elseif($type==58) {
				//Вытаскивание рун
				if(!isset($po['rune_id'])) {
					$see1 = 0;
				}
			}elseif($type == 65) {
			  if(isset($po['sudba']) && $po['sudba'] != '0') {
				$see1 = 0;
			  }
			  if(isset($po['toclan'])) {
				$po['toclan1'] = explode('#',$po['toclan']);
				$po['toclan1'] = $po['toclan1'][0];
				if($po['toclan1'] != $this->info['clan']) {
				  $see1 = 0;
				}
			  }
			}elseif($type == 67) {
				$po['toclan1'] = explode('#',$po['toclan']);
				$po['toclan1'] = $po['toclan1'][0];
				if($po['toclan1'] > 0) {
					$see1 = 0;
				}
				if($this->itemsX($pl['id']) > 1) {
					#$see1 = 0;
				}
				if($po['frompisher'] > 0) {
					$see1 = 0;
				}
				if($pl['gift'] > 0) {
					$see1 = 0;
				}
				if($po['sudba'] > 0) {
					$see1 = 0;
				}
			}elseif($type == 69) {
				$po['toclan1'] = explode('#',$po['toclan']);
				$po['toclan1'] = $po['toclan1'][0];
				if($po['toclan1'] > 0) {
					$see1 = 0;
				}
				if($po['frompisher'] == 0) {
					$see1 = 0;
				}
				if($pl['gift'] > 0) {
					$see1 = 0;
				}
			}elseif($type == 70) {
				$po['toclan1'] = explode('#',$po['toclan']);
				$po['toclan1'] = $po['toclan1'][0];
				if($po['toclan1'] > 0) {
					$see1 = 0;
				}
				if($po['frompisher'] <= 1) {
					$see1 = 0;
				}
			}
			
			if($see1==1){
				if($k==1){
					$k = 0;
				}else{
					$k = 1;
				}
				$pozonb = 0;
				if(($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27){ //Зоны блока +
					if(!isset($po['zonb'])) { $po['zonb'] = 0; }
					$pozonb = $po['zonb']+1;
				}
				//правая часть
				$mx = '';
				if(isset($po['upatack_lvl'])) {
					$mx .= ' +'.$po['upatack_lvl'];
				}
				if(isset($po['modif'])) {
					$mx .= ' (мф)';
				}
				$col = $this->itemsX($pl['id']);
				if($col>1 && $pl['inGroup']!=0 ){ 
					$pl['kolvo'] = $col;
					$mx .= ' (x'.$col.')';
				}
				if(isset($po['tr_align']) && !isset($po['tr_align_bs'])) {
					$pl['name'] .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$po['tr_align'].'.gif >';
				} elseif(isset($po['tr_align_bs'])) {
					if($po['tr_align_bs'] == '1') {
						$pl['name'] .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align1.75.gif >';
					} elseif($po['tr_align_bs'] == '3') {
						$pl['name'] .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align3.01.gif >';
					}
				}
				if( isset($po['renameadd']) && $po['renameadd'] != '' ) {
					$pl['name'] .= ' (<small>Предмет: '.$po['renameadd'].'</small>)';
				}
				if( isset($po['icos']) ) {
					$pl['name'] = '<span class=icos_'.$po['icos'].' >'.$pl['name'].' <span><small>&nbsp;'.$po['icos'].'&nbsp;</small></span></span>';
				}
				$is2  = '<a oncontextmenu="top.addTo(\''.$pl['id'].'\',\'item\'); return false;" class="inv_name" href="http://xcombats.com/item/'.$pl['item_id'].'" target="_blank">'.$pl['name'].''.$mx.'</a>';
				$is2 .= '&nbsp;&nbsp;';
				if($pl['massa']>0){
					$is2 .= ' (Масса: '.($pl['massa']*$col).')';
				}
				if($pl['gift']!=''){
					$ttl = '';
					if($pl['gift']==1){
						$ttl = 'Вы не можете передать этот предмет кому-либо';
					}else{
						$ttl = 'Этот предмет вам подарил '.$pl['gift'].'. Вы не сможете передать этот предмет кому-либо еще';
					}
					$is2 .= ' <img title="'.$ttl.'" src="http://img.xcombats.com/i/podarok.gif">';
				}
				
				if(isset($po['art'])){
					$is2 .= ' <img title="Артефакт" src="http://img.xcombats.com/i/artefact.gif">';
				}
				
				if(isset($po['sudba'])){
					if($po['sudba']=='0'){
						$is2 .= ' <img title="Этот предмет будет связан общей судьбой с первым, кто наденет его. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif">';
					}elseif($po['sudba']=='1'){
						$is2 .= ' <img title="Этот предмет будет связан общей судьбой с первым, кто возьмет предмет. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif">';
					}else{
						$is2 .= ' <img title="Этот предмет связан общей судьбой с '.$po['sudba'].'. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/desteny.gif">';
					}
				}
				if($pl['price1']>0){ //цена
					$pex = explode('.',$pl['price1']);
					if($pex[1]=='00'){
						$pl['price1'] = $pex[0];
					}
					if($_GET['toRent']==2){
						$is2 .= '<br><b>Цена: <img src=/coin1.png height=11 >&nbsp;'.($pl['price1']).' кр.</b>';
					} else{
						//echo '['.$pl['price1'].']';
						$is2 .= '<br><b>Цена: <img src=/coin1.png height=11 >&nbsp;'.($pl['price1']*$col).' кр.</b>';
					}
				}
				if($pl['pricerep']>0){
					$is2 .= ' <small><b>('.round($pl['pricerep']*$col,2).' Воинственности)</b></small>';
				}
				if($pl['iznosMAX']>0){ //долговечность
					$izcol = '';
					if(floor($pl['iznosNOW'])>=(floor($pl['iznosMAX'])-ceil($pl['iznosMAX'])/100*20)){
						$izcol = 'brown';
					}
	 	 	 	 	 if($pl['iznosMAXi'] == 999999999) {
						$is2 .= '<br>Долговечность: <font color="brown">неразрушимо</font >';
					}else{					
						$is2 .= '<br>Долговечность: <font color="'.$izcol.'">'.floor($pl['iznosNOW']).'/'.ceil($pl['iznosMAX']).'</font>';
					}
				}
				if( $po['battleUseZd'] > 0 ) {
					$is2 .= '<br>Задержка использования: '.$this->timeOut($po['battleUseZd']).'';
				}
				if(isset($po['srok']) && $po['srok'] > 0){
					$pl['srok'] = $po['srok'];
				}
				if($pl['srok'] > 0){ //Срок годности предмета
					$is2 .= '<br>Срок годности: '.$this->timeOut($pl['srok']).' (до '.date('d.m.Y H:i',$pl['time_create']+$pl['srok']).')';
				}
				if($pl['magic_chance'] > 0) {
					$is2 .= '<br>Вероятность срабатывания: '.min(array($pl['magic_chance'],100)).'%';
				} 
				if((int)$pl['magic_inci'] > 0){ //Продолжительность действия магии:
					$efi = mysql_fetch_array(mysql_query('SELECT `id2`,`mname`,`type1`,`img`,`mdata`,`actionTime`,`type2`,`type3`,`onlyOne`,`oneType`,`noAce`,`see`,`info`,`overch`,`bp`,`noch` FROM `eff_main` WHERE `id2` = "'.((int)$pl['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0){
						$is2 .= '<br>Продолжительность действия: '.$this->timeOut($efi['actionTime']);
					}
				}

				$notr = 0;
				if(isset($po['sudba']) && $po['sudba']!='0' && $po['sudba']!=$this->info['login']){
					$notr++;
				}
				//<b>Требуется минимальное:</b>
				if( !isset($po['notr']) ) {
					$tr = ''; $t = $this->items['tr'];
					$x = 0;
					while($x < count($t)){
						$n = $t[$x];
						if(isset($po['tr_'.$n]) && $po['tr_'.$n] != 0) {
							if( $n == 'sex' ) {
								if( $this->info['sex'] != $po['tr_'.$n] ) {
									$tr .= '<font color="red">'; $notr++;
								}
							}elseif($po['tr_'.$n] > $this->stats[$n]) {
								if($n == 'align_bs' && $this->info['inTurnir'] > 0) {
								  if($po['tr_align_bs'] == '1') {
									 if($this->info['align_real'] <= 1 || $this->info['align_real'] >= 2) { $pal = false; } else { $pal = true; }
								   } elseif($po['tr_align_bs'] == '3') {
									 if($this->info['align_real'] <= 3 || $this->info['align_real'] >= 4) { $tar = false; } else { $tar = true; }
								   }
								}
								if($n == 'rep') {
									$temp = explode('::', $po['tr_'.$n]); 
									if($this->rep['rep'.$temp[1]] < $temp[0]) { $tr .= '<font color="red">'; $notr++; } 
									unset($temp);
								}elseif($n == 'align_bs' && $this->info['inTurnir'] > 0 && ($pal = false || $tar = false)) {
									 $tr .= '<font color="red">'; $notr++;
								} elseif($n != 'align' && $n != 'align_bs' || floor($this->info['align']) != $po['tr_'.$n]) {
									$tr .= '<font color="red">'; $notr++;
								}
							}
							$tr .= '<br />• ';
							if($n == 'rep') {
								$temp = explode('::',$po['tr_'.$n]);
								$tr .= $this->is[$n].' '.ucfirst(str_replace('city',' city',$temp[1])).': '.$temp[0];
								unset($temp);
							} elseif($n != 'align' && $n != 'align_bs') {
								if( $n == 'sex' ) {
									if( $po['tr_'.$n] == 1 ) {
										$tr .= $this->is[$n].': Женский';
									}else{
										$tr .= $this->is[$n].': Мужской';
									}
								}else{
									$tr .= $this->is[$n].': '.$po['tr_'.$n];
								}
							} else {
								$tr .= $this->is[$n].': '.$this->align_nm[$po['tr_'.$n]];
							}
							if( $n == 'sex' ) {
								if( $this->info['sex'] != $po['tr_'.$n] ) {
									$tr .= '</font>';
								}
							}elseif($po['tr_'.$n] > $this->stats[$n]) {
							  if($n == 'align_bs' && $this->info['inTurnir'] > 0 && ($pal = false || $tar = false)) {
									 $tr .= '</font>';
							  } elseif($n != 'align' && $n != 'align_bs' || floor($this->info['align']) != $po['tr_'.$n]) {
								$tr .= '</font>';
							  }
							}
						}
						$x++;
					}
					if($tr!=''){
						$is2 .= '<br><b>Требуется минимальное:</b>'.$tr;
					}
				}
				//<b>Действует на:</b>
				$tr = ''; $t = $this->items['add'];
				if(isset($po['mf_stats']) && $po['mf_stats'] > 0) {
					$tr .= '<br>Свободные характеристики: '.$po['mf_stats'];
				}
				if(isset($po['mf_mod']) && $po['mf_mod'] > 0) {
					$tr .= '<br>Свободные модификаторы: '.$po['mf_mod'];
				}
				if(isset($po['mf_mib']) && $po['mf_mib'] > 0) {
					$tr .= '<br>Свободные улучшения брони: '.$po['mf_mib'];
				}
				
				$x = 0;
				while($x<count($t)){
					$n = $t[$x];
					if(isset($po['add_'.$n],$this->is[$n])){
						$z = '+';
						if($po['add_'.$n]<0){
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['add_'.$n];
						if(isset($po['mf_stats']) && $po['mf_stats'] > 0) {
							if($n == 's1' || $n == 's2' || $n == 's3' || $n == 's5') {
								$tr .= ' <a href="main.php?inv=1&otdel='.$_GET['otdel'].'&rstv='.$pl['id'].'&mf='.$n.'"><img src="http://img.xcombats.com/i/up.gif" width="11" height="11"></a>';
							}
						}
						if(isset($po['mf_mod']) && $po['mf_mod'] > 0) {
							if($n == 'm1' || $n == 'm2' || $n == 'm4' || $n == 'm5') {
								$tr .= ' <a href="main.php?inv=1&otdel='.$_GET['otdel'].'&rstv='.$pl['id'].'&mf='.$n.'"><img src="http://img.xcombats.com/i/up.gif" width="11" height="11"></a>';
							}
						}
					}
					$x++;
				}
				//действует на (броня)
				$i = 1; $bn = array(1=>'головы',2=>'корпуса',3=>'пояса',4=>'ног');
				while($i<=4){
					if(isset($po['add_mab'.$i])){
						if($po['add_mab'.$i]==$po['add_mib'.$i] && $pl['geniration']==1){
							$z = '+';
							if($po['add_mab'.$i]<0){
								$z = '';
							}
							$tr .= '<br>• Броня '.$bn[$i].': '.$z.''.$po['add_mab'.$i];
						}else{
							$tr .= '<br>• Броня '.$bn[$i].': '.$po['add_mib'.$i].'-'.$po['add_mab'.$i];
						}
						if(isset($po['mf_mib']) && $po['mf_mib'] > 0) {
							$tr .= ' <a href="main.php?inv=1&otdel='.$_GET['otdel'].'&rstv='.$pl['id'].'&mf=mib'.$i.'"><img src="http://img.xcombats.com/i/up.gif" width="11" height="11"></a>';
						}
					}
					$i++;
				}
				
				if($tr!=''){
					$is2 .= '<br><b>Действует на:</b>'.$tr;
				}
				//<b>Свойства предмета:</b>
				$tr = ''; $t = $this->items['sv'];
				if(isset($po['sv_yron_min'],$po['sv_yron_max'])){
					$tr .= '<br>• Урон: '.$po['sv_yron_min'].' - '.$po['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t)){
					$n = $t[$x];
					if(isset($po['sv_'.$n])){
						$z = '+';
						if($po['sv_'.$n]<0){
							$z = '';
						}
						$tr .= '<br>• '.$this->is[$n].': '.$z.''.$po['sv_'.$n];
					}
					$x++;
				}
				if($pl['2too']==1){
					$tr .= '<br>• Второе оружие';
				}
				if($pl['2h']==1){
					$tr .= '<br>• Двуручное оружие';
				}
				if(isset($po['zonb'])){
					$tr .= '<br>• Зоны блокирования: ';
					if($pozonb>0){
						$x = 1;
						while($x<=$pozonb){
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '—';
					}
				}
				if($tr!=''){
					$is2 .= '<br><b>Свойства предмета:</b>'.$tr;
				}
				
				//Особенности
				$tr = '';
				$x = 1;
				while($x<=4){
					if(isset($po['tya'.$x]) && $po['tya'.$x]>0){
						$tyc = 'Ничтожно редки';
						if($po['tya'.$x]>6){
							$tyc = 'Редки';
						}
						if($po['tya'.$x]>14){
							$tyc = 'Малы';
						}
						if($po['tya'.$x]>34){
							$tyc = 'Временами';
						}
						if($po['tya'.$x]>79){
							$tyc = 'Регулярны';
						}
						if($po['tya'.$x]>89){
							$tyc = 'Часты';
						}
						if($po['tya'.$x]>=100){
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tya'.$x].': '.$tyc.' ('.$po['tya'.$x].'%)';
					}
					$x++;
				}
				$x = 1;
				while($x<=7){
					if(isset($po['tym'.$x]) && $po['tym'.$x]>0){
						$tyc = 'Ничтожно редки';
						if($po['tym'.$x]>6){
							$tyc = 'Редки';
						}
						if($po['tym'.$x]>14){
							$tyc = 'Малы';
						}
						if($po['tym'.$x]>34){
							$tyc = 'Временами';
						}
						if($po['tym'.$x]>79){
							$tyc = 'Регулярны';
						}
						if($po['tym'.$x]>89){
							$tyc = 'Часты';
						}
						if($po['tym'.$x]>=100){
							$tyc = 'Всегда';
						}
						$tr .= '<br>• '.$this->is['tym'.$x].': '.$tyc.' ('.$po['tym'.$x].'%)';
					}
					$x++;
				}
				if($tr!=''){
					$is2 .= '<br><b>Особенности:</b>'.$tr;
				}
				if($notr==0){
					$d[0] = 1;
					if($pl['magic_inci']!='' || $pl['magic_inc']!=''){
						$d[2] = 1;
					}
				}
				
				$tr = '';
				
				if(floor($pl['iznosNOW'])>=ceil($pl['iznosMAX'])){
					$d[0] = 0;
					$d[2] = 0;
				}				
				//Апгрейды вещей
				$tr = '';
				//Встроенная магия
				if($pl['magic_inci']!='' || $pl['magic_inc']!='') {
					if($pl['magic_inc'] == '') {
						$pl['magic_inc'] = $pl['magic_inci'];
					}
					$mgi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$pl['magic_inc'].'" AND `type1` = "12345" LIMIT 1'));
					if(isset($mgi['id2'])) {
						$is2 .= '<div> Встроено заклятие <img height=18 title="'.$mgi['mname'].'" src="http://img.xcombats.com/i/eff/'.$mgi['img'].'"> '.$mgi['minfo'].'</div>';
					}
				}
				
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
					
					$tr .= '<br>&bull; Встроенная руна: <small><font color='.$rnc.'>&bull; <u><b>'.$po['rune_name'].' ['.$po['rune_lvl'].']</b></u></font></small>';
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
					$this->is['mab1'] = 'Броня головы';
					$this->is['mab2'] = 'Броня корпуса';
					$this->is['mab3'] = 'Броня пояса';
					$this->is['mab4'] = 'Броня ног';
					$tr .= '<br>&bull; Встроенно зачарование: <small><font color='.$rnc.'><u><b>'.$po['spell_name'].'</b></u> ('.$this->is[$po['spell_st_name']].': +'.$po['spell_st_val'].')</font></small>';
					unset($this->is['mab1'],$this->is['mab2'],$this->is['mab3'],$this->is['mab4']);
					unset($rnc);
				}
				

				if(isset($po['imposed']) && $po['imposed']>0) {
					if($po['imposed_lvl'] == 0) {
						$rnc = 'maroon';
					}elseif($po['imposed_lvl'] == 1) {
						$rnc = '#624542';
					}elseif($po['imposed_lvl'] == 2) {
						$rnc = '#77090b';
					}elseif($po['imposed_lvl'] == 3) {
						$rnc = '#d99800';
					}else{
						$rnc = '#282828';
					}
					$po['imposed_name'] = str_replace('Чары ','',$po['imposed_name']); 
					$tr .= '<br>&bull; <font color='.$rnc.'>Наложены заклятия:</font> '.$po['imposed_name'].' '; 
					unset($rnc);
				} 
				if($tr!='') {
					$is2 .= '<br><b>Улучшения предмета:</b>';
					$is2 .= $tr;
				}
				
				/*
				if($pl['lvl_itm']>0) {
					$is2 .= '<br><b>Уровень развития</b>: ['.$pl['blvl'].'/100]';
				}
				
				if($pl['so']>0) {
					$is2 .= '<br>&bull; Очки развития предмета:';
										
					$is2 .= '<div style="margin-left:20px;"><small>
					
Сила: '.(0+$po['add_s1']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2=1"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(10+25*$po['add_s1']).' ОР</b></small>
<br>Ловкость: '.$po['add_s2'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2=2"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(10+25*$po['add_s2']).' ОР</b></small>
<br>Интуиция: '.$po['add_s3'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2=3"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(10+25*$po['add_s3']).' ОР</b></small>
<br>Интеллект: '.$po['add_s5'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2=5"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(10+25*$po['add_s5']).' ОР</b></small>

<br>Мф. крит. удара: '.$po['add_m1'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=1"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(1+2*$po['add_m1']).' ОР</b></small>
<br>Мф. против крит. удара: '.$po['add_m2'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=2"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(1+2*$po['add_m2']).' ОР</b></small>
<br>Мф. увертывания: '.$po['add_m4'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=3"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(1+2*$po['add_m4']).' ОР</b></small>
<br>Мф. против увертывания: '.$po['add_m5'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=4"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(1+2*$po['add_m5']).' ОР</b></small>

<br>Броня головы: '.(0+$po['add_mib1']).'-'.(0+$po['add_mab1']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=5"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(5+5*$po['add_mab1']).' ОР</b></small>
<br>Броня головы: '.(0+$po['add_mib2']).'-'.(0+$po['add_mab2']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=6"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(5+5*$po['add_mab2']).' ОР</b></small>
<br>Броня головы: '.(0+$po['add_mib3']).'-'.(0+$po['add_mab3']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=7"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(5+5*$po['add_mab3']).' ОР</b></small>
<br>Броня головы: '.(0+$po['add_mib4']).'-'.(0+$po['add_mab4']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=8"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(5+5*$po['add_mab4']).' ОР</b></small>

<br>Мощность урона: '.$po['add_m10'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=9"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(4+4*$po['add_m10']).' ОР</b></small>
<br>Мощность магии: '.$po['add_m11a'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=11"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(5+5*$po['add_m11a']).' ОР</b></small>

<br>Защита от урона: '.$po['add_za'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=10"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(5+5*$po['add_za']).' ОР</b></small>
<br>Защита от магии: '.$po['add_zm'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs2mf=12"><img src="http://img.xcombats.com/i/plus.gif"></a> <small><b>'.(4+4*$po['add_zm']).' ОР</b></small>

</small></div>';
					
					$is2 .= '&bull; Осталось очков развития: '.$pl['so'].'';
				}
				*/
					
				if(isset($po['free_stats']) && $po['free_stats']>0){
					$is2 .= '<br><b>Распределение статов:</b>';
					$is2 .= '<div style="margin-left:20px;"><small>Сила: '.$po['add_s1'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=1"><img src="http://img.xcombats.com/i/plus.gif"></a><br>Ловкость: '.$po['add_s2'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=2"><img src="http://img.xcombats.com/i/plus.gif"></a><br>Интуиция: '.$po['add_s3'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=3"><img src="http://img.xcombats.com/i/plus.gif"></a><br>Интеллект: '.$po['add_s5'].' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufs=5"><img src="http://img.xcombats.com/i/plus.gif"></a></small></div>';
					$is2 .= '&bull; Осталось распределений: '.$po['free_stats'].'';
				}
				
				if(isset($po['sm_abil'])) {
					//Возможно сохранять и распределять скилы
					$mx2 = 1; $mx1 = 0; $mx3 = $this->lookStats($this->info['stats']);
					while($mx2<=7){
						$mx1 += $mx3['s'.$mx2]-$po['add_s'.$mx2];
						$mx2++;
					}
					$mx1 += $this->info['ability'];
					if($mx1>0) {
						$is2 .= '<br><b>Распределение характеристик:</b>';
						if(isset($po['sudba']) && $po['sudba']=='0') {
							$mx1 = 0;
							$is2 .= '<div style="margin-left:20px;"><small>&bull; Распределение характеристик будет доступно после первого одевания</small></div>';
						}else{
							$is2 .= '<div style="margin-left:20px;"><small>
							Сила: '.(0+$po['add_s1']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=1"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Ловкость: '.(0+$po['add_s2']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=2"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Интуиция: '.(0+$po['add_s3']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=3"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Выносливость: '.(0+$po['add_s4']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=4"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Интеллект: '.(0+$po['add_s5']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=5"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							Мудрость: '.(0+$po['add_s6']).' <a href="?inv=1&itmid='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&ufsmst=6"><img src="http://img.xcombats.com/i/plus.gif"></a><br>
							</small></div>';					
							$is2 .= 'Осталось распределений: '.$mx1;
						}
					}
					unset($mx1,$mx2,$mx3);
				}
				
				if(isset($po['sm_skill'])){
					//Возможно сохранять и распределять скилы
					$mx2 = 1; $mx1 = 0; $mx3 = $this->lookStats($this->info['stats']);
					while($mx2<=7){
						$mx1 += ($mx3['a'.$mx2]+$mx3['mg'.$mx2])-($po['add_a'.$mx2]+$po['add_mg'.$mx2]);
						$mx2++;
					}
					$mx1 += $this->info['skills'];
					if($mx1>0){
						$is2 .= '<br><b>Распределение владений оружием и магией:</b>';
						if(isset($po['sudba']) && $po['sudba']=='0'){
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
							$is2 .= 'Осталось распределений: '.$mx1;
						}
					}
					unset($mx1,$mx2,$mx3);
				}
				
				if(isset($po['complect']) || isset($po['complect2'])){
					$is2 .= '<br><i>Дополнительная информация:</i>';
				}
				if(isset($po['complect'])){
					//не отображается
					$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT `id`,`com`,`name`,`x`,`data` FROM `complects` WHERE `com` = "'.$po['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($plc = mysql_fetch_array($spc)){
						$com1['name'] = $plc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$plc['x'].'</font>: ';
						//действие комплекта
						$i1c = 0; $i2c = 0;
						$i1e = $this->lookStats($plc['data']);
						while($i1c<count($this->items['add'])){
							if(isset($i1e[$this->items['add'][$i1c]])){
								$i3c = $i1e[$this->items['add'][$i1c]];
								if($i3c>0){
									$i3c = '+'.$i3c;
								}
								if($i2c>0){
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
				if(isset($po['complect2'])){
					//не отображается
					$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT `id`,`com`,`name`,`x`,`data` FROM `complects` WHERE `com` = "'.$po['complect2'].'" ORDER BY  `x` ASC LIMIT 20');
					while($plc = mysql_fetch_array($spc)){
						$com1['name'] = $plc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$plc['x'].'</font>: ';
						//действие комплекта
						$i1c = 0; $i2c = 0;
						$i1e = $this->lookStats($plc['data']);
						while($i1c<count($this->items['add'])){
							if(isset($i1e[$this->items['add'][$i1c]])){
								$i3c = $i1e[$this->items['add'][$i1c]];
								if($i3c>0){
									$i3c = '+'.$i3c;
								}
								if($i2c>0){
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
					$is2 .= '<br>&bull; Часть комплекта (подгонка): <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				if($pl['max_text'] > 0) {
					//Инвентарь
					$sm_sp = mysql_query('SELECT `id`,`item_id`,`time`,`login`,`type`,`text`,`city`,`x` FROM `items_text` WHERE `item_id` = "'.$pl['id'].'" ORDER BY `id` ASC  LIMIT 500');
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
				}elseif(isset($po['onitm_text'])) {
					$is2 .= '<div>На предмете записан текст:<br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><div style="background-color:#e8e8e8;padding:5px;"><CODE>'.$po['onitm_text'].'</CODE></div></td>
							</tr>
						</table>
					</div>';
				}
				
				if(isset($po['gravi'])) {
					$is2 .= '<div><img title="'.$this->city_name[$po['gravic']].'" style="vertical-align:bottom" width=34 height=19 src=http://img.xcombats.com/i/city_ico2/'.$po['gravic'].'.gif> На поверхности выгравирована надпись:</div><div align=left style=color:#575757>&nbsp; &nbsp;'.$po['gravi'].'</div>';
				}

				$is2 .= '<small style="">';

				if($pl['info']!=''){
					$is2 .= '<div><b>Описание:</b></div><div>'.$pl['info'].'</div>';
				}

				if(isset($po['info']) && $po['info']!=''){
					$is2 .= '<div>'.$po['info'].'</div>';
				}

				if($pl['maidin']!=''){
					$is2 .= '<div>Сделано в '.$this->city_name[$pl['maidin']].'</div>';
				}
				
				if(isset($po['toclan'])) {
					$po['toclan1'] = explode('#',$po['toclan']);
					$clpo = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.$po['toclan1'][0].'" LIMIT 1'));
					if(isset($clpo['id'])) {
						$is2 .= '<div style="color:brown;">Предмет принадлежит клану <img style="vertical-align:bottom" src="http://img.xcombats.com/i/clan/'.$clpo['name_mini'].'.gif" width="24" height"15"> <b>'.$clpo['name'].'</b>';
						$plpo = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`cityreg` FROM `users` WHERE `id` = "'.$po['toclan1'][1].'" LIMIT 1'));
						if(isset($plpo['id'])) {
							$is2 .= ' <font color=grey>(Дар игрока <b>'.$plpo['login'].'</b><a href="info/'.$plpo['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_'.$plpo['cityreg'].'.gif" width="9"></a>)</font>';
							if( $plpo['login'] == $this->info['login'] ) {
								if( isset($_GET['backmyitm']) && $pl['id'] == $_GET['backmyitm'] ) {
									$pl['inOdet'] = 0;
									$pl['uid'] = $plpo['id'];
									unset($po['toclan1'],$po['toclan']);
									$pl['data'] = $this->impStats($po);
									mysql_query('UPDATE `items_users` SET `data` = "'.mysql_real_escape_string($pl['data']).'",`inOdet` = 0,`uid` = "'.$pl['uid'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
								}
								$is2 .= '<br>(Вы владелец предмета: <a href="main.php?clan&deposit&backmyitm='.$pl['id'].'">Вернуть предмет себе</a>)';
							}
						}
						$is2 .= '</div>';
					}
				}
				if(isset($po['noremont'])){
					$is2 .= '<div style="color:brown;">Предмет не подлежит ремонту</div>';
				}
				if(isset($po['nosale'])){
					$is2 .= '<div style="color:brown;">Предмет нельзя продать</div>';
				}
				if(isset($po['nomodif'])){
					$is2 .= '<div style="color:brown;">Предмет нельзя улучшать</div>';
				}
				if(isset($po['nodelete'])){
					$is2 .= '<div style="color:brown;">Предмет нельзя выбросить</div>';
				}
				if(isset($po['frompisher'])){
					$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
				}
				if(isset($po['sleep_moroz']) && $po['sleep_moroz'] > 0 ) {
					$is2 .= '<div style="color:brown;">Предмет не портится во время сна</div>';
				}
				
				if(isset($po['fromlaba']) && $po['fromlaba']>0){
					$is2 .= '<div style="color:brown;">Предмет из лабиринта</div>';
				}
				
				if(isset($po['vip_sale']) && $po['vip_sale']>0){
					if( $this->stats['slvtm'] > time() && $this->stats['silver'] > 1 ) {
						if( $pl['time_create'] != $this->stats['slvtm'] ) {
							$pl['time_create'] = $this->stats['slvtm'];
							mysql_query('UPDATE `items_users` SET `time_create` = "'.$pl['time_create'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}
						$is2 .= '<div style="color:brown;">Предмет будет недоступен через '.$this->timeOut($pl['time_create']-time()).'</div>';
						//$is2 .= '<div style="color:brown;">Предмет исчезнет через '.$this->timeOut($pl['time_create']-time()+86400*30).'</div>';
					}else{
						$is2 .= '<div style="color:brown;">Предмет недоступен, приобретите конкретный VIP</div>';
						if( ($pl['time_create']-time()+86400*30) < 1 ) {
							$is2 .= '<div style="color:brown;">Предмет рассыпался у Вас на глазах...</div>';
						}else{
							$is2 .= '<div style="color:brown;">Предмет исчезнет через '.$this->timeOut($pl['time_create']-time()+86400*30).'</div>';
						}
					}
				}
				
				if($pl['dn_delete']>0){
					$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
				}	
				
				if( $this->pokol > $pl['geni'] ) {
					$is2 .= '<div style="color:brown">Предмет устарел</div>';
				}
				
				if(isset($po['zazuby']) && $po['zazuby']>0){
					$is2 .= '<div style="color:brown;">Предмет куплен за зубы</div>';
				}
				
				$is2 .= '</small>';
				
				if($col > 1000) {
					$is1 .= '<table border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td><img src="http://img.xcombats.com/i/items/'.$pl['img'].'"></td>
							  </tr>
							  <tr>
								<td align="right"><span style="position:relative;margin-bottom:5px;"><small style="position:absolute;background-color:grey;padding:1px;bottom:1px;right:1px;background-color:#E0E0E0;"><b>x'.$col.'</b></small></span></td>
							  </tr>
							</table>';
				
				}else{				
					$is1 .= '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'" style="margin-bottom:5px;">';
				}

				//$is1 .= '<br><small>(id'.$pl['id'].')</small><br>';
				
				$is1 .= '<br>';

				if($type == 80) {
					$is1 .= '<small><a href="main.php?inv=1&otdel='.floor($_GET['otdel']).'&use_rune='.floor($_GET['use_rune']).'&item_rune=0&item_rune_id='.$pl['id'].'">Выбрать данный предмет</a></small>';
				}elseif($type == 69) {
					if(!isset($po['hprs'])) {
						$po['hprs'] = 0.001;
					}
					if(!isset($po['hprp'])) {
						$po['hprp'] = 0.0001;
					}
					$prs1 = $this->floordec($po['hprs']*$col,2);
					$rps1 = $this->floordec($po['hprp']*$col);
					$is1 .= '<small style="font-size:11px;"><a href="?sale1kr='.$pl['id'].'">Обменять на '.$prs1.' кр.</a><br><a href="?sale1rep='.$pl['id'].'">Обменять на '.$rps1.' ед. репутации</a>';
					$is1 .= '</small>';
				}elseif($type == 68) {
					$is1 .= '<small style="font-size:11px;"><a href="?r=3&itm_take='.$pl['id'].'">Забрать</a><br><a href="?r=3&itm_cancel='.$pl['id'].'">Отказаться</a>';
					if($pl['lastUPD'] > 0) {
						$is1 .= '<br><br>('.$this->timeOut(($pl['lastUPD']+7*24*60*60) - time()).')';
					}
					$is1 .= '</small>';
				}elseif($type == 67) {
					$is1 .= '<small style="font-size:11px;"><a href="javascript:void(0)" onclick="itmToUser('.$pl['id'].')">передать за '.(round(1+$pl['price1']/100*7)*$col).' кр.</a></small>';
				}elseif($type == 66) {
					$pos = explode('#', $po['toclan']);
	 	 	 	 	 $us_1 = $pos[1];
					if($pl['uid'] < 1) {
						$is1 .= '<small style=""><a href="?clan&deposit&take_itm='.$pl['id'].'">Взять предмет</a></small>';
					}else{
						$yui = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`cityreg` FROM `users` WHERE `id` = "'.mysql_real_escape_string($pl['uid']).'" LIMIT 1'));
						$is1 .= '<small>Предмет у игрока<br><b>'.$yui['login'].'</b><a href="info/'.$yui['id'].'" target="_blank"><img width="10" src="http://img.xcombats.com/i/inf_'.$yui['cityreg'].'.gif"></a></small>';
						if($this->info['tt'][14][0] == 1 || ($this->info['id'] == $us_1)) {
						  $is1 .= '<br><small style=""><a href="?clan&deposit&ungive_itm='.$pl['id'].'">Изъять предмет</a></small>';
						}
					}
				}elseif($type == 65) {
					if(!isset($po['toclan'])) {
						$is1 .= '<small style=""><a href="?clan&deposit&give_itm='.$pl['id'].'">Пожертвовать</a></small>';	
					}else{
						$is1 .= '<small style=""><a href="?clan&deposit&give_itm='.$pl['id'].'">Вернуть</a></small>';	
					}
				}elseif($type == 62) {
					$upgitm = mysql_fetch_array(mysql_query('SELECT * FROM `items_upgrade` WHERE `iid` = "'.$pl['item_id'].'" LIMIT 1'));
					if(!isset($upgitm['id'])) {
						$is1 .= '<small style="">Улучшение невозможно</small>';
					}else{
						$ui1dt = mysql_fetch_array(mysql_query('SELECT `id`,`price1`,`price2` FROM `items_main` WHERE `id` = "'.$upgitm['iup'].'" LIMIT 1'));
						$prcup = '';
						if( $upgitm['price1'] == -1 ) {
							$upgitm['price1'] = $ui1dt['price1']-$pl['1price'];
						}
						if( $upgitm['price2'] == -1 ) {
							$upgitm['price2'] = $ui1dt['price2']-$pl['2price'];
						}
						if($upgitm['price1'] > 0) {
							$prcup .= $upgitm['price1'].' кр.';
						}
						if($upgitm['price2'] > 0) {
							if($prcup!='') {
								$prcup .= ' и ';
							}
							$prcup .= $upgitm['price2'].' екр.';
						}
						$is1 .= '<small style=""><a href="?r=8&upgradelvl='.$pl['id'].'">Улучшить за '.$prcup.'</a></small>';
						unset($prcup);
					}
				}elseif($type == 63) {
					$prcup = '';
					$is1 .= '<small style=""><a href="?r=9&upgradelvlcom='.$pl['id'].'">комплект</a></small>';
					unset($prcup);
				}elseif($type == 64) {
					$prcup = '';
					$is1 .= '<small style=""><a href="?r=9">Выбрать другой предмет</a></small>';
					unset($prcup);
				}elseif($type==61) { //улучшение предмета
					if($pl['price1']>$pl['1price']) {
						$pl['1price'] = $pl['price1'];
					}
					$prpod = $pl['1price']/2.37;
					if($pl['price2']>$pl['2price']) {
						$pl['2price'] = $pl['price2'];
					}
					$prpod2 = 30*($pl['2price']/2.37);
					if($prpod2 > $prpod) {
						$prpod = $prpod2;
					}
					if(!isset($po['add_s1']) && !isset($po['add_s2']) && !isset($po['add_s3']) && !isset($po['add_s5'])) {
						$prpod = $prpod/2.37;
					}
					$prpod = round($prpod+$prpod/100*(37.795*($po['upgrade']+1)));
					$is1 .= '<small style=""><a href="?upgrade='.$pl['id'].'&r=6&t=2&rnd='.$code.'">Усилить за '.$prpod.' кр.</a></small>';	
				}elseif($type==60) { //модифицирование
					if($pl['price1']>$pl['1price']) {
						$pl['1price'] = $pl['price1'];
					}
					$prpod = $pl['1price']/2;
					if($pl['price2']>$pl['2price']) {
						$pl['2price'] = $pl['price2'];
					}
					$prpod2 = 30*($pl['2price']/2.37);
					if($prpod2 > $prpod) {
						$prpod = $prpod2;
					}
					if(!isset($po['add_s1']) && !isset($po['add_s2']) && !isset($po['add_s3']) && !isset($po['add_s5'])) {
						$prpod = $prpod/2;
					}
					$prpod = round($prpod);
					$is1 .= '<small style=""><a href="?modif='.$pl['id'].'&r=7&t=2&rnd='.$code.'">Модифицировать за '.$prpod.' кр.</a></small>';				
					
				}elseif($type==59) { //дезинтеграция
					if($po['tr_lvl'] > $pl['level']) {
						$pl['level'] = $po['tr_lvl'];
					}
					$prpod = 5*$pl['level']+35;
					if(isset($po['rune_id'])) {
						$prpod += 3;
					}
					if(isset($po['upatack_id'])) {
						$prpod += 14;
					}
					if(isset($po['spell_id'])) {
						$prpod += 7;
					}
					//$is1 .= '<small style=""><a href="?ubeff='.$pl['id'].'&r=3&t=2&rnd='.$code.'">Дезинтегрировать за '.$prpod.' кр.</a></small>';
					$is1 .= '<small style=""><a href="?ubeff='.$pl['id'].'&r=3&t=2&rnd='.$code.'">Дезинтегрировать за 100 кр.</a></small>';
				}elseif($type==58) { //вытаскивание рун
					if($po['tr_lvl'] > $pl['level']) {
						$pl['level'] = $po['tr_lvl'];
					}
					$prpod = 10*$pl['level']+40;
					if (isset($po['noremont']) or isset($po['frompisher'])) {
						$is1 .= '<small style="">Предмет не подлежит извлечению рун</small>';
					}else{
						$is1 .= '<small style=""><a href="?unrune='.$pl['id'].'&r=4&t=2&rnd='.$code.'">Извлечь руны за '.$prpod.' кр.</a></small>';
					}
				} elseif($type == 57) {
					$prpod = 30;
					if($pl['type'] == 22) { $prpod = 35; } elseif($pl['type'] == 18) { $prpod = 15; }
					if(isset($po['gravi'])) {
			 	 	   $is1 .= '<small style=""><a onclick="top.un_grava(\''.$pl['id'].'\',\''.$pl['name'].'\',\''.$prpod.'.00\',\'data\')" href="javascript:void(0)">Изменить надпись за '.$prpod.' кр.</a></small>';
					} else {
					  $is1 .= '<small style=""><a onclick="top.grava(\''.$pl['id'].'\',\''.$pl['name'].'\',\''.$prpod.'.00\',\'data\')" href="javascript:void(0)">Выгравировать надпись за '.$prpod.' кр.</a></small>';
					}
				} elseif($type==56) { //Подгонка
					if($po['tr_lvl']>$pl['level']) {
						$pl['level'] = $po['tr_lvl'];
					}
					$prpod = 5*$pl['level']+10;
					$prhp = 6*$pl['level']+6;
					$is1 .= '<small style=""><a href="?podgon='.$pl['id'].'&r=5&t=2&rnd='.$code.'">Подогнать за '.$prpod.' кр.</a></small>';
				}elseif($type==15) { //кормушка зверя
					$is1 .= '<a href="main.php?pet=1&obj_corm='.$pl['id'].'&rnd='.$code.'">Кормить</a>';
				}elseif($type==11){
					$pl['rep'] = 0;
					if($this->rep['rep1']<100) {
						if( $po['tr_lvl'] >= 4 && $po['tr_lvl'] <= 6 ) {
							$pl['rep'] = 1;
						}
						if($pl['item_id']==1035){
							$pl['rep'] = 2;
						}
					}elseif($this->rep['rep1']>99) {
						if( $po['tr_lvl'] >= 7 && $po['tr_lvl'] <= 8 ) {
							$pl['rep'] = 1;
						}
						if($pl['item_id']==1035){
							$pl['rep'] = 1;
						}
					}elseif($this->rep['rep1']>999) {
						if( $po['tr_lvl'] >= 9 && $po['tr_lvl'] <= 10 ) {
							$pl['rep'] = 1;
						}
						if($pl['item_id']==1035){
							$pl['rep'] = 1;
						}
					}elseif($this->rep['rep1']>9999) {
						if( $po['tr_lvl'] >= 11 && $po['tr_lvl'] <= 11 ) {
							$pl['rep'] = 1;
						}
						if($pl['item_id']==1035){
							$pl['rep'] = 1;
						}
					}else{
						if($pl['item_id']==1035){
							$pl['rep'] = 2;
						}
					}
					$is1 .= '<a href="javascript:void(0);" onclick="takeItRun(\''.$pl['img'].'\','.$pl['id'].','.$pl['rep'].');">Выбрать</a>';
				}elseif($type==14){
					$is1 .= '<a href="javascript:void(0);" onclick="massTakeItRun(\''.$pl['img'].'\','.$pl['id'].',0);">Выбрать</a>';
				}elseif($type==12){
					$is1 .= '<a href="javascript:void(0);" onclick="takeItRun(\''.$pl['img'].'\','.$pl['id'].',1);">Выбрать</a>';
				}elseif($type==10){
					//Общага (отображение предметов в общаге (под стеклом))
					$is1 .= '<a href="javascript:void(0)" class="obj_take" data-code="'.$code.'" data-room="'.((int)$_GET['room']).'" rel="'.$pl['id'].'">В рюкзак</a>';
				}elseif($type==9){
					//Общага (отображение предметов в инвентаре (под стеклом))
					$is1 .= '<a href="javascript:void(0)" class="obj_add" data-code="'.$code.'" data-room="'.((int)$_GET['room']).'" rel="'.$pl['id'].'">Под стекло</a>';
				 }elseif($type==8){
					 //Общага (отображение предметов в инвентаре)
					 $is1 .= '<a href="javascript:void(0)" class="obj_add" data-code="'.$code.'" data-room="'.((int)$_GET['room']).'" rel="'.$pl['id'].'">В сундук</a>';
				 }elseif($type==7){
					 //Общага (отображение предметов в общаге)
					 $is1 .= '<a href="javascript:void(0)" class="obj_take" data-code="'.$code.'" data-room="'.((int)$_GET['room']).'" rel="'.$pl['id'].'">В рюкзак</a>';
				 }elseif($type==6){
					//Цветочный магазин
					$is1 .= '<a href="main.php?otdel=2&add_item_f='.$pl['id'].'&rnd='.$code.'">Добавить</a>';
				}elseif($type==5){
					//передача
					$is1 .= '<a onClick="saleitem('.$pl['id'].',1); return false;" href="javascript:void(0)">подарить</a><br><a onClick="saleitem('.$pl['id'].',2); return false;" href="#">передать</a><br><small style="font-size:10px">(налог: 1 кр.)</small>';
				}elseif($type==12){
					//передача почта
					$skcd = round($col*($pl['price1']*0.06-0.01*$this->stats['os1']),2);
					if($skcd < 0.06) {
						$skcd = 0.06;
					}
					$is1 .= '<a href="main.php?otdel='.$_GET['otdel'].'&setlogin='.$_REQUEST['setlogin'].'&setobject='.$pl['id'].'&room=2&tmp='.$code.'" onclick="return confirm(\'Передать предмет '.$pl['name'].'?\')">передать&nbsp;за&nbsp;'.(1+$skcd).'&nbsp;кр.</A>';
				}elseif($type==13){
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
				}elseif($type==4){
					//ремонт
					$r1 = round(($pl['price1']*0.06)/100,2);
					$r2 = round(($pl['price1']*0.06)/10,2);
					$r3 = round($pl['price1']*$pl['iznosNOW']*(0.06/100),2);
					
					/*
					if( $this->stats['silver'] >= 5 ) {
						$r1 = round(($r1/100*50),2);
						$r2 = round(($r2/100*50),2);
						$r3 = round(($r3/100*50),2);
					}
					*/
					
					if($r1<0.05){ $r1 = 0.05; }
					if($r2<0.05){ $r2 = 0.05; }
					if($r3<0.05){ $r3 = 0.05; }
					
					$is1 .= '<small style=""><a href="?remon='.$pl['id'].'&t=1&rnd='.$code.'">Ремонт 1 ед. за '.$r1.' кр.</a><br>';
					if($pl['iznosNOW']>=10){$is1 .= '<a href="?remon='.$pl['id'].'&t=2&rnd='.$code.'">Ремонт 10 ед. за '.$r2.' кр.</a><br>';}
					$is1 .= '<a href="?remon='.$pl['id'].'&t=3&rnd='.$code.'">Полный ремонт за '.$r3.' кр.</a></small>';
					if($c['zuby'] == true) {
						if( $this->info['level'] < 8 ) {
							$is1 .= '<hr><small style=""><a onClick="if(!confirm(\'Отремонтировать предмет за зубы?\n(Предмет нельзя будет продать) \')){ return false; }" href="?remonz='.$pl['id'].'&t=1&rnd='.$code.'">Ремонт 1 ед. за '.$this->zuby($r1).'</a><br>';
							if($pl['iznosNOW']>=10){$is1 .= '<a onClick="if(!confirm(\'Отремонтировать предмет за зубы?\n(Предмет нельзя будет продать) \')){ return false; }" href="?remonz='.$pl['id'].'&t=2&rnd='.$code.'">Ремонт 10 ед. за '.$this->zuby($r2).'</a><br>';}
							$is1 .= '<a onClick="if(!confirm(\'Отремонтировать предмет за зубы?\n(Предмет нельзя будет продать) \')){ return false; }" href="?remonz='.$pl['id'].'&t=3&rnd='.$code.'">Полный ремонт за '.$this->zuby($r3).'</a></small>';

						}
					}
				}elseif($type==3){
					$is1 .= '<input type="button" onClick="document.getElementById(\'itemgift\').value='.$pl['id'].';document.F1.submit();" value="Подарить" />';
				}elseif($type==2){
					global $shopProcent; 
					$shpCena = $pl['1price'];
					if( $pl['1price'] == 0 ) {
						$shpCena = $pl['price1'];
					}
					$plmx = 0;
					if($pl['iznosMAXi']!=$pl['iznosMAX'] && $pl['iznosMAX']!=0){
						$plmx = $pl['iznosMAX'];
					}else{
						$plmx = $pl['iznosMAXi'];
					}
					if($pl['iznosNOW']>0){
						$prc1 = floor($pl['iznosNOW'])/ceil($plmx)*100;
					}else{
						$prc1 = 0;
					}
					$shpCena = $this->shopSaleM($shpCena,$pl);
					$shpCena = $shpCena/100*(100-$prc1);
					if( $pl['iznosMAXi'] < 999999999 ) {
						if($pl['iznosMAX']>0 && $pl['iznosMAXi']>0 && $pl['iznosMAXi']>ceil($pl['iznosMAX']))
						{
							$shpCena = $shpCena/100*(ceil($pl['iznosMAX'])/$pl['iznosMAXi']*100);
						}
					}
					$shpCena = $this->round2($shpCena/100*(100-$shopProcent));
					if($shpCena<0){
						$shpCena = 0;
					} 
					if($pl['kolvo']>0){
						$shpCena = $shpCena*$pl['kolvo'];
					}
	 	 	 	 	 if(isset($po['toclan'])) {
						$po['toclan1'] = explode('#',$po['toclan']);
						$us_1 = $po['toclan1'][1];
						if($us_1 != $this->info['id']) {
							$d_s = false;
						} else {
							$d_s = true;
						}
					} else {
						$d_s = true;
					}
					if($d_s == true) {
						$is1 .= '<a href="javascript:void(0)" onClick="if(confirm(\'Продать предмет &quot;'.$pl['name'].'&quot; за '.$shpCena.' кр.?\')){ location = \'main.php?sale&sd4='.$this->info['nextAct'].'&item='.$pl['id'].'&rnd='.$code.'\'; }">Продать за '.$shpCena.' кр.</a>';
						if($pl['pricerep'] > 0) {
							$is1 .= '<br><a href="javascript:void(0)" onClick="if(confirm(\'Обменять предмет &quot;'.$pl['name'].'&quot; на '.floor($pl['pricerep']*$pl['kolvo']).' воинственности?\')){ location = \'main.php?sale&sd4='.$this->info['nextAct'].'&item_rep='.$pl['id'].'&rnd='.$code.'\'; }">Обменять на '.floor($pl['pricerep']*$pl['kolvo']).' Воин. </a>';
						}
	 	 	 	 	 } else {
						$is1 .= 'Это не ваш предмет.';
	 	 	 	 	 }
				}elseif($type==16){
					$shpCena = $pl['price2'];
					if($pl['2price'] > 0 ) {
						$shpCena = $pl['2price'];
					}
					$shpCena = $this->shopSaleM($shpCena,$pl);
					if($pl['kolvo']>0){
						$shpCena = $shpCena*$pl['kolvo'];
					}
					$plmx = 0;
					if($pl['iznosMAXi']!=ceil($pl['iznosMAX']) && ceil($pl['iznosMAX'])!=0){
						$plmx = ceil($pl['iznosMAX']);
					}else{
						$plmx = $pl['iznosMAXi'];
					}
					if($pl['iznosNOW']>0){
						$prc1 = floor($pl['iznosNOW'])/ceil($plmx)*100;
					}else{
						$prc1 = 0;
					}
					$shpCena = $shpCena/100*(100-$prc1);
					if(ceil($pl['iznosMAX'])>0 && $pl['iznosMAXi']>0 && $pl['iznosMAXi']>ceil($pl['iznosMAX'])){
						$shpCena = $shpCena/100*(ceil($pl['iznosMAX'])/$pl['iznosMAXi']*100);
					}
					//$shpCena = $this->round2($shpCena*0.5); // Цена предметов
					if( isset($po['art']) ) {
						$shpCena = $this->round2($shpCena*$this->berezCena()); // Цена арта
					}else{
						$shpCena = $this->round2($shpCena*$this->berezCena()); // Цена
					}
					if($shpCena<0){
						$shpCena = 0;
					}
	 	 	 	 	 if(isset($po['toclan'])) {
						$po['toclan1'] = explode('#',$po['toclan']);
						$us_1 = $po['toclan1'][1];
						if($us_1 != $this->info['id']) {
							$d_s = false;
						} else {
							$d_s = true;
						}
					} else {
						$d_s = true;
					}
	 	 	 	 	 if($d_s == true) {
						$is1 .= '<a href="javascript:void(0)" onClick="if(confirm(\'Продать предмет &quot;'.$pl['name'].'&quot; за '.$shpCena.' екр.?\')){ location = \'main.php?sale&sd4='.$this->info['nextAct'].'&item='.$pl['id'].'&rnd='.$code.'\'; }">Продать за '.$shpCena.' екр.</a>';
					 } else {
						$is1 .= 'Это не ваш предмет.';
					 }
	 	 	 	 }elseif($type==30){
					if(isset($po['toclan'])) {
							$po['toclan1'] = explode('#',$po['toclan']);
							$us_1 = $po['toclan1'][1];
							if($us_1 != $this->info['id']) {
							$d_s = false;
						} else {
							$d_s = true;
						}
					} else {
						$d_s = true;
					}
	 	 	 	 	 if($d_s == true) {
	 	 	 	 	   $is1.= '<form method="POST"><input type="hidden" value="'.$pl['id'].'" name="iid"><input type="text" value="" name="summTR"><input type="submit" value="Сдать в магазин" name="PresTR"></form>'; 
					} else {
	 	 	 	 	   $is1.= 'Это не ваш предмет.';
	 	 	 	 	}
	 	 	 	}elseif($type==31){
					$is1.= '<form method="POST"><input type="hidden" value="'.$pl['id'].'" name="iid"><input type="submit" value="Забрать" name="PresTR"> </form>';
				}elseif($type==70){
					$repob = 1*$pl['kolvo'];
					$is1 .= '<a href="main.php?repsale&repitm='.$pl['id'].'"><small>Обменять на<br>'.$repob.' репутации</small></a>';
				}else{
					if($d[2]==1) { //можно использовать
						$inv1 = '';
						if(isset($_GET['inv'])) {
							$inv1 = 'inv=1&';
						}
						if($pl['item_id']==74){
							$is1 .= '<a onclick="top.addNewSmile('.$pl['id'].',0); return false;" href="javascript:void(0)" title="Использовать">исп-ть</a>';
						}else{
							$useUrl = '';
							if($pl['magic_inc']==''){
								$pl['magic_inc'] = $pl['magic_inci'];
							}
							if($pl['magic_inc'] && $pl['type']==30){
								//используем эликсир
								$pldate = '<table border=\\\'0\\\' width=\\\'100%\\\' cellspacing=\\\'0\\\' cellpadding=\\\'5\\\'><tr><td rowspan=2 width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
								$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\');';
							}elseif($pl['magic_inc'] && $pl['type']==29){ //используем заклятие 
								if(isset($po['useOnLogin']) && !isset($po['zazuby'])){ //на персонажа
									$useUrl = 'top.useMagic(\''.$pl['name'].'\','.(0+$pl['id']).',\''.$pl['img'].'\',1,\'main.php?'.$inv1.'otdel='.((int)$_GET['otdel']).'&use_pid='.$pl['id'].'&rnd='.$code.'\');';
								}else{ //просто использование (на себя, либо без указания предмета\логина)
									$pldate = '<table border=\\\'0\\\' width=\\\'100%\\\' cellspacing=\\\'0\\\' cellpadding=\\\'5\\\'><tr><td rowspan=2 width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
									$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\','.(0+$_GET['otdel']).');';
								}
								//на предмет
							}
							if($useUrl != '') {
								$is1 .= '<a href="javascript:void(0)" onClick="'.$useUrl.'" title="Использовать">исп-ть</a>';
							}else{
								$d[2] = 0;
							}
						}
					}
					
					if($pl['max_text'] > 0 && $pl['max_text']-$pl['use_text'] > 0) {
						$is1 .= '<a onclick="top.addNewText('.$pl['id'].','.($pl['max_text']-$pl['use_text']).','.$pl['inRazdel'].'); return false;" href="javascript:void(0)" title="Записать текст на предмете">Записать</a><br>';
					}
					if($pl['type']==31 || $pl['type']==46 || $pl['type']==62 || $pl['type']==68 || $pl['type']==37 || $pl['type']==47){
						if($d[2]==1){
							$is1 .= '<br>';
						}
						if($pl['type'] != 37 || !isset($po['item_inbox']) || $po['item_inbox'] == 0) {
							$is1 .= '<a href="javascript:void(0);" onClick="top.useRune('.$pl['id'].',\''.$pl['name'].'\',\''.$pl['img'].'\',\'main.php?inv=1&otdel='.((int)$_GET['otdel']).'&use_rune='.$pl['id'].'&rnd='.$code.'\');return false;" title="Использовать">Исп-ть</a><br>';
						}
					}
					
					if($d[0]==1 && $pl['type']!=30 && $pl['type']!=31 && (($pl['type']!=38 && $pl['type']!=39 && $pl['type']!=37) || $pl['gift']!='')) {//можно одеть
						if(!isset($po['noodet']) && $pl['inslot'] > 0){
							if($d[2]==1){
								$is1 .= '<br>';
							}
							$is1 .= '<a href="main.php?otdel='.$pl['inRazdel'].'&inv=1&oid='.$pl['id'].'&rnd='.$code.'" title="Надеть">надеть</a>';
						}
					}
					if(isset($po['open']) && $d[0]==1){
						if($d[2]==1){
							$is1 .= '<br>';
						}
						$is1 .= '<a href="main.php?otdel='.$pl['inRazdel'].'&inv=1&open=1&oid='.$pl['id'].'&rnd='.$code.'" title="Открыть">Открыть</a>';	
					}
					if(isset($po['close'])){
						if($d[2]==1){
							$is1 .= '<br>';
						}
						$is1 .= '<small><b>Предмет закрыт</b></small>';	
					}
					if($pl['group']>0){
						$is1 .= '<br>';
						if($this->itemsX($pl['id']) < $pl['group_max'] ) {
							$is1 .= '<a href="main.php?inv=1&otdel='.((int)$_GET['otdel']).'&stack='.$pl['id'].'&rnd='.$code.'" title="Собрать"><img src="http://img.xcombats.com/i/stack.gif" /></a>';
						}
						if($this->itemsX($pl['id'])>1 ){ 
							$is1 .= ' <a
							onClick="top.unstack('.$pl['id'].',\''.$pl['img'].'\',\''.$pl['name'].'\',1,\'<table border=\\\'0\\\' width=\\\'100%\\\' cellspacing=\\\'0\\\' cellpadding=\\\'5\\\'><tr><td align=\\\'center\\\' rowspan=\\\'2\\\' width=\\\'70px\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></td><td align=\\\'left\\\'>Разделить предмет <b>'.$pl['name'].'</b>?</td></tr></table>\',\''.intval($_GET['otdel']).'\'); return false;"
							href="main.php?inv=1&otdel='.((int)$_GET['otdel']).'&unstack='.$pl['id'].'&rnd='.$code.'" title="Разделить"><img src="http://img.xcombats.com/i/unstack.gif" /></a>'.$script;
						}
					}
					if(isset($po['toclan'])) {
						$po['toclan1'] = explode('#',$po['toclan']);
						$us_1 = $po['toclan1'][1];
						if($us_1 != $this->info['id']) {
							$d[1] = 0;
						}
	 	 	 	 	}
					
					$is1 .= ' <a href="javascript:void(0);" onclick="top.addfastpanel(\''.$pl['id'].'\',\''.$pl['name'].'\',\''.$pl['type'].'\',\''.$pl['1price'].'\',\''.$pl['2price'].'\',\''.$this->city_name[$pl['maidin']].'\',\''.$pl['img'].'\',\''.$pl['item_id'].'\',\''.$pl['iznosNOW'].'\',\''.$pl['iznosMAX'].'\',\''.intval($_GET['otdel']).'\',\''.$d[0].'\',\''.$d[2].'\',\'0\');" title="Добавить в избранное"><img width="16" height="15" src="http://img.xcombats.com/add_itm2.gif"></a> ';
				
					if($d[1]==1) { //можно выкинуть
						if(!isset($po['nodelete'])) {
							$is1 .= ' <a onClick="top.drop('.$pl['id'].',\''.$pl['img'].'\',\''.$pl['name'].'\',1,\'<table border=\\\'0\\\' width=\\\'100%\\\' cellspacing=\\\'0\\\' cellpadding=\\\'5\\\'><tr><td rowspan=2><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></td><td align=\\\'left\\\'>Предмет <b>'.$pl['name'].'</b> будет утерян, вы уверены ?</td></tr></table>\',\''.intval($_GET['otdel']).'\'); return false;" href="javascript:void(0);" title="Выкинуть предмет"><img src="http://img.xcombats.com/i/clear.gif"></a>';
						}
						//$is1 .= ' <img onclick="if (confirm(\'Предмет &quot;'.$pl['name'].'&quot; будет утерян, вы уверены?\')) window.location=\'main.php?inv=1&delete='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&sd4='.$this->info['nextAct'].'&rnd='.$code.'\'" title="Выкинуть предмет" src="http://img.xcombats.com/i/clear.gif" style="cursor:pointer;">';
					}
				}
				
				//собираем все в одно (:
				if( !isset($_GET['backmyitm']) || $pl['id'] != $_GET['backmyitm'] ) {
					$rt[2] .= '<tr class="item"><td align="center" bgcolor="#'.$clr[$k].'"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="100" align="center" style="border-right:#A5A5A5 1px solid; padding:5px;">'.$is1.'</td><td valign="top" align="left" style="padding-left:3px; padding-bottom:3px; padding-top:7px;"><div align="left">'.$is2.'</div></td></tr></table></td></tr>'; 
					$rt[1] += $pl['massa'];
				}
				$i++;
			}
			$j++;
		}
		$rt[0] = $i;
		$rt['collich']=$j;
		return $rt;
	}
	
	public function itemsX($id,$uid = NULL, $item_id=NULL){
		$item = mysql_fetch_assoc(mysql_query('SELECT `iu`.`id`,`iu`.`item_id`,`iu`.`uid`,`iu`.`inGroup`,`iu`.`inShop` FROM `items_users` AS `iu` WHERE `iu`.`delete` = "0" AND `iu`.`id` = "'.((int)$id).'" LIMIT 1 '));
		if($item['inGroup'] == 0){
			$grp = ' LIMIT 1';
		} else {
			$grp = ' LIMIT 1000';
		}
		//$grp = ' LIMIT 1';
		$r = mysql_num_rows(mysql_query('SELECT `iu`.`id` FROM `items_users` AS `iu` WHERE `iu`.`inShop` = "'.$item['inShop'].'" AND `iu`.`item_id` = "'.$item['item_id'].'" AND `iu`.`uid` = "'.($item['uid']).'" AND `iu`.`delete` = "0" AND `iu`.`inGroup` = "'.($item['inGroup']).'" '.$grp.' '));
		/*
		$r = mysql_fetch_array(mysql_query('SELECT COUNT(`iu`.`id`) FROM `items_users` AS `iu` WHERE `iu`.`inShop` = "'.$item['inShop'].'" AND `iu`.`item_id` = "'.$item['item_id'].'" AND `iu`.`uid` = "'.($item['uid']).'" AND `iu`.`delete` = "0" AND `iu`.`inGroup` = "'.($item['inGroup']).'" '.$grp.' '));
		$r = $r[0];
		*/
		unset($item);
		return $r;
	}
	
	 private function stackGroupCheck($uid, $group, $item){ // Находит неиспользованный ID группы предметов.
		$g = 0;
		$i = 0; 
		do {
			$i++;
			$gr = mysql_fetch_array(mysql_query('SELECT `iu`.id, `iu`.inGroup, `iu`.`item_id` FROM `items_users` AS `iu` 
			WHERE `iu`.`uid`="'.$uid.'" AND `iu`.`delete`="0"AND `iu`.`inGroup`="'.$i.'" AND `iu`.item_id="'.$item.'" 
			LIMIT 1'));
			if( empty($gr['inGroup']) ) {
				$g = 1;
			} 
		} while ($g == 0);
		return $i;
	 }
	
	public function stack($id) {
		global $c,$code;
		$where = '';
		$itm = mysql_fetch_array(mysql_query('SELECT 
`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`, count(`iuu`.id) as inGroupCount
		FROM `items_users` AS `iu`
		LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`)
		LEFT JOIN `items_users` as `iuu` ON (`iuu`.inGroup = `iu`.inGroup AND `iuu`.item_id = `im`.id AND `iuu`.inShop = 0)
		WHERE `iu`.`id` = "'.mysql_real_escape_string((int)$id).'" AND `iu`.`uid` = "'.$this->info['id'].'" AND `iuu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" LIMIT 1'));

		if(isset($itm['id']) && $itm['iznosNOW']==0 ) { //группируем похожие свободные предметы с этим
			// создаем группу
			if( $itm['inGroup'] == 0 ) { // Если предмет не в группе, собираем все похожие предметы по группам.
				$items= mysql_num_rows(mysql_query('SELECT `iu`.id, `iu`.inGroup, `iu`.`item_id` FROM `items_users` AS `iu` WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0"AND `iu`.`inGroup`="0" AND `iu`.item_id="'.$itm['item_id'].'"'));
				$items = ceil( $items / $itm['group_max'] );
			} else { // Добираем предметы в группу до полного количества, из предметов с inGroup == 0
				$curG = mysql_num_rows(mysql_query('SELECT `iu`.id, `iu`.inGroup, `iu`.`item_id` FROM `items_users` AS `iu` WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0"AND `iu`.`inGroup`="'.$itm['inGroup'].'" AND `iu`.item_id="'.$itm['item_id'].'"')); // Текущее количество предметов в выбранной группе
				$curNG = mysql_num_rows(mysql_query('SELECT `iu`.id, `iu`.inGroup, `iu`.`item_id` FROM `items_users` AS `iu` WHERE `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0"AND `iu`.`inGroup`="0" AND `iu`.item_id="'.$itm['item_id'].'"')); // Текущее количество предметов которые без группы
				$needG = $itm['group_max']-$curG; // Задаем требуемое количество для добора, если нужно больше 0 и требуемое количество меньше
				
				if( $needG > 0 AND $curNG==0 ) {
					$curItem = mysql_fetch_array(mysql_query('SELECT `iu`.id, `iu`.inGroup, count(`iu`.inGroup) as itemsInGroup, `iu`.`item_id` FROM `items_users` AS `iu` WHERE `iu`.`inGroup`!="'.$itm['inGroup'].'" AND `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `iu`.item_id="'.$itm['item_id'].'" GROUP BY `iu`.inGroup HAVING itemsInGroup <= "'.$needG.'" ORDER BY itemsInGroup DESC LIMIT 1'));
					if(isset($curItem['id']) ){
						$where = ' `iu`.`inGroup` = "'.$curItem['inGroup'].'" AND '; 
					}
					$itm['group_max'] = $needG;
				} else { 
					$itm['group_max'] = $needG;
				} 
				$items = 1;
			}
			$s = 0;
			do {
				if( !isset($curG) AND $curNG==0 ) $itm['inGroup'] = $this->stackGroupCheck($this->info['id'], $itm['inGroup'], $itm['item_id']); 
				$sp = mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl` FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE '.$where.' `iu`.`kolvo` = "1" AND `iu`.`item_id` = "'.$itm['item_id'].'" AND `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" ORDER BY `iu`.`inGroup` ASC LIMIT '.$itm['group_max'].'');
				$i = 0; $j = 0;
				while($pl = mysql_fetch_array($sp)) {
					$pl['data'] = $this->lookStats($pl['data']);unset($pl['data']['frompisher']);$pl['data'] = $this->impStats($pl['data']);
					$itm['data'] = $this->lookStats($itm['data']);unset($itm['data']['frompisher']);$itm['data'] = $this->impStats($itm['data']);
					if( $pl['data']==$itm['data'] && $pl['name']==$itm['name'] && $itm['iznosMAX']==$pl['iznosMAX'] && $pl['iznosNOW']==0 && ($pl['timeOver']==0 || $pl['timeOver']>time()) && $pl['gift']==$itm['gift'] ){
						$upd = mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `inGroup` = "'.$itm['inGroup'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						if($upd){
							$j++;
						}
						$i++;
					}
				}
				mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				$s++;
			} while ($s < $items); 
		}
	}
	
	public function unstack($id,$x = NULL) {
		$id = (int)$id;
		$itm = mysql_fetch_array(mysql_query('SELECT `iu`.id, `iu`.inGroup, `im`.`id` as item_id,`im`.`name`, count(`iuu`.id) as inGroupCount
FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) LEFT JOIN `items_users` as `iuu` ON (`iuu`.inGroup = `iu`.inGroup AND `iuu`.item_id = `im`.id )
WHERE `iuu`.`uid`="'.$this->info['id'].'" AND `iu`.`uid`="'.$this->info['id'].'" AND `iu`.`delete`="0" AND `im`.`group` = "1" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.id='.mysql_real_escape_string((int)$id).'
ORDER BY `iu`.lastUPD DESC
LIMIT 1'));
		if(isset($itm['id']) && $itm['inGroup']>0 && $itm['inGroupCount']>0) {
			if($x==0 OR $x==NULL) {
				$x = $this->itemsX($itm['id']); //кол-во распада
				$inGroup = 0 ;
			} elseif( $x != 0 ) {  
				$inGroup = $this->stackGroupCheck($this->info['id'], $itm['inGroup'], $itm['item_id']);
			} else {
				$inGroup = 0 ;
			}
			$sp = mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl` FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid` = "'.$this->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inGroup` = "'.$itm['inGroup'].'" AND `iu`.`item_id` = "'.$itm['item_id'].'" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`group` = "1" LIMIT '.$x.'');
			$i = 0; $j = 0;
			while($pl = mysql_fetch_array($sp)){
				$upd = mysql_query('UPDATE `items_users` SET `inGroup` = "'.$inGroup.'", `lastUPD` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				if($upd){
					$j++;	
				}
				$i++;
			}
			if( $this->itemsX($itm['id']) == 1){
				mysql_query('UPDATE `items_users` SET `inGroup` = "0", `lastUPD` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			}
			if( $this->itemsX($pl['id']) == 1){
				mysql_query('UPDATE `items_users` SET `inGroup` = "0", `lastUPD` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
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
			if(isset($de[0],$de[1]))
			{
				if(!isset($ist[$de[0]])) {
					$ist[$de[0]] = 0;
				}
				$ist[$de[0]] = $de[1];
			}
			$i++;
		}
		return $ist;
	}
	
	public function testItems($uid, $sn, $dt) {
		global $c, $code;
		$st = false; $rt = false;
		if($uid!=$this->info['id'])
		{
			$u = mysql_fetch_array(mysql_query('SELECT `u`.`align`,`u`.`battle`,`u`.`clan`,`u`.`animal`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`country`,`u`.`country_reg`,`u`.`sex`,`u`.`obraz`,
			`st`.`id`,`st`.`lider`,`st`.`btl_cof`,`st`.`last_hp`,`st`.`last_pr`,`st`.`smena`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`enNow`,`st`.`transfers`,`st`.`regHP`,`st`.`regMP`,`st`.`showmenu`,`st`.`prmenu`,`st`.`ability`,`st`.`skills`,`st`.`sskills`,`st`.`nskills`,`st`.`exp`,`st`.`exp_eco`,`st`.`minHP`,`st`.`minMP`,`st`.`zv`,`st`.`dn`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`last_a`,`st`.`last_b`,`st`.`battle_text`,`st`.`upLevel`,`st`.`wipe`,`st`.`bagStats`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`nextAct`,`st`.`active`,`st`.`bot`,`st`.`lastAlign`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`s`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`ref_data`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`bn_capitalcity`,`st`.`bn_demonscity`
			FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
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
			$cl = mysql_query('SELECT
			`iu`.`id` AS `iduid`,
			`iu`.`time_sleep`,`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
			`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
			FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`iu`.`inOdet`!="0" OR `iu`.`data` LIKE "%srok%" OR `iu`.`data` LIKE "%vip_sale%" OR `iu`.`data` LIKE "%sudba=1%" OR ( `iu`.`data` LIKE "%zazuby=%" AND `iu`.`data` NOT LIKE "%srok=%" ) OR `iu`.`iznosNOW` > 0 OR `im`.`srok` > 0 OR (`iu`.`timeOver`<'.time().' AND `iu`.`timeOver`!="0")) AND `iu`.`uid`="'.$u['id'].'" AND (`iu`.`delete`="0" OR `iu`.`delete`="1000")');
			while($itm = mysql_fetch_array($cl)) {
				$po = array();
				$po = $this->lookStats($itm['data']);
				$po['lvl'] = $u['level'];
				//проверяем требования				
				$t = $this->items['tr'];
				$x = 0;
				$notr = 0;
				$j = 1;
				while($j<=4){
					if(!isset($po['tr_s'.$j]) && $itm['type']!=26){
						$po['tr_s'.$j] = 0;
					}
					$j++;
				}
				if(!isset($po['notr'])) {
					while($x < count($t)) {
					  $n = $t[$x];
					  if( isset($po['tr_'.$n]) && $n == 'sex' ) {
						  if( $po['tr_'.$n] != $this->info['sex'] ) {
							 $notr++; 
						  }
					  }elseif(isset($po['tr_'.$n])) {
						if($po['tr_'.$n] > $this->stats[$n] && $n != 'align' && $n != 'align_bs') {
						  $notr++;
						}
					  }
					  $x++;
					}
					if($this->info['twink']>0) {
						//Не дороже 100 екр.
						if( $itm['price2'] > 1 || $itm['2price'] > 1 ) {
							$notr++;
						}
						//Нельзя руны + чарки
						if( isset($po['rune']) && $po['rune'] > 0 ) {
							$notr++;
						}
						if( isset($po['spell_id']) && $po['spell_id'] > 0 ) {
							$notr++;
						}
					}
				}
				if(isset($po['sudba']) && $po['sudba'] == 1 ) {
					$po['sudba'] = $u['login'];
					$itm['data'] = $this->impStats($po);
					mysql_query('UPDATE `items_users` SET `data` = "'.$itm['data'].'" WHERE `id` = "'.$itm['iduid'].'" AND `uid` = "'.$u['id'].'" LIMIT 1');
				}
				if(isset($po['zazuby']) && !isset($po['srok']) && ( $itm['type'] == 29 || $itm['type'] == 30 || $itm['type'] == 36 || $itm['type'] == 49 || $itm['type'] == 46 || $itm['type'] == 34 ) ) {
					//Предметы за зубы со сроком годности
					$po['srok'] = 86400*7;
					$itm['data'] = $this->impStats($po);
					mysql_query('UPDATE `items_users` SET `data` = "'.$itm['data'].'" WHERE `id` = "'.$itm['iduid'].'" AND `uid` = "'.$u['id'].'" LIMIT 1');
				}
				if(isset($po['srok']) && $po['srok'] > 0){
					$itm['srok'] = $po['srok'];
				}
				if(isset($po['vip_sale'])) {
					if($sn['silver'] < 2 && ($itm['time_create']-time()) < 0) {
						if( $itm['item_id'] == 4704 ) {
							$po['musor2'] = 4708;
						}
						$this->isport($itm['id'],$itm['timeOver'],$itm['overType'],$u['id'],(int)$po['musor2'],$itm['type'],$itm['name'],$po['zazuby']);
						$notr++;
					}
				}
				if($itm['iznosNOW']>=ceil($itm['iznosMAX']) && $itm['iznosMAXi'] != 999999999){
					$notr++;	
				}
				if($notr > 0 && $itm['inOdet'] != 0) {
					//снимаем предмет
					$this->snatItem($itm['id'],$u['id']);
					$snIt++;
				}
				//проверяем срок годности
				if($itm['iznosNOW']>=ceil($itm['iznosMAX']) && $itm['iznosMAXi'] != 999999999){
					//предмет сломался
					if(isset($po['musor'])){
						if($po['musor']>0){
							$this->recr($itm['id'],$itm['type'],$u['id'],(int)$po['musor']);
						}
					}
				}
				if($itm['time_create']+$itm['srok'] <= time() && $itm['srok']>0 && $itm['time_sleep'] == 0){
					if($itm['inOdet']!=0){
						$this->snatItem($itm['id'],$u['id']);
						$snIt++;
					}
					//удаляем предмет
					if( $itm['item_id'] == 4704 ) {
						$po['musor2'] = 4708;
					}
					$this->isport($itm['id'],$itm['timeOver'],$itm['overType'],$u['id'],(int)$po['musor2'],$itm['type'],$itm['name']);
				}elseif($itm['time_create']+$itm['srok'] <= time() && $itm['srok']>0){
					echo 'test';
				}
			}		
			
			if($snIt>0){
				$this->testItems($uid,$sn,1);
			}elseif($dt==0){
				return -2;
			}
		}else{
			return 0;
		}
	}
	
	public function recr($id,$tp,$uid,$id2){
		if($id!=0){
			if($uid!=0){
				$uid2 = 'AND `uid`="'.$uid.'"';
			}else{
				$uid2 = '';
			}
			$upd = mysql_query('UPDATE `items_users` SET `delete`="'.time().'" WHERE `id` = "'.$id.'" '.$uid2.' LIMIT 1');
			if($upd){
				$this->addDelo(2,$uid,'&quot;<font color="maroon">System.inventory</font>&quot;: Предмет [itm:'.$it.'] был <b>сломан</b>.',time(),$this->info['city'],'System.inventory',0,0);
				if($id2>1){
					//Добавляем пустую бутылку
					$this->addItem($id2,$uid,'noodet=1|noremont=1');
				}
			}
		}
	}
	
	public function isport($it,$t,$tp,$uid,$id2,$type,$name,$zub){
		if($id2 == 4708 ) {
			$tp = 1;	
		}
		if($it!=0){
			if($uid!=0){
				$uid2 = 'AND `uid`="'.$uid.'"';
			}else{
				$uid2 = '';
			}
			$upd = mysql_query('UPDATE `items_users` SET `delete`="'.time().'",`timeOver`="1" WHERE `id` = "'.$it.'" '.$uid2.' LIMIT 1');
			if($upd){
				$upd = mysql_query('UPDATE `items_users` SET `inGroup`="0",`timeOver`="'.time().'" WHERE `inGroup` = "'.$it.'" '.$uid2.'');
				$this->addDelo(2,$uid,'&quot;<font color="maroon">System.inventory</font>&quot;: Предмет <b>'.$name.'</b> [itm:'.$it.'] был <b>испорчен</b>.',time(),$this->info['city'],'System.inventory',0,0);
				if($tp!=0){
					//Добавляем испорченый предмет в инвентарь, в зависимости от типа
					$zzba = '';
					if( $zub > 0 ) {
						$zzba = '|zazuby=1';
					}
					$po = $this->lookStats($this->stats['items'][$i]['data']);
					if($id2>0){
						if($id2 == 4708 ) {
							$this->addItem($id2,$uid,'|notransfer=1|nosale=1'.$zzba);
						}else{
							$this->addItem($id2,$uid,'|noodet=1'.$zzba);
						}
					}else{
						if( $type == 30 ) {
							//испорченный эликсир
							$this->addItem(4036,$uid,'|renameadd='.$name.'|noodet=1'.$zzba);
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
			$sv[$i] = '<img class="nopriemuse" title="Пустой слот заклятия" src="http://img.xcombats.com/i/items/w/w101.gif" />';
			$i++;
		}
		$i = 0;
		while($i < count($this->stats['items'])) {
			if($this->stats['items'][$i]['inslot'] == 40 || $this->stats['items'][$i]['inslot'] == 51) {
				if($this->stats['items'][$i]['useInBattle']==0 || $this->stats['items'][$i]['btl_zd']>0 || $this->stats['items'][$i]['iznosNOW']>=$this->stats['items'][$i]['iznosMAX'] || $this->stats['items'][$i]['magic_inci']=='' || $this->stats['items'][$i]['magic_inci']=='0') {
					$vl = ' class="nopriemuse"';
				}else{
					$po = $this->lookStats($this->stats['items'][$i]['data']);
					if($po['useOnLogin']==1) {
						$useUrl = 'top.useMagicBattle(\''.$this->stats['items'][$i]['name'].'\','.$this->stats['items'][$i]['id'].',\''.$this->stats['items'][$i]['img'].'\',1,1,\'\',\''.$this->stats['items'][$i]['useInBattle'].'\');';
					}else{
						$useUrl = 'top.useMagicBattle(\''.$this->stats['items'][$i]['name'].'\','.$this->stats['items'][$i]['id'].',\''.$this->stats['items'][$i]['img'].'\',1,2);';
					}
					$vl = 'style="cursor:pointer" onclick="'.$useUrl.'"';
				}
				$sv[$this->stats['items'][$i]['inOdet']-39] = '<img '.$vl.' title="Долговечность: '.floor($this->stats['items'][$i]['iznosNOW']).'/'.floor($this->stats['items'][$i]['iznosMAX']).'" src="http://img.xcombats.com/i/items/'.$this->stats['items'][$i]['img'].'" />';
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
	
	public function nameItemMf($pl,$po) {
		$r = $pl['name'];
		if( isset($po['icos']) ) {
			$r = '<span class=icos_'.$po['icos'].' >'.$pl['name'].' <span style=font-size:8px>&nbsp;'.$po['icos'].'&nbsp;</span></span>';
		}
		//if( $this->info['admin'] > 0 ) {
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
				$r .= '<br><small><font color='.$rnc.'>&bull; Руна: <b>'.$po['rune_name'].' ['.$po['rune_lvl'].']</b></font></small>';
				unset($rnc);
			}
			if( isset($po['spell']) ) {
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
				$r .= '<br><small><font color='.$rnc.'>&bull; '.$po['spell_name'].'</font></small>';
				unset($rnc);
			}
		return $r;
	}
	
	public function getInfoPers($uid,$i1,$sn = 0,$ivv = 0){
		global $c,$code;
		
		$st = false; $rt = false; $type_info = 1;
		if($uid!=$this->info['id']){
			$u = mysql_fetch_array(mysql_query('SELECT `u`.`inTurnir`,`u`.`allLock`,`u`.`battle`,`u`.`zag`,`u`.`banned`,`u`.`align`,`u`.`clan`,`u`.`animal`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`country`,`u`.`country_reg`,`u`.`sex`,`u`.`obraz`,
			`st`.`id`,`st`.`lider`,`st`.`btl_cof`,`st`.`last_hp`,`st`.`last_pr`,`st`.`smena`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`enNow`,`st`.`transfers`,`st`.`regHP`,`st`.`regMP`,`st`.`showmenu`,`st`.`prmenu`,`st`.`ability`,`st`.`skills`,`st`.`sskills`,`st`.`nskills`,`st`.`exp`,`st`.`exp_eco`,`st`.`minHP`,`st`.`minMP`,`st`.`zv`,`st`.`dn`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`last_a`,`st`.`last_b`,`st`.`battle_text`,`st`.`upLevel`,`st`.`wipe`,`st`.`bagStats`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`nextAct`,`st`.`active`,`st`.`bot`,`st`.`lastAlign`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`s`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`ref_data`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`bn_capitalcity`,`st`.`bn_demonscity`
			FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
			if($sn==0){
				$sn = $this->getStats($uid,$i1);
			}
		}else{
			$u  = $this->info;
			if(isset($this->stats['hpAll'])){
				$sn = $this->stats;
			}elseif($sn!=0){
			}else{
				$sn = $this->getStats($uid,$i1);
			}
		}

		$tp_img = array(
			1 => 4,
			2 =>5,
			14 => 6,
			3 => 7,
			5 => 8,
			7 => 9,
			17 => 10,
			16 => 11,
			13 => 12,
			10 => 13,
			9 => 14,
			8 => 15,
			11 => 17, //кольцо 2
			12 => 18 //кольцо 3		
		);

		if(isset($u['id'],$u['stats'])){
			//if( $u['inTurnir'] > 0 ) {
			//	$type_info = 2;
			//}
			$rt = array(0=>'',1=>array());
			$st = array();
			$st['id'] = $u['id'];
			$st['login'] = $u['login'];
			$st['lvl'] = $u['level'];

			//Характеристики от предметов и их изображение
			$witm	 = array();
			$witm[1] =  '<img width="60" height="60" style="display:block;" title="Пустой слот шлем" src="http://img.xcombats.com/i/items/w/w9.gif">';
			$witm[2] =  '<img width="60" height="40" style="display:block;" title="Пустой слот наручи" src="http://img.xcombats.com/i/items/w/w13.gif">';
			$witm[3] =  '<img width="60" height="60" style="display:block;" title="Пустой слот оружие" src="http://img.xcombats.com/i/items/w/w3.gif">';
			$witm[4] =  '<img width="60" height="80" style="display:block;" title="Пустой слот броня" src="http://img.xcombats.com/i/items/w/w4.gif">';
			$witm[7] =  '<img width="60" height="40" style="display:block;" title="Пустой слот пояс" src="http://img.xcombats.com/i/items/w/w5.gif">';
			$witm[8] =  '<img width="60" height="20" style="display:block;" title="Пустой слот серьги" src="http://img.xcombats.com/i/items/w/w1.gif">';
			$witm[9] =  '<img width="60" height="20" style="display:block;" title="Пустой слот ожерелье" src="http://img.xcombats.com/i/items/w/w2.gif">';
			$witm[10] = '<img width="20" height="20" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[11] = '<img width="20" height="20" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[12] = '<img width="20" height="20" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[13] = '<img width="60" height="40" style="display:block;" title="Пустой слот перчатки" src="http://img.xcombats.com/i/items/w/w11.gif">';
			$witm[14] = '<img width="60" height="60" style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w10.gif">';
			$witm[16] = '<img width="60" height="80" style="display:block;" title="Пустой слот поножи" src="http://img.xcombats.com/i/items/w/w19.gif">';
			$witm[17] = '<img width="60" height="40" style="display:block;" title="Пустой слот обувь" src="http://img.xcombats.com/i/items/w/w12.gif">';
			//40-52 слот под магию		
			$witm[53] = '<img width="40" height="20" style="display:block;" title="Пустой слот правый карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[54] = '<img width="40" height="20" style="display:block;" title="Пустой слот левый карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[55] = '<img width="40" height="20" style="display:block;" title="Пустой слот центральный карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[56] = '<img width="40" height="20" style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$witm[57] = '<img width="40" height="20" style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$witm[58] = '<img width="40" height="20" style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$cl = mysql_query('SELECT 
			`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
			`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
			FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`inOdet`!="0" AND `iu`.`uid`="'.$u['id'].'" AND `iu`.`delete`="0"');
			$wj = array(1=>false,2=>false,4=>false,5=>false,6=>false);
			$b1 = '<br>';
			
			while($pl = mysql_fetch_array($cl))
			{				
				$td = $this->lookStats($pl['data']);	
				
				if(isset($td['modif'])) {
					$pl['name'] = $pl['name'].' (мф)';
				}
				
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
				
				$pl['name'] = $this->nameItemMf($pl,$td);
												
				$lvar = '';
				if(isset($td['add_hpAll']) && $td['add_hpAll']!=0)
				{
					if($td['add_hpAll']!=0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$lvar .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if(isset($td['sv_yron_max']) && $td['sv_yron_max']>0)
				{
					$lvar .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if(isset($td['add_mab1']) && $td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $pl['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$lvar .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$lvar .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if(isset($td['add_mab2']) && $td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $pl['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$lvar .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$lvar .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if(isset($td['add_mab3']) && $td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $pl['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$lvar .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$lvar .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if(isset($td['add_mab4']) && $td['add_mab4']>0)
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
	 	 	 	 	 if($pl['iznosMAXi'] == 999999999) {
						$lvar .= '<br>Долговечность: <font color=brown>неразрушимо</font > ';
					}else{					
						$lvar .= '<br>Долговечность: '.floor($pl['iznosNOW']).'/'.ceil($pl['iznosMAX']);
					}
				}
				
				if( $po['battleUseZd'] > 0 ) {
					$is2 .= '<br>Задержка использования: '.$this->timeOut($po['battleUseZd']).'';
				}
				
				if(isset($td['gravi'])) {
					$td['gravitp'] = array(
						18 => 'кинжале',
						19 => 'топоре',
						20 => 'молоте',
						21 => 'клинке',
						22 => 'посохе',
						23 => 'луке',
						24 => 'арбалете',
						26 => 'рукоятке',
						27 => 'предмете',
						28 => 'предмете'
					);
					$lvar .= '<br>На '.$td['gravitp'][$pl['type']].' выгравирована надпись: '.$td['gravi'].'';
					unset($td['gravitp']);
				}
				
				/*
				if( $pl['inOdet'] == 3 ) {
					if( $pl['2h'] == 1 ) {
						if(@isset($sn['items_img'][$tp_img[14]])) {
							$uimg2 = 'rimg/r'.$sn['items_img'][$tp_img[14]];
							$witm[14] = '<img width="60" height="60" style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/'.$uimg2.'">';
						}else{
							$uimg2 = 'i/items/'.$pl['img'].'';
							$witm[14] = '<img width="60" height="60" style="background-image:url(http://img.xcombats.com/i/items/w/w10.gif);display:block;filter:alpha(opacity=37);opacity:0.37;-moz-opacity:0.37;-khtml-opacity:0.37;" title="Пустой слот щит" src="http://img.xcombats.com/'.$uimg2.'">';
						}
						unset($uimg2);
					}
				}
				*/
								
				if(@isset($sn['items_img'][$tp_img[$pl['inOdet']]])) {
					$uimg = 'rimg/r'.$sn['items_img'][$tp_img[$pl['inOdet']]];
				}else{
					$uimg = 'i/items/'.$pl['img'].'';
				}
				
				$witm[$pl['inOdet']]  = '<img style="display:block;" src="http://img.xcombats.com/'.$uimg.'" onMouseOver="top.hi(this,\'<b>'.$pl['name'].'</b>'.$lvar.'\',event,3,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();">';
				
				if($i1==1){
					$witm[$pl['inOdet']] = '<a href="http://xcombats.com/item/'.$pl['item_id'].'" target="_blank">'.$witm[$pl['inOdet']].'</a>';
				}else{
					if($pl['inOdet']>=40 && $pl['inOdet']<=52 && !isset($_GET['inv'])){
						$useUrl = '';
						if($pl['magic_inc']==''){
							$pl['magic_inc'] = $pl['magic_inci'];
						}
						if($pl['magic_inc'] && $pl['type']==30){
							//используем эликсир
							$pldate = '<table border=\\\'0\\\' width=\\\'100%\\\' cellspacing=\\\'0\\\' cellpadding=\\\'5\\\'><tr><td rowspan=2 width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
							$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\');';
						}elseif($pl['magic_inc'] && $pl['type']==29){
							//используем заклятие
							//на персонажа
							if(isset($td['useOnLogin'])){
								$inv1 = '';
								if(isset($_GET['inv'])) {
									$inv1 = 'inv=1&otdel='.((int)$_GET['otdel']).'&';
								}
								$useUrl = 'top.useMagic(\''.$pl['name'].'\','.(0+$pl['id']).',\''.$pl['img'].'\',1,\'main.php?'.$inv1.'use_pid='.$pl['id'].'&rnd='.$code.'\');';
							}else{
								//просто использование (на селя, либо без указания предмета\логина)
								$pldate = '<table border=\\\'0\\\' width=\\\'100%\\\' cellspacing=\\\'0\\\' cellpadding=\\\'5\\\'><tr><td rowspan=2 width=\\\'80\\\' valign=\\\'middle\\\'><div align=\\\'center\\\'><img src=\\\'http://img.xcombats.com/i/items/'.$pl['img'].'\\\'></div></td><td valign=\\\'middle\\\' align=\\\'left\\\'>&quot;<b>'.$pl['name'].'</b>&quot;<br>Использовать сейчас?</td></tr></table>';
								$useUrl = 'top.useiteminv(\''.(0+$pl['id']).'\',\''.$pl['img'].'\',\''.$pl['img'].'\',1,\''.$pldate.'\',\''.(0+$_GET['otdel']).'\');';
							}				
						}
						$witm[$pl['inOdet']] = '<a href="javascript:void(0);" onClick="'.$useUrl.'">'.$witm[$pl['inOdet']].'</a>';
					}elseif($pl['item_id']==998 && !isset($_GET['inv'])){
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
			if($wj[52] != false) { $wj1i .= $br; }
				$td = array();							
				$td = $this->lookStats($wj[1]['data']);
				$wj[1]['name'] = $this->nameItemMf($wj[1],$td);
				$wj1i .= '<b>'.$wj[1]['name'].'</b>';	
				if(isset($td['add_hpAll']) && $td['add_hpAll']!=0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj1i .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if(isset($td['sv_yron_max']) && $td['sv_yron_max']>0)
				{
					$wj1i .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if(isset($td['add_mab1']) && $td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj1i .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$wj1i .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if(isset($td['add_mab2']) && $td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj1i .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$wj1i .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if(isset($td['add_mab3']) && $td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj1i .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$wj1i .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if(isset($td['add_mab4']) && $td['add_mab4']>0)
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
	 	 	 	 	 if($wj[1]['iznosMAXi'] == 999999999) {
						$wj1i .= '<br>Долговечность: <font color=brown>неразрушимо</font > ';
					}else{					
						$wj1i .= '<br>Долговечность: '.floor($wj[1]['iznosNOW']).'/'.ceil($wj[1]['iznosMAX']).'';
					}
				}
			}
			if(isset($wj[52]) && $wj[52]!=false)
			{
				$td = $this->lookStats($wj[52]['data']);
				$wj[52]['name'] = $this->nameItemMf($wj[52],$td);
				$wj1i = $wj1i;

				if($wj[52]['iznosMAX']>0)
				{
	 	 	 	 	 if($wj[52]['iznosMAXi'] == 999999999) {
						$wj1i = '<br>Долговечность: <font color=brown>неразрушимо</font ><br>'.$wj1i;
					}else{					
						$wj1i = '<br>Долговечность: '.floor($wj[52]['iznosNOW']).'/'.ceil($wj[52]['iznosMAX']).''.$wj1i;
					}
				}
				
				if(isset($td['add_mab4']) && $td['add_mab4']>0)
				{
					if($td['add_mib4']==$td['add_mab4'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab4']<0){ $m1l = ''; }
						$wj1i = '<br>Броня ног: '.$m1l.''.(0+$td['add_mab4']).''.$wj1i;
					}else{
						$wj1i = '<br>Броня ног: '.(0+$td['add_mib4']).'-'.(0+$td['add_mab4']).''.$wj1i;
					}
				}

				if(isset($td['add_mab3']) && $td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj1i = '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).''.$wj1i;
					}else{
						$wj1i = '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).''.$wj1i;
					}
				}
				
				if(isset($td['add_mab2']) && $td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj1i = '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).''.$wj1i;
					}else{
						$wj1i = '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).''.$wj1i;
					}
				}
				
				if(isset($td['add_mab1']) && $td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[1]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj1i = '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).''.$wj1i;
					}else{
						$wj1i = '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).''.$wj1i;
					}
				}
				
				if(isset($td['add_hpAll']) && $td['add_hpAll']!=0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj1i = '<br>Уровень жизни: '.$td['add_hpAll'].''.$wj1i;
				}
				
				if(isset($td['sv_yron_max']) && $td['sv_yron_max']>0)
				{
					$wj1i = '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].''.$wj1i;
				}
				
				$wj1i = '<b>'.$wj[52]['name'].'</b>'.$wj1i;
				$wj[1]['img'] = $wj[52]['img'];
				$wj[1]['id']  = $wj[52]['id'];
				$wj[1]['inRazdel'] = $wj[52]['inRazdel'];
			}
			//Рубаха,Броня,Плащ
			$wj4idd = $wj[5]['item_id'];
			$wj4i = '';
			if($wj[6]!=false)
			{
				$td = array();	
				$td = $this->lookStats($wj[6]['data']);
				$wj[6]['name'] = $this->nameItemMf($wj[6],$td);
				$wj4i .= '<b>'.$wj[6]['name'].'</b>';				
				if($td['add_hpAll']!=0)
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
	 	 	 	 	 if($wj[6]['iznosMAXi'] == 999999999) {
						$wj4i .= '<br>Долговечность: <font color=brown>неразрушимо</font > ';
					}else{					
						$wj4i .= '<br>Долговечность: '.floor($wj[6]['iznosNOW']).'/'.ceil($wj[6]['iznosMAX']).'';
					}
				}
				if($wj[5]!=false || $wj[4]!=false)
				{
					$wj4i .= $br;
				}
			}
			if($wj[5]!=false)
			{
				$td = array();	
				$td = $this->lookStats($wj[5]['data']);	
				$wj[5]['name'] = $this->nameItemMf($wj[5],$td);
				$wj4i .= '<b>'.$wj[5]['name'].'</b>';				
				if($td['add_hpAll']!=0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj4i .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if(isset($td['sv_yron_max']) && $td['sv_yron_max']>0)
				{
					$wj4i .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if(isset($td['add_mab1']) && $td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[5]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$wj4i .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if(isset($td['add_mab2']) && $td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[5]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$wj4i .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if(isset($td['add_mab3']) && $td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[5]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$wj4i .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if(isset($td['add_mab4']) && $td['add_mab4']>0)
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
	 	 	 	 	 if($wj[5]['iznosMAXi'] == 999999999) {
						$wj4i .= '<br>Долговечность: <font color=brown>неразрушимо</font > ';
					}else{					
						$wj4i .= '<br>Долговечность: '.floor($wj[5]['iznosNOW']).'/'.ceil($wj[5]['iznosMAX']).'';
					}
				}
				if($wj[4]!=false)
				{
					$wj4i .= $br;
				}
			}
			if($wj[4]!=false)
			{
				$td = array();	
				$td = $this->lookStats($wj[4]['data']);
				$wj[4]['name'] = $this->nameItemMf($wj[4],$td);
				$wj4i .= '<b>'.$wj[4]['name'].'</b>';					
				if(isset($td['add_hpAll']) && $td['add_hpAll']!=0)
				{
					if($td['add_hpAll']>0)
					{
						$td['add_hpAll'] = '+'.$td['add_hpAll'];
					}
					$wj4i .= '<br>Уровень жизни: '.$td['add_hpAll'].'';
				}
				if(isset($td['sv_yron_max']) && $td['sv_yron_max']>0)
				{
					$wj4i .= '<br>Урон: '.$td['sv_yron_min'].'-'.$td['sv_yron_max'].'';
				}
				if(isset($td['add_mab1']) && $td['add_mab1']>0)
				{
					if($td['add_mib1']==$td['add_mab1'] && $wj[4]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня головы: '.$m1l.''.(0+$td['add_mab1']).'';
					}else{
						$wj4i .= '<br>Броня головы: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
					}
				}
				if(isset($td['add_mab2']) && $td['add_mab2']>0)
				{
					if($td['add_mib2']==$td['add_mab2'] && $wj[4]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня корпуса: '.$m1l.''.(0+$td['add_mab2']).'';
					}else{
						$wj4i .= '<br>Броня корпуса: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
					}
				}
				if(isset($td['add_mab3']) && $td['add_mab3']>0)
				{
					if($td['add_mib3']==$td['add_mab3'] && $wj[4]['geniration']==1)
					{
						$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
						$wj4i .= '<br>Броня пояса: '.$m1l.''.(0+$td['add_mab3']).'';
					}else{
						$wj4i .= '<br>Броня пояса: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
					}
				}
				if(isset($td['add_mab4']) && $td['add_mab4']>0)
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
	 	 	 	 	 if($wj[4]['iznosMAXi'] == 999999999) {
						$wj4i .= '<br>Долговечность: <font color=brown>неразрушимо</font > ';
					}else{					
						$wj4i .= '<br>Долговечность: '.floor($wj[4]['iznosNOW']).'/'.ceil($wj[4]['iznosMAX']).'';
					}
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
				if(isset($sn['items_img'][$tp_img[1]])) {
					$uimg = 'rimg/r'.$sn['items_img'][$tp_img[1]];
				}else{
					$uimg = 'i/items/'.$wj[1]['img'].'';
				}
				$witm[1] = '<img style="display:block;" src="http://img.xcombats.com/'.$uimg.'" onMouseOver="top.hi(this,\''.$wj1i.'\',event,3,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">';
				if($i1==1)
				{
					$witm[1] = '<a href="http://xcombats.com/item/'.$wj[1]['item_id'].'" target="_blank">'.$witm[1].'</a>';
				}else{
					$witm[1] = '<a href="main.php?otdel='.$wj[1]['inRazdel'].'&inv=1&sid='.$wj[1]['id'].'&rnd='.$code.'">'.$witm[1].'</a>';
				}	
			}
			if($wj[4]!=false || $wj[5]!=false || $wj[6]!=false)
			{
				if(isset($sn['items_img'][$tp_img[5]])) {
					$uimg = 'rimg/r'.$sn['items_img'][$tp_img[5]];
				}else{
					$uimg = 'i/items/'.$wj[4]['img'].'';
				}
				$witm[4] = '<img style="display:block;" src="http://img.xcombats.com/'.$uimg.'" onMouseOver="top.hi(this,\''.$wj4i.'\',event,3,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">';
				if($i1==1)
				{
					if( $wj4idd > 0 ) {
						$wj[4]['item_id'] = $wj4idd;
					}
					$witm[4] = '<a href="http://xcombats.com/item/'.$wj[4]['item_id'].'" target="_blank">'.$witm[4].'</a>';
				}else{
					$witm[4] = '<a href="main.php?otdel='.$wj[4]['inRazdel'].'&inv=1&sid='.$wj[4]['id'].'&rnd='.$code.'">'.$witm[4].'</a>';
				}	
			}
			/*------------ ГЕНЕРИРУЕМ ИНФ. О ПЕРСОНАЖЕ ---------------*/
			$nmmsgl1 = 0;
			if($u['align'] >= 1 && $u['align'] < 2) {
				$nmmsgl1 = 1;
			}elseif($u['align'] >= 3 && $u['align'] < 4) {
				$nmmsgl1 = 3;
			}elseif($u['align'] >= 7 && $u['align'] < 8) {
				$nmmsgl1 = 7;
			}elseif($u['align'] >= 50 && $u['align'] < 50) {
				$nmmsgl1 = 60;
			}
			if($u['admin'] > 0) {
				$nmmsgl1 = 60;
			}
			if(@isset($sn['items_img'][2])) {
				$msl = '<img width="120" height="40" style="display:block" src="http://img.xcombats.com/rimg/r'.$sn['items_img'][2].'">';
			}else{
				$msl = '<img width="120" height="40" style="display:block" src="http://img.xcombats.com/i/slot_bottom'.$nmmsgl1.'.gif">';
			}
			unset($nmmsgl1);
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
				$an = mysql_fetch_array(mysql_query('SELECT `id`,`type`,`name`,`uid`,`delete`,`inBattle`,`eda`,`exp`,`obraz`,`stats`,`level`,`sex`,`levelUp`,`pet_in_cage`,`max_exp`,`priems`,`bonus` FROM `users_animal` WHERE `id` = "'.$u['animal'].'" AND `pet_in_cage` = 0 AND `delete` = "0" LIMIT 1'));
				if(isset($an['id']))
				{
					if($i1 != 1){
						$anml = '<div style="position:absolute; width:40px; height:73px; z-index:3; top:147px; left:80px;"><a href="main.php?pet"><img height="73" width="40" src="http://'.$c['img'].'/i/obraz/'.$an['sex'].'/'.$an['obraz'].'.gif" title="'.$an['name'].' ['.$an['level'].'] (Посмотреть образ)"></a></div>';
					}else{
						if( $an['eda'] > 0 ) {
							$anml = '<div style="position:absolute; width:40px; height:73px; z-index:3; top:147px; left:80px;"><img height="73" width="40" src="http://'.$c['img'].'/i/obraz/'.$an['sex'].'/'.$an['obraz'].'.gif" title="'.$an['name'].' ['.$an['level'].']"></div>';
						}
					}
				}
			}
			$eff = '';
			//-------- генерируем эффекты
			$efs = mysql_query('SELECT 
			`eu`.`id`,`eu`.`id_eff`,`eu`.`uid`,`eu`.`tr_life_user`,`eu`.`name`,`eu`.`data`,`eu`.`overType`,`eu`.`timeUse`,`eu`.`timeAce`,`eu`.`user_use`,`eu`.`delete`,`eu`.`v1`,`eu`.`v2`,`eu`.`img2`,`eu`.`x`,`eu`.`hod`,`eu`.`bj`,`eu`.`sleeptime`,`eu`.`no_Ace`,
			`em`.`id2`,`em`.`mname`,`em`.`type1`,`em`.`img`,`em`.`mdata`,`em`.`actionTime`,`em`.`type2`,`em`.`type3`,`em`.`onlyOne`,`em`.`oneType`,`em`.`noAce`,`em`.`see`,`em`.`info`,`em`.`overch`,`em`.`bp`,`em`.`noch`
			 FROM `eff_users` AS `eu` LEFT JOIN `eff_main` AS `em` ON (`eu`.`id_eff` = `em`.`id2`) WHERE `eu`.`uid`="'.mysql_real_escape_string($u['id']).'" AND `delete`="0" AND `deactiveTime` < "'.time().'" ORDER BY `deactiveTime` DESC,`timeUse` ASC');
			while($e = mysql_fetch_array($efs))
			{
				$esee = 1;
				if($e['see']==0 && $i1==1)
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
				if($e['img'] == '') {
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
						$ei .= 'Осталось: '.$out.'';
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
					$eff .= '<img width="38" height="23" style="margin:1px;display:block;float:left;" src="http://img.xcombats.com/i/eff/'.$e['img'].'"onMouseOver="top.hi(this,\''.$ei.'\',event,0,1,1,1,\'\');" onMouseOut="top.hic(event);" onMouseDown="top.hic(event);" >';
				}elseif($e['timeUse']+$e['timeAce']+$e['actionTime']<time() && $e['timeUse']!=77)
				{
					//удаляем эффект
					$ed = $this->lookStats($e['data']);
					if(!isset($ed['finish_file']) || $this->info['id'] == $e['uid']) {
						$this->endEffect($e['id'],$u);
					}
				}
			}
			
			$ebns = mysql_fetch_array(mysql_query('SELECT * FROM `users_bonus` WHERE `id` = "'.$u['id'].'" LIMIT 1'));
			if(isset($ebns['id'])) {
				if( $ebns['haot'] > 0 && $i1 == 0 ) {
					$ei = '<b><u>Повышенный опыт (x'.$ebns['haot'].')</u></b> (Бонус)<br>Получаемый опыт (%): +'.$ebns['haot'].'<br>Осталось: <i>Бесконечно</i>';
					$eff .= '<img width="38" height="23" style="margin:1px;display:block;float:left;" src="http://img.xcombats.com/i/eff/1inv.gif"onMouseOver="top.hi(this,\''.$ei.'\',event,0,1,1,1,\'\');" onMouseOut="top.hic(event);" onMouseDown="top.hic(event);" >';
				}
			}
			
			if($sn['itmslvl'] == 0) {
				//$ei = '<b><u>Легкое вооружение</u></b> (Эффект)<br>Осталось: <i>Бесконечно</i>';
				//$eff .= '<img width="38" height="23" style="margin:1px;display:block;float:left;" src="http://img.xcombats.com/i/eff/light_armor.gif"onMouseOver="top.hi(this,\''.$ei.'\',event,0,1,1,1,\'\');" onMouseOut="top.hic(event);" onMouseDown="top.hic(event);" >';
			}
			
			//здоровье
			if( $type_info == 1 ) {
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
			}elseif( $type_info == 2 ) {
				$hptop = 0;
				$lh = array(0=>'hp_none',1=>1);
				$lh[1] = floor((0+$sn['hpNow'])/(0+$sn['hpAll'])*200);
				if($lh[1]>0){  $lh[0] = 'hp_1'; }
				if($lh[1]>32){ $lh[0] = 'hp_2'; }
				if($lh[1]>65){ $lh[0] = 'hp_3'; }
				if($sn['mpAll']>0)
				{

					//мана
					$lm = array(0=>'hp_none',1=>1);
					$lm[1] = floor($sn['mpNow']/$sn['mpAll']*200);
					if($lm[1]>0){ $lm[0] = 'hp_mp'; }
					$hpmp .='<div id="vmp'.$u['id'].'" title="Уровень маны" align="left" class="seemp" style="position:absolute; top:10px; width:200px; height:10px; z-index:12;"> '.floor($sn['mpNow']).'/'.(0+$sn['mpAll']).'</div>
							 <div title="Уровень маны" class="hpborder" style="position:absolute; top:10px; width:200px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
							 <div class="'.$lm[0].' senohp" style="height:9px; position:absolute; top:10px; width:'.$lm[1].'px; z-index:11;" id="lmp'.$u['id'].'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
							 <div title="Уровень маны" class="hp_none" style="position:absolute; top:10px; width:200px; height:10px; z-index:10;"></div>';
				}else{
					$hptop = 5;
				}
				$hpmp = '<div id="vhp'.$u['id'].'" title="Уровень жизни" align="left" class="seehp" style="position:absolute; top:'.$hptop.'px; width:200px; height:10px; z-index:12;"> '.floor($sn['hpNow']).'/'.(0+$sn['hpAll']).'</div>
						 <div title="Уровень жизни" class="hpborder" style="position:absolute; top:'.$hptop.'px; width:200px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
						 <div class="'.$lh[0].' senohp" style="height:9px; width:'.$lh[1].'px; position:absolute; top:'.$hptop.'px; z-index:11;" id="lhp'.$u['id'].'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
						 <div title="Уровень жизни" class="hp_none" style="position:absolute; top:'.$hptop.'px; width:200px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div>'.$hpmp;
				//Собираем НР и МР
				$hpmp = '<div style="position:relative;height:20px;">'.$hpmp.'</div>';
			}
			
			$lgn = '<b>'.$u['login'].'</b> ['.$u['level'].']<a href="/info/'.$u['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif"></a>';
			if($u['clan']!=0)
			{
				$pc = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`name_mini`,`align`,`type_m`,`money1`,`exp` FROM `clan` WHERE `id`="'.$u['clan'].'" LIMIT 1'));
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
				$pb .= '<div style="margin:2px;"><font color="red"><b>Персонаж заблокирован</b></font></div>';
			}
			if($u['allLock'] > time()) {
				$pb .= '<div style="margin:2px;"><font color="red"><b>Временный запрет передач!</b></font></div>';
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
			
			if($ssm>0 && $i1==0)
			{
				$witmg .= '<table style="padding-top:2px;padding-bottom:2px;" width="240" border="0" cellspacing="0" cellpadding="0">
  <tr>
	 <td width="60" height="60">'.$witm[59].'</td>
	 <td width="60">'.$witm[60].'</td>
	 <td width="60">'.$witm[61].'</td>
	 <td width="60">'.$witm[62].'</td>
  </tr>
</table>';
			}
			
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
			$zag = '';
			if($u['zag']!='' && $i1 == 1) {
				$rt[0] .= '<style> .inf2s { position:relative; filter: alpha(opacity=10); -moz-opacity: 0.10; -khtml-opacity: 0.10; opacity: 0.10; } .inf2s:hover { background-color:#e2e0e0;filter: alpha(opacity=70); -moz-opacity: 0.70; -khtml-opacity: 0.70; opacity: 0.70; } </style>';
				
				$zag = '<img width="243" height="283" style="position:absolute;top:-1px;left:-1px;" src="http://img.xcombats.com/i/zag/'.$u['zag'].'">';
/*
				$witmn[1] = '<img style="display:block;" title="Пустой слот шлем" src="http://img.xcombats.com/i/items/w/w9.gif">';
				$witmn[2] = '<img style="display:block;" title="Пустой слот наручи" src="http://img.xcombats.com/i/items/w/w13.gif">';
				$witmn[3] = '<img style="display:block;" title="Пустой слот оружие" src="http://img.xcombats.com/i/items/w/w3.gif">';
				$witmn[4] = '<img style="display:block;" title="Пустой слот броня" src="http://img.xcombats.com/i/items/w/w4.gif">';
				$witmn[7] = '<img style="display:block;" title="Пустой слот пояс" src="http://img.xcombats.com/i/items/w/w5.gif">';
				$witmn[8] = '<img style="display:block;" title="Пустой слот серьги" src="http://img.xcombats.com/i/items/w/w1.gif">';
				$witmn[9] = '<img style="display:block;" title="Пустой слот ожерелье" src="http://img.xcombats.com/i/items/w/w2.gif">';
				$witmn[10] = '<img style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
				$witmn[11] = '<img style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
				$witmn[12] = '<img style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
				$witmn[13] = '<img style="display:block;" title="Пустой слот перчатки" src="http://img.xcombats.com/i/items/w/w11.gif">';
				$witmn[14] = '<img style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w10.gif">';
				$witmn[16] = '<img style="display:block;" title="Пустой слот поножи" src="http://img.xcombats.com/i/items/w/w19.gif">';
				$witmn[17] = '<img style="display:block;" title="Пустой слот обувь" src="http://img.xcombats.com/i/items/w/w12.gif">';
*/
				$j2 = 0;
				while($j2 <= 17) {
					$witm[$j2] = '<div class="inf2s">'.$witm[$j2].'</div>';
					$j2++;
				}
				
				$eff = $eff;
				
			}
			//<div style="width:240px; padding:2px; border-bottom:1px solid #666666; border-right:1px solid #666666; border-left:1px solid #FFFFFF; border-top:1px solid #FFFFFF;">
			if( $type_info == 1 ) {
				$rt[0] .= '<div id="lgnthm" style="width:246px; padding:2px;" align="center">'.$lgn.'</div>
				<div style="width:240px; padding:2px; border-bottom:1px solid #666666; border-right:1px solid #666666; border-left:1px solid #FFFFFF; border-top:1px solid #FFFFFF;">
				<div align="center"><!-- blocked -->'.$pb.'</div>
				<table width="240" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="60" valign="top">
					<table width="60" height="280" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td height="60"><div style="position:relative">'.$zag.''.$witm[1].'</div></td>
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
						<td valign="top">';
						if($zag == ''){
							if($i1 == 0) {
								if(!isset($_GET['inv'])) {
									$invg = array(0=>'main.php?inv=1',1=>'Рюкзак');
								}else{
									$invg = array(0=>'main.php?skills=1',1=>'Умения');	
								}
							}else{
								$o = mysql_fetch_array(mysql_query('SELECT `id` FROM `obraz` WHERE `img` = "'.mysql_real_escape_string($this->info['obraz']).'" AND `sex` = "'.$this->info['sex'].'" LIMIT 1'));
								$invg = array(0=>'http://xcombats.com/shadow/'.$o['id'].'" target="_blank',1=>'Галерея образов');
							}
							$uobr = 'i/obraz/'.$u['sex'].'/'.$u['obraz'].'';
							if(isset($sn['items_img'][1])) {
								$uobr = 'rimg/r'.$sn['items_img'][1];
							}
							$rt[0] .= '<div style="position:relative;height:220px;">
								<!-- образ -->
									<div style="position:absolute; width:120px; height:220px; z-index:1;"><a href="'.$invg[0].'"><img onMouseOver="top.hi(this,\'Перейти в &quot;<b>'.$invg[1].'</b>&quot;\',event,2,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();" width="120" height="220" src="http://img.xcombats.com/'.$uobr.'" '.$oi.'></a></div>
									<div style="position:absolute; width:120px; height:auto; z-index:3;" align="left">'.$eff.'</div>'.$anml.'
							</div>';
						}else{
							if($i1 == 0) {
								if(!isset($_GET['inv'])) {
									$invg = array(0=>'main.php?inv=1',1=>'Рюкзак');
								}else{
									$invg = array(0=>'main.php?skills=1',1=>'Умения');	
								}
							}else{
								$invg = array(0=>'http://lib.xcombats.com/obraz.php?namez='.$u['zag'].'" target="_blank',1=>'Галерея образов');
							}
							$rt[0] .= '<div class="inf2s" style="position:relative;height:220px;">
								<!-- образ -->
									<div style="position:absolute; width:120px; height:220px; z-index:1;"><a href="'.$invg[0].'"><img onMouseOver="top.hi(this,\'Перейти в &quot;<b>'.$invg[1].'</b>&quot;\',event,2,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();" width="120" height="220" src="http://img.xcombats.com/1x1.gif" '.$oi.'></a></div>
									<div style="position:absolute; width:120px; height:auto; z-index:3;" align="left">'.$eff.'</div>'.$anml.'
							</div>';
						}
						
						unset($invg);
						
						$rt[0] .= '</td>
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
				</table>'.$witmg.'</div>';
			}elseif($type_info == 2) {
				$rt[0] .= '<div style="width:209px; padding:2px;" align="center">'.$lgn.'</div>
				<div style="width:196px; padding:2px;">
				<div align="center"><!-- blocked -->'.$pb.'</div>
				<div align="center">
				<!-- HP and MP -->
				'.$hpmp.'
				<!-- -->
				</div>
				<table width="196" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="60" valign="top">
					<table width="60" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td height="20"><div style="position:relative">'.$zag.''.$witm[8].'</div></td>
					  </tr>
					  <tr>
						<td height="20">'.$witm[9].'</td>
					  </tr>
					  <tr>
						<td height="20">'.$witm[3].'</td>
					  </tr>
					  <tr>
						<td height="80">'.$witm[4].'</td>
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
					</table>
					</td>
					<td valign="top">
					<table width="76" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td valign="top">';
						if($zag == ''){
						if($i1 == 0) {
							if(!isset($_GET['inv'])) {
								$invg = array(0=>'main.php?inv=1',1=>'Рюкзак');
							}else{
								$invg = array(0=>'main.php?skills=1',1=>'Умения');	
							}
						}else{
							$invg = array(0=>'http://lib.xcombats.com/obraz.php?name='.$u['obraz'].'" target="_blank',1=>'Галерея образов');
						}
						$u['obraz'] = '0.gif';
						$uobr = 'i/obraz/'.$u['sex'].'/old/'.$u['obraz'].'';
						if(isset($sn['items_img'][1])) {
							$uobr = 'rimg/r'.$sn['items_img'][1];
						}
						$rt[0] .= '<div style="position:relative;height:209px;">
							<!-- образ -->
								<div style="position:absolute; width:76px; height:209px; z-index:1;"><a href="'.$invg[0].'"><img onMouseOver="top.hi(this,\'Перейти в &quot;<b>'.$invg[1].'</b>&quot;\',event,2,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();" width="76" height="209" src="http://img.xcombats.com/'.$uobr.'" '.$oi.'></a></div>
								<div style="position:absolute; width:76px; height:auto; z-index:3;" align="left">'.$eff.'</div>'.$anml.'
						</div>';}
						
						unset($invg);
						
						$rt[0] .= '</td>
					  </tr>
					</table>
					</td>
					<td width="60" valign="top">
					<table width="60" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td height="60">'.$witm[1].'</td>
					  </tr>
					  <tr>
						<td height="40">'.$witm[13].'</td>
					  </tr>
					  <tr>
						<td height="60">'.$witm[14].'</td>
					  </tr>
					  <tr>
						<td height="40">'.$witm[17].'</td>
					  </tr>
					</table>
					</td>
				  </tr>
				</table>'.$witmg.'</div>';
			}
			
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
	
	public function endEffect($id,$u,$test)
	{
		if($test == false) {
			$test = 0;
		}
		
		$e = mysql_fetch_array(mysql_query('SELECT
		`eu`.`id`,`eu`.`tr_life_user`,`eu`.`id_eff`,`eu`.`uid`,`eu`.`name`,`eu`.`data`,`eu`.`overType`,`eu`.`timeUse`,`eu`.`timeAce`,`eu`.`user_use`,`eu`.`delete`,`eu`.`v1`,`eu`.`v2`,`eu`.`img2`,`eu`.`x`,`eu`.`hod`,`eu`.`bj`,`eu`.`sleeptime`,`eu`.`no_Ace`,
		`em`.`id2`,`em`.`mname`,`em`.`type1`,`em`.`img`,`em`.`mdata`,`em`.`actionTime`,`em`.`type2`,`em`.`type3`,`em`.`onlyOne`,`em`.`oneType`,`em`.`noAce`,`em`.`see`,`em`.`info`,`em`.`overch`,`em`.`bp`,`em`.`noch`
		FROM `eff_users` AS `eu` LEFT JOIN `eff_main` AS `em` ON (`eu`.`id_eff` = `em`.`id2`) WHERE `eu`.`id`="'.mysql_real_escape_string($id).'" AND `delete`="0" AND `deactiveTime` < "'.time().'"'));
		
		if(isset($e['id']))
		{		
			$sleep = $this->testAction('`vars` = "sleep" AND `uid` = "'.$e['uid'].'" LIMIT 1', 1);
			if( $e['id_eff'] == 2 ) {
				//Проверка
				$ev = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_main` WHERE `name` = "'.mysql_real_escape_string(str_replace('Изучение: ','',$e['name'])).'" LIMIT 1'));
				$et = mysql_fetch_array(mysql_query('SELECT `id` FROM `actions` WHERE `uid` = "'.$e['uid'].'" AND `vars` LIKE "%read%" AND `vals` = "'.$ev['id'].'" LIMIT 1'));	
				if(isset($et['id'])) {
					if( $et['time'] < time() ) {
						$et = false;
					}else{
						$et = true;
					}
				}else{
					$et = false;
				}
			}else{
				$et = false;
			}
			if($et == false && $e['sleeptime'] == 0 && $sleep['vars'] != 'sleep') {
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
						mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('".$u['city']."','".$u['room']."','','".$u['login']."','".$text."','-1','6','0',1)");
						if($u['battle']>0) {
							$lastHOD = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type` FROM `battle_logs` WHERE `battle` = "'.$u['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
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
		$itm = mysql_fetch_array($cl = mysql_query('SELECT
		`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
		FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id`="'.mysql_real_escape_string($id).'" AND `iu`.`inOdet`!="0" '.$au.' AND `iu`.`delete`="0" LIMIT 1 FOR UPDATE'));
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
	
	public function odetItem($id, $uid) {
		if($uid != 0) {
		  $au = 'AND `iu`.`uid` = "'.mysql_real_escape_string($uid).'"';
		} else {
		  $au = '';
		}
		
		$itm = mysql_fetch_array(mysql_query('SELECT
		`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
		`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
		FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id`="'.mysql_real_escape_string($id).'" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" '.$au.' AND `iu`.`delete`="0" LIMIT 1'));
		if(isset($itm['id'])) {
		  if($itm['group'] == 1) {
			if($this->itemsX($itm['id']) > 1) {
					$this->unstack($itm['id'],1);
			  		/*$itm = mysql_fetch_array(mysql_query('SELECT
					`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
					`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
					FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`inOdet`="0" AND `iu`.`inShop`="0" '.$au.' AND `iu`.`delete`="1000" AND `iu`.`inGroup` = "'.$itm['id'].'" LIMIT 1'));
					if(!isset($itm['id'])) {
						$this->error = 'Группа предметов ошибочна...';
						$notr++;
					} else {
						$itm['delete'] = 0;
					}*/					
				}
			}
			
			
			$tr = $this->lookStats($itm['data']);
			$notr = $this->trItem($tr);
			$msb = '';
			//if(isset($tr['art']) && $this->stats['art']>=2) {
				//$this->error = 'Вы не можете надеть более двух артефактов';
				//$notr++;
			//}
			if($this->info['twink']>0) {
				//Не дороже 100 екр.
				if( $itm['price2'] > 100 || $itm['2price'] > 100 ) {
					$notr++;
				}
				//Нельзя руны + чарки
				if( isset($tr['rune']) && $tr['rune'] > 0 ) {
					$notr++;
				}
				if( isset($tr['spell_id']) && $tr['spell_id'] > 0 ) {
					$notr++;
				}
			}
			if(isset($tr['vip_sale'])) {
				if($this->stats['silver'] < 2) {
					$notr++;
				}
			}
			if(isset($tr['sudba'])) {
				if($tr['sudba'] != '0' && $tr['sudba'] != $this->info['login']) {
					$notr++;
				} elseif($tr['sudba'] == '0'){
					$tr['sudba'] = $this->info['login'];
					$itm['data'] = $this->impStats($tr);
					$msb = ',`data`="'.$itm['data'].'"';
				}
			}
			if(isset($tr['tr_align_bs']) && $this->info['inTurnir'] > 0) {
			  if($tr['tr_align_bs'] == '1') {
				if($this->info['align_real'] <= 1 || $this->info['align_real'] >= 2) { $notr++; } else { $notr = 0; }
			  } else {
				if($this->info['align_real'] <= 3 || $this->info['align_real'] >= 4) { $notr++; } else { $notr = 0; }
			  }
			}
			
			if( isset($tr['notr']) ) {
				$notr = 0;
			}
			
			if($notr > 0) {
				//Не хватает характеристик или не совпадают условия
				if(isset($tr['open']) && isset($_GET['open'])) {
					$this->error = 'Вы не можете открыть данный предмет';
				} else {
					$this->error = 'Вы не можете надеть данный предмет';
				}
				return 0;
			}elseif(isset($tr['open']) && isset($_GET['open']) && isset($tr['items_in_file']))
			{
				$io = '';
				if($itm['inGroup'] > 0) {
					mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'",`inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
				if(file_exists('_incl_data/class/magic/'.$tr['items_in_file'].'.php')){
					require('_incl_data/class/magic/'.$tr['items_in_file'].'.php');
					if(!isset($no_open_itm)) {
						$this->deleteItem($itm['id'],$this->info['id']);
						$this->error = 'Вы успешно открыли &quot;'.$itm['name'].'&quot;:<br>'.$io.'...';
					}else{
						unset($no_open_itm);
					}					
				}else{
					$this->error = 'Предмет &quot;'.$itm['name'].'&quot; невозможно открыть...';
				}
			}elseif(isset($tr['open']) && isset($_GET['open']))
			{
				//открываем предмет
				$io = '';
				$i = 0;
				$itms = explode(',',$tr['items_in']);
				
				
				if($itm['type']==37) {
					//Распаковываем упаковку
					$io = '';
					$itmin = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `id` = "'.$tr['item_inbox'].'" LIMIT 1'));
					$itmmn = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$itmin['item_id'].'" LIMIT 1'));
					//
					if($itm['gift'] == '' || $itm['gift'] == '0') {
						$this->error = 'Предмет должен быть подарен, прежде чем его открывать!';
					}elseif(!isset($itmin['id'])) {
						$this->error = 'В упаковке ничего нет, скорее всего предмет кто-то вытащил от туда...';
					}else{
						$io .= $itmmn['name'];
						if($itmin['item_id'] == 4867) {
							//Не откроешь
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							$this->error = 'Не удалось открыть подарок. Содержимое испорчено.';
						}elseif($itmin['item_id'] == 4868) {
							//Летучая мышь
							mysql_query('UPDATE `stats` SET `hpNow` = 1,`mpNow` = 1 WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							$this->error = 'Вы потеряли все HP...';
						}elseif($itmin['item_id'] == 4870) {
							//Минута молчания
							if( $this->info['molch1'] > time() ) {
								$this->info['molch1'] += 3600;
							}else{
								$this->info['molch1'] = time()+3600;
							}
							mysql_query('UPDATE `users` SET `molch1` = "'.$this->info['molch1'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							$this->error = 'Вы оказались под действие заклятия молчания...';
						}elseif($itmin['item_id'] == 4873) {
							//Крысиный яд
							$a = mysql_fetch_array(mysql_query('SELECT `id`,`type`,`name`,`uid`,`delete`,`inBattle`,`eda`,`exp`,`obraz`,`stats`,`level`,`sex`,`levelUp`,`pet_in_cage`,`max_exp`,`priems`,`bonus` FROM `users_animal` WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$this->info['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							if(isset($a['id'])) {
								$this->error = 'Ваш зверь &quot;'.$a['name'].'&quot; странно позеленел...';
								mysql_query('UPDATE `users_animal` SET `eda` = 0, `yad` = "'.(time()+86400*7).'" WHERE `id` = "'.$a['id'].'" LIMIT 1');
							}else{
								$this->error = 'Кто-то пытался отравить вашего зверя, которого у вас нет :)';
							}
						}elseif($itmin['item_id'] == 4869) {
							//Неасчастный случай  (легкая травма на 1 час)
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							mysql_query('INSERT INTO `eff_users`
							(`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES (
								"0","'.(time()+3600).'","-1",
								"Неверие в человечество","add_s'.rand(1,3).'=-'.rand(5,15).'","'.$this->info['id'].'",
								"4", "bad_present_travma1.gif","0", "1"
							)');					
							$this->error = 'Вы травмированы. В том числе и физически...';
						}elseif($itmin['item_id'] == 4872) {
							//Трагедия  (средняя травма на 1 час)
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							mysql_query('INSERT INTO `eff_users`
							(`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES (
								"0","'.(time()+3600).'","-1",
								"Неверие в человечество","add_s'.rand(1,3).'=-'.rand(16,25).'","'.$this->info['id'].'",
								"4", "bad_present_travma2.gif","0", "2"
							)');
							$this->error = 'Вы травмированы. В том числе и физически...';
						}elseif($itmin['item_id'] == 4876) {
							//Катастрофа  (тяжелая травма на 1 час)
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							mysql_query('INSERT INTO `eff_users`
							(`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES (
								"0","'.(time()+3600).'","-1",
								"Неверие в человечество","add_s'.rand(1,3).'=-'.rand(26,35).'","'.$this->info['id'].'",
								"4", "bad_present_travma3.gif","0", "3"
							)');
							$this->error = 'Вы травмированы. В том числе и физически...';
						}elseif($itmin['item_id'] == 4878) {
							//Недвижимость  (add_puti=7200)
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							mysql_query('INSERT INTO `eff_users`
							(`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES (
								"0","'.(time()+7200).'","-1",
								"Недвижимость","add_puti=1","'.$this->info['id'].'",
								"4", "bad_present_chains.gif","0", "3"
							)');
							$this->error = 'Вы не можете передвигаться...';
						}elseif($itmin['item_id'] == 4874) {
							//Сюрприз для мага  на час
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							mysql_query('INSERT INTO `eff_users`
							(`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES (
								"0","'.(time()+3600).'","-1",
								"Сюрприз для Мага","add_s5=-50","'.$this->info['id'].'",
								"4", "bad_present_dmage.gif","0", "3"
							)');
							$this->error = 'Вам нехорошо...';
						}elseif($itmin['item_id'] == 4871) {
							//Сюрприз для воина  на час
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->deleteItem($itmin['id'],$this->info['id']);
							mysql_query('INSERT INTO `eff_users`
							(`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES (
								"0","'.(time()+3600).'","-1",
								"Сюрприз для Воина","add_s1=-50","'.$this->info['id'].'",
								"4", "bad_present_dfighter.gif","0", "3"
							)');
							$this->error = 'Вам нехорошо...';
						}elseif(mysql_query('UPDATE `items_users` SET `uid` = "'.$this->info['id'].'",`lastUPD` = "'.time().'",`gift` = "'.$itm['gift'].'",`gtxt1` = "'.$itm['gtxt1'].'",`gtxt2` = "Предмет из упаковки. Дата запаковки: '.date('d.m.Y H:i:s',$itmin['time_create']).'" WHERE `id` = "'.$itmin['id'].'" LIMIT 1')) {
							//Удаляем упаковку
							$this->deleteItem($itm['id'],$this->info['id']);
							$this->error = 'Вы успешно открыли &quot;'.$itm['name'].'&quot;, внутри было найдено:<br>'.$io.'...';
						}else{
							$this->error = 'Неудалось открыть подарок, что же там?';
						}
						//
					}
					//
				}else{					
					
					while($i<count($itms))
					{
						if(isset($itms[$i]))
						{
							$x = 0;
							$itms[$i] = explode('*',$itms[$i]);
							$x += (int)$itms[$i][1];
							$itms[$i] = $itms[$i][0];
							$s = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img`,`type`,`inslot`,`2h`,`2too`,`iznosMAXi`,`inRazdel`,`price1`,`price2`,`price3`,`magic_chance`,`info`,`massa`,`level`,`magic_inci`,`overTypei`,`group`,`group_max`,`geni`,`ts`,`srok`,`class`,`class_point`,`anti_class`,`anti_class_point`,`max_text`,`useInBattle`,`lbtl`,`lvl_itm`,`lvl_exp`,`lvl_aexp` FROM `items_main` WHERE `id`="'.((int)$itms[$i]).'" LIMIT 1'));
							if(isset($s['id']))
							{

								$j = 1;
								while($j<=$x)
								{
									$pid = $this->addItem($s['id'],$this->info['id']);
									if($pid>0)
									{
										mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'",`gift` = "'.$itm['gift'].'" WHERE `id` = "'.$pid.'" AND `uid` = "'.$this->info['id'].'" LIMIT 1');
									}
									$j++;
								}
								$io .= ''.$s['name'].' (x'.$x.'), ';	
							}
						}
						$i++;
					}
					if($itm['inGroup'] > 0) {
						mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'",`inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
					}
					$this->deleteItem($itm['id'],$this->info['id']);
					$this->error = 'Вы успешно открыли &quot;'.$itm['name'].'&quot;, внутри было найдено:<br>'.$io.'...';
				}
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
				
				
				
				
				
				$upd = mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `inOdet` = "'.$inSlot.'"'.$msb.' WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				if($itm['inGroup'] > 0) {
				  mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `inGroup` = 0, `delete` = 0 WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
				if($upd) {
					//Если предмет привязывается после одевания
					//if($itm[''])
					//{
					//	
					//}
					return 1; 
				} else {
					$this->error = '(!) Ошибка обновления данных';
					return 0; 
				}
			}
		}else{
			$this->error = 'Предмет не найден в вашем рюкзаке';
			return 0;
		}
	}	
	
	public function deleteItem($id,$uid,$coldel = 0)
	{
		if($uid!=0)
		{
			$au = 'AND `iu`.`uid`="'.mysql_real_escape_string($uid).'"';
		}else{
			$au = '';
		}
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.*
		FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id`="'.mysql_real_escape_string($id).'" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" '.$au.' AND (`iu`.`delete`="0" OR `iu`.`delete`="1000") LIMIT 1'));
		if(isset($itm['id']))
		{
			
			if( $coldel == 0 ) {
				//Удаляем целиком
				$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'" WHERE `id`="'.$itm['id'].'" LIMIT 1');
				$col = $this->itemsX($itm['id']);
				if($col > 0) {
					mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'",`inGroup` = "0" WHERE `inGroup`="'.$itm['id'].'" LIMIT '.$col);
				}
			}else{
				//Удаляем конкретное кол-во
				$col = $this->itemsX($itm['id']);
				if( $col > 1 ) {
					if( $col <= $coldel ) {
						$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'" WHERE `id`="'.$itm['id'].'" LIMIT 1');
					}
					$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'",`inGroup` = "0" WHERE `inGroup`="'.$itm['id'].'" AND `delete` = "1000" LIMIT '.$coldel);
				}else{
					//Удаляем целиком
					$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'" WHERE `id`="'.$itm['id'].'" LIMIT 1');
					$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'",`inGroup` = "0" WHERE `inGroup`="'.$itm['id'].'" AND `delete` = "1000" LIMIT '.$col);
				}
			}
			if($upd)
			{
				if(isset($_GET['deleteall7'])) {
					$st = $this->lookStats($itm['data']);
					$whr = '';
					if(isset($st['frompisher'])) {
						$whr .= 'AND `data` LIKE "%frompisher='.$st['frompisher'].'%"';
					}
					$col = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id`="'.$itm['item_id'].'" AND `inOdet` = 0 AND `delete` < 100000 AND `uid` = "'.$itm['uid'].'" AND `data` NOT LIKE "%nodelete%"'.$whr));
					$col = $col[0];
					mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'" WHERE `item_id`="'.$itm['item_id'].'" AND `inOdet` = 0 AND `delete` < 100000 AND `uid` = "'.$itm['uid'].'" AND `data` NOT LIKE "%nodelete%"'.$whr);
					$this->error = 'Предметы "'.$itm['name'].' (x'.($col+1).')" выброшены';
					$this->addDelo(1,$uid,'&quot;<font color="maroon">System.inventory</font>&quot;: Предметы &quot;<b>'.$itm['name'].' (x'.$col.')</b>&quot; [itm:'.$itm['id'].'='.time().'] были <b>выброшены</b>.',time(),$this->info['city'],'System.inventory',0,0);
				}else{
					$this->error = 'Предмет "'.$itm['name'].'" выброшен';
					$this->addDelo(1,$uid,'&quot;<font color="maroon">System.inventory</font>&quot;: Предмет &quot;<b>'.$itm['name'].'</b>&quot; [itm:'.$itm['id'].'] был <b>выброшен</b>.',time(),$this->info['city'],'System.inventory',0,0);
				}
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
		
	public function get_battle_cache($uid,$battle) {
		$r = false;
		if( $uid > 0 && $battle > 0 ) {
			$r = mysql_fetch_array(mysql_query('SELECT * FROM `battle_cache` WHERE `battle` = "'.mysql_real_escape_string($battle).'" AND `uid` = "'.mysql_real_escape_string($uid).'" ORDER BY `id` DESC LIMIT 1'));
			if( !isset($r['id']) ) {
				$r = false;
			}else{
				$r = json_decode($r['data'],true);
			}
		}
		return $r;
	}
	
	public function clear_battle_cache($uid) {
		mysql_query('DELETE FROM `battle_cache` WHERE `uid` = "'.mysql_real_escape_string($uid).'"');
	}
	
	public function getStats($uid,$i1,$res = 0,$reimg = false,$btl_cache = false,$minimal = false)
	{
		global $c;
		if(count($uid)>1)
		{
			$u = $uid;
		}elseif($uid!=$this->info['id'] || $res==1)
		{
			if( $minimal == true ) {
				$u = mysql_fetch_array(mysql_query('SELECT `u`.`twink`,`u`.`swin`,`u`.`slose`,`u`.`stopexp`,`u`.`battle`,`u`.`id`,`u`.`no_ip`,`u`.`level`,`u`.`login`,`u`.`clan`,
				`st`.`id`,`st`.`lider`,`st`.`btl_cof`,`st`.`last_hp`,`st`.`last_pr`,`st`.`smena`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`enNow`,`st`.`transfers`,`st`.`regHP`,`st`.`regMP`,`st`.`showmenu`,`st`.`prmenu`,`st`.`ability`,`st`.`skills`,`st`.`sskills`,`st`.`nskills`,`st`.`exp`,`st`.`exp_eco`,`st`.`minHP`,`st`.`minMP`,`st`.`zv`,`st`.`dn`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`last_a`,`st`.`last_b`,`st`.`battle_text`,`st`.`upLevel`,`st`.`wipe`,`st`.`bagStats`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`nextAct`,`st`.`active`,`st`.`bot`,`st`.`lastAlign`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`s`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`ref_data`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`bn_capitalcity`,`st`.`bn_demonscity`
				FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
			}else{
				$u = mysql_fetch_array(mysql_query('SELECT `u`.`twink`,`u`.`swin`,`u`.`slose`,`u`.`stopexp`,`u`.`battle`,`u`.`id`,`u`.`no_ip`,`u`.`level`,`u`.`login`,`u`.`clan`,
				`st`.`id`,`st`.`lider`,`st`.`btl_cof`,`st`.`last_hp`,`st`.`last_pr`,`st`.`smena`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`enNow`,`st`.`transfers`,`st`.`regHP`,`st`.`regMP`,`st`.`showmenu`,`st`.`prmenu`,`st`.`ability`,`st`.`skills`,`st`.`sskills`,`st`.`nskills`,`st`.`exp`,`st`.`exp_eco`,`st`.`minHP`,`st`.`minMP`,`st`.`zv`,`st`.`dn`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`last_a`,`st`.`last_b`,`st`.`battle_text`,`st`.`upLevel`,`st`.`wipe`,`st`.`bagStats`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`nextAct`,`st`.`active`,`st`.`bot`,`st`.`lastAlign`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`s`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`ref_data`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`bn_capitalcity`,`st`.`bn_demonscity`
				FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
			}
		}else{
			$u = $this->info;
		}
		
		/*if( $btl_cache == true ) {
			$cache = $this->get_battle_cache( $u['id'],$u['battle'] );
			if( $cache == false ) {
				unset($cache);
			}else{
				$cache['st'] = $u + $cache['st'];
			}
		}*/
		
		/*if( $u['battle'] > 0  ) {
			$cache_items = $this->get_battle_cache( $u['id'],$u['battle'] );
			if( $cache_items == false ) {
				unset($cache_items);
			}else{
				$cache_items['st']['hpNow'] = $u['hpNow'];
				$cache_items['st']['mpNow'] = $u['mpNow'];
			}
		}*/
		
		if(isset($u['id'],$u['stats']) && !isset($cache))
		{			
			$st  = array();
			$s_vi = array();
			$s_v = array();
						
			if(!isset($cache_items)) {
				//
				//$st = new SplFixedArray(1024000);
				//
				$lvl = mysql_fetch_array(mysql_query('SELECT `upLevel`,`nextLevel`,`exp`,`money`,`money_bonus1`,`money_bonus2`,`ability`,`skills`,`nskills`,`sskills`,`expBtlMax`,`hpRegen`,`mpRegen`,`money2` FROM `levels` WHERE `upLevel` = "'.$u['upLevel'].'" LIMIT 1'));
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
				$st['irka'] = 0;
				$sts = explode('|',$u['stats']);
				$i = 0; $ste = '';
				//Родные характеристики
				while($i<count($sts))
				{
					$ste = explode('=',$sts[$i]);
					if(isset($ste[1]))
					{
						if(!isset($st[$ste[0]])) {
							$st[$ste[0]] = 0;
						}
						$st[$ste[0]]  += intval($ste[1]);
						
						if(!isset($st2[$ste[0]])) {
							$st2[$ste[0]] = 0;
						}
						$st2[$ste[0]] += intval($ste[1]);
					}
					$i++;
				}			
			
				if($u['admin'] > 0) {
					mysql_query('UPDATE `stats` SET
						`tactic1` = 25,
						`tactic2` = 25,
						`tactic3` = 25,
						`tactic4` = 25,
						`tactic5` = 25,
						`tactic6` = 25,
						`tactic7` = 25,
						`priems_z` = "0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|"
					WHERE `id` = "'.$u['id'].'" LIMIT 1
					');
				}
			
				//Шаблонные картинки
				if($this->info['id'] == $u['id'] || $reimg != false) {
					$sp_img = mysql_query('SELECT * FROM `items_img` WHERE `uid` = "'.$u['id'].'" AND `img_id` != "0" LIMIT 16');
					while($pl_img = mysql_fetch_array($sp_img)) {
						$pl_img_r = mysql_fetch_array(mysql_query('SELECT * FROM `reimage` WHERE ((`uid` = "'.$u['id'].'" AND `clan` = "0") OR (`clan` = "'.$u['clan'].'" AND '.$u['clan'].' > 0)) AND `good` > 0 AND `bad` = "0" AND `id` = "'.$pl_img['img_id'].'" LIMIT 1'));
						if(isset($pl_img_r['id'])) {
							$st['items_img'][$pl_img['type']] = $pl_img_r['id'].'.'.$pl_img_r['format'];
						}else{
							mysql_query('UPDATE `items_img` SET `img_id` = "0" WHERE `id` = "'.$pl_img['id'].'" LIMIT 1');
						}
					}
				}
			
				//Характеристики от предметов
				$cl = mysql_query('SELECT 
				`im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`pricerep`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp`,
				`iu`.`id`,`iu`.`item_id`,`iu`.`1price`,`iu`.`2price`,`iu`.`uid`,`iu`.`use_text`,`iu`.`data`,`iu`.`inOdet`,`iu`.`inShop`,`iu`.`delete`,`iu`.`iznosNOW`,`iu`.`iznosMAX`,`iu`.`gift`,`iu`.`gtxt1`,`iu`.`gtxt2`,`iu`.`kolvo`,`iu`.`geniration`,`iu`.`magic_inc`,`iu`.`maidin`,`iu`.`lastUPD`,`iu`.`timeOver`,`iu`.`overType`,`iu`.`secret_id`,`iu`.`time_create`,`iu`.`time_sleep`,`iu`.`inGroup`,`iu`.`dn_delete`,`iu`.`inTransfer`,`iu`.`post_delivery`,`iu`.`lbtl_`,`iu`.`bexp`,`iu`.`so`,`iu`.`blvl`
				FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`inOdet`!="0" AND `iu`.`uid`="'.$u['id'].'" AND `iu`.`delete`="0" LIMIT 250');
				$ia = $this->items['add'];
				$h = 0;
				$hnd1 = 0;
				$hnd2 = 0;
				$sht1 = 0;
				$reitm = array();
				$coms = array(); // комплекты
				$dom = array();
				$btl_cof = 0;
				$oza = array(
					1=>array(0,0),
					2=>array(0,0),
					3=>array(0,0),
					4=>array(0,0)
				); //особенности защиты
				$ozm = array(
					1=>array(0,0),
					2=>array(0,0),
					3=>array(0,0),
					4=>array(0,0)
				); //особенности магии
	
				$itmslvl = 0;
				$itmsCfc = 0;
				$st['reting'] = 0;
				while($pl = mysql_fetch_array($cl))
				{	
					if($pl['inOdet'] <= 18 && $pl['inOdet'] > 0 ) {
						if( $pl['1price'] > 0 ) {
							$st['irka'] += $pl['1price'];	
						}else{
							$st['irka'] += $pl['price1'];
						}
					}
					/* Доминирование */
					//$dom[count($dom)] = array($pl['inOdet'],$pl['class'],$pl['class_point'],$pl['anti_class'],$pl['anti_class_point'],$pl['level'],$u['level'],$pl['price2']);
					
					
					if($pl['inOdet'] <= 18 && $pl['inOdet'] > 0 ) {
						/*if( $pl['2price'] > 0 ) {
							$st['reting'] += $pl['2price']*12.5;
						}elseif( $pl['price2'] > 0 ) {
							$st['reting'] += $pl['price2']*12.5;
						}elseif( $pl['1price'] > 0 ) {
							$st['reting'] += $pl['1price'];
						}elseif( $pl['price1'] > 0 ) {
							$st['reting'] += $pl['price1'];
						}*/
					}
					
					
					$st['wp'.$pl['inOdet'].'id'] = $h; 
	
					$st['items'][$h] = $pl;	$h++;
					if($pl['inOdet']==3 && (($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27 || $pl['type']==28))
					{
						$hnd1 = 1;
					}
					if($pl['inOdet']==14 && (($pl['type']>=18 && $pl['type']<=24) || $pl['type']==26 || $pl['type']==27 || $pl['type']==28))
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
							if(!isset($sti[$ste[0]])) {
								$sti[$ste[0]] = 0;
							}
							$sti[$ste[0]] += intval($ste[1]);
						}
						$i++;
					}
					
					if( !isset($sti['zazuby']) && !isset($sti['frompisher']) ) {
						if( $pl['inOdet'] < 18 && $pl['inOdet'] > 0 ) {
							if( $pl['2price'] == 0 && $pl['price2'] == 0 ) {
								if( $pl['1price'] > 0 ) {
									$st['prckr'] += $pl['1price'];	
								}else{
									$st['prckr'] += $pl['price1'];
								}
							} else {
								if( $pl['2price'] > 0 ) {
							 		$st['preckr'] += $pl['2price'];
								}else{
							 		$st['preckr'] += $pl['price2'];
								}
							}
						}
					}else{
						if( $pl['inOdet'] < 18 && $pl['inOdet'] > 0 ) {
							if( $pl['2price'] == 0 && $pl['price2'] == 0 ) {
								if( $pl['1price'] > 0 ) {
									$st['prckr'] += round($pl['1price']/3);	
								}else{
									$st['prckr'] += round($pl['price1']/3);
								}
							}
						}
					}
					
					if($pl['inOdet'] <= 18 && $pl['inOdet'] > 0 ) {

						$st['reting'] += 1;
					}
					
					if(isset($sti['add_oza'])) {
	
					}
					
					$ko = 1;
					while($ko <= 4) {
						if(isset($sti['add_oza'.$ko])) {
							if(isset($sti['add_oza'])) {
								if($sti['add_oza'] == 1) {
									//Слабая
									$oza[$ko][0] += 1;
									$oza[$ko][1] += 9;
								}elseif($sti['add_oza'] == 2) {
									//Нормальная
									$oza[$ko][0] += 20;
									$oza[$ko][1] += 39;
								}elseif($sti['add_oza'] == 3) {
									//Хорошая
									$oza[$ko][0] += 40;
									$oza[$ko][1] += 69;
								}elseif($sti['add_oza'] == 4) {
									//Посредственная
									$oza[$ko][0] += 10;
									$oza[$ko][1] += 19;
								}elseif($sti['add_oza'] == 5) {
									//Великолепная
									$oza[$ko][0] += 70;
									$oza[$ko][1] += 89;
								}
							}
							if(isset($sti['add_ozm'])) {
								if($sti['add_ozm'] == 1) {
									//Слабая
									$ozm[$ko][0] += 1;
									$ozm[$ko][1] += 9;
								}elseif($sti['add_ozm'] == 2) {
									//Нормальная
									$ozm[$ko][0] += 20;
									$ozm[$ko][1] += 39;
								}elseif($sti['add_ozm'] == 3) {
									//Хорошая
									$ozm[$ko][0] += 40;
									$ozm[$ko][1] += 69;
								}elseif($sti['add_ozm'] == 4) {
									//Посредственная
									$ozm[$ko][0] += 10;
									$ozm[$ko][1] += 19;
								}elseif($sti['add_ozm'] == 5) {
									//Великолепная
									$ozm[$ko][0] += 70;
									$ozm[$ko][1] += 89;
								}
							}
							if($sti['add_oza'.$ko] == 1) {
								//Слабая
								$oza[$ko][0] += 1;
								$oza[$ko][1] += 9;
							}elseif($sti['add_oza'.$ko] == 2) {
								//Нормальная
								$oza[$ko][0] += 20;
								$oza[$ko][1] += 39;
							}elseif($sti['add_oza'.$ko] == 3) {
								//Хорошая
								$oza[$ko][0] += 40;
								$oza[$ko][1] += 69;
							}elseif($sti['add_oza'.$ko] == 4) {
								//Посредственная
								$oza[$ko][0] += 10;
								$oza[$ko][1] += 19;
							}elseif($sti['add_oza'.$ko] == 5) {
								//Великолепная
								$oza[$ko][0] += 70;
								$oza[$ko][1] += 89;
							}
							if($sti['add_ozm'.$ko] == 1) {
								//Слабая
								$ozm[$ko][0] += 1;
								$ozm[$ko][1] += 9;
							}elseif($sti['add_ozm'.$ko] == 2) {
								//Нормальная
								$ozm[$ko][0] += 20;
								$ozm[$ko][1] += 39;
							}elseif($sti['add_ozm'.$ko] == 3) {
								//Хорошая
								$ozm[$ko][0] += 40;
								$ozm[$ko][1] += 69;
							}elseif($sti['add_ozm'.$ko] == 4) {
								//Посредственная
								$ozm[$ko][0] += 10;
								$ozm[$ko][1] += 19;
							}elseif($sti['add_ozm'.$ko] == 5) {
								//Великолепная
								$ozm[$ko][0] += 70;
								$ozm[$ko][1] += 89;
							}
						}
						$ko++;
					}
					
					//if( $sti['tr_lvl'] == $u['level'] ) {
					if( $pl['inOdet'] <= 18 ) {
						$itmslvl++;
					}
					if( $pl['inOdet'] <= 14 ) {
						if( $pl['tr_lvl'] == $u['level'] ) {
							$itmsCfc += 1;
						}else{
							$itmsCfc += ($sti['tr_lvl']/$u['level'])/4;
						}
					}
					//}
					
					if(isset($sti['art'])) {
						if(!isset($st['art'])) {
							$st['art'] = 0;
						}
						$st['art'] += $sti['art'];
					}
					
					if(isset($sti['complect']))
					{
						$coms[count($coms)]['id'] = $sti['complect'];
						if(!isset($coms['com'][$sti['complect']]))
						{
							$coms['com'][$sti['complect']] = 0;	
							if(!isset($coms['new'])) {
								$coms['new'] = array();
							}
							$coms['new'][count($coms['new'])] = $sti['complect'];
						}
						$coms['com'][$sti['complect']]++;
						if($pl['2h'] > 0) {
							$coms['com'][$sti['complect']]++;
						}
					}
					if(isset($sti['complect2']))
					{
						$coms[count($coms)]['id'] = $sti['complect2'];
						if(!isset($coms['com'][$sti['complect2']]))
						{
							$coms['com'][$sti['complect2']] = 0;	
							if(!isset($coms['new'])) {
								$coms['new'] = array();
							}
							$coms['new'][count($coms['new'])] = $sti['complect2'];
						}
						$coms['com'][$sti['complect2']]++;
						if($pl['2h'] > 0) {
							$coms['com'][$sti['complect2']]++;
						}
					}
					
					if(isset($sti['zonb']) && $sti['zonb']!=0)
					{
						if(!isset($st['zonb'])) {
							$st['zonb'] = 0;
						}
						$st['zonb'] += $sti['zonb'];
					}
					
					if(isset($sti['zona']) && $sti['zona']!=0)
					{
						if(!isset($st['zona'])) {
							$st['zona'] = 0;
						}
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
								//Действует на (Свойства)
								/*if( isset($sti['sv_'.$ia[$i]]) ) {
									if($ia[$i] != 'zmproc' && $ia[$i] != 'zaproc' && $ia[$i] != 'm9') {
										/*if( $ia[$i] == 'm1' || 
											$ia[$i] == 'm2' ||
											$ia[$i] == 'm4' || 
											$ia[$i] == 'm5' || 
											$ia[$i] == 'za' ||
											$ia[$i] == 'za1' ||
											$ia[$i] == 'za2' ||
											$ia[$i] == 'za3' ||
											$ia[$i] == 'za4' || 
											$ia[$i] == 'zm' ||
											$ia[$i] == 'zm1' ||
											$ia[$i] == 'zm2' ||
											$ia[$i] == 'zm3' ||
											$ia[$i] == 'zm4' || 
											$ia[$i] == 'zm5' ||
											$ia[$i] == 'zm6' ||
											$ia[$i] == 'zm7' ||
											$ia[$i] == 'zma' 
										) {*/
											//$st[$ia[$i]] += intval($sti['sv_'.$ia[$i]]);
										/*}*/
									//}
								//}
								//Действует на (Действует на)
								if(isset($sti['add_'.$ia[$i]])) {
									
									if($ia[$i] != 'zmproc' && $ia[$i] != 'zaproc') {
										if(!isset($st[$ia[$i]])) {
											$st[$ia[$i]] = 0;
										}
										$st[$ia[$i]] += intval($sti['add_'.$ia[$i]]);
									}else{
										if(!isset($st[$ia[$i]])) {
											$st[$ia[$i]] = 0;
										}
										$st[$ia[$i]] = 100-$st[$ia[$i]];
										$st[$ia[$i]] = $st[$ia[$i]] - $st[$ia[$i]]/100*intval($sti['add_'.$ia[$i]]);
										$st[$ia[$i]] = 100-$st[$ia[$i]];
									}
									
								}
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
							if(isset($sti['sv_'.$ia[$i]])) {
								if(!isset($s_v[$ia[$i]])){
									$s_v[$ia[$i]] = 0;
									$s_v['z'][$pl['inOdet']][$ia[$i]] = 0;
								}
								$s_v[$ia[$i]] += intval($sti['sv_'.$ia[$i]]);
								$s_v['z'][$pl['inOdet']][$ia[$i]] += intval($sti['sv_'.$ia[$i]]);
							}
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
				
				//Харки от иконок
				$efs = mysql_query('SELECT * FROM `users_ico` WHERE `uid`="'.mysql_real_escape_string($u['id']).'" AND (`endTime` > "'.time().'" OR `endTime` = 0)');
				while($e = mysql_fetch_array($efs))
				{
						$sts = $this->lookStats($e['bonus']);
						$i = 0;
						while($i<count($ia))
						{
							if(isset($ia[$i]))
							{
								if (!isset($sti[$ia[$i]])){
								$sti[$ia[$i]] = 0;
								 }
								if (!isset($sts['add_'.$ia[$i]])){
									$sts['add_'.$ia[$i]] =0;
								}
	
								$sti[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
	
								if(!isset($st[$ia[$i]])){
									$st[$ia[$i]] = 0;
								}
								  //
								$st[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
							}
							$i++;
						}	
				}
				/*if( $u['battle'] > 0 ) {
					//Кэшируем
					$cache_items = array(
						'st' => $st,
						'sti' => $sti,
						's_v' => $s_v,
						's_vi' => $s_vi
					);
					$cache_items = json_encode($cache_items);
					mysql_query('INSERT INTO `battle_cache` (`uid`,`battle`,`data`,`time`) VALUES ("'.$u['id'].'","'.$u['battle'].'","'.mysql_real_escape_string($cache_items).'","'.time().'")');
					unset($cache_items);
				}*/
			}else{
				/*$st = $cache_items['st'];
					unset($cache_items['st']);
				$sti = $cache_items['sti'];
					unset($cache_items['sti']);
				$s_v = $cache_items['s_v'];
					unset($cache_items['s_v']);
				$s_vi = $cache_items['s_vi'];
				unset($cache_items);*/
			}	
									
			//
			//if( $this->info['id'] != $u['id'] ) {
				if( $u['battle'] > 0 ) {
					$test_btl_info = mysql_fetch_array(mysql_query('SELECT `id`,`noeff` FROM `battle` WHERE `id` = "'.$u['battle'].'" LIMIT 1'));
					$test_noef = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle_actions` WHERE `uid` = "'.$u['id'].'" AND `vars` = "noeffectbattle1" AND `btl` = "'.$u['battle'].'" LIMIT 1'));
				}
			//}
			//Характеристики от эффектов
			$h = 0;
			$nbs = array();
			$prsu = array();
			if(!isset($test_noef['id'])) {
				$efs = mysql_query('SELECT 
				`eu`.`id`,`eu`.`id_eff`,`eu`.`tr_life_user`,`eu`.`uid`,`eu`.`name`,`eu`.`data`,`eu`.`overType`,`eu`.`timeUse`,`eu`.`timeAce`,`eu`.`user_use`,`eu`.`delete`,`eu`.`v1`,`eu`.`v2`,`eu`.`img2`,`eu`.`x`,`eu`.`hod`,`eu`.`bj`,`eu`.`sleeptime`,`eu`.`no_Ace`,
				`em`.`id2`,`em`.`mname`,`em`.`type1`,`em`.`img`,`em`.`mdata`,`em`.`actionTime`,`em`.`type2`,`em`.`type3`,`em`.`onlyOne`,`em`.`oneType`,`em`.`noAce`,`em`.`see`,`em`.`info`,`em`.`overch`,`em`.`bp`,`em`.`noch` FROM `eff_users` AS `eu` LEFT JOIN `eff_main` AS `em` ON (`eu`.`id_eff` = `em`.`id2`) WHERE `eu`.`uid`="'.mysql_real_escape_string($u['id']).'" AND `eu`.`delete`="0" AND `eu`.`deactiveTime` < "'.time().'" AND `eu`.`v1`!="priem" ORDER BY `eu`.`id` ASC LIMIT 50');
				while($e = mysql_fetch_array($efs))
				{
					if( $u['dnow'] == 0 ) {
						if( $u['battle'] == 0 ) {
							if(!isset($effdels[$e['id_eff']])) {
								$effdels[$e['id_eff']]++;
								mysql_query('DELETE FROM `eff_users` WHERE `id_eff` = "'.$e['id_eff'].'" AND `uid` = "'.$u['id'].'" AND `id` != "'.$e['id'].'" ORDER BY `id` ASC');
							}
							//mysql_query('DELETE FROM `eff_users` WHERE `id_eff` = "'.$e['id_eff'].'" AND `uid` = "'.$u['id'].'" AND `id` != "'.$e['id'].'" ORDER BY `id` ASC');
							//Переводим в заряды
							if($e['v1'] != 'priem') {
								if($e['hod'] != -1) {
									mysql_query('UPDATE `eff_users` SET `hod` = "-1",`timeUse` = "'.(time()+($e['hod']*$c['effz'])-$e['actionTime']).'" WHERE `id` = "'.$e['id'].'" LIMIT 1');
								}
							}
						}else{
							if($e['v1'] != 'priem' && $c['effz'] > 0) {
								if($e['hod'] == -1) {
									$efzz = round(($e['timeUse']+$e['actionTime']+$e['timeAce'])-time());
									if( $efzz > 0 ) {
										mysql_query('UPDATE `eff_users` SET `hod` = "'.($efzz/$c['effz']).'" WHERE `id` = "'.$e['id'].'" LIMIT 1');
									}
								}
							}
						}
					}
					//echo '['.date( 'd.m.Y H:i:s' , time() + round((($e['timeUse']+$e['timeAce']+$e['actionTime'])-time())/$c['effz'])*$c['effz']).']';
					if($test_btl_info['noeff']==1 && isset($this->ekrcast[$e['id_eff']])) {
						//эффекты не действуют
					}elseif( $e['sleeptime'] != 0 && substr_count($e['v1'],'pgb') > 0 ) {
						//Не отображаем действие пристрастия во время сна
					}elseif( ( $e['timeUse']+$e['timeAce']+$e['actionTime']>time() || $e['timeUse']==77 ) )
					{					
						if($e['v1'] == 'priem') {
							$prsu[$e['v2']] = 0+$prsu['x'];
						}
						$st['effects'][$h] = $e;	$h++;
						$sts = $this->lookStats($e['data']);
						if(isset($sts['itempl']) && $sts['itempl'] > 0) {
							$nbs[$sts['itempl']] += 1;
						}
						if( isset($sts['puti']) ) {
							$st['puti'] = $sts['puti'];
						}
						if( isset($sts['add_silver']) ) {
							$st['slvtm'] = $e['timeUse']+$e['actionTime'];
						}
						$i = 0;
						while($i<count($ia))
						{
							if(isset($ia[$i]))
							{
								if(isset($sts['add_'.$ia[$i]])) {
									if(!isset($sti[$ia[$i]])) {
										$sti[$ia[$i]] = 0;
									}
									$sti[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
								}
								if(isset($sts['add_'.$ia[$i]])) {
									if($ia[$i] != 'zaproc' && $ia[$i] != 'zmproc') {
										if(!isset($st[$ia[$i]])) {
											$st[$ia[$i]] = 0;
										}
										$st[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
									}else{
										if(!isset($st[$ia[$i]])) {
											$st[$ia[$i]] = 0;
										}
										$st[$ia[$i]] = 100-$st[$ia[$i]];
										$st[$ia[$i]] = $st[$ia[$i]] - $st[$ia[$i]]/100*intval($sts['add_'.$ia[$i]]);
										$st[$ia[$i]] = 100-$st[$ia[$i]];
									}								
								}
							}
							$i++;
						}	
						$i = 0;
						while($i<count($ia))
						{
							if(isset($ia[$i]))
							{
								if(isset($sts['sv_'.$ia[$i]])) {
									if(!isset($s_vi[$ia[$i]])) {
										$s_vi[$ia[$i]] = 0;
									}
									$s_vi[$ia[$i]] += intval($sts['sv_'.$ia[$i]]);
								}
								if(isset($sts['sv_'.$ia[$i]])) {
									if(!isset($s_v[$ia[$i]])) {
										$s_v[$ia[$i]] = 0;
									}
									$s_v[$ia[$i]] += intval($sts['sv_'.$ia[$i]]);
								}
							}
							$i++;
						}	
					}elseif($e['timeUse']!=77){
						//удаляем эффект	
						if( $e['img2'] != 'tz.gif' || $u['id'] == $this->info['id'] ) {				
							if( $e['sleeptime'] == 0 ) {
								$this->endEffect($e['id'],$u);
							}
							$st['act'] = 1;
						}
					}
				}
			}else{
				$st['noeffectbattle1'] = 1;
			}
			
			//Заглушки от эффектов
			//$st['items_img'][$pl_img['type']] = $pl_img_r['id'].'.'.$pl_img_r['format'];
			if( $nbs[4899] > 0 ) {
				//Зеленый комплект
				$st['items_img'][8] = 'robe_illusion4.gif';
				$st['items_img'][11] = 'leg_illusion4.gif';				
			}elseif( $nbs[4900] > 0 ) {
				//Золото комплект
				$st['items_img'][8] = 'robe_illusion5.gif';
				$st['items_img'][11] = 'leg_illusion5.gif';				
			}elseif( $nbs[4901] > 0 ) {
				//Голубой комплект
				$st['items_img'][8] = 'robe_illusion3.gif';
				$st['items_img'][11] = 'leg_illusion3.gif';				
			}elseif( $nbs[4902] > 0 ) {
				//Синий комплект
				$st['items_img'][8] = 'robe_illusion6.gif';
				$st['items_img'][11] = 'leg_illusion6.gif';				
			}elseif( $nbs[4903] > 0 ) {
				//Желтый комплект
				$st['items_img'][8] = 'robe_illusion8.gif';
				$st['items_img'][11] = 'leg_illusion8.gif';				
			}elseif( $nbs[4904] > 0 ) {
				//Сиреневое платье
				$st['items_img'][8] = 'robe_illusion7.gif';
				$st['items_img'][11] = 'leg_illusion7.gif';				
			}elseif( $nbs[4905] > 0 ) {
				//Оранжевое платье
				$st['items_img'][8] = 'robe_illusion9.gif';
				$st['items_img'][11] = 'leg_illusion9.gif';				
			}elseif( $nbs[4908] > 0 ) {
				//Набор Темной Одежды
				$st['items_img'][8] = 'robe_illusion2.gif';
				$st['items_img'][11] = 'leg_illusion2.gif';
				$st['items_img'][10] = 'boots_illusion2.gif';
				$st['items_img'][9] = 'belt_illusion2.gif';
				$st['items_img'][5] = 'naruchi_illusion2.gif';	
				$st['items_img'][4] = 'helmet_illusion2.gif';
				$st['items_img'][12] = 'perchi_illusion2.gif';				
			}elseif( $nbs[4906] > 0 ) {
				//Набор Бриллиантовой Одежды
				$st['items_img'][8] = 'robe_illusion1.gif';
				$st['items_img'][11] = 'leg_illusion1.gif';
				$st['items_img'][10] = 'boots_illusion1.gif';
				$st['items_img'][9] = 'belt_illusion1.gif';
				$st['items_img'][5] = 'naruchi_illusion1.gif';	
				$st['items_img'][4] = 'helmet_illusion1.gif';
				$st['items_img'][12] = 'perchi_illusion1.gif';				
			}
			if( $nbs[4909] > 0 ) {
				//Набор Золотых украшений
				$st['items_img'][13] = 'ring_illusion2.gif';
				$st['items_img'][17] = 'ring_illusion2.gif';
				$st['items_img'][18] = 'ring_illusion2.gif';
				$st['items_img'][14] = 'amulet_illusion2.gif';
				$st['items_img'][15] = 'earrings_illusion2.gif';				
			}elseif( $nbs[4907] > 0 ) {
				//Набор Золотых украшений
				$st['items_img'][13] = 'ring_illusion1.gif';
				$st['items_img'][17] = 'ring_illusion1.gif';
				$st['items_img'][18] = 'ring_illusion1.gif';
				$st['items_img'][14] = 'amulet_illusion1.gif';
				$st['items_img'][15] = 'earrings_illusion1.gif';				
			}
			
			if( $itmslvl < 5 ) {
				//Эффект ослабления
				$st['itmslvl'] = 0;
			}else{
				$st['itmslvl'] = 1;
			}
			//
			$st['itmsCfc'] = $itmsCfc;
			
			unset($test_btl_info);
			
			if( $u['battle'] > 0 ) {
				//Характеристики от приемов
				$efs = mysql_query('SELECT `eu`.`id`,`eu`.`id_eff`,`eu`.`uid`,`eu`.`name`,`eu`.`data`,`eu`.`overType`,`eu`.`timeUse`,`eu`.`timeAce`,`eu`.`user_use`,`eu`.`tr_life_user`,`eu`.`delete`,`eu`.`v1`,`eu`.`v2`,`eu`.`img2`,`eu`.`x`,`eu`.`hod`,`eu`.`bj`,`eu`.`sleeptime`,`eu`.`no_Ace` FROM `eff_users` AS `eu` WHERE `eu`.`uid`="'.mysql_real_escape_string($u['id']).'" AND `eu`.`delete`="0" AND `eu`.`deactiveTime` < "'.time().'" AND `eu`.`v1` = "priem" ORDER BY `eu`.`id` ASC');
				$st['set_pog'] = array();
				$st['set_pog2'] = array();
				while($e = mysql_fetch_array($efs))
				{
					$e['type1'] = 14;
					$e['img'] = $e['img2'];
					if($e['tr_life_user'] > 0 ) {
						$trlu = mysql_fetch_array(mysql_query('SELECT `hpNow` FROM `stats` WHERE `id` = "'.$e['tr_life_user'].'" LIMIT 1'));
						if( floor($trlu['hpNow']) < 1 ) {
							$this->endEffect($e['id'],$u);
							$st['act'] = 1;
						}
					}
					if($e['timeUse']+$e['timeAce']+$e['actionTime']>time() || $e['timeUse']==77)
					{				
						if($e['v1'] == 'priem') {
							$prsu[$e['v2']] = 0+$prsu['x'];
						}
						$st['effects'][$h] = $e;	$h++;
						$sts = $this->lookStats($e['data']);
						if($e['v2'] == 217) {
							$st['raztac'] = 1;
						}
						if(isset($sts['add_pog']))
						{
							$ctt = count($st['set_pog']);
							$st['set_pog'][$ctt]['id'] = $h;
							$st['set_pog'][$ctt]['y']  = $sts['add_pog'];
							unset($ctt);
						}
						if(isset($sts['natoe'])) {
							$st['set_natoe']['id'] = $h;
							$st['set_natoe']['a'] = 0 + $sts['natoe_end'];
							$st['set_natoe']['b'] = 0 + $sts['natoe'];
							$st['set_natoe']['eff_id'] = $e['id'];
							$st['set_natoe']['t'] = 0 + $sts['natoe_type'];
							$st['set_natoe']['user_id'] = $e['tr_life_user'];
						}
						if(isset($sts['add_pog2']))
						{
							$ctt = count($st['set_pog2']);
							$st['set_pog2'][$ctt]['id'] = $h;
							$st['set_pog2'][$ctt]['y']  = $sts['add_pog2'];
							$st['set_pog2'][$ctt]['p']  = $sts['add_pog2p'];
							$st['set_pog2'][$ctt]['m']  = $sts['add_pog2mp'];
							unset($ctt);
						}
						$i = 0;
						while($i<count($ia))
						{
							if(isset($ia[$i]))
							{
								if($ia[$i] != 'zaproc' && $ia[$i] != 'zmproc') {
									$sti[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
									$st[$ia[$i]] += intval($sts['add_'.$ia[$i]]);
								}else{
									$sti[$ia[$i]] = 100-$sti[$ia[$i]];
									$sti[$ia[$i]] = $sti[$ia[$i]] - $sti[$ia[$i]]/100*intval($sts['add_'.$ia[$i]]);
									$sti[$ia[$i]] = 100-$sti[$ia[$i]];	
																	
									$st[$ia[$i]] = 100-$st[$ia[$i]];
									$st[$ia[$i]] = $st[$ia[$i]] - $st[$ia[$i]]/100*intval($sts['add_'.$ia[$i]]);
									$st[$ia[$i]] = 100-$st[$ia[$i]];
								}
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
			}
				
			//Характеристики от статов
			
			if(!isset($st['hpAll'])) {
				$st['hpAll'] = 0;
			}
			$st['hpAll'] += $st['s4']*6;
			
			//$st['enAll'] += 10;
			//$st['enAll'] += floor($st['s11']*10);
			//$st['enAll'] += round($st['s4']*0.09);
			
			if(!isset($st['mpAll'])) {
				$st['mpAll'] = 0;
			}
			@$st['mpAll'] += @$st['s6']*10;
			
			//Турнир
				if(!isset($st['m1'])) {
					$st['m1'] = 0;
				} $st['m1']	 += $st['s3']*6;
				
			
			// мф.анти-крит = 2.5
				if(!isset($st['m2'])) {
					$st['m2'] = 0;
				} $st['m2']	 += $st['s3']*4;

			// мф.уворот = 2.5
				if(!isset($st['m4'])) {
					$st['m4'] = 0;
				}
				$st['m4']	 += $st['s2']*6;
			
			// мф.анти-уворот = 2.5
				if(!isset($st['m5'])) {
					$st['m5'] = 0;
				}
				$st['m5']	 += $st['s2']*4;
			
			
			
			if(!isset($st['za'])) {
				$st['za'] = 0;
			}
			$st['za']	 += $st['s4']*1.5;	
			
			if(!isset($st['zm'])) {
				$st['zm'] = 0;
			}		
			$st['zm']	 += $st['s4']*1.5;
			
			//Мощности
			//Мощность против "Мощность крит. урона". Гамс
				if(!isset($st['antm3'])) {
    				$st['antm3'] = 0;
  			 	}
   				$st['antm3']  += $st['s4']*0.5;
			
		
			
			if(!isset($st['m10'])) {
				$st['m10'] = 0;
			}
			$st['m10'] += 0;
			
			if(!isset($st['m11'])) {
				$st['m11'] = 0;
			}
			$st['m11'] += 0;
			
			if(!isset($st['m11a'])) {
				$st['m11a'] = 0;
			}
			$st['m11a'] += 0;
			
			if(!isset($st['m7'])) {
				$st['m7'] = 0;
			}
			$st['m7'] += 0;
			
			if(!isset($st['m8'])) {
				$st['m8'] = 0;
			}
			$st['m8'] += 0;
		
			//Бонусы комплектов
			$i = 0;
			while($i<=count(@$coms['new']))
			{
				if(@isset($coms['new'][$i]))
				{
					//$coms[$i]['id'] - id комплекта, $j - кол-во предметов данного комплекта
					$j = @$coms['com'][$coms['new'][$i]];
					$com = mysql_fetch_array(mysql_query('SELECT `id`,`com`,`name`,`x`,`data` FROM `complects` WHERE `com` = "'.((int)$coms['new'][$i]).'" AND `x` <= '.((int)$j).' ORDER BY  `x` DESC  LIMIT 1'));					
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
		
			//Бонусы статов
			//Бонусы статов
			//сила
			if($st['s1']>24 && $st['s1']<50)	{ $st['m10']  += 5;  }
			if($st['s1']>49 && $st['s1']<75)	{ $st['m10']  += 10; }
			if($st['s1']>74 && $st['s1']<100)	{ $st['m10']  += 15; $st['hpAll'] += 50; }
			if($st['s1']>99 && $st['s1']<125)	{ $st['m10']  += 15; $st['hpAll'] += 50; $st['minAtack'] += 10; $st['maxAtack'] += 10; }
			if($st['s1']>124 && $st['s1']<150)	{ $st['m10']  += 25; $st['m2'] += 75; $st['m5'] += 75; $st['hpAll'] += 50; $st['minAtack'] += 10; $st['maxAtack'] += 10; }
			if($st['s1']>149 && $st['s1']<200)	{ $st['m10']  += 35; $st['m2'] += 75; $st['m5'] += 75; $st['hpAll'] += 100; $st['minAtack'] += 10; $st['maxAtack'] += 10; }
			if($st['s1']>199 && $st['s1']<249)	{ $st['m10']  += 35; $st['m2'] += 75; $st['m5'] += 75; $st['hpAll'] += 100; $st['minAtack'] += 15; $st['maxAtack'] += 20; }
			if($st['s1']>249)					{ $st['m10']  += 40; $st['m2'] += 75; $st['m5'] += 75; $st['hpAll'] += 150; $st['minAtack'] += 15; $st['maxAtack'] += 20; }
			//ловкость 
			if($st['s2']>24 && $st['s2']<50){ $st['m7']  += 5;  }
			if($st['s2']>49 && $st['s2']<75){ $st['m7']  += 5; $st['m4']  += 35; $st['m2']  += 15; }
			if($st['s2']>74 && $st['s2']<100){ $st['m7']  += 15; $st['m4']  += 35; $st['m2']  += 15; }
			if($st['s2']>99 && $st['s2']<125){ $st['m7']  += 15; $st['m4']  += 105; $st['m2']  += 40; }
			if($st['s2']>124){ $st['m7']  += 15; $st['m4']  += 105; $st['m2']  += 40; $st['m15'] += 5; }
			//if($st['s2']>149 && $st['s2']<175){ $st['m7']  += 20; $st['m4']  += 155; $st['m2']  += 50; $st['m15'] += 7; }
			//if($st['s2']>174){ $st['m7']  += 25; $st['m4']  += 205; $st['m2']  += 50; $st['m15'] += 9; }			
			//интуиция
			if($st['s3']>24 && $st['s3']<50){ $st['m3'] += 10; }
			if($st['s3']>49 && $st['s3']<75){ $st['m3'] += 10; $st['m1'] += 35; $st['m5'] += 15; }
			if($st['s3']>74 && $st['s3']<100){ $st['m3'] += 25; $st['m1'] += 35; $st['m5'] += 15; }
			if($st['s3']>99 && $st['s3']<125){ $st['m3'] += 25; $st['m1'] += 105; $st['m5'] += 45; }
			if($st['s3']>124){ $st['m3'] += 25; $st['m1'] += 105; $st['m5'] += 45; $st['m14'] += 5; }
			//if($st['s3']>149 && $st['s3']<175){ $st['m3'] += 30; $st['m1'] += 125; $st['m5'] += 55; $st['m14'] += 7; }
			//if($st['s3']>174){ $st['m3'] += 35; $st['m1'] += 185; $st['m5'] += 65; $st['m14'] += 9; }			
			//выносливость
			/*
			//выносливость
			if($u->stats['s4']>0)							{ $st[5]['hpAll']  += 30;  }
			if($u->stats['s4']>24 && $u->stats['s4']<50)	{ $st[4]['hpAll']  += 50;  }
			if($u->stats['s4']>49 && $u->stats['s4']<75)	{ $st[4]['hpAll']  += 150; }
			if($u->stats['s4']>74 && $u->stats['s4']<100)	{ $st[4]['hpAll']  += 300; }
			if($u->stats['s4']>99 && $u->stats['s4']<125)	{ $st[4]['hpAll']  += 300; $st[4]['zaproc'] += 10; $st[4]['zmproc'] += 10; }
			if($u->stats['s4']>124 && $u->stats['s4']<150)	{ $st[4]['hpAll']  += 500; $st[4]['zaproc'] += 10; $st[4]['zmproc'] += 10; }
			if($u->stats['s4']>149 && $u->stats['s4']<200)	{ $st[4]['hpAll']  += 750; $st[4]['zaproc'] += 10; $st[4]['zmproc'] += 10; }
			if($u->stats['s4']>199 && $u->stats['s4']<225)	{ $st[4]['hpAll']  += 1250; $st[4]['zaproc'] += 20; $st[4]['zmproc'] += 20; $st[4]['m2'] += 600; $st[4]['m5'] += 600; $st[4]['minAtack'] += 15; $st[4]['maxAtack'] += 20; }
			if($u->stats['s4']>224 && $u->stats['s4']<250)	{ $st[4]['hpAll']  += 1400; $st[4]['zaproc'] += 20; $st[4]['zmproc'] += 20; $st[4]['m2'] += 750; $st[4]['m5'] += 750; $st[4]['minAtack'] += 20; $st[4]['maxAtack'] += 25; }
			if($u->stats['s4']>249)							{ $st[4]['hpAll']  += 1550; $st[4]['zaproc'] += 25; $st[4]['zmproc'] += 25; $st[4]['m2'] += 1000; $st[4]['m5'] += 1000; $st[4]['minAtack'] += 25; $st[4]['maxAtack'] += 25; }

			*/
			if($st['s4']>0)						{ $st['hpAll']  += 30;  }
			if($st['s4']>24 && $st['s4']<50)	{ $st['hpAll']  += 50;  }
			if($st['s4']>49 && $st['s4']<75)	{ $st['hpAll']  += 150; }
			if($st['s4']>74 && $st['s4']<100)	{ $st['hpAll']  += 300; }
			if($st['s4']>99 && $st['s4']<125)	{ $st['hpAll']  += 300; $st['zaproc'] += 10; $st['zmproc'] += 10; }
			if($st['s4']>124 && $st['s4']<150)	{ $st['hpAll']  += 500; $st['zaproc'] += 10; $st['zmproc'] += 10; }
			if($st['s4']>149 && $st['s4']<200)	{ $st['hpAll']  += 750; $st['zaproc'] += 10; $st['zmproc'] += 10; }
			if($st['s4']>199 && $st['s4']<225)	{ $st['hpAll']  += 1250; $st['zaproc'] += 20; $st['zmproc'] += 20; $st['m2'] += 600; $st['m5'] += 600; $st['minAtack'] += 15; $st['maxAtack'] += 20; }
			if($st['s4']>224 && $st['s4']<250)	{ $st['hpAll']  += 1400; $st['zaproc'] += 20; $st['zmproc'] += 20; $st['m2'] += 750; $st['m5'] += 750; $st['minAtack'] += 20; $st['maxAtack'] += 25; }
			if($st['s4']>249)					{ $st['hpAll']  += 1550; $st['zaproc'] += 25; $st['zmproc'] += 25; $st['m2'] += 1000; $st['m5'] += 1000; $st['minAtack'] += 25; $st['maxAtack'] += 25; }
			//интелект 
			if(@$st['s5']>24 && @$st['s5']<50){ @$st['m11'] += 5; }
			if(@$st['s5']>49 && @$st['s5']<75){ @$st['m11'] += 10;  }
			if(@$st['s5']>74 && @$st['s5']<100){ @$st['m11'] += 17; }
			if(@$st['s5']>99 && @$st['s5']<125){ @$st['m11'] += 25; }
			if(@$st['s5']>124){ @$st['m11'] += 35;  }
			//if(@$st['s5']>149 && @$st['s5']<175){ @$st['m11'] += 50; }
			//if(@$st['s5']>174){ @$st['m11'] += 75; }
			//мудрость
			if(@$st['s6']>24 && $st['s6']<50){ @$st['mpAll'] += 50; }
			if(@$st['s6']>49 && $st['s6']<75){ @$st['mpAll'] += 100; }
			if(@$st['s6']>74 && $st['s6']<100){ @$st['mpAll'] += 175; @$st['speedmp'] += 375; }
			if(@$st['s6']>99 && $st['s6']<125){ @$st['mpAll'] += 250; @$st['speedmp'] += 500; }
			if(@$st['s6']>124){ @$st['mpAll'] += 250; @$st['speedmp'] += 500; @$st['pzm'] += 3; }
			//if(@$st['s6']>149 && $st['s6']<175){ @$st['mpAll'] += 300; @$st['speedmp'] += 750; @$st['pzm'] += 5; }
			//if(@$st['s6']>174){ @$st['mpAll'] += 450; @$st['speedmp'] += 1000; @$st['pzm'] += 8; }	
			
			//если второе оружие одето
			if($hnd2==1 && $hnd1==1)
			{
				$st['zona']++;
			}
			
			if($sht1==1)
			{
				$st['zonb']++;
			}
			
			/* Владения */
			$i = 1;						
			while($i<=7)
			{				
				if(!isset($st['pm'.$i])) {
					$st['pm'.$i] = 0;
				}
				if(!isset($st['a'.$i])) {
					$st['a'.$i] = 0;
				}
				if(!isset($st['mg'.$i])) {
					$st['mg'.$i] = 0;
				}
				if(!isset($st['zm'.$i])) {
					$st['zm'.$i] = 0;
				}
				if(isset($st['s5'])) {		$st['pm'.$i] += $st['s5']*0.5;	}
				if(isset($st['m11a'])) {	$st['pm'.$i] += $st['m11a'];	}
				if(isset($st['aall'])) {	$st['a'.$i] += $st['aall'];		}
				if(isset($st['m2all'])) {	$st['mg'.$i] += $st['m2all'];	}
				if(isset($st['zm'])) {		$st['zm'.$i] += $st['zm'];		}
				if(isset($st['zma'])) {		$st['zm'.$i] += $st['zma'];		}
				if($i<=4)
				{					
					if(!isset($st['mib'.$i])) {
						$st['mib'.$i] = 0;
					}
					if(!isset($st['mab'.$i])) {
						$st['mab'.$i] = 0;
					}
					if(!isset($st['mg'.$i])) {
						$st['mg'.$i] = 0;
					}
					if(!isset($st['pm'.$i])) {
						$st['pm'.$i] = 0;
					}
					if(!isset($st['pa'.$i])) {
						$st['pa'.$i] = 0;
					}
					if(!isset($st['za'.$i])) {
						$st['za'.$i] = 0;
					}
					$st['mib'.$i] += 0;
					$st['mab'.$i] += 0;
					if(isset($st['mall'])) {	$st['mg'.$i] += $st['mall'];	}
					if(isset($st['m11'])) {		$st['pm'.$i] += $st['m11'];		}
					if(isset($st['m10'])) {		$st['pa'.$i] += $st['m10'];		}
					if(isset($st['za'])) {		$st['za'.$i] += $st['za'];		}
				}				
				$i++;
			}
			
			//Уязвимость оружие и магиям
			
			$i = 1;
			while( $i <= 7 ) {
				@$st['yzm'.$i] += @$st['yzma'];
				if( $i <= 4 ) {
					@$st['yzm'.$i] += @$st['yzm'];//стихийный урон только
					@$st['yza'.$i] += @$st['yza']; //урон оружия
				}
				//Отнимает от защиты от урона
				if( isset($st['yza'.$i]) && $i <= 4 ) {
					$st['za'.$i] = ($st['za'.$i]/100*(100+($st['yza'.$i])));
					if( $st['za'.$i] < 0 ) {
						$st['za'.$i] = 0;
					}
				}
				//Отнимает от защиты от магии
				if( isset($st['yzm'.$i]) ) {
					$st['zm'.$i] = ($st['zm'.$i]/100*(100+($st['yzm'.$i])));
					if( $st['zm'.$i] < 0 ) {
						$st['zm'.$i] = 0;
					}
				}				
				$i++;
			}
			
			$st['pa3'] += $st['s1']*1 + $st['s2']*0 + $st['s3']*0; //дробящий
			$st['pa2'] += $st['s1']*0.7 + $st['s2']*0.2 + $st['s3']*0.2; //рубящий
			$st['pa1'] += $st['s1']*0.6 + $st['s2']*0.4 + $st['s3']*0; //колющий
			$st['pa4'] += $st['s1']*0.06 + $st['s2']*0 + $st['s3']*0.4; //режущий
			
			
			
			if(isset($st['hpVinos']) && $st['hpVinos'] != 0) {
				$st['hpAll'] += round($st['hpVinos']*$st['s4']);
			}
			
			if(isset($st['mpVinos']) && $st['mpVinos'] != 0) {
				$st['mpAll'] += round($st['mpVinos']*$st['s6']);
			}
			
			if(isset($st['hpProc']) && $st['hpProc'] != 0) {
				$st['hpAll'] += round($st['hpAll']/100*$st['hpProc']);
			}
			
			if(isset($st['mpProc']) && $st['mpProc'] != 0) {
				$st['mpAll'] += round($st['mpAll']/100*$st['mpProc']);
			}
			
			//Реген. - 250 ед.
			/*if( $u['level'] > 7 ) {
				$st['speedhp'] -= 240;
				$st['speedmp'] -= 240;
			}*/
			
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
			
			$st['ozash'] = $oza;
			$st['ozmsh'] = $ozm;
			$st['weapon1'] = $hnd1;
			$st['weapon2'] = $hnd2;
			$st['sheld1']  = $sht1;
			$st['sv_']	  = $s_v;
			$st['sv_i']	 = $s_vi;
			$st['dom']	  = $dom;		
			$st['prsu']	 = $prsu;
			
			$st['x'] = $u['x'];
			$st['y'] = $u['y'];
			$st['s'] = $u['s'];
			
			//Собираем рейтинг
			/*$st['reting'] = 0;
			//
			$st['reting'] += $st['hpAll'];
			$st['reting'] += $st['mpAll'];
			//
			$st['reting'] += $st['m1'];
			$st['reting'] += $st['m2'];
			$st['reting'] += $st['m4'];
			$st['reting'] += $st['m5'];
			//
			$st['reting'] += $st['m6']*10;
			$st['reting'] += $st['m7']*10;
			$st['reting'] += $st['m8']*10;
			$st['reting'] += $st['m9']*10;
			//
			$st['reting'] += $st['a1']*7;
			$st['reting'] += $st['a2']*7;
			$st['reting'] += $st['a3']*7;
			$st['reting'] += $st['a4']*7;
			$st['reting'] += $st['a5']*7;
			$st['reting'] += $st['a6']*7;
			$st['reting'] += $st['a7']*7;
			//
			$st['reting'] += $st['mg1']*7;
			$st['reting'] += $st['mg2']*7;
			$st['reting'] += $st['mg3']*7;
			$st['reting'] += $st['mg4']*7;
			$st['reting'] += $st['mg5']*7;
			$st['reting'] += $st['mg6']*7;
			$st['reting'] += $st['mg7']*7;
			//
			$st['reting'] += $st['m3']*5;
			$i8 = 1;
			$st['reting'] += $st['za']*5;
			$st['reting'] += $st['zm']*5;
			//
			$st['reting'] += $st['s5']*5;
			$st['reting'] += $st['s6']*5;
			//
			while( $i8 < 8 ) {
				$st['reting'] += $st['pa'.$i]*5;
				$st['reting'] += $st['pm'.$i]*5;
				//
				$st['reting'] += $st['za'.$i]*10;
				$st['reting'] += $st['zm'.$i]*10;
				$i8++;
			}
			//
			$i8 = 1;
			while( $i8 < 5 ) {
				$st['reting'] += $st['mib'.$i];
				$st['reting'] += $st['mab'.$i];
				$i8++;
			}*/
			
			$st['reting'] = 1+ceil($st['reting']);
			$st['reting'] = 1+$st['irka'];
			$st['reting'] = 1+$st['prckr'] + $st['preckr']*7;
			
			//Бонус от вип аккаунтов
			if( $st['silver'] >= 1 ) {
				$st['speed_dungeon'] += 20;
				$st['speedhp'] += 50;
				$st['speedmp'] += 50;
			}
			if( $st['silver'] >= 2 ) {
				$st['exp'] += 50;
			}
			if( $st['silver'] >= 3 ) {
				
			}
			if( $st['silver'] >= 4 ) {
				$st['maxves'] += round($st['maxves']/2);
			}
			//Добавочный подьем для игроков
			$st['maxves'] += 500;
						
			if( $st['silver'] >= 5 ) {
				$st['exp'] += 50;
			}
			
			if( $u['admin'] > 0 ) {
				$st['speed_dungeon'] += 1000;
				$st['speedhp'] += 1000000000000000;
			}
			
			//Сохраняем рейтинг игрока
			$st['reting'] = floor($st['reting']);
			if(@$st['btl_cof'] != @$st['reting']) {
				$st['btl_cof'] = $st['reting'];
				mysql_query('UPDATE `stats` SET `btl_cof` = "'.$st['reting'].'" WHERE `id` = "'.$st['id'].'" LIMIT 1');
			}

			if($st['hpAll'] < 1) {
				$st['hpAll'] = 1;
			}
			
			if($st['mpAll'] < 0) {
				$st['mpAll'] = 0;
			}

			if($u['room'] == 411) {
				//Хоккей, эффект от шайбы -25% НР
				$shb = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u['id'].'" AND `item_id` = 4910 AND `delete` = 0 LIMIT 1'));
				if(isset($shb['id'])) {
					$st['hpAll'] = round($st['hpAll']/100*75);
				}
			}
		
			if( stristr($u['login'], '(зверь ') == true || (stristr($u['login'], 'Каменный страж') && $u['ip'] == '0') ) {
				$st['this_animal'] = 1;
			}else{
				$st['this_animal'] = 0;
			}
			
			$rt = array();
			if($i1==1)
			{
				$rt[0] = $st;
				$rt[1] = $st2; //родные статы
			}else{
				$rt = $st;
			}
			
			if( $btl_cache == true && $cache == false ) {
				$dataca = array(
					'st' => $st,
					'st2' => $st2
				);
				$dataca = json_encode($dataca);
				mysql_query('INSERT INTO `battle_cache` (`uid`,`battle`,`data`,`time`) VALUES ("'.$u['id'].'","'.$u['battle'].'","'.mysql_real_escape_string($dataca).'","'.time().'")');
			}
		}
		
		if( isset($cache) ) {
			if( $i1 == 1 ) {
				$rt = array( $cache['st'] , $cache['st2'] );
			}else{
				$rt = $cache['st'];
			}
		}
		
		return $rt;
	}	
	
	public function send($color,$room,$city,$from,$to,$text,$time,$type,$toChat,$spam,$sound,$new = 1,$typeTime = 0,$global = 0)
	{
		mysql_query("INSERT INTO `chat` (`global`,`typeTime`,`new`,`sound`,`color`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`spam`) VALUES
		('".$global."','".$typeTime."','".$new."','".$sound."','".$color."','".$city."','".$room."','".$from."','".$to."','".$text."','".$time."','".$type."','".$toChat."','".$spam."')");
		$msg_id = mysql_insert_id();
		return $msg_id;
	}
	
	//получаем уровень
	public function testLevel()
	{
		global $c;
		$rt = 0;
		if( $this->info['exp'] > $c['expstop'] ) {
			$this->info['exp'] = $c['expstop'];
			mysql_query('UPDATE `stats` SET `exp` = "'.$c['expstop'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
		}
		
		if( $this->info['exp_eco'] > 17500 ) {
			$this->info['exp_eco'] = 17500;
		}
		
		if(isset($this->stats['levels']['upLevel']))
		{
			$telvl = array(	98 => 120000000,97 => 52000000,96 => 50000000,95 => 48000000,94 => 46000000,93 => 45000000,92 => 44000000,91 => 42000000,90 => 40000000,89 => 38000000,88 => 36000000,87 => 35000000,86 => 34000000,85 => 32000000,84 => 30000000,83 => 20000000,82 => 19500000,81 => 19000000,80 => 18000000,79 => 17500000,78 => 17000000,77 => 16000000,76 => 15500000,75 => 14000000,74 => 13000000,73 => 10000000,72 => 9900000,71 => 9750000,70 => 9500000,69 => 9250000,68 => 9000000,67 => 8500000,66 => 7500000,65 => 6500000,64 => 6000000,63 => 3000000,62 => 2800000,61 => 2600000,60 => 2500000,59 => 2400000,58 => 2300000,57 => 2175000,56 => 2000000,55 => 1750000,54 => 1500000,53 => 300000,52 => 280000,51 => 260000,50 => 250000,49 => 225000,48 => 200000,47 => 175000,46 => 150000,45 => 75000,44 => 60000,43 => 30000,42 => 27000,41 => 23000,40 => 21000,39 => 19000,38 => 17000,37 => 15500,36 => 14000,35 => 12500,34 => 12000,33 => 11000,32 => 10000,31 => 9000,30 => 8000,29 => 7000,28 => 6000,27 => 5000,26 => 4600,25 => 4200,24 => 3800,23 => 3350,22 => 2900,21 => 2500,20 => 2200,19 => 2050,18 => 1850,17 => 1650,16 => 1450,15 => 1300,14 => 1100,13 => 950,12 => 830,11 => 670,10 => 530,9 => 410,8 => 350,7 => 280,6 => 215,5 => 160,4 => 110,3 => 75,2 => 45,1 => 20);
			if( $this->info['exp'] >= $telvl[$this->info['upLevel']] ) {					
				$lvl  = mysql_fetch_array(mysql_query('SELECT `upLevel`,`nextLevel`,`exp`,`money`,`money_bonus1`,`money_bonus2`,`ability`,`skills`,`nskills`,`sskills`,`expBtlMax`,`hpRegen`,`mpRegen`,`money2` FROM `levels` WHERE `upLevel`="'.$this->info['upLevel'].'" LIMIT 1'));			
				$lvln = mysql_fetch_array(mysql_query('SELECT `upLevel`,`nextLevel`,`exp`,`money`,`money_bonus1`,`money_bonus2`,`ability`,`skills`,`nskills`,`sskills`,`expBtlMax`,`hpRegen`,`mpRegen`,`money2` FROM `levels` WHERE `upLevel`="'.($lvl['upLevel']+1).'" LIMIT 1'));
				//Кристал вечности
				if($this->info['exp']>12499 && $this->info['level']<=5 && $c['infinity5level'] == true)
				{
					$itm = mysql_fetch_array(mysql_query('SELECT `id`,`item_id`,`1price`,`2price`,`3price`,`uid`,`use_text`,`data`,`inOdet`,`inShop`,`delete`,`iznosNOW`,`iznosMAX`,`gift`,`gtxt1`,`gtxt2`,`kolvo`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`timeOver`,`overType`,`secret_id`,`time_create`,`inGroup`,`dn_delete`,`inTransfer`,`post_delivery`,`lbtl_`,`bexp`,`so`,`blvl` FROM `items_users` WHERE `item_id` = "1204" AND `delete` = "0" AND `uid` = "'.$this->info['id'].'" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
					if(!isset($itm['id']) && $this->info['host_reg'] != 'noinfinity5' && $this->info['twink'] == 0)
					{
						$this->info['exp_eco'] += $this->info['exp']-12499;
						$this->info['exp'] = 12499;
						mysql_query('UPDATE `stats` SET `exp` = "12499",`exp_eco` = "'.$this->info['exp_eco'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						echo '<script>chat.sendMsg(["new","'.time().'","6","","'.$this->info['login'].'","Для перехода на 6-ой уровень требуется &quot;<b>Кристалл Вечности [6]</b>&quot;.","Black","1","1","0"]);</script>';
					}else{
						$this->info['exp'] += $this->info['exp_eco'];
						$this->info['exp_eco'] = 0;
						mysql_query('UPDATE `stats` SET `exp` = "'.$this->info['exp'].'",`exp_eco` = "0" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
						$text = 'Предмет &quot;<b>Кристалл Вечности [6]</b>&quot; был успешно использован.';
						echo '<script>chat.sendMsg(["new","'.time().'","6","","'.$this->info['login'].'","'.$text.'","Black","1","1","0"]);</script>';
						//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1',".$this->info['city']."','".$this->info['room']."','','".$this->info['login']."','".$text."','".time()."','6','0')");
					}
	
				}
				//****************
				$i = 0; $ult = 0;
				//mysql_query('LOCK TABLES users,stats,mults,bank,referal_bous,levels,chat WRITE');
				while($i!=1)
				{
					if($c['nolevel'] == true && $this->info['exp']>=$lvl['exp'] && isset($lvln['upLevel']))
					{
						$tlus = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `real` = 1 AND `level` = "'.$this->info['level'].'" LIMIT 1'));
						//берем апп или уровень, $lvln
						if($tlus[0] < $this->info['level']*5) {
							//Нельзя получать лвл/аппы пока не будет 100 персонажей текущего уровня
							$this->info['exp'] = $lvl['exp']-1;
							mysql_query('UPDATE `stats` SET `exp` = "'.$this->info['exp'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						}
					}
					if($this->info['exp']>=$lvl['exp'] && isset($lvln['upLevel']))
					{					
						if($lvl['nextLevel']>$this->info['level'])
						{
							//Выдаем бонусные предметы
							/*
							if($lvl['nextLevel']==8){
								$text = 'При переходе на 8 уровень Вы получаете предмет &quot;<b>Свиток барыги -Таба-</b>&quot;.';
								echo '<script>chat.sendMsg(["new","'.time().'","6","","'.$this->info['login'].'","'.$text.'","Black","1","1","0"]);</script>';
								$this->addItem(1190,$this->info['id'],'|sudba='.$this->info['login']);
							}
							*/
							
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
							}elseif($lvl['nextLevel']==12)
							{
								$a4 = 30;
							}
							
							$this->stats['s4'] += $a4;
							$sex1 = '';
							if($this->info['sex']==1)
							{
								$sex1 = 'ла';
							}
							
							$ult = 1;
	
							//Рефералы
							if($this->info['twink'] == 0 && (round($this->info['host_reg']) > 0 && ( $this->info['dieline'] == 0 || $this->info['dieline'] < $lvl['exp'] ))) {
								$mtest = mysql_fetch_array(mysql_query('SELECT `id`,`uid`,`uid2`,`ip` FROM `mults` WHERE (`uid` = "'.$this->info['id'].'" AND `uid2` = "'.((int)$this->info['host_reg']).'") OR (`uid2` = "'.$this->info['id'].'" AND `uid` = "'.((int)$this->info['host_reg']).'") LIMIT 1'));
								$rlog = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`catch` FROM `users` WHERE `id` = "'.((int)$this->info['host_reg']).'" LIMIT 1'));
								$rlogs = mysql_fetch_array(mysql_query('SELECT `id`,`ref_data` FROM `stats` WHERE `id` = "'.((int)$this->info['host_reg']).'" LIMIT 1'));
								
								if(!isset($mtest['id']) && isset($rlog['id']) && $this->info['activ'] == 0) {
									$rfs['data'] = explode('|',$rlogs['ref_data']);
									$bnk = $rfs['data'][0];
									if($bnk < 1) {
										$bnk = mysql_fetch_array(mysql_query('SELECT `id` FROM `bank` WHERE `uid` = "'.((int)$rlog['id']).'" AND `block` = "0" LIMIT 1'));
										$bnk = $bnk['id'];
									}
									$ekr = '0.00';
									$bn = mysql_fetch_array(mysql_query('SELECT `id`,`type`,`level`,`add_bank`,`add_money`,`finish_battle`,`online`,`onlyOne`,`add_crystals` FROM `referal_bous` WHERE `type` = 1 AND `level` = "'.$lvl['nextLevel'].'" LIMIT 1'));
									if(isset($bn['id']) && ($bn['add_bank'] > 0 || $bn['add_money'] > 0) ) {
										$ekr = $bn['add_bank'];
										$kr = $bn['add_money'];
										$up = mysql_query('UPDATE `bank` SET `money1` = `money1` + '.$kr.',`money2` = `money2` + '.$ekr.' WHERE `id` = "'.mysql_real_escape_string($bnk).'" LIMIT 1');
										if($up) {
											$this->send('',$this->info['room'],$this->info['city'],'',$rlog['login'],'Ваш реферал <b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$lvl['nextLevel'].'! На Ваш банковский счет №'.$bnk.' зачисленно '.$kr.' кр., зачисленно '.$ekr.' Екр.',-1,6,0,0,0,1);
											
											$rlog['catch'] += $bn['add_bank'];
											mysql_query('UPDATE `users` SET `catch` = "'.$rlog['catch'].'" WHERE `id` = "'.$rlog['id'].'" LIMIT 1');
											
										}else{
											$this->send('',$this->info['room'],$this->info['city'],'',$rlog['login'],'Ваш реферал <b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$lvl['nextLevel'].'! (Ошибка зачисления, обратитесь с Администрации проекта) На Ваш банковский счет №'.$bnk.' зачисленно '.$ekr.' кр.',-1,6,0,0,0,1);
										}
									}
									//
									mysql_query("UPDATE `users` SET `referals` = `referals` + 10 WHERE `id` = '".mysql_real_escape_string($rlog['id'])."' LIMIT 1");
									//
								}elseif(isset($rlog['id'])){
									$this->send('',$this->info['room'],$this->info['city'],'',$rlog['login'],'Ваш реферал <b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$lvl['nextLevel'].'! <small><font color=red>(Персонаж не активирован, либо у вас совпадают IP)</font></small>',-1,6,0,0,0,1);
								}
							}
							
							
							
							$tst = $this->lookStats($this->info['stats']);
							$tst['s4'] += $a4;
							$this->info['stats'] = $this->impStats($tst);
						}
						
						/*if( $this->info['twink'] == 0 ) { 
							if( $this->info['exp'] >= 300000 ) {
								$this->send('',$this->info['room'],$this->info['city'],'',$this->info['login'],'За достижение нового уровня/аппа, Вы получаете: <b>'.$lvl['money'].' кр.</b>',-1,6,0,0,0,1);
							}
						}*/
						
						$this->info['level'] = $lvl['nextLevel'];
						$this->stats['levels'] = $lvln;
						$this->info['ability'] += $lvl['ability'];
						$this->info['skills'] += $lvl['skills'];
						$this->info['sskills'] += $lvl['sskills'];
						$this->info['nskills'] += $lvl['nskills'];
						if( $this->info['twink'] == 0 ) {
							//if( $this->info['level'] < 8 ) {
								//Выдаем за аппы зубы 0-7 лвл
								//$this->info['money4'] = $lvl['money']+$this->info['money4'];
							//}else{
								$this->info['money'] = $lvl['money']+$this->info['money'];
							//}
						}
						$lvl  = $lvln;
						$lvln = mysql_fetch_array(mysql_query('SELECT `upLevel`,`nextLevel`,`exp`,`money`,`money_bonus1`,`money_bonus2`,`ability`,`skills`,`nskills`,`sskills`,`expBtlMax`,`hpRegen`,`mpRegen`,`money2` FROM `levels` WHERE `upLevel`="'.($lvl['upLevel']+1).'" LIMIT 1'));
						$this->info['upLevel'] += 1;
						$rt++;									
					}else{
						$i = 1;
					}							
				}
				if($ult == 1) {
					if( $this->info['level'] == 4 || $this->info['level'] == 8 ) {
						//Убрать задержки на пещеры
						mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$this->info['id'].'" AND `vars` LIKE "psh%"');
						$this->send('',$this->info['room'],$this->info['city'],'',$this->info['login'],'Вы достигли '.$this->info['level'].' уровня, все задержки на посещения пещер сняты.',-1,6,0,0,0,1);
					}
					/*if( $this->info['level'] > 0 && $this->info['level'] <= 5 ) {
						$this->info['money4'] += 100;
						mysql_query('UPDATE `users` SET `money4` = "'.$this->info['money4'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						$this->send('',$this->info['room'],$this->info['city'],'',$this->info['login'],'За достижение нового уровня вы получаете '.$this->zuby(100,1).'',-1,6,0,0,0,1);
					}*/
					//if( isset($itm['id']) || $lvl['nextLevel'] != 6 ) {
						//
						/*$txti  = 'Достиг';
						if( $this->info['sex'] == 1 ) {
							$txti .= 'ла';
						}
						$txti .= ' '.$this->info['level'].' уровня!<br>'.date('d.m.Y H:i').'';
						//
						mysql_query('INSERT INTO `users_ico` (
							`uid`,`time`,`text`,`img`,`type`,`x`,`see`
						) VALUES (
							"'.$this->info['id'].'","'.time().'","'.$txti.'","pod_lvl'.$this->info['level'].'.gif","2","1","1"
						)');*/
						//
						$this->send('',$this->info['room'],$this->info['city'],'','','<b>'.$this->info['login'].'</b> достиг'.$sex1.' уровня '.$this->info['level'].'!',time(),6,0,0,0,1,2);
					//}
				}
				//mysql_query('UNLOCK TABLES');
				if($rt > 0) {
					if( $this->info['level'] >= 10 ) {
						//$this->info['stopexp'] = 1; //Блокировка опыта
						$this->info['stopexp'] = 0;
					}else{
						$this->info['stopexp'] = 0;
					}
					$upd = mysql_query('UPDATE `users` SET `stopexp` = "'.$this->info['stopexp'].'" , `level` = "'.$this->info['level'].'",`money` = "'.$this->info['money'].'",`money4` = "'.$this->info['money4'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					$upd = mysql_query('UPDATE `users_twink` SET `stopexp` = "'.$this->info['stopexp'].'" WHERE `uid` = "'.$this->info['id'].'" LIMIT 1');
					if($upd)
					{
						mysql_query('UPDATE `stats` SET `ability` = "'.$this->info['ability'].'",`skills` = "'.$this->info['skills'].'",`nskills` = "'.$this->info['nskills'].'",`sskills` = "'.$this->info['sskills'].'",`stats` = "'.$this->info['stats'].'",`upLevel` = "'.$this->info['upLevel'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						$this->info['money'] = $this->r2($this->info['money']);
					}
					return 1;
				}
				//****************
			}
		}
		/*------------*/
		if($this->info['animal']>0)
		{
			//уровень зверя
			$a = mysql_fetch_array(mysql_query('SELECT `id`,`type`,`name`,`uid`,`delete`,`inBattle`,`eda`,`exp`,`obraz`,`stats`,`level`,`sex`,`levelUp`,`pet_in_cage`,`max_exp`,`priems`,`bonus` FROM `users_animal` WHERE `uid` = "'.$this->info['id'].'" AND `id` = "'.$this->info['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
			if(isset($a['id']) && $a['level'] < 10)
			{
				$ea = array(
					0=>0,
					1=>110,
					2=>410,
					3=>1300,
					4=>2500,
					5=>5000,
					6=>12500,
					7=>30000,
					8=>300000,
					9=>3000000,
					10=>10000000,
					11=>120000000
				);
				$mx = array(
					0=>25,
					1=>50,
					2=>100,
					3=>150,
					4=>300,
					5=>600,
					6=>900,
					7=>1500,
					8=>3000,
					9=>6000,
					10=>9000,
					11=>12000
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
							$a['stats'] = mysql_fetch_array(mysql_query('SELECT `id`,`type`,`exp`,`level`,`stats`,`bonus` FROM `levels_animal` WHERE `type` = "'.$a['type'].'" AND `level` = "'.$a['level'].'" LIMIT 1'));
							$a['stats'] = $a['stats']['stats'];
							$this->send('',$this->info['room'],$this->info['city'],'',$this->info['login'],'<b>'.$a['name'].'</b> достиг '.$a['level'].' уровня!',time(),6,0,0,0,1);
							mysql_query('UPDATE `users_animal` SET `stats` = "'.$a['stats'].'",`level`="'.$a['level'].'",`max_exp`="'.$a['max_exp'].'" WHERE `id` = "'.$a['id'].'" LIMIT 1');
						}
						$iz = -2;
					}
					if( $iz > 1000 ) {
						$iz = -2;
					}
					$iz++;
				}
			}
		}
		/*------------*/
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
			$u = mysql_fetch_array(mysql_query('SELECT `u`.`align`,`u`.`clan`,`u`.`battle`,`u`.`animal`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`country`,`u`.`country_reg`,`u`.`sex`,`u`.`obraz`,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id`="'.mysql_real_escape_string($uid).'" OR `u`.`login`="'.mysql_real_escape_string($uid).'" LIMIT 1'));
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
			//$btl = mysql_fetch_array(mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `id` = "'.$u['battle'].'" AND `team_win` = "-1" LIMIT 1'));
		}
		if($u['battle']==0 || (isset($btl['id']) && $btl['time_start']>$this->info['timereg']))
		{
			$sth = $u['minHP']; //Стандартное время восстановления в минутах HP
			$stm = $u['minMP']; //Стандартное время восстановления в минутах MP
			$sh = 0; //Скорость регенерации НР в 1 сек.
			$sm = 0; //Скорость регенерации MР в 1 сек.
			/*---Двужильный(Особенность)---*/
			if(@$st['os9']>0){
				if(@$st['os9']==5) {
					@$st['os9']=6;
				}
				$sth = floor($u['minHP']-($u['minHP']/100)*($st['os9']*5));
			}
			/*---Двужильный(Особенность)---*/
			/*---Здравомыслящий(Особенность)---*/
			if(@$st['os10']>0){
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
			if(!isset($st['speedhp'])) { $st['speedhp'] = 0; }
			if( (0.0001+$st['speedhp']+$st['levels']['hpRegen']) < 0.00001 ) {
				$sh += ($sh/100)*0.00001;
			}else{
				$sh += ($sh/100)*(0.0001+$st['speedhp']+$st['levels']['hpRegen']);
			}
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
			if(!isset($st['speedmp'])) { $st['speedmp'] = 0; }
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
	
	public function send_mime_mail($name_from, // имя отправителя
	 	 	 	 	    $email_from, // email отправителя
	 	 	 	 	    $name_to, // имя получателя
	 	 	 	 	    $email_to, // email получателя
	 	 	 	 	    $data_charset, // кодировка переданных данных
	 	 	 	 	    $send_charset, // кодировка письма
	 	 	 	 	    $subject, // тема письма
	 	 	 	 	    $body // текст письма
	 	 	 	 	    )
	   {
	  $to = $this->mime_header_encode($name_to, $data_charset, $send_charset)
					 . ' <' . $email_to . '>';
	  $subject = $this->mime_header_encode($subject, $data_charset, $send_charset);
	  $from =  $this->mime_header_encode($name_from, $data_charset, $send_charset)
						 .' <' . $email_from . '>';
	  if($data_charset != $send_charset) {
		$body = iconv($data_charset, $send_charset, $body);
	  } 
	  $headers = "From: $from\r\n";
	  $headers .= "Content-type: text/html; charset=$send_charset\r\n";
	
	  return mail($to, $subject, $body, $headers);
	}
	
	public function mime_header_encode($str, $data_charset, $send_charset) {
	  if($data_charset != $send_charset) {
		$str = iconv($data_charset, $send_charset, $str);
	  }
	  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
	}
	
	public function set_cl_item($id, $user, $cl) {
	  $item_ = mysql_fetch_array(mysql_query('SELECT `iu`.*, `im`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `iu`.`item_id` = `im`.`id` WHERE `iu`.`uid` = "'.$user['id'].'" AND `iu`.`delete` = 0 AND `iu`.`inOdet` = 0 AND `iu`.`inShop` = 0 AND `im`.`inslot` > 0 AND `iu`.`gift` = "" AND `iu`.`data` NOT LIKE "%frompisher=%" AND `iu`.`id` = "'.$id.'" LIMIT 1'));
	  $res = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.$cl.'" LIMIT 1'));
	  if(isset($item_['id'])) {
		 $po = $this->lookStats($item_['data']);
		 if(isset($po['toclan'])) {
		  $po['toclan1'] = explode('#', $po['toclan']);
		  $po['toclan1'] = $po['toclan1'][0];
		 }
		 if(isset($po['sudba']) && $po['sudba'] != '0') {
		   $r = '<font color="#FF0000"><b>Предмет связан с вами судьбой</b></font><br>';
		 } elseif(isset($po['toclan']) && $po['toclan1'] != $user['clan']) {
		   $r = '<font color="#FF0000"><b>Предмет и так пренадлежит клану...</b></font><br>';
		 } elseif($user['inTurnir'] > 0 || $user['inTurnirnew'] > 0) {
		   $r = '<font color="#FF0000"><b>Во время участия в турнире запрещено использовать клановое хранилище.</b></font><br>';
		 } else {
		   if((isset($po['sudba']) && $po['sudba'] != '0') || isset($po['icos']) || isset($po['frompisher']) || isset($po['fromlaba']) || ($item_['gift'] != '' && $item_['gift'] != 0) ) {
			 $r = 'Не удалось';
		   } elseif(!isset($po['toclan'])) {
			 $po['toclan'] = $user['clan'].'#'.$user['id'];
			 $item_['data'] = $this->impStats($po);
			 if(mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `uid` = "-21'.$user['clan'].'", `data` = "'.$item_['data'].'" WHERE `id` = "'.$id.'" LIMIT 1')) {
			  $r = '<font color="#FF0000"><b>Вы успешно пожертвовали предмет &quot;'.$item_['name'].'&quot; клану</b></font><br />';
			  $col = $this->itemsX(((int)$id));
			  $this->addDelo(2, $user['id'],'&quot;<font color="green">System.transfer.MONEY</font>&quot;: Предмет &quot;'.$item_['name'].'&quot; (#id : "'.$id.'") (x'.$col.') был пожертвован клану &quot;'.$res['name'].'&quot; ('.$res['id'].').', time(), $user['city'], 'System.transfer.clan', 0, 0);
			  mysql_query('INSERT INTO `clan_operations` (`clan`, `time`, `type`, `text`, `val`, `uid`) VALUES ("'.$res['id'].'", "'.time().'", "4", "'.$user['login'].'", "'.$item_['name'].' (x'.$col.') Ид : ['.$id.']", "'.$user['id'].'")');					
			 } else {
			  $r = '<font color="#FF0000"><b>Не удалось...</b></font><br>';
			}
		   } else {
			 if(mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `uid` = "-21'.$user['clan'].'" WHERE `id` = "'.$id.'" LIMIT 1')) {
			  $col = $this->itemsX(((int)$id));
			  mysql_query('INSERT INTO `clan_operations` (`clan`, `time`, `type`, `text`, `val`, `uid`) VALUES ("'.$res['id'].'", "'.time().'", "3", "'.$user['login'].'", "'.$item_['name'].'" (x'.$col.') Ид : ['.$id.'], "'.$user['id'].'")');
			  $r = '<font color="#FF0000"><b>Вы успешно вернули предмет &quot;'.$item_['name'].'&quot; в хранилище клана</b></font><br />';
			} else {
			  $r = '<font color="#FF0000"><b>Не удалось...</b></font><br>';
			}
		   }
		 }
	  } else {
		 $r = '<font color="#FF0000"><b>Предмет не найден.</b></font><br>';
	  }
	  return $r;
	}
	
	
	public function ungive_itm_cl($id, $user, $cl) {
	  $itm_ = mysql_fetch_array(mysql_query('SELECT `iu`.*, `im`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `iu`.`item_id` = `im`.`id` WHERE `iu`.`uid` >= 0 AND `iu`.`delete` = 0 AND `iu`.`id` = "'.$id.'" LIMIT 1'));
	  $res = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.$cl.'" LIMIT 1'));
	  $user_itm = mysql_fetch_array(mysql_query('SELECT `id`,`bot`,`clone` FROM `stats` WHERE `id` = "'.$itm_['uid'].'" LIMIT 1'));
	  if( $user_itm['bot'] > 0 || $user_itm['clone'] > 0 || !isset($user_itm['id']) ) {
		  $r = '<font color="#FF0000"><b>Вы не можете изьять данный предмет, он не принадлежит клану</b></font><br />';
	  }elseif(isset($itm_['id'])) {
		 if($user['inTurnir'] == 0 && $user['inTurnirnew'] == 0) {
		   if($itm_['inOdet'] != 0) { $o = ', `inOdet` = 0'; } else { $o = ''; }
		   $r = '<font color="#FF0000"><b>Вы успешно изъяли предмет &quot;'.$itm_['name'].'&quot;</b></font><br />';
		   $col = $this->itemsX(((int)$id));
		   mysql_query('INSERT INTO `clan_operations` (`clan`, `time`, `type`, `text`, `val`, `uid`) VALUES ("'.$res['id'].'", "'.time().'", "6", "'.$user['login'].'", "'.$itm_['name'].' (x'.$col.') Ид : ['.$id.'] | У персонажа : ['.$itm_['uid'].']", "'.$user['id'].'")');
		   mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `uid` = "-21'.$res['id'].'" '.$o.' WHERE `id` = "'.$id.'" LIMIT 1');
		 } else {
		   $r = '<font color="#FF0000"><b>Во время участия в турнире запрещено использовать клановое хранилище.</b></font><br />';
		 }
	  } else {
		 $r = '<font color="#FF0000"><b>Предмет не найден.</b></font><br />';
	  }
	  return $r;
	}
	
	public function take_itm_cl($id, $user, $cl) {
	  $itm_ = mysql_fetch_array(mysql_query('SELECT `iu`.*, `im`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `iu`.`item_id` = `im`.`id` WHERE `iu`.`uid` = "-21'.$user['clan'].'" AND `iu`.`delete` = 0 AND `iu`.`id` = "'.$id.'" LIMIT 1'));
	  $res = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.$cl.'" LIMIT 1'));
	  if(isset($itm_['id'])) {
		 $po = $this->lookStats($itm_['data']);
		 if(isset($po['toclan'])) {
		  $cls = explode('#', $po['toclan']);
		  $cls = $cls[1];
		 }
		 if($user['inTurnir'] == 0 && $user['inTurnirnew'] == 0) {
		   $col = $this->itemsX(((int)$id));
		   mysql_query('INSERT INTO `clan_operations` (`clan`, `time`, `type`, `text`, `val`, `uid`) VALUES ("'.$res['id'].'", "'.time().'", "5", "'.$user['login'].'", "'.$itm_['name'].' (x'.$col.') Ид : ['.$id.'] Хозяин : ['.$cls.']", "'.$user['id'].'")');
		   mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `uid` = "'.$user['id'].'" WHERE `id` = "'.$id.'" LIMIT 1');
		   $r = '<font color="#FF0000"><b>Вы успешно взяли предмет &quot;'.$itm_['name'].'&quot; из хранилища</b></font><br />';
		 } else {
		   $r = '<font color="#FF0000"><b>Во время участия в турнире запрещено использовать клановое хранилище.</b></font><br />';
		 }
	  } else {
		 $r = '<font color="#FF0000"><b>Предмет не найден.</b></font><br />'.$id; 
	  }
	  return $r;
	}
	
	public function rem_itm_cl($user, $cl, $type) {
	  $itms = mysql_query('SELECT * FROM `items_users` WHERE (`uid` = "-21'.$user['clan'].'" OR `data` LIKE "%toclan='.$user['clan'].'#%")');
	  while($pl = mysql_fetch_array($itms)) {
		 $po = $this->lookStats($pl['data']);
		 if(isset($po['toclan'])) {
		  $cls = explode('#', $po['toclan']);
		  $cls = $cls[1];
		 }
		 if($cls == $user['id']) {
		   if($pl['uid'] != $user['id']) {
			 if($pl['inOdet'] != 0) { mysql_query('UPDATE `items_users` SET `inOdet` = 0 WHERE `id` = "'.$pl['id'].'"'); }
		   }
		   unset($po['toclan']);
		   $pl['data'] = $this->impStats($po);
		   $col = $this->itemsX(((int)$pl['id']));
		   $it_n = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$pl['item_id'].'"'));
		   mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `uid` = "'.$user['id'].'", `data` = "'.$pl['data'].'" WHERE `id` = "'.$pl['id'].'"');
		   mysql_query('INSERT INTO `clan_operations` (`clan`, `time`, `type`, `text`, `val`, `uid`) VALUES ("'.$res['id'].'", "'.time().'", "'.$type.'", "'.$user['login'].'", "'.$it_n['name'].' (x'.$col.') Ид : ['.$pl['id'].']", "'.$user['id'].'")');
		 } elseif($cls != $user['id'] && $pl['uid'] == $user['id']) {
		   $col = $this->itemsX(((int)$pl['id']));
		   $it_n = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$pl['item_id'].'"'));
		   mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'", `uid` = "-21'.$user['clan'].'" WHERE `id` = "'.$pl['id'].'"');
		   mysql_query('INSERT INTO `clan_operations` (`clan`, `time`, `type`, `text`, `val`, `uid`) VALUES ("'.$res['id'].'", "'.time().'", "9", "'.$user['login'].'", "'.$it_n['name'].' (x'.$col.') Ид : ['.$pl['id'].']", "'.$user['id'].'")');
		 }
	  }
	}
	
	public function send_mail($to,$to_name,$from = 'support@xcombats.com',$name = '<b>Бойцовский Клуб</b> 2',$title,$text) {
		$this->send_mime_mail($name,
	 	 	    $from,
	 	 	    $to_name,
	 	 	    $to,
	 	 	    'CP1251',  // кодировка, в которой находятся передаваемые строки
	 	 	    'KOI8-R', // кодировка, в которой будет отправлено письмо
	 	 	    $title,
	 	 	    $text); // \r\n
	}
	
	public function roomInfo($id, $short=false) {
		if($short==true){
			$select = ' `id`, `name`, `code`, `city`, `timeGO`, `level`,`roomGo` ';
		} else {
			$select = ' * ';
		}
		$roomInfo = mysql_fetch_assoc(mysql_query('SELECT '.$select.' FROM `room` WHERE `id` = "'.$id.'" LIMIT 1'));
		if($roomInfo['roomGo']) $roomInfo['roomGo'] = explode(',', $roomInfo['roomGo']);
		if($roomInfo['level']) $roomInfo['level'] = explode('-', $roomInfo['level']);
		return $roomInfo;
	}
	
public function showAbils() {
  $r = '';
  $sp = mysql_fetch_array(mysql_query('SELECT * FROM `abils_user` WHERE `uid` = "'.$this->info['id'].'" LIMIT 1'));
  if(isset($sp['id'])) {
	$r .= '';
  } else {
	mysql_query('INSERT INTO `abils_user` (`uid`) VALUES ("'.$this->info['id'].'")');
  }
  return $r;
}
	
}


$u = user::start();
?>