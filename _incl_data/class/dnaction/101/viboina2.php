<?
if( isset($s[1]) && $s[1] == '101/viboina2' ) {
	/*
		�������
		* ������������� �� ����������� ������ � 4 ,  � 26
		* ��� ������� ��������� 1 ����� ������� - 4298
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => false
	);
	
	//��������� �����
	$vad['sp'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "4298" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
	if( isset($vad['sp']['id']) ) {
		// ������� �������� �� ����������� ��������� 14.10.2015
		// $vad['pl'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$vad['sp']['item_id'].'" LIMIT 1')); // ����� �������� "����� �������" � ���.. ����� �������... �������.
		$vad['go'] = true;
	}
	if( $vad['go'] == true ) {
		mysql_query('UPDATE `stats` SET `x` = "4",`y` = "26",`s` = "4" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		//$u->deleteItem($vad['sp']['id'],$u->info['id'],1);// ������ 14.10.2015
		$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'" WHERE `id`="'.$vad['sp']['id'].'" LIMIT 1');
		if($upd){
			$r = '�� ������������� ��� ������  &quot;����� �������&quot; �� ������ �������';
			echo '<script>location.href="main.php"</script>';
		} else {
			$r = '���-�� ����� �� ���, ���������� �����.';
		}
	}elseif( !isset($vad['sp']['id']) ) {
		$r = '��� ����������� ��������� &quot;����� �������&quot;';
	}
	unset($vad);
}
?>