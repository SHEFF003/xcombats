<?php

if(!defined('GAME')) {
	die();
}

class season {
	
	public static $yy = array(
		1  => array( 1 , '01' , '������' , '����������' ),
		2  => array( 2 , '02' , '�������' , '�����������' ),
		3  => array( 3 , '03' , '����' , '����������' ),
		4  => array( 4 , '04' , '������' , '����������' ),
		5  => array( 5 , '05' , '���' , '�������' ),
		6  => array( 6 , '06' , '����' , '��������' ),
		7  => array( 7 , '07' , '����' , '��������' ),
		8  => array( 8 , '08' , '������' , '������������' ),
		9  => array( 9 , '09' , '��������' , '������������' ),
		10 => array( 10 , '10' , '�������' , '�����������' ),
		11 => array( 11 , '11' , '������' , '����������' ),
		12 => array( 12 , '12' , '�������' , '�����������' ),
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