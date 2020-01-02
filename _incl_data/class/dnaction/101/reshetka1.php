<?
if( isset($s[1]) && $s[1] == '101/reshetka1' ) {
	/*
		Решетка
		* Телепортирует на необходимую клетку
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => false
	);
	
	//Проверяем камни
	$vad['sp'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "1189" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
	if( isset($vad['sp']['id']) ) {
		// Удалена проверка на группировку предметов 14.10.2015
		// $vad['pl'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$vad['sp']['item_id'].'" LIMIT 1'));
		$vad['go'] = true;
	}
	if( $vad['go'] == true ) {
		mysql_query('UPDATE `stats` SET `x` = "4",`y` = "42",`s` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		//$u->deleteItem($vad['sp']['id'],$u->info['id'],1);
		echo '<script>location.href="main.php"</script>';
	}elseif( !isset($vad['sp']['id']) ) {
		$r = 'Для прохода требуется предмет &quot;Мерцающий ключ №3&quot;';
	}
	unset($vad);
}
?>