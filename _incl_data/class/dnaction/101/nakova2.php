<?
if( isset($s[1]) && $s[1] == '101/nakova2' ) {
	/*
		���������� 2
		* ������������� �� ����������� ������ � 3 ,  � 42
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	
	if( $vad['go'] == true ) {
		mysql_query('UPDATE `stats` SET `x` = "3",`y` = "42",`s` = "3" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$r = '�� ������� � ����������...';
		echo '<script>location.href="main.php"</script>';
	}
	unset($vad);
}
?>