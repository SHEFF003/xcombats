<?php
class GameDealerClass {
	//Конфигурации
	public $c = array(
		/* MySQL База данных */
			'db_name'		=>	'pay_operations', //Таблица в которую заносятся данные
			'db_host'		=>	'localhost',
			'db_user'		=>	'bk2connect',
			'db_pass'		=>	'6OE7LHS1',
			'db_base'		=>	'bk2d12base',
		/* Настройки платежей */
			'ip_list'		=>	array('213.133.99.53'), //Указывать через запятую (Разрешенные IP)
			'key'			=>	'2c03e2f961fdc111a1b0e35f63e00453', //gamedealer key 2c03e2f961fdc111a1b0e35f63e00453 HjLyfzBGfhjkM01
			'id'			=>	'20101' //ID проекта
	),
	$ip = '',
	$BACK = array(); //Результат который возвращаем в конце
	
	//Кодируем из ЮТФ-8 в Кирилицу
	public function in($text) {
		return iconv("UTF-8","cp1251",$text);
	}
	
	//Кодируем из Кирилицу в ЮТФ-8
	public function out($text) {
		return iconv("cp1251","UTF-8",$text);
	}
	
	//Добавляем данные в базу данных
	public function add($type,$value,$money) {
		mysql_query('INSERT INTO `'.$this->c['db_name'].'` (`time`,`type`,`ip`,`value`,`money`,`project`) VALUES ("'.time().'","'.mysql_real_escape_string($type).'","'.$_SERVER['HTTP_X_REAL_IP'].'","'.mysql_real_escape_string($value).'","'.mysql_real_escape_string($money).'","'.mysql_real_escape_string($this->id).'")');
	}
	
	//Подключаемся к базе данных
	public function connect_db() {
		$db = mysql_connect($this->c['db_host'],$this->c['db_user'],$this->c['db_pass']) or die('Ошибка подключения к MySQL серверу!');
		mysql_select_db($this->c['db_base'],$db) or die('Ошибка подключения к базе данных!');
		mysql_query('SET NAMES cp1251');
	}
	
	public function output($a,$v = NULL) {
		$r = '';		
		$i = 0;
		while($i < count($a)) {
			$rn = '';
			$tb = '';
			if($v != NULL) {
				$rn = "\r\n";
				$tb = "	";
			}
			$r .= $rn.'<'.$a[$i][0].'>';
			if(!is_array($a[$i][1])) {
				$rn = '';
				$tb = '';
				$r .= $rn.$tb.($this->out($a[$i][1]));
			}else{
				if($i > 0) {
					$r .= $rn;
				}
				$r .= $tb.($this->output($a[$i][1],1));
			}
			$r .= $rn.'</'.$a[$i][0].'>';
			$i++;
		}
		return $r;
	}
	
	//Генерируем XML-файл
	public function backInformation() {
		header('Content-Type: text/html/force-download');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo $this->output($this->BACK,1);
	}
	
	//Проверка существования персонажа
	public function test_accaunt($nick) {
		$r = false;
		$nick = mysql_fetch_array(mysql_query('SELECT `id` FROM `bank` WHERE `id` = "'.mysql_real_escape_string($nick).'" LIMIT 1'));
		if(isset($nick['id'])) {
			$r = true;
		}
		return $r;
	}
	
	//Получает счет в банке по логину
	public function getBank($nick) {
		$nick = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `login` = "'.mysql_real_escape_string($nick).'" LIMIT 1'));
		$nick = mysql_fetch_array(mysql_query('SELECT `id` FROM `bank` WHERE `uid` = "'.mysql_real_escape_string($nick['id']).'" LIMIT 1'));
		return $nick['id'];
	}
	
	//Поиск логина
	public function bank_user($nick) {
		$nick = mysql_fetch_array(mysql_query('SELECT `id`,`uid FROM `bank` WHERE `id` = "'.mysql_real_escape_string($nick).'" LIMIT 1'));
		$nick = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `login` = "'.mysql_real_escape_string($nick['uid']).'" LIMIT 1'));
		return $nick['login'];
	}
	
