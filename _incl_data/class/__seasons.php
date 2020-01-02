<?php

if(!defined('GAME')) {
	die();
}

class season {
	
	public static $yy = array(
		1  => array( 1 , '01' , 'Январь' , 'Январьский' ),
		2  => array( 2 , '02' , 'Февраль' , 'Февральский' ),
		3  => array( 3 , '03' , 'Март' , 'Мартовский' ),
		4  => array( 4 , '04' , 'Апрель' , 'Апрельский' ),
		5  => array( 5 , '05' , 'Май' , 'Майский' ),
		6  => array( 6 , '06' , 'Июнь' , 'Июньский' ),
		7  => array( 7 , '07' , 'Июль' , 'Июльский' ),
		8  => array( 8 , '08' , 'Август' , 'Августовский' ),
		9  => array( 9 , '09' , 'Сентябрь' , 'Сентябрьский' ),
		10 => array( 10 , '10' , 'Октябрь' , 'Октябрьский' ),
		11 => array( 11 , '11' , 'Ноябрь' , 'Ноябрьский' ),
		12 => array( 12 , '12' , 'Декабрь' , 'Декабрьский' ),
	);
	
	public static $date = array( ), $m = array( );
	
	public static function data( $val ) {
		self::$m = mysql_fetch_array(mysql_query('SELECT * FROM `sss_m` WHERE `id` = "'.mysql_real_escape_string((int)$val).'" LIMIT 1'));
		if( self::$m['s'] != self::$date['m'] ) {
			self::$m = array( );
		}
	}
	
}

?>