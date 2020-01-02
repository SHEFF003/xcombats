<?php

namespace Core;

class Route {
	
	public static $json_return = false, $device = 'PC', $device_type = 'PC', $device_id = 0;
	
	public static function begin() {
			
		if ( isset($_SERVER['HTTP_ACCEPT']) && in_array('application/json', explode(',', $_SERVER['HTTP_ACCEPT'])) ) {
			self::$json_return = true;
		}
	
		$url_data = parse_url($_SERVER['REQUEST_URI']);
		$uri = urldecode($url_data['path']);

		$urls = array(
			//Локации
			DP . '/comission' 								=> 'comission',
			DP . '/auction' 								=> 'auction',
			DP . '/debuger' 								=> 'debuger',
			DP . '/admin' 									=> 'admin'
		);
		
		$found_module = false;
		
		foreach ( $urls as $url => $handler ) {
			if ( preg_match("#^" . $url . "/*$#", $uri) ) {
					$class_name = "Logic\\$handler";
					if(class_exists($class_name)) {
						$controller = new $class_name;
						$controller->defaultAction();
						$found_module = true;
					}else{
						self::ErrorClass404($class_name);
					}
				break;		
			} else {
				continue;
			}
		}
		
		if ( !$found_module ) {
			self::ErrorPage404();	
		}
	}
	
	public static function redirect($url) {
		header('Location: ' . $url);		
	}
	
	public static function ErrorPage404() {
		die('Страница не найдена');
	}
	
	public static function ErrorClass404($name) {
		die('Расширение '. $name .' не найдено');
	}
}

?>