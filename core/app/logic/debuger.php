<?

namespace Logic;

use \Core\View as view;

class Debuger {
	
	/*
	@ Базовый метод начал генирации модуля
	@ Здесь происходит определение типа данных, а так-же
	@ проверка возможности просмотра данного модуля
	@ все поддключаемые классы должны быть НЕОБХОДИМЫМИ!
	*/
	public static function defaultAction() {
		
		//Подключаем пакеты
		\Core\User::connect();
		\Core\User::room();
		
		if (\Core\User::$data == false ) {
			//Нет доступа, персонаж не авторизирован или заблокирован
			echo 'Авторизируйтесь через <a href="/index.php">главную страницу</a>.';
		}elseif( stristr($_SERVER['HTTP_ACCEPT'],'application/json') == true ) {
			echo self::getJSON();
		}else{
			echo self::getHTML();
		}
	}
	
	/*
	@ Метод выводящий HTML-контент на сторону пользователя
	@ Через конкретный шаблонизатор
	*/
	public static function getHTML() {		
		//PC версия главной страницы
		//
		$skills = array();
		$pl = \Core\Database::query( 'SELECT * FROM `priems` WHERE `activ` = 1 ORDER BY `img` ASC', array(
			//
		), true , true );
		$i = 0;
		while( $i != -1 ) {
			if(!isset($pl[$i])) {
				$i = -2;
			}else{
				$skills .= ',[' . $pl[$i]['id'] . ',"' . $pl[$i]['img'] . '","' . $pl[$i]['name'] . '"]';
			}
			$i++;
		}
		$skills = ltrim($skills,',');
		//
		return view::generateTpl( 'debuger', array(
			'title'		=> COPY . ' :: Вопросы по игре и сдача багов',
			
			//Передаем данные пакетов
			'user'		=> \Core\User::$data,
			'stats'		=> \Core\User::$stats,
			'room'		=> \Core\User::$room,
			
			'OK'		=> OK,
			'copy'		=> COPY,
			'rights'	=> RIGHTS,
			
			'skills'	=> $skills,			
			
			'ver'		=> '0.0.1'
		) );
	}
	
	/*
	@ Метод выводящий JSON-контент на сторону пользователя
	@ Информация берется из переменной self::$JSON
	*/
	public static function getJSON() {		
		$r = array();
				
		return \Core\Utils::jsonencode( $r );
	}
	
}

?>