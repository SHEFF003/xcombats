<?
if( isset($s[1]) && $s[1] == '3/altar' ) {
	/*
		������
		* ����� �������� ���� �� 4 eff
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = '������ �� ���������...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		$rn = array(
    1 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("422","'.$u->info['id'].'","������������� ������: ��������","add_s2=5|nofastfinisheff=1","0","'.time().'") ' ,
    2 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("423","'.$u->info['id'].'","������������� ������: ����","add_s1=5|nofastfinisheff=1","0","'.time().'") ' ,
    3 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("424","'.$u->info['id'].'","������������� ������: �����������","add_speedhp=-50|nofastfinisheff=1","0","'.time().'") ' ,
    4 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("425","'.$u->info['id'].'","������ �����","add_yron_min=-25|add_yron_max=25|nofastfinisheff=1","0","'.time().'") ');
        $rn = $rn[rand(1,4)];
        mysql_query($rn);
		$r = '�� ��� �������� ��������!';
	}
	
	unset($vad);
}

?>