<?
if( isset($s[1]) && $s[1] == '15/vorota' ) {
	/*
		������: ������ ������, ����� �����
		"������� ����� ����� �������"  - 4443
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	$vad['you'] = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$u->info['id'].'" LIMIT 1'));
	$vad['itm'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = 4910 AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	if($u->info['x'] != $obj['x']) {
		$r = '�� ������ ������� ������ ����� ����� ��������, ���� ����� ������!';
	}elseif(($vad['you']['team'] == 2 && $obj['name'] == '������ �����') || ($vad['you']['team'] == 1 && $obj['name'] == '������ �������')) {
		$r = '�� ������ ������ ����� � ���� ������? :)';
	}elseif(isset($vad['itm']['id'])) {
		$r = '�� ������� ����� � '.$obj['name'].'! ';
		//��������� ����� ��� ���, 30% ��� �����
		if(rand(0,100) < 30) {
			$r = ' <B>�����O����������!</b> ����� ���������� �� ����� ����!';
			$this->sys_chat('<b>'.$u->info['login'].'</b> ����� <font color=red><b>���!!!</b></font>!  (����� ���������� �� �����)');
			mysql_query('UPDATE `katok_now` SET `win` = `win` + 1 WHERE `id` = "'.$vad['you']['id'].'" LIMIT 1');
		}else{
			$r = ' � ���������! ����� ���������� �� ����� ����!';
			$this->sys_chat('<b>'.$u->info['login'].'</b> ��������! (����� ���������� �� �����)');
		}
		//������� ����� � ������ �� ����� �����
		mysql_query('DELETE FROM `items_users` WHERE `id` = "'.$vad['itm']['id'].'" LIMIT 1');
		//
		mysql_query('INSERT INTO `dungeon_obj` (
			`name`,`dn`,`x`,`y`,`img`,`delete`,`action`,`for_dn`,
			`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`
		) VALUES (
			"�����","'.$u->info['dnow'].'","5","7","shaiba.png","0","fileact:15/shaiba","0",
			"0","120","220","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt1:69,rl1:-47,rt2:74,rl2:126,rt3:76,rl3:140,rt4:80,rl4:150}"
		)');
		//
	}else{
		$r = '��� ����� ��� ����� ������ ������, ������������� � �������� �!';
	}
	
	
	unset($vad);
}
?>