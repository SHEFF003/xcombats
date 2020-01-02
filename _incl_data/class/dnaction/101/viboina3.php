<?
if( isset($s[1]) && $s[1] == '101/viboina3' ) {
	/*
		Выбоина
		* Телепортирует на необходимую клетку х 4 ,  у 25
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	
	if( $vad['go'] == true ) {
		mysql_query('UPDATE `stats` SET `x` = "4",`y` = "25",`s` = "2" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$r = 'Вы перешли на другую сторону завала';
		echo '<script>location.href="main.php"</script>';
	}
	unset($vad);
}
?>