<?
if( isset($s[1]) && $s[1] == '101/viboina' ) {
	/*
		������: �������
		* ����� ����� ����� , ������ , ������ �������
		* ����� ������ 100-1000 ��
		* ����� ������������ ������ 2 �������� �� �������
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	$vad['test2'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	$vad['test3'] = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_bad" LIMIT 1'));
	$vad['test4'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_bad_use" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	$vad['test5'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_bad_use" AND `uid` != "'.$u->info['id'].'" LIMIT 1'));
	
	if( !isset($vad['test3']['id']) ) {
		//���������� ������� ��� ���
		if( rand(0,100) < 50 ) {
			$vad['bad'] = 1;
		}else{
			$vad['bad'] = 0;
		}
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'_bad","'.$vad['bad'].'"
		)');
	}else{
		$vad['bad'] = $vad['test3']['vals'];
	}
	
	if( $vad['test2'][0] > 0 ) {
		//$r = '�� ��� �������� ����� ���...';
		$r = '�������, �� ����� �������� ������...';
		$vad['go'] = false;
	}elseif( $vad['test1'][0] > 1 || ( $vad['test4'] == 0 && $vad['test5'] >= 2 ) ) {
		$r = '�������, ���-�� ����� ������� ������ ���...';
		$vad['go'] = false;
	}
	
	if( $vad['bad'] == 1 && $vad['go'] == true ) {
		//�� ������� � �������
		$vad['test3'] = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_bad_use" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if( !isset($vad['test3']['id']) ) {
			//������� �� 50% �� , ���� �� ������� 2 ��.
			$vad['go'] = false;
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'_bad_use",""
			)');
			$r = '� ������� ���� ������� ������������� ����� �� ���������� ����������!';
			$vad['min_hp'] = round(1+$u->stats['hpNow']/2);
			$u->stats['hpNow'] -= $vad['min_hp'];
			if( $u->stats['hpNow'] < 0 ) {
				$u->stats['hpNow'] = 0;
			}
			if($u->info['sex'] == 0) {
				$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> ������ � ������� ����������� � &quot;'.$obj['name'].'&quot;. <b>-'.$vad['min_hp'].'</b> ['.floor($u->stats['hpNow']).'/'.round($u->stats['hpAll']).']';
			}else{
				$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> ������� � ������� ����������� � &quot;'.$obj['name'].'&quot;. <b>-'.$vad['min_hp'].'</b> ['.floor($u->stats['hpNow']).'/'.round($u->stats['hpAll']).']';
			}
			$this->sys_chat($vad['text']);
			$u->info['hpNow'] = $u->stats['hpNow'];
			mysql_query('UPDATE `stats` SET `regHP` = "'.time().'",`hpNow` = "'.$u->stats['hpNow'].'" WHERE `id` = "'.$u->stats['id'].'" LIMIT 1');
			//
			$this->testDie();
		}
	}
	
	if( $vad['go'] == true ) {
		//���������� �������, ������, ���� �����
		$vad['items'] = array(2,877,896);
		
		$vad['items'] = $vad['items'][rand(0,count($vad['items'])-1)];
		if( $vad['items'] != 0 ) {
			//����������� �������
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'",""
			)');
			if( $this->pickitem($obj,$vad['items'],$u->info['id']) ) {
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