<?php

namespace Core;
class Utils {

	/*
	@ Метод редиректа на другую страницу
	*/
	public function redirect( $url ) {
		header( 'location: ' . $url );
	}
	
	/*
	@ Метод разделения параметров
	@
	*/
	public static function lookStats($m) {
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

	/*
	@ Метод вывода целых чисел
	@ TYPE: 0 - любое число
	*/
	public function num( $val, $type ) {
		if( $type == 0 ) {
			$val = floor((int)$val);
		}
		return $val;
	}
	
	/*
	@ Метод определения пустое значение или нет
	@ TYPE: 0 - число, 1 - текст
	*/
	public function emptyVal( $val, $type ) {
		$r = true;
		if( !isset($val) ) {
			$r = false;
		}elseif( $type == 0 ) {
			if( $val == 0 ) {
				$r = false;
			}
		}elseif( $type == 1 ) {
			$val = str_replace( ' ', '', str_replace( '	', '', $val ) );			
			if( $val == '' ) {
				$r = false;
			}
		}
		return $r;
	}
	
	/*
	@ Метод фильтрации строки (для POST или GET)
	*/
	public function fs( $val ) {
		$val = htmlspecialchars( $val ) ;
		return $val;
	}
	
	/*
	@ Метод создания куки и удаления
	*/
	public function cookie( $name , $value = NULL , $time = NULL ) {
		if( $value == NULL ) {
			return $_COOKIE[$name];
		}elseif( $value != false ) {		
			if( $time == NULL ) {
				$time = 86400;
			}
			return setcookie( $name , $value , OK + $time , '/' );
		}else{
			return setcookie( $name , '' , OK - 86400 , '/' );
		}
	}
	
	/*
	@ Метод начала сессии
	*/
	public function ses_start() {
		if ( session_id() ) return true;
		else return session_start();
	}
	
	/*
	@ Методпроверки текста
	*/
	public function testVal( $val , $min , $max , $sym , $nosym , $nostart , $noend , $data ) {
		$r = true;
		if( mb_strlen($val,'UTF-8') < $min || mb_strlen($val,'UTF-8') > $max ) {
			$r = false;
		}else{
			//Допустимые символы
			if( $sym != false ) {
				$i = 0;
				$new_val = mb_strtolower($val,'UTF-8');
				while( $i < mb_strlen($val,'UTF-8') ) {
					$j = 0;
					$k = 0;
					$k2 = 0;
					while( $j < mb_strlen($sym,'UTF-8') ) {
						if( mb_strtolower($val[$i],'UTF-8') == mb_strtolower($sym[$j],'UTF-8') ) {
							$k++;
						}else{
							if( isset($data['noXsym']) ) {
								//Нельзя использовать более X символов подряд
								$l = 0;
								$notxt = '';
								while( $l < $data['noXsym'] ) {
									$notxt .= mb_strtolower($sym[$j],'UTF-8');
									$l++;
								}
								if( mb_strpos($new_val,$notxt,NULL,'UTF-8') !== false ) {
									$k2++;
								}
							}
						}
						$j++;
					}
					if( $k == 0 || $k2 > 0 ) {
						$i = mb_strlen($val,'UTF-8');
						$r = false;
					}
					$i++;
				}
			}
			//Не допустимые символы
			if( $nosym != false ) {
				$i = 0;
				$new_val = '';
				while( $i < count($nosym) ) {
					if( mb_strpos(mb_strtolower($val,'UTF-8'),mb_strtolower($nosym[$i],'UTF-8'),NULL,'UTF-8') !== false ) {
						$i = count($nosym);
						$r = false;
					}
					$i++;
				}
			}
			//не допустимое начало
			if( $nostart != false ) {
				$i = 0;
				$new_val = '';
				while( $i < count($nostart) ) {
					if( mb_substr( $val, 0, mb_strlen($nostart[$i],'UTF-8'),'UTF-8') == $nostart[$i] ) {
						$i = count($nostart);
						$r = false;
					}
					$i++;
				}
			}
			//не допустимый конец
			if( $noend != false ) {
				$i = 0;
				$new_val = '';
				while( $i < count($noend) ) {
					if( mb_substr( $val, ( mb_strlen($val,'UTF-8') - mb_strlen($noend[$i],'UTF-8') ) , 0 , 'UTF-8') == $noend[$i] ) {
						$i = count($noend);
						$r = false;
					}
					$i++;
				}
			}
		}
		return $r;
	}
	
	/*
	@ Метод "завершения" сессии
	*/
	public function ses_end() {
		if ( session_id() ) {
			// Если есть активная сессия, удаляем куки сессии,
			setcookie(session_name(), session_id(), time()-60*60*24);
			// и уничтожаем сессию
			session_unset();
			session_destroy();
		}
	}
	
	/*
	@ Метод определения типа строка, цифры, эл.почта
	*/
	public function takeType( $val ) {
		
		if( preg_match( "|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $val ) ) {
				//Эл.почта
				return 2;
		}else{
			preg_match_all( '([0-9])', $val, $matches );
			$res = implode( NULL, $matches[0] );
			if( mb_strlen( $val, 'UTF-8' ) > mb_strlen( $res, 'UTF-8' ) ) {
				//Строка
				return 1;
			}else{
				//Число
				return 3;
			}
		}
	}
	
	/*
	@ Метод проверки правильности паролей
	*/
	public function testPass( $psw1, $psw2 ) {
		if( $psw1 == $psw2 ) {
			return true;
		}else{
			return false;
		}
	}
	
	/*
	@ Метод генирации ключа авторизации
	*/
	public function createAuth( $par ) {
		if( !isset( $par['rand'] ) ) {
			$par['rand'] = rand(10000000,90000000);
		}
		$r = $par['rand'] . md5( $par['ip'] . '+' . $par['id'] . '+' . $par['pass'] . '+' . $par['rand'] );
		return $r;
	}
	
	/*
	@ Метод проверки ключа авторизации
	*/
	public function testAuth( $auth, $par ) {
		$par['rand'] = substr( $auth, 0, 8 );
		if( $auth == self::createAuth( $par ) ) {
			return true;
		}else{
			return false;
		}
	}
	
	/*
	@ Метод вывода JSON данных
	*/
	public function JSON_Headers() {
		header('Expires: Mon, 26 Jul 1970 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Content-Type: application/json; charset=utf-8');
		return true;
	}
	
	/*
	@ Метод приобразования обьекта в JSON
	*/
	public function jsonencode( $val ) {
		array_walk_recursive( $val, function( &$value, $key ) {
		   $value = iconv( "CP1251", "UTF-8", $value );
		});
		return json_encode( $val );
		//return json_encode( $val );
		//return self::json_fix_cyr( json_encode( $val ) );
	}
	
	/*
	@ Метод приобразования JSON в обьект
	*/
	public function jsondecode( $val ) {
		return json_decode( $val );
	}
		
	/*
	@ Метод фикса кириллических символов
	*/
	public function json_fix_cyr($json_str) { 
		$cyr_chars = array ( 
			'\u0430' => 'а', '\u0410' => 'А', 
			'\u0431' => 'б', '\u0411' => 'Б', 
			'\u0432' => 'в', '\u0412' => 'В', 
			'\u0433' => 'г', '\u0413' => 'Г', 
			'\u0434' => 'д', '\u0414' => 'Д', 
			'\u0435' => 'е', '\u0415' => 'Е', 
			'\u0451' => 'ё', '\u0401' => 'Ё', 
			'\u0436' => 'ж', '\u0416' => 'Ж', 
			'\u0437' => 'з', '\u0417' => 'З', 
			'\u0438' => 'и', '\u0418' => 'И', 
			'\u0439' => 'й', '\u0419' => 'Й', 
			'\u043a' => 'к', '\u041a' => 'К', 
			'\u043b' => 'л', '\u041b' => 'Л', 
			'\u043c' => 'м', '\u041c' => 'М', 
			'\u043d' => 'н', '\u041d' => 'Н', 
			'\u043e' => 'о', '\u041e' => 'О', 
			'\u043f' => 'п', '\u041f' => 'П', 
			'\u0440' => 'р', '\u0420' => 'Р', 
			'\u0441' => 'с', '\u0421' => 'С', 
			'\u0442' => 'т', '\u0422' => 'Т', 
			'\u0443' => 'у', '\u0423' => 'У', 
			'\u0444' => 'ф', '\u0424' => 'Ф', 
			'\u0445' => 'х', '\u0425' => 'Х', 
			'\u0446' => 'ц', '\u0426' => 'Ц', 
			'\u0447' => 'ч', '\u0427' => 'Ч', 
			'\u0448' => 'ш', '\u0428' => 'Ш', 
			'\u0449' => 'щ', '\u0429' => 'Щ', 
			'\u044a' => 'ъ', '\u042a' => 'Ъ', 
			'\u044b' => 'ы', '\u042b' => 'Ы', 
			'\u044c' => 'ь', '\u042c' => 'Ь', 
			'\u044d' => 'э', '\u042d' => 'Э', 
			'\u044e' => 'ю', '\u042e' => 'Ю', 
			'\u044f' => 'я', '\u042f' => 'Я', 
			
			'\r' => '', 
			'\n' => '<br />', 
			'\t' => '' 
		);
		foreach ($cyr_chars as $cyr_char_key => $cyr_char) { 
			$json_str = str_replace($cyr_char_key, $cyr_char, $json_str); 
		} 
		return $json_str; 
	}
	
	public static function timeOut($ttm)
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
	
}

?>