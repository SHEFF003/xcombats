<?
if( isset($s[1]) && $s[1] == '12/fontan2' ) {
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => false
	);

	// ��������� �������� �� �������� �� ������ 4 ������� �� ������.
	
		$vad['use_fontan'] = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `vars` = "use_fontan" AND `vals` = "2" LIMIT 1')); // �������� �� ������������� �����.
		if(!isset($vad['use_fontan']['id'])) {
				$vad['all_uses'] = mysql_num_rows(mysql_query('SELECT * FROM `dungeon_actions` WHERE  `dn` = "'.$u->info['dnow'].'" AND `vars` = "use_fontan" AND `vals` = "2" LIMIT 5'));
				if($vad['all_uses']>=4){
						$r = '������ �� ��������, ���-�� ������� ����� ������.';
				} else {
						$vad['kill_dk'] = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `id_bot` = "123" AND `dn` = "'.$u->info['dnow'].'" AND `for_dn` = "0" LIMIT 1')); //��������� ����� �� ������� ����
						if( isset($vad['kill_dk']['id2']) ) {
								if( $vad['kill_dk']['delete'] > 0 ) {
										$vad['bt'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "2" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
										if( isset($vad['bt']['id']) ) { 
											$vad['go'] = true;
										} else {
											$r = '� ��� ��� ������ �������.';
										}
								} else {
										$r = '�� �� ������ ��������������� ��������, ���� ������� ���� ����.';
								}
						} else {
								$r = '�� ������� ��� ������� ���� ������? ����� ��� �� ��������..';
						}
				}
		} else {
				if($u->info['sex']==1)$a='�'; else $a='';
				$r = '��� �������, ��� ����� � ��� ���'.$a.'..';
		}
		if( $vad['go'] == true ) {
				mysql_query('INSERT INTO `dungeon_actions` (`uid`,`dn`,`x`,`y`,`time`,`vars`,`vals`) VALUES ( "'.$u->info['id'].'","'.$u->info['dnow'].'","'.$u->info['x'].'","'.$u->info['y'].'","'.time().'", "use_fontan","2" )'); // ������ ���, ��� � ������ � ���!
				$r = '������� ������ ������� � ������ �� ��������� �.';
				$u->deleteItem(intval($vad['bt']['id']),$u->info['id'],1); // ������� ������ ������� = 2.
				$u->addItem(round(1186),$u->info['id'],'|musor=2|noremont=1|nosale=1',12, 3); // ������� ����� ��������� 1186
		}
		unset($vad);
}
?>