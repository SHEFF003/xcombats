<?
if( isset($s[1]) && $s[1] == '12/fontan3' ) {
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => false
	);

	// ��������� �������� �������� �� ������ 2 ������� �� ������. 
	
		$vad['use_fontan'] = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `vars` = "use_fontan" AND `vals` = "3" LIMIT 1')); // �������� �� ������������� �����. 
		if(!isset($vad['use_fontan']['id'])) {
				$vad['all_uses'] = mysql_num_rows(mysql_query('SELECT * FROM `dungeon_actions` WHERE  `dn` = "'.$u->info['dnow'].'" AND `vars` = "use_fontan" AND `vals` = "3" LIMIT 5'));
				if($vad['all_uses']>=2){
						$r = '������ �� ��������, ���-�� ������� ����� ������.';
				} else {
						$vad['kill_monsters'] = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE ((`id_bot` = "370" && `x` = "-2" && `y` = "31") OR (`id_bot` = "374" && `x` = "-2" && `y` = "31") OR (`id_bot` = "375" && `x` = "-3" && `y` = "32") OR (`id_bot` = "375" && `x` = "-3" && `y` = "32") OR (`id_bot` = "373" && `x` = "-1" && `y` = "32") OR (`id_bot` = "373" && `x` = "-3" && `y` = "32") OR (`id_bot` = "373" && `x` = "-3" && `y` = "31")) AND `delete` = "0" AND `dn` = "'.$u->info['dnow'].'" AND `for_dn` = "0" LIMIT 10')); //��������� ����� �� ������ ��������
						if( !isset($vad['kill_monsters']['0']['id2']) ) {
							$vad['bt'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "2" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
							if( isset($vad['bt']['id']) ) {
								if( $vad['bt']['inGroup'] > 0 ) {
									$r = '������� �� ������ ���������� � ������';
								}else{
									// �������� ����� 
									$vad['gems']['query'] = mysql_query('SELECT `id`,`inGroup` FROM `items_users` WHERE (`item_id` = "908" OR `item_id` = "906" OR `item_id` = "907" OR `item_id` = "881" OR `item_id` = "878" OR `item_id` = "888") AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 99'); 
									// �������� ��������� ������ ��...
									$vad['gems']['query'] = mysql_result($vad['gems']['query'], (rand(0, (mysql_num_rows($vad['gems']['query'])-1))),0); 
									 
									if( $vad['gems']['query'] != "" ) {
										$vad['go'] = true;
									} else {
										$r = '���-�� ����� �� ���, ��������� ����������� ������...';
									}
								}
							} else {
								$r = '� ��� ��� ������ �������.';
							}
						} else {
							$r = '�� ������� ��� ����� ��� ������ ��������?';
						}
				}
		} else {
			if($u->info['sex']==1)$a='�'; else $a='';
			$r = '��� �������, ��� ����� � ��� ���'.$a.'..';
		}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`uid`,`dn`,`x`,`y`,`time`,`vars`,`vals`) VALUES ( "'.$u->info['id'].'","'.$u->info['dnow'].'","'.$u->info['x'].'","'.$u->info['y'].'","'.time().'", "use_fontan","3" )'); // ������ ���, ��� � ������ � ���!
		$r = '������� ������ ������� � ������ �� ��������� �.';
		
		$u->deleteItem(intval($vad['gems']['query']),$u->info['id'],1); // ������� ������.
		$u->deleteItem(intval($vad['bt']['id']),$u->info['id'],1); // ������� ������ ������� = 2.
		$u->addItem(round(1188),$u->info['id'],'|musor=2|noremont=1|nosale=1',12, 3); // ������� ����� ��������� 1188
	}
	unset($vad);
}
?>