<?
if( isset($s[1]) && $s[1] == 'vbig3' ) {
	/*
		������: �������� ��������� (4561)
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = '���-�� ������ &quot;'.$obj['name'].'&quot; �� ���...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		if( rand(0,100) > 25 ) {
			$r = '������� &quot;'.$obj['name'].'&quot; �� ������ �� ����������...';
		}else{
			$vad['items'] = array(4561);
			$vad['items'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($vad['items'][rand(0,count($vad['items'])-1)]).'" LIMIT 1'));
			if( !isset($vad['dn_delete'][$vad['items']['id']]) ) {
				$vad['dn_delete'][$vad['items']['id']] = false;
			}
			$r = '������� &quot;'.$obj['name'].'&quot; �� ���������� &quot;'.$vad['items']['name'].'&quot;';
			$this->pickitem($obj,$vad['items']['id'],0,'',$vad['dn_delete'][$vad['items']['id']]);
		}
	}
	
	unset($vad);
}
?>