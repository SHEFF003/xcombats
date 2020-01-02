<?

namespace Logic;

use \Core\View as view;

class Admin {
	
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
		return view::generateTpl( 'admin', array(
			'title'		=> COPY . ' :: Панель админа',
			
			//Передаем данные пакетов
			'user'		=> \Core\User::$data,
			'stats'		=> \Core\User::$stats,
			'room'		=> \Core\User::$room,
			
			'OK'		=> OK,
			'copy'		=> COPY,
			'rights'	=> RIGHTS,
						
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