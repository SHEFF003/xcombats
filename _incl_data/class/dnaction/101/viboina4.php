<?
if( isset($s[1]) && $s[1] == '101/viboina4' ) {
	/*
		�������
		* ������������� �� ����������� ������ � 4 ,  � 27
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	
	if( $vad['go'] == true ) {
		mysql_query('UPDATE `stats` SET `x` = "4",`y` = "27",`s` = "2" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$r = '�� ������� �� ������ ������� ������';
		echo '<script>location.href="main.php"</script>';
	}
	unset($vad);
}
?>