	//Начинаем обработку запросов
	public function start_session() {
		
		$this->ip = $_SERVER['HTTP_X_REAL_IP'];		
		
		//Подключаемся к БД
		$this->connect_db();
		
		//Получаем данные запроса
		//$xml = file_get_contents('php://input');		
		
		//Парсинг XML запроса
		if(function_exists('simplexml_load_string')) {
			$xml = simplexml_load_string($xml);
		}else{			
			$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Не удалось произвести обработку запроса'))));
			die($this->backInformation());
		}
		
		$this->id = $xml->projectid;
		
		if(!in_array($this->ip,$this->c['ip_list'])) {
			$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Нет доступа с данного IP'))));
			die($this->backInformation());
		}		
		
		//Обработка запросов
		if($xml->method == 'check_balance') {
			//<sign>MD5(method+MD5(gdKey))</sign>
			
			$sign = md5($xml->method.md5($this->c['key']));
			
			if($sign == $xml->sign) {
				//Баланс дилера
				$balance = 1000000;
				$this->BACK = array(array('gdanswer',array(array('status','1'),array('desc','Баланс дилера: '.$balance),array('balance',$balance))));
				$this->add('4','check:'.$xml->nick.':1'.$r,0);
			}
			
		}elseif($xml->method	== 'check') {
			/*
			nick - логин персонажа	<sign>MD5(nick+method+MD5(gdKey))</sign>	*/
			
			$sign = md5($xml->nick.$xml->method.md5($this->c['key']));
			
			if($sign == $xml->sign) {
				$xml->nick = $this->in($xml->nick);
				if($this->test_accaunt($xml->nick) == true) {
					//Персонаж найден и зачисляем ему игровую валюту
					$this->BACK = array(array('gdanswer',array(array('status','1'),array('desc','Банковский счет найден'))));
					$this->add('3','check:'.$xml->nick.':1'.$r,0);
				}else{
					//Персонаж не найден
					$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Платеж не обработан. Банковский счет не найден.'))));
					$this->add('-1','Персонаж не найден:pay:'.$xml->nick.':0',0);
				}
			}
		}elseif($xml->method == 'pay') {
			/* Проводим платеж 
			nick - логин аккаунта , projectid - id проекта , sign , amount - деньги , payid - id платежа	*/
			
			$sign = md5($xml->nick.$xml->projectid.$xml->amount.$xml->payid.$xml->method.md5($this->c['key']));
			
			if($sign == $xml->sign) {
				$xml->nick = $this->in($xml->nick);
				if($this->test_accaunt($xml->nick) == true) {
					//Персонаж найден и зачисляем ему игровую валюту
					$bank = $this->test_accaunt($xml->nick);
					if($bank > 0) {
						mysql_query('UPDATE `bank` SET `money2` = `money2` + '.mysql_real_escape_string($xml->amount).' WHERE `id` = "'.mysql_real_escape_string($xml->nick).'" LIMIT 1');
						$this->BACK = array(array('gdanswer',array(array('status','1'),array('desc','Платеж прошел успешно'),array('id',$this->c['id']))));
						$this->add('2','pay:'.$xml->nick.':'.$xml->projectid.':'.$xml->sign.':'.$xml->amount.':'.$xml->payid.':'.$bank['id'],$xml->amount);
						
						$user = mysql_fetch_array(mysql_query('SELECT `id`,`uid` FROM `bank` WHERE `id` = "'.mysql_real_escape_string($xml->nick).'" LIMIT 1'));
						$user = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`city`,`sex`,`room` FROM `users` WHERE `id` = "'.mysql_real_escape_string($user['uid']).'" LIMIT 1'));
						
						mysql_query('UPDATE `users` SET `catch` = `catch` + '.mysql_real_escape_string(floor($xml->amount)).' WHERE `id` = "'.mysql_real_escape_string($xml->nick).'" LIMIT 1');
						
						$r = '<span class=date>'.date('d.m.Y H:i').'</span> Алхимик <img src=http://img.xcombats.com/i/align/align50.gif width=12 height=15 /><u><b>Enchanter</b> / Автоматическая оплата</u> сообщает: ';
						
						if($user['sex'] == 1) {
							$r .= 'Уважаемая';
						}else{
							$r .= 'Уважаемый';
						}
						
						$r .= ' <b>'.$user['login'].'</b>, на Ваш банковский счет №'.$bank.' зачислено '.$xml->amount.' Ekr. Благодарим Вас за покупку!';
						
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$user['city']."','".$user['room']."','','".$user['login']."','".$r."','-1','5','0')");
						
					}else{
						$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','У пользователя отсутствует банк'),array('id',$this->c['id']))));
						$this->add('-1','У персонажа отсутствует банк:pay:'.$xml->nick.':'.$xml->projectid.':'.$xml->sign.':'.$xml->amount.':'.$xml->payid.':'.$bank['id'],$xml->amount);
					}
				}else{
					//Персонаж не найден
					$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Платеж не обработан. Персонаж не найден.'))));
					$this->add('-1','Персонаж не найден:pay:'.$xml->nick.':0',0);
				}	
			}else{
				//Ошибка сигнатуры
				$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Ошибка сигнатуры'))));
				$this->add('-1','Ошибка сигнатуры:pay:'.$xml->nick.':0',0);
			}
		}elseif($xml->method == 'check_login') {
			/* Проверка аккаунта
			nick - логин аккаунта , projectid - id проекта , sign	*/
			$sign = md5($xml->nick.$xml->method.md5($this->c['key']));
			
			if($sign == $xml->sign) {
				$xml->nick = $this->in($xml->nick);
				if($this->test_accaunt($xml->nick) == true) {
					//Персонаж найден
					$this->BACK = array(array('gdanswer',array(array('status','1'),array('desc','Счет найден'),array('addinfo',$this->bank_user($xml->nick)))));
					$this->add('1','check_login:'.$xml->nick.':1'.$r,0);
				}else{
					//Персонаж не найден
					$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Счет не найден'))));
					$this->add('-1','Персонаж не найден:check_login:'.$xml->nick.':0',0);
				}
			}else{
				//Ошибка сигнатуры
				$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Ошибка сигнатуры'))));
				$this->add('-1','Ошибка сигнатуры:pay:'.$xml->nick.':0',0);
			}
		}else{
			$this->BACK = array(array('gdanswer',array(array('status','-1'),array('desc','Неизвестный тип запроса'))));
			$this->add('-1','Неизвестный тип запроса:error_method:gamedealer',0);
		}
		
		//Заносим информацию
		/* Пример результата запроса
			$this->BACK = array(
				array('gdanswer',array(array('status',-100),array('desc','Описание запроса')))
			);
		*/
		
		//Возвращаем результат
		$this->backInformation();
	}
}

$pay = new GameDealerClass;
$pay->start_session();
?>