<?
if( isset($s[1]) && $s[1] == '101/nakova2' ) {
	/*
		Наковальня 2
		* Телепортирует на необходимую клетку х 3 ,  у 42
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	
	if( $vad['go'] == true ) {
		mysql_query('UPDATE `stats` SET `x` = "3",`y` = "42",`s` = "3" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$r = 'Вы подошли к наковальне...';
		echo '<script>location.href="main.php"</script>';
	}
	unset($vad);
}
?>