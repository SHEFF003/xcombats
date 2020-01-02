<?php

if( !defined('GAME') ) {
	die();
}

class botLogic {	
	
	public $bot = array( );
	
	//Получение информации о боте
	public function botInfo( $id ) {
		
		self::$bot = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if( !isset(self::$bot['id']) ) {
			self::$bot = 'stop';	
		}
	}
		
}
?>