<?
if( isset($s[1]) && $s[1] == '101/kotel' ) {
	/*
		������: �����
		* ����� ����� �������� ����
		* ����� ������ 100-1000 ��
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = '���-�� ���������� �����...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		if( rand(0,100) > 11 ) {
			//�������
			$r = '�� �������� ���������� ���� �� �����!';
			$vad['min_hp'] = round($u->stats['hpNow']);
			$u->stats['hpNow'] = 0;
			if($u->info['sex'] == 0) {
				$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> ������� ���������� ���� �� &quot;'.$obj['name'].'&quot;. <b>-'.$vad['min_hp'].'</b> ['.floor($u->stats['hpNow']).'/'.round($u->stats['hpAll']).']';
			}else{
				$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> �������� ���������� ���� �� &quot;'.$obj['name'].'&quot;. <b>-'.$vad['min_hp'].'</b> ['.floor($u->stats['hpNow']).'/'.round($u->stats['hpAll']).']';
			}
			$this->sys_chat($vad['text']);
			$u->info['hpNow'] = $u->stats['hpNow'];
			mysql_query('UPDATE `stats` SET `regHP` = "'.time().'",`hpNow` = "'.$u->stats['hpNow'].'" WHERE `id` = "'.$u->stats['id'].'" LIMIT 1');
			//
			$this->testDie();
		}else{
			//�������
			$this->pickitem($obj,4269,$u->info['id']);
			$r = '������� &quot;'.$obj['name'].'&quot; �� ���������� ������� &quot;�������� ����&quot;';
		}
	}
	
	unset($vad);
}
?>