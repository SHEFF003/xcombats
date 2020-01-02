<?php

namespace Core;

class Chat {

	//Системное сообщение
	public static function send_system( $uid , $text ) {
		if( !is_string($uid) ) {
			$uid = \Core\Database::query( 'SELECT `login` FROM `users` WHERE `uid` = :uid ORDER BY `id` ASC LIMIT 1' , array(
				'uid' => $uid
			));	
			$uid = $uid['login'];
		}
		\Core\Database::query( 'INSERT INTO `chat` (`to`,`time`,`type`,`text`,`color`,`typeTime`,`new`) VALUES (
			:to , :time , :type , :text , :color , :typeTime , :new
		)', array(
			'to'	=> $uid,
			'time'	=> OK,
			'type'	=> 6,
			'text'	=> $text,
			'color'	=> 'Black',
			'typeTime'	=> 0,
			'new'	=> 1
		) );
	}
		
}

?>