<?
if( isset($s[1]) && $s[1] == '12/sunduk_12_5stage' ) {
	/*
		������: ������ ������, ����� �����
		"������ ����� ����"  - 4518
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" LIMIT 5'));
	$vad['test2'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	if( $vad['test2'][0] > 0 ) {
		$r = '�� ��� �������� &quot;'.$obj['name'].'&quot;...';
		$vad['go'] = false;
	}elseif( $vad['test1'][0] > 5 ) {
		$r = '���-�� ������� &quot;'.$obj['name'].'&quot; ������ ���...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		$vad['items'] = array(4518);
		
		$vad['items'] = $vad['items'][rand(0,count($vad['items'])-1)];
		if( $vad['items'] != 0 ) {
			# ����������� �������
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'",""
			)');
			if( !isset($vad['dn_delete'][$vad['items']]) ) {
				$vad['dn_delete'][$vad['items']] = false;
			}
			if( $this->pickitem($obj,$vad['items'],$u->info['id'],'', true) ) {
				$r = '�� ���������� ��������...';
			}else{
				$r = '���-�� ����� �� ���, �������� ������������...';
			}
		}else{
			$r = '�� �� ����� ������ ���������...';
		}
	}
	
	unset($vad);
}
?>