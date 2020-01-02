<?php

if( !defined('GAME') ) {
	die();
}

class botPriemLogic {	
	
	static $p = array();

	static function start( $i, $id ) {
		
		self::$p = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$id.'" LIMIT 1'));
		
		$test = self::testpriem( self::$p );
		
		if( isset(self::$p['id']) && $test == 0 ) {			
			//Используем прием под номером $id
				if( $id == 1	) {	self::priem1( $i );		}
			elseif( $id == 2	) {	self::priem2( $i );		}
			elseif( $id == 3	) {	self::priem3( $i );		}
			elseif( $id == 4	) {	self::priem4( $i );		}
			elseif( $id == 5	) {	self::priem5( $i );		}
			elseif( $id == 6	) {	self::priem6( $i );		}
			elseif( $id == 7	) {	self::priem7( $i );		}
			elseif( $id == 8	) {	self::priem5( $i );		}
			elseif( $id == 9	) {	self::priem6( $i );		}
			elseif( $id == 10	) {	self::priem7( $i );		}
			elseif( $id == 11	) {	self::priem11( $i );	}
			
			elseif( $id == 45	) {	self::priem45( $i );	}
			
			elseif( $id == 211	) {	self::priem211( $i );	}
			
			elseif( $id == 223	) {	self::priem223( $i );	}
			
			elseif( $id >= 175 && $id <= 179 ) { self::priem175( $i ); }
			
			//*********************************		
		}
		return true;
		
	}
	
	static function usePriem($id,$on) {
		
		//if(self::$p['id'] > 7 && self::$p['id'] < 11) {
			//botLogic::e(botLogic::$bot['login'].', использую прием &quot;'.self::$p['name'].'&quot; , battle: <a target=_blank href=http://xcombats.com/logs.php?log='.botLogic::$bot['battle'].'>Link</a>');
		//}
		
		$go_txt = '&usepriem='.$id;
		if( $on != '') {
			$on = str_replace(' ','%20',$on);
			$go_txt .= '&useon='.$on;
		}
		botLogic::inuser_go_btl( botLogic::$bot , $go_txt );
	}
	
	
		//Силовое поле
				static function priem175( $i ) {
					$su = true;					
					//Логика использования приема
					/*if(botLogic::$st['hpNow'] >= botLogic::$st['hpAll']) {
						$su = false;
					}elseif(botLogic::$st['hpNow'] < 5) {
						$su = false;
					}elseif(rand(0,100) >= 50) {
						$su = false;
					}*/
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
	
		//Прикрыться
				static function priem1( $i ) {
					$su = true;					
					//Логика использования приема
					if(botLogic::$st['hpNow'] >= botLogic::$st['hpAll']) {
						$su = false;
					}elseif(botLogic::$st['hpNow'] < 5) {
						$su = false;
					}elseif(rand(0,100) >= 50) {
						$su = false;
					}
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
		//Вломить
				static function priem2( $i ) {
					$su = true;					
					//Логика использования приема
					if(isset(botLogic::$pr[1]) && botLogic::$pr[1] < 1 && botLogic::$st['hpNow'] > 5 && rand(0,100) >= 50) {
						$su = false;
					}
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
		//Собрать зубы
				static function priem3( $i ) {
					$su = true;					
					//Логика использования приема					
					if(botLogic::$st['hpNow'] >= botLogic::$st['hpAll']) {
						$su = false;
					}elseif(botLogic::$st['hpNow'] < 10) {
						$su = false;
					}elseif(isset(botLogic::$pr[1]) && botLogic::$pr[1] < 1) {
						$su = false;
					}
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
	
		//Воля к победе
				static function priem6( $i ) {
					$su = true;					
					//Логика использования приема
					if(self::hp() > 32) {
						$su = false;
					}					
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
			
		//Танец ветра
				static function priem8( $i ) {
					$su = true;					
					//Логика использования приема		
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
		//Дикая удача
				static function priem9( $i ) {
					$su = true;					
					//Логика использования приема		
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
		//Предвиденье
				static function priem10( $i ) {
					$su = true;					
					//Логика использования приема		
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
		//Рывок
				static function priem223( $i ) {
					$su = true;					
					//Логика использования приема		
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
	
		//Удачный удар
				static function priem11( $i ) {
					$su = true;					
					//Логика использования приема
					if(isset(botLogic::$pr[6]) && botLogic::$st['tactic7'] > 0 && self::hp() < 66 && botLogic::$st['tactic1'] < 14) {
						$su = false;
					}
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
		//Сильный удар
				static function priem4( $i ) {
					$su = true;					
					//Логика использования приема
					if(isset(botLogic::$pr[6]) && botLogic::$st['tactic7'] > 0 && self::hp() < 66 && botLogic::$st['tactic1'] < 14) {
						$su = false;
					}elseif((isset(botLogic::$pr[11]) || isset(botLogic::$pr[6])) && rand(0,100) <= 75) {
						$su = false;
					}
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
	
		//Агрессивная защита
				static function priem211( $i ) {
					$su = true;					
					//Логика использования приема
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
		//Утереть пот
				static function priem5( $i ) {
					$su = true;					
					//Логика использования приема					
					if(self::hp() > 95) {
						$su = false;
					}
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
	
		//Абсолютная защита
				static function priem45( $i ) {
					$su = true;					
					//Логика использования приема
					
					if($su == true) {
						self::usePriem( $i );
					}		
				}
				
				
		//Активная защита
				static function priem7( $i ) {
					$su = true;					
					//Логика использования приема
					/*						Если есть абсолютная защита (агрессивную) и её возможно исопльзовать, а так-же НР менее 70%					*/
					if( ( (isset(botLogic::$pr[45]) && botLogic::$pr[45] < 1) || (isset(botLogic::$pr[211]) && botLogic::$pr[211] < 1) ) && self::hp() < 80 ) {
						$su = false;
					}
					if($su == true) {
						self::usePriem( $i );
					}		
				}
	
	
	
	
	static function hp() {
		$p = round((botLogic::$st['hpNow']/botLogic::$st['hpAll']*100),2);		
		return $p;
	}
	
	//Тест на возможность использования
	static function testpriem($pl) {
		global $u;
		$notr = 0;
		
		$tr = $u->lookStats($pl['tr']);
		$d2 = $u->lookStats($pl['date2']);
		
		$x = 1;
		while( $x <= 7 ) {
			if(botLogic::$bot['tactic'.$x] < $pl['tt'.$x] && $x!=7 && $pl['tt'.$x] > 0) {
				$notr++;
			}elseif($x==7) {
				if($pl['tt'.$x]>0 && botLogic::$bot['tactic'.$x]<=0) {
					$notr++;
				}
			}
			$x++;
		}
		
		if($pl['xuse']>0) {
			$xu = $u->testAction('`vars` = "use_priem_'.botLogic::$bot['battle'].'_'.botLogic::$bot['id'].'" AND `vals` = "'.$pl['id'].'" LIMIT '.$pl['xuse'].'',2);
			if($xu[0] >= $pl['xuse']) {
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
					if($tr['tr_'.$n] > botLogic::$bot['level'])
					{
						$notr++;
					}
				}elseif($tr['tr_'.$n] > botLogic::$st[$n])
				{
					$notr++;
				}
			}
			$x++;
		}
		
		if(isset($tr['tr_mpNow']))
		{
			if(botLogic::$st['mpNow'] < $tr['tr_mpNow'])
			{
				$notr++;
			}
		}
		
		if($pl['trUser']==1)
		{
			//требует чтобы пользователь с кем-то разменивался (при ожидании прием гаснит)
			$ga = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle_act` WHERE `battle` = "'.botLogic::$bot['battle'].'" AND `uid1` = "'.botLogic::$bot['id'].'" AND `uid2` = "'.botLogic::$bot['enemy'].'" LIMIT 1'));
			if(isset($ga['id']))
			{
				$notr++;
			}
		}	
		
		//Если прием уже использовали
		if(botLogic::$st['prsu'][$pl['id']]>0) {
			$notr++;
		}
		
		return $notr;
	}

}
?>