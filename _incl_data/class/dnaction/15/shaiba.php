<?
if( isset($s[1]) && $s[1] == '15/shaiba' ) {
	/*
		������: ������ ������, ����� �����
		"������� ����� ����� �������"  - 4443
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	
	$r = '����� � ���! ������� � � ������ ����������!';
	
	//������� �����
	mysql_query('DELETE FROM `dungeon_obj` WHERE `id` = "'.$obj['id'].'" LIMIT 1');
	
	//��������� ����� � ��������� ������
	$vad['itm'] = $u->addItem(4910,$u->info['id']);
	if($vad['itm'] > 0) {
		mysql_query('UPDATE `items_users` SET `gift` = "�����" WHERE `id` = "'.$vad['itm'].'" LIMIT 1');
	}
	$this->sys_chat('<b>'.$u->info['login'].'</b> ���������� �����!');
	
	unset($vad);
}
?>