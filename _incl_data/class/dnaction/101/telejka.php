<?
if( isset($s[1]) && $s[1] == '101/telejka' ) {
	/*
		������: �������
		* ����� �������� ���� �� ���� ��������
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = '���-�� ������� &quot;'.$obj['name'].'&quot; �� ���...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		if( rand(0,100) < 80 ) {
			if( rand(0,100) < 51 ) {
				$r = '������� &quot;'.$obj['name'].'&quot; �� ���������� &quot;�������� �������&quot;';
				$this->pickitem($obj,895,$u->info['id']);
			}else{
				$r = '������� &quot;'.$obj['name'].'&quot; �� ���������� &quot;�������&quot;';
				$this->pickitem($obj,875,$u->info['id']);
			}
		}else{
			$r = '�� ������ �� ������ �����...';
		}
	}
	
	unset($vad);
}
?>