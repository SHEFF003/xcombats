<?
if( isset($s[1]) && $s[1] == '12/door_01' ) {
	/*
		�������
		* ������������� �� ����������� ������
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => false
	);
	
	//��������� �����
	$vad['sp'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "4516" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
	if( isset($vad['sp']['id']) ) {
		if( $vad['sp']['inGroup'] > 0 ) {
			$r = '������� �� ������ ���������� � ������';
		}else{
			$vad['pl'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$vad['sp']['item_id'].'" LIMIT 1'));
			$vad['go'] = true;
		}
	}
	if($u->info['x'] == '-3' && $u->info['y'] == '64' && $vad['go'] == true ){ 
		mysql_query('UPDATE `stats` SET `x` = "-3",`y` = "63" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');  
		header('location: main.php');
	}elseif( $u->info['x'] == '-3' && $u->info['y'] == '63') {
		$r = '������� ��� �������';
	}elseif( !isset($vad['sp']['id']) ) {
		$r = '��� ������� ��������� ������� &quot;���� �� ������������&quot;';
	}
	unset($vad);
}
?